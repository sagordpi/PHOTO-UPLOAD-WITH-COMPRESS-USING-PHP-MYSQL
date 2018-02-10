<?php
	//include("formeu/config/confg.php");
	//include("session_check.php");
	//$user_id = $_SESSION["user_id"];
	
	date_default_timezone_set("Asia/Dhaka");
	set_include_path(dirname(__FILE__));
	include('image_lib.php');
	//@System Analyst/Programmer : Md. Saiful Islam Sagor.
	//@Web Developer/Designer : Md. Sazzad Hossain Salim
	//@Author : StarFDC
	//@Cell : +88 01922 52 44 69
	//@Website : www.starfdc.com
	$date      = date("Y-m-d");
	$date_time = date("Y-m-d g:i:s a");
	// IMAGE UPLOAD ///////////////////////
	$error      = 0;
	$message    = 'done';
	$field_name = 'photo_upload';
	$folder     = "photo/orginal/";
	$valid_ext  = ['jpg', 'jpeg', 'png'];
	if (!empty($_FILES[$field_name]['name'])) {
		$ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, $valid_ext)) {
			$error   = 1;
			$message = 'Please upload a valid image ';
		}
		$max_size   = 1048576 * 8;
		$image_size = number_format($_FILES[$field_name]['size'] / 1024, 2);
		if ($image_size > $max_size) {
			$error   = 1;
			$message = 'you can not upload more then 8MB file';
		}
		if ($error == 0) {
			$new_name   = time() . '_' . $_FILES[$field_name]['name'];
			$upload_dir = $folder . $new_name;
			$path       = $_FILES[$field_name]['tmp_name'];
			$type       = pathinfo($path, PATHINFO_EXTENSION);
			/*			$data       = file_get_contents($path);
						$data       = 'data:image/' . $type . ';base64,' . base64_encode($data);
						list($type, $data) = explode(';', $data);
						list(, $data) = explode(',', $data);
						$data   = base64_decode($data);
						$upload = file_put_contents($upload_dir, $data);*/
			$upload = true;
			if ($upload) {
				$destination = 'photo/' . $new_name;
				$config      = ['image_library'  => 'gd2',
				                'source_image'   => $_FILES[$field_name]['tmp_name'],
				                'new_image'      => $upload_dir,
				                'maintain_ratio' => true,
				                'width'          => 600,
				                'override'       => true,
				                'quality'        => 60,
				                'height'         => ''];
				$img         = new ci_Image_lib();
				$img->initialize($config);
				$img->resize();
				$counter = 0;
				compress($upload_dir, $destination, 70, $counter);
				unlink($upload_dir);
			}
		}
	}
	/**
	 * @param $source
	 * @param $destination
	 * @param $quality
	 * @param $counter
	 */
	function compress($source, $destination, $quality, $counter) {
		$counter++;
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);
		if (file_exists($destination)) {
			unlink($destination);
		}
		imagejpeg($image, $destination, $quality);
		$file_size = filesize($destination);
		$file_size = intval($file_size / 1024, 2);
		if ($counter < 30) {
			if ($file_size > 50) {
				compress($source, $destination, $quality - 5, $counter);
			}
		}
	}
	
	//$insert_action = mysqli_query($con, "INSERT INTO `gallery_photos` (`id`, `user_id`, `photo_upload`) VALUES (NULL, '$user_id', '$new_name')");
?>
<script>
	alert("Success");
	location.replace("index.php");
</script>

