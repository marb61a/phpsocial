<?php
    class Swirl {
        // MySQL connection variable
        private $con;
        
        // User object for user that is logged in
        private $user_obj;
        
        // Constructor
        public function __construct($con, $userLoggedIn){
            $this->con = $con;
            $this->user_obj = new User($con, $userLoggedIn);
        }
        
        // This will get a single swirl
        public function getSingleSwirl($swirl_id){
            $userLoggedIn = $this->user_obj->getUsername();
            
            // Set viewed to yes for all notifications for that user
            $set_viewed = $this->con->query("UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn'  AND link LIKE '%=$swirl_id'");
            
            //Initialise a string that holds data to return
            $str = '';
        }
    }
?>