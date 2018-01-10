<?php
    include("../../config/config.php");
    include("../classes/User.php");
    include("../classes/Swirl.php");
    
    //Number of rows to be loaded
    $limit = 10; 
    
    $swirls = new Swirl($con, $_REQUEST['userLoggedIn']);
    //Call load profile posts function
    $swirls->loadPostsProfilePage($_REQUEST, $limit); 
?>