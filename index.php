<?php
    // Includes the header  
    include("includes/header.php");
    
    
?>

<!-- column for logged in user details on the left -->
<div class="user_details column">
    <a href="<?php echo $user['username']; ?>">
        <img src="<?php echo $user['profile_pic']?>">
    </a>
    <div class="user_details_left_right">
        <br>
        <a href="<?php echo $user['username']; ?>"><?php echo $user['first_name']." ".$user['last_name']; ?></a>
		<br>
		    Posts: <?php echo $user['num_posts']; ?>
		<br>
		    Bumps: <?php echo $user['num_bumps']; ?>
		<br>
    </div>
</div>

<div class="main_column column" id="main_column">
    <form class="">
        
    </form>
    <br>
	<hr style="margin-bottom: 15px;"/>
	
</div>

<!-- Trending Words Column-->
<div class="user_details column">
    <h4>Popular</h4>
    <div class="trends">
        <?php
            $sql = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");
            foreach ($sql as $r) {
                
            }
        ?>        
    </div>
</div>

<!-- Script for infinite scrolling and for loading posts -->
<script>
    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
</script>
