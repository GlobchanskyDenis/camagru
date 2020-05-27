<?php

$photoAmount = 3;

$requestAjax = [
    'error' => false,
    'img1'     => false,
    'img2'     => false,
    'img3'     => false,
    'meta1'    => false,
    'meta2'    => false,
    'meta3'    => false,
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


$photoArr = getLastPhotosfromDB($connectDB, $photoAmount, $_SESSION['loggued_on_user']);
if ($photoArr == false || $photoArr == null) {
    $requestAjax['error'] = 'cannot get old photo from database';
    echo json_encode($requestAjax);
    exit;
}

for ($i=1; $i <= $photoAmount; $i++) {
    $img = $photoArr['img'.$i];
    if ($img != false) {
        $img['data'] = base64_encode($img['data']);
        $requestAjax['img'.$i] = $img;
    }
}

echo json_encode($requestAjax);

?>