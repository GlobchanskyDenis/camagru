/*

// ===== JQUERY в проекте запрещен (внезапно). Оставлю этот код на память. Он рабочий. =====

function ajaxCheckReg() {

	var emailMarker = document.querySelector(".emailMarker");
	if (document.forms['updateEmail']['email'].value == '') 
		emailMarker.style.opacity = 0;
	if ((	document.forms['updateEmail']['email'].value == '')) {
		return;
	}

	function callbackFunc(data) {
		var emailMarker = document.querySelector(".emailMarker");
		data = jQuery.parseJSON(data);
		var message = document.getElementById("errorMessage");

		if (data['request'] != '')
			message.innerHTML = data['request'];
		else if (data['email'] != '' && document.forms['updateEmail']['email'].value != '')
			message.innerHTML = data['email'];
		else
			message.innerHTML = '';

		// console.log( "rx: "+data['email']+' '+data['request']);

		if (data['email'] != '' && document.forms['updateEmail']['email'].value != '')
			emailMarker.style.opacity = 1;
		else
			emailMarker.style.opacity = 0;
	}

	var newQuery = '';
	newQuery =	'email=' + document.forms['updateEmail']['email'].value;
	
	// console.log( "tx: " + newQuery );

	$.ajax({
		method: "POST",
		url:    "scripts/ajaxUpdateMail.php",
		data:   newQuery
	})

	.done(function (data) {
		callbackFunc(data);
	})
}
*/

function asyncRequest() {

	// bind, initialize marker. check necessity for async request
	var emailMarker = document.querySelector(".emailMarker");
	if (document.forms['updateEmail']['email'].value == '') 
		emailMarker.style.opacity = 0;
	if ((	document.forms['updateEmail']['email'].value == '')) {
		return;
	}

	// Check necessity for async request
	var necessity = 0;
	if (document.forms['updateEmail']['email'].value != '' &&
			window.gLastEmail != document.forms['updateEmail']['email'].value) {
		necessity = 1;
	}
	if (necessity == 0)	{
		return ;
	}

	// form and send request
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "scripts/ajaxUpdateMail.php");
	xhr.responseType = 'json';
	let formData = new FormData();
	formData.append("email", document.forms['updateEmail']['email'].value);
	// console.log('');
	// console.log('tx: email='+document.forms['updateEmail']['email'].value);
	xhr.send(formData);

	// in valid request case
	xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById("errorMessage").innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
			var emailMarker = document.querySelector(".emailMarker");
			var requestData= xhr.response;
			var message = document.getElementById("errorMessage");

			if (requestData.request != '')
				message.innerHTML = requestData.request;
			else if (requestData.email != '' && document.forms['updateEmail']['email'].value != '')
				message.innerHTML = requestData.email;
			else
				message.innerHTML = '';

			if (requestData.email != '' && document.forms['updateEmail']['email'].value != '')
				emailMarker.style.opacity = 1;
			else
				emailMarker.style.opacity = 0;

			// console.log( 'rx: request='+requestData.request+' email='+requestData.email);
		}
		window.gLastEmail = document.forms['updateEmail']['email'].value;
	}

	// in invalid request case
	xhr.onerror = function() {
		document.getElementById("errorMessage").innerHTML = "Запрос не удался";
	};
}

var gLastEmail = '';

setInterval( asyncRequest, 1000);
