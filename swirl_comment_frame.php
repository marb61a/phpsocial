<html>
    <head>
        <title></title>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">    
    </head>
    <body>
        <?php
            include("config/config.php");
    		include("./includes/classes/User.php");
    		include("./includes/classes/Message.php");
    		include("./includes/classes/Swirl.php");
    		include("./includes/classes/Notification.php");
    		
    		if($_SESSION["username"]){
    		    $userLoggedIn = $_SESSION["username"];
    		    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    		    $user = mysqli_fetch_array($user_details_query);
    		} else {
    		    ("Location: register.php");
    		}
        ?>
        <style type="text/css">
            * {
                font-size: 12px;
			    font-family: Arial, Helvetica, Sans-serif;
            }
            
            hr {
                background-color: #fff;
                margin-top: 12px;
                height: 1px;
                border: 0px ;
            }
            
            body {
                min-width: 100%;
			    background-color: #ECF0F1;
            }
        </style>
    </body>
</html>