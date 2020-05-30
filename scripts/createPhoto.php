<?php

$requestAjax = [
	'error' => ''
];

session_start();

if (!include_once("functions.php")) {
    $requestAjax['error'] = 'cannot find functions file';
    echo json_encode($requestAjax);
	exit; 
}

if (!include_once("../config/rules.php")) {
    $requestAjax['error'] = 'cannot find important file';
    echo json_encode($requestAjax);
	exit; 
}

if ( ($requestAjax['error'] = checkCreatePhotoRequest()) != '' ) {
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
	$requestAsync['error'] = 'Cannot connect to Database';
	echo json_encode($requestAsync);
	exit;
}

$name = $_REQUEST['name'];
$img = $_REQUEST['img'];
$filter = $_REQUEST['filter'];
$date = date('Y:m:d_h:i:s:');
$time = microtime();
$msec = $time[2];
$login = $_SESSION['loggued_on_user'];
$filename = '../photoStorage/'.$login.'_'.$date.$msec.'.png';

$img = str_replace('data:image/png;base64,', '', $img);
$data = base64_decode($img);
if (!file_exists('../photoStorage')) {
    $requestAjax['error'] = 'photo folder does not exists';
    echo json_encode($requestAjax);
    exit; 
}
if (file_exists($filename)) {
    $requestAjax['error'] = 'wait 1 second';
    echo json_encode($requestAjax);
    exit; 
}
if (!file_put_contents($filename , $data)) {
    $requestAjax['error'] = 'cannot create photo';
    echo json_encode($requestAjax);
    exit; 
}

$img1 = imagecreatefrompng($filename);
$img2 = imagecreatefrompng('../img/filters/filter'.$filter.'.png');

$cut = imagecreatetruecolor(480, 360); 
imagecopy($cut, $img1, 0, 0, 0, 0, 480, 360);
imagecopy($cut, $img2, 0, 0, 0, 0, 480, 360);
if (!imagepng($cut, $filename)) {
    $requestAjax['error'] = 'cannot save filtered photo';
    echo json_encode($requestAjax);
    exit;
}

if (!($data = file_get_contents($filename))) {
    $requestAjax['error'] = 'cannot open filtered photo';
    echo json_encode($requestAjax);
    exit;
}

if (!addPhotoDB($connectDB, $login, $filename, $name, $data)) {
    $requestAjax['error'] = 'cannot add photo to database';
    echo json_encode($requestAjax);
    exit;
}

if (!unlink($filename)) {
    $requestAjax['error'] = 'cannot delete temp photo';
    echo json_encode($requestAjax);
    exit;
}

echo json_encode($requestAjax);

?>