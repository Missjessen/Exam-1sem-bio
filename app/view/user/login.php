<h1>Login</h1>
<form method="POST" action="<?= BASE_URL ?>index.php?page=login">
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Log ind</button>
</form>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
