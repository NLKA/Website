<?php

/**
Helper functions to access db user records.
*/

/**
Check if a user with name pUser exists in db.
@param pSql: The sql connection to use
@param pUser: A user name
@return: Whether a user with this name exists or not
*/
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

/**
Check whether a prefix of length 30 of pEmailHashFullOrPrefix exists in db.
@param pSql: The sql connection to use
@param pEmailHashFullOrPrefix: The hash or prefix of a hash to look for
@return: Whether a prefix of length 30 of pEmailHashFullOrPrefix is present in db.
*/  
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

/**
Gets the email hash prefix for user with name pUser from db.
@param pSql: The sql connection to use
@param pUser: The user name
@return: The email hash prefix or empy string
*/
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
