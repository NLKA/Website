<?php

require_once('config.php');
require_once('user.php');

global $allowCreateOriginAccount;

// Set up queries
$queryUserTable = "CREATE TABLE IF NOT EXISTS `user` (
            	`user` varchar(20) NOT NULL,
            	`password` varchar(300) NOT NULL,
            	`email` varchar(30) NOT NULL,
            	`activated` boolean NOT NULL,
            	`isPrivileged` boolean NOT NULL,
            	PRIMARY KEY (`user`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$queryServiceDayStaff = "CREATE TABLE IF NOT EXISTS `serviceDayStaff` (
				`serviceDayId` INT NOT NULL,
				`user` varchar(20) NOT NULL,
                `selected` boolean NOT NULL,
                PRIMARY KEY (`serviceDayId`, `user`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queryOperationsLog = "CREATE TABLE IF NOT EXISTS `operationsLog` (
                `logEntryId` INT NOT NULL AUTO_INCREMENT,
                `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `user` varchar(20) NOT NULL,
                `action` TEXT NOT NULL,
                PRIMARY KEY (`logEntryId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queryOnDemandToken = "CREATE TABLE IF NOT EXISTS `onDemandToken` (
                `tokenId` INT NOT NULL AUTO_INCREMENT,
                `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `token` TEXT NOT NULL,
                PRIMARY KEY (`tokenId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queryServiceStaffNote = "CREATE TABLE IF NOT EXISTS `serviceStaffNote` (
                `noteId` INT NOT NULL AUTO_INCREMENT,
                `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `user` varchar(20) NOT NULL,
                `serviceDayId` INT NOT NULL,
                `note` TEXT NOT NULL,
                PRIMARY KEY (`noteId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queryCalendarEntry = "CREATE TABLE IF NOT EXISTS `calendarEntry` (
                `entryId` INT NOT NULL AUTO_INCREMENT,
                `timeAdded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `user` varchar(20) NOT NULL,
                `date` TEXT NOT NULL,
                `time` TEXT NOT NULL,
                `entry` TEXT NOT NULL,
                PRIMARY KEY (`entryId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

// Connect to db server
$connection = new mysqli($dbServer, $dbUser, $dbPassword);
if ($connection->connect_errno) {
	die('Failed to connect to MySQL: '.$mysqli->connect_error);
}

// Create db and table
$connection->query($queryDB);
echo "Created db".$dbName."<br/>";

$connection->select_db($dbName);
$connection->query($queryUserTable);
$connection->query($queryServiceDayStaff);
$connection->query($queryOperationsLog);
$connection->query($queryOnDemandToken);
$connection->query($queryServiceStaffNote);
$connection->query($queryCalendarEntry);
echo "Created tables<br/>";

// Check if there are accounts and create origin otherwise
$results = $connection->query("SELECT * FROM user;");
if ($results->num_rows == 0 && $allowCreateOriginAccount) {
    User::create("origin", "origin", "nomail@nightlines.eu");
    echo "Created origin account";

    // activate account and make admin
    $connection->query("UPDATE user SET activated = 1, isPrivileged = 1 WHERE user = 'origin';");
}

$connection->close();

?>