<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

require('loggedin.php');

if (loggedin() == true) {
	header("Location: hive");
	die();
}

if ($_POST) {

	$return = "fail";

	$rememberme = "off";

	if (isset($_POST["rememberme"])) {
		$rememberme = "on";
	}

	$query = "
	SELECT
		accountID,
		email,
		password,
		salt,
		confirmed,
		status
	FROM accounts
	WHERE
		email = :email
	";

	$query_params = array(
		':email' => $_POST["email"]
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

	$login_ok = false;

	$row = $stmt->fetch();

	if($row)
	{
		if ($row['confirmed'] == 1) {
			$check_password = hash('sha256', $_POST['password'] . $row['salt']);
			for($round = 0; $round < 65536; $round++)
			{
				$check_password = hash('sha256', $check_password . $row['salt']);
			}

			if($check_password === $row['password'])
			{
				$login_ok = true;
			}

			else{
				$login_ok = false;
				$return = "invalid";
			}

		}

		else{
			$login_ok = false;
			$return = "unconfirmed";
		}

		if ($row['status'] == "banned") {
			$login_ok = false;
			$return = "banned";
		}
		
	}

	else{
		$return = "invalid";
	}

	if($login_ok == true){
		unset($row['salt']);
		unset($row['password']);


		$_SESSION['user'] = $row;

		$query = "
		SELECT
			alias,
			bio,
			joinDate,
			accountID
		FROM profile
		WHERE
			accountID = :accountID
		";

		$query_params = array(
			':accountID' => $row['accountID']
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

		$_SESSION['user']['status'] = $row['status'];
		$_SESSION['user']['email'] = $row['email'];

		$row = $stmt->fetch();

		$_SESSION['user']['alias'] = $row['alias'];
		$_SESSION['user']['bio'] = $row['bio'];
		$_SESSION['user']['joinDate'] = $row['joinDate'];


		if ($rememberme == "on") {
			setcookie('user[email]', $_SESSION['user']['email'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
			setcookie('user[accountID]', $row['accountID'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
			setcookie('user[alias]', $row['alias'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
			setcookie('user[bio]', $row['bio'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
			setcookie('user[joinDate]', $row['joinDate'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
			setcookie('user[status]', $_SESSION['user']['status'], time() + 2592000, '/', $_SERVER['SERVER_NAME']);
		}

		$return = "success";

	}

	echo $return;
}

