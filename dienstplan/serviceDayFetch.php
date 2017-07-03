<?php

include_once('/etc/apache2/db-passwords/nightline.php');

function serviceStaffCountForService($pId) {
    // Fetch from db
    $sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
    $stmt->bind_param('i', $pId);
    $stmt->execute();
    $resultsUsers = $stmt->get_result();
    $stmt->close();

    return $resultsUsers->num_rows;
}

?>
