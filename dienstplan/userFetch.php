<?php

 function checkUsernameExists($pSql, $pUser) {
    // Look up in db
    $stmt = $pSql->prepare("SELECT * FROM user WHERE user = ?");
    $stmt->bind_param('s', $pUser);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Check results
    return ($results->num_rows != 0);
 }

 function checkEmailExists($pSql, $pEmail) {
    // Look up in db
    $stmt = $pSql->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param('s', $pEmail);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Check results
    return ($results->num_rows != 0);
 }

?>