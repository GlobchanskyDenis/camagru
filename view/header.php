<?php
// if (!isset($_SESSION)) {
// 	session_start();
// }

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user']) {
?>

		<header>
			<div id="camagru">
				<div>
					Camagru
				</div>
			</div>
			<div id="navigation">
				<ul id="bar">
					<li class="barItem"><div onclick="window.location.href='snapchat.php'"><img src="img/snapshot.png" class="icon"></div></li>
					<li class="barItem"><div onclick="window.location.href='gallery.php'"><img src="img/gallery.png" class="icon"></div></li>
					<li class="barItem" onclick="notifClick()"><div><img src="img/notifications_off.png" class="icon" id='notificationsIcon'></div>
						<!-- Тут меню уведомлений. Оно описано чуть ниже -->
					</li>
					<li class="barItem" onclick="menuClick()"><div><img src="img/sendvich.png" class="icon" onmouseover="menuMouseOver()"></div>
						<ul class="bar__drop">
							<div id="userName"><?php echo $_SESSION['loggued_on_user'] ?></div>
							<li class="bar__dropItem" onclick="window.location.href='settings.php'"><div><img src="img/settings.png" class="icon">Settings</div></li>
							<li class="bar__dropItem" onclick="window.location.href='profile.php'"><div><img src="img/profile.png" class="icon">Profile</div></li>
							<li class="bar__dropItem" onclick="window.location.href='scripts/logout.php'"><div><img src="img/logout.png" class="icon">Logout</div></li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="notif__drop">
				<div class="notifHeader">
					<div id="notifHeader">
						No notifications found
					</div>
				</div>
				<!-- <div class='notif' id='notif1'>
					<div class="notifBody" id="notifBody1">
						123456789012345 has liked your snap
					</div>
					<div class="vertAlignator">
						<img src="img/delete.png" class="icon" id="notifDel1">
					</div>
				</div>
				<div class='notif' id='notif2'>
					<div class="notifBody" id="notifBody2">
						notification 2
					</div>
					<div class="vertAlignator">
						<img src="img/delete.png" class="icon">
					</div>
				</div> -->
				<!-- <div class='notif' id='notif3'>
					<div class="notifBody" id="notifBody3">
						notification 3
					</div>
					<div class="vertAlignator">
						<img src="img/delete.png" class="icon">
					</div>
				</div> -->
			</div>
		</header>

<?php
	} else {
?>

		<header>
			<div id="camagru">
				<div>
					Camagru
				</div>
			</div>
			<div id="navigation">
				<ul id="bar">
					<div style="display: block; width: 50px;"></div><!-- Это костыль для корректного отображения меню -->
					<li class="barItem"><div onclick="window.location.href='index.php'"><img src="img/home.png" class="icon"></div></li>
					<li class="barItem"><div onclick="window.location.href='gallery.php'"><img src="img/gallery.png" class="icon"></div></li>
					<li class="barItem" onclick="menuClick()"><div><img src="img/sendvich.png" class="icon" onmouseover="menuMouseOver()"></div>
						<ul class="bar__drop">
							<li class="bar__dropItem" onclick="window.location.href='signIn.php'"><div><img src="img/signIn.png" class="icon">Sign In</div></li>
							<li class="bar__dropItem" onclick="window.location.href='signUp.php'"><div><img src="img/signUp.png" class="icon">Sign Up</div></li>
						</ul>
					</li>
				</ul>
			</div>
		</header>

<?php
	}
?>