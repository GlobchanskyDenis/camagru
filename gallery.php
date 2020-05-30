<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Gallery</title>
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/gallery.css">
	<script src="view/menu.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("view/header.php");
		?>

		<div class="categoryTitle">The Gallery of Camagru</div>
		<div id="errorMessage"><?php if (isset($_SESSION['last_error'])) {echo $_SESSION['last_error']; $_SESSION['last_error'] = '';} ?></div>
		<section class="galery">

			<div class="item" id="item1">
				<img src="img/deleteSnap.png" class="delete" id="delete1">
				<div class="itemHeader" id="itemHeader1">Photo that I don't want to see</div>
				<img class="photo" id="photo1" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like1" src="img/like.png" title="like">
					<div class="counter" id="counter1"></div>
					<div class="author" id="author1">author</div>
					<img src="img/comment.png" class="comments" id="comments1" title="comments">
				</div>
			</div>

			<div class="item" id="item2">
				<img src="img/deleteSnap.png" class="delete" id="delete2">
				<div class="itemHeader" id="itemHeader2">Ugly</div>
				<img class="photo" id="photo2" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like2" src="img/liked.png" title="like">
					<div class="counter" id="counter2">1223</div>
					<div class="author" id="author2">bsabre___012345</div>
					<img src="img/comment.png" class="comments" id="comments2" title="comments">
				</div>
			</div>

			<div class="item" id="item3">
				<img src="img/deleteSnap.png" class="delete" id="delete3">
				<div class="itemHeader" id="itemHeader3">Photo that I don't want to see12345</div>
				<img class="photo" id="photo3" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like3" src="img/like.png" title="like">
					<div class="counter" id="counter3">2</div>
					<div class="author" id="author3">admin</div>
					<img src="img/comment.png" class="comments" id="comments3" title="comments">
				</div>
			</div>

			<div class="item" id="item1">
				<img src="img/deleteSnap.png" class="delete" id="delete1">
				<div class="itemHeader" id="itemHeader1">Photo that I don't want to see</div>
				<img class="photo" id="photo1" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like1" src="img/like.png" title="like">
					<div class="counter" id="counter1"></div>
					<div class="author" id="author1">author</div>
					<img src="img/comment.png" class="comments" id="comments1" title="comments">
				</div>
			</div>

			<div class="item" id="item2">
				<img src="img/deleteSnap.png" class="delete" id="delete2">
				<div class="itemHeader" id="itemHeader2">Ugly</div>
				<img class="photo" id="photo2" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like2" src="img/liked.png" title="like">
					<div class="counter" id="counter2">1223</div>
					<div class="author" id="author2">bsabre___012345</div>
					<img src="img/comment.png" class="comments" id="comments2" title="comments">
				</div>
			</div>

			<div class="item" id="item3">
				<img src="img/deleteSnap.png" class="delete" id="delete3">
				<div class="itemHeader" id="itemHeader3">Photo that I don't want to see12345</div>
				<img class="photo" id="photo3" src="img/480_360+placeholder.png">
				<div class="itemFooter">
					<img class="like" id="like3" src="img/like.png" title="like">
					<div class="counter" id="counter3">2</div>
					<div class="author" id="author3">admin</div>
					<img src="img/comment.png" class="comments" id="comments3" title="comments">
				</div>
			</div>

			
		</section>


		<div style="height: 25px; width: 100%"></div> <!-- НЕ УДАЛЯТЬ!!! ЭТО ДЛЯ КОРРЕКТНОГО ОТОБРАЖЕНИЯ ПОДВАЛА -->
	</div>

	<?php require("view/footer.php");
	?>

</body>
</html>
