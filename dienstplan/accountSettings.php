<?php

require_once('user.php');
require_once('usersTable.php');
require_once('serviceTable.php');
require_once('footer.php');

session_start();

// Check if user is still logged in
$user = User::loginSession();

if (!$user) {
	// Login and request redirect
	$_SESSION['redirect'] = 'home.php';
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
 			<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='home.php'>Zurück zum Dashboard</a></p>
			<p>Hier kannst du deine Emailadresse und Passwort ändern.</p>
			<form action='changeOwnAccountSettings.php?type=email' method='post'>
  				<a class='fieldLabel'>Aktuelle Adresse: <?php echo $user->email?>. Neue Emailadresse:</a><br>
  				<input type="text" name="value"><br>
  				<input type= "submit" value="Ändern">
  			</form>
  			<form action='changeOwnAccountSettings.php?type=password' method='post'>
  				<a class='fieldLabel'>Neues Passwort:</a><br>
  				<input type="password" name="value"><br>
  				<input type= "submit" value="Ändern">
  			</form>
		</div>
		<?php 
			global $footer;
			echo $footer;
		?>
	</body>
	<?php
}

?>