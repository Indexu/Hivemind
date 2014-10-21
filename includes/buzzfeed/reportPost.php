<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$postInfo = filter_var($_POST["postInfo"], FILTER_SANITIZE_STRING);
	$reason = filter_var($_POST["reason"], FILTER_SANITIZE_STRING);
	$details = filter_var($_POST["details"], FILTER_SANITIZE_STRING);
	$agree = filter_var($_POST["agree"], FILTER_SANITIZE_STRING);

	$postID = explode(".", $postInfo);

	$postID = end($postID);

	if ($agree == "true") {
		$query = "
	        INSERT INTO reports (
	            submitter,
	            postID,
	            reason,
	            details
	            ) VALUES (
	            :submitter,
	            :postID,
	            :reason,
	            :details
	            )
	        ";


            $query_params = array(
            	':submitter' => $_SESSION['user']['accountID'],
            	':postID' => $postID,
            	':reason' => $reason,
            	':details' => $details
            );
		
			$stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);

			echo "reported";
	}

			

	

}
?>