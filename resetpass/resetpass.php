<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

if(empty($_GET['key']) && !$_POST){
	header("Location: ../");
	die();
}

if ($_POST) {
	if (strlen($_POST['key']) == 33) {

		if ($_POST['password'] != $_POST['confirm']) {
			die("nomatch");
		}

		$password = $_POST['password'];

			//Generate random hex to be used as salt
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

		    //Use SHA256
		$password = hash('sha256', $password . $salt);

		    //Hash 65536 more times!
		for($round = 0; $round < 65536; $round++)
		{
			$password = hash('sha256', $password . $salt);
		}

		    //INSERT
		$query = "
		UPDATE accounts
		SET
		password = :password,
		salt = :salt
		WHERE
		confirm_key = :confirm_key
		";

		$query_params = array(
			':password' => $password,
			':salt' => $salt,
			':confirm_key' => $_POST['key']
			);

		try
		{
			$stmt = $pdo->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage());
		}

		echo "success";
	}

	else{
		echo "invalidkey";
	}

}