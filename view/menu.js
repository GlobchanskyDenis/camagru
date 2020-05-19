function menuClose(menu) {
	// console.log("menu Close");
	menu.style.top = '-1000px';
}

function menuOpen(menu) {
	// console.log("menu Open");
	menu.style.top = '40px';
}

function notifOpen(notif) {
	// console.log("notif Open");
	notif.style.top = '40px';
}

function notifClose(notif) {
	// console.log("notif Close");
	notif.style.top = '-1000px';
}

function menuClick() {
	var menu = document.querySelector(".bar__drop");
	var notif = document.querySelector(".notif__drop");
	// console.log("menuClick");

	if (menu.style.top && menu.style.top == '40px') {
		menuClose(menu);
	} else {
		if (notif) {
			notifClose(notif);
		}
		menuOpen(menu);
	}
	// console.log("");
}

function notifClick() {
	var menu = document.querySelector(".bar__drop");
	var notif = document.querySelector(".notif__drop");
	// console.log("notifClick");

	if (notif.style.top && notif.style.top == '40px') {
		notifClose(notif);
	} else {
		menuClose(menu);
		notifOpen(notif);
	}
	// console.log("");
}