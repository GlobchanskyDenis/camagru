/*

// ===== JQUERY в проекте запрещен (внезапно). Оставлю этот код на память. Он рабочий. =====

function ajaxCheckReg() {

	var loginMarker = document.querySelector(".loginMarker");
	var passwdMarker = document.querySelector(".passwdMarker");
	var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
	var emailMarker = document.querySelector(".emailMarker");

	if (document.forms['updateLogin']['login'].value == '') 
		loginMarker.style.opacity = 0;
	if (document.forms['updatePasswd']['passwd'].value == '') 
		passwdMarker.style.opacity = 0;
	if (document.forms['updatePasswd']['passwdConfirm'].value == '') 
		passwdConfirmMarker.style.opacity = 0;
	if (document.forms['updateEmail']['email'].value == '') 
		emailMarker.style.opacity = 0;


	if ((	document.forms['updateLogin']['login'].value == '' &&
			document.forms['updatePasswd']['passwd'].value == '' &&
			document.forms['updatePasswd']['passwdConfirm'].value == '' &&
			document.forms['updateEmail']['email'].value == ''))
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
		else if (document.forms['updateLogin']['login'].value != '' && data['login'] != '')
			message.innerHTML = data['login'];
		else if (document.forms['updatePasswd']['passwd'].value != '' && data['passwd'] != '')
			message.innerHTML = data['passwd'];
		else if (document.forms['updatePasswd']['passwdConfirm'].value != '' && data['passwdConfirm'] != '')
			message.innerHTML = data['passwdConfirm'];
		else if (data['email'] != '' && document.forms['updateEmail']['email'].value != '')
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

		if (document.forms['updateLogin']['login'].value != '' && data['login'] != '')
			loginMarker.style.opacity = 1;
		else
			loginMarker.style.opacity = 0;

		if (document.forms['updatePasswd']['passwd'].value != '' && data['passwd'] != '')
			passwdMarker.style.opacity = 1;
		else
			passwdMarker.style.opacity = 0;

		if (document.forms['updatePasswd']['passwdConfirm'].value != '' && data['passwdConfirm'] != '')
			passwdConfirmMarker.style.opacity = 1;
		else
			passwdConfirmMarker.style.opacity = 0;

		if (data['email'] != '' && document.forms['updateEmail']['email'].value != '')
			emailMarker.style.opacity = 1;
		else
			emailMarker.style.opacity = 0;

	}

	var newQuery = '';
	newQuery =	'login=' + document.forms['updateLogin']['login'].value + 
				'&passwd=' + document.forms['updatePasswd']['passwd'].value +
				'&passwdConfirm=' + document.forms['updatePasswd']['passwdConfirm'].value +
				'&email=' + document.forms['updateEmail']['email'].value;
	// console.log( "tx: " + newQuery );

	$.ajax({
		method: "POST",
		url:    "scripts/ajaxUpdate.php",
		data:   newQuery
	})

	.done(function (data) {
		callbackFunc(data);
	})
}

function updateNotifications() {
	var checkBox = document.querySelector(".notificationsCheckbox");
	var notif = document.getElementById("notifications");
	var data;
	if (checkBox.checked) {
		data = "data=0";
		notif.innerHTML = 'Email Notifications ON';
	} else {
		data = "data=1";
		notif.innerHTML = 'Email Notifications OFF';
	}

	function callbackFunc(data) {
		var message = document.getElementById("errorMessage");
		// console.log("before: "+data);
		data = jQuery.parseJSON(data);
		// console.log("after: "+data);
		if (data['error'] != '') {
			message.innerHTML = data['error'];
		}
	}

	// console.log(checkBox.checked);
	$.ajax({
		method: "POST",
		url:    "scripts/ajaxUpdateNotifications.php",
		data:   data
	})

	.done(function (data) {
		callbackFunc(data);
	})
}
*/

$(document).ready(function(){
	var checkBox = document.querySelector(".notificationsCheckbox");

	function callbackFunc(data) {
		var checkBox = document.querySelector(".notificationsCheckbox");
		var message = document.getElementById("errorMessage");
		var notif = document.getElementById("notifications");
		// console.log("before: "+data);
		data = jQuery.parseJSON(data);
		// console.log("after: "+data);
		if (data['error'] != '') {
			message.innerHTML = data['error'];
		}
		if (data['data']) {
			checkBox.setAttribute('checked', true);
			notif.innerHTML = 'Email Notifications ON';
		} else {
			notif.innerHTML = 'Email Notifications OFF';
		}
	}

	$.ajax({
		method: "POST",
		url:    "scripts/ajaxGetNotifications.php",
		data:   ''
	})

	.done(function (data) {
		callbackFunc(data);
	})
})

function updateNotifications() {

	// var checkBox = document.querySelector(".notificationsCheckbox");
	// var notif = document.getElementById("notifications");
	// var data;
	// if (checkBox.checked) {
	// 	data = "data=0";
	// 	notif.innerHTML = 'Email Notifications ON';
	// } else {
	// 	data = "data=1";
	// 	notif.innerHTML = 'Email Notifications OFF';
	// }

	// function callbackFunc(data) {
	// 	var message = document.getElementById("errorMessage");
	// 	// console.log("before: "+data);
	// 	data = jQuery.parseJSON(data);
	// 	// console.log("after: "+data);
	// 	if (data['error'] != '') {
	// 		message.innerHTML = data['error'];
	// 	}
	// }

	// // console.log(checkBox.checked);
	// $.ajax({
	// 	method: "POST",
	// 	url:    "scripts/ajaxUpdateNotifications.php",
	// 	data:   data
	// })

	// .done(function (data) {
	// 	callbackFunc(data);
	// })

	var checkBox = document.querySelector(".notificationsCheckbox");
	var notif = document.getElementById("notifications");

	console.log('');
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "scripts/ajaxUpdateNotifications.php");
	xhr.responseType = 'json';
	let formData = new FormData();
	if (checkBox.checked) {
		formData.append("data", 0);
		console.log('tx: data=0');
		notif.innerHTML = 'Email Notifications ON';
	} else {
		formData.append("data", 1);
		console.log('tx: data=1');
		notif.innerHTML = 'Email Notifications OFF';
	}
	xhr.send(formData);

	xhr.onload = function() {
		if (xhr.status != 200) {
			console.log(`Ошибка ${xhr.status}: ${xhr.statusText}`);
		} else {
			var message = document.getElementById("errorMessage");
			var requestData= xhr.response;
			console.log("rx: error="+requestData.error);
			if (requestData.error != '') {
				message.innerHTML = requestData.error;
			}
		}
	}

	// in invalid request case
	xhr.onerror = function() {
		console.log("Запрос не удался");
	};
}

function asyncRequest() {

	// bind, initialize marker. check necessity for async request
	var loginMarker = document.querySelector(".loginMarker");
	var passwdMarker = document.querySelector(".passwdMarker");
	var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
	var emailMarker = document.querySelector(".emailMarker");

	if (document.forms['updateLogin']['login'].value == '') 
		loginMarker.style.opacity = 0;
	if (document.forms['updatePasswd']['passwd'].value == '') 
		passwdMarker.style.opacity = 0;
	if (document.forms['updatePasswd']['passwdConfirm'].value == '') 
		passwdConfirmMarker.style.opacity = 0;
	if (document.forms['updateEmail']['email'].value == '') 
		emailMarker.style.opacity = 0;

	if ((	document.forms['updateLogin']['login'].value == '' &&
			document.forms['updatePasswd']['passwd'].value == '' &&
			document.forms['updatePasswd']['passwdConfirm'].value == '' &&
			document.forms['updateEmail']['email'].value == ''))		{
		return;
	}

	// form and send request
	console.log('');
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "scripts/ajaxUpdate.php");
	xhr.responseType = 'json';
	let formData = new FormData();

	formData.append("login", document.forms['updateLogin']['login'].value);
	formData.append("passwd", document.forms['updatePasswd']['passwd'].value);
	formData.append("passwdConfirm", document.forms['updatePasswd']['passwdConfirm'].value);
	formData.append("email", document.forms['updateEmail']['email'].value);

	console.log('tx: login='+document.forms['updateLogin']['login'].value);
	console.log('tx: passwd='+document.forms['updatePasswd']['passwd'].value);
	console.log('tx: passwdConfirm='+document.forms['updatePasswd']['passwdConfirm'].value);
	console.log('tx: email='+document.forms['updateEmail']['email'].value);

	xhr.send(formData);	

	// in valid request case
	xhr.onload = function() {
		if (xhr.status != 200) {
			console.log(`Ошибка ${xhr.status}: ${xhr.statusText}`);
		} else {
			var loginMarker = document.querySelector(".loginMarker");
			var passwdMarker = document.querySelector(".passwdMarker");
			var passwdConfirmMarker = document.querySelector(".passwdConfirmMarker");
			var emailMarker = document.querySelector(".emailMarker");

			var requestData= xhr.response;
			var message = document.getElementById("errorMessage");

			if (requestData.request != '')
				message.innerHTML = requestData.request;
			else if (document.forms['updateLogin']['login'].value != '' && requestData.login != '')
				message.innerHTML = requestData.login;
			else if (document.forms['updatePasswd']['passwd'].value != '' && requestData.passwd != '')
				message.innerHTML = requestData.passwd;
			else if (document.forms['updatePasswd']['passwdConfirm'].value != '' && requestData.passwdConfirm != '')
				message.innerHTML = requestData.passwdConfirm;
			else if (requestData.email != '' && document.forms['updateEmail']['email'].value != '')
				message.innerHTML = requestData.email;
			else
				message.innerHTML = '';

			if (document.forms['updateLogin']['login'].value != '' && requestData.login != '')
				loginMarker.style.opacity = 1;
			else
				loginMarker.style.opacity = 0;

			if (document.forms['updatePasswd']['passwd'].value != '' && requestData.passwd != '')
				passwdMarker.style.opacity = 1;
			else
				passwdMarker.style.opacity = 0;

			if (document.forms['updatePasswd']['passwdConfirm'].value != '' && requestData.passwdConfirm != '')
				passwdConfirmMarker.style.opacity = 1;
			else
				passwdConfirmMarker.style.opacity = 0;

			if (requestData.email != '' && document.forms['updateEmail']['email'].value != '')
				emailMarker.style.opacity = 1;
			else
				emailMarker.style.opacity = 0;

			console.log('rx: request='+requestData.request);
			console.log('rx: login='+requestData.login);
			console.log('rx: passwd='+requestData.passwd);
			console.log('rx: passwdConfirm='+requestData.passwdConfirm);
			console.log('rx: email='+requestData.email);
		}
	}

	// in invalid request case
	xhr.onerror = function() {
		console.log("Запрос не удался");
	};
}

setInterval( asyncRequest, 2000);