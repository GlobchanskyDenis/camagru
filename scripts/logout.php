<?php
session_start();

$_SESSION = [];
unset($_COOKIE[session_name()]);
session_destroy();
header('Location: ../index.php');
?>