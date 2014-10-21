<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	require($root . '\hopar\GRU_H1\includes\variables.php');

	require($root . '\hopar\GRU_H1\includes\loggedin.php');

	if (loggedin() == false) {
		echo "not signed in";
		header("Location: ../../");
		die();
	}

	$self = false;

	if (!empty($_GET['id'])) {
		$id = $_GET['id'];

		if ($id == $_SESSION['user']['accountID']) {
			$self = true;
		}

		else{
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

	        $already = "false";

	        for ($i=0; $i < count($friends); $i++) {
	        	if ($id == $friends[$i]) {
	        		$already = "true";
	        	}
	        }
		}
	}

	else{
		$id = $_SESSION['user']['accountID'];
		$self = true;
	}

	
	function friendButton(){

		global $self;
		global $already;

		if ($self == false && $already == "false") {
			echo "<button class='friendButton addFriend'>Add bee as friend</button>";
		}

		if ($already == "true") {
			echo "Bee is a friend";
		}

		if($self == true){
			echo "<button class='secondary friendButton'>Manage friends</button>";
		}
	}

	if ($id > 0) {
		$query = "
		SELECT
			profile.alias AS alias,
			profile.bio AS bio,
			profile.joinDate AS joinDate,
			friends.friends AS friends
		FROM profile
		JOIN friends ON (profile.accountID=friends.accountID)
		WHERE
			profile.accountID = :accountID
			AND friends.accountID = :accountID
		";

		$query_paramsInfo = array(
			':accountID' => $id
		);

		try
		{
			$stmt = $pdo->prepare($query);
			$result = $stmt->execute($query_paramsInfo);
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage());
		}

		$row = $stmt->fetch();
		$bio = $row['bio'];
		$joinDate = substr($row['joinDate'], 0, 10);
		$alias = $row['alias'];

		$query = "
		SELECT
			board,
			threadID,
			postID,
			comment, 
			postDate,
			image 
		FROM threads
			WHERE accountID = :accountID
			ORDER BY postDate DESC

		";

		$query_paramsInfo = array(
			':accountID' => $id
		);

		try
		{
			$stmt = $pdo->prepare($query);
			$result = $stmt->execute($query_paramsInfo);
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage());
		}

		$activity = $stmt->fetchAll();

		function recentActivity(){

			global $activity;

			$loop = 5;

			if (count($activity) == 0) {
				echo "<div class='panel'><h5>No posts have been made.</h5></div>";
			}

			else{
				if (count($activity) < 5) {
				$loop = count($activity);
			}


				for ($i=0; $i < $loop; $i++) {
					$image = null;

					if ($activity[$i]['image'] == "true:swf") {
						$image = "[Flash] ";
					}
					else if ($activity[$i]['image'] != "false") {
						$image = "[Image] ";
					}
					echo "<a href='http://tsuts.tskoli.is/hopar/gru_h1/hive/?nav=" . $activity[$i]['board'] . ":1:" . $activity[$i]['threadID'] . "#p_" . $activity[$i]['postID'] . "' class='panel profilePostLink'>
					<h5 class='recentComment'>" . $image . $activity[$i]['comment'] . "</h5>
					<h6>" . $activity[$i]['postDate'] . "</h6>
					</a>";
				}

			}

			
		}

		function friendsList(){
			global $row;
			global $pdo;
			global $self;

			$friends = $row['friends'];

			if ($friends != "") {
				$friendQuery = str_replace(":", ",", $friends);

				$query = "
				SELECT
					profile.accountID,
					profile.alias,
					accounts.status
				FROM profile
				JOIN accounts ON (profile.accountID=accounts.accountID)
					WHERE profile.accountID IN ($friendQuery)

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

				$friendFetch = $stmt->fetchAll();

				if ($self == false) {
					echo "<table>
                  				<thead>
				                    <tr>
				                      <th>Alias</th>
				                      <th>Status</th>
				                    </tr>
				                  </thead>

				                  <tbody>";

					for ($i=0; $i < count($friendFetch); $i++) {
						
				                    echo "<tr class='userListRow'>
				                    		<td class='userListId' style='display:none'>" . $friendFetch[$i]['accountID'] . "</td>
				                    		<td>" . $friendFetch[$i]['alias'] . "</td>
				                    		<td>" . $friendFetch[$i]['status'] ."</td>
				                    	</tr>";
				                  

					}	
					echo "</tbody></table>";
				}

				else{
					echo "<table>
                  				<thead>
				                    <tr>
				                      <th>Alias</th>
				                      <th>Status</th>
				                      <th>Remove</th>
				                    </tr>
				                  </thead>

				                  <tbody>";

					for ($i=0; $i < count($friendFetch); $i++) {
						
				                    echo "<tr class='userListRow'>
				                    		<td class='userListId' style='display:none'>" . $friendFetch[$i]['accountID'] . "</td>
				                    		<td>" . $friendFetch[$i]['alias'] . "</td>
				                    		<td>" . $friendFetch[$i]['status'] ."</td>
				                    		<td class='removeColumn'><a title='Remove friend' class='removeFriend'>de<a></td>
				                    	</tr>";
				                  

					}	
					echo "</tbody></table>";
				}
				
			}

			
		}

		
	}