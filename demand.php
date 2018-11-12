<?php

require_once('dienstplan/config.php');

/**
Demand service for the next available date.
@param token: A token assigned on entry, then also present in db.
*/

// Only proceed if this URL was called from the on demand button
$callFromOnDemandButton = $_GET['origin'] == "onDemandButton";
if ($callFromOnDemandButton) {
    // Connect to db
	$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

    // Get token in URL query from db
    $stmt = $sqlConnetion->prepare("SELECT * FROM onDemandToken WHERE token = ? AND DATE_ADD(time, INTERVAL 1 HOUR) >= NOW()");
    $stmt->bind_param('s', $_GET['token']);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    // Check if provided token is valid
    if ($results->num_rows != 1) {
        echo "Token invalid";
        exit();
    } else {
        // Remove token from db
        $stmt = $sqlConnetion->prepare("DELETE FROM onDemandToken WHERE token = ?");
        $stmt->bind_param('s', $_GET['token']);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();
    }

	// Fetch next date
	$stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE DATE_ADD(TIMESTAMP(date), INTERVAL 16 HOUR) >= NOW() AND service = 0 ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    $onDemandDayAvailable = $results->num_rows > 0;
    if ($onDemandDayAvailable) {
        $firstRow = $results->fetch_assoc();

        // Check if at least two nightliners are available
        $stmt = $sqlConnetion->prepare("SELECT user FROM serviceDayStaff WHERE serviceDayId = ?");
        $stmt->bind_param('i', $firstRow['serviceDayId']);
        $stmt->execute();
        $resultsUsers = $stmt->get_result();
        $stmt->close();

        $serviceStaffAvailable = $resultsUsers->num_rows >= 2;
        if ($serviceStaffAvailable) {
            $dateOutputString = "n/a";
            if ($firstRow['date'] == date('Y-m-d')) {
                $dateOutputString = "heute";
            } else {
                $date = new DateTime($firstRow['date']);
                $dateOutputString = $date->format('d.m.');
            }

            // Write to db
            $stmt = $sqlConnetion->prepare("INSERT INTO onDemandEntry (ip, serviceDayId) VALUES (?,?)");
            $stmt->bind_param('si', hash("sha256", $_SERVER['REMOTE_ADDR']), $firstRow['serviceDayId']);
            $stmt->execute();
            $stmt->close();

            // Send mail
            $sendMail = true;
            if ($sendMail) {
                $to      =  'ka-aktive@nl2.kip.uni-heidelberg.de';
                $subject =  '[NL-Bot] Dienst angefordert';
                $message =  'Es wurde eben ein Telefondienst für '.$dateOutputString.' angefordert. Einen schönen Tag dir! ☀️  -- Nightline Bot';
                $headers =  'From: no-reply@nightline-karlsruhe.de'."\r\n".
                            'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
                            'X-Mailer: PHP/'.phpversion();

                mail($to, $subject, $message, $headers);
            }

            // Redirect back - success
            header("Location: on-demand.html?success=1");
        } else {
            // Redirect back - No(!) success
            header("Location: on-demand.html");
        }
    } else {
        // Redirect back - No(!) success
        header("Location: on-demand.html");
    }

    $sqlConnetion->close();
} else {
    // Redirect back - No(!) success
    header("Location: on-demand.html");  
}

?>