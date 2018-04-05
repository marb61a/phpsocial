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
                        <div class='swirl_post' onClick='javascript:toggle$id()'>
                            <div class='swirl_profile_pic'>
                                <img src='$profile_pic' width='50'>
                            </div>
                            <div class='posted_by' style='color: #ACACAC;'>
                                <a href='$added_by'> $firstName $lastName </a> ".$user_to_string." &nbsp;&nbsp;&nbsp;$time_message $from_mobile
                                $delete_button
                            </div>
                            <div id='post_body'>$body<br/><br/></div>

                            <div class='newsfeedPostOptions'>
                                Comments ($comments_check_num_rows)&nbsp;&nbsp;&nbsp
                                <iframe src='swirl_bump.php?post_id=$id' scrolling='no'> </iframe>
                            </div>
                        </div>
                        <div class='swirl_comment' id='toggleComment$id' style='display: none;'>
                            <iframe src='./swirl_comment_frame.php?post_id=$id' frameborder='0' id='comment_iframe'></iframe>
                        </div>
                        <br>
                        <hr style='margin:0';/>
                        <br>
                    "
                ?>
                
                <script>
                    $(document).ready(function(){
                        $('#post<?php echo $id; ?>').on('click', function(){
                             bootbox.confirm("Are you sure you want to delete this swirl?", function(result) {
                                $.post("includes/form_handlers/delete_swirl.php?post_id=<?php echo $id; ?>",{result:result});

                                if(result){
                                    location.reload();
                                }
                            });     
                        });      
                    });
                </script>
                <?php             
                        } //Check if user is friends with person who posted
                        else {
                        	echo "You cannot see this post as you are not friends with this user.";
                        	return;
                        }
            
            	    } //if num_rows != 0
            	    else { 
            	        //No more posts to load. Show 'Finished' message
            	        echo "<p>No post was found. If you clicked on a link it may be broken.</p>";
            	        return;
            	    }
            	  
            	    //Show swirls
            	    echo $str; 
            	}//End get single post
                    
                // Gets swirls by friends
                public function loadPostsFriends($data, $limit) {
                    $page = $data['page'];
                    $userLoggedIn = $this->user_obj->getUsername();
                    
                    if($page == 1){
                        // Start at the first post
                        $start = 0;
                    } else {
                        // Start where last loaded posts left off
                        $start = ($page - 1) * $limit;
                    }
                    
                    // Initialise a string that will hold data to return
                    $str = '';
                    $data = $this->con->query("SELECT * FROM swirls WHERE deleted='no' ORDER BY id DESC");
                    
                    // If the query returns empty there are no more posts to load
                    if($data->num_rows > 0){
                        // The number of results checked (not necessarily posted)
                        $num_iterations = 0;
                        $count = 1;
                        
                        while($row = $data->fetch_array(MYSQLI_ASSOC)){
                            $id = $row['id'];
                            $body = $row['body'];
                            $added_by = $row['added_by'];
                            $date_time = $row['date_added'];
                            $mobile_device = $row['mobile_device'];
                            $hidden_mode = $row['hidden_mode'];
                            
                            // Prepare the user_to string so that it can be echoed, even if the post is not to a user
                            if($row['user_to'] == 'none'){
                                if($row['user_to'] == 'none');        
                            } else {
                                $user_to_obj = new User($this->con, $row['user_to']);
                                
                                // Get the first and last name
                                $user_to_name = $user_to_obj->getFirstAndLastName();
                                
                                $user_to_string = " to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                            }
                            
                            // Check if the user who posted has their account closed, if so do not show the post
                            $added_by_user = new User($this->con, $added_by);
                            if($added_by_user->isClosed() == "yes"){
	                            continue;
                            }
                            
                            // A user object username of user logged in 
                            $logged_in_user = new User($this->con, $userLoggedIn);
                            
                            // Check if the person who posted is friends with user
                            if($logged_in_user->isFriend($added_by)){
                                if($num_iterations++ < $start){
                                    continue;
                                }
                                
                                // Once 10 posts have been loaded
                                if($count > $limit){
                                    break; 
                                } else {
                                    $count++;
                                }
                                
                                // If the user posted show the delete button
                                if($userLoggedIn == $added_by){
                                    $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                                } else {
                                    $delete_button = "";
                                }
                                
                                $get_user_details = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                                $userRow = mysqli_fetch_assoc($get_user_details);
                                
                                // If hidden mode = no, use profile pic of user, else use random picture
                                if($hidden_mode == 'no'){
                                    $firstName = $userRow['first_name'];
	                                $lastName = $userRow['last_name'];
	                                $profile_pic = $userRow['profile_pic'];    
                                } else {
                                    $firstName = 'Someone said: ';
            	                    $lastName = '';
            	                    // This is needed to avoid someone said linking to a profile
            	                    $added_by = '';
            	                    
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
            	                    } // End Switch
                                } // End hidden mode
                                ?>
                                
                                <script language="javascript">
                                    function toggle<?php echo $id; ?>(){
                                        var target = $( event.target );
                                        
                                        if ( !target.is( "a" ) ){
                                            var ele = document.getElementById("toggleComment<?php echo $id; ?>");
                                            
                                            if(ele.style.display == "block"){
                                                ele.style.display = "none";
                                            } else {
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
                	                    } else if($interval->d == 1){
                	                        $days = $interval->d." day ago";         
                	                    } else {
                	                        $days = $interval->d." days ago";
                	                    }
                	                    
                	                    if($interval->m == 1){
	                                        $time_message = $interval->m." month ".$days;
                	                    } else{
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
	                                } else if($interval->i >= 1){
                                        if($interval->i == 1){
                                            $time_message = $interval->i." minute ago";
                                        } else {
                                            $time_message = $interval->i." minutes ago";
                                        }
                                    } else {
                                        if($interval->s < 30){
                                            $time_message = "Just now";
                                        } else {
                                            $time_message = $interval->s." seconds ago";
                                        }
                                    } 
                                    
                                    //Check if the post came from mobile device
                                    if($mobile_device == "yes"){
                                        $from_mobile = "&nbsp;&nbsp;&nbsp;Via mobile device";
                                    } else {
                                        $from_mobile = "";
                                    }
                                    
                                    // String containing post
                                    $str .= "
                                        <div class='swirl_post' onClick='javascript:toggle$id()'>
                                            <div class='swirl_profile_pic'>
                                                <img src='$profile_pic' width='50'>
                                            </div>
                                            <div class='posted_by' style='color: #ACACAC;'>
                                                <a href='$added_by'>$firstName $lastName</a>".
                                                $user_to_string." &nbsp;&nbsp:@nbsp; $time_message $from_mobile
                                                $delete_button
                                            </div>
                                            <div id='post_body'>$body<br/><br/></div>
                                            <div class='newsfeedPostOptions'>
                                                Comments ($comments_check_num_rows)&nbsp;&nbsp;&nbsp
                                                <iframe src='swirl_bump.php?post_id=$id' scrolling='no'></iframe>
                                            </div>
                                        </div>
                                        <div class='swirl_comment' id='toggleComment$id' style='display: none;'>
                                            <iframe  src='./swirl_comment_frame.php?post_id=$id' frameborder='0' id='comment_iframe'></iframe>
                                        </div>
                                        <br>
                                        <hr style='margin:0';/>
                                        <br>
                                    ";
                                ?>
                                
                                <script>
                                    $(document).ready(function(){
                                        $('#post<?php echo $id; ?>').on('click', function(){
                                            bootbox.confirm("Are you sure you want to delete this swirl?", function(result){
                                                $.post("includes/form_handlers/delete_swirl.php?post_id=<?php echo $id; ?>",{result:result});
                                                
                                                if(result){
                                                    location.reload();
                                                }
                                            });    
                                        });
                                    });
                                </script>
                            
                            <?php    
                            }
                        } //  End while loop
                        
                        // If posts were loaded
                        if($count > $limit){
                            // This holds the value of the next page and must stay hidden
                            $str.="<input type='hidden' class='nextpage' value='".($page + 1)."'>
                            <input type='hidden' class='noMorePosts' value='false'>";
                        } else {
                            // If there are no more posts to load show the finished message
                            $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>No More posts to show!</p>";
                        }
                    }
                    
                    // Show swirls
                    echo $str;
                }
                
                    // Post swirl
                    public function postSwirl($body, $isHidden, $user_to, $is_mobile){
                        // Remove the HTML tags
                        $body = strip_tags($body);
                        
                        // Escape all special characters
                        $body = $this->con->real_escape_string($body);
                        
                        // Remove spaces such as tabs, spaces etc
                        $check_empty = preg_replace('/\s+/', '',$body);
                        
                        // If the text body is no empty
                        if($check_empty != ""){
                            // Check if the user posted a Youtube link, start with splitting teaxt body into an array at spaces
                            $body_array = preg_split("/\s+/", $body);
                            
                            // Iterate through the array
                            foreach($body_array as $key => $value){
                                
                            }
                        }
                    }
                
	            }
                
                // If posts were loaded
	            if($count > $limit){
	        	    // Holds value of next page. Must stay hidden
	        	    $str.="<input type='hidden' class='nextpage' value='".($page + 1)."'><input type='hidden' class='noMorePosts' value='false'>";
	            } else  
	        	    // No more posts to load. Show 'Finished' message
	        	    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>No more posts to show!</p>";
	            
            }
            
            //Show swirls
	        echo $str;
        }
    }
?>