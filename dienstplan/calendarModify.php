<?php

require_once('user.php');
require_once('config.php');
require_once('logger.php');

global $dbServer, $dbUser, $dbPassword, $dbName;

// Check permission
session_start();
$user = User::loginSession();
if (!$user) {
    echo "Please log in first";
    exit;
}

// Execute op on db
$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

if ($_GET['op'] == "add") {
    if (strtotime($_GET['date'])) {
        $stmt = $sqlConnetion->prepare("INSERT INTO calendarEntry (user, date, time, entry) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $user->user, $_GET['date'], $_GET['time'], $_GET['entry']);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_GET['op'] == "delete") {
    $stmt = $sqlConnetion->prepare("DELETE FROM calendarEntry WHERE entryId = ?;");
    $stmt->bind_param('s', $_GET['id']);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();
}

$sqlConnetion->close();

// Redirect back
header("Location: calendar.php");

?>