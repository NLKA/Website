<?php

require_once('user.php');
require_once('userFetch.php');

session_start();

// Check if user is still logged in
$user = User::loginSession($_SESSION['user'], $_SESSION['sessionID']);

if ($user) {
	$sqlConnection = User::connect();

	if ($_GET['type'] == "email") {
		User::changeEmail($user->user, htmlspecialchars($_POST['value']));
		header('Location: accountSettings.php');
	}

	if ($_GET['type'] == "password") {
		User::changePassword($user->user, htmlspecialchars($_POST['value']));
		header('Location: logoutAccount.php');
	}
} else {
	echo "Please log in first";
}

?>