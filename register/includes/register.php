<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

if($_POST)
{
    //Er þetta ajax?
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }

    //Meira validate
    if(!isset($_POST["email"]))
    {
        die("emailmissing");
    }

    if (!isset($_POST["password"])) {
        die("passmissing");
    }

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        die("emailinvalid");
    }

    else{

        $email = $_POST["email"];
        $alias = $_POST["alias"];

        $query = "SELECT 1 FROM accounts
                  WHERE email= :email";

        $query_params = array(
            ':email' => $email
            );

        try
        {
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        //Ef email er tekið
        $row = $stmt->fetch();

        if($row)
        {
            die("emailtaken");
        }

        //INSERT
        $query = "
        INSERT INTO accounts (
            email,
            password,
            salt,
            confirmed,
            confirm_key
            ) VALUES (
            :email,
            :password,
            :salt,
            :confirmed,
            :confirm_key
            )
        ";

        //Generate random hex to be used as salt
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

        //Use SHA256
        $password = hash('sha256', $_POST['password'] . $salt);

        //Hash 65536 more times!
        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }

        //Randomize confirm key
        $key = $email . $alias . $salt;  
        $key = md5($key);

        $query_params = array(
            ':email' => $email,
            ':password' => $password,
            ':salt' => $salt,
            ':confirmed' => 1,
            ':confirm_key' => $key
            ); 

        //Submit query
        try
        {
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        //INSERT - Profile
        $query = "
        SELECT
            MAX(accountID) AS accountID
        FROM accounts
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

        $query = "
        INSERT INTO profile (
                alias,
                bio,
                accountID
            ) VALUES (
                :alias,
                :bio,
                :accountID
            )
        ";

        $query_params = array(
            ':alias' => $alias,
            ':bio' => 'This user has not created a bio yet',
            ':accountID' => $row['accountID']
            );

        try
        {
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        $query = "
        INSERT INTO friends (
                accountID
            ) VALUES (
                :accountID
            )
        ";

        $query_paramsFriends = array(
            ':accountID' => $row['accountID']
            );

        try
        {
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_paramsFriends);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        //----------- CONFIRMATION EMAIL ------------
        //----------- CURRENTLY DISABLED ------------

        $message="Welcome to Hivemind! <br /><br />";
        $message.="<a href='http://www.hivemind.is/confirmation/?key=$key'>Click here</a> to activate your account. <br /><br />";
        $message.="If something goes wrong with the link, paste this URL in the address bar:<br />";
        $message.="http://www.hivemind.is/confirmation/?key=$key"; 

       	echo "success";
    }
}
?>