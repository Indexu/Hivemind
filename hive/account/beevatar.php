<?php

$allowedExts = array("gif", "jpeg", "jpg", "png", "pjpeg", "x-png");

$beevatar = 0;

if (!empty($_GET['id'])) {
	//for ($i=0; $i < count($allowedExts); $i++) { 
		if (file_exists($root . '\hopar\GRU_H1\hive\img\profile/avatars/' . $_GET['id'] . ".jpeg"))
		{
			$beevatar = "http://tsuts.tskoli.is/hopar/gru_h1/hive\img\profile\avatars/" . $_GET['id'] . ".jpeg";
		}
	//}

	if ($beevatar !== 0) {
		echo "<img class='beevatar' src='". $beevatar ."'>";
	}

	else{
		echo "<img class='beevatar' src='http://tsuts.tskoli.is/hopar/gru_h1/hive/img\profile/avatars/default.jpeg'>";
	}
}

else{
	//for ($i=0; $i < count($allowedExts); $i++) { 
		if (file_exists($root . '\hopar\GRU_H1\hive\img\profile\avatars/' . $_SESSION['user']['accountID'] . ".jpeg"))
		{
			$beevatar = "http://tsuts.tskoli.is/hopar/gru_h1/hive/img\profile\avatars/" . $_SESSION['user']['accountID'] . ".jpeg";
		}
	//}

	if ($beevatar !== 0) {
		echo "<img class='beevatar' src='". $beevatar ."'>";
	}

	else{
		echo "<img class='beevatar' src='http://tsuts.tskoli.is/hopar/gru_h1/hive/img\profile/avatars/default.jpeg'>";
	}
}

