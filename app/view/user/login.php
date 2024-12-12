<div class="auth-form">
    <h1>Log ind</h1>
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=login') ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Adgangskode:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Log ind</button>
    </form>
    <p>Har du ikke en konto? <a href="<?= htmlspecialchars(BASE_URL . 'index.php?page=register') ?>">Opret en profil</a></p>
</div>
