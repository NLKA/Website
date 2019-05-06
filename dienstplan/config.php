<?php

include_once('/etc/apache2/db-passwords/nightline.php');

$allowCreateOriginAccount = false;

// MySQL db data
$dbName = "serviceDaysSystem";		// Name of SQL database which will be created
$dbServer = "localhost:8889";
$dbUser = "root";				
$dbPassword = "root";
$dbSalt = '$6$3BS49wgmW3yFEiuqQ/odfYItivM=';    // Change everything after $6$

// Session
$sessionTimeout = 3600;

?>