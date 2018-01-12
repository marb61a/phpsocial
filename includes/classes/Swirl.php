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
            
            $data = $this->con->query("SELECT * FROM swirls WHERE id='$swirl_id' AND deleted='no'");
            
            //If a query returns empty then there are no more rows to load
            if($data->num_rows != 0){
                $row = fetch_array(MYSQLI_ASSOC);
                $id = $swirl_id;
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];
                $mobile_device = $row['mobile_device'];
                $hidden_mode = $row['hidden_mode'];
                
                // Prepare the user_to string so that a post can be echoed even if it is not to a user
                if($row['user_to'] == 'none'){
                    $user_to_string = ""; 
                } else {
                     $user_to_obj = new User($this->con, $row['user_to']);
                     
                     // Get the first and last name
                     $user_to_name = $user_to_obj->getFirstAndLastName();
                     $user_to_string = " to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                }
                
                // Check to see if the user that has posted had closed their account, it the account is closed do not show the post
                $added_by_user = new User($this->con, $added_by);
                if($added_by_user->isClosed() == 'yes'){
                    echo "We're sorry but this user has closed their account.";
                    return;
                }
                
                // Check to see if the user is friends with the user that posted
                
            }
        }
    }
?>