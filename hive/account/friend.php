<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	if ($_GET) {

		$isFriend = "false";

		$id = $_GET['id'];

		$query = "
        SELECT
            friends
        FROM friends
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

        $friends = explode(':', $row['friends']);

        for ($i=0; $i < count($friends); $i++) { 
        	if ($friends[$i] == $id) {
        		$isFriend = "true";
        	}
        }

        if ($isFriend == "false") {

        	array_push($friends, $id);

        	$addFriends = implode(":", $friends);

            //Upphaflega er ":" fyrir framan, taka það í burtu.
            if ($row['friends'] == null) {
                $addFriends = substr($addFriends, 1, (strlen($addFriends) - 1));
            }

        	$query = "
	        UPDATE friends
	        SET
	            friends = :friends
	        WHERE
	            accountID = :accountID
	        ";

	        $query_paramsUpdate = array(
            	':accountID' => $_SESSION['user']['accountID'],
            	':friends' => $addFriends
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

        }

        echo $isFriend;
		
	}

    else{
        echo "error";
    }

?>