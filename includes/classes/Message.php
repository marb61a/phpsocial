<?php
    class Message{
        // MySQL connection variable
        private $con;
        
        // User object for user that is logged in
        private $user_obj;
        
        // Constructor
        public function __construct($con, $userLoggedIn){
            $this->con = $con;
            $this->user_obj = new User($con, $userLoggedIn);
        }
        
        public function getUnreadNumber(){
            // Username of logged in user
            $userLoggedIn = $this->user_obj->getUserName();
            
            $query = $this->con->query("SELECT id FROM messages WHERE viewed='no' AND user_to='$userLoggedIn'");
            return $query->num_rows;
        }
        
        // This function returns the most recent user that userLoggedIn interacted with
        public function getMostRecentUser(){
            $userLoggedIn = $this->user_obj->getUserName();
            
            // Get the last message interaction involving userLoggedIn
            $get_recent_query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn'
                                                OR user_from='$userLoggedIn' ORDER BY ID DESC LIMIT 1");
            
            // If the user has not sent or received any messages yet
            if(mysqli_num_rows($get_recent_query) == 0)
                return false;
            
            $row = mysqli_fetch_assoc($get_recent_query);
            
            // Get the usernames of the interactions
            $user_to = $row['user_to'];
            $user_from = $row['user_from'];
            
            //Return username which is not the user logged in
            if($user_to != $userLoggedIn){
                return $user_to;
            } else {
                return $user_from;
            }
            
        }
        
        // Get meassages involving user logged in and a user that is passed in as a parameter
        public function getMessages($other_user){
            $userLoggedIn = $this->user_obj->getUserName();
            
            // Holds all data to be returned
            $data = "";
            
            // Set opened to yes for all messages for that user
            $set_viewed = $this->con->query("UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$other_user'");
            
            $get_messages_query = mysqli_query($this->con, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$other_user')
                OR (user_from='$userLoggedIn' AND user_to='$other_user') ORDER BY id ASC");
            
            while($row = mysqli_fetch_assoc($get_messages_query)){
                $user_to = $row['user_to'];
    			$user_from = $row['user_from'];
    			$body = $row['body'];
    			
    			// If the user is logged in the div will be green and put on the left otherwise it will be blue
    			$div_top = ($user_to == $userLoggedIn) ? "<div class='message' id='green'>" : "<div class='message' id='blue'>";
    			
    			// Concatenate with the message body
    			$data = $data . $div_top . $body . "</div><br><br>";
            }
            
                return $data;
        }
        
        // Sends the message
        public function sendMessage($user_to, $body, $date){
            if($body != ""){
                $userLoggedIn = $this->user_obj->getUsername();
                
                $send_message_query = mysqli_query($this->con, "INSERT INTO messages VALUES 
                    ('', '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
            }
        }
        
        // Get recent conversations in order, this is not for drop down and there is no infinite scroll
        public function getConvos(){
            $userLoggedIn = $this->user_obj->getUsername();
            
            // A string to hold the data that will be returned
            $return_string = "";
            
            // An array for usernames of conversations
            $convos = array();
            
            $get_convos_query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' 
            OR user_from='$userLoggedIn' ORDER BY id DESC");
            
            while($row = mysqli_fetch_assoc($get_convos_query)){
                //Check to see if the user_logged_in sent or received the last message
                $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];
                
                // If the username is not already in the array then push it in
                if(!in_array($user_to_push, $convos))
                    array_push($convos, $user_to_push);
            }
            
            // An array of usernames that user_logged_in has conversed with
            foreach($convos as $username){
                //User object for user found
                $user_found_obj = new User($this->con, $username);
                
                // Get the latest message between user_logged_in and user_found
                $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);
                
                // If the message body is greater than 11 then add '...' 
                $dots = strlen($latest_message_details[1]) >= 12 ? "....":"";
                
                // Split the message at 13 characters
                $split - str_split($latest_message_details[1], 12);
                
                $split = $split[0].$dots;
                $return_string .= 
                    "<a href='messages.php?u=$username'>
                        <div class='user_found_messages'>
							<img src='".$user_found_obj->getProfilePic()."' style='border-radius: 5px; margin-right: 5px;'>
								".$user_found_obj->getFirstAndLastName()." 
							<span class='timestamp_smaller' id='grey' >".$latest_message_details[2]."</span>
							<p id='grey' style='margin: 0;'>".$latest_message_details[0].$split." </p>
						</div>
					</a>";
            }
            
            return Sreturn_string;
        }
        
        // Gets recent conversations in order, this time for drop down and includes infinite scroll
        public function getConvosDropdown($data, $limit){
            // The page number passed as a parameter
            $page = $data['page'];
            
            // The username for user logged in
            $userLoggedIn = $this->user_obj->getUsername();
            
            // A string to hold data that will be returned
            $return_string = "";
            
            // An array for usernames of conversations
            $convos = array();
            
            if($page ==1 ){
                // Start at the first post
                $start = 0;
            } else {
                // Start where the last loaded posts left off
                $start = ($page - 1) * $limit;
            }
            
            //Set viewed to yes for all messages for that user.
            $set_viewed = $this->con->query("UPDATE messages SET viewed='yes' WHERE user_to='$userLoggedIn'");
            
            $get_convos_query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' 
                OR user_from='$userLoggedIn' ORDER BY id DESC");
            
            while($row = mysqli_fetch_assoc($get_convos_query)){
                // Check if user_logged_in sent or received last message
                $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];
                
                // If the username is not alread in an array put it in
                if(!in_array($user_to_push, $convos))
				    array_push($convos, $user_to_push);
            }
            
            // The number of messages checked (not posted necessarily)
            $num_iterations = 0;
            
            // The number of messages posted
            $count = 1;
            
            // The array of usernames that user_logged_in has conversed with
            foreach ($convos as $username){
                if($num_iterations++ < $start)
        		    continue;
        		
        		// Once 5 notifications have been loaded then stop
        		
            }
        }
    }
?>


