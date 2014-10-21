<?php
if($_POST)
{

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	$type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
	$text = filter_var($_POST["text"], FILTER_SANITIZE_STRING);

	$text = strtolower($text);

	$arr = str_split($text);

	for ($i=0; $i < count($arr); $i++) { 
		if ($arr == '%') {
			die("<p>Nice try</p>");
		}
	}

	if ($type == "id") {
		$query = "
	        SELECT
	        	profile.accountID AS accountID,
	            profile.alias AS alias
	        FROM profile
	        WHERE
	        	profile.accountID = :accountID
	        ";


            $query_params = array(
            	':accountID' => $text
            );
		
			$stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);

            $row = $stmt->fetchAll();

            $return = "";

            for ($i=0; $i < count($row); $i++) { 
            	$return .= "<a href='http://tsuts.tskoli.is/hopar/gru_h1/hive/profile/?id=" . $row[$i]['accountID'] . "'>
            		ID: " . $row[$i]['accountID'] . " | Alias: " . $row[$i]['alias'] . "</a><br/>";
            }

			echo $return;
	}

	elseif ($type == "alias"){
		$query = "
	        SELECT
	        	profile.accountID AS accountID,
	            profile.alias AS alias
	        FROM profile
	        WHERE
	        	profile.alias LIKE '%$text%'
	        ";
		
			$stmt = $pdo->prepare($query);
            $result = $stmt->execute();

            $row = $stmt->fetchAll();

            $return = "";

            for ($i=0; $i < count($row); $i++) { 
            	$return .= "<a href='http://tsuts.tskoli.is/hopar/gru_h1/hive/profile/?id=" . $row[$i]['accountID'] . "'>
            		ID: " . $row[$i]['accountID'] . " | Alias: " . $row[$i]['alias'] . "</a><br/>";
            	
            }

			echo $return;
	}

			

	

}