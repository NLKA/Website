<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check permission to view
$tokenHash = "0c7c36061cc3c9027fcfecde263e229ef718ae66835ed88348a50f12b966e70c";
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

// Execute op on db
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
if ($_GET['op'] == "delete") {
	echo "Deleting...";
	$stmt = $sqlConnetion->prepare("DELETE FROM serviceDay WHERE serviceDayId = ?;");
	$stmt->bind_param('s', $_GET['id']);
	$stmt->execute();
	$results = $stmt->get_result();
	$stmt->close();
}

if ($_GET['op'] == "add") {
	echo "Adding...";
	$stmt = $sqlConnetion->prepare("INSERT INTO serviceDay (date) VALUES (?)");
    $stmt->bind_param('s', STR_TO_DATE($_GET['date'], '%d-%m-%Y'));
    $stmt->execute();
    $stmt->close();
}

echo "Done";

$sqlConnetion->close();

// Redireact back
//header("Location: serviceDays.php?token=".$_GET['token']);

?>