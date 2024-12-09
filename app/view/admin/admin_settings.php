<?php
error_log("Aktuel rute: $current_page", print_r($current_page));
// Undgå undefined variable-fejl ved at kontrollere, om $settings er sat
$site_title = $settings['site_title'] ?? '';
$contact_email = $settings['contact_email'] ?? '';
$opening_hours = $settings['opening_hours'] ?? '';
$about_content = $settings['about_content'] ?? ''; 
?>
<form action="?page=admin_settings" method="post">
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


<style>body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.admin-container {
    width: 80%;
    max-width: 800px;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    text-align: center;
}

p {
    color: #666;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

input:focus, textarea:focus {
    outline: none;
    border-color: #007bff;
}

.btn-submit {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #0056b3;
}
</style>