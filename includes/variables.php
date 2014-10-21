<?php

$root = $_SERVER['DOCUMENT_ROOT'];

	// ------------------------Title--------------------------------------
	$title = "Hive";

	if (isset($_GET['nav'])) {
		$title = $_GET['nav'];

		$title = explode(":", $title);

		$title = $title[0];

		switch ($title) {
		    case "b":
		        $title = "random";
		        break;

		    case "f":
		        $title = "flash";
		        break;	

		    case "t":
		        $title = "technology";
		        break;

		    case "am":
		        $title = "animu & mango";
		        break;

		    case "fur":
		        $title = "furry";
		        break;

		    case "wg":
		        $title = "wallpapers/general";
		        break;

		    case "wa":
		        $title = "wallpapers/animu";
		        break;

		    case "wv":
		        $title = "wallpapers/vidya";
		        break;

		    case "peta":
		        $title = "cute animals";
		        break;
		}

	}

	//----------------------------BANNER IMAGE-------------------------------------------

	$imgFolder = $root . '\hopar\GRU_H1\hive\img\header';

	$bg = array();

	foreach (new DirectoryIterator($imgFolder) as $fileInfo) {
	    if($fileInfo->isDot()) continue;

	    if ($fileInfo != "Thumbs.db") {
	    	array_push($bg, (string)$fileInfo);
	    }
	    
	}

  	$i = rand(0, count($bg)-1); // generate random number size of the array
  	$selectedBg = $bg[$i]; // set variable equal to which random filename was chosen

  	//-------------------------Sub-headder--------------------------------------
  	$subheadder = array();

	$subheadder[0] = "Fruit Plantation";
	$subheadder[1] = "The imageboard the world's buzzing about";
	$subheadder[2] = "Next level imageboarding";
	$subheadder[3] = "Get rekt m8";
	/*$subheadder[4] = "";
	$subheadder[5] = "";
	$subheadder[6} = "";
	$subheadder[7] = "";
	$subheadder[8] = "";
	$subheadder[9] = "";
	$subheadder[10] = "";
	$subheadder[11] = "";
	$subheadder[12] = "";
	$subheadder[13] = "";
	$subheadder[14] = "";*/

  	$j = rand(0, count($subheadder)-1); // generate random number size of the array
  	$selectedSub = $subheadder[$j]; // set variable equal to which random filename was chosen

?>