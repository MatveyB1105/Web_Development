<?php

	session_start();
	require_once "Connection/connection.php";

	$email = $_GET['email'];
	$password = $_GET['password'];
	$isAdmin = $_GET['isAdmin'];

	if (strcmp($isAdmin,"yes") == 0) {
		$check_admin = $conn -> query("SELECT * FROM employee WHERE email='$email' AND password='$password'");
		$admin = $check_admin -> fetch_assoc();
		if ($admin == NULL) {
            $_SESSION['message'] = 'Неверные логин и/или пароль';
            header('Location: auth_form.php');
		} else {
            $_SESSION['admin'] = array (
                "id" => $admin['employee_id'],
                "last_name" => $admin['last_name'],
                "first_name" => $admin['first_name'],
            );
		header('Location: empl_cabinet.php');
		};
	} else {
		$check_user = $conn -> query("SELECT * FROM client WHERE email='$email' AND password='$password'");
		$client = $check_user -> fetch_assoc();
		if ($client == NULL) {
	    	$_SESSION['message'] = 'Неверные логин и/или пароль';
	    	header('Location: auth_form.php');
		} else {
	    	$_SESSION['client'] = array (
	      		"id" => $client['Client_ID'],
		      	"last_name" => $client['last_name'],
    		  	"first_name" => $client['first_name'],
		    );
	    	$lifetime = 120;
	    	$name = 'client';
	    	$id =  $_SESSION['client']['id'];
	    	setcookie($name, $id, time() + $lifetime, '/');
    		header('Location: cabinet.php');
		};
	};
	$conn -> close();
?>