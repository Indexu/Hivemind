<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');

	require($root . '\hopar\GRU_H1\includes\variables.php');

	require($root . '\hopar\GRU_H1\includes\loggedin.php');

	if (loggedin() == false || checkStatus() == "banned" || checkStatus() == "") {
		echo "not signed in";
		
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

