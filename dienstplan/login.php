<!DOCTYPE html>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
 	<div id='content'>
		<h1>Nightline Karlsruhe - Login</h1>
		<form action='loginAccount.php' method='post'>
  			<a class='fieldLabel'>Benutzername:</a><br>
  			<input type="text" name="username"><br>
  			<a class='fieldLabel'>Passwort:</a><br>
  			<input type="password" name="password"><br>
  			<input type= "submit" value= "Einloggen">
		</form>
	</div>

	<?php
		require_once('footer.php');
		global $footer;
		echo $footer;
	?>
</body>