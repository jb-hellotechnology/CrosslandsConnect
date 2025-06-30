<?php

include('../secrets.php');

include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
	$uploadDir = '../uploads/'; // Directory to store uploaded files
	$uploadedFile = $uploadDir . $_POST['contact_id'].'.jpg';
	$uploadOk = true;

	// Check if the file is an image
	$imageFileType = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));
	if (!getimagesize($_FILES['photo']['tmp_name'])) {
		$message = '<p class="alert error">File upload failed</p>';
		$uploadOk = false;
	}

	// Check file size (adjust this value as needed)
	if ($_FILES['photo']['size'] > 5 * 1024 * 1024) { // 5MB limit
		$message = '<p class="alert error">5MB limit exceeded</p>';
		$uploadOk = false;
	}

	// Allow only certain file formats (e.g., jpg, png)
	if ($imageFileType != 'jpg' && $imageFileType != 'jpeg') {
		$message = '<p class="alert error">Only JPG files allowed</p>';
		$uploadOk = false;
	}

	if ($uploadOk) {
		if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadedFile)) {
			$message = '<p class="alert success">Profile updated</p>';
			$file = $uploadedFile;
			resize_crop_image(1024, 1024, $uploadedFile, $uploadedFile);
		} else {
			$message = '<p class="alert error">Failed to upload file</p>';
		}
	}
}

$CONTACT_ID 	= $_POST['contact_id'];
$bio			= strip_tags($_POST['biography']);
$image 			= $file;
$share_phone 	= $_POST['share_phone'];
$share_email 	= $_POST['share_email'];
	
if($file){
	$sql = "UPDATE contacts SET bio='".$bio."', image='".$image."', share_phone='".$share_phone."', share_email='".$share_email."' WHERE CONTACT_ID='".$CONTACT_ID."'";
}else{
	$sql = "UPDATE contacts SET bio='".$bio."', share_phone='".$share_phone."', share_email='".$share_email."' WHERE CONTACT_ID='".$CONTACT_ID."'";
}
$params = array();
$result = $database->query($sql, $params);

$response = json_encode(array("message"=>$message, "file"=>$file.'?v='.rand()));
echo $response;

//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
	$imgsize = getimagesize($source_file);
	$width = $imgsize[0];
	$height = $imgsize[1];
	$mime = $imgsize['mime'];
 
	switch($mime){
		case 'image/gif':
			$image_create = "imagecreatefromgif";
			$image = "imagegif";
			break;
 
		case 'image/png':
			$image_create = "imagecreatefrompng";
			$image = "imagepng";
			$quality = 7;
			break;
 
		case 'image/jpeg':
			$image_create = "imagecreatefromjpeg";
			$image = "imagejpeg";
			$quality = 80;
			break;
 
		default:
			return false;
			break;
	}
	 
	$dst_img = imagecreatetruecolor($max_width, $max_height);
	$src_img = $image_create($source_file);
	 
	$width_new = $height * $max_width / $max_height;
	$height_new = $width * $max_height / $max_width;
	//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
	if($width_new > $width){
		//cut point by height
		$h_point = (($height - $height_new) / 2);
		//copy image
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	}else{
		//cut point by width
		$w_point = (($width - $width_new) / 2);
		imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	}
	 
	$image($dst_img, $dst_dir, $quality);
 
	if($dst_img)imagedestroy($dst_img);
	if($src_img)imagedestroy($src_img);
}
//usage example

?>