<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	if ($_POST){

		$friend = $_POST['id'];

			$query = "
				SELECT
					friends
				FROM friends
					WHERE accountID = :accountID

				";

				$query_paramsRemove = array(
					':accountID' => $_SESSION['user']['accountID']
				);

				try
				{
					$stmt = $pdo->prepare($query);
					$result = $stmt->execute($query_paramsRemove);
				}
				catch(PDOException $ex)
				{
					die("Failed to run query: " . $ex->getMessage());
				}

				$row = $stmt->fetch();

				$array = explode(':', $row['friends']);

				if (($key = array_search($friend, $array)) !== false) {
				    unset($array[$key]);
				}

				$friendString = implode(':', $array);

				$query = "
                    UPDATE friends
                    SET
                        friends = :friends
                    WHERE
                        accountID = :accountID
                    ";

                    $query_paramsUpdateFriends = array(
						':accountID' => $_SESSION['user']['accountID'],
						':friends' => $friendString
					);

                    try
                    {
                        $stmt = $pdo->prepare($query);
                        $result = $stmt->execute($query_paramsUpdateFriends);
                        echo "Removed";
                    }
                    catch(PDOException $ex)
                    {
                        die("Failed to run query: " . $ex->getMessage());
                    }
	}	

