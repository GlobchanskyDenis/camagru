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
	<link rel="stylesheet" href="css/headerFooter.css">
	<link rel="stylesheet" href="css/sign.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;1,200;1,400;1,600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
	<div id="wrapper">

		<?php require("scripts/header.php");
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

	<?php require("scripts/footer.php");
	?>
	<script>

		function ajaxCheckReg() {

			var loginMarker = document.querySelector(".loginMarker");
			var passwdMarker = document.querySelector(".passwdMarker");
			var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
			var emailMarker = document.querySelector(".emailMarker");

			if (document.forms['signUpMenu']['login'].value == '') 
				loginMarker.style.opacity = 0;
			if (document.forms['signUpMenu']['passwd'].value == '') 
				passwdMarker.style.opacity = 0;
			if (document.forms['signUpMenu']['passwdConfirm'].value == '') 
				passwdConfirmMarker.style.opacity = 0;
			if (document.forms['signUpMenu']['email'].value == '') 
				emailMarker.style.opacity = 0;

			// var login = document.forms['signUpMenu']['login'].value;
			// var passwd = document.forms['signUpMenu']['passwd'].value;
			// var passwdConfirm = document.forms['signUpMenu']['passwdConfirm'].value;
			// var email = document.forms['signUpMenu']['email'].value;

			if ((	document.forms['signUpMenu']['login'].value == '' &&
					document.forms['signUpMenu']['passwd'].value == '' &&
					document.forms['signUpMenu']['passwdConfirm'].value == '' &&
					document.forms['signUpMenu']['email'].value == ''))
				// 	 ||
				// (	login == document.forms['signUpMenu']['login'].value &&
				// 	passwd == document.forms['signUpMenu']['passwd'].value &&
				// 	passwdConfirm == document.forms['signUpMenu']['passwdConfirm'].value &&
				// 	email == document.forms['signUpMenu']['email'].value
				// ))
			{
				return;
			}

			function callbackFunc(data) {

				var loginMarker = document.querySelector(".loginMarker");
				var passwdMarker = document.querySelector(".passwdMarker");
				var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
				var emailMarker = document.querySelector(".emailMarker");

				data = jQuery.parseJSON(data);
				var message = document.getElementById("errorMessage");

				if (data['request'] != '')
					message.innerHTML = data['request'];
				else if (document.forms['signUpMenu']['login'].value != '' && data['login'] != '')
					message.innerHTML = data['login'];
				else if (document.forms['signUpMenu']['passwd'].value != '' && data['passwd'] != '')
					message.innerHTML = data['passwd'];
				else if (document.forms['signUpMenu']['passwdConfirm'].value != '' && data['passwdConfirm'] != '')
					message.innerHTML = data['passwdConfirm'];
				else if (data['email'] != '' && document.forms['signUpMenu']['email'].value != '')
					message.innerHTML = data['email'];
				else
					message.innerHTML = '';

				console.log( "rx: "+data['other']+' '+ 
									data['login']+' '+
									data['passwd']+' '+
									data['passwdConfirm']+' '+
									data['email']+' '+
									data['request']
							);

				if (document.forms['signUpMenu']['login'].value != '' && data['login'] != '')
					loginMarker.style.opacity = 1;
				else
					loginMarker.style.opacity = 0;

				if (document.forms['signUpMenu']['passwd'].value != '' && data['passwd'] != '')
					passwdMarker.style.opacity = 1;
				else
					passwdMarker.style.opacity = 0;

				if (document.forms['signUpMenu']['passwdConfirm'].value != '' && data['passwdConfirm'] != '')
					passwdConfirmMarker.style.opacity = 1;
				else
					passwdConfirmMarker.style.opacity = 0;

				if (data['email'] != '' && document.forms['signUpMenu']['email'].value != '')
					emailMarker.style.opacity = 1;
				else
					emailMarker.style.opacity = 0;

			}

			var newQuery = '';
			newQuery =	'login=' + document.forms['signUpMenu']['login'].value + 
						'&passwd=' + document.forms['signUpMenu']['passwd'].value +
						'&passwdConfirm=' + document.forms['signUpMenu']['passwdConfirm'].value +
						'&email=' + document.forms['signUpMenu']['email'].value;
			console.log( "tx: " + newQuery );

			$.ajax({
				method: "POST",
				url:    "scripts/ajaxCheckReg.php",
        		data:   newQuery
    		})

    		.done(function (data) {
        		callbackFunc(data);
    		})
		}

		setInterval( ajaxCheckReg, 5000);

	</script>
</body>
</html>