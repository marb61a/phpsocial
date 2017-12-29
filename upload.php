<?php
    include("includes/header.php");
    
    $profile_id = $user['username'];
    $imgSrc = "";
    $result_path = "";
    $msg = "";
    
    /***********************************************************
        Step 1 - Remove The Temp image, if it exists
    ***********************************************************/
    if(isset($_POST['x']) && !isset($_FILES['image']['name'])){
        // Delete the users temp image
        $temppath = 'assets/images/profile_pics/'.$profile_id.'_temp.jpeg';
        if(file_exists($temppath)){
            @unlink($temppath);
        }
    } 
    
    /***********************************************************
    	Step 2 - Upload Original Image To Server
    ***********************************************************/
    if(isset($_FILES['image']['name'])){
        // Get the name, size & temp location
        $ImageName = $_FILES['image']['name'];
        $ImageSize = $_FILES['image']['size'];
        $ImageTempName = $_FILES['image']['temp_name'];
    }
?>