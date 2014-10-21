<?php
	$query = "
		SELECT
			profile.accountID AS accountID,
			profile.alias AS alias,
			profile.joinDate AS joinDate,
			accounts.status AS status
		FROM profile
		JOIN accounts ON (profile.accountID=accounts.accountID)
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

		$users = $stmt->fetchAll();

		function userList(){

			global $users;

			for ($i=0; $i < count($users); $i++) { 
				echo "<a href='#'><tr class='userListRow'><td class='userListId'>" . $users[$i]['accountID'] . "</td><td>" . $users[$i]['alias'] . "</td><td>" . $users[$i]['status'] ."</td></tr></a>";
			}

		}
?>

<div class="row">

    <div class="large-4 columns">
        <div class="panel">
            <h4>Userlist</h4>
            
            <table>
            	<thead>
			    	<tr>
					   	<th>ID</th>
					   	<th>Alias</th>
					   	<th>Status</th>
					</tr>
			  </thead>

			  <tbody>
			  	<?php userList() ?>
			  </tbody>

            </table>

        </div>
            
    </div>
</div>
