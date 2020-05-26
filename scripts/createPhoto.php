<?php

$requestAjax = [
	'error' => '',
    'img1'	=> '',
    'img2'	=> '',
    'img3'	=> '',
];

session_start();

if (!include_once("functions.php")) {
    $requestAjax['error'] = 'cannot find functions file';
    echo json_encode($requestAjax);
	exit; 
}

if ( ($requestAjax['error'] = checkCreatePhotoRequest()) != '' ) {
	echo json_encode($requestAjax);
	exit; 
}

if (!include_once('connectDB.php')) {
	$requestAjax['error'] = 'Error: cannot find DB connection script';
	echo json_encode($requestAjax);
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
    $requestAjax['error'] = 'wait 1 second dude))';
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

if (!addPhotoDB($connectDB, $login, $filename, $name)) {
    $requestAjax['error'] = 'cannot add photo to database';
    echo json_encode($requestAjax);
    exit;
}

echo json_encode($requestAjax);

?>