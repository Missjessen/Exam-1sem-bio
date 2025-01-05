<div class="register-form">
    <h1>Opret Konto</h1>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST" action="index.php?page=register">
        <label for="name">Navn:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Adgangskode:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Registrer</button>
    </form>

    <p>Har du allerede en konto? <a href="index.php?page=login">Log ind her</a></p>
</div>
