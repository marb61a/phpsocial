<?php
    class User {
        // MySQL connection variable
        private $con;
        
        // User object for user that is logged in
        private $user_obj;
        
        // Constructor
        public function __construct($con, $user){
            $this->con = $con;
            $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
            
            // Array of user details
            $this->user = mysqli_fetch_array($user_details_query);
        }
        
        public function getUserName(){
            return $this->user['username'];
        }
        
        public function getNumberOfFriendRequests(){
            $username = $this->user['username'];
            $requests_query = $this->con->query("SELECT id FROM friend_requests WHERE user_to='$username'");
            return $requests_query->num_rows;
        }
        
        public function getFirstAndLastName(){
            $username = $this->user['username'];
            $name_query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username'");
            $row = mysqli_fetch_assoc($name_query);
            return $row['first_name']." ".$row['last_name'];
        }
        
        public function getFriendArray(){
            $username = $this->user['username'];
            $name_query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$username'");
            $row = mysqli_fetch_assoc($name_query);
            return $row['friend_array'];
        }
        
        public function getProfilePic(){
    		$username = $this->user['username'];
    		$name_query = mysqli_query($this->con, "SELECT profile_pic FROM users WHERE username='$username'");
    		$row = mysqli_fetch_assoc($name_query);
    		return $row['profile_pic'];
    	}
    
    	public function isClosed(){
    		$username = $this->user['username'];
    		$is_closed_query = mysqli_query($this->con, "SELECT user_closed FROM users WHERE username='$username'");
    		$row = mysqli_fetch_assoc($is_closed_query);
    		return $row['user_closed'];
    	}
    	
    	// Check if the main user is friends with the user
    	public function isFriend(){
    	    // Put a comma either side of the user name to check
    	    $usernameComma = ",".$username_to_check.",";
    	    
    	    // Check if the username to check appears if the friend array
    	    if((strstr($this->user['friend_array'], $usernameComma)) || ($username_to_check == $this->user['username'])){
    	        return true;
    	    } else {
    	        return false;
    	    }
    	}
    	
    	public function removeFriend($user_to_remove){
    	    $logged_in_user = $this->user['username'];
    	    
    	    // The friend array for the user who owns the profile
    	    $add_friend_check_username = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
    	    $row = mysqli_fetch_assoc($add_friend_check_username);
    	    $friendArrayUsername = $row['friend_array'];
    	    
    	    // Remove other user from logged_in_user friend array
    	    $new_friend_array = str_replace($user_to_remove.",", "", $this->user['friend_array']);
		    $removeFriendQuery = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$logged_in_user'");
		    
		    // Remove logged_in_user from the other person's array
		    $new_friend_array = str_replace($this->user['username'].",", "", $friendArrayUsername);
		    $removeFriendQuery_username = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$user_to_remove'");
    	    
    	}
    	
    	// Sends a request to the user which is passed as a parameter
    	public function sendRequest($user_to){
    	    $user_from = $this->user['username'];
    	    $createRequest = mysqli_query($this->con, "INSERT INTO friend_requests VALUES ('', '$user_to', '$user_from')");
    	}
    	
    	// This returns true if a request has been received from the user which was passed as a parameter
    	public function didReceiveRequest($user_from){
    	    $user_to = $this->user['username'];
    	    $check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
    	    
    	    if(mysqli_num_rows($check_request_query) != 0){
    	        return true;
    	    } else {
    	        return false;
    	    }
    	}
    	
    	// This will return true if request was already to the user which is passed as a parameter
    	public function didSendRequest($user_to){
    	    $user_from = $this->user['username'];
    	    $check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
    	    
    	    if(mysqli_num_rows($check_request_query) != 0){
    	        return true;
    	    } else {
    	        return false;
    	    }
    	}
    	
    	// Iterates through friend arrays and returns the number of equal elements
    	public function getMutualFriends($user_to_check){
    	    
    	}
    }
?>