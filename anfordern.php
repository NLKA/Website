<?php

// DB example
include('/etc/apache2/db-passwords/nightline.php');

$data = [1, 5, 9];

$db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
$db->query("CREATE TABLE test (id INT PRIMARY KEY)");
$insert_number_statement = $db->prepare("INSERT INTO test VALUES (?)");

foreach ($data as $n) {
	$insert_number_statement->execute([$n]);
}

$results = $db->query("SELECT * FROM test");

foreach ($results as $row) {
	echo implode(" -- ", $row);
	echo "<br />";
}

$db->query("DROP TABLE test");

echo "Success";

/////////////////////////////////
// TODO REMOVE !!! //////////////
exit(0); ////////////////////////
/////////////////////////////////

// send mail
$to      = 'karlsruhe@nightlines.eu';
$subject = '[NL] Telefondienst angefordert';
$message = 'Es wurde heute ein Telefondienst angefordert. Wer wird heute Abend da sein? -- Nightline Bot';
$headers = 'From: no-reply@nightline-karlsruhe.de' . "\r\n" .
           'Reply-To: no-reply@nightline-karlsruhe.de' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

?>
