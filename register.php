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
    $error_array = "";          // Will hold any error messages
    
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
                    echo "That Email address is already in use";
                }
            }
            else {
                echo "Invalid Format";
            }
        }
        else {
            echo "The Email Addresses Do Not Match";
        }

    }
?>

<html>
    <head>
        <title>Welcome to PHPSocial</title>
    </head>
    <body>
        <form action="register,php" method="POST">
            <input type="text" name="reg_fname" placeholder="First Name" required>
            <br>
            <input type="text" name="reg_lname" placeholder="Last Name" required>
            <br>
            <input type="email" name="reg_email" placeholder="EMail" required>
            <br>
            <input type="email" name="reg_email2" placeholder="Confirm Email" required>
            <br>
            <input type="password" name="reg_password" placeholder="Password" required>
            <br>
            <input type="password" name="reg_password2" placeholder="Confirm Password" required>
            <br>
            <input type="submit" name="register_button" value="Register">
        </form>   
    </body>
</html>