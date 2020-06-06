<?php
	if (!include_once('config/rules.php')) {
		$_SESSION['last_error'] = 'cant find one of files';
		header('Location: signIn.php');
		exit;
	}
	if (!isset($_SESSION)) {
		session_start();
	}

	if (!isset($_SESSION['loggued_on_user'])) {
		$_SESSION['last_error'] = 'You are not logged';
		header('Location: signIn.php');
		exit;
	}
	if (!isset($_SESSION['status'])) {
		$_SESSION['last_error'] = 'Cant find your user status';
		header('Location: signIn.php');
		exit;
	}
	$accessPermission = false;
	foreach ($registered as $permittedStatus) {
		if ($permittedStatus == $_SESSION['status']) {
			$accessPermission = true;
		}
	}
	if (!$accessPermission) {
		$_SESSION['last_error'] = 'You have no rights to go there';
		header('Location: signIn.php');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home page</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/profile.css">
	<script src="view/menu.js"></script>
	<script src="view/profile.js"></script>
	<script src="view/notifications.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">User profile</div>
		<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
		<section id="galery">
		</section>

		<img src="img/getPhotos.png" id="getPhotos">

		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>