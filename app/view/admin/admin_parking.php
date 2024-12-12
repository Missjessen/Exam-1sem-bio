<?php
require_once dirname(__DIR__, 3) . '/init.php';

// Modtagerens email (udskift med din egen emailadresse)
$to = "Missjessen87@gmail.com";

// Emne for testmailen
$subject = "Test Email via PHP mail()";

// Beskedens indhold
$message = "Dette er en testmail sendt via PHP's mail() funktion.";

// Afsenderens emailadresse
$from = "Missjessen87@gmail.com"; // Skift til en gyldig emailadresse på dit domæne
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
