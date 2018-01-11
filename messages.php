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
        <br>
        <a href="<?php echo $user['username']; ?>"><?php echo $user['first_name']." ".$user['last_name']; ?></a>
		<br>
		Posts: <?php echo $user['num_posts']; ?>
		<br>
		Bumps: <?php echo $user['num_bumps']; ?>
		<br>
    </div>
</div>

<div class="main_column column" id="main_column">
    <?php 
    	if($user_to != "new"){
    		echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>"; 
    ?>
		<div class="loaded_messages" id="x">
			<?php echo $message_obj->getMessages($user_to); ?>
		</div>
        <?php 
        } else 
    		echo "<h4>New Message</h4>";
        ?>
    ?>
    <div class="message_post">
        <form action="" method="POST">
            <?php if($user_to == "new"){ ?>
                Select the friend you would like to send the message to. 
                <br><br>
                To: <input type="text" onkeyup="getUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder='Name' autocomplete='off' id='search-text-input'>
				<div class="results"></div>
				<?php
            } else { ?>
                <textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea>
				<input type="submit" name="post_message" class="info" id="message_submit" value="Send">
            <?php
			}
			?>    
            
        </form>
    </div>
    
    <!-- Javascript to scroll messages to bottom -->
	<script>
		var objDiv = document.getElementById("x");
		objDiv.scrollTop = objDiv.scrollHeight;
	</script>
</div>
