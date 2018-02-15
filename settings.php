<?php
    include("includes/header.php");
    require 'includes/form_handlers/settings_handler.php';
?>

<div class="main_column column" id="main_column">
    <h4>Account Settings</h4> 
        Profile Picture
    <?php
        echo "<img src=".$user['profile_pic']." id='small_profile_pic'>";
    ?>
    <br>
    <a href="upload.php">Upload new profile picture</a><br><br><br><br>
    
    Modify the values and click 'Update Details'
	<br><br>
	<?php
	    //This gets the updated first name, last name and email
	    $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
	    $user_data_row = mysqli_fetch_assoc($user_data_query);
	    
	    $first_name = $user_data_row['first_name'];
		$last_name = $user_data_row['last_name'];
		$email = $user_data_row['email'];
	?>
	
	<form action="settings.php" method="POST">
	    First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" required /><br><br>
	    Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" required /><br><br>
	    Email: <input type="text" name="email" value="<?php echo $femail; ?>" required /><br><br>
	    <?php echo $message; ?>
	    
	    <input type="submit" name="submit" id="save_details" value="Update Details">
	</form>
	<hr />
	
	<h4>Change Password</h4>
	<form action="settings.php" method="POST">
	    Old Password: <input type="password" name="old_password" required/><br><br>
		New Password: <input type="password" name="new_password_1" required/><br><br>
		Repeat New Password : <input type="password" name="new_password_2" required/><br><br>
	    <?php echo $password_message; ?>
	    
	    <input type="submit" name="submit_password" id="save_details" value="Update Password">
	</form>
	<hr />
	
	<h4>Close Account</h4>
	<form action="settings.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Close Account">				
	</form>
</div>

<?php //include("includes/footer.php");?>