<?php

include_once('/etc/apache2/db-passwords/nightline.php');

$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

$stmt = $sqlConnetion->prepare("SELECT * FROM storySubmission;");
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$sqlConnetion->close();

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