<?php

require_once('user.php');

function buildUsersTable($pUser) {
	if (!$pUser->isPrivileged) {
		return;
	}

	// Build admin page
	echo "<h2>Benutzerverwaltung</h2>";

	$sqlConnetion = User::connect();
    $stmt = $sqlConnetion->prepare("SELECT * FROM user");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

	echo "<table>";
    echo "<tr><th>Benutzername</th><th>Emailadresse</th><th>Typ</th><th>Status</th></tr>";
	while ($row = $results->fetch_assoc()) {
    	echo "<tr>";
    	echo "<td>".$row['user']." <a class='redButton' href='deleteAccount.php?user=".$row['user']."'>Löschen</a>"."</td>";
    	echo "<td>".$row['email']."</td>";
        if ($row['isPrivileged']) {
            echo "<td>Admin <a class='yellowButton' href='changeAdmin.php?user=".$row['user']."&privileged=0'>Zu normalem User machen</a></td>";
        } else {
            echo "<td>Benutzer <a class='yellowButton' href='changeAdmin.php?user=".$row['user']."&privileged=1'>Zu Admin machen</a></td>";
        }
    	if ($row['activated']) {
    		echo "<td><a>Aktiviert</a> <a class='redButton' href='activateAccount.php?user=".$row['user']."&activate=0'>Sperren</a>";
    	} else {
    		echo "<td><a>Nicht aktiviert</a> <a class='greenButton' href='activateAccount.php?user=".$row['user']."&activate=1'>Aktivieren</a>";
    	}
        echo "<br/><br/><a class='yellowButton' href='passwordReset.php?user=".$row['user']."'>Passwort zurücksetzen</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}

?>