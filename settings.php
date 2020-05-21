<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home page</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/settings.css">
	<script src="view/updateAjaxify.js"></script>
	<script src="view/menu.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">Personal settings</div>
		<div id="content">
			<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
			
			<div id="settingsBody">
				<form action="scripts/updateLogin.php" method="POST" id="updateLogin">
					<div class="inputHeader">Change Login</div>
					<input type="text" class="input" name="login" placeholder="new login">
					<div class="loginMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>
				<form action="scripts/updatePasswd.php" method="POST" id="updatePasswd">
					<div class="inputHeader">Change Password</div>
					<input type="password" class="input" name="passwd" placeholder="new password">
					<div class="passwdMarker"></div>
					<input type="password" class="input" name="passwdConfirm" placeholder="confirm">
					<div class="passwdConfirmMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>
				<form action="scripts/updateEmail.php" method="POST" id="updateEmail">
					<div class="inputHeader">Change E-mail</div>
					<input type="text" class="input" name="email" placeholder="new e-mail">
					<div class="emailMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>

				<form action="#" method="POST">
					<input type="checkbox" class="notificationsCheckbox" onchange="updateNotifications()">
					<div id="notifications">Email Notifications OFF</div>
				</form>
			</div>
			
		</div>
		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>