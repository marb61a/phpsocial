<?php
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