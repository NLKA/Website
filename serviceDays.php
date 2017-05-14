<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check permission to view
$tokenHash = "0c7c36061cc3c9027fcfecde263e229ef718ae66835ed88348a50f12b966e70c";
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

// Fetch table from db
echo "Fetching from db";
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay;");
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$sqlConnetion->close();

// Print table
echo "Scheduled services: <br/>"

echo "<table>";
while ($row = $results->fetch_assoc()) {
	echo "<tr>";
    echo "<td>".$row['serviceDayId']."</td>";
    echo "<td>".$row['date']."</td>";
    echo "<td>".$row['service']."</td>";
    echo "<td><a href='serviceDayModify.php?token=".$_GET['token']."&op=delete&id=".$row['serviceDayId']."'>Delete</a></td>";
    echo "</tr>";
}
echo "</table>";

echo "<br/>Noting else to show";

?>