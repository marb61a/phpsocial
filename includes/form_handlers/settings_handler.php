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
?>