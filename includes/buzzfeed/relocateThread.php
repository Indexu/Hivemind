<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$TID = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
	$BOARD = filter_var($_POST["board"], FILTER_SANITIZE_STRING);

	$APPROVEDBOARDS = ["b","f","t","am","pol","mlp","lol","dota","wg","wa","wv","peta", "tv"];

	if (in_array($BOARD, $APPROVEDBOARDS)) {
		if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

			$query = "UPDATE threads SET threads.board = " . $BOARD . " WHERE threads.threadID = '" . $TID . "'";

			$query = "
                UPDATE threads
                SET
                    threads.board = :board
                WHERE
                    threads.threadID = :tid
                ";

            $query_params = array(
            	':board' => $BOARD,
            	':tid' => $TID
            );
		
			$stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);

			echo "moved";

		}

		else{
			echo "notAdmin";
		}
	}

	else{
		echo "notBoard";
	}

	

}
?>