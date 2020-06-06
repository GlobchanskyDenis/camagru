<?php

function xmlDefense($str) : string {
	if (!is_string($str))
		return 'Sory, but bad string';
	$str = htmlspecialchars($str, ENT_QUOTES, ini_get("default_charset"), false);
	$str = str_replace("`", '&grave;', $str);
	return $str;
}

function autorizeDB($connectDB, $login, $passwd) : int {
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
			return 1;
		}
	}	catch (PDOException $e)	{
		return -1;
	}
	return 0;
}

function checkUserStatusDB($connectDB, $login, $passwd) : int {
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
			return 1;
		}
	} catch (PDOException $e) {
		return -1;
	}
	return 0;
}

function checkLoginInDB($connectDB, $login) : int {
	$params = [
		':login'	=> $login,
	];
	$query = 'SELECT login FROM users WHERE login = :login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && $results['login'] == $login) {
			return 1;
		}
	} catch(PDOException $e) {
		return -1;
	}
	return 0;
}

function checkEmailInDB($connectDB, $email) : int {
	$params = [
		':email'	=> $email,
	];
	$query = 'SELECT email FROM users WHERE email = :email';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['email']) && $results['email']) {
			return 1;
		}
	} catch(PDOException $e) {
		return -1;
	}
	return 0;
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
		return false;
	}
	return false;
}

function getUserStatusDB($connectDB, $login) {
	$query = 'SELECT status FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute( [':login' => $login] );
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['status'])) {
			return $results['status'];
		} else {
			return false;
		}
	} catch(PDOException $e) {
		return false;
	}
}

function getUserMail_ifNotifStatus($connectDB, $login) {
	$query = 'SELECT notifications, email FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute( [':login' => $login] );
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['notifications']) && isset($results['email'])) {
			if ($results['notifications'] == true) {
				return $results['email'];
			} else {
				return '';
			}
		}
		return false;
	} catch(PDOException $e) {
		return false;
	}
}

function addPhotoDB($connectDB, $login, $fileName, $name, $data) : bool {
	$query = 'SELECT id FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':login' => $login]);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['id'])) {
			$authorID = $results['id'];
		} else {
			return false;
		}
		$params = [
			':author'	=> $login,
			':authorID'	=> $authorID,
			':fileName'	=> $fileName,
			':name'		=> $name,
			':data'		=> $data
		];
		$query = 'INSERT INTO photo (filename, author, authorID, name, data, regDate, modDate)
							VALUES (:fileName, :author, :authorID, :name, :data, NOW(), NOW())';
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch (PDOException $e) {
		return false;
	}
	// return false;
}

function getPhotosByAuthorFromDB($connectDB, $limit, $login, $lastID) {
	if ($lastID == 0) {
		$query = "SELECT id, data, author, name, likeCounter FROM photo WHERE author=:login ORDER BY id DESC LIMIT :limit";
		$dst = [
			'error' => ''
		];
		try {
			$stmt = $connectDB->prepare($query);
			$stmt->bindValue(':login', $login);
			// Mysql prepared statements хочет чтобы LIMIT был только тип INT
			// Поэтому вот эти танцы с бубном
			$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
			$stmt->execute();
			$i = 1;
			while (($results = $stmt->fetch(PDO::FETCH_ASSOC)) && $i <= $limit) {
				$dst['img'.$i] = $results;
				$i++;
			}
			return $dst;
		} catch(PDOException $e) {
			$dst['error'] = $e->getMessage();
			return $dst;
		}
	} else {
		$query = "SELECT id, data, author, name, likeCounter FROM photo WHERE author=:login AND id<:id ORDER BY id DESC LIMIT :limit";
		$dst = [
			'error' => ''
		];
		try {
			$stmt = $connectDB->prepare($query);
			$stmt->bindValue(':login', $login);
			$stmt->bindValue(':id', $lastID);
			// Mysql prepared statements хочет чтобы LIMIT был только тип INT
			// Поэтому вот эти танцы с бубном
			$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
			$stmt->execute();
			$i = 1;
			while (($results = $stmt->fetch(PDO::FETCH_ASSOC)) && $i <= $limit) {
				$dst['img'.$i] = $results;
				$i++;
			}
			return $dst;
		} catch(PDOException $e) {
			$dst['error'] = $e->getMessage();
			return $dst;
		}
	}
}

function getAllPhotosFromDB($connectDB, $limit, $lastID) {
	if ($lastID == 0) {
		$query = "SELECT id, data, author, name, likeCounter FROM photo ORDER BY id DESC LIMIT :limit";
		$dst = [
			'error' => ''
		];
		try {
			$stmt = $connectDB->prepare($query);
			// Mysql prepared statements хочет чтобы LIMIT был только тип INT
			// Поэтому вот эти танцы с бубном
			$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
			$stmt->execute();
			$i = 1;
			while (($results = $stmt->fetch(PDO::FETCH_ASSOC)) && $i <= $limit) {
				$dst['img'.$i] = $results;
				$i++;
			}
			return $dst;
		} catch(PDOException $e) {
			$dst['error'] = $e->getMessage();
			return $dst;
		}
	} else {
		$query = "SELECT id, data, author, name, likeCounter FROM photo WHERE id<:id ORDER BY id DESC LIMIT :limit";
		$dst = [
			'error' => ''
		];
		try {
			$stmt = $connectDB->prepare($query);
			$stmt->bindValue(':id', $lastID);
			// Mysql prepared statements хочет чтобы LIMIT был только тип INT
			// Поэтому вот эти танцы с бубном
			$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
			$stmt->execute();
			$i = 1;
			while (($results = $stmt->fetch(PDO::FETCH_ASSOC)) && $i <= $limit) {
				$dst['img'.$i] = $results;
				$i++;
			}
			return $dst;
		} catch(PDOException $e) {
			$dst['error'] = $e->getMessage();
			return $dst;
		}
	}
}

function isUserLikedPhoto($connectDB, $login, $photoID) {
	$query = "SELECT * FROM likeTable WHERE photoID=:photoID AND whoLikedLogin=:login";
	$params = [
		':photoID'	=> $photoID,
		':login'	=> $login
	];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && 
			 isset($results['whoLikedLogin']) && 
			 $results['whoLikedLogin'] == $login ) {
			return true;
		}
		return false;
	} catch(PDOException $e) {
		return false;
	}
}

function setLikePhotoByID($connectDB, $photoID, $login) {
	$query = 'INSERT INTO likeTable (photoID, whoLikedLogin, date) VALUES (:photoID, :login, NOW())';
	$params = [
		':photoID'	=> $photoID,
		':login'	=> $login
	];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		return false;
	}
}

function unsetLikePhotoByID($connectDB, $photoID, $login) {
	$query = 'DELETE FROM likeTable WHERE whoLikedLogin=:login AND photoID=:photoID';
	$params = [
		':login'	=> $login,
		':photoID'	=> $photoID
	];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		return $e->getMessage();
		return false;
	}
}

function setNotificationToDB($connectDB, $authorLogin, $photoID, $userNotifier, $message, $isLike) {
	$query = 'INSERT INTO notifTable (author, photoID, userNotifier, message, islikeNotif, date) 
							VALUES (:author, :photoID, :userNotifier, :message, :isLike, NOW())';
	$params = [
		':author'		=> $authorLogin,
		':photoID'		=> $photoID,
		':userNotifier'	=> $userNotifier,
		':message'		=> $message,
		'isLike'		=> $isLike
	];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
		return false;
	}
}

function getNotifID($connectDB, $photoID, $userNotifier, $isLikeNotif) {
	$query = 'SELECT id FROM notifTable WHERE photoID=:photoID AND userNotifier=:userNotifier AND isLikeNotif=:isLikeNotif';
	$params = [
		':photoID'		=> $photoID,
		':userNotifier'	=> $userNotifier,
		'isLikeNotif'	=> $isLikeNotif
	];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && isset($results['id']) ) {
			return $results['id'];
		}
		return false;
	} catch(PDOException $e) {
		return false;
	}
}

function getNotificationsByAuthorDB($connectDB, $authorLogin, $limit) {
	$query = "SELECT id, photoID, userNotifier, message FROM notifTable 
			WHERE author=:author AND activeStatus=TRUE ORDER BY id DESC LIMIT :limit";
	$dst = [];
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->bindValue(':author', $authorLogin);
		// Mysql prepared statements хочет чтобы LIMIT был только тип INT
		// Поэтому вот эти танцы с бубном
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$i = 1;
		while ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && 
			isset($results['id']) && isset($results['userNotifier']) && 
			isset($results['photoID'])  && isset($results['message']) ) {
			$dst['notif'.$i] = $results;
			$i++;
		}
		return $dst;
	} catch(PDOException $e) {
		return false;
	}
}

function updateNotificationStatusByID($connectDB, $notifID, $newStatus) {
	$query = 'UPDATE notifTable SET activeStatus=:newStatus WHERE id=:id';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->bindValue(':newStatus', $newStatus, PDO::PARAM_BOOL);
		// Mysql prepared statements хочет чтобы LIMIT был только тип INT
		// Поэтому вот эти танцы с бубном
		$stmt->bindValue(':id', $notifID);
		$stmt->execute();
		return true;
	} catch(PDOException $e) {
		return false;
	}
}

function reduceLikeCountByPhotoID($connectDB, $photoID) {
	$query = "SELECT likeCounter FROM photo WHERE id=:photoID";
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':photoID' => $photoID]);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && isset($results['likeCounter']) ) {
			$newLikeCounter = (int)($results['likeCounter']) - 1;
			$query = 'UPDATE photo SET likeCounter=:newLikeCounter WHERE id=:photoID';
			$params = [
				':newLikeCounter' => $newLikeCounter,
				':photoID' => $photoID
			];
			$newstmt = $connectDB->prepare($query);
			$newstmt->execute($params);
			return $newLikeCounter;
		}
	} catch(PDOException $e) {
		return false;
	}
	return false;
}

function increaseLikeCountByPhotoID($connectDB, $photoID) {
	$query = "SELECT likeCounter FROM photo WHERE id=:photoID";
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':photoID' => $photoID]);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && isset($results['likeCounter']) ) {
			$newLikeCounter = (int)($results['likeCounter']) + 1;
			$query = 'UPDATE photo SET likeCounter=:newLikeCounter WHERE id=:photoID';
			$params = [
				':newLikeCounter' => $newLikeCounter,
				':photoID' => $photoID
			];
			$newstmt = $connectDB->prepare($query);
			$newstmt->execute($params);
			return $newLikeCounter;
		}
	} catch(PDOException $e) {
		return false;
	}
	return false;
}

function getAuthorByPhotoID($connectDB, $id) : string {
	$query = "SELECT author FROM photo WHERE id=:id";
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':id' => $id]);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && isset($results['author']) ) {
			return $results['author'];
		}
	} catch(PDOException $e) {
		return '';
	}
	return '';
}

function getAuthorByNotifID($connectDB, $id) {
	$query = "SELECT author FROM notifTable WHERE id=:id";
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':id' => $id]);
		if ( ($results = $stmt->fetch(PDO::FETCH_ASSOC)) && isset($results['author']) ) {
			return $results['author'];
		}
	} catch(PDOException $e) {
		return false;
	}
	return false;
}

function deletePhotoByID($connectDB, $id) : bool {
	$query = "DELETE FROM photo WHERE id=:id";
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':id' => $id]);
		$query = 'DELETE FROM likeTable WHERE photoID=:id';
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':id' => $id]);
		$query = 'DELETE FROM notifTable WHERE photoID=:id';
		$stmt = $connectDB->prepare($query);
		$stmt->execute([':id' => $id]);
		return true;
	} catch(PDOException $e) {
		return false;
	}
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
		return false;
	}
	return false;
}

function updateUserStatusDB($connectDB, $login, $newStatus) {
	$params = [
		':login'	=> $login,
		':Status'	=> $newStatus,
	];
	$query = 'UPDATE users SET status=:Status WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		return true;
	} catch(PDOException $e) {
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

function getConfirmEmailCodeDB($connectDB, $login) : string {
	$params = [
		':login'	=> $login,
	];
	$query = 'SELECT id, passwd FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['id']) && isset($results['passwd'])) {
			return md5($results['id'].$results['passwd']);
		}
	} catch(PDOException $e) {
		return 'error';
	}
	return '';
}

function getEmailDB($connectDB, $login) : string {
	$params = [
		':login'	=> $login,
	];
	$query = 'SELECT email FROM users WHERE login=:login';
	try {
		$stmt = $connectDB->prepare($query);
		$stmt->execute($params);
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($results && isset($results['email'])) {
			return $results['email'];
		}
	} catch(PDOException $e) {
		return 'error';
	}
	return '';
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

function checkAuthRequest() : string {
	if ($_SERVER['REQUEST_METHOD'] != 'POST')	{ return 'Wrong request method'; }
	if (!array_key_exists('login', $_REQUEST))	{ return 'Login not existed'; }
	if ($_REQUEST['login'] === '')				{ return 'Login is empty'; }
	if (!array_key_exists('passwd', $_REQUEST))	{ return 'Password not exist '; }
	if ($_REQUEST['passwd'] === '')				{ return 'Password is empty'; }
	if ($_REQUEST['submit'] !== 'signIn')		{ return 'You didnt push submit'; }
    return '';
}

function checkValidationRequest() : string {
	if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != '') {
		return 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	}
	if (!isset($_SESSION['to_confirm']) || $_SESSION['to_confirm'] == '') {
		return 'Authorize or sign up first';
	}
	return '';
}

function checkRegLogin() : string {

	$login = $_REQUEST['login'];

	if (!array_key_exists('login', $_REQUEST))	{ return 'Login not exist'; }
	if (!is_string($login))						{ return 'Login is not a string'; }
	if (!include_once('../config/rules.php'))	{ return 'Error: cannot run one of scripts'; }
	if (strlen($login) > LOGIN_MAX_LENGTH)		{ return 'Max login length '. LOGIN_MAX_LENGTH. ' symbols'; }
	if (strlen($login) < LOGIN_MIN_LENGTH)		{ return 'Min login length '. LOGIN_MIN_LENGTH. ' symbols'; }
	if (!ctype_alnum($login))					{ return 'Only letters and digits are available in login'; }
	if ($login[0] >= '0' && $login[0] <= '9')	{ return 'First login symbol must be letter'; }

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
	if ( !($isUpCase && $isLowCase && $isDigit && !ctype_alnum($passwd)) ) {
		return 'Password must include UpCase, LowCase, Digit and Special char';
	}
	return '';
}

function checkRegPasswdConfirm() : string {

	if (!array_key_exists('passwdConfirm', $_REQUEST))		{ return 'Password confirm not exist'; }
	if ($_REQUEST['passwd'] !== $_REQUEST['passwdConfirm'])	{ return 'Passwd and its confirm dont match'; }

	return '';
}

function checkRegEmail() : string {

	$email = $_REQUEST['email'];

	if (!array_key_exists('email', $_REQUEST))	{ return 'E-mail not exist'; }
	if (!is_string($email))						{ return 'Email is not a string'; }
	if (!include_once('../config/rules.php'))	{ return 'Error: cannot run one of scripts'; }
	if (strlen($email) > EMAIL_MAX_LENGTH)		{ return 'Max email length '.EMAIL_MAX_LENGTH.' symbols'; }
	if (strlen($email) < EMAIL_MIN_LENGTH)		{ return 'Min email length '.EMAIL_MIN_LENGTH.' symbols'; }

	if (substr_count ($email , '@') != 1 || $email[0] === '@' || $email[strlen($email) - 1] === '@') {
		return 'Incorrect email - check @ symbol';
	}

	$arr = explode('@', $email);
	$domain = $arr[1];
	if (substr_count ($domain , '.') != 1 || $domain[0] === '.' || $domain[strlen($domain) - 1] === '.') {
		return 'Incorrect email. Check domain';
	}
	
	return '';
}

function checkCreatePhotoRequest() : string {
	if (!isset($_SESSION['loggued_on_user']) || $_SESSION['loggued_on_user'] == '') {
		return 'You are not logged on';
	}
	if ( !isset($_REQUEST['name']) || !isset($_REQUEST['img']) ) {
		return 'invalid request';
	}
	if ( !is_string($_REQUEST['name']) || strlen($_REQUEST['name']) > PHOTO_NAME_MAX_LENGTH ||
			strlen($_REQUEST['name']) < PHOTO_NAME_MIN_LENGTH) {
		return 'invalid request. Check photo title';
	}
	if (strlen($_REQUEST['img']) > PHOTO_MAX_LENGTH) {
		return 'invalid request. Bad photo size';
	}
	if ( !isset($_REQUEST['filter']) || !is_numeric($_REQUEST['filter']) ) {
		return 'invalid request';
	}
	if ( $_REQUEST['filter'] < 1 || $_REQUEST['filter'] > 6 ) {
		return 'invalid request';
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

function sendConfirmMail($login, $email, $confirmCode) {
	$subject = 'Registration in Camagru';
	// $message = "<html><body>
	// <p><span style='font-size: 1.5em; color: green;'>Hello, <b>$login</b></span></br></p>
	// <p>you must confirm your registration by entering this confirm code:</p>
	// <p><span style='background-color: light-blue;'>$confirmCode</span></p>
	// <p>or you can simply push <a href='192.168.0.197:8080/scripts/validate.php?code=$confirmCode'>here</a></p>
	// <p><form action='192.168.0.197:8080/scripts/validate.php' method='POST'>
	// <input type='text' name='code' value='$confirmCode' hidden>
	// <input type='submit'>
	// </form></p>
	// </body></html>";

	$message = '<html><body style="font-size: 1.4em;"><p><span style="font-size: 1.3em; color: green;">Hello, <b>'.$login.'</b></span></p>'.PHP_EOL;
	$message .= '<p>you must confirm your registration by entering this confirm code:</p>';
	$message .= '<p><span style="color: white; background-color: black; font-size:1.3em;">'.$confirmCode.'</span></p>';
	$message .= '</body></html>';

	$headers  = 'MIME-Version: 1.0' . PHP_EOL;
	$headers .= 'Content-type: text/html; charset=utf8' . PHP_EOL;
	mail($email, $subject, $message, $headers);
}
?>