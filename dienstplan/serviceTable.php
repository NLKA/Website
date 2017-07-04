<?php

require_once('config.php');

function buildServiceTable($pUser) {
	global $dbServer, $dbUser, $dbPassword, $dbName;

	if (!$pUser) {
		return;
	}

	// // Buid service days table
	echo "<h2>Aktueller Dienstplan</h2>";
    if ($pUser->isPrivileged && $_GET['editServices'] == 0) {
        echo "<p><a class='greyButton' href='home.php?editServices=1'>Dienste bearbeiten</a></p>";
    }

	// Fetch table from db
	$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
	$results = $sqlConnetion->query("SELECT * FROM serviceDay WHERE date >= CURDATE() ORDER BY date;");

	// Print table
	echo "<table border='1' style='width: 100%;'>";
	echo "<tr><th>Datum</th><th>Findet statt</th><th>Nightliner</th></tr>";

    $rowCount = 0;
	while ($row = $results->fetch_assoc()) {
		echo "<tr>";
            echo "<td>";
                echo "<p>".$row['date']." (".date("D", strtotime($row['date'])).") "; 
                if ($pUser->isPrivileged && $_GET['editServices'] == 1) {
                    echo "<a class='redButton' href='serviceDayModify.php?op=delete&id=".$row['serviceDayId']."'>Löschen</a>";    		
                }
                echo "</p>";
            echo "</td>";

            echo "<td>";
                if ($row['service']) {
                    echo "<p>✅ Ja ";
                    if ($pUser->isPrivileged) {
                        echo "<a class='yellowButton' href='serviceDayModify.php?op=unconfirm&id=".$row['serviceDayId']."'>Widerrufen</a>";	
                    }
                    echo "</p>";
                } else {
                    // Check if at least two nightliners are available
                    $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
                    $stmt->bind_param('i', $row['serviceDayId']);
                    $stmt->execute();
                    $resultsUsers = $stmt->get_result();
                    $stmt->close();

                    $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
                    $oneMissing = $resultsUsers->num_rows == 1;
                    if ($serviceStaffAvailable) {
                        echo "<p>⏳ Ausstehend ";
                        if ($pUser->isPrivileged) {
                            echo "<a class='greenButton' href='serviceDayModify.php?op=confirm&id=".$row['serviceDayId']."'>Bestätigen</a>";        
                        }

                        if ($rowCount == 0) {
                            echo "<p><b>🚨 Aktiv in On-Demand</b></p>";
                        }
                    } else {
                        if ($oneMissing) {
                            echo "<p>❌👤 Zu wenige Nightliner "; 
                        } else {
                            echo "<p>❌👥 Keine Nightliner "; 
                        }
                    }
                    echo "</p>";
                }
            echo "</td>";

    	   // load entries for this service day
            echo "<td>";
                $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
                $stmt->bind_param('i', $row['serviceDayId']);
                $stmt->execute();
                $resultsUsers = $stmt->get_result();
                $stmt->close();

                $selfHasEntry = false;
                $isFirst = true;
                while ($rowUser = $resultsUsers->fetch_assoc()) {
                    if (!$isFirst) {
                        echo ", ";
                    } else {
                        echo "<p>";
                        $isFirst = false;
                    }

                    echo $rowUser['user'];

                    if ($rowUser['user'] == $pUser->user) {
                        $selfHasEntry = true;
                    }
                }
                if (!$isFirst) {
                    echo "</p>";
                }

                echo "<p>";
                    if ($selfHasEntry) {
                        echo " <a class='yellowButton' href='serviceStaffModify.php?op=delete&id=".$row['serviceDayId']."&user=".$pUser->user."'>Zurücknehmen</a>";
                    } else {
                        echo " <a class='greenButton' href='serviceStaffModify.php?op=add&id=".$row['serviceDayId']."&user=".$pUser->user."'>Zum Dienst eintragen</a>";
                    }

                if ($pUser->isPrivileged) {
                    echo " <a class='greyButton' href='editServiceStaff.php?id=".$row['serviceDayId']."'>Bearbeiten</a>";
                }
                echo "</p>";
            echo "</td>";
        echo "</tr>";

        $rowCount++;
	}
	echo "</table>";

	echo "<br/>";
	// Add new service day form
	echo "<form action='serviceDayModify.php' method='get'>";
	   echo "<label>Neuen Diensttermin anlegen (YYYY-MM-DD): </label>";
	   echo "<input type='hidden' name='op' value='add' />";
	   echo "<input type='text' id='datepicker' name='date'> ";
	   echo "<input type='submit' value='Anlegen'/>";
	echo "</form>";

    echo "<script src='js/jquery-ui/external/jquery/jquery.js'></script>";
    echo "<script src='js/jquery-ui/jquery-ui.min.js'></script>";
    echo "<script>$(function() { $('#datepicker').datepicker({dateFormat:'yy-mm-dd'}); });</script>";

    // Build history
    echo "<h2>Vergangene Dienste</h2>";
    if ($pUser->isPrivileged && $_GET['editServices'] == 0) {
        echo "<p><a class='greyButton' href='home.php?editServices=1'>Historie bearbeiten</a></p>";
    }

    $results = $sqlConnetion->query("SELECT * FROM serviceDay WHERE date < CURDATE() ORDER BY date desc;");

    // Print table
    echo "<table border='1' style='width: 100%;'>";
    echo "<tr><th>Datum</th><th>Fand statt</th><th>Nightliner</th></tr>";

    while ($row = $results->fetch_assoc()) {
        echo "<tr>";
            echo "<td>".$row['date']." (".date("D", strtotime($row['date'])).")";
            if ($pUser->isPrivileged && $_GET['editServices'] == 1) {
                echo " <a class='redButton' href='serviceDayModify.php?op=delete&id=".$row['serviceDayId']."'>Löschen</a>";
            
            }
        echo "</td>";

        echo "<td>";
            if ($row['service']) {
                echo "✅ Ja";
            } else {
                echo "🚫 Nein";
            }
        echo "</td>";

        // load entries for this service day
        echo "<td>";
            $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
            $stmt->bind_param('i', $row['serviceDayId']);
            $stmt->execute();
            $resultsUsers = $stmt->get_result();
            $stmt->close();

            $isFirst = true;
            while ($rowUser = $resultsUsers->fetch_assoc()) {
                if (!$isFirst) {
                    echo ", ";
                } else {
                    echo "<p>";
                    $isFirst = false;
                }

                echo $rowUser['user'];
            }

            if (!$isFirst) {
                echo "</p>";
            }

            if ($pUser->isPrivileged) {
                echo "<p><a class='greyButton' href='editServiceStaff.php?id=".$row['serviceDayId']."'>Bearbeiten</a></p>";
            }
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

	$sqlConnetion->close();
}

?>