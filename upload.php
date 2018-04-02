<?php
    include("includes/header.php");
    
    $profile_id = $user['username'];
    $imgSrc = "";
    $result_path = "";
    $msg = "";
    
    // Step 1 - Remove The Temp image, if it exists
    if(isset($_POST['x']) && !isset($_FILES['image']['name'])){
        // Delete the users temp image
        $temppath = 'assets/images/profile_pics/'.$profile_id.'_temp.jpeg';
        if(file_exists($temppath)){
            @unlink($temppath);
        }
    } 
    
    // Step 2 - Upload Original Image To Server
    if(isset($_FILES['image']['name'])){
        // Get the name, size & temp location
        $ImageName = $_FILES['image']['name'];
        $ImageSize = $_FILES['image']['size'];
        $ImageTempName = $_FILES['image']['temp_name'];
        
        // Get the file extension
        $ImageType = @explode('/', $_FILES['image']['type']);
        // File Type
        $type = $ImageType[1];
        // Set the upload directory
        $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/socialmedia/assets/images/profile_pics';
        // Set the file name, starting with the temporary file name 
        $file_temp_name = $profile_id.'_original.'.md5(time()).'n'.$type;
        // The temp file path
        $fullpath = $uploaddir."/".$file_temp_name;
        $file_name = $profile_id.'_temp.jpeg';
        // For the final resized image
        $fullpath_2 = $uploaddir."/".$file_name;
        
        // Move the file to the proper location
        $move = move_uploaded_file($ImageTempName, $fullpath);
        // Ensure right permissions
        chmod($fullpath, 0777); 
        // Check for a valid upload
        if(!$move){
            die("There was a problem with the file upload");
        } else {
            // The image to display in the crop area
            $imgSrc= "assets/images/profile_pics/".$file_name;
            // A message to the page
            $msg= "Upload Complete!";
            // The file name to post from the cropping form to the resize
            $src = $file_name;
        }
        
        // Step 3 - Resize The Image To Fit In Cropping Area
        // Get the uploaded image size
        clearstatcache();				
		$original_size = getimagesize($fullpath);
		$original_width = $original_size[0];
		$original_height = $original_size[1];
		
		// Specify the new size
		$main_width = 500;
		$main_height = $original_height / ($original_width / $main_width);
		
		// Create a new image using the correct php function
		if($_FILES["image"]["type"] == "image/gif"){
		    $src2 = imagecreatefromgif($fullpath);
		} else if($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
		    $src2 = imagecreatefromjpeg($fullpath);
		} else if($_FILES["image"]["type"] == "image/png"){
		    $src2 = imagecreatefrompng($fullpath);
		} else {
		    $msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
		}
		
		// Create the new resized image
		$main = imagecreatetruecolor($main_width, $main_height);
		imagecopyresampled($main,$src2,0, 0, 0, 0,$main_width,$main_height,$original_width,$original_height);
		
		// Upload the new version
		$main_temp = $fullpath_2;
		imagejpeg($main, $main_temp, 90);
		chmod($main_temp,0777);
		
		// Free up memory
		imagedestroy($src2);
		imagedestroy($main);
		
		// Delete the original 
		@ unlink($fullpath);
    }
    
    // Step 4 Cropping & Converting The Image To Jpg
    if(isset($_POST['x'])){
        // The type of file posted
        $type = $_POST['type'];
        
        // The image source
        $src = 'assets/images/profile_pics/'.$_POST['src'];	
		$finalname = $profile_id.md5(time());
		
		if($type == 'jpg' || $type == 'jpeg' || $type == 'JPG' || $type == 'JPEG'){
		    // Target dimensions
		    $targ_w = $targ_h = 150;
		    
		    // The quality of the output
		    $jpeg_quality = 90;
		    
		    // Create a cropped copy of the image
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
		    // Save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 
			
		} else if($type == 'png' || $type == 'PNG'){
		    // Target dimensions
		    $targ_w = $targ_h = 150;
		    
		    // The quality of the output
		    $jpeg_quality = 90;
		    
		    // Create a cropped copy of the image
		    $img_r = imagecreatefrompng($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
			// Save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 
			
		} else if($type == 'gif' || $type == 'GIF'){
		    //the target dimensions 150x150
			$targ_w = $targ_h = 150;
			
		    // The quality of the output
			$jpeg_quality = 90;
			
    		// Create a cropped copy of the image
			$img_r = imagecreatefromgif($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
		    // Save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 
			
		}
		
		// Free up memory
		imagedestroy($img_r);
		imagedestroy($dst_r);
		
		// Delete the original image 
		@unlink($src);
		
		//return cropped image to page	
		$result_path ="assets/images/profile_pics/".$finalname."n.jpeg";

		//Insert image into database
		$insert_pic_query = mysqli_query($con, "UPDATE users SET profile_pic='$result_path' WHERE username='$userLoggedIn'");
		header("Location: ".$userLoggedIn);
		
    }
?>

<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;"></div>
<div class="main_column column">
	<div id="formExample">
		<p><b> <?=$msg?> </b></p>
		<form action="upload.php" method="post"  enctype="multipart/form-data">
			Upload something<br /><br />
	        <input type="file" id="image" name="image" style="width:200px; height:30px; " /><br /><br />
	        <input type="submit" value="Submit" style="width:85px; height:25px;" />
		</form>
	</div>

	<?php
		 //if an image has been uploaded display cropping area
		if($imgSrc){
	?>
	
	<script>
		$('#Overlay').show();
		$('#formExample').hide();
	</script>
	
	<div id="CroppingContainer" style="width:800px; max-height:600px; background-color:#FFF; margin-left: 
		-200px; position:relative; overflow:hidden; border:2px #666 solid; z-index:2001; padding-bottom:0px;" >
		<div>
			
		</div>
	</div>
	<?php  } ?>
</div>