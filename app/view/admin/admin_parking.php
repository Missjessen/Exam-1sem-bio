<?php
$to = "missejssen87@gmail.com";
$subject = "Test Email";
$message = "Dette er en testmail.";
$headers = "From: missjessen87@cruise-nights-cinema.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail blev sendt!";
} else {
    echo "Mail blev ikke .";
}


?>