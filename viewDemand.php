<?php

include_once('/etc/apache2/db-passwords/nightline.php');
include_once('tokenHash.php');

// Check permission to view
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

// Fetch table from db
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$stmt = $sqlConnetion->prepare("SELECT * FROM onDemandEntry;");
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$sqlConnetion->close();

// Print table
echo "<table>";
while ($row = $results->fetch_assoc()) {
	echo "<tr>";
    echo "<td>".$row['demandId']."</td>";
    echo "<td>".$row['time']."</td>";
    echo "<td>".$row['ip']."</td>";
    echo "<td>".$row['serviceDayId']."</td>";
    echo "</tr>";
}
echo "</table>";

?>