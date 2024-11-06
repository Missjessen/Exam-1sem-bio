<?php
require_once 'includes/connection.php';
require_once 'oop/Security.php';

Security::startSession();

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$username = Security::sanitizeString($_POST['username']);
    //$password = $_POST['password'];

    //$sql = "SELECT * FROM users WHERE username = :username AND is_validated = 1";
    //$stmt = $db->prepare($sql);
    //$stmt->bindParam(':username', $username);
    //$stmt->execute();
    //$user = $stmt->fetch(PDO::FETCH_ASSOC);

    //if ($user) {
        // Udskriv hash fra databasen og brug input password til debugging (fjern disse linjer efter test)
       // echo "Hashed password from DB: " . $user['password'] . "<br>";
        //echo "Input password: " . $password . "<br>";
        
        // Kontrollér, om password matcher det hash, der er gemt i databasen
        //if (password_verify($password, $user['password'])) {
           // $_SESSION['user_id'] = $user['id'];
           // echo "Login succes!";
           // header("Location: user_dashboard.php");
           // exit();
        //} else {
           // echo "Adgangskoden matcher ikke.";
       // }
    //} else {
        //echo "Brugeren findes ikke eller er ikke valideret.";
    //}
//}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label for="username">Brugernavn:</label>
        <input type="text" name="username" required>

        <label for="password">Adgangskode:</label>
        <input type="password" name="password" required>

        <button type="submit">Log ind</button>
    </form>
</body>
</html>
