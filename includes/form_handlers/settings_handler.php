<?php
    if(isset($_POST['submit'])){
        $first_name = $_POST['first_name'];
    	$last_name = $_POST['last_name'];
    	$email = $_POST['email'];
    	
    	// Check if a new email is already registered
    	$email_check_query = mysqli_query($con, "SELECT username FROM users WHERE email='$email'");
        $row = mysqli_fetch_assoc($email_check_query);
        $matched_user = $row['username'];
        
        if($matched_user == "" || $matched_user == $userLoggedIn){
            $message = "Details updated!<br><br>";
            $query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email'WHERE username='$userLoggedIn' ");
        } else {
            $message = "That email is already in use!<br><br>";
        }
    	
    } else {
        $message = "";
    }
    
    if(isset($_POST['close_account'])){
    	header("Location: close_account.php");
    }
    
    // Close the account
    if(isset($_POST['submit_password'])){
        // Set some password variables
        $oldPassword = strip_tags($_POST['old_password']);
    	$newPassword = strip_tags($_POST['new_password_1']);
    	$repeatPassword = strip_tags($_POST['new_password_2']);
    	
    	$passwordQuery = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
    	$row = mysqli_fetch_assoc($passwordQuery);
    	$dbPassword = $row['password'];
    	
    	// Use MD5 on the old password before checking to see if it matches
    	$oldPasswordMD5 = md5($oldPassword);
    	
    	// Check to see if oldPassword equals dbPassword
    	if($oldPasswordMD5 == $dbPassword){
    	    // Continue changing the users password and check whether the 2 new passwords match
    	    if($newPassword == $repeatPassword){
    	        if(strlen($newPassword) <= 4){
    	            $password_message = "Sorry, your password must be at least 5 characters long!<br><br>";    
    	        } else {
    	            
    	        }
    	    } else {
    	        
    	    }
    	} else {
    	    
    	}
    } else {
        $password_message = "";
    }
?>