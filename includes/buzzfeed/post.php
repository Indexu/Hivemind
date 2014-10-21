<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	require($root . '\hopar\GRU_H1\includes\loggedin.php');

	$UID = $_SESSION['user']['accountID'];

	$TID = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
	$UPOST = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);

	$BOARD = filter_var($_POST["board"], FILTER_SANITIZE_STRING);
	$STATUS = checkStatus();
	$APPROVEDBOARDS = ["b","f","t","am","pol","mlp","lol","dota","wg","wa","wv","peta","tv"];

	//------------------------------------------------------

	if ($STATUS != "banned") {

		if (in_array($BOARD, $APPROVEDBOARDS)) {

			$return = "";

			$query = "SELECT threads.postID as 'postID' FROM threads";
			$result = $pdo->query($query); 
			$p = array();
			foreach ($result as $row) { array_push($p, $row['postID']); }
			if (count($p) > 0) { $p = (max($p) + 1); }

			$query = "SELECT threads.threadID as 'threadID' FROM threads";
			$i = array();
			$result = $pdo->query($query); 
			foreach ($result as $row) { array_push($i, $row['threadID']); }
			if (count($i) > 0) { $i = (max($i) + 1); }

	//-----------------------------------------

			if ($TID >= 0) {

				$query = "SELECT threads.postIndex as 'postIndex' FROM threads WHERE threads.threadID = '" . $TID . "'";
				$pI = 0;
				$result = $pdo->query($query); 
				foreach ($result as $row) { 

					$pI = $pI + 1;

				}

					$query = "INSERT INTO `threads`(`board`, `threadID`, `accountID`, `postID`, `postIndex`, `comment`, `image`) 
					VALUES ('".$BOARD."', '".$TID."','".$UID."','".$p."','".$pI."','".$UPOST."','false')";

					$return = "Success!";

			} else {

				$query = "INSERT INTO `threads`(`board`, `threadID`, `accountID`, `postID`, `postIndex`, `comment`, `image`) 
				VALUES ('".$BOARD."', '".$i."','".$UID."','".$p."','0','".$UPOST."','false')";

				$return = "Success!";

			}

			$result = $pdo->query($query);

			echo $return;

		} else {

			echo "This board does not exist";

		}

	} else {

		echo "You can't post, you are banned";

	}

}
?>