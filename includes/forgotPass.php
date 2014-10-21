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
		if ($row['status'] != "banned") {

			$email = $row['email'];

			$key = $email . $row['alias'] . $row['salt'] . $row['password'] . $row['status'];  
        	$key = md5($key);
        	$key = "r" . $key;

        	$proceed = true;
		}

		else{
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

        //----------- FORGOT PASS EMAIL ------------
        //----------- CURRENTLY DISABLED ------------

        $message="Your password reset link. <br /><br />";
        $message.="Someone requested a password reset of the account " . $email . "<br />";
        $message.="If you did not make this request, simply ignore this email.<br /><br />";
        $message.="Press the link below to reset your password:<br />";
        $message.="<a href='http://www.hivemind.is/resetpass/?key=$key'>Reset password</a><br /><br />";
        $message.="If something goes wrong with the link, paste this URL in the address bar:<br />";
        $message.="http://www.hivemind.is/resetpass/?key=$key"; 

        $return = "success";
		
	}

	echo $return;
}