<?php

	function loggedin(){

		$loggedin = false;

		if (isset($_SESSION['user']) && isset($_SESSION['user']['email'])) {
			$loggedin = true;
			
			if ($_SESSION['user']['status'] == "banned") {
				$loggedin = false;
				return $loggedin;
			}
		}

		if (isset($_COOKIE['user']) && isset($_COOKIE['user']['email'])) {

			if ($_COOKIE['user']['status'] == "banned") {
				$loggedin = false;
				return $loggedin;
			}

			global $pdo;
		
			$query = "
		        SELECT
		            accounts.accountID AS accountID,
		            accounts.email AS email,
		            accounts.status AS status,
		            profile.alias AS alias,
		            profile.bio AS bio,
		            profile.joinDate AS joinDate
		        FROM accounts
		        JOIN profile ON (accounts.accountID=profile.accountID)
		        WHERE
		            accounts.accountID = :accountID AND profile.accountID = :accountID
	        ";

	        $query_paramsCheck = array(
	            ':accountID' => $_COOKIE['user']['accountID']
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

	        $proceed = true;

	        if ($row['accountID'] != $_COOKIE['user']['accountID']) {
	        	$proceed = false;
	        }

	        elseif ($row['email'] != $_COOKIE['user']['email']) {
	        	$proceed = false;
	        }

	        elseif ($row['alias'] != $_COOKIE['user']['alias']) {
	        	$proceed = false;
	        }

	        elseif ($row['bio'] != $_COOKIE['user']['bio']) {
	        	$proceed = false;
	        }

	        elseif ($row['joinDate'] != $_COOKIE['user']['joinDate']) {
	        	$proceed = false;
	        }

	        elseif ($row['status'] != $_COOKIE['user']['status']) {
	        	$proceed = false;
	        }

	        if ($proceed == true) {
	        	$_SESSION['user']['email'] = $_COOKIE['user']['email'];
				$_SESSION['user']['accountID'] = $_COOKIE['user']['accountID'];
				$_SESSION['user']['alias'] = $_COOKIE['user']['alias'];
				$_SESSION['user']['bio'] = $_COOKIE['user']['bio'];
				$_SESSION['user']['joinDate'] = $_COOKIE['user']['joinDate'];
				$_SESSION['user']['status'] = $_COOKIE['user']['status'];

				$loggedin = true;
	        }

	        else{
	        	unset($_SESSION['user']);
			    setcookie('user[email]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
			    setcookie('user[accountID]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
				setcookie('user[alias]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
				setcookie('user[bio]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
				setcookie('user[joinDate]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
			    setcookie('user[status]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	        }
			
		}

		return $loggedin;

	}

	function checkStatus(){
		global $pdo;
		
		$query = "
	        SELECT
	            status
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

	        return $row['status'];
	}

?>