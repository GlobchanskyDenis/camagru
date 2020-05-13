<?php

session_start();

$errors = [
	'request' => '',
	'login' => '',
	'passwd' => '',
	'passwdConfirm' => '',
	'email' => ''
];

if (!include_once("functions.php")) {
	$errors['request'] = 'Error: cannot run one of scripts';
	echo json_encode($errors);
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$errors['request'] = 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	echo json_encode($errors);
	exit;
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$errors['request'] = 'Config file not found';
	echo json_encode($errors);
	exit;
}

if (!include_once('../config/database.php')) {
	$errors['request'] = 'Error: cannot run one of scripts';
	echo json_encode($errors);
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$errors['request'] = 'Cannot connect to Database';
	echo json_encode($errors);
	exit;
}

if ( ($ret = checkRegLogin()) != '' )	{
	$errors['login'] = $ret;
}

if ( checkLoginInDB( $connectDB, $_REQUEST['login'] ) ) {
	$errors['login'] = 'This login is already taken';
	echo json_encode($errors);
	exit;
}

if ( ($ret = checkRegPasswd()) != '' )	{ 
	$errors['passwd'] = $ret;
}

if ( ($ret = checkRegPasswdConfirm()) != '' )	{ 
	$errors['passwdConfirm'] = $ret;
} 

if ( ($ret = checkRegEmail()) != '' )	{ 
	$errors['email'] = $ret;
}

if ( checkEmailInDB( $connectDB, $_REQUEST['email'] ) ) {
	$errors['email'] = 'This email is already taken';
	echo json_encode($errors);
	exit;
}

$errors['request'] = $_COOKIE[session_name()];
echo json_encode($errors);

?>