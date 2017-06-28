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
	$userToReset = $_GET['user'];
	$activate = $_GET['activate'];
	if (checkUsernameExists($sqlConnection, $userToReset)) {
		// Generate random string

		echo "Passwort generieren";
		$newPassword = randomString(16);
		User::changePassword($userToReset, $newPassword);
		echo "passwort geändert";

		// Send mail to inform user about new login token
		echo "Email fetchen";
		$to = emailForUser($sqlConnection, $userToReset); #
		echo "Email gefetcht";
		if ($to != "") {
			$subject = 	'[NL-Bot] Passwort reset';
			$message = 	'Hallo, eben wurde dein Nightline-Passwort zurückgesetzt. Du kannst dich nun mit folgendem Passwort unter nightline-karlsruhe.de/dienstplan/ anmelden: '.$newPassword.'Wichtg: Unmittelbar unter Einstelungen das neues anderes Passwort festlegen!  -- Nightline Bot';
    		$headers =  'From: no-reply@nightline-karlsruhe.de'."\r\n".
                		'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
                		'X-Mailer: PHP/'.phpversion();
                
			mail($to, $subject, $message, $headers);
			echo "Passwort wurde zurückgesetzt und der Benutzer per Email informiert";
		} else {
			echo "No email known";
		}

 		// Redirect if requested
		// header('Location: home.php');
	} else {
		echo "Cannot reset password: User ".$userToReset." does not exist";
	}
} else {
	echo "Please log in first";
}

function randomString($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $string = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $string .= $keyspace[random_int(0, $max)];
    }
    return $string;
}

?>