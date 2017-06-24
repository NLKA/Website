<?php

include_once('/etc/apache2/db-passwords/nightline.php');

$allowCreateOriginAccount = true;

// MySQL db data
$dbName = $DB_NAME;		// Name of SQL database which will be created
$dbServer = $DB_HOST;
$dbUser = $DB_USER;				
$dbPassword = $DB_PASSWORD;
$dbSalt = '$6$3BS49wgmW3yFEiuqQ/odfYItivM=';    // Change everything after $6$

// Session
$sessionTimeout = 3600;

?>