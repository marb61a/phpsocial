<?php
    include("../../config/config.php");
    include("../../includes/classes/User.php"); 

    $query = $_POST['query'];
    $userLoggedIn = $_POST['userLoggedIn'];


    $names = explode(" ", $query);
    
    // If a query contains an underscore assume its the user searching user names
    if (strpos($query,'_') !== false){
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
    }
    //If query has two words, assume they are first and last names respectively
    else if(count($names) == 2){
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') 
            AND user_closed='no' LIMIT 8");
    }
    //If query has one word only, search first names or last names
    else{
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') 
            AND user_closed='no' LIMIT 8");    
    }
    
    if($query != ""){
        while($usersReturnedArray = mysqli_fetch_array($usersReturned)){
            $user = new User($con, $userLoggedIn);
             
            // If the user found is not user_logged_in get mutual friends
            if($usersReturnedArray['username'] != $userLoggedIn){
                $mutual_friends = $user->getMutualFriends($usersReturnedArray['username']). " friends in common";
            } else {
                $mutual_friends = "";
            }
            
            // If the user is friends with the user found
            if($user->isFriend($usersReturnedArray['username'])){
                echo "<div class='resultDisplay'>
                    <a href='messages.php?u=".$usersReturnedArray['username']."' style='color: #1485BD;'>
                        <div class='liveSearchProfilePic'>
                            <img src='".$usersReturnedArray['profile_pic']."'>
                        </div>
                        <div class='liveSearchText'>
                            ".$usersReturnedArray['first_name']." ".$usersReturnedArray['last_name']."
                            <p>".$usersReturnedArray['username']."</p>
                            <p id='grey'>".$mutual_friends." </p>
                        </div>
                    </a>
                </div>";
            }
        }
    }
?>