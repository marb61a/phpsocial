<?php
	include("./config/config.php");
	include("./includes/classes/User.php");
	include("./includes/classes/Message.php");
	include("./includes/classes/Swirl.php");
	include("./includes/classes/Notification.php");
	
	if(isset($_SESSION['username'])){
		$userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
		$user = mysqli_fetch_array($user_details_query);
	} else {
		header("Location: register.php");
	}
	
	// Check what type of device a user is using
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
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
				    
				    <a href="index.php">
				    	<i class="icon fa  fa-home fa-lg"></i>
				    </a>
				    
				    <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
				    	<i class="icon fa  fa-bell-o fa-lg"></i>
				    	<?php
				    		if($num_notifications > 0){
				    			echo '<span class="notification_badge" id="unread_notification">'.$num_notifications.'</span>';
				    		}
				    	?>
				    </a>
				    
				    <a href="requests.php">
						<i class="icon fa  fa-users fa-lg"></i>
						<?php 
							if($num_friend_requests > 0){
								echo '<span class="notification_badge" id="unread_request">'.$num_friend_requests.'</span>';
							}
						?>
					</a>
					
					<a href="settings.php">
						<i class="icon fa  fa-cog fa-lg"></i>
					</a>
				
					<a href="includes/handlers/logout.php">
						<i class="icon fa  fa-sign-out fa-lg"></i>
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
						// Get the height of the div containing dropdown data
						var inner_height = $('.dropdown_data_window').innerHeight();
						
						var scroll_top = $('.dropdown_data_window').scrollTop();
						var page = $('.dropdown_data_window').find('.nextpageDropdownData').val();
				    	var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();
				    	
				    	if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false'){
				    		// Hold the name of the page to send ajax
				    		var pageName;
				    		
				    		// The value in the hidden input field in dropdown_data_window div. Hold the type of data to load.
				    		var type = $('#dropdown_data_type').val();
				    		
				    		if(type == 'notification'){
				    			pageName = "ajax_load_notifications.php";
				    		} else if(type == 'message'){
				    			pageName = "ajax_load_messages.php";
				    		} else if(type == 'friend_request')
				    			pageName = "ajax_load_friend_requests.php"; 
				    			
				    			var ajaxreq = $.ajax({
				    				url:"includes/handlers/" + pageName,
					        		type:"POST",
					    			data:"page=" + page + "&userLoggedIn=" + userLoggedIn,
					    			cache: false,
					    			success: function(){
					    				// Remove current .nextPage (hidden input)
					    				$('.dropdown_data_window').find('.nextpageDropdownData').remove();
					    			}
				    			});
				    	}
				    	
				    	return false;
					});
				});
			</script>
        <div class="wrapper">