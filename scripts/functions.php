<?php
function xmlDefense($str) {
	if (!is_string($str))
		return 'Sory, but bad string';
	$str = htmlspecialchars($str, ENT_QUOTES, ini_get("default_charset"), false);
	$str = str_replace("`", '&grave;', $str);
	return $str;
}

function autorizeDB($connectDB, $login, $passwd) : bool {
	$pass = md5($passwd);
	$params = [
		':login'	=> $login,
		':passwd'	=> $pass,
	];
	$query = 'SELECT login, passwd FROM users WHERE login = :login AND passwd = :passwd';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && $results['login'] == $login && $results['passwd'] == $pass) {
			return true;
		}
	}	catch (PDOException $e)	{
		// echo 'Exception found!</br>autorizeDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function checkUserStatusDB($connectDB, $login, $passwd) : bool {
	$pass = md5($passwd);
	$params = [
		':login'	=> $login,
		':passwd'	=> $pass,
	];
	$query = 'SELECT status FROM users WHERE login = :login AND passwd = :passwd';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && ($results['status'] == 'user' || 
						$results['status'] == 'admin' || 
						$results['status'] == 'superUser')) {
			return true;
		}
	} catch (PDOException $e) {
		// echo 'Exception found!</br>checkUserStatusDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function checkLoginInDB($connectDB, $login) : bool {
	$params = [
		':login'	=> $login,
	];
	$query = 'SELECT login FROM users WHERE login = :login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && $results['login'] == $login) {
			return true;
		}
	} catch(PDOException $e) {
		// echo 'Exception found!</br>checkLoginInDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function checkEmailInDB($connectDB, $email) : bool {
	$params = [
		':email'	=> $email,
	];
	$query = 'SELECT email FROM users WHERE email = :email';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && $results['email'] == $email) {
			return true;
		}
	} catch(PDOException $e) {
		// echo 'Exception found!</br>checkEmailInDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function userRegisterDB($connectDB, $login, $passwd, $email, $status) : bool {
	$pass = md5($passwd);
	$params = [
		':login'	=> $login,
		':passwd'	=> $pass,
		':email'	=> $email,
		':status'	=> $status,
	];
	$query = 'INSERT INTO users (login, passwd, email, status, registrationDate, lastVisited) 
						VALUES (:login, :passwd, :email, :status, NOW(), NOW())';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		// echo 'Exception found!</br>userRegisterDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function updateUserTime($connectDB, $login) : bool {
	$params = [
		':login'	=> $login,
	];
	$query = 'UPDATE users SET lastVisited=NOW() WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		// echo 'Exception found!</br>userRegisterDB</br>'.PHP_EOL;
		// exit;
		$_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function updateLogin($connectDB, $login, $newLogin) : bool {
	$params = [
		':login'	=> $login,
		':newLogin'	=> $newLogin,
	];
	$query = 'UPDATE users SET login=:newLogin WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		// echo 'Exception found!</br>userRegisterDB</br>'.PHP_EOL;
		// exit;
		// $_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function updateEmail($connectDB, $login, $newEmail) : bool {
	$params = [
		':login'	=> $login,
		':newEmail'	=> $newEmail,
	];
	$query = 'UPDATE users SET email=:newEmail WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		// echo 'Exception found!</br>userRegisterDB</br>'.PHP_EOL;
		// exit;
		// $_SESSION['last_error'] .= 'database connection error ';
		return false;
	}
	return false;
}

function updatePasswd($connectDB, $login, $passwd) : bool {
	$params = [
		':login'	=> $login,
		':passwd'	=> md5($passwd),
	];
	$query = 'UPDATE users SET passwd=:passwd WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		return false;
	}
	return false;
}

function getNotifications($connectDB, $login) : int {
	$params = [
		':login'	=> $login,
	];
	$query = 'SELECT notifications FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results) {
			return $results['notifications'];
		}
	} catch(PDOException $e) {
		return (-1);
	}
	return (-1);
}

function updateNotifications($connectDB, $login, $notif) : bool {
	$params = [
		':login'	=> $login,
		':notif'	=> $notif,
	];
	$query = 'UPDATE users SET notifications=:notif WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		return false;
	}
	return false;
}

function checkAuthRequest() : bool {
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		$_SESSION['last_error'] .= 'Wrong request method ';
        return false;
	}
	if (!array_key_exists('login', $_REQUEST)) {
		$_SESSION['last_error'] .= 'Login not existed ';
        return false;
	}
	if ($_REQUEST['login'] === '') {
		$_SESSION['last_error'] .= 'Login is empty ';
		return false;
	}
	if (!array_key_exists('passwd', $_REQUEST)) {
		$_SESSION['last_error'] .= 'Password not exist ';
        return false;
	}
	if ($_REQUEST['passwd'] === '') {
		$_SESSION['last_error'] .= 'Password is empty ';
		return false;
	}
	if ($_REQUEST['submit'] !== 'signIn') {
		$_SESSION['last_error'] .= 'You didnt push submit ';
        return false;
	}
    return true;
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

function checkRegPasswdConfirm() : string {

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