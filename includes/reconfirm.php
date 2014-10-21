<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

if ($_POST) {
	$return = "fail";

	$query = "
	SELECT
		accounts.accountID AS accountID,
		accounts.email AS email,
		accounts.password AS password,
		accounts.salt AS salt,
		accounts.confirmed AS confirmed,
		accounts.status AS status,
		profile.alias AS alias
	FROM accounts
	JOIN profile ON (accounts.accountID=profile.accountID)
	WHERE
		accounts.email = :email
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

	$proceed = false;

	$row = $stmt->fetch();

	if($row)
	{
		if ($row['confirmed'] == 0 && $row['status'] != "banned") {
			$check_password = hash('sha256', $_POST['password'] . $row['salt']);
			for($round = 0; $round < 65536; $round++)
			{
				$check_password = hash('sha256', $check_password . $row['salt']);
			}

			if($check_password === $row['password'])
			{
				$proceed = true;
			}

			else{
				$proceed = false;
				$return = "fail";
			}

			$email = $row['email'];

			$key = $email . $row['alias'] . $row['salt'];  
        	$key = md5($key);

		}

		else{
			$proceed = false;
			$return = "confirmed";
		}

		if ($row['status'] == "banned") {
			$proceed = false;
			$return = "banned";
		}
		
	}

	else{
		$return = "fail";
	}

	if($proceed == true){

		$query = "
        UPDATE accounts
        SET
            confirm_key = :confirm_key
        WHERE
             accountID = :accountID
        ";

        $query_paramsUpdate = array(
        	':confirm_key' => $key,
			':accountID' => $row['accountID']
		);

        try
        {
        	$stmt = $pdo->prepare($query);
        	$result = $stmt->execute($query_paramsUpdate);
        }
        catch(PDOException $ex)
        {
        	die("Failed to run query: " . $ex->getMessage());
        }

        //----------- CONFIRMATION EMAIL ------------
        //----------- CURRENTLY DISABLED ------------

        $message="Welcome to Hivemind! <br /><br />";
        $message.="<a href='http://www.hivemind.is/confirmation/?key=$key'>Click here</a> to activate your account. <br /><br />";
        $message.="If something goes wrong with the link, paste this URL in the address bar:<br />";
        $message.="http://www.hivemind.is/confirmation/?key=$key"; 

        $return = "sendfail";
		
	}

	echo $return;
}