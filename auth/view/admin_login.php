<form method="POST" action="<?= BASE_URL ?>index.php?page=admin_login">
    <h2>Admin Login</h2>
    <?php if (isset($data['error'])): ?>
        <p class="error"><?= htmlspecialchars($data['error'], ENT_QUOTES) ?></p>
    <?php endif; ?>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Log ind</button>
</form>
