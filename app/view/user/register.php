<form method="POST" action="<?= htmlspecialchars(currentPageURL('register')) ?>">
    <label for="username">Brugernavn:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit" name="submit">Opret profil</button>
</form>
