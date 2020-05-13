<?php

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$_SESSION['last_error'] .= 'Config file not found';
	header('Location: ../signUp.php');
	exit;
}

if (!include_once('../config/database.php')) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header('Location: ../signUp.php');
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$_SESSION['last_error'] .= 'Cannot connect to Database';
	header('Location: ../signUp.php');
	exit;
}

?> 