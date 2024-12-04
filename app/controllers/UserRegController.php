<?php
require_once 'init.php';
class UserRegController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
    }

    public function registerUser($postData) {
        $username = Security::sanitizeString($postData['username']);
        $password = Security::sanitizeString($postData['password']);
        $email = filter_var($postData['email'], FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new Exception("Ugyldig email-adresse.");
        }

        if (strlen($password) < 8) {
            throw new Exception("Adgangskoden skal være mindst 8 tegn.");
        }

        if ($this->userModel->checkUserExists($username)) {
            throw new Exception("Brugernavn er allerede taget.");
        }

        if ($this->userModel->createUser($username, $password, $email)) {
            return "Bruger oprettet! Du kan nu logge ind.";
        } else {
            throw new Exception("Kunne ikke oprette bruger. Prøv igen.");
        }
    }

    public function loginUser($postData) {
        $username = Security::sanitizeString($postData['username']);
        $password = Security::sanitizeString($postData['password']);

        $user = $this->userModel->authenticateUser($username, $password);

        if ($user) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time(); // Session timeout starter her
            return "Velkommen, " . $user['username'] . "!";
        } else {
            throw new Exception("Forkert brugernavn eller adgangskode.");
        }
    }
}
