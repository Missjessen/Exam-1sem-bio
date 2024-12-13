<h1>Opret Profil</h1>
<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="index.php?page=register">
    <label for="name">Navn:</label>
    <input type="text" id="name" name="name" required placeholder="Indtast dit navn">
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="Indtast din email">
    
    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required placeholder="Indtast din adgangskode">
    
    <button type="submit">Opret Profil</button>
</form>
