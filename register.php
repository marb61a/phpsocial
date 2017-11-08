<?php
    session_start();
    
    $con = mysqli_connect();
    
    if (mysqli_connect_errno()) {
        echo "Failed to connect: ".mysqli_connect_errno();
    }
    
    $query = mysqli_query();
    
    // Declare variables in order to prevent errors occuring
    $fname = "";                // First Name
    $lname = "";                // Last Name
    $em = "";                   // Email Address
    $em2 = "";                  // Email Address Confirmation
    $password = "";             // Password
    $password2 = "";            // Password Confirmation
    $date = "";                 // User sign up date
    $error_array = array();     // Will hold any error messages
    
    if(isset($_POST['register_button'])){
        // The registration form values
        // First Name
        $fname = strip_tags($_POST['reg_fname']);           // Removes the HTML tags
        $fname = str_replace(' ', '', $fname);              // Remove spaces
        $fname = ucfirst(strtolower($fname));               // First letter to uppercase
        $_SESSION['reg_fname'] = $fname;                    // Stores the first name into a session variable
        
        // Last Name
        $lname = strip_tags($_POST['reg_lname']);           // Removes the HTML tags
        $lname = str_replace(' ', '', $lname);              // Remove spaces
        $lname = ucfirst(strtolower($lname));               // First letter to uppercase
        $_SESSION['reg_lname'] = $lname;                    // Stores the last name into a session variable
        
        // Email
        $em = strip_tags($_POST['reg_email']);              // Removes the HTML tags
        $em = str_replace(' ', '', $em);                    // Remove spaces
        $em = ucfirst(strtolower($em));                     // First letter to uppercase
        $_SESSION['reg_email'] = $em;                       // Stores the email address into a session variable
        
        // Email2 (confirmation)
        $em2 = strip_tags($_POST['reg_em2']);               // Removes the HTML tags
        $em2 = str_replace(' ', '', $em2);                  // Remove spaces
        $em2 = ucfirst(strtolower($em2));                   // First letter to uppercase
        $_SESSION['reg_email2'] = $em2;                     // Stores the repeat email address into a session variable
        
        // Password
        $password = strip_tags($_POST['reg_password']);     // Removes the HTML tags
        $password2 = strip_tags($_POST['reg_password2']);   // Removes the HTML tags
        
        $date = date("Y-m-d");                              // Current date
        
        if($em == $em2){
            // Ensure that the email is in a proper format
            if(filter_var($em, FILTER_VALIDATE_EMAIL)){
                $em = filter_var($em, FILTER_VALIDATE_EMAIL);
                
                // Check to see if the email address already exists
                $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em");
                
                // Count the number of rows that were returned
                $num_rows = mysqli_num_rows($e_check);
                
                if($num_rows > 0){
                    array_push($error_array, "That Email address is already in use<br>");
                }
            }
            else {
                 array_push($error_array, "Invalid Format<br>");
            }
        }
        else {
             array_push($error_array, "The Email Addresses Do Not Match");
        }
        
        if(strlen($fname) > 25 || strlen($fname) < 2){
             array_push($error_array, "Your first name must be between 2 and 25 characters long");
        }
        
        if(strlen($lname) > 25 || strlen($lname) < 2){
             array_push($error_array, "Your last name must be between 2 and 25 characters long");
        }
        
        if($password != $password2){
            echo "Your passwords must match";
        }
        else{
            if(preg_match('/[^A-Za-z0-9]/', $password)){
                 array_push($error_array, "Your password can only contain English characters and numbers");
            }
        }
        
        if(strlen($password) > 25 || strlen($password) < 5){
             array_push($error_array, "Your password must be between 5 and 30 characters long");
        }
    }
?>

<html>
    <head>
        <title>Welcome to PHPSocial</title>
    </head>
    <body>
        <form action="register,php" method="POST">
            <input type="text" name="reg_fname" placeholder="First Name" <?php 
                if(isset($_SESSION['reg_fname'])){
                    echo $_SESSION['reg_fname'];
                }
            ?> required>
            <br>
            <?php if(in_array("Your first name must be between 2 and 25 characters long<br>", $error_array)) echo "Your first name must be between 2 and 25 characters long<br>";?>
            
            <input type="text" name="reg_lname" placeholder="Last Name" <?php 
                if(isset($_SESSION['reg_lname'])){
                    echo $_SESSION['reg_lname'];
                }
            ?> required>
            <br>
            <?php if(in_array("Your last name must be between 2 and 25 characters long<br>", $error_array)) echo "Your last name must be between 2 and 25 characters long<br>";?>
            
            <input type="email" name="reg_email" placeholder="EMail" <?php 
                if(isset($_SESSION['reg_email'])){
                    echo $_SESSION['reg_email'];
                }
            ?> required>
            <br>
            <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";?>
            
            <input type="email" name="reg_email2" placeholder="Confirm Email" <?php 
                if(isset($_SESSION['reg_email2'])){
                    echo $_SESSION['reg_email2'];
                }
            ?> required>
            <br>
            
            <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
            else if(in_array("Invalid Email format<br>", $error_array)) echo "Invalid Email format<br>";
            else if(in_array("Emails do not match<br>", $error_array)) echo "Emails do not match<br>";?>
            
            <input type="password" name="reg_password" placeholder="Password" required>
            <br>
            <input type="password" name="reg_password2" placeholder="Confirm Password" required>
            <br>
            
            <?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>";
            else if(in_array("Your password can only contain English characters and numbers<br>", $error_array)) echo "Your password can only contain English characters and numberst<br>";
            else if(in_array("Your password must be between 5 and 30 characters long<br>", $error_array)) echo "Your password must be between 5 and 30 characters long<br>";?>
            
            <input type="submit" name="register_button" value="Register">
        </form>   
    </body>
</html>