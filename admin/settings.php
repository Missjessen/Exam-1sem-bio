<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

include 'includes/headeradmin.php';
?>
<h1>Indstillinger</h1>
<p>Opdater website indstillinger som titel, kontaktinformation og farveskema.</p>

<form action="settings.php" method="post">
    <label for="site_title">Website Titel:</label>
    <input type="text" id="site_title" name="site_title" value="Drive-In Bio">
    
    <label for="contact_email">Kontakt E-mail:</label>
    <input type="email" id="contact_email" name="contact_email" value="kontakt@driveinbio.dk">
    
    <button type="submit">Gem Indstillinger</button>
</form>
<?php include 'includes/footer.php'; ?>
