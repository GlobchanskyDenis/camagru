<?php

session_start();

if ($_SESSION['loggued_on_user']) {
?>

		<header>
			<div id="camagru">
				<div>
					Camagru
				</div>
			</div>
			<div id="navigation">
				<ul id="bar">
					<li class="barItem"><div>Take shot</div></li>
					<li class="barItem"><div onclick="window.location.href='gallery.php'">Gallery</div></li>
					<li class="barItem"><div><?php echo $_SESSION['loggued_on_user'] ?></div>
						<ul class="bar__drop">
							<li class="bar__dropItem"><div onclick="window.location.href='settings.php'">Settings</div></li>
							<li class="bar__dropItem"><div onclick="window.location.href='profile.php'">Profile</div></li>
							<li class="bar__dropItem"><div onclick="window.location.href='scripts/logout.php'">Logout</div></li>
						</ul>
					</li>
				</ul>
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
					<li class="barItem"><div onclick="window.location.href='index.php'">Home page</div></li>
					<li class="barItem"><div onclick="window.location.href='gallery.php'">Gallery</div></li>
					<li class="barItem"><div>User</div>
						<ul class="bar__drop">
							<li class="bar__dropItem"><div onclick="window.location.href='signIn.php'">Sign In</div></li>
							<li class="bar__dropItem"><div onclick="window.location.href='signUp.php'">Sign Up</div></li>
						</ul>
					</li>
				</ul>
			</div>
		</header>

<?php
	}
?>