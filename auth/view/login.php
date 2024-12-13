<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Adgangskode:</label>
        <input type="password" name="password" required>
        <label>Rolle:</label>
        <select name="role">
            <option value="user">Bruger</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Log ind</button>
        <?php if (isset($error)): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
