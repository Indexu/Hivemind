<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$id = $_POST["id"];

	if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

		$query = "DELETE FROM friends WHERE accountID = " . $id;
		$result = $pdo->query($query);

		$query = "DELETE FROM profile WHERE accountID = " . $id;
		$result = $pdo->query($query);

		$query = "DELETE FROM accounts WHERE accountID = " . $id;
		$result = $pdo->query($query);

		$query = "UPDATE threads SET accountID = -1 WHERE accountID= " . $id;
		$result = $pdo->query($query);

		echo "success";

	}

	else{
		echo "notAdmin";
	}

}
?>