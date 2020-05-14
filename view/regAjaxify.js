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

setInterval( ajaxCheckReg, 2000);