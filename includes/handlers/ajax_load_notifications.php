<?php
    include("../../config/config.php");
    include("../classes/User.php");
    include("../classes/Notification.php");
    
    //Number of rows to be loaded
    $limit = 7; 
    
    $notification = new Notification($con, $_REQUEST['userLoggedIn']);
    $notification->getNotifications($_REQUEST, $limit);
?>