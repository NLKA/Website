<?php

require_once('user.php');
require_once('userFetch.php');

session_start();

// Check if user is still logged in
$user = User::loginSession($_SESSION['user'], $_SESSION['sessionID']);

if ($user) {
	$sqlConnection = User::connect();

	if ($_GET['type'] == "email") {
		$success = User::changeEmail($user->user, htmlspecialchars($_POST['value']));
		if ($success) {
			header('Location: accountSettings.php');
		} else {
			echo("Could not change email address, not valid. Try again");
		}
	}

	if ($_GET['type'] == "password") {
		$success = User::changePassword($user->user, htmlspecialchars($_POST['value']));
		if ($success) {
			header('Location: logoutAccount.php');
		} else {
			echo("Could not change password (minimum 6 characters needed). Try again");
		}
	}
} else {
	echo "Please log in first";
}

?>