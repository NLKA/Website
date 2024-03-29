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

    // Add new service day form
    echo "<form action='serviceDayModify.php' method='get'>";
       echo "<label>Neuen Diensttermin anlegen: </label>";
       echo "<input type='hidden' name='op' value='add' />";
       echo "<input type='text' id='datepicker' name='date' placeholder='JJJJ-MM-TT'> ";
       echo "<input type='submit' value='Anlegen'/>";
    echo "</form>";

    echo "<script src='js/jquery-ui/external/jquery/jquery.js'></script>";
    echo "<script src='js/jquery-ui/jquery-ui.min.js'></script>";
    echo "<script>$(function() { $('#datepicker').datepicker({dateFormat:'yy-mm-dd'}); });</script>";

	// Fetch table from db
	$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
	$results = $sqlConnetion->query("SELECT * FROM serviceDay WHERE date >= CURDATE() ORDER BY date;");

	// Print table
	echo "<table border='1' style='width: 100%;'>";
	echo "<tr><th>Datum</th><th>Findet statt</th><th>Nightliner</th></tr>";

    $noConfirmedService = true;
    $rowCount = 0;
    $firstRowInOnDemand = false;
    $firstRowStaffAvailable = false;
	while ($row = $results->fetch_assoc()) {
		echo "<tr>";
            echo "<td class='serviceColumn'>";
                echo "<p>".$row['date']." (".date("D", strtotime($row['date'])).") "; 
                if ($pUser->isPrivileged && $_GET['editServices'] == 1) {
                    echo "<a class='redButton' href='serviceDayModify.php?op=delete&id=".$row['serviceDayId']."'>Löschen</a>";    		
                }
                echo "</p>";
            echo "</td>";

            echo "<td class='serviceColumn'>";
                echo "<p>";
                    if ($row['service']) {
                        $noConfirmedService = false;
                        echo "✅ Ja ";
                        if ($pUser->isPrivileged) {
                            echo "<a class='yellowButton' href='serviceDayModify.php?op=unconfirm&id=".$row['serviceDayId']."'>Widerrufen</a>";	
                        }
                    } else {
                        echo "<a class='greenButton' href='serviceDayModify.php?op=confirm&id=".$row['serviceDayId']."'>Bestätigen</a><br/>";

                        // Check if at least two nightliners are available
                        $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
                        $stmt->bind_param('i', $row['serviceDayId']);
                        $stmt->execute();
                        $resultsUsers = $stmt->get_result();
                        $stmt->close();

                        $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
                        $oneMissing = $resultsUsers->num_rows == 1;
                        if ($serviceStaffAvailable) {
                            echo "<br/>⏳ Ausstehend ";

                            if ($rowCount == 0) {
                                $firstRowInOnDemand = ((int)date('H') < 16 || date('Y-m-d') != $row['date']);
                                $firstRowStaffAvailable = $serviceStaffAvailable;
                            }
                            if ($noConfirmedService && ($firstRowInOnDemand && $rowCount == 0 || !$firstRowInOnDemand && $firstRowStaffAvailable && $rowCount == 1)) {
                                echo "<p><b>🚀 Aktiv in On-Demand</b></p>";
                            }
                        } else {
                            if ($oneMissing) {
                                echo "<p>⚠️👤 Zu wenige Nightliner "; 
                            } else {
                                echo "<p>❌👥 Keine Nightliner "; 
                            }
                        }
                    }

                    // Display number of requests
                    $stmtRequests = $sqlConnetion->prepare("SELECT COUNT(*) AS count FROM onDemandEntry WHERE serviceDayId = ?;");
                    $stmtRequests->bind_param('i', $row['serviceDayId']);
                    $stmtRequests->execute();
                    $resultsRequests = $stmtRequests->get_result();
                    $stmtRequests->close();

                    $requestsRow = $resultsRequests->fetch_assoc();
                    echo "<p>";
                        if ($requestsRow['count'] == 0) {
                            echo "(Noch keine Anfragen)";
                        } else {
                            echo "(Aktuelle Anfragen: ".$requestsRow['count'].")";
                        }
                    echo "</p>";
                echo "</p>";
            echo "</td>";

    	    // load entries for this service day
            echo "<td>";
                // Print staff entries
                $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
                $stmt->bind_param('i', $row['serviceDayId']);
                $stmt->execute();
                $resultsUsers = $stmt->get_result();
                $stmt->close();

                $selfHasEntry = false;
                $dummyHasEntry = false;
                $isFirst = true;
                while ($rowUser = $resultsUsers->fetch_assoc()) {
                    if (!$isFirst) {
                        echo ", ";
                    } else {
                        echo "<p>";
                        $isFirst = false;
                    }

                    echo $rowUser['user'];

                    // Check existing users
                    if ($rowUser['user'] == $pUser->user) {
                        $selfHasEntry = true;
                    }
                    if ($rowUser['user'] == "DummyUser") {
                        $dummyHasEntry = true;
                    }
                }
                if (!$isFirst) {
                    echo "</p>";
                }

                // Print staff notes
                $stmt = $sqlConnetion->prepare("SELECT * FROM serviceStaffNote WHERE serviceDayId = ?");
                $stmt->bind_param('i', $row['serviceDayId']);
                $stmt->execute();
                $resultsUsers = $stmt->get_result();
                $stmt->close();

                $isFirstNote = true;
                while ($rowNote = $resultsUsers->fetch_assoc()) {
                    if (!$isFirstNote) {
                        echo "<br/>";
                    } else {
                        echo "<p>";
                        $isFirstNote = false;
                    }

                    echo "<a class='note'><b>Notiz von ".trim($rowNote['user']).'</b>: '.$rowNote['note']."</a><br/>";
                }
                if (!$isFirst) {
                    echo "</p>";
                }

                // Add buttons
                echo "<p>";
                    if ($selfHasEntry) {
                        echo " <a class='yellowButton' href='serviceStaffModify.php?op=delete&id=".$row['serviceDayId']."&user=".$pUser->user."'>Zurücknehmen</a>";
                    } else {
                        echo " <a class='greenButton' href='serviceStaffModify.php?op=add&id=".$row['serviceDayId']."&user=".$pUser->user."'>Zum Dienst eintragen</a>";
                    }

                    if (!$dummyHasEntry) {
                        echo " <a class='greyButton' href='serviceStaffModify.php?op=add&id=".$row['serviceDayId']."&user=DummyUser'>Dummy eintragen</a>";
                    }
                    echo "<br/>";
                    echo "<br/>";

                    if ($pUser->isPrivileged) {
                        echo " <a class='greyButton' href='editServiceStaff.php?id=".$row['serviceDayId']."'>Bearbeiten</a>";
                    }

                    echo " <a class='greyButton' href='editServiceNote.php?id=".$row['serviceDayId']."'>+ Notiz</a>"; 
                echo "</p>";
            echo "</td>";
        echo "</tr>";

        $rowCount++;
	}
	echo "</table>";

	echo "<br/>";

    // Build history
    echo "<h2>Vergangene Dienste</h2>";
    if ($pUser->isPrivileged && $_GET['editServices'] == 0) {
        echo "<p><a class='greyButton' href='home.php?editServices=1'>Historie bearbeiten</a></p>";
    }

    $results = $sqlConnetion->query("SELECT * FROM serviceDay WHERE date < CURDATE() ORDER BY date desc;");

    // Print table
    echo "<table border='1' style='width: 100%;'>";
    echo "<tr><th>Datum</th><th>Fand statt</th><th>Nightliner</th></tr>";

    $MAX_HISTORY_ROWS = 3;

    $rowCount = 0;
    while (($row = $results->fetch_assoc()) && ($rowCount < $MAX_HISTORY_ROWS)) {
        echo "<tr>";
            echo "<td class='serviceColumn'>".$row['date']." (".date("D", strtotime($row['date'])).")";
            if ($pUser->isPrivileged && $_GET['editServices'] == 1) {
                echo " <a class='redButton' href='serviceDayModify.php?op=delete&id=".$row['serviceDayId']."'>Löschen</a>";
            
            }
        echo "</td>";

        echo "<td class='serviceColumn'>";
            if ($row['service']) {
                echo "✅ Ja";
            } else {
                echo "🚫 Nein";
            }

            // Display number of requests
            $stmtRequests = $sqlConnetion->prepare("SELECT COUNT(*) AS count FROM onDemandEntry WHERE serviceDayId = ?;");
            $stmtRequests->bind_param('i', $row['serviceDayId']);
            $stmtRequests->execute();
            $resultsRequests = $stmtRequests->get_result();
            $stmtRequests->close();

            $requestsRow = $resultsRequests->fetch_assoc();
            echo "<p>";
                if ($requestsRow['count'] == 0) {
                    echo "(Keine Anfragen)";
                } else {
                    echo "(Anfragen: ".$requestsRow['count'].")";
                }
            echo "</p>";
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

            // Print staff notes
            $stmt = $sqlConnetion->prepare("SELECT * FROM serviceStaffNote WHERE serviceDayId = ?");
            $stmt->bind_param('i', $row['serviceDayId']);
            $stmt->execute();
            $resultsUsers = $stmt->get_result();
            $stmt->close();

            $isFirstNote = true;
            while ($rowNote = $resultsUsers->fetch_assoc()) {
                if (!$isFirstNote) {
                    echo "<br/>";
                } else {
                    echo "<p>";
                    $isFirstNote = false;
                }

                echo "<a class='note'><b>Notiz von ".trim($rowNote['user'])."</b>: ".$rowNote['note']."</a><br/>";
            }
            if (!$isFirst) {
                echo "</p>";
            }
            
            // Add button
            if ($pUser->isPrivileged) {
                echo "<p><a class='greyButton' href='editServiceStaff.php?id=".$row['serviceDayId']."'>Bearbeiten</a></p>";
            }

            echo "</td>";
        echo "</tr>";

        $rowCount++;
    }
    echo "</table>";

    if ($rowCount == $MAX_HISTORY_ROWS) {
        echo "<p>(Einige ältere Dienste werden nicht angezeigt)</p>";
    }

	$sqlConnetion->close();
}

?>