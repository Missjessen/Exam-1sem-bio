<?php
require_once __DIR__ . '/init.php';


// Modtagerens email (udskift med din egen emailadresse)
$to = "nsj@cruise-nights-cinema.dk"; // Skift til den emailadresse, du vil teste med

// Emne for testmailen
$subject = "Test Email via PHP mail()";

// Beskedens indhold
$message = "Dette er en testmail sendt via PHP's mail() funktion.";

// Afsenderens emailadresse (skift til en gyldig emailadresse på dit domæne)
$from = "nanjes01@365essv.dk"; 
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
