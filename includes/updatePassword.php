<?php 

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
	$postData = file_get_contents('php://input');


	$query = "
	SELECT
		MAX(accountID) as accountID
	FROM accounts
	";

	try
	{
		$stmt = $pdo->prepare($query);
		$result = $stmt->execute();
	}

	catch(PDOException $ex)
	{
		die("Failed to run query: " . $ex->getMessage());
	}

	$row = $stmt->fetch();

    //Generate random hex to be used as salt
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

    //Use SHA256
	$password = hash('sha256', $postData . $salt);

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
             accountID = :accountID
        ";

    $query_params = array(
		':password' => $password,
		':salt' => $salt,
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

}

?>