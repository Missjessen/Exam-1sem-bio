<?php

class MovieFrontendController {
    private $model;
    private $recipientEmail = "nsj@cruise-nights-cinema.dk";

    public function __construct(MovieFrontendModel $model) {
        $this->model = $model;
    }

    public function showMovieDetails($uuid) {
        $movie = $this->model->getMovieByUuid($uuid);
        if (!$movie) {
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../view/errors/404.php';
            exit();
        }

        require_once __DIR__ . '/../view/user/movieDetails.php';
    }

    public function handleContactForm() {
        // Tjek om formen er indsendt
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hent og rens data
            $name = htmlspecialchars(trim($_POST['name']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars(trim($_POST['subject']));
            $message = htmlspecialchars(trim($_POST['message']));

            // Valider input
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                return "Alle felter skal udfyldes.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "Ugyldig email-adresse.";
            }

            // Opsætning af email-indhold
            $body = "Navn: $name\nEmail: $email\n\nBesked:\n$message";

            // Send email
            if (mail($this->recipientEmail, $subject, $body, "From: $email\n")) {
                return "Tak for din besked! Vi vender tilbage hurtigst muligt.";
            } else {
                return "Der opstod en fejl ved afsendelse af email. Prøv igen senere.";
            }
        }

        return null; // Ingen handling, hvis metoden ikke er POST
    }
}
