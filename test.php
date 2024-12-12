<?php
require_once __DIR__ . '/init.php';



$mymail = "nsj@cruise-nights-cinema.dk"; // Din modtager-email
$email = $_POST['email']; // Brugerens email
$subject = $_POST['subject']; // Emne fra formularen
$message = $_POST['message']; // Besked fra formularen

// Regex til validering af email
$regexp = "/^[^0-9][A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";

// Valider email og tjek for tomme felter
if (!preg_match($regexp, $email)) {
    echo "Ugyldig email-adresse.";
} elseif (empty($email) || empty($message) || empty($subject)) {
    echo "Alle felter skal udfyldes.";
} elseif (isset($_POST['submit'])) {
    // Email-indhold
    $body = "Besked:\n$message\n\nAfsenderens email: $email";

    // Header med fast afsender og brugerens email i Reply-To
    $headers = "From: noreply@yourpage.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send mail
    if (mail($mymail, $subject, $body, $headers)) {
        echo "Tak for din besked! Vi vender tilbage hurtigst muligt.";
    } else {
        echo "Der opstod en fejl ved afsendelse af din besked.";
    }
}
?>
