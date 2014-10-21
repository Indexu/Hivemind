<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$reportID = filter_var($_POST["reportID"], FILTER_SANITIZE_STRING);

	if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

		$query = "
	        SELECT
	            postID
	        FROM reports
	        WHERE
	            reportID = :reportID
	        ";

	        $query_paramsCheck = array(
	            ':reportID' => $reportID
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

	        $postID = $row['postID'];

	        $query = "
	        SELECT
	            board,
	            threadID
	        FROM threads
	        WHERE
	            postID = :postID
	        ";

	        $query_paramsCheck = array(
	            ':postID' => $postID
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

	        $post = $row['board'] . ":1:" . $row['threadID'] . "#p_" . $postID;

		echo $post;

	}

	else{
		echo "notAdmin";
	}

}
?>