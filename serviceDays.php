<?php

include_once('/etc/apache2/db-passwords/nightline.php');

// Check permission to view
$tokenHash = "0c7c36061cc3c9027fcfecde263e229ef718ae66835ed88348a50f12b966e70c";
if (hash("sha256", $_GET['token']) != $tokenHash) {
	echo "Permission token invalid";
	exit;
}

// Fetch table from db
$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay;");
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$sqlConnetion->close();

// Print table
echo "<p>Scheduled services:</p><br/>";

echo "<table>";
while ($row = $results->fetch_assoc()) {
	echo "<tr>";
    echo "<td>".$row['serviceDayId']."</td>";
    echo "<td>".$row['date']."</td>";
    echo "<td>".$row['service']."</td>";
    echo "<td><a href='serviceDayModify.php?token=".$_GET['token']."&op=delete&id=".$row['serviceDayId']."'>Delete</a></td>";
    echo "<td><a href='serviceDayModify.php?token=".$_GET['token']."&op=confirm&id=".$row['serviceDayId']."'>Confirm</a></td>";
    echo "</tr>";
}
echo "</table>";
echo "<br/><p>Noting else to show</p><br/>";

// Print input field
echo "<form action='serviceDayModify.php' method='get'>";
echo "<label>Add a date (YYYY-MM-DD):</label>";
echo "<input type='hidden' name='token' value='".$_GET['token']."'/>";
echo "<input type='hidden' name='op' value='add' />";
echo "<input type='text' name='date'>";
echo "<input type='submit' value='submit'/>";
echo "</form>";

?>