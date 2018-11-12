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

 function checkEmailHashPrefix30Exists($pSql, $pEmailHashFullOrPrefix) {
    $prefixOfLength30 = substr($pEmailHashFullOrPrefix, 0, 30);

    // Look up in db
    $stmt = $pSql->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param('s', $prefixOfLength30);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Check results
    return ($results->num_rows != 0);
}

function emailHashPrefix30ForUser($pSql, $pUser) {
    if (checkUsernameExists($pSql, $pUser)) {
        // Look up in db
        $stmt = $pSql->prepare("SELECT * FROM user WHERE user = ?");
        $stmt->bind_param('s', $pUser);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        if ($results->num_rows != 0) {
            $row = $results->fetch_assoc();
            return $row['email'];
        }
    } else {
        return "";
    }
}

?>
