<?php
    // This turns on out put buffering
    ob_start();
    session_start();
    
    // Sets the default timezone
    $timezone = date_default_timezone_set("Europe/Dublin");
    
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "c9";
    $dbport = 3306;

    // Create connection
    $con = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    // if ($con->connect_error) {
    //     die("Connection failed: " . $db->connect_error);
    // } 
    // echo "Connected successfully (".$con->host_info.")";
    
    // $con = mysqli_connect("localhost", "root", "","socialmedia");
    
    if (mysqli_connect_errno()) {
        echo "Failed to connect: ".mysqli_connect_errno();
    }
?>