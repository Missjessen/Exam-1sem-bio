<?php
require_once dirname(__DIR__, 3) . '/init.php';

$to = "missejessen87@gmail.com";
$subject = "Test Email";
$message = "Dette er en testmail for at tjekke mail()-funktionen.";
$headers = "From: test@yourdomain.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sendt!";
} else {
    echo "Mail blev ikke sendt.";
}
?>