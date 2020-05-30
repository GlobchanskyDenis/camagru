function menuClose(menu) {
	menu.style.top = '-1000px';
}

function menuOpen(menu) {
	menu.style.top = '40px';
}

function notifOpen(notif) {
	notif.style.top = '40px';
}

function notifClose(notif) {
	notif.style.top = '-1000px';
}

function menuClick() {
	var menu = document.querySelector(".bar__drop");
	var notif = document.querySelector(".notif__drop");

	if (menu.style.top && menu.style.top == '40px') {
		menuClose(menu);
	} else {
		if (notif) {
			notifClose(notif);
		}
		menuOpen(menu);
	}
}

function menuMouseOver() {
	var menu = document.querySelector(".bar__drop");
	var notif = document.querySelector(".notif__drop");
	notifClose(notif);
	menuOpen(menu);
}

function notifClick() {
	var menu = document.querySelector(".bar__drop");
	var notif = document.querySelector(".notif__drop");

	if (notif.style.top && notif.style.top == '40px') {
		notifClose(notif);
	} else {
		menuClose(menu);
		notifOpen(notif);
	}
}