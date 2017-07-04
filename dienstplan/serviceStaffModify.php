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
    $stmt = $sqlConnetion->prepare("INSERT INTO serviceDayStaff (serviceDayId, user) VALUES (?, ?)");
    $stmt->bind_param('is', $_GET['id'], $_GET['user']);
    $stmt->execute();
    $stmt->close();

    // log action
    logAction($user, "[Service] Add entry for ".$_GET['user']." at service #".$_GET['id']);
}

if ($user->isPrivileged || $user->user == $_GET['user']) {
    if ($_GET['op'] == "delete") {
        $stmt = $sqlConnetion->prepare("DELETE FROM serviceDayStaff WHERE serviceDayId = ? AND user = ?;");
        $stmt->bind_param('is', $_GET['id'], $_GET['user']);
        $stmt->execute();
        $stmt->close();

        // log action
        logAction($user, "[Service] Remove entry for ".$_GET['user']." at service #".$_GET['id']);
    }
}

$sqlConnetion->close();

// Redirect if requested
if ($_SESSION['redirect']) {
    $redirectString = "Location: ".$_SESSION['redirect'];
    unset($_SESSION['redirect']);
    header($redirectString);
} else {
    // otherwise redirect to home
    $redirectString = "Location: home.php"; 
    header($redirectString);
}

?>