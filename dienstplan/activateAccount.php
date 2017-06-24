<?php

require_once('user.php');
require_once('userFetch.php');

session_start();

// Check if user is still logged in
$user = User::loginSession($_SESSION['user'], $_SESSION['sessionID']);

if ($user) {
	if (!$user->isPrivileged) {
		echo "This action can only be performed by an admin";
		return;
	}

	// Check if the requested user name exists
	$sqlConnection = User::connect();
	$userToActivate = $_GET['user'];
	$activate = $_GET['activate'];
	if (checkUsernameExists($sqlConnection, $userToActivate)) {
		$sqlConnetion = User::connect();
    	$stmt = $sqlConnetion->prepare("UPDATE user SET activated = ? WHERE user = ?");
    	$stmt->bind_param('is', $activate, $userToActivate);
    	$stmt->execute();
    	$stmt->close();

 		// Redirect if requested
		header('Location: home.php');
	} else {
		echo "Cannot activate account: User ".$userToActivate." does not exist";
	}
} else {
	echo "Please log in first";
}

?>