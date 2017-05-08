<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Write story to db if set
if (isset($_POST['story'])) {
	$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

	$stmt = $sqlConnetion->prepare("INSERT INTO storySubmission (story, ip) VALUES (?, ?)");
	$stmt->bind_param('ss', $_POST['story'], $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	$stmt->close();

	$sqlConnetion->close();

	echo "Submission saved";
}

?>