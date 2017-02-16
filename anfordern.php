<?php

// send mail
$to      = 'karlsruhe@nightlines.eu';
$subject = '[NL] Telefondienst angefordert';
$message = 'Es wurde heute ein Telefondienst angefordert. Wer wird heute Abend da sein? -- Nightline Bot';
$headers = 'From: no-reply@nightline-karlsruhe.de' . "\r\n" .
           'Reply-To: no-reply@nightline-karlsruhe.de' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

?>
