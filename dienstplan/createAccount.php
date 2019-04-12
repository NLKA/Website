<?php

require_once('user.php');

// Fetch data
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

?>
<!DOCTYPE html>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
 	<div id='content'>
 <?php

// Rate limiting
usleep(3000000);		// Sleep for 3 seconds

// Attempt to create account
try {
	User::create($username, $password, $email);

	echo "<h2>Account wurde erstellt</h2>";
	echo "<p>Der Account ".$username." wurde angelegt. Nun muss dieser nur noch von der Nightline bestätigt werden, schau später wieder vorbei. <a href='login.php'>Hier geht es zum Login</a></p>";
} catch (Exception $e) {
	echo "<h2>Erneut versuchen</h2>";
	echo "<p>Der Account konnte nicht erstellt werden: ";
	echo $e->getMessage();
	echo "</p>";
}

?>
	</div>

	<?php
		require_once('footer.php');
		global $footer;
		echo $footer;
	?>
</body>