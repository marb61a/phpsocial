<?php
    if(isset($_GET['q'])){
        $query = $_GET['q'];
    } else {
        $query = "";
    }
    
    if (isset($_GET[type])) {
        $type = $_GET['type'];
    } else {
        $type = "name";
    }
    
?>

<div class="main_column column" id="main_column">
    <?php
        if ($query = "") {
            echo "You must enter something in the search box.";
        } else {
            // Search usernames
            if($type == "username"){
    			$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no'");
    		} else {
    		    $names = explode("", $query);
            
                // If the query has 3 words then assume the middle one is a middle name and is to be ignored
                
    		}
            
        }
            
    ?>
</div>