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
        }
    }
?>