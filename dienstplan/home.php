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
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
 		<div id='content'>
 			<h1>Nightline Karlsruhe</h1>
 			<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='accountSettings.php'>Einstellungen</a></p>
			<?php
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