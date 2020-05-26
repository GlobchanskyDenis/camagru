<?php

$requestAjax = [
    'error' => false,
    'img1'  => false,
    'img2'  => false,
    'img3'  => false,
];

session_start();

if (!include_once("functions.php")) {
    $requestAjax['error'] = 'cannot find functions file';
    echo json_encode($requestAjax);
	exit; 
}

if (!include_once('connectDB.php')) {
	$requestAjax['error'] = 'Error: cannot find DB connection script';
	echo json_encode($requestAjax);
	exit;
}

$photoArr = get3LastPhotosfromDB($connectDB, $_SESSION['loggued_on_user']);
if ($photoArr == false || $photoArr == null) {
    $requestAjax['error'] = 'cannot get old photo from database';
    echo json_encode($requestAjax);
    exit;
}

$requestAjax['img1'] = $photoArr['img1'];
$requestAjax['img2'] = $photoArr['img2'];
$requestAjax['img3'] = $photoArr['img3'];

echo json_encode($requestAjax);

?>