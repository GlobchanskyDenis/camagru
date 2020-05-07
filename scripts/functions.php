<?php
function xmlDefense($str) {
	if (!is_string($str))
		return 'Sory, but bad string';
	$str = htmlspecialchars($str, ENT_QUOTES, ini_get("default_charset"), false);
	$str = str_replace("`", '&grave;', $str);
	return $str;
}

function autorizeBD($database, $login, $passwd) : bool {
	$pass = md5($passwd);

	$stmt = $database->prepare("SELECT login, passwd FROM users WHERE login = ? AND passwd = ?");
	$stmt->bind_param("ss", $login, $pass);

	if ($stmt->execute() && $stmt->fetch()) {
		return true;
	}
	return false;
}

function checkUserStatusBD($database, $login, $passwd) : bool {
	$pass = md5($passwd);

	$stmt = $database->prepare("SELECT status FROM users WHERE login = ? AND passwd = ?");
	$stmt->bind_param("ss", $login, $pass);

	if (!$stmt->execute()) {
		return false;
	}
	if ( ($answer = $stmt->get_result()) ) {
		$answer->data_seek(0);
		$line = $answer->fetch_assoc();
		if ($line && ($line['status'] == 'user' || $line['status'] == 'admin' || $line['status'] == 'superuser')) {
			return true;
		}	
	}
	return false;
}

function checkLoginInBD($database, $login) : bool {
	$stmt = $database->prepare("SELECT login FROM users WHERE login = ?");
	$stmt->bind_param("s", $login);

	if ($stmt->execute() && $stmt->fetch()) {
		return false;
	}
	return true;
}

function checkEmailInBD($database, $email) : bool {
	$stmt = $database->prepare("SELECT email FROM users WHERE email = ?");
	$stmt->bind_param("s", $email);

	if ($stmt->execute() && $stmt->fetch()) {
		return false;
	}
	return true;
}

function userRegisterBD($database, $login, $passwd, $email, $status) : bool {
	$pass = md5($passwd);

	$stmt = $database->prepare("INSERT INTO users (login, passwd, email, status) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $login, $pass, $email, $status);

	if ($stmt->execute() && $stmt->fetch()) {
		return true;
	}
	return false;
}

function checkAuthRequest() : bool {
	$ret = true;
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		$_SESSION['last_error'] .= 'Wrong request method ';
        $ret = false;
	}
	if (!array_key_exists('login', $_REQUEST)) {
		$_SESSION['last_error'] .= 'Login not existed ';
        $ret = false;
	} elseif ($_REQUEST['login'] === '') {
		$_SESSION['last_error'] .= 'Login is empty ';
		$ret = false;
	}
	if (!array_key_exists('passwd', $_REQUEST)) {
		$_SESSION['last_error'] .= 'Password not exist ';
        $ret = false;
	} elseif ($_REQUEST['passwd'] === '') {
		$_SESSION['last_error'] .= 'Password is empty ';
		$ret = false;
	}
	if ($_REQUEST['submit'] !== 'signIn') {
		$_SESSION['last_error'] .= 'You didnt push submit ';
        $ret = false;
	}
    return $ret;
}

function checkRegLogin() : string {

	$login = $_REQUEST['login'];

	if (!array_key_exists('login', $_REQUEST))	{ return 'Login not exist'; }
	if (!is_string($login))						{ return 'Login is not a string'; }
	if (!include_once('../config/rules.php'))	{ return 'Error: cannot run one of scripts'; }
	if (strlen($login) > LOGIN_MAX_LENGTH)		{ return 'Max login length '. LOGIN_MAX_LENGTH. ' symbols'; }
	if (strlen($login) < LOGIN_MIN_LENGTH)		{ return 'Min login length '. LOGIN_MIN_LENGTH. ' symbols'; }
	if (!ctype_alnum($login))					{ return 'Only letters and digits are available in login'; }
	if ($login[$i] >= '0' && $login[$i] <= '9') { return 'First login symbol must be letter'; }

	return '';
}

function checkRegPasswd() : string {

	$passwd = $_REQUEST['passwd'];
	
	if (!array_key_exists('passwd', $_REQUEST))	{ return 'Password not exist'; }
	if (!is_string($passwd))					{ return 'Password is not a string'; }
	if (!include_once('../config/rules.php'))	{ return 'Error: cannot run one of scripts'; }
	if (strlen($passwd) < PASSWD_MIN_LENGTH)	{ return 'Min password length '. PASSWD_MIN_LENGTH. ' symbols'; }
	if (strlen($passwd) > PASSWD_MAX_LENGTH)	{ return 'Max password length '. PASSWD_MAX_LENGTH. ' symbols'; }

	$len = strlen($passwd);
	$isUpCase = false;
	$isLowCase = false;
	$isDigit = false;
	for ($i = 0; $i < $len; $i++) {
		if (($passwd[$i] >= 'A' && $passwd[$i] <= 'Z'))	{ $isUpCase = true; }
		if (($passwd[$i] >= 'a' && $passwd[$i] <= 'z'))	{ $isLowCase = true; }
		if (($passwd[$i] >= '0' && $passwd[$i] <= '9'))	{ $isDigit = true; }
	}
	if ( !($isUpCase && $isLowCase && $isDigit && !ctype_alnum($login)) ) {
		return 'Password must include UpCase, LowCase, Digit and other symbols';
	}
	return '';
}

function checkRegPasswdConfirm() {

	if (!array_key_exists('passwdConfirm', $_REQUEST))		{ return 'Password confirm not exist'; }
	if ($_REQUEST['passwd'] !== $_REQUEST['passwdConfirm'])	{ return 'Passwd and its confirm dont match'; }

	return '';
}

function checkRegEmail() {

	$email = $_REQUEST['email'];

	if (!array_key_exists('email', $_REQUEST))	{ return 'E-mail not exist'; }
	if (!is_string($email))						{ return 'Email is not a string'; }
	if (!include_once('../config/rules.php'))	{ return 'Error: cannot run one of scripts'; }
	if (strlen($email) > EMAIL_MAX_LENGTH)		{ return 'Max email length '.EMAIL_MAX_LENGTH.' symbols'; }
	if (strlen($email) < EMAIL_MIN_LENGTH)		{ return 'Min email length '.EMAIL_MIN_LENGTH.' symbols'; }

	if (substr_count ($email , '@') != 1 || $email[0] === '@' || $email[strlen[$email] - 1] === '@') {
		return 'Incorrect email - check @ symbol';
	}

	$arr = explode('@', $email);
	$domain = $arr[1];
	if (substr_count ($domain , '.') != 1 || $domain[0] === '.' || $domain[strlen($domain) - 1] === '.') {
		return 'Incorrect email';
	}
	
	return '';
}

function regRequestErrors() : string {
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST')		{ return 'Wrong request method'; }
	if ($_REQUEST['submit'] !== 'signUp')			{ return 'You didnt push submit'; }
	if ( ($ret = checkRegLogin()) != '' )			{ return $ret; }
	if ( ($ret = checkRegPasswd()) != '' )			{ return $ret; }
	if ( ($ret = checkRegPasswdConfirm()) != '' )	{ return $ret; }
	if ( ($ret = checkRegEmail()) != '' )			{ return $ret; }

	return '';
}
?>