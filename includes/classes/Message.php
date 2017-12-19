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
            
        }
    }
?>