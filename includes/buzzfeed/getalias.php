<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	$UID = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

	$return = "";

	$query = "SELECT profile.alias as 'alias' FROM profile WHERE profile.accountID = '" . $UID . "'";

	$result = $pdo->query($query);
	foreach ($result as $row) { 
				
		$return = $row['alias'];

	}
		
	echo $return;
	
}
?>