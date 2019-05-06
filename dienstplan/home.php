<?php

require_once('user.php');
require_once('leaderBoard.php');
require_once('usersTable.php');
require_once('serviceTable.php');
require_once('footer.php');

session_start();

// Check if user is still logged in	
$user = User::loginSession();

//enable redirecting
$_SESSION['redirect'] = 'home.php';

if (!$user) {
	header("Location: login.php");
} else {
	?>
	<!DOCTYPE html>

	<head>
		<title>Nightline Karlsruhe - Dienstplan</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
 		<div id='content'>
 			<h1>Nightline Karlsruhe</h1>
 			<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='accountSettings.php'>Einstellungen</a> &nbsp;  <a class='greyButton' href='calendar.php'>Kalender</a> &nbsp; <a class='greyButton' target='_blank' href='https://intranet.nightlines.eu/foswiki/'>Wiki ↗︎</a>
			<?php
				echo "<h2 style='color:#fe4e31;'>Wichtig: Bitte ab jetzt im offiziellen Dienstplan im Wiki eintragen, da dieses System umgestellt wird! <a href='https://intranet.nightlines.eu/foswiki/bin/view/Karlsruhe/Dienstplan'>Link zum offiziellen Dienstplan</a></h2>";

				buildLeaderBoard($user);
				buildServiceTable($user);
				buildUsersTable($user);
			?>
		</div>

		<?php 
			global $footer;
			echo $footer;
		?>
	</body>
	<?php
}

?>