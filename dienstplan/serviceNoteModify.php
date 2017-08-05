<?php

require_once('user.php');
require_once('config.php');

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

if ($_POST['op'] == "add") {
    $stmt = $sqlConnetion->prepare("INSERT INTO serviceStaffNote (user, serviceDayId, note) VALUES (?, ?, ?);");
    $stmt->bind_param('sis', $user->user, $_POST['serviceDayId'], $_POST['note']);
    $stmt->execute();
    $stmt->close();
}

$sqlConnetion->close();

// Redirect back
header("Location: home.php");

?>