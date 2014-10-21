<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$postID = filter_var($_POST["postInfo"], FILTER_SANITIZE_STRING);

		if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

			$query = "
                UPDATE threads
                SET
                    threads.comment = :comment,
                    threads.image = :image
                WHERE
                    threads.postID = :pid
                ";

            $query_params = array(
            	':comment' => "[POST DELETED]",
            	':image' => "false",
            	':pid' => $postID
            );
		
			$stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);

            $allowedExts = array("gif", "jpeg", "jpg", "png", "pjpeg", "x-png");

            for ($i=0; $i < count($allowedExts); $i++) { 
			    if (file_exists($root . '\hopar\GRU_H1\hive\img\posts/' . $postID . "." . $allowedExts[$i]))
			      {
			        unlink($root . '\hopar\GRU_H1\hive\img\posts/' . $postID . "." . $allowedExts[$i]);

			        if (file_exists($root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $postID . ".jpeg"))
				      {
				        unlink($root . '\hopar\GRU_H1\hive\img\posts\thumbs/' . $postID . ".jpeg");
				      }
			      }
			 }

			echo "deleted";
		}

		else{
			echo "notAdmin";
		}

	

}
?>