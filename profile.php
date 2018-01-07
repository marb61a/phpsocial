<?php
    if (isset($_GET['profile_username'])) {
        $username = $_GET['profile_username'];
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
        
        // Details of the user of the profile page
        $profile_username = mysqli_fetch_array($user_details_query);
        
        // A message object for users that have logged in
        $message_obj = new Message($con, $userLoggedIn);
        
        // The remove_friend button was pressed
        if(@$_POST['removeFriend']){
            $logged_in_user = new User($con, $user['username']);
	        $logged_in_user->removeFriend($profile_username['username']);
        }
        
        // The add_friend button is pressed
        if (@$_POST['addFriend']) {
        	$logged_in_user = new User($con, $user['username']);
        	$logged_in_user->sendRequest($profile_username['username']);
        }
        
        // The respond_to_request button is pressed
        if (@$_POST['respondToRequest']) {
        	header("Location: requests.php");
        }
        
        // The send button on the message form is pressed
        if(isset($_POST['post_message'])){
            // Check the message box to ensure that is has text in it
            if(isset($_POST['message'])){
                $body = mysql_real_escape_string($_POST['message_body']);
        		$date = date("Y-m-d H:i:s");
        		$message_obj->sendMessage($profile_username['username'], $body, $date);
            }
            
            // The tab to be loaded
            $link = '#profileTabs a[href="#messages_div"]';
            echo "
                <script>
                    $(function(){
                        $('".$link."').tab('show');
                    })
                </script>
            ";
        }
    }
?>

<style type="text/css">
    .top_bar {
        margin-bottom: 0px;
    }
    
    .wrapper{
        margin-left: 0px;
        padding-left: 0px;
    }
</style>

<div class="profile_left">
    <img src="<?php echo $profile_username['profile_pic']; ?>">
    <div class="profile_left_right">
        
    </div>
</div>