<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$reportID = filter_var($_POST["reportID"], FILTER_SANITIZE_STRING);

	if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

		$query = "DELETE FROM reports WHERE reports.reportID = '" . $reportID . "'";
	
		$result = $pdo->query($query);

		echo "success";

	}

	else{
		echo "notAdmin";
	}

}
?>