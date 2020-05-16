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

setInterval( ajaxCheckReg, 2000);
