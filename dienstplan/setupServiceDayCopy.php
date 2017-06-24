<?php

require_once('config.php');

// Create story table
$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword);
$sqlConnetion->select_db($dbName);

$queryTable = "CREATE TABLE IF NOT EXISTS `serviceDay` (
					`serviceDayId` INT NOT NULL AUTO_INCREMENT,
					`date` date NOT NULL,
					`service` boolean NOT NULL,
					PRIMARY KEY (serviceDayId)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sqlConnetion->query($queryTable);
$sqlConnetion->close();

echo "Created service day table";

?>