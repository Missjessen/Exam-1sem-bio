<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/constants.php';
include 'includes/headeradmin.php';


// Forbind til databasen
$conn = new mysqli($db_host, DB_USER, DB_PASS, $db_dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Håndter opdatering af indstillinger via formularindsendelse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_title' => $_POST['site_title'],
        'contact_email' => $_POST['contact_email'],
        'opening_hours' => $_POST['opening_hours'],
        'about_content' => $_POST['about_content']
    ];
    
    updateSettings($conn, $settings);
}

// Hent eksisterende indstillinger for at udfylde formularen
$site_title = getSetting($conn, 'site_title');
$contact_email = getSetting($conn, 'contact_email');
$opening_hours = getSetting($conn, 'opening_hours');
$about_content = getSetting($conn, 'about_content');

$conn->close();
?>
<h1>Indstillinger</h1>
<p>Opdater website indstillinger som titel, kontaktinformation og farveskema.</p>
<form action="settings.php" method="post">
    <label for="site_title">Website Titel:</label>
    <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" required>

    <label for="contact_email">Kontakt Email:</label>
    <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>" required>

    <label for="opening_hours">Åbningstider:</label>
    <input type="text" id="opening_hours" name="opening_hours" value="<?php echo htmlspecialchars($opening_hours); ?>" required>

    <label for="about_content">Om Os:</label>
    <textarea id="about_content" name="about_content" rows="5" required><?php echo htmlspecialchars($about_content); ?></textarea>

    <button type="submit">Gem Indstillinger</button>
</form>




