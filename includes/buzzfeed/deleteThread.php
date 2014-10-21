<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$TID = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

	if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

		$query = "DELETE FROM threads WHERE threads.threadID = '" . $TID . "'";
	
		$result = $pdo->query($query);

		return "Thread Nr." . $TID . " has been deleted";

	}

}
?>