<html>
    <head>
        <title></title>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">    
    </head>
    <body>
        <?php
            include("config/config.php");
    		include("./includes/classes/User.php");
    		include("./includes/classes/Message.php");
    		include("./includes/classes/Swirl.php");
    		include("./includes/classes/Notification.php");
    		
    		if($_SESSION["username"]){
    		    $userLoggedIn = $_SESSION["username"];
    		    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    		    $user = mysqli_fetch_array($user_details_query);
    		} else {
    		    ("Location: register.php");
    		}
        ?>
        <style type="text/css">
            * {
                font-size: 12px;
			    font-family: Arial, Helvetica, Sans-serif;
            }
            
            hr {
                background-color: #fff;
                margin-top: 12px;
                height: 1px;
                border: 0px ;
            }
            
            body {
                min-width: 100%;
			    background-color: #ECF0F1;
            }
        </style>
        
        <script language="javascript">
            function toggle(){
                var ele = document.getElementById("comment_section");
                if(ele.style.display == "block"){
                    ele.style.display = "none";
                } else {
                    ele.style.display = "block";
                }
            }
        </script>
        
        <?php
            // Get the id of the swirl
            if(isset($_GET['post_id'])) {
                $post_id = $_GET['post_id'];
            }
            
            $user_query = mysqli_query($con, "SELECT added_by, user_to FROM swirls WHERE id='$post_id'");
    		$get_user = mysqli_fetch_assoc($user_query);
    		$posted_to = $get_user['added_by'];
    		$user_to = $get_user['user_to'];
    		
    		if(isset($_POST['postComment' . $post_id])){
    		    $post_body = $_POST['post_body'];
			    $post_body = mysqli_escape_string($con, $post_body);
			    $date_time_now = date("Y-m-d H:i:s");
			    $insert_post = mysqli_query($con, "INSERT INTO swirl_comments VALUES ('', '$post_body', '$userLoggedIn', 
			        '$posted_to', '$date_time_now', 'no', '$post_id')");
			    
			    // Insert a notification to the user that posted
			    if($posted_to != $userLoggedIn){
			        $notification = new Notification($con, $userLoggedIn);
			        $notification->insertNotification($post_id, $posted_to, "swirl_comment");
			    }
			    
			    // If the post was to a user let the user know
			    if($user_to != 'none' && $user_to != $userLoggedIn){
			        $notification = new Notification($con, $userLoggedIn);
			        $notification->insertNotification($post_id, $user_to, "swirl_profile_post_comment");
			    }
			    
			    // Select all the users that commented and send them notifications 
			    $get_commenters = $con->query("SELECT posted_by FROM swirl_comments WHERE post_id='$post_id'");
			    
			    // An array to hold the users that have been notified of a comment
			    $notified_users_array = array();
			    
			    while($row = $get_commenters->fetch_array(MYSQLI_ASSOC)){
			        // Send other users who commented a notification
			        if($row['posted_by'] != $posted_to && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], 
			            $notified_users_array) && $row['posted_by'] != $user_to){
			            $notification = new Notification($con, $userLoggedIn);
					    $notification->insertNotification($post_id, $row['posted_by'], "swirl_comment_non_owner");
					    
					    // Add user to array to keep track of who has been notified
					    array_push($notified_users_array, $row['posted_by']);
			        }
			    }
			    
			    echo "<p>Comment Posted!<p/>";
    		}
        ?>
        
        <form action="swirl_comment_frame.php?post_id=<?php echo $post_id; ?>" 
                id='comment_form' method="POST" name="postComment<?php echo $post_id; ?>">
            <textarea  name='post_body'></textarea>
			<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post">    
        </form>
        
        <?php
            $get_comments = mysqli_query($con, "SELECT * FROM swirl_comments WHERE post_id='$post_id' ORDER BY id ASC");
            $count = mysqli_num_rows($get_comments);
            
            if($count != 0){
                while($comment = mysqli_fetch_assoc($get_comments)){
                    $comment_body = $comment['post_body'];
    				$posted_to = $comment['posted_to'];
    				$posted_by = $comment['posted_by'];
    				$date_added = $comment['date_added'];
    				$removed = $comment['removed'];
    
    				$date_time_now = date("Y-m-d H:i:s");
    				$time_message = "";
                    $start_date = new DateTime($date_added);
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
                    
                    $getFirstAndLast = mysqli_query($con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$posted_by'");
    				$userRow = mysqli_fetch_assoc($getFirstAndLast);
    				$firstName = $userRow['first_name'];
    				$lastName = $userRow['last_name'];
    				$profilePic = $userRow['profile_pic'];
				
                }
            }
        ?>
    </body>
</html>