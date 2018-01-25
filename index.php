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
    
    $(document).ready(function(){
        // Show the loading icon
        $('#loading').show();
        
        // Original AJAX request for loading first posts
        $.ajax({
            url:"includes/handlers/ajax_load_posts.php",
		    type:"POST",
		    data:"page=1&userLoggedIn=" + userLoggedIn,
		    cache: false,
		    
		    success: function(data){
		        // Hide loading icon
		        $('#loading').hide();
		        
		        // Insert the returned data into the div
		        $('.swirls_area').html(data);
		    }
        });
        
        $(window).scroll(function(){
            // Get the height of the div containing posts
            var height = $('..swirls_area').height();
            var scroll_top = $(this).scrollTop();
            var page = $('.swirls_area').find('.nextpage').val();
		    var noMorePosts = $('.swirls_area').find('.noMorePosts').val();
		    
		    if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
		        // Show the loading icon
		        $('#loading').show();
		        
		        var ajaxreq = $.ajax({
		            url:"includes/handlers/ajax_load_posts.php",
			        type:"POST",
			        data:"page=" + page + "&userLoggedIn=" + userLoggedIn,
			        cache:false,
			        
			        success: function(){
			            // Remove the  current .nextPage (hidden input)  
			            $('.swirls_area').find('.nextPage').remove();
			            
			            // Remove the current .noMorePosts (hidden input)
			            $('.swirls_area').find('.noMorePosts').remove();
			            
			            // Hide the loading icon
			            $('#loading').hide();
			            
			            // Append with new posts
			            $('.swirls_area').append(response);
			        }
		        });
		    }
		    
		    return false;
        });
    }); // End of the document.ready
    
</script>

<?php //include("includes/footer.php");?>
