<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Write demand to db
if ($_GET['origin'] == "onDemandButton") {
	$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

	$stmt = $sqlConnetion->prepare("INSERT INTO onDemandEntry (ip) VALUES (?)");
	$stmt->bind_param('s', $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	$stmt->close();
	$sqlConnetion->close();

	header("Location: on-demand.html?success=1");
} else {
	header("Location: on-demand.html");
}

?>