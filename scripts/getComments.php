<?php

$requestAjax = [
    'error'    => ""
];

if (!isset($_REQUEST['commentAmount']) || !is_numeric($_REQUEST['commentAmount']) ||
        $_REQUEST['commentAmount'] <= 0 || $_REQUEST['commentAmount'] > 100) {
    $requestAjax['error'] = 'Invalid request';
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
    $requestAjax['error'] = 'Invalid request in getPhotosByAuthor 2 Request='.$_REQUEST['lastID'];
    echo json_encode($requestAjax);
	exit;
}

$photoID = (int)$_REQUEST['photoID'];
$commentAmount = (int)($_REQUEST['commentAmount']);

for ( $i = 1; $i < $commentAmount; $i++ ) {
    $requestAjax['comment'.$i] = false;
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
    $commentArr = getCommentsByPhotoIdFromDB($connectDB, $commentAmount, $photoID, 0);

    if ($commentArr['error'] != '') {
        $requestAjax['error'] = 'cannot get comments from database';//. Ercode = '.$photoArr['error'];
        echo json_encode($requestAjax);
        exit;
    }

    for ($i=1; $i <= $commentAmount && isset($commentArr['comment'.$i]); $i++) {
        $comment = $commentArr['comment'.$i];
        if ($comment != false) {
            $comment['date'] = xmlDefense($comment['date']);
            $comment['author'] = xmlDefense($comment['author']);
            $comment['text'] = str_replace("\n", '</br>', xmlDefense($comment['text']));
            $requestAjax['comment'.$i] = $comment;
        }
    }
} else {

    // Это выполняется в случае, если фото нужны начиная НЕ с самого последнего
    $commentArr = getCommentsByPhotoIdFromDB($connectDB, $commentAmount, $photoID, $_REQUEST['lastID']);

    if ($commentArr['error'] != '') {
        $requestAjax['error'] = 'cannot get comments from database';//. Ercode = '.$photoArr['error'];
        echo json_encode($requestAjax);
        exit;
    }

    for ($i=1; $i <= $commentAmount && isset($commentArr['comment'.$i]); $i++) {
        $comment = $commentArr['comment'.$i];
        if ($comment != false) {
            $comment['date'] = xmlDefense($comment['date']);
            $comment['author'] = xmlDefense($comment['author']);
            $comment['text'] = str_replace("\n", '</br>', xmlDefense($comment['text']));
            $requestAjax['comment'.$i] = $comment;
        }
    }
}

echo json_encode($requestAjax);

?>