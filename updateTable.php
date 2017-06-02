<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check permission to perform action
$tokenHash = "0c7c36061cc3c9027fcfecde263e229ef718ae66835ed88348a50f12b966e70c";
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

// Create story table
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$queryTable = "UPDATE storySubmission SET ip=SHA2(ip, 256) WHERE submissionId < 30";

$sqlConnetion->query($queryTable);
$sqlConnetion->close();

echo "Successfully updated table";

?>