<?php

require_once('dienstplan/config.php');

// Create story table
$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

// Note: We need 45 chars to save IPv4-mapped v6 addresses!
$queryTable = "CREATE TABLE IF NOT EXISTS `onDemandEntry` (
					`demandId` INT NOT NULL AUTO_INCREMENT,
					`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`ip` varchar(45),
					`serviceDayId` INT NOT NULL,
					PRIMARY KEY (demandId)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sqlConnetion->query($queryTable);
$sqlConnetion->close();

echo "Created on demand table";

?>