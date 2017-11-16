<?php
    
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
        
        if(empty($error_array)){
            $password = md5($password);                 // Encrypts the password before it is sent to the database
            
            $username = strtolower($fname."_".$lname);  // Generate a username by concatenating the first and last names
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
            
            $i = 0;
            
            // If the username exists add a number to the username eg JackBlack, JackBlack1 etc
            while(mysqli_num_rows($check_username_query) != 0){
                $i++;                                   // Adds 1 to the value
                $username = $username ."_".$i;
                $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
            }
        
            // Assignment of profile picture
            $rand = rand(1, 2);                             // A random number between 1 and 2
            
            if($rand == 1)
                $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
            else if ($rand == 2)
                $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
            
            $query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname', '$username', '$em', '$packagexml', '$date', '$profile_pic',
                                                        '0', '0', 'no', ',')");
            
            array_push($error_array, "<span style='color:#14C800'>You Are Ready To Login</span><br>");                                            
            
            // Clear Session variables
            $_SESSION["reg_fname"] = "";
            $_SESSION["reg_lname"] = "";
            $_SESSION["reg_email"] = "";
            $_SESSION["reg_email2"] = "";
            
        }
    }
?>