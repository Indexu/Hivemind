
<?php
if ($_POST) {

	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	if ($_FILES['file']['size'] !== 0) {

		function make_thumb($src, $dest, $desired_width) {

			/* read the source image */
			$info = new SplFileInfo($src);
  			//$ext = strtolower(getExtension($src));
			$ext = $info->getExtension();

			if ($ext != 'swf') {
				if($ext =='jpg' || $ext =='jpeg' || $ext == 'pjpeg'){
					$source = imagecreatefromjpeg($src);
				}

				if($ext =='gif'){
					$source = imagecreatefromgif($src);
				}

				if($ext =='png' || $ext == 'x-png'){
					$source = imagecreatefrompng($src);
				}

				$width = imagesx($source);
				$height = imagesy($source);

				/* find the "desired height" of this thumbnail, relative to the desired width  */
				$desired_height = floor($height * ($desired_width / $width));

				/* create a new, "virtual" image */
				$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

				/* copy source image at a resized size */
				imagecopyresampled($virtual_image, $source, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

				/* create the physical thumbnail image to its destination */
				imagejpeg($virtual_image, $dest);
			}
			
		}

		$root = $_SERVER['DOCUMENT_ROOT'];

		$query = "SELECT threads.postID as 'postID' FROM threads";
		$result = $pdo->query($query); 
		$p = array();
		foreach ($result as $row) { 

			array_push($p, $row['postID']);

		}
		if (count($p) > 0) {

			$p = (max($p));

		}

		$title = "";

		if (!empty($_GET['nav'])) {
			$title = $_GET['nav'];

			$title = explode(":", $title);

			$title = $title[0];
		}

		$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			$extension = strtolower($extension);

		if ($title != "f") {

			if ($extension == "swf") {
				die("You cannot post flash here");
			}

			$allowedExts = array("gif", "jpeg", "jpg", "png", "pjpeg", "x-png");
			if ((($_FILES["file"]["type"] == "image/gif")
				|| ($_FILES["file"]["type"] == "image/jpeg")
				|| ($_FILES["file"]["type"] == "image/jpg")
				|| ($_FILES["file"]["type"] == "image/pjpeg")
				|| ($_FILES["file"]["type"] == "image/x-png")
				|| ($_FILES["file"]["type"] == "image/png"))
				&& ($_FILES["file"]["size"] < 3245728)
				&& in_array($extension, $allowedExts))
			{
				if ($_FILES["file"]["error"] > 0)
				{
					echo "ERROR ERROR ERROR";
				}

				move_uploaded_file($_FILES["file"]["tmp_name"],
					$root . '\hopar\GRU_H1\hive\img\posts/' . $p . "." . $extension);

				$original = $root . '\hopar\GRU_H1\hive\img\posts/' . $p . "." . $extension;
	            $thumbFolder = $root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $p . ".jpeg";

	   		    make_thumb($original, $thumbFolder, 261);

	   		    if (!file_exists($root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $p . ".jpeg")) {

	   		    	copy($root . '\hopar\GRU_H1\hive\img/not-found.jpeg',$root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $p . ".jpeg"); 

	   		    }
			}

		}

		else{

			if ($_FILES["file"]["type"] == "application/x-shockwave-flash" && $_FILES["file"]["size"] < 8388608)
			{
				if ($_FILES["file"]["error"] > 0)
				{
					echo "ERROR ERROR ERROR";
				}

				move_uploaded_file($_FILES["file"]["tmp_name"],
					$root . '\hopar\GRU_H1\hive\img\posts/' . $p . "." . $extension);

				copy($root . '\hopar\GRU_H1\hive\img/flash.jpg',$root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $p . ".jpeg");
			}

			else{
				echo "Invalid file";
			}		
		}

		$true = 'true:' . $extension;
			$query = "UPDATE threads
			SET
			image = '" . $true . "'
			WHERE
			postID = '" . $p . "'
			";

			$result = $pdo->query($query);

			//header($url);
	}
	else
		{
			echo "Invalid file";
		}
}

?>