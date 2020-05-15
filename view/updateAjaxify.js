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
	var data;
	if (checkBox.checked) {
		data = "data=0";
	} else {
		data = "data=1";
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

$(document).ready(function(){
	var checkBox = document.querySelector(".notificationsCheckbox");

	function callbackFunc(data) {
		var checkBox = document.querySelector(".notificationsCheckbox");
		var message = document.getElementById("errorMessage");
		// console.log("before: "+data);
		data = jQuery.parseJSON(data);
		// console.log("after: "+data);
		if (data['error'] != '') {
			message.innerHTML = data['error'];
		}
		if (data['data']) {
			checkBox.setAttribute('checked', true);
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

setInterval( ajaxCheckReg, 2000);