<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');   

    //Has the user buzzed?
if($_POST)
{
    if ($_SESSION["user"]["status"] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "
    UPDATE accounts
    SET
        status = :status
    WHERE
        accountID = :accountID
    ";

    $query_params = array(
        ':status' => $status,
        ':accountID' => $id
    );

    try
    {
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute($query_params);
        echo "success";
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }
}

}


