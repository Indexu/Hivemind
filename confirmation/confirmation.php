<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
include($root . '\hopar\GRU_H1\includes\variables.php');

$response = array();
$response[0] = "Error: Not a valid confirmation key.";
$response[1] = "class='panel confirmResponse errorConfirm'";

if (!empty($_GET['key'])) {
	$key = $_GET['key'];

	if (strlen($key) == 32) {

		$query = "
		SELECT
		email,
		confirmed
		FROM accounts
		WHERE confirm_key = :confirm_key
		";

		$query_paramsKey = array(
			':confirm_key' => $key
		);

		try
		{
			$stmt = $pdo->prepare($query);
			$result = $stmt->execute($query_paramsKey);
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage());
		}

		$row = $stmt->fetch();

		if ($row['confirmed'] == 0 && strlen($row['email']) > 0 ) {
			$query = "
			UPDATE accounts
			SET
				confirmed = :confirmed
			WHERE
				confirm_key = :confirm_key
			";

			$query_paramsConfirm = array(
				':confirmed' => 1,
				':confirm_key' => $key
			);

			try
			{
				$stmt = $pdo->prepare($query);
				$result = $stmt->execute($query_paramsConfirm);
				$response[0] = "You are now a confirmed bee.";
				$response[1] = "class='panel confirmResponse confirmedConfirm'";
			}
			catch(PDOException $ex)
			{
				die("Failed to run query: " . $ex->getMessage());
			}
		}

		elseif (strlen($row['email']) > 0) {
			$response[0] = "Link expired.";
		}
	}

	

}

