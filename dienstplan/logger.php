<?php

require_once('user.php');

function logAction($pUser, $pActionDescription) {
	// Fetch user name
	$userName = $pUser->user;

	// Write to db
	$sqlConnetion = User::connect();
	$stmt = $sqlConnetion->prepare("INSERT INTO operationsLog (user, action) VALUES (?, ?)");
    $stmt->bind_param('ss', $userName, $pActionDescription);
    $stmt->execute();
    $stmt->close();
}

?>