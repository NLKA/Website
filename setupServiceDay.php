<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Create story table
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$queryTable = "CREATE TABLE IF NOT EXISTS `serviceDay` (
					`serviceDayId` INT NOT NULL AUTO_INCREMENT,
					`date` date NOT NULL,
					PRIMARY KEY (serviceDayId)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sqlConnetion->query($queryTable);
$sqlConnetion->close();

echo "Created service day table";

?>