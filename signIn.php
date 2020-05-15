<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Autentication</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/sign.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">Autentication</div>

		<div id="content">
			<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
			<form id="signInMenu" method="post" action="scripts/authenticate.php">
				<div class="inputHeader">login</div>
				<input type="text" class="input" name="login" placeholder="Type in your login">
				<div class="loginMarker"></div>
				<div class="inputHeader">password</div>
				<input type="password" class="input" name="passwd" placeholder="Type in password">
				<div class="passwdMarker"></div>
				<input type="submit" class="submit" name="submit" value="signIn">
			</form>
		</div>

		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>