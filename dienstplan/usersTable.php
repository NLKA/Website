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
    echo "<tr><th>Benutzer</th><th>Emailadresse</th><th>Typ</th><th>Status</th></tr>";
	while ($row = $results->fetch_assoc()) {
    	echo "<tr>";
            echo "<td>";
                echo "<p>".$row['user']."</p>";
                echo "<p><a class='redButton' href='deleteAccount.php?user=".$row['user']."'>Löschen</a></p>";
            echo "</td>";
            
            echo "<td>";
                echo $row['email'];
                echo "<p><a class='yellowButton' href='passwordReset.php?user=".$row['user']."'>Passwort zurücksetzen</a></p>";
            echo "</td>";

            if ($row['isPrivileged']) {
                echo "<td>";
                    echo "<p>Admin</p>";
                    echo "<p><a class='yellowButton' href='changeAdmin.php?user=".$row['user']."&privileged=0'>Zu normalem User machen</a></p>";
                echo "</td>";
            } else {
                echo "<td>";
                    echo "<p>Benutzer</p>";
                    echo "<p><a class='yellowButton' href='changeAdmin.php?user=".$row['user']."&privileged=1'>Zu Admin machen</a></p>";
                echo "</td>";
            }
    	if ($row['activated']) {
    		echo "<td>";
                echo "<p>Aktiviert</p>";
                echo "<p><a class='redButton' href='activateAccount.php?user=".$row['user']."&activate=0'>Sperren</a></p>";
            echo "</td>";
    	} else {
    		echo "<td>";
                echo "<p>Nicht aktiviert</p>";
                echo "<p><a class='greenButton' href='activateAccount.php?user=".$row['user']."&activate=1'>Aktivieren</a></p>";
            echo "</td>";
    	}
		echo "</tr>";
	}
	echo "</table>";
}

?>