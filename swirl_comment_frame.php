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
					    
			        }
			    }
    		}
        ?>
    </body>
</html>