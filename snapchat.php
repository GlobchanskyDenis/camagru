<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Gallery</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/snap.css">
	<script src="view/menu.js"></script>
	<script src="view/webcam.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitleGrid">
			<div class="title">Push to take shot</div>
			<img src="img/takeshot.png" class="takeshot" onclick="takeshot()">
		</div>
		<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
		<section class="content">
			<section class="snap">
				<section class="preview">
					<form name="snapMetadata">
						<input type="text" placeholder="Name your photo here" name="name" class="snapTitle">
						<input type="text" name="takePhotoPermission" hidden>
						<input type="text" name="offsetX" hidden>
						<input type="text" name="offsetX" hidden>
						<input type="text" name="srcImgPath" hidden>
					</form>
					<div class="videoBox">
						
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
				<img src="img/480_360+placeholder.png" class="lastSnap" id="snap1">
				<img src="img/480_360+placeholder.png" class="lastSnap" id="snap2">
				<img src="img/480_360+placeholder.png" class="lastSnap" id="snap3">
			</section>
		</section>
		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>
