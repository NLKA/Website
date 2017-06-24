<?php

require_once('user.php');
require_once('userFetch.php');

session_start();

// Check if user is still logged in
$user = User::loginSession($_SESSION['user'], $_SESSION['sessionID']);

if ($user) {
	if (!$user->isPrivileged) {
		echo "This action can only be performed by an admin";
		exit;
	}

	// Check if the requested user name exists
	$sqlConnection = User::connect();
	$userToChange = $_GET['user'];
	$privileged = $_GET['privileged'];
	if (checkUsernameExists($sqlConnection, $userToChange)) {
		$sqlConnetion = User::connect();
    	$stmt = $sqlConnetion->prepare("UPDATE user SET isPrivileged = ? WHERE user = ?");
    	$stmt->bind_param('is', $privileged, $userToChange);
    	$stmt->execute();
    	$stmt->close();

 		// Redirect if requested
		header('Location: home.php');
	} else {
		echo "Account does not exist";
	}
} else {
	echo "Please log in first";
}

?>