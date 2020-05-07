<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Gallery</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/gallery.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("scripts/header.php");
		?>

		<div class="categoryTitle">The Gallery of Camagru</div>
		<section id="galery">
			<div class="item">
				<div class="itemHeader">Photo that I don't want to see</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter">2</span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Ugly</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter"></span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Its me?</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter">1</span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Photo that I don't want to see</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/liked.png" alt="like">
						<span id="counter">2</span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Ugly</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter"></span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Its me?</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter">1</span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
			<div class="item">
				<div class="itemHeader">Ugly</div>
				<img id="myPhoto" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<div class="likeBlock">
						<img id="like" src="img/like.png" alt="like">
						<span id="counter"></span>
					</div>
					<div class="comment">
						-> comments here <-
					</div>
				</div>
			</div>
		</section>


		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("scripts/footer.php");
	?>

</body>
</html>
