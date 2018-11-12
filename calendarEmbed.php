<?php

require_once('dienstplan/config.php');

function buildCalendarInline() {
    global $dbServer, $dbUser, $dbPassword, $dbName;

    // Prepare sql connection
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);
    if ($sqlConnetion->connect_errno) {
        echo("<p>Kalender derzeit nicht verfügbar.<p>");
        return; // Do nothing else 
    }

    // Print calendar
    $stmt = $sqlConnetion->prepare("SELECT * FROM calendarEntry WHERE date >= CURDATE() ORDER BY date ASC");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    if ($results->num_rows > 0) {
        echo "<table>";
            echo "<tr><th>Datum</th><th>Uhrzeit oder Dauer</th><th>Was?</th></tr>";
                while ($row = $results->fetch_assoc()) {
                    // Print entry
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
    } else {
        echo "<p>Momentan sind keine Kalendereinträge vorhanden. Schau bald wieder vorbei, um auf dem aktuellen Stand zu bleiben.</p>";
    }
        
    // Close sql connection
    $sqlConnetion->close();
}

?>