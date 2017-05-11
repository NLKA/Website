<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Create story table
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

// Note: We need 45 chars to save IPv4-mapped v6 addresses!
$queryTable = "CREATE TABLE IF NOT EXISTS `onDemandEntry` (
					`demandId` INT NOT NULL AUTO_INCREMENT,
					`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`ip` varchar(45),
					PRIMARY KEY (demandId)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sqlConnetion->query($queryTable);
$sqlConnetion->close();

echo "Created on demand table";

?>