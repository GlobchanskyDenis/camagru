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

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != '') {
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

$ret = checkLoginInDB( $connectDB, $_REQUEST['login'] );
if ( $ret == 1 ) {
	$errors['login'] = 'This login is already taken';
} else if ( $ret == -1 ) {
	$errors['request'] = 'Connection DB error';
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

$ret = checkEmailInDB( $connectDB, $_REQUEST['email'] );
if ( $ret == 1 ) {
	$errors['email'] .= 'This email is already taken';
} else if ( $ret == -1 ) {
	$errors['request'] = 'Connection DB error';
	echo json_encode($errors);
	exit;
}

echo json_encode($errors);

?>