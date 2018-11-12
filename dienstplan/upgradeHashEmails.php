<?php

require_once('config.php');
require_once('hashing.php');

echo("Connecting");

// Get SQL connection
global $dbName, $dbServer, $dbUser, $dbPassword;
$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
if ($sqlConnetion->connect_errno) {
    die('Failed to connect to MySQL: '.$mysqli->connect_error);
}

echo("Getting Accounts");

// Get all accounts
$stmt = $sqlConnetion->prepare("SELECT * FROM user");
$stmt->execute();
$resultAllUsers = $stmt->get_result();
$stmt->close();

echo("Upgrading...");

// For each account: Update email to hash
while ($row = $resultAllUsers->fetch_assoc()) {
    // Get old email and hash it
    $plainTextEmail = $row['email'];
    $alreadyHashed = substr($plainTextEmail, 0, 1) == "$";
    if ($alreadyHashed) {
        echo("Already hashed.");
    } else {
        $emailHashed = $sqlConnetion->real_escape_string(usersHash($plainTextEmail));
        $emailHashPrefix30 = substr($emailHashed, 0, 30);

        // Update email
        $stmt = $sqlConnetion->prepare("UPDATE user SET email = ? WHERE user = ?");
        $stmt->bind_param('ss', $emailHashPrefix30, $row['user']);
        $stmt->execute();
        $stmt->close();
    }
}

echo("Finished.");

?>
