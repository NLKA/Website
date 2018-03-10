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
        $stmt = $sqlConnetion->prepare("INSERT INTO serviceDay (date, service) VALUES (?, 0)");
        $stmt->bind_param('s', $_GET['date']);
        $stmt->execute();
        $stmt->close();

        // log action
        logAction($user, "[Service] Add service date".$_GET['date']);
    }
}


if ($user->isPrivileged) {
    if ($_GET['op'] == "delete") {
        $stmt = $sqlConnetion->prepare("DELETE FROM serviceDay WHERE serviceDayId = ?;");
        $stmt->bind_param('s', $_GET['id']);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        // log action
        logAction($user, "[Service] Delete service #".$_GET['id']);
    }

    if ($_GET['op'] == "confirm") {
        $stmt = $sqlConnetion->prepare("UPDATE serviceDay SET service = 1 WHERE serviceDayId = ?");
        $stmt->bind_param('s', $_GET['id']);
        $stmt->execute();
        $stmt->close();

        // Fetch date
        $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE serviceDayId = ?");
        $stmt->bind_param('s', $_GET['id']);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        $dateOutputString = "n/a";
        if ($results->num_rows > 0) {
            // Display that a service is available
            $firstRow = $results->fetch_assoc();
            if ($firstRow['date'] == date('Y-m-d')) {
                $dateOutputString = "heute";
            } else {
                $date = new DateTime($firstRow['date']);
                $dateOutputString = $date->format('d.m.');
            }
        }

        // Send mail to list
        $to      =  'ka-aktive@nl2.kip.uni-heidelberg.de';
        $subject =  '[NL-Bot] Dienst '.$dateOutputString;
        $message =  'Der Telefondienst für '.$dateOutputString.' wurde eben nach Anfragen auf der Website bestätigt und findet statt. Bitte überprüfe, ob du dich eingetragen hast und spreche dich ggf. mit deinem Partner ab. Bis bald und frohes Telefonieren! ☎️  -- Nightline Bot';
        $headers =  'From: no-reply@nightline-karlsruhe.de'."\r\n".
                    'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
                    'X-Mailer: PHP/'.phpversion();
                
        mail($to, $subject, $message, $headers);

        // log action
        logAction($user, "[Service] Confirm service #".$_GET['id']);
    }

    if ($_GET['op'] == "unconfirm") {
        $stmt = $sqlConnetion->prepare("UPDATE serviceDay SET service = 0 WHERE serviceDayId = ?");
        $stmt->bind_param('s', $_GET['id']);
        $stmt->execute();
        $stmt->close();
    }
}

$sqlConnetion->close();

// Redirect back
header("Location: home.php");

?>