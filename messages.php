<?php
    include("includes/header.php");
    
    // The message object
    $message_obj = new Message($con, $user['username']);
    
    // Check if the username is passed via a url
    if (isset($_GET['u'])) {
        $user_to = $_GET['u'];
    } else {
        $user_to = $message_obj->getMostRecentUser();
        if($user_to == false)
            $user_to == "new";
    }
    
    // If the user is not creating a new message, make user object
    if($user_to != "new")
        $user_to_obj = new User($con, $user_to);
    
?>

<div class="user_details column">
    <a href="<?php echo $user['username']; ?>">
        <img src="<?php echo $user['profile_pic']; ?>">
    </a>
    <div class="user_details_left_right">
        
    </div>
</div>