<?php

function serviceStaffCountForService($pId) {
    // Fetch from db
    $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
    $stmt->bind_param('i', $pId);
    $stmt->execute();
    $resultsUsers = $stmt->get_result();
    $stmt->close();

    return $resultsUsers->num_rows;
}

?>
