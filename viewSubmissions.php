<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check permission to view
if (crypt($_GET['token']) != "$1$ZxWC99bA$YJAq5Xh8pnO/.qDjy1RaT0") {
	echo "Permission token invalid";
	exit;
}

// Fetch table from db
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$stmt = $sqlConnetion->prepare("SELECT * FROM storySubmission;");
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$sqlConnetion->close();

// Print table
echo "<table>";
while ($row = $results->fetch_assoc()) {
	echo "<tr>";
    echo "<td>".$row['submissionId']."</td>";
    echo "<td>".$row['time']."</td>";
    echo "<td>".$row['ip']."</td>";
    echo "<td>".$row['story']."</td>";
    echo "</tr>";
}
echo "</table>";

?>