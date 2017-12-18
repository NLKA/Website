<?php

require_once('dienstplan/config.php');

function buildOnDemandTopbar() {
    global $dbServer, $dbUser, $dbPassword, $dbName;

    // Prepare sql connection
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

    // Check if there is a cofirmed service in the future or today
    $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE date >= CURDATE() AND service = 1 ORDER BY date ASC;");
    $stmt->execute();

    $results = $stmt->get_result();
    $stmt->close();

    if ($results->num_rows > 0) {
        // Display that a sure service is available
        $firstRow = $results->fetch_assoc();
        if ($firstRow['date'] == date('Y-m-d')) {
            echo "<div id='topBar'><p id='topbarText'>☎️ Wir sind heute 21-0h für dich erreichbar unter 0721-75406646</div>";
        } else {
            $date = new DateTime($firstRow['date']);
            $dateOutputString = $date->format('d.m.');
            echo "<div id='topBar'><p id='topbarText'>☎️ Wir sind am ".$dateOutputString." 21-0h für dich erreichbar unter 0721-75406646</div>";
        }
    } else {
        // Now check if there are scheduled services in the future or today that are bookable
        $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE DATE_ADD(TIMESTAMP(date), INTERVAL 16 HOUR) >= NOW() AND service = 0 ORDER BY date ASC;");
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        // ...and display topbar if this is the case
        $hasOnDemandServiceDays = $results->num_rows > 0;
        if ($hasOnDemandServiceDays) {
            $firstRow = $results->fetch_assoc();

            // check if two service staff members are present
            $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
            $stmt->bind_param('i', $firstRow['serviceDayId']);
            $stmt->execute();
            $resultsUsers = $stmt->get_result();
            $stmt->close();

            $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
            if ($serviceStaffAvailable) {
                if ($firstRow['date'] == date('Y-m-d')) {
                    echo "<div id='topBar'><p id='topbarText'>☎️ Wir können heute 21-0h für dich erreichbar sein: <a href='on-demand.html' id='anfordern'>Telefondienst anfordern</a></div>";
                } else {
                    $date = new DateTime($firstRow['date']);
                    $dateOutputString = $date->format('d.m.');
                    echo "<div id='topBar'><p id='topbarText'>☎️ Wir können am ".$dateOutputString." 21-0h für dich erreichbar sein: <a href='on-demand.html' id='anfordern'>Telefondienst anfordern</a></div>";
                }
            }
        }
    }

    // Close sql connection
    $sqlConnetion->close();
}

function buildOnDemandInline() {
    global $dbServer, $dbUser, $dbPassword, $dbName;

    // Prepare sql connection
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

    // Check if there is a confirmed service in the future or today
    $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE date >= CURDATE() AND service = 1 ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    if ($results->num_rows > 0) {
        // Display that a service is available
        $firstRow = $results->fetch_assoc();
        if ($firstRow['date'] == date('Y-m-d')) {
            echo "<p>Wir sind <b>heute Abend</b> 21-0h für dich, wie gewohnt, unter der Nummer <b>0721-75406646</b> erreichbar.</p>";
        } else {
            $date = new DateTime($firstRow['date']);
            $dateOutputString = $date->format('d.m.');
            echo "<p>Wir sind am <b>".$dateOutputString."</b> 21-0h für dich, wie gewohnt, unter der Nummer <b>0721-75406646</b> erreichbar.";
        }
    } else {
        // Now check if there are scheduled services in the future or today that are bookable
        $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE DATE_ADD(TIMESTAMP(date), INTERVAL 16 HOUR) >= NOW() AND service = 0 ORDER BY date ASC;");
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        // ...and display text if this is the case
        $hasOnDemandServiceDays = $results->num_rows > 0;
        if ($hasOnDemandServiceDays) {
            $firstRow = $results->fetch_assoc();

            // check if two service staff members are present
            $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
            $stmt->bind_param('i', $firstRow['serviceDayId']);
            $stmt->execute();
            $resultsUsers = $stmt->get_result();
            $stmt->close();

            $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
            if ($serviceStaffAvailable) {
                if ($firstRow['date'] == date('Y-m-d')) {
                    echo "<p>Unseren nächsten Telefondienst kannst du für <b>heute Abend</b> zwischen 21 und 0 Uhr, <a href='on-demand.html'>hier anfordern</a>.</p>";
                } else {
                    $date = new DateTime($firstRow['date']);
                    $dateOutputString = $date->format('d.m.');
                    echo "<p>Unseren nächsten Telefondienst kannst du für den<b> ".$dateOutputString."</b> 21-0h <a href='on-demand.html' id='anfordern'>hier anfordern.</a></p>";
                }
            } else {
                // Display that no service is scheduled
                echo "<p><b>Die nächsten Tage, an denen du einen Telefondienst anfordern kannst, werden an dieser Stelle bekanntgegeben.</b> Momentan steht noch kein Termin fest.</p><br />";
            }
        } else {
            // Display that no service is scheduled
            echo "<p><b>Die nächsten Tage, an denen du einen Telefondienst anfordern kannst, werden an dieser Stelle bekanntgegeben.</b></p><br />";
        }
    }

    // Also display other dates
    echo "<p>Mögliche weitere Termine, die bald angefordert werden können:</p>";
    $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE date > CURDATE() ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    while ($row = $results->fetch_assoc()) {
        $date = new DateTime($row['date']);
        $dateOutputString = $date->format('d.m.');
        echo "<p>".$dateOutputString."</p>";
    }
    
    // Close sql connection
    $sqlConnetion->close();
}

function buildOnDemandPage() {
    global $dbServer, $dbUser, $dbPassword, $dbName;

    // Prepare sql connection
    $sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

    // Check if there is a cofirmed service in the future or today
    $stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE DATE_ADD(TIMESTAMP(date), INTERVAL 16 HOUR) >= NOW() AND service = 0 ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Display if a service is available
    $onDemandDayAvialable = $results->num_rows > 0;
    if ($onDemandDayAvialable) {
        $firstRow = $results->fetch_assoc();

        // check if two service staff members are present
        $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
        $stmt->bind_param('i', $firstRow['serviceDayId']);
        $stmt->execute();
        $resultsUsers = $stmt->get_result();
        $stmt->close();

        $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
        if ($serviceStaffAvailable) {
            if ($_GET['success'] != 1) {
                // Generate on demand token and write to db
                $token = join('-', str_split(bin2hex(openssl_random_pseudo_bytes(8)), 4));

                $stmt = $sqlConnetion->prepare("INSERT INTO onDemandToken (token) VALUES (?)");
                $stmt->bind_param('s', $token);
                $stmt->execute();
                $stmt->close();

                // Save token locally
                echo "<script>var onDemandToken='".$token."';</script>";
            }

            // Display ui
            if ($firstRow['date'] == date('Y-m-d')) {
                echo "<a id='button' class='ym-button ym-next'>Dienst heute Abend 21-0h anfordern</a>";
            } else {
                $date = new DateTime($firstRow['date']);
                $dateOutputString = $date->format('d.m.');
                echo "<a id='button' class='ym-button ym-next'>Dienst am ".$dateOutputString." 21-0h anfordern</a>";
                echo "<script>$('#topBar').hide();</script>";
            }
        } else {
            echo "Momentan sind noch keine weiteren Dienste geplant.";
        }
    } else {
        echo "Momentan sind noch keine weiteren Dienste geplant.";
    }
        
    // Close sql connection
    $sqlConnetion->close();
}

?>