<?php

include_once('/etc/apache2/db-passwords/nightline.php');
include_once('tokenHash.php');

// Check permission to perform action
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