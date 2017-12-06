<?php
    if (isset($_GET['profile_username'])) {
        $username = $_GET['profile_username'];
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
        
        // Details of the user of the profile page
        $profile_username = mysqli_fetch_array($user_details_query);
        
        // A message object for users that have logged in
        $message_obj = new Message($con, $userLoggedIn);
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