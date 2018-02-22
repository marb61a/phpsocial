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
        <?php $num_friends = substr_count($profile_username['friend_array'], "," ) - 1; ?>
		<p><?php echo "Posts: ".$profile_username['num_posts']; ?></p>
		<p><?php echo "Bumps: ".$profile_username['num_bumps']; ?></p>
		<p><?php echo "Friends: ".$num_friends; ?></p>
    </div>
    
    <!--- The buttons for a profile, add_friends etc -->
    <form action="<?php echo $profile_username["username"]?>" method="POST">
        <?php
            // If a user account is closed then redirect
            $profile_user = new User($con, $profile_username['username']);
            
            if($profile_user->isClosed() == "yes")
			    header("Location: user_closed.php");
			    
			// A user object for logged_in user
			$logged_in_user = new User($con, $user['username']);
			
			// If a user is not on their own profile
			if($user['username'] != $profile_username['username']){
			    if ($logged_in_user->isFriend($profile_username['username'])){ 
    				// If users are friends, show remove friend button. 
    				echo '<input type="submit" name="removeFriend" class="danger" value="Remove Friend"><br>';
    			} else if ($logged_in_user->didReceiveRequest(($profile_username['username']))){
    			    // If a request has been received from the profile user
				    echo '<input type="submit" name="respondToRequest" class="warning" value="Respond to Request"><br>';
    			} else if($logged_in_user->didSendRequest(($profile_username['username']))){
    			    // If a request has already  been sent to the profile user and is awaiting reponse
    			    echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
    			} else {
    			    // If the users are not friends show the add friend button
    			    echo '<input type="submit" name="addFriend" class="success" value="Add Friend"><br>';
    			}
			}
			
			// Posting button
			echo '<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">';
			
			// If a user is not on their own profile
			if($user['username'] != $profile_username['username']){
			    echo '<div class="profile_left_window">';
			        // Mutual Friends
			        echo $logged_in_user->getMutualFriends($profile_username['username'])." friends in common";
			    echo '</div>';
			}
        ?>
    </form>
</div>

<div class="profile_main_column column">
    <!--- Nav tabs --->
    <ul class="nav nav-tabs" role="tablist" id="profileTabs">
        <li role="presentation" class="active">
            <a href="#swirlfeed_div" aria-controls="swirlfeed_div" role="tab" data-toggle="tab">Swirlfeed</a>
        </li>
        <li role="presentation">
            <a href="#about_div" aria-controls="about_div" role="tab" data-toggle="tab">About</a>
        </li>
        <li role="presentation">
            <a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Message</a>
        </li>
    </ul>
    <div class="tab-content">
        <!--- The Swirlfeed div --->
        <div role="tabpanel" class="tab-pane fade in active" id="swirlfeed_div">
            <div class="swirls_area"></div>
            <img id='loading' src='assets/images/icons/loading.gif'>
        </div>
        
        <!--- The About div --->
        <div role="tabpanel" class="tab-pane fade" id="about_div">
            Blah
        </div>
        
        <!--- The Messages div --->
        <div role="tabpanel" class="tab-pane fade" id="messages_div">
            <?php
                $profile_user_obj = new User($con, $profile_username['username']);
                echo "<h4>You and <a href='".$profile_username['username']."'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";
            ?>
            
            <div class="loaded_messages" id="x" style="height: 56%;">
                <?php
                    echo $message_obj->getMessages($profile_username['username']);
                ?>
            </div>
            
            <div class="message_post">
                <form action="" method="POST">
                    <textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea>
					<input type="submit" name="post_message" class="info" id="message_submit" value="Send">
                </form>
            </div>
        </div>
    </div>
</div>