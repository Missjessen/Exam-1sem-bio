<?php
require_once __DIR__ . '/init.php';
// Modtagerens email (udskift med din egen emailadresse)
$message = "Dette er en testmail sendt via PHP's mail() funktion.";

// Afsenderens emailadresse
$from = "Missjessen87@"; // Skift til en gyldig emailadresse på dit domæne
$headers = "From: $from\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Forsøg at sende mailen
if (mail($to, $subject, $message, $headers)) {
    echo "Mail blev sendt til $to!";
} else {
    echo "Mail blev ikke sendt.";
}
?>
