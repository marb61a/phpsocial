<?php
	include("../../config/config.php");
	include("../classes/User.php");
	include("../classes/Notification.php");
	include("../classes/Swirl.php");

	if(isset($_POST['post_body'])){
		//Check if user is on mobile device
		if ($iphone || $android || $palmpre || $ipod || $berry) 
			$is_mobile = "yes";
		else
			$is_mobile = "no";

		$swirl = new Swirl($con, $_POST['user_from']); 
		$swirl->postSwirl($_POST['post_body'], 'no', $_POST['user_to'], $is_mobile);
	}
?>