<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home page</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/settings.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">Personal settings</div>
		<div id="content">
			<div id="errorMessage"><?php echo $_SESSION['last_error']; $_SESSION['last_error'] = ''; ?></div>
			
			<div id="settingsBody">
				<form action="#" method="POST">
					<div class="inputHeader">Change Login</div>
					<input type="text" class="input" name="newLogin" placeholder="new login">
					<div class="loginMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>
				<form action="#" method="POST">
					<div class="inputHeader">Change Password</div>
					<input type="password" class="input" name="passwd" placeholder="new password">
					<div class="passwdMarker"></div>
					<input type="password" class="input" name="passwdConfirm" placeholder="confirm">
					<div class="passwdConfirmMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>
				<form action="#" method="POST">
					<div class="inputHeader">Change E-mail</div>
					<input type="text" class="input" name="newEmail" placeholder="new e-mail">
					<div class="emailMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok">
				</form>
				<form action="#" method="POST">
					<input type="checkbox" class="notificationsCheckbox" checked>
					<div id="notifications">Email Notifications OFF</div>
					<!-- <input type="text" class="input" name="newEmail" placeholder="new e-mail">
					<div class="emailMarker"></div>
					<input type="submit" class="submit" name="submit" value="Ok"> -->
				</form>
			</div>
			
		</div>
		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>