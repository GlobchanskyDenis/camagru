<?php

$requestAsync = [
    'error' =>  ''
];

session_start();

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
    $requestAsync['error'] = 'Config file not found';
	echo json_encode($requestAsync);
	exit;
}

if (!include_once('../config/database.php')) {
	$requestAsync['error'] = 'Error: cannot run one of scripts';
	echo json_encode($requestAsync);
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$requestAsync['error'] = 'Cannot connect to Database';
	echo json_encode($requestAsync);
	exit;
}

if (!include_once('functions.php')) {
	$requestAsync['error'] = 'Cannot find functions script';
	echo json_encode($requestAsync);
	exit;
}

if (!isset($_SESSION['loggued_on_user'])) {
	$requestAsync['error'] = 'You are not logged in';
	echo json_encode($requestAsync);
	exit;
}

if (!isset($_REQUEST['id'])) {
	$requestAsync['error'] = 'Invalid request';
	echo json_encode($requestAsync);
	exit;
}

// Узнать автора фото по id и сравнить его с текущим владельцем сессии
if ( $_SESSION['loggued_on_user'] != getAuthorByNotifID($connectDB, $_REQUEST['id']) ) {
    $requestAsync['error'] = 'This notification is not yours. You cant delete it. You are '.$_SESSION['loggued_on_user'].' author is '. getAuthorByNotifID($connectDB, $_REQUEST['id']) . ' id = ' . $_REQUEST['id'];
	echo json_encode($requestAsync);
	exit;
}

// Сделать запрос на удаление фото из бд
if ( !updateNotificationStatusByID($connectDB, $_REQUEST['id'], false) ) {
    $requestAsync['error'] = 'cannot delete notification from database';
	echo json_encode($requestAsync);
	exit;
}

echo json_encode($requestAsync);

?>