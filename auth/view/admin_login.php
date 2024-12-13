<h1>Admin Login</h1>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Log ind</button>
</form>
