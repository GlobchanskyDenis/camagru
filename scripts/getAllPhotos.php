<?php

$requestAjax = [
    'error'    => "",
    'img1'     => false,
    'img2'     => false,
    'img3'     => false
];

session_start();

if (!isset($_SESSION['loggued_on_user'])) {
    $user = '';
} else {
    $user = $_SESSION['loggued_on_user'];
}

if (!isset($_REQUEST['photoAmount']) || !is_numeric($_REQUEST['photoAmount']) ||
        $_REQUEST['photoAmount'] <= 0 || $_REQUEST['photoAmount'] > 100) {
    $requestAjax['error'] = 'Invalid request';
    echo json_encode($requestAjax);
	exit;
}

if ( !isset($_REQUEST['lastID']) ||
        !( is_numeric($_REQUEST['lastID']) || $_REQUEST['lastID'] == false ) || 
        ( is_numeric($_REQUEST['lastID']) && $_REQUEST['lastID'] <= 0) ) {
    $requestAjax['error'] = 'Invalid request in getPhotosByAuthor 2 Request='.$_REQUEST['lastID'];
    echo json_encode($requestAjax);
	exit;
}

$photoAmount = (int)($_REQUEST['photoAmount']);

for ( $i = 1; $i < $photoAmount; $i++ ) {
    $requestAjax['img'.$i] = false;
}

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

if ($_REQUEST['lastID'] == "") {

    // Это выполняется в случае, если фото нужны начиная с самого последнего
    $photoArr = getAllPhotosFromDB($connectDB, $photoAmount, 0);

    if ($photoArr['error'] != '') {
        $requestAjax['error'] = 'cannot get photo from database';//. Ercode = '.$photoArr['error'];
        echo json_encode($requestAjax);
        exit;
    }

    for ($i=1; $i <= $photoAmount && isset($photoArr['img'.$i]); $i++) {
        $img = $photoArr['img'.$i];
        if ($img != false) {
            $img['name'] = xmlDefense($img['name']);
            $img['author'] = xmlDefense($img['author']);
            $img['data'] = base64_encode($img['data']);
            $img['likeCount'] = $img['likeCounter'];
            $img['isLiked'] = isUserLikedPhoto($connectDB, $user, $img['id']);
            $img['isAuthor'] = ($user === $img['author']) ? 1 : 0;
            $requestAjax['img'.$i] = $img;
        }
    }
} else {

    // Это выполняется в случае, если фото нужны начиная НЕ с самого последнего
    $photoArr = getAllPhotosFromDB($connectDB, $photoAmount, $_REQUEST['lastID']);

    if ($photoArr['error'] != '') {
        $requestAjax['error'] = 'cannot get photo from database';//. Ercode = '.$photoArr['error'];
        echo json_encode($requestAjax);
        exit;
    }

    for ($i=1; $i <= $photoAmount && isset($photoArr['img'.$i]); $i++) {
        $img = $photoArr['img'.$i];
        if ($img != false) {
            $img['name'] = xmlDefense($img['name']);
            $img['author'] = xmlDefense($img['author']);
            $img['data'] = base64_encode($img['data']);
            $img['likeCount'] = $img['likeCounter'];
            $img['isLiked'] = isUserLikedPhoto($connectDB, $user, $img['id']);
            $img['isAuthor'] = ($user === $img['author']) ? 1 : 0;
            $requestAjax['img'.$i] = $img;
        }
    }
}

echo json_encode($requestAjax);

?>