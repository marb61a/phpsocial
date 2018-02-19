<?php

?>
<html>
    <head>
        <title>Welcome to PHPSocial</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css"/> 
    	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
    	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    	<script src="assets/js/jquery.Jcrop.js"></script>
    	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
    	<link rel="stylesheet" href="assets/js/jquery.paulund_modal_box.js"/>
    	<script src="assets/bootstrap/js/bootstrap.js"></script>
    	<script src="assets/js/jcrop_bits.js"></script>
    	<script src="assets/js/swirlfeed.js"></script>
    	<script src="assets/js/bootbox.min.js"></script>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
				<a href="index.php">PHPSocial</a>
			</div>
			<div class="search">
			    <form action="search.php" method="GET" name="search_form">
			        <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" 
			        name="q" placeholder='Search...' autocomplete='off' id='search-text-input'>
			        <div class="button_holder">
			            <img src='assets/images/icons/magnifying_glass.png'/>
			        </div>
			    </form>
			    <div class="search_results">
				</div>
				<div class="search_results_footer_empty">
				</div>
				<nav>
				    <?php
				        // Get the number of unread notifications
				        $notification = new Notification($con, $userLoggedIn);
				        
				        // Returns the number of unread notifications
				        $num_notifications = $notification->getUnreadNumber();
				        
				        // Gets the number of unread messages
					    $messages = new Message($con, $userLoggedIn);
					    
					    // Returns the number of unread messages
					    $num_messages = $messages->getUnreadNumber();
					    
					    // Gets the number of unread friend requests
					    $user_obj = new User($con, $userLoggedIn);
					    
					    // Returns the number of unread friend requests
					    $num_friend_requests = $user_obj->getNumberOfFriendRequests();
				    ?>
				    
				    <a href="<?php echo $user['username']; ?>">
				    	<?php echo $user['first_name']; ?>
				    </a>
				    
				    <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				    	<i class="icon fa  fa-envelope-o fa-lg"></i>
				    	<?php
				    		if($num_messages > 0){
				    			echo '<span class="notification_badge" id="unread_message">'.$num_messages.'</span>';
				    		}
				    	?>
				    </a>
				</nav>
				
				<!-- This div holds dropdown data notifications or messages -->
				<div class="dropdown_data_window"></div>
				
				<!-- This value is used for the type of data to load e.g. messages, notifications -->
				<input type="hidden" id="dropdown_data_type" value="">
			</div>
			
			<!-- script for loading dropdown_data AND infinite scrolling -->
			<script>
				var userLoggedIn = '<?php echo $userLoggedIn; ?>';
				
				$(document).ready(function(){
					$(window).scroll(function(){
						
					});
				});
			</script>
        </div>
    </body>
</html>