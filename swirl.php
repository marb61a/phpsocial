<?php
    // Includes the header  
    include("includes/header.php"); 
    
    // Get id from the url
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
?>

<!-- A column for logged in users on the left --->
<div class="user_details column">
    <a href="<?php echo $user['username']; ?>">
        <img src="<?php echo $user['profile_pic']; ?>">
    </a>
    <div class="user_details_left_right">
        <br>
        <a href="<?php echo $user['username']; ?>">
            <?php echo $user['first_name']." ".$user['last_name']; ?>
        </a>
        <br>
        Posts: <?php echo $user['num_posts']; ?>
        <br>
        Bumps: <?php echo $user['num_bumps']; ?>
		<br>
    </div>
</div>

<div class="main_column column" id="main_column">
    <div class="swirls_area">
        
    </div>
</div>