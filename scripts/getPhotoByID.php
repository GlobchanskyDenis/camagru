<?php

$requestAjax = [
    'error'    => "",
    'img'     => false,
];

if (!isset($_REQUEST['photoID']) || !is_numeric($_REQUEST['photoID']) || $_REQUEST['photoID'] <= 0) {
    $requestAjax['error'] = 'Invalid request';
    echo json_encode($requestAjax);
	exit;
}

$photoID = (int)($_REQUEST['photoID']);

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

if (!($photo = getPhotoByIdFromDB($connectDB, $photoID))) {
	$requestAjax['error'] = 'cannot get photo from database';
	echo json_encode($requestAjax);
	exit;
}

// $img['name'] = xmlDefense($img['name']);
//             $img['author'] = xmlDefense($img['author']);
$requestAjax['img'] = base64_encode($photo['data']);

echo json_encode($requestAjax);

?>