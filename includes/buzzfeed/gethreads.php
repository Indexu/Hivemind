<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	$BOARD = filter_var($_POST["board"], FILTER_SANITIZE_STRING);

	$returnArray = array();

	$query = "SELECT threads.threadID as 'id', 
	threads.accountID as 'uid', 
	threads.postID as 'pid', 
	threads.postIndex as 'index', 
	threads.comment as 'comment', 
	threads.image as 'image', 
	threads.postDate as 'timestamp', 
	threads.board as 'board' FROM threads
	WHERE threads.board = '" . $BOARD . "'";
		
	$result = $pdo->query($query);
	foreach ($result as $row) {

		$alias = "";
		$status = "";

		if ($row['uid'] != -1) {

			$aliasquery = "SELECT profile.alias as 'alias' FROM profile WHERE profile.accountID = '" . $row['uid'] . "'";

			$alias = $pdo->query($aliasquery);
			foreach ($alias as $ind) {

				$alias = $ind['alias'];

			}

			$statusquery = "SELECT accounts.status as 'status' FROM accounts WHERE accounts.accountID = '" . $row['uid'] . "'";

		$status = $pdo->query($statusquery);
		foreach ($status as $stat) {

			$status = $stat['status'];
			
		}

		} else { 

			$alias = "(user removed)";
			$status = "removed";

		}

		$postArray = array( $row['id'], $alias, $row['pid'], $row['index'], $row['comment'], $row['image'], $status, $row['timestamp'], $row['board'] );

		array_push($returnArray, json_encode($postArray));

	}

	echo (json_encode($returnArray));
	
}
?>
