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
					<div class="videoBox">
						<canvas id="canvas">
						</canvas>
						<img src="img/filters/filter0.png" id="filter">
						<video id="video" title="Advertisement" webkit-playsinline="true" playsinline="true" muted="muted">
						<!-- autoplay -->
						<!-- <source src="video.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'> -->
						<!-- <source src="http://youtube.com/watch?v=a5uQMwRMHcs"> -->
						<!-- <img src="img/480_360+placeholder.png"> -->
						</video>
					</div>
					
				</section>
				<section class="stickers">
					<div class="stickerGrid1">
						<div class="sticker" id="st0" onclick="selectFilter0()"></div>
						<img src="img/stickers/sticker1.png" class="sticker" id="st1" onclick="selectFilter1()">
						<img src="img/stickers/sticker2.png" class="sticker" id="st2" onclick="selectFilter2()">
						<img src="img/stickers/sticker3.png" class="sticker" id="st3" onclick="selectFilter3()">
					</div>
					<div class="stickerGrid2">
						<img src="img/stickers/sticker4.png" class="sticker" id="st4" onclick="selectFilter4()">
						<img src="img/stickers/sticker5.png" class="sticker" id="st5" onclick="selectFilter5()">
						<img src="img/stickers/sticker6.png" class="sticker" id="st6" onclick="selectFilter6()">
					</div>
				</section>
			</section>
			<section class="lastSnaps">
				<!-- placeholder_640_360.png -->
				<img src="img/480_360+placeholder.png">
				<img src="img/480_360+placeholder.png">
				<img src="img/480_360+placeholder.png">
			</section>
		</section>
		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>
