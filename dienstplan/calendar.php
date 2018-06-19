<?php

require_once('user.php');
require_once('calendarTable.php');
require_once('footer.php');

session_start();

// Check if user is still logged in	
$user = User::loginSession();

//enable redirecting
$_SESSION['redirect'] = 'calendar.php';

if (!$user) {
	header("Location: login.php");
} else {
	?>
	<!DOCTYPE html>

	<head>
		<title>Nightline Karlsruhe - Kalender</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
 		<div id='content'>
 			<h1>Nightline Karlsruhe</h1>
 			<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='home.php'>Zurück zum Dashboard</a>
			<?php
				buildCalendarTable($user);
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