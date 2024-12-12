<form method="POST" action="<?= htmlspecialchars(currentPageURL('login')) ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit" name="submit">Log ind</button>
</form>
