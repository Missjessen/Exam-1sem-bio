<h1>Registrering</h1>
<form method="POST" action="<?= BASE_URL ?>index.php?page=register">
    <label for="name">Navn:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Registrer</button>
</form>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>



