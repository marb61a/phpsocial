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
				    
				    // Get logged in user's total bums
				    $total_user_bumps = $user_bumped_array['num_bumps'];
				    
				    // Increase the total by 1
				    $total_user_bumps++;
				    
				    // Update users bump column value 
				    $user_bump = mysqli_query($con, "UPDATE users SET num_bumps='$total_user_bumps' WHERE username='$user_bumped'");
				    
				    // Insert Notification
				    // Check if the bump notification is already in the table, if so do not send another
				    $notice_check = $con->query("SELECT * FROM notifications WHERE user_to='$user_bumped' 
				        AND user_from='$userLoggedIn' AND link LIKE '%id=$post_id' AND message LIKE '%bumped%'");
				    
				    // If notification doesn't exist already
				    if($notice_check->num_rows == 0){
				        // If the user is not bumping their own post
				        if($user_bumped != $userLoggedIn){
				            $notification = new Notification($con, $userLoggedIn);
				            $notification->insertNotification($post_id, $user_bumped, "swirl_bump");
				        }
				    }
    		    }
    		    
    		    if(isset($_POST['unbump_button'])){
    		        $total_bumps--;
    		        $bump = mysqli_query($con, "UPDATE swirls SET bumps='$total_bumps' WHERE id='$post_id'");
				    $remove_user = mysqli_query($con, "DELETE FROM user_bumps WHERE username='$userLoggedIn' AND post_id='$post_id'");
				    
    				$total_user_bumps = $user_bumped_array['num_bumps'];
    				$total_user_bumps--;
    				$user_bump = mysqli_query($con, "UPDATE users SET num_bumps='$total_user_bumps' WHERE username='$user_bumped'");
    		    }
    		}
    		
    		// Check for previous bumps
    		$check_for_bumps = mysqli_query($con, "SELECT * FROM user_bumps WHERE username='$userLoggedIn' AND post_id='$post_id'");
    		$numrows_bumps = mysqli_num_rows($check_for_bumps);
    		
    		if($numrows_bumps >= 1){
    		    echo '
    		        <form action="swirl_bump.php?post_id=' . $post_id . '" method="POST">
    		            <input type="submit" class="commentBump" name="unbump_button' . $id . '" value="Unbump">
    		            <div classes="bump_value">
    		                '. $total_bumps .' Bumps
    		            </div>
    		        </form>    
    		    ';
    		} else if($numrows_bumps == 0){
    		    echo '
    		        <form action="swirl_bump.php?post_id=' . $post_id . '" method="POST">
    		            <input type="submit" class="commentBump" name="bump_button' . $id . '" value="Bump">
    		            <div classes="bump_value">
    		                '. $total_bumps .' Bumps
    		            </div>
    		        </form>    
    		    ';
    		}
    		
        ?>
    </body>
</html>