<?php
$to = "recipient@example.com";
$subject = "Test Email";
$message = "Dette er en testmail.";
$headers = "From: your_email@yourdomain.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail blev sendt!";
} else {
    echo "Mail blev ikke sendt.";
}
?>