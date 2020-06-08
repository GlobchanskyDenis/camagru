<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mail Confirm Page</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/mailConfirm.css">
	<script src="view/menu.js"></script>
	<script src="view/mailConfirmAjaxify.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">Mail validation</div>

		<div id="content">
			<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
			<div id="mailConfirmBody">
				<form method="post" action="scripts/validate.php">
					<div class="inputHeader">Mail confirm code</div>
					<input type="text" name="code" id="code" placeholder="Put confirm code here">
					<input type="submit" class="submit" value="Confirm">
				</form>
				<form method="POST" action="scripts/resendMail.php">
					<div class="inputHeader">Resend mail code?</div>
					<input type="submit" class="submit" value="Resend">
				</form>
				<form method="post" action="scripts/changeMail.php" id="updateEmail">
					<div class="inputHeader">Or change your mail</div>
					<input type="text" name="email" id="email">
					<div class="emailMarker"></div>
					<input type="submit" class="submit" value="Change">
				</form>
			</div>
		</div>

		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>