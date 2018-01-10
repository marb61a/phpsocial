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
    	    
    	}
    }
?>