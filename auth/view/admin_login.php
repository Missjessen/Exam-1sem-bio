<h1>Admin Login</h1>
<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="index.php?page=admin_login">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="Indtast din email">
    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required placeholder="Indtast din adgangskode">
    <button type="submit">Log ind</button>
</form>

