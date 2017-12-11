<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    </head>
    <body>
        <style type="text/css">
            * {
                -webkit-transition: all 0s ease-in-out;
  			    -moz-transition: all 0s ease-in-out;
            }
            
            body {
                background-color: #fff;
            }
            
            input [type="submit"] {
                background-color: transparent;
                border: none;
                height: auto;
                width: auto;
                margin: 0;
                color: #3598db;
            }
            input [type="submit"]:active {
                background-color: transparent;
                border: none;
                height: auto;
                width: auto;
                margin: 0;
                color: #3598db;
                padding: 0;
            }
        </style>
        <?php
            include("config/config.php");
    		include("./includes/classes/User.php");
    		include("./includes/classes/Message.php");
    		include("./includes/classes/Swirl.php");
    		include("./includes/classes/Notification.php");
        ?>
    </body>
</html>