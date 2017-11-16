<?php
    // This turns on out put buffering
    ob_start();
    session_start();
    
    // Sets the default timezone
    $timezone = date_default_timezone_set("Europe/Dublin");
    
    $con = mysqli_connect();
    
    if (mysqli_connect_errno()) {
        echo "Failed to connect: ".mysqli_connect_errno();
    }
?>