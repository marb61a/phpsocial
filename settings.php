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
</div>