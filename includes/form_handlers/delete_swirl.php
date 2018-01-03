<?php
	include("../../config/config.php");
	
	if(isset($_GET['post_id'])) 
				$post_id = $_GET['post_id'];
	
	if(isset($_POST['result'])){
		if($_POST['result'] == 'true') {
			$delete_query = mysqli_query($con, "UPDATE swirls SET deleted='yes' WHERE id='$post_id'");
		}
	}
?>