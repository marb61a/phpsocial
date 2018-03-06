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
                if($this->user_obj->isFriend($added_by)){
                    // If the user is the one who posted then show the delete button
                    if($userLoggedIn == $added_by){
                        $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                    } else {
                        $delete_button = "";
                    }
                    
                    $get_user_details = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $userRow = mysqli_fetch_assoc($get_user_details);
                    
                    // If hidden mode = no then use the profile picture of the user, if not use a random image
                    if($hidden_mode == 'no'){
                        $firstName = $userRow['first_name'];
                        $lastName = $userRow['last_name'];
                        $profile_pic = $userRow['profile_pic'];    
                    } else {
                        $firstName = 'Someone said: ';
                        $lastName = '';
                        // This is needed so Someone said does not link to a users profile
                        $added_by = '';
                        
                        // A random number between 1 and 16
                        $random_num = rand(1, 16);
                        
                        switch($random_num){
                            case '1':
                                $profile_pic = "assets/images/profile_pics/defaults/head_alizarin.png";
                                break;
                            case '2':
                                $profile_pic = "assets/images/profile_pics/defaults/head_amethyst.png";
                                break;
                            case '3':
                                $profile_pic = "assets/images/profile_pics/defaults/head_belize_hole.png";
                                break;
                            case '4':
                                $profile_pic = "assets/images/profile_pics/defaults/head_carrot.png";
                                break;
                            case '5':
                                $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
                                break;
                            case '6':
                                $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
                                break;
                            case '7':
                                $profile_pic = "assets/images/profile_pics/defaults/head_green_sea.png";
                                break;
                            case '8':
                                $profile_pic = "assets/images/profile_pics/defaults/head_nephritis.png";
                                break;
                            case '9':
                                $profile_pic = "assets/images/profile_pics/defaults/head_pete_river.png";
                                break;
                            case '10':
                                $profile_pic = "assets/images/profile_pics/defaults/head_pomegranate.png";
                                break;
                            case '11':
                                $profile_pic = "assets/images/profile_pics/defaults/head_pumpkin.png";
                                break;
                            case '12':
                                $profile_pic = "assets/images/profile_pics/defaults/head_red.png";
                                break;
                            case '13':
                                $profile_pic = "assets/images/profile_pics/defaults/head_sun_flower.png";
                                break;
                            case '14':
                                $profile_pic = "assets/images/profile_pics/defaults/head_turqoise.png";
                                break;
                            case '15':
                                $profile_pic = "assets/images/profile_pics/defaults/head_wet_asphalt.png";
                                break;
                            case '16':
                                $profile_pic = "assets/images/profile_pics/defaults/head_wisteria.png";
                                break;    
                        } // End of the switch
                    } // End of is hidden
                }
                
                ?>
                
                <script language="javascript">
                    function toggle<?php echo $id; ?>() {
                        var target = $( event.target );
                        if ( !target.is( "a" ) ) {
                            var ele = document.getElementById("toggleComment<?php echo $id; ?>");
                            if(ele.style.display == "block") {
                                ele.style.display = "none";
                            }
                            else {
                                ele.style.display = "block";
                            }
                        }
                    }
                </script>
                
                <?php
                    $comments_check = mysqli_query($this->con, "SELECT * FROM swirl_comments WHERE post_id='$id'");
                    $comments_check_num_rows = mysqli_num_rows($comments_check);

                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time);
                    $end_date = new DateTime($date_time_now);
                    $interval = $start_date->diff($end_date);
                    
                    if($interval->y >= 1){
                        if($interval->y == 1){
                            $time_message = $interval->y." year ago";
                        } else {
                            $time_message = $interval->y." years ago";
                        }
                    } else if($interval->m >= 1){
                        if($interval->d == 0){
                            $days = " ago";
                        } else if ($interval->d == 1){
                            $days = $interval->d." day ago";
                        } else {
                            $days = $interval->d." days ago";
                        }
                        
                        if($interval->m == 1){
                            $time_message = $interval->m." month ".$days;
                        } else {
                            $time_message = $interval->m." months ".$days;
                        }
                    } else if($interval->d >= 1){
                        if($interval->d == 1){
                            $time_message = "Yesterday";
                        } else {
                            $time_message = $interval->d." days ago";
                        }
                    } else if($interval->h >= 1){
                        if($interval->h == 1){
                            $time_message = $interval->h." hour ago";
                        } else {
                            $time_message = $interval->h." hours ago";
                        }
                    }  else if($interval->i >= 1){
                        if($interval->i == 1){
                            $time_message = $interval->i." minute ago";
                        } else{
                            $time_message = $interval->i." minutes ago";
                        }
                    }   
                    else {
                        if($interval->s < 30){
                            $time_message = "Just now";
                        } else {
                            $time_message = $interval->s." seconds ago";
                        }
                    }  
                    
                    // Check to see if the post came from a mobile device
                    if($mobile_device == "yes"){
                        $from_mobile = "&nbsp;&nbsp;&nbsp;Via mobile device";
                    } else {
                        $from_mobile = "";
                    }
                    
                    $str .= "<br>
                    
                    "
                ?>
            }
        }
    }
?>