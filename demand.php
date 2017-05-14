<?php

include_once('/etc/apache2/db-passwords/nightline.php');

if ($_GET['origin'] == "onDemandButton") {
	// Write to db
	$sqlConnetion = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

	$stmt = $sqlConnetion->prepare("INSERT INTO onDemandEntry (ip) VALUES (?)");
	$stmt->bind_param('s', $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	$stmt->close();
	$sqlConnetion->close();

	// Fetch next date
	$stmt = $sqlConnetion->prepare("SELECT * FROM serviceDay WHERE date >= CURDATE() ORDER BY date ASC;");
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();

    $dateOutputString = "n/a";
    if ($results->num_rows > 0) {
        // Display that a service is available
        $firstRow = $results->fetch_assoc();
        if ($firstRow['date'] == date('Y-m-d')) {
            $dateOutputString = "heute";
        } else {
            $date = new DateTime($firstRow['date']);
            $dateOutputString = $date->format('d.m.');
        }
    }
        
    $sqlConnetion->close();

	// Send mail
	$to      = 	'ka-aktive@nl2.kip.uni-heidelberg.de';
	$subject = 	'[NL-Bot] Dienst angefordert';
	$message = 	'Es wurde eben ein Telefondienst für '.$dateOutputString.' angefordert. Einen schönen Tag dir! -- Nightline Bot';
	$headers = 	'From: no-reply@nightline-karlsruhe.de'."\r\n".
         		'Reply-To: no-reply@nightline-karlsruhe.de'."\r\n".
           		'X-Mailer: PHP/'.phpversion();

	//mail($to, $subject, $message, $headers);

	// Redirect back
	header("Location: on-demand.html?success=1");
} else {
	header("Location: on-demand.html");
}

?>