<?php

include_once('/etc/apache2/db-passwords/nightline.php');
include_once('tokenHash.php');

// Check permission to view
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

echo $DB_USER;
echo "<br/>";
echo $DB_PASSWORD;

?>