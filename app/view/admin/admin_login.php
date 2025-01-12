<form method="POST" action="<?= BASE_URL ?>index.php?page=admin_login">
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>
    <label for="email">Admin Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Log ind</button>
</form>
