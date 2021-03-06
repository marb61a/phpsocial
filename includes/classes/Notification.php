<?php
    class Notification {
        // MySQL connection variable
        private $con;
        
        // User object for user that is logged in
        private $user_obj;
        
        // Constructor
        public function __construct($con, $user){
            $this->con = $con;
            $this->user_obj = new User($con, $userLoggedIn);
        }      
        
        // Returns the number of unread notifications
        public function getUnreadNumber(){
            $userLoggedIn = $this->user_obj->getUserName();
            $query = $this->con->query("SELECT id FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
            return $query->num_rows;
        }
        
        public function getNotifications($data, $limit){
            // The page number is passed as a parameter
            $page = $data['page'];
            $userLoggedIn = $this->user_obj->getUserName();
            $str = "";
            
            if($page == 1){
                // Start at the first post
                $start = 0;
            } else {
                // Starts where the last loaded posts were
                $start = ($page - 1) * $limit;
            }
            
            //Set viewed to yes for all notifications for that user.
            $set_viewed = $this->con->query("UPDATE notifications SET viewed='yes' WHERE user_to='$userLoggedIn'");
            $data = $this->con->query("SELECT * FROM notifications WHERE user_to='$userLoggedIn' ORDER BY id DESC");
            
            // If there are no notifications
            if($data->num_rows == 0){
                echo "You don't have any notifications to load.";
			    return;
            }
            
            // The number of results checked
            $num_iterations = 0;
            
            // The number of results posted
            $count = 1;
            
            while($row = $data->fetch_array(MYSQLI_ASSOC)){
                // If the start position from last loads has not been reached yet
                if($num_iterations++ < $start)
                    continue;
                
                // Once 5 notifications have been loaded, stop
                if($count > $limit){
                    break;
                } else {
                    $count++;
                }
                
                $user_from = $row['user_from'];

    			$query = $this->con->query("SELECT * FROM users WHERE username='$user_from'");
    			$userData = $query->fetch_array(MYSQLI_ASSOC);
    			
    			// Calculate how long ago the notification was received
    			$date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($row['datetime']);
                $end_date = new DateTime($date_time_now);
                $interval = $start_date->diff($end_date);
                
                if($interval->y >= 1){
                    if($interval->y == 1){
                        $time_message = $interval->y." year ago";
                    } else {
                        $time_message = $interval->y." years ago";
                    }
                } elseif($interval->m >= 1){
                    if($interval->d == 0){
                        $days = " ago";
                    } else if($interval->d == 1){
                        $days = $interval->d." day ago";
                    } else {
                        $days = $interval->d." day ago";
                    }
                    
                    if($interval->m == 1){
                        $time_message = $interval->m." month ".$days;
                    } else {
                        $time_message = $interval->m." months ".$days;
                    }
                } else if($interval->d >= 1){
                    if($interval->d == 1){
                        $time_message = "Yesterday";
                    } else{
                        $time_message = $interval->d." days ago";    
                    }
                } else if($interval->h >= 1){
                    if($interval->h == 1){
                        $time_message = $interval->h." hour ago";
                    } else{
                        $time_message = $interval->h." hours ago";
                    }
                } else if($interval->i >= 1){
                    if($interval->i == 1){
                        $time_message = $interval->i." minute ago";
                    } else {
                        $time_message = $interval->i." minutes ago";
                    }
                } else {
                    if($interval->s < 30){
                        $time_message = "Just now";
                    } else {
                        $time_message = $interval->s." seconds ago";
                    }
                } 
                
                // If this is yes, then this notification has been clicked on before.
                $opened = $row['opened'];
                
                // If the message is unopened, change background color slightly
                $style = ($opened == 'no') ? "background-color: #DDEDFF;" : "";
                
                $str .= "<a href='".$row['link']."'>
                    <div class='resultDisplay resultDisplayNotification' style='".$style."'>
                        <div class='notificationsProfilePic'>
                            <img src='".$userData['profile_pic']."'>
                        </div>
                        <p class='timestamp_smaller' id='grey'>".$time_message."</p>".$row['message']."    
                    </div>
                </a>";
                
            } // End of the while loop
            
            // If posts were loaded
            if($count > $limit){
                // Holds value of next page, it must stay hidden
                $str.="<input type='hidden' class='nextpageDropdownData' value='".($page + 1).
                "'><input type='hidden' class='noMoreDropdownData' value='false'>";
            } else {
                // No more Notifications to load. Show 'Finished' message
	        	$str .= "<input type='hidden' class='noMoreDropdownData' value='true'>
	        	<p style='text-align: center;'>No more notifications to load!</p>";
            }
            
            echo $str;
        }
        
        // Insert Notification
        public function insertNotification($post_id, $user_to, $type){
            // Username of user logged in
            $userLoggedIn = $this->user_obj->getUsername(); 
            
            // First and last name of user logged in
		    $userLoggedInName = $this->user_obj->getFirstAndLastName(); 

		    // Current date and time
		    $date_time = date("Y-m-d H:i:s"); 
		    
		    switch ($type){
		        
		    }
        }
    }
?>