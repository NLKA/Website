<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check captcha
if (isset($_POST['g-recaptcha-response'])) {
 	$captcha = $_POST['g-recaptcha-response'];
 	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$CAPTCHA_SECRET."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

 	if ($response['success'] == false) {
    	echo 'Error: Captcha failed';
    	exit;
    }
} else {
	echo "Error: No Captcha data";
	exit;
}

// Check publishing permission flag
if ($_POST['publishPermission'] != true) {
	echo "Error: Permission checkbox not set";
	exit;
}
       
// Write story to db if set
if (isset($_POST['story'])) {
	$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

	$stmt = $sqlConnetion->prepare("INSERT INTO storySubmission (story, ip) VALUES (?, ?)");
	$stmt->bind_param('ss', $_POST['story'], $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	$stmt->close();

	$sqlConnetion->close();

	header("Location: submit.html?submissionSuccess=1");
} else {
	echo "Error: Nothing to be submitted";
}

?>