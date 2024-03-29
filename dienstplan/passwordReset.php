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

	echo "Remote password resets are currently disabled. Please contact administrator with any questions.";

	// Has to be re-written in order to handle hashed email prefixes 30!

	/*
	// Check if the requested user name exists
	$sqlConnection = User::connect();
	$userToReset = $_GET['user'];
	$activate = $_GET['activate'];
	if (checkUsernameExists($sqlConnection, $userToReset)) {
		// Generate random string

		$newPassword = join('-', str_split(bin2hex(openssl_random_pseudo_bytes(8)), 4));
		User::changePassword($userToReset, $newPassword);

		// Send mail to inform user about new login token
		$to = emailForUser($sqlConnection, $userToReset);
		if ($to != "") {
			$subject = 	'[NL-Bot] Passwort reset';
			$message = 	'Hallo, eben wurde dein Nightline-Passwort zurückgesetzt. Du kannst dich nun mit folgendem Passwort unter nightline-karlsruhe.de/dienstplan/ anmelden: '.$newPassword.' Wichtig: Unmittelbar unter Einstellungen ein neues Passwort festlegen!  -- Nightline Bot';
    		$headers =  'From: no-reply@nightline-karlsruhe.de'."\r\n".
                		'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
                		'X-Mailer: PHP/'.phpversion();
                
			mail(utf8_decode($to), utf8_decode($subject), utf8_decode($message), $headers);
			echo "Passwort wurde zurückgesetzt und der Benutzer per Email informiert";
		} else {
			echo "No email known";
		}

 		// Redirect if requested
		// header('Location: home.php');
	} else {
		echo "Cannot reset password: User ".$userToReset." does not exist";
	}
	*/
} else {
	echo "Please log in first";
}

?>