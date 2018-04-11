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
                if(count($names) == 3){
                    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%')
                    AND user_closed='no'");
                } else if (count($names) == 2){
                    // If the query has 2 words assume they are the first and last names
                    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') 
                    AND user_closed='no'");
                } else {
                    // If the query has only one word search both first and last names
                    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') 
                    AND user_closed='no'");
                }
    		}
            
            // Check if results were found
            if(mysqli_num_rows($user_details_query) == 0){
                echo "We can't find anyone with a ".$type." like: ".$query;
            } else {
                // Show a message with the number of results found
                echo mysqli_num_rows($user_details_query)." results found:<br><br>";
            }
            
            echo "<p id='grey'>Try searching for:</p>";
		    echo "<a href='search.php?q=".$query."&type=name'>Names</a>, <a href='search.php?q=".$query."&type=username'>Usernames</a><br><br><hr>";
		    
		    while($user_found = mysqli_fetch_array($user_details_query)){
		        $user_object = new User($con, $user['username']);
		        
		        // Declare a variable for the friend button
		        $button = "";
		        
		        // Declare a variable for mutual friends 
		        $mutual_friends = "";
		        
		        // If user_found is not their own profile
		        if($user['username'] != $user_found['username']){
		            // Generate a friend button depending on the status of the friendship
		            if($user_object->isFriend($user_found['username'])){
		                // If the users are friends then show the remove friend button
		                $button = '<input type="submit" name="'.$user_found['username'].'" class="danger" value="Remove Friend" style="width:175px;">';
		                
		            } else if($user_object->didReceiveRequest($user_found['username'])){
		                // If a request has been received from the profile user
		                $button = '<input type="submit" name="'.$user_found['username'].'" class="warning" value="Respond to Request" style="width:175px;">';
		                
		            } else if($user_object->didSendRequest($user_found['username'])){
		                // If a request has already been sent to the profile user and is awaiting response
		                $button = '<input type="submit" name="" class="default" value="Request Sent">';
		            } else {
		                // If the users are not friends, show the add friend button
		                $button = '<input type="submit" name="'.$user_found['username'].'" class="success" value="Add Friend">';
		                
		                // User specific form to handle a button press
		                if(isset($_POST[ $user_found['username'] ])){
		                    // Check friendship status
		                    if ($user_object->isFriend($user_found['username'])){
		                        $user_object->removeFriend($user_found['username']);
							    header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
							    
							  // If a request has been received  
		                    } else if ($user_object->didReceiveRequest($user_found['username'])){
		                        // Redirect to the requests page
		                        header("Location: requests.php");
		                        
		                        // If a request has been sent
		                    } else if ($user_object->didSendRequest($user_found['username'])) {
		                        // Do nothing
		                        
		                        // Perform the add friend operation
		                    } else {
		                        $user_object->sendRequest($user_found['username']);
							    header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		                    }
		                }
		            }
		            
		            echo "<div class='search_result'> 
		                <div class='searchPageFriendButtons'>
		                    <form action='' method='POST'>
		                        ".$button."
							    <br>
		                    </form>
		                </div>
		                <div class='result_profile_pic'>
		                    <a href='".$user_found['username']."'>
		                        <img src='".$user_found['profile_pic']."' style='height: 100px;'>
		                    </a>
		                </div>
		                <a href='".$user_found['username']."'>".$user_found['first_name']." ".$user_found['last_name']."<br>
					    <p id='grey'>".$user_found['username']."</p>
					    </a><br>
					    ".$mutual_friends."<br>
		            </div>
		            <hr />";
		        }
		    }
        }
    ?>
</div>

<?php include("includes/footer.php");?>