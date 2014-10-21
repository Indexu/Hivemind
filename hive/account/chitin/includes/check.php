<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
    require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	$pass_ok = false;

	if ($_POST) {

		$query = "
		SELECT
			password,
			salt
		FROM accounts
		WHERE
			accountID = :accountID
		";

		$query_paramsCheck = array(
			':accountID' => $_SESSION['user']['accountID']
			);

		try
		{
			$stmt = $pdo->prepare($query);
			$result = $stmt->execute($query_paramsCheck);
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage());
		}

		$row = $stmt->fetch();
		if($row)
		{
			$check_password = hash('sha256', $_POST['confirmPassword'] . $row['salt']);
			for($round = 0; $round < 65536; $round++)
			{
				$check_password = hash('sha256', $check_password . $row['salt']);
			}

			if($check_password === $row['password'])
			{
				$pass_ok = true;
			}
		}
	}

	echo $pass_ok;

?>