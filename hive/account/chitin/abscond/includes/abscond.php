<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

require($root . '\hopar\GRU_H1\includes\variables.php');

require($root . '\hopar\GRU_H1\includes\loggedin.php');

if (loggedin() == false) {
    echo "not signed in";
    header("Location: ../../../");
    die();
}

    //Has the user buzzed?
if(!empty($_POST))
{

        //Change password hash
    if(!empty($_POST['password']))
    {
        $query = "
        SELECT
        password,
        salt
        FROM accounts
        WHERE
            accountID = :accountID
        ";

        $query_params = array(
            ':accountID' => $_SESSION['user']['accountID']
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

        $pass_ok = false;

        $row = $stmt->fetch();
        if($row)
        {
            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }

            if($check_password === $row['password'])
            {
                $pass_ok = true;
            }

            if ($pass_ok === true) {
                $query = "DELETE FROM friends WHERE accountID = " . $_SESSION['user']['accountID'];
                $result = $pdo->query($query);

                $query = "DELETE FROM profile WHERE accountID = " . $_SESSION['user']['accountID'];
                $result = $pdo->query($query);

                $query = "DELETE FROM accounts WHERE accountID = " . $_SESSION['user']['accountID'];
                $result = $pdo->query($query);

                $query = "UPDATE threads SET accountID = -1 WHERE accountID= " . $_SESSION['user']['accountID'];
                $result = $pdo->query($query);

                header("Location: ../../../../");
                die();
            }
        }
    }
    else
    {
        echo "No buzzword was entered. No changes have been made.";
    }
}