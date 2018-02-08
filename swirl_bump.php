<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    </head>
    <body>
        <style type="text/css">
            * {
                -webkit-transition: all 0s ease-in-out;
  			    -moz-transition: all 0s ease-in-out;
            }
            
            body {
                background-color: #fff;
            }
            
            input [type="submit"] {
                background-color: transparent;
                border: none;
                height: auto;
                width: auto;
                margin: 0;
                color: #3598db;
            }
            input [type="submit"]:active {
                background-color: transparent;
                border: none;
                height: auto;
                width: auto;
                margin: 0;
                color: #3598db;
                padding: 0;
            }
        </style>
        <?php
            include("config/config.php");
    		include("./includes/classes/User.php");
    		include("./includes/classes/Message.php");
    		include("./includes/classes/Swirl.php");
    		include("./includes/classes/Notification.php");
    		
    		if(isset($_SESSION["username"])){
    		    $userLoggedIn = $_SESSION["username"];
    		    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    		    $user = mysqli_fetch_array($user_details_query);
    		} else {
    		    header("Location: register.php");
    		}
    		
    		$id = "";
    		if(isset($_GET['post_id'])){
    		    $post_id = mysqli_real_escape_string($con, $_GET['post_id']);
    		    
    		    if(ctype_alnum($post_id)){
    		        $get_bumps = mysqli_query($con, "SELECT bumps, added_by FROM swirls WHERE id='$post_id'");
    		        $user_bumped = mysqli_fetch_array($get_bumps);
    		        $total_bumps = $user_bumped['bumps'];
    		        $user_bumped = $user_bumped['added_by'];
    		        
    		        // An array for a user who posted the post
    		        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_bumped'");
				    $user_bumped_array = mysqli_fetch_array($user_details_query);
    		    }
    		    
    		    if(isset($_POST['bump_button'])){
    		        // This will increase the bumps for the post by 1
    		        $total_bumps++;
    		        
    		        // Update the post with the new bump value
    		        $bump = mysqli_query($con, "UPDATE swirls SET bumps='$total_bumps' WHERE id='$post_id'");
    		        
    		        // Insert the user into the bumps table
				    $user_bumps = mysqli_query($con, "INSERT INTO user_bumps VALUES ('', '$userLoggedIn', '$post_id')");
    		    }
    		}
        ?>
    </body>
</html>