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

    $query = "
        SELECT
            profile.bio AS bio,
            profile.alias AS alias
        FROM profile
        WHERE
            accountID = :accountID
        ";

        $query_paramsGet = array(
            ':accountID' => $_SESSION['user']["accountID"]
            );

        try
        {
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute($query_paramsGet);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        $profile = $stmt->fetch();
    
    //Has the user buzzed?
    if(!empty($_POST))
    {

        $query = "
        SELECT
            password,
            salt
        FROM accounts
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

        $change = false;

        if($row)
        {
            $check_password = hash('sha256', $_POST['confirmPassword'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }

            if($check_password === $row['password'])
            {
                $change = true;
            }
        }

        if ($change === true) {

            if (!empty($_FILES['file'])) {
                require('includes/upload.php');
            }

            //Change password - hash
            if(!empty($_POST['password']))
            {
                $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                $password = hash('sha256', $_POST['password'] . $salt);
                for($round = 0; $round < 65536; $round++)
                {
                    $password = hash('sha256', $password . $salt);
                }

                //Change password - parameters
                if($password !== null)
                {
                    $query_paramsPass[':password'] = $password;
                    $query_paramsPass[':salt'] = $salt;
                    $query_paramsPass[':accountID'] = $_SESSION['user']['accountID'];

                    $query = "
                    UPDATE accounts
                    SET
                        password = :password,
                        salt = :salt
                    WHERE
                        accountID = :accountID
                    ";

                    try
                    {
                        $stmt = $pdo->prepare($query);
                        $result = $stmt->execute($query_paramsPass);
                    }
                    catch(PDOException $ex)
                    {
                        die("Failed to run query: " . $ex->getMessage());
                    }
                }
            }

            else
            {
                //Keep old password
                $password = null;
                $salt = null;
            }
            

            if (!empty($_POST['alias'])) {

                $query_paramsAlias[':alias'] = $_POST['alias'];
                $query_paramsAlias[':accountID'] = $_SESSION['user']['accountID'];

                $query = "
                UPDATE profile
                SET
                    alias = :alias
                WHERE
                    accountID = :accountID
                ";

                try
                {
                    $stmt = $pdo->prepare($query);
                    $result = $stmt->execute($query_paramsAlias);

                    $_SESSION['user']['alias'] = $_POST['alias'];
                }
                catch(PDOException $ex)
                {
                    die("Failed to run query: " . $ex->getMessage());
                }

            }

            if (!empty($_POST['bio'])) {

                $query_paramsBio[':bio'] = $_POST['bio'];
                $query_paramsBio[':accountID'] = $_SESSION['user']['accountID'];

                $query = "
                UPDATE profile
                SET
                    bio = :bio
                WHERE
                    accountID = :accountID
                ";

                try
                {
                    $stmt = $pdo->prepare($query);
                    $result = $stmt->execute($query_paramsBio);

                    $_SESSION['user']['bio'] = $_POST['bio'];
                }
                catch(PDOException $ex)
                {
                    die("Failed to run query: " . $ex->getMessage());
                }

            }
        }
        
        
    }
    