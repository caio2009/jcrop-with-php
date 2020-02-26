<?php
	
	// Get image to crop
	$imageToCrop = imagecreatefromjpeg($_FILES['image_to_crop']['tmp_name']);

	/* Test values
	$x = 0;
	$y = 0;
	$width = 200;
	$height = 200;
	*/

	$x = $_POST['x'];
	$y = $_POST['y'];
	$width = $_POST['width'];
	$height = $_POST['height'];

	// Cropping
	$croppedImage = imagecrop($imageToCrop, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

	if ($_FILES['image_to_crop']['error'] === 0) {
		if ($croppedImage !== FALSE) {
			imagejpeg($croppedImage);
			imagedestroy($croppedImage);
			// echo "x: ".$x.", y: ".$y.", width: ".$width.", height: ".$height; /* test */
		}
		else {
			echo 'Failed cropping image.';
		}
	}
	else {
		echo 'Error loading image: '.$_FILES['image_to_crop']['name'];
	}

	imagedestroy($imageToCrop);

?>