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
        <?php
            $swirl = new Swirl($con, $userLoggedIn);
            $swirl->getSingleSwirl($id);
        ?>
    </div>
</div>

<!-- Trending words column -->
<div class="user_details column">
    <h4>Popular</h4>
    <div class="trends">
        <?php 
            $sql = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");
            
            foreach($sql as $r){
                $query=$r['title'];
			    $wdot=strlen($query)>=14 ? "....":"";
			    $sp_t=str_split($query,14);
			    $sp_t=$sp_t[0];
			    
			    echo '<div style="padding:1px;">';
			    echo $sp_t."".$wdot;
			    echo '<div style="margin-top:5px;"></div>'; 
			    echo "</div>";
            }
        ?>
    </div>
</div>

<?php //include("includes/footer.php");?>