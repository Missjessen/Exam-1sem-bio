<div class="auth-form">
    <h1>Opret Profil</h1>
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=register') ?>">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Adgangskode:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Bekræft Adgangskode:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit">Opret Profil</button>
    </form>
    <p>Har du allerede en konto? <a href="<?= htmlspecialchars(BASE_URL . 'index.php?page=login') ?>">Log ind</a></p>
</div>
