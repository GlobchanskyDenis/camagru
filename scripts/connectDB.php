<?php

if (!isset($_SESSION['last_error'])) {
	$_SESSION['last_error'] = '';
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$_SESSION['last_error'] .= 'Config file not found';
	header($prevLocation);
	exit;
}

if (!include_once('../config/database.php')) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header($prevLocation);
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$_SESSION['last_error'] .= 'Cannot connect to Database';
	header($prevLocation);
	exit;
}

?> 