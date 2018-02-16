<?php 
    include("includes/header.php");
?>

<div class="main_column column" id="main_column">
    <h4>Friend Requests</h4>
    <?php
        // Get all requests to the user
        $get_friend_requests = mysqli_query($con, "SELECT user_from FROM friend_requests WHERE user_to='$userLoggedIn'");
        if(mysqli_num_rows($get_friend_requests) == 0){
            echo "You have no friend requests at the moment";
        } else {
            while($row = mysqli_fetch_assoc($get_friend_requests)){
                // The user making request username
                $user_from = $row['user_from'];
                $user_from_details = new User($con, $user_from);
                
                // Get the first and last name of the user making request
                echo $user_from_details->getFirstAndLastName();
                $user_from_friend_array = $user_from_details->getFriendArray();
                
                if(isset($_POST['acceptRequest'.$user_from])){
                    $addFriendQuery = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array,'$user_from,') 
                        WHERE username='$userLoggedIn'");
    				$addFriendQuery = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array,'$userLoggedIn,') 
    				    WHERE username='$user_from'");
    
    				$deleteRequest = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
    				echo "You are now friends!";
    				header("Location: requests.php");
                }
                
                if(isset($_POST['ignoreRequest'.$user_from])){
                    $deleteRequest = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				    echo "Request Ignored";
				    header("Location: requests.php");
                }
    ?>            
                <form action="requests.php" method="POST">
					<input type="submit" name="acceptRequest<? echo $user_from; ?>" id="accept_button" value="Accept">
					<input type="submit" name="ignoreRequest<? echo $user_from; ?>" id="decline_button" value="Ignore">
				</form>
				<br>
				<br>
                < ?php
            }
        }
    ?>
</div>
