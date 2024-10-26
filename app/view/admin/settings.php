<?php
session_start();

// Inkluder databaseforbindelse og autoloader
require_once '/Applications/XAMPP/xamppfiles/htdocs/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php'; // Brug en absolut sti


// Brug AdminController til at håndtere logik
$controller = new AdminController($db); // Videregiv $db til konstruktøren


// Håndter opdatering af indstillinger via formularindsendelse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Saml værdier fra POST-requesten
    $settings = [
        'site_title' => $_POST['site_title'],
        'contact_email' => $_POST['contact_email'],
        'opening_hours' => $_POST['opening_hours'],
        'about_content' => $_POST['about_content']
    ];

    // Opdater indstillinger via controlleren
    $controller->updateSettings($settings);

    // For at vise en opdateret version uden refresh-problemer, kan du opdatere $settings her:
    $settings = $controller->getSettings(['site_title', 'contact_email', 'opening_hours', 'about_content']);
}

// Brug controlleren til at hente indstillingerne, hvis de ikke blev opdateret
$site_title = $settings['site_title'] ?? '';
$contact_email = $settings['contact_email'] ?? '';
$opening_hours = $settings['opening_hours'] ?? '';
$about_content = $settings['about_content'] ?? '';
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
