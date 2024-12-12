<?php
require_once dirname(__DIR__, 2) . '/init.php';

$to = "missejessen87@gmail.com";
$subject = "Test Email";
$message = "Dette er en testmail for at tjekke mail()-funktionen.";
$headers = "From: test@cruise-nights-cinema.dk";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sendt!";
} else {
    echo "Mail blev ikke sendt.";
}
?>