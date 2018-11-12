<?php

require_once('dienstplan/config.php');

/**
Echos a table element with calendar entries from db.
*/
function buildCalendarInline() {
    // Access global db config
    global $dbServer, $dbUser, $dbPassword, $dbName;

    // Prepare sql connection
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
    if ($sqlConnetion->connect_errno) {     // In case of db connection issues... 
        echo("<p>Kalender derzeit nicht verfügbar.<p>");
        return;     // Do nothing else 
    }

    // Fetch future entries from database
    $stmt = $sqlConnetion->prepare("SELECT * FROM calendarEntry WHERE date >= CURDATE() ORDER BY date ASC");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Create table with entries
    $hasEntries = $results->num_rows > 0;
    if ($hasEntries) {      // In case there are future calendar entries
        echo "<table>";
            // Add table head
            echo "<tr><th>Datum</th><th>Uhrzeit oder Dauer</th><th>Was?</th></tr>";
        
            // Add all future entry rows
            while ($row = $results->fetch_assoc()) {
                echo "<tr>";
                    echo "<td>";
                        echo "<p>".$row['date']."</p>";
                    echo "</td>";
            
                    echo "<td>";
                        if ($row['time'] == "") {
                            echo "--";
                        } else {
                            echo "<p>".$row['time']."</p>";
                        }
                    echo "</td>";

                    echo "<td>";
                        echo "<p>".$row['entry']."</p>";
                    echo "</td>";
                echo "</tr>";
            }
        echo "</table>";
    } else {    // In case there are no entries
        echo "<p>Momentan sind keine Kalendereinträge vorhanden. Schau bald wieder vorbei, um auf dem aktuellen Stand zu bleiben.</p>";
    }
        
    // Close sql connection
    $sqlConnetion->close();
}

?>