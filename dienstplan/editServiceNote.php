<?php

require_once('user.php');
require_once('footer.php');

session_start();

// Check if user is still logged in
$user = User::loginSession();
if (!$user) {
	return;
}

// enable redirecting
$_SESSION['redirect'] = 'editServiceStaff.php?id='.$_GET['id'];

?>
<!DOCTYPE html>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
 	<div id='content'>
 		<h1>Nightline Karlsruhe</h1>
 		<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='home.php'>Zurück zum Dashboard</a></p>
 		
 		<h2>Notiz hinzufügen</h2>
 		<form action='serviceNoteModify.php' method='post'>
     		<a class='fieldLabel'>Notiz:</a><br>
      		<input type="text" name="note"><br>
      		<?php
      		    echo "<input type='hidden' name='op' value='add'>";
      			echo "<input type='hidden' name='serviceDayId' value='".$_GET['id']."'>";
      		?>
      		<input type= "submit" value="Notiz hinzufügen">
    	</form>
	</div>
	
	<?php 
		global $footer;
		echo $footer;
	?>
</body>