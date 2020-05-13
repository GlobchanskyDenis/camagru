<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script src="view/regAjaxify.js"></script>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/sign.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">Registration menu</div>

		<div id="content">
			<div id="errorMessage"><?php echo $_SESSION['last_error']; $_SESSION['last_error'] = '';?></div>
			<form id="signUpMenu" method="post" action="scripts/registration.php">
				<div class="inputHeader">login</div>
				<input type="text" class="input" name="login" placeholder="Type in your login">
				<div class="loginMarker"></div>
				<div class="inputHeader">password</div>
				<input type="password" class="input" name="passwd" placeholder="Type in password">
				<div class="passwdMarker"></div>
				<div class="inputHeader">confirm password</div>
				<input type="password" class="input" name="passwdConfirm" placeholder="Type in password">
				<div class="passwdConfirmMarker"></div>
				<div class="inputHeader">e-mail</div>
				<input type="text" class="input" name="email" placeholder="Type in e-mail">
				<div class="emailMarker"></div>
				<input type="submit" class="submit" name="submit" value="signUp">
			</form>
		</div>

		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>