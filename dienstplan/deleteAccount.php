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
	$userToDelete = $_GET['user'];
	if (checkUsernameExists($sqlConnection, $userToDelete)) {
		// Delete from db
		$sqlConnetion = User::connect();
    	$stmt = $sqlConnetion->prepare("DELETE FROM user WHERE user = ?");
    	$stmt->bind_param('s', $userToDelete);
    	$stmt->execute();
    	$stmt->close();

 		// Redirect if requested
		header('Location: home.php');
	} else {
		echo "Cannot delete account: User ".$userToDelete." does not exist";
	}
} else {
	echo "Please log in first";
}

?>