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
        }
    }
?>