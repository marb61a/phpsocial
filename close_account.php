<?php
    include("includes/header.php");
    
    // The cancel button was pressed
    if(isset($_POST['cancel'])){
    	header("Location: settings.php");
    }
    
    // The close account button was pressed
    if(isset($_POST['close_account'])){
        $close_account = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
        session_destroy();
        header("Location: register.php");
    }
?>

<div class="main_column column" id="main_column">
    <h4>Close Account</h4>
    Are you sure you want to close your account?<br><br>
	Closing your account will hide your profile and all your activity from other users.<br><br>
	You can re-open your account at anytime by simply logging in again. This will make all your existing activity visible again. <br>
	
	<form action="close_account.php" method="POST">
	    <input type="submit" name="close_account" id="close_account" value="Yes. Close it!">				
		<input type="submit" name="cancel" id="save_details" value="Don't Close!">
	</form>
</div>

<?php //include("includes/footer.php");?>