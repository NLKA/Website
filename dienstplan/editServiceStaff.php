<?php

require_once('user.php');
require_once('footer.php');

session_start();

// Check if user is still logged in
$user = User::loginSession();

if (!$user->isPrivileged) {
	return;
}

// enable redirecting
$_SESSION['redirect'] = 'editServiceStaff.php?id='.$_GET['id'];

// Build admin page
$sqlConnetion = User::connect();
$stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$resultsUsers = $stmt->get_result();
$stmt->close();

?>
<!DOCTYPE html>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
 	<div id='content'>
 		<h1>Nightline Karlsruhe</h1>
 		<p>Hallo <?php echo $user->user;?>! <a class='greyButton' href='logoutAccount.php'>Abmelden</a> <a class='greyButton' href='home.php'>Zurück zum Dashboard</a></p>
 		<h2>Dienst bearbeiten</h2>
 		<h3>Eingetragene Nightliner</h3>
<?php

$selfHasEntry = false;
while ($rowUser = $resultsUsers->fetch_assoc()) {
    echo "<p>".$rowUser['user']." <a href='serviceStaffModify.php?op=delete&id=".$_GET[id]."&user=".$rowUser['user']."' class='redButton'>Entfernen</a></p>";
}

?>
		 <h3>Anderen Nightliner eintragen</h3>
<?php

$stmt = $sqlConnetion->prepare("SELECT * FROM user WHERE user NOT IN (SELECT user FROM serviceDayStaff WHERE serviceDayId = ?);");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$resultsUsers = $stmt->get_result();
$stmt->close();

$selfHasEntry = false;
while ($rowUser = $resultsUsers->fetch_assoc()) {
    echo "<p>".$rowUser['user']." <a class='greenButton' href='serviceStaffModify.php?op=add&id=".$_GET[id]."&user=".$rowUser['user']."'>Hinzufügen</a></p>";
}

?>

	</div>
	<?php 
		global $footer;
		echo $footer;
	?>
</body>