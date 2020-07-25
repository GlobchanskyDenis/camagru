<?php

$requestAjax = [
	'error'    => "",
	'counter'  => false
	// 'text'	=> false,
	// 'id'	=> false
];

session_start();

if (!isset($_SESSION['loggued_on_user']) || $_SESSION['loggued_on_user'] == '') {
	$requestAjax['error'] = 'You are not logged';
    echo json_encode($requestAjax);
	exit;
}

if ( !isset($_REQUEST['text']) ) {
    $requestAjax['error'] = 'Invalid request';
    echo json_encode($requestAjax);
	exit;
}

if ( $_REQUEST['text'] == '' ) {
    $requestAjax['error'] = 'Empty comment';
    echo json_encode($requestAjax);
	exit;
}

if ( strlen($_REQUEST['text']) > 500 ) {
    $requestAjax['error'] = 'Too long comment';
    echo json_encode($requestAjax);
	exit;
}

if (!isset($_REQUEST['photoID']) || !is_numeric($_REQUEST['photoID']) || $_REQUEST['photoID'] <= 0 ) {
    $requestAjax['error'] = 'Invalid request';
    echo json_encode($requestAjax);
	exit;
}

if ( !isset($_REQUEST['lastID']) ||
        !( is_numeric($_REQUEST['lastID']) || $_REQUEST['lastID'] == false ) || 
        ( is_numeric($_REQUEST['lastID']) && $_REQUEST['lastID'] <= 0) ) {
    $requestAjax['error'] = 'Invalid request 2';
    echo json_encode($requestAjax);
	exit;
}

$photoID = (int)$_REQUEST['photoID'];
$text = ($_REQUEST['text']);
$login = $_SESSION['loggued_on_user'];
$lastID = $_REQUEST['lastID'];

if (!include_once("functions.php")) {
    $requestAjax['error'] = 'cannot find functions file';
    echo json_encode($requestAjax);
	exit; 
}

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
	$requestAjax['error'] = 'Cannot connect to Database';
	echo json_encode($requestAjax);
	exit;
}

if (!($author = getAuthorByPhotoID($connectDB, $photoID))) {
	$requestAjax['error'] = 'Cannot get author by photoID to Database';
	echo json_encode($requestAjax);
	exit;
}

if (!addCommentToDB($connectDB, $login, $photoID, $text)) {
	$requestAjax['error'] = 'Cannot add comment to Database';
	echo json_encode($requestAjax);
	exit;
}

if (!($commentArr = getNewCommentsByPhotoIdFromDB($connectDB, $photoID, $lastID))) {
	$requestAjax['error'] = 'Cannot get comments from Database';
	echo json_encode($requestAjax);
	exit;
}

file_put_contents("log.txt" , "place1");

if ($login != $author) {
	file_put_contents("log.txt" , "place2");
	if ( !($ret = setNotificationToDB($connectDB, $author, $photoID, $login, 'commented your photo', false))) {
		$requestAsync['error'] = 'cannot set notification '.$ret;
		echo json_encode($requestAsync);
		file_put_contents("log.txt" , "err1");
		exit;
	}
	file_put_contents("log.txt" , "place3");
	if ( ($mail = getUserMail_ifNotifStatus($connectDB, $author)) === false ) {
		$requestAsync['error'] = 'cannot get user mail notification status';
		echo json_encode($requestAsync);
		file_put_contents("log.txt" , "err2");
		exit;
	}
	file_put_contents("log.txt" , "place4");
	if ($mail) {
		file_put_contents("log.txt" , "place5");
		$mailRequest = [
			'login'         => $author,
			'email'         => $mail,
			'userNotifier'  => $login,
			'message'       => 'commented your photo'
		];
		$urlEncodedMailRequest = http_build_query($mailRequest);
		$headers = [
			'Content-type: application/x-www-form-urlencoded',
			'Content-length: '.strlen($urlEncodedMailRequest)
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.$MAIL_HOST.':'.$MAIL_PORT.'/scripts/sendNotifEmail.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $urlEncodedMailRequest);
		curl_exec($ch);
		curl_close($ch);
		file_put_contents("log.txt" , "place6");
	}  
}

if ($commentArr['error'] != '') {
	$requestAjax['error'] = 'cannot get comments from database';//. Ercode = '.$photoArr['error'];
	echo json_encode($requestAjax);
	exit;
}
$requestAjax['counter'] = 0;

for ($i=1; isset($commentArr['comment'.$i]); $i++) {
	$requestAjax['counter'] = 20;
	$comment = $commentArr['comment'.$i];
	if ($comment != false) {
		$comment['date'] = xmlDefense($comment['date']);
		$comment['author'] = xmlDefense($comment['author']);
		$comment['text'] = str_replace("\n", '</br>', xmlDefense($comment['text']));
		$requestAjax['comment'.$i] = $comment;
		$requestAjax['counter'] = $requestAjax['counter'] + 1;
	}
}

// $i = 1;
// while (isset($comments['comment'.$i])) {
// 	$comment = $comments['comment'.$i];
// 	$i++;
// 	$comment['text'] = str_replace("\n", '</br>', xmlDefense($comment['text']));
// 	// $requestAjax['id'] = $comment['id'];
// 	// $requestAjax['date'] = $comment['date'];
// 	$comment['author'] = xmlDefense($comment['author']);
// 	$requestAjax['comment'.$i] = $comment;
// }


echo json_encode($requestAjax);

?>