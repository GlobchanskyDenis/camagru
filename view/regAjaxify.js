/*

// ===== JQUERY в проекте запрещен (внезапно). Оставлю этот код на память. Он рабочий. =====

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

	

	if ((	document.forms['signUpMenu']['login'].value == '' &&
			document.forms['signUpMenu']['passwd'].value == '' &&
			document.forms['signUpMenu']['passwdConfirm'].value == '' &&
			document.forms['signUpMenu']['email'].value == ''))
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

		// console.log( "rx: "+data['other']+' '+ 
		// 					data['login']+' '+
		// 					data['passwd']+' '+
		// 					data['passwdConfirm']+' '+
		// 					data['email']+' '+
		// 					data['request']
		// 			);

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
	// console.log( "tx: " + newQuery );

	$.ajax({
		method: "POST",
		url:    "scripts/ajaxCheckReg.php",
		data:   newQuery
	})

	.done(function (data) {
		callbackFunc(data);
	})
}
*/

function asyncRequest() {

	// bind, initialize marker. check necessity for async request
	var loginMarker = document.querySelector(".loginMarker");
	var passwdMarker = document.querySelector(".passwdMarker");
	var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
	var emailMarker = document.querySelector(".emailMarker");
	var necessity = 0;

	if (document.forms['signUpMenu']['login'].value == '') 
		loginMarker.style.opacity = 0;
	if (document.forms['signUpMenu']['passwd'].value == '') 
		passwdMarker.style.opacity = 0;
	if (document.forms['signUpMenu']['passwdConfirm'].value == '') 
		passwdConfirmMarker.style.opacity = 0;
	if (document.forms['signUpMenu']['email'].value == '') 
		emailMarker.style.opacity = 0;

	if (document.forms['signUpMenu']['login'].value != '' &&
			window.gLastLogin != document.forms['signUpMenu']['login'].value) {
		necessity = 1;
	}
	if (document.forms['signUpMenu']['passwd'].value != '' &&
			window.gLastPasswd != document.forms['signUpMenu']['passwd'].value) {
		necessity = 1;
	}
	if (document.forms['signUpMenu']['passwdConfirm'].value != '' &&
			window.gLastPasswdConfirm != document.forms['signUpMenu']['passwdConfirm'].value) {
		necessity = 1;
	}
	if (document.forms['signUpMenu']['email'].value != '' &&
			window.gLastEmail != document.forms['signUpMenu']['email'].value) {
		necessity = 1;
	}
	if (necessity == 0)	{
		return ;
	}

	// form and send request
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "scripts/ajaxCheckReg.php");
	xhr.responseType = 'json';
	let formData = new FormData();

	formData.append("login", document.forms['signUpMenu']['login'].value);
	formData.append("passwd", document.forms['signUpMenu']['passwd'].value);
	formData.append("passwdConfirm", document.forms['signUpMenu']['passwdConfirm'].value);
	formData.append("email", document.forms['signUpMenu']['email'].value);

	// console.log('');
	// console.log('tx: login='+document.forms['signUpMenu']['login'].value);
	// console.log('tx: passwd='+document.forms['signUpMenu']['passwd'].value);
	// console.log('tx: passwdConfirm='+document.forms['signUpMenu']['passwdConfirm'].value);
	// console.log('tx: email='+document.forms['signUpMenu']['email'].value);

	xhr.send(formData);	

	// in valid request case
	xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById("errorMessage").innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
			var loginMarker = document.querySelector(".loginMarker");
			var passwdMarker = document.querySelector(".passwdMarker");
			var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
			var emailMarker = document.querySelector(".emailMarker");

			var requestData= xhr.response;
			var message = document.getElementById("errorMessage");

			if (requestData.request != '')
				message.innerHTML = requestData.request;
			else if (document.forms['signUpMenu']['login'].value != '' && requestData.login != '')
				message.innerHTML = requestData.login;
			else if (document.forms['signUpMenu']['passwd'].value != '' && requestData.passwd != '')
				message.innerHTML = requestData.passwd;
			else if (document.forms['signUpMenu']['passwdConfirm'].value != '' && requestData.passwdConfirm != '')
				message.innerHTML = requestData.passwdConfirm;
			else if (requestData.email != '' && document.forms['signUpMenu']['email'].value != '')
				message.innerHTML = requestData.email;
			else
				message.innerHTML = '';

			if (document.forms['signUpMenu']['login'].value != '' && requestData.login != '')
				loginMarker.style.opacity = 1;
			else
				loginMarker.style.opacity = 0;

			if (document.forms['signUpMenu']['passwd'].value != '' && requestData.passwd != '')
				passwdMarker.style.opacity = 1;
			else
				passwdMarker.style.opacity = 0;

			if (document.forms['signUpMenu']['passwdConfirm'].value != '' && requestData.passwdConfirm != '')
				passwdConfirmMarker.style.opacity = 1;
			else
				passwdConfirmMarker.style.opacity = 0;

			if (requestData.email != '' && document.forms['signUpMenu']['email'].value != '')
				emailMarker.style.opacity = 1;
			else
				emailMarker.style.opacity = 0;

			// console.log('rx: request='+requestData.request);
			// console.log('rx: login='+requestData.login);
			// console.log('rx: passwd='+requestData.passwd);
			// console.log('rx: passwdConfirm='+requestData.passwdConfirm);
			// console.log('rx: email='+requestData.email);
		}
	}

	// in invalid request case
	xhr.onerror = function() {
		document.getElementById("errorMessage").innerHTML = "Запрос не удался";
	};

	// This is old data for now
	window.gLastLogin = document.forms['signUpMenu']['login'].value;
	window.gLastPasswd = document.forms['signUpMenu']['passwd'].value;
	window.gLastPasswdConfirm = document.forms['signUpMenu']['passwdConfirm'].value;
	window.gLastEmail = document.forms['signUpMenu']['email'].value;
}

var gLastLogin = '';
var gLastPasswd = '';
var gLastPasswdConfirm = '';
var gLastEmail = '';

setInterval( asyncRequest, 1000);