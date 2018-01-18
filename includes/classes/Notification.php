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
                
            }
        }
    }
?>