<?php

$requestAsync = [
    'error' =>  '',
    'isLiked' => 0,
    'newLikeCounter' => 0
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

if ( isUserLikedPhoto($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['id']) ) {

    if ( !unsetLikePhotoByID($connectDB, $_REQUEST['id'], $_SESSION['loggued_on_user']) ) {
        $requestAsync['error'] = 'cannot unset like this photo';
        echo json_encode($requestAsync);
        exit;
    }

    if ( ($newLikeCounter = reduceLikeCountByPhotoID($connectDB, $_REQUEST['id'])) === false ) {
        $requestAsync['error'] = 'cannot reduce likes of this photo';
        echo json_encode($requestAsync);
        exit;
    }

    $requestAsync['newLikeCounter'] = $newLikeCounter;

} else {

    if ( !setLikePhotoByID($connectDB, $_REQUEST['id'], $_SESSION['loggued_on_user']) ) {
        $requestAsync['error'] = 'cannot like this photo';
        echo json_encode($requestAsync);
        exit;
    }

    if ( ($newLikeCounter = increaseLikeCountByPhotoID($connectDB, $_REQUEST['id'])) === false ) {
        $requestAsync['error'] = 'cannot increase likes of this photo';
        echo json_encode($requestAsync);
        exit;
    }

    if ( ($authorLogin = getAuthorByPhotoID($connectDB, $_REQUEST['id'])) == false ) {
        $requestAsync['error'] = 'cannot get author of this photo';
        echo json_encode($requestAsync);
        exit;
    }

    if  (   $authorLogin != $_SESSION['loggued_on_user'] &&
            !($notifID = getNotifID($connectDB, $_REQUEST['id'], $_SESSION['loggued_on_user'], true) ) ) {

        if ( !setNotificationToDB($connectDB, $authorLogin, $_REQUEST['id'], $_SESSION['loggued_on_user'], 'liked your photo', true)) {
            $requestAsync['error'] = 'cannot set notification';
            echo json_encode($requestAsync);
            exit;
        }

        if ( ($mail = getUserMail_ifNotifStatus($connectDB, $authorLogin)) === false ) {
            $requestAsync['error'] = 'cannot get user mail notification status';
            echo json_encode($requestAsync);
            exit;
        }

        if ($mail) {
            $mailRequest = [
                'login'         => $authorLogin,
                'email'         => $mail,
                'userNotifier'  => $_SESSION['loggued_on_user'],
                'message'       => 'liked your photo'
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
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 50);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $urlEncodedMailRequest);
            curl_exec($ch);
            curl_close($ch);
        }  
    }
    $requestAsync['isLiked'] = 1;
    $requestAsync['newLikeCounter'] = $newLikeCounter;
}

echo json_encode($requestAsync);

?>