<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
require($root . '\hopar\GRU_H1\includes/loggedin.php');
require($root . '\hopar\GRU_H1\includes\variables.php');

$result = checkStatus();

//Check if the user has the authority to be here
if ($result != "beekeeper" && $result != "queen_bee") {
	unset($_SESSION['user']);
	setcookie('user[email]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	setcookie('user[accountID]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	setcookie('user[alias]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	setcookie('user[bio]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	setcookie('user[joinDate]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);
	setcookie('user[status]', '', time() - 7200, '/', $_SERVER['SERVER_NAME']);

	header("Location: ../");
	die();

}

//Count the number of reports and display
function countReports(){
	global $pdo;

	$query = "
	SELECT
	COUNT(*) AS count
	FROM reports
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

	echo "(" . $row['count'] . ")";
}

//Generate reports rable
function reportList(){

	global $pdo;
	
	$query = "
	SELECT
	*
	FROM reports
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

	$reports = $stmt->fetchAll();

	echo "<table class='reportTable'>
	<thead>
		<tr>
			<th>reportID</th>
			<th>submitter</th>
			<th>postID</th>
			<th>reason</th>
			<th>details</th>
			<th>remove</th>
		</tr>
	</thead>

	<tbody>";

		for ($i=0; $i < count($reports); $i++) {

			echo "<tr class='reportRow'>
			<td class='reportID'>" . $reports[$i]['reportID'] . "</td>
			<td class='submitterID'>" . $reports[$i]['submitter'] . "</td>
			<td>" . $reports[$i]['postID'] ."</td>
			<td>" . $reports[$i]['reason'] ."</td>
			<td>" . $reports[$i]['details'] ."</td>
			<td class='removeColumn'><a title='Delete report' class='removeReport'>de</a></td>
		</tr>";


	}	
	echo "</tbody></table>";


}

//Generate userlist
function userList(){
	global $pdo;

	$query = "
	SELECT
	profile.accountID AS accountID,
	profile.alias AS alias,
	profile.joinDate AS joinDate,
	accounts.email AS email,
	accounts.status AS status,
	accounts.confirmed AS confirmed
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

	echo "<table class='accountsTable'>
	<thead>
		<tr>
			<th>ID</th>
			<th>alias</th>
			<th>email</th>
			<th>status</th>
			<th>confirmed</th>
			<th>delete</th>
		</tr>
	</thead>

	<tbody>";

	

	for ($i=0; $i < count($users); $i++) { 

		$banned = "";
		$brood = "";
		$queen_bee = "";
		$beekeeper = "";

		switch ($users[$i]['status']) {
			case 'banned':
				$banned = "selected";
				break;
			case 'brood':
				$brood = "selected";
				break;

			case 'queen_bee':
				$queen_bee = "selected";
				break;

			case 'beekeeper':
				$beekeeper = "selected";
				break;
			
			default:
				break;
		}

		echo "<tr class='userListRow'>
				<td class='userListId'>" . $users[$i]['accountID'] . "</td>
				<td>" . $users[$i]['alias'] . "</td>
				<td>" . $users[$i]['email'] . "</td>
				<td class='userListStatus'>
					<select name='userStatus' class='userListSelect'>
						<option " . $banned . " value='banned'>banned</option>
						<option " . $brood . " value='brood'>brood</option>
						<option " . $queen_bee . " value='queen_bee'>queen_bee</option>
						<option " . $beekeeper . " value='beekeeper'>beekeeper</option>
					</select>
				</td>
				<td>" . $users[$i]['confirmed'] . "</td>
				<td class='removeColumn deleteUserClick'><a title='Delete user' class='deleteUser'>de</a></td>
			</tr>";
	}

	echo "</tbody></table>";

}