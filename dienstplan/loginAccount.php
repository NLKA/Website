<?php

require_once('user.php');

// Begin session
session_start();

// Fetch data
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

// Attempt to login
if (!$user = User::loginPassword($username, $password)) {
	header("Location: login.php");
} else {
	// Create session and set user, sessionID
	$_SESSION['user'] = $username;

	// Redirect if requested
	if ($_SESSION['redirect']) {
		$redirectString = "Location: ".$_SESSION['redirect']; 
		header($redirectString);
	} else {
		// otherwise redirect to home
		$redirectString = "Location: home.php"; 
		header($redirectString);
	}
}

?>