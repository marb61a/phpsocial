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
        }
    }
?>