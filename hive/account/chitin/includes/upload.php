<?php

//Create thumbnail - Credit: http://davidwalsh.name/create-image-thumbnail-php
function make_thumb($src, $dest, $desired_width) {

  /* read the source image */
  $info = new SplFileInfo($src);
  //$ext = strtolower(getExtension($src));
  $ext = $info->getExtension();
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

//--------END thumbnail-----------

$root = $_SERVER['DOCUMENT_ROOT'];

//Check if file exists - Meirihluti kóðans: W3Schools
if ($_FILES['file']['size'] !== 0) {
  $allowedExts = array("gif", "jpeg", "jpg", "png", "pjpeg", "x-png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
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
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }

  //Var eitthvað skilið eftir?
  for ($i=0; $i < count($allowedExts); $i++) { 
    if (file_exists($root . '\hopar\GRU_H1\hive\img\profile/' . $_SESSION['user']['accountID'] . "." . $allowedExts[$i]))
      {
        unlink($root . '\hopar\GRU_H1\hive\img\profile/' . $_SESSION['user']['accountID'] . "." . $allowedExts[$i]);
      }
  }
    //Færa + rename file
    move_uploaded_file($_FILES["file"]["tmp_name"],
      $root . '\hopar\GRU_H1\hive\img\profile/' . $_SESSION['user']['accountID'] . "." . $extension);

    //Thumbnail
    $original = $root . '\hopar\GRU_H1\hive\img\profile/' . $_SESSION['user']['accountID'] . "." . $extension;
    $thumbFolder = $root . '\hopar\GRU_H1\hive\img\profile\avatars/' . $_SESSION['user']['accountID'] . ".jpeg";

    make_thumb($original, $thumbFolder, 261);

    //Eyða upphaflegu skránni
    unlink($root . '\hopar\GRU_H1\hive\img\profile/' . $_SESSION['user']['accountID'] . "." . $extension);
}
else
{
  echo "Invalid file";
}
}

