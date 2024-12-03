<?php
error_log("Aktuel rute: $current_page", print_r($current_page));
// Undgå undefined variable-fejl ved at kontrollere, om $settings er sat
$site_title = $settings['site_title'] ?? '';
$contact_email = $settings['contact_email'] ?? '';
$opening_hours = $settings['opening_hours'] ?? '';
$about_content = $settings['about_content'] ?? ''; 
?>
<form action="admin_settings" method="post">
    <label for="site_title">Website Titel:</label>
    <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="contact_email">Kontakt Email:</label>
    <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="opening_hours">Åbningstider:</label>
    <input type="text" id="opening_hours" name="opening_hours" value="<?php echo htmlspecialchars($opening_hours ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="about_content">Om Os:</label>
    <textarea id="about_content" name="about_content" rows="5" required><?php echo htmlspecialchars($about_content ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

    <button type="submit">Gem Indstillinger</button>
</form>
