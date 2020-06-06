function setNotification(itemNbr, who, message) {
	var notifMenu = document.querySelector(".notif__drop");
	if (document.getElementById('notif'+itemNbr)) {
		var notif = document.getElementById('notif'+itemNbr);
		var body = document.getElementById('notifBody'+itemNbr);
		var del = document.getElementById('notifDel'+itemNbr);
	} else {
		var notif = document.createElement('div');
		var body = document.createElement('div');
		var delDiv = document.createElement('div');
		var del = document.createElement('img');

		notif.setAttribute('class', 'notif');
		notif.setAttribute('id', 'notif'+itemNbr);

		body.setAttribute('class', 'notifBody');
		body.setAttribute('id', 'notifBody'+itemNbr);

		delDiv.setAttribute('class', 'vertAlignator');

		del.setAttribute('class', 'icon');
		del.setAttribute('id', 'notifDel'+itemNbr);
		del.src = 'img/delete.png';

		delDiv.appendChild(del);
		notif.appendChild(body);
		notif.appendChild(delDiv);
		notifMenu.appendChild(notif);
	}
	notif.style.display = 'grid';
	body.innerHTML = who + ' ' + message;
	document.getElementById( 'notifDel' + itemNbr ).addEventListener("click", deleteNotification.bind(null, itemNbr));
}

function shiftNotifUp(itemNbr) {
	document.getElementById('notif' + itemNbr).style.display = document.getElementById('notif' + (itemNbr + 1)).style.display;
	document.getElementById('notifBody' + itemNbr).innerHTML = document.getElementById('notifBody' + (itemNbr + 1)).innerHTML;
}

function deleteNotification(itemNbr) {

	// Checking permission for function work
	if (window.gWorkPermission && window.gWorkPermission != '') {
		document.getElementById('errorMessage').innerHTML = 'wait 1 second';
		return ;
	}

	// Disable work permission for all function that listen extern events
	window.gWorkPermission = 'denied';

	// Make async request to DB -  
    // we need delete notification by its ID
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/asyncDeleteNotification.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    formData.append("id", window.gNotifID[itemNbr]);
    xhr.send(formData);
    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAsync = xhr.response;
            if (!requestAsync) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
			}
			document.getElementById('errorMessage').innerHTML = requestAsync.error;
            if (requestAsync.error == '') {

				while ( document.getElementById( 'notif' + (itemNbr + 1) ) && 
						document.getElementById( 'notif' + (itemNbr + 1) ).style.display != 'none' ) {
					shiftNotifUp(itemNbr);
                    window.gNotifID[itemNbr] = window.gNotifID[itemNbr + 1];
                    itemNbr++;
				}
				window.gNotifAmount = window.gNotifAmount - 1;
				document.getElementById( 'notif' + (itemNbr) ).style.display = 'none';
				getNotifications();
			}
		}
		// Enable work permission for all function that listen extern events
		window.gWorkPermission = '';
	}
	xhr.onerror = function() {
		document.getElementById('errorMessage').innerHTML = "Запрос удаления уведомления не удался";
		// Enable work permission for all function that listen extern events
		window.gWorkPermission = '';
	};
}

function getNotifications() {

	let xhr = new XMLHttpRequest();
	xhr.open("POST", "scripts/getNotifications.php");
	xhr.send();

	xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
			var requestAsync = JSON.parse(xhr.response);
			var notif;
			if (!requestAsync) {
				document.getElementById('errorMessage').innerHTML = 'empty async request';
				return ;
			}
			if (requestAsync['notif1']) {
				if (document.getElementById('notifHeader'))
					document.getElementById('notifHeader').innerHTML = 'Notifications:';
				if (document.getElementById('notificationsIcon'))
					document.getElementById('notificationsIcon').src = 'img/notifications_on.png';
			} else {
				if (document.getElementById('notifHeader'))
					document.getElementById('notifHeader').innerHTML = 'Empty';
				if (document.getElementById('notificationsIcon'))
					document.getElementById('notificationsIcon').src = 'img/notifications_off.png';
			}
			var i = 1;
			while (requestAsync[ 'notif' + i ]) {
				notif = requestAsync[ 'notif' + i ];
				setNotification(i,
								notif.userNotifier,
								notif.message);
				window.gNotifID[i] = notif.id;
				window.gNotifAmount++;
				i++;
			}
		}
	}

	xhr.onerror = function() {
		document.getElementById('errorMessage').innerHTML = "Запрос не удался";
	};
}

var gNotifAmount = 0;
var gNotifID = [];

setInterval( getNotifications, 5000);
