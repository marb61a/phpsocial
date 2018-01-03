<?php 
	function email_exists($email, $con){
		$e_check = mysqli_query($con, "SELECT `email` FROM `users` WHERE `email`='$email'");
		//Count the number of rows returned 
		$email_check = mysqli_num_rows($e_check);
		
		return ($email_check == 0) ? false : true;
	}
?>