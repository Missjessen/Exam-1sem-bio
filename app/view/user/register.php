<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<h1>Registrering</h1>
<form method="POST" action="index.php?page=register">
    <label for="name">Navn:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Opret Bruger</button>
</form>



