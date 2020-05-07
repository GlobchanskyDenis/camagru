<?php

session_start();

$errors = [
	'other' => '',
	'login' => '',
	'passwd' => '',
	'passwdConfirm' => '',
	'email' => '',
	'request' => ''
];

if (!include_once("functions.php")) {
	$errors['other'] = 'Error: cannot run one of scripts';
	echo json_encode($errors);
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$errors['other'] = 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	echo json_encode($errors);
	exit;
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$errors['other'] = 'Config file not found';
	echo json_encode($errors);
	exit;
}

if (!include_once('../config/database.php')) {
	$errors['other'] = 'Error: cannot run one of scripts';
	echo json_encode($errors);
	exit;
}

if ( ($ret = checkRegLogin()) != '' )	{
	$errors['login'] = $ret;
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


$database = mysqli_connect(
	$DB_DSN, $DB_USER, $DB_PASSWORD, $DB_NAME);

if (mysqli_connect_errno() || $database == null) {
	$errors['request'] = 'Error ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
	echo json_encode($errors);
	exit;
}

if (!checkLoginInBD(	$database, $_REQUEST['login'] )) {
	$errors['request'] = 'This login is already taken';
	echo json_encode($errors);
	exit;
}

if (!checkEmailInBD(	$database, $_REQUEST['email'] )) {
	$errors['request'] = 'This email is already taken';
	echo json_encode($errors);
	exit;
}

echo json_encode($errors);

?>