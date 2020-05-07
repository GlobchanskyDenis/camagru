<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home page</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/sign.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("scripts/header.php");
		?>

		<div class="categoryTitle">What is Camagru?</div>
		<div id="content">
			<p>Camagru is my own instagram. You can register here, make photo on webcam 
				or simply upload them whith superposable images, browse gallery of a previous 
				photo's, like and comment them.</p>
			<p>I use PHP, JavaScript and MySQL</p>
		</div>

		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("scripts/footer.php");
	?>

</body>
</html>