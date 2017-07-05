<?php

require_once('dienstplan/config.php');

if ($_GET['origin'] == "onDemandButton") {
	$sqlConnetion = new mysqli($dbServer, $dbUser, $dbPassword, $dbName);

	// Fetch next date
	$stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE DATE_ADD(TIMESTAMP(date), INTERVAL 16 HOUR) >= NOW() AND service = 0 ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    if ($results->num_rows > 0) {
        $firstRow = $results->fetch_assoc();

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

        echo "write to db";

		// Send mail
		$to      = 	'rs@robinschnaidt.com';
		$subject = 	'[NL-Bot] Dienst angefordert';
		$message = 	'Es wurde eben ein Telefondienst für '.$dateOutputString.' angefordert. Einen schönen Tag dir! ☀️  -- Nightline Bot';
		$headers = 	'From: no-reply@nightline-karlsruhe.de'."\r\n".
         			'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
           			'X-Mailer: PHP/'.phpversion();

		mail($to, $subject, $message, $headers);

		// Redirect back
		header("Location: on-demand.html?success=1");
    } else {
        // Redirect back
        header("Location: on-demand.html");
    }

    $sqlConnetion->close();
} else {
    // Redirect back
    header("Location: on-demand.html");  
}

?>