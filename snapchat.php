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
	<title>Snapchat</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/snap.css">
	<script src="view/menu.js"></script>
	<script src="view/webcam.js"></script>
	<script src="view/notifications.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitleGrid">
		<!-- onclick="chooseFile()" -->
			<div class="title">Take photo here</div>
			<img src="img/takeshot.png" class="takeshot" onclick="takeshot()" title="take shot">
			<form class="takeshot" id="upload" title="choose your file">
				<input type="file" accept="image/*" onchange="readURL(this);">
			</form>
		</div>
		<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
		<section class="content">
			<section class="snapMaker">
				<section class="preview">
					<form name="snapMetadata">
						<input type="text" placeholder="Name your photo here" name="name" class="snapTitle">
						<input type="text" name="srcImgPath" hidden>
					</form>
					<div class="videoBox">
						<img id="uploadPhoto">
						<img src="img/filters/filter0.png" id="filter">
						<video id="video" title="Advertisement" webkit-playsinline="true" playsinline="true" muted="muted">
						</video>
					</div>
					
				</section>
				<section class="stickers">
					<form>
						<div class="stickerGrid1">
							<label>
								<input type="radio" class="radio" name="sticker" value="0" onclick="selectFilter0()" checked>
								<div class="sticker"></div>
							</label>
							<label>
								<input type="radio" class="radio" name="sticker" value="1" onclick="selectFilter1()">
								<img src="img/stickers/sticker1.png" class="sticker">
							</label>
							<label>
								<input type="radio" class="radio" name="sticker" value="2" onclick="selectFilter2()">
								<img src="img/stickers/sticker2.png" class="sticker">
							</label>
							<label>
								<input type="radio" class="radio" name="sticker" value="3" onclick="selectFilter3()">
								<img src="img/stickers/sticker3.png" class="sticker">
							</label>
						</div>
						<div class="stickerGrid2">
							<label>
								<input type="radio" class="radio" name="sticker" value="4" onclick="selectFilter4()">
								<img src="img/stickers/sticker4.png" class="sticker">
							</label>
							<label>
								<input type="radio" class="radio" name="sticker" value="5" onclick="selectFilter5()">
								<img src="img/stickers/sticker5.png" class="sticker">
							</label>
							<label>
								<input type="radio" class="radio" name="sticker" value="6" onclick="selectFilter6()">
								<img src="img/stickers/sticker6.png" class="sticker">
							</label>
						</div>
					</form>
				</section>
			</section>
			<section class="lastSnaps">
				<div class="snapBox" id="snapBox1">
					<img src="img/deleteSnap.png" class="deleteSnap" id="del1">
					<img src="" class="snap" id="snap1">
				</div>
				<div class="snapBox" id="snapBox2">
					<img src="img/deleteSnap.png" class="deleteSnap" id="del2">
					<img src="" class="snap" id="snap2">
				</div>
				<div class="snapBox" id="snapBox3">
					<img src="img/deleteSnap.png" class="deleteSnap" id="del3">
					<img src="" class="snap" id="snap3">
				</div>
			</section>
		</section>
		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>
