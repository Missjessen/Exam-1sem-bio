<?php
require_once __DIR__ . '/init.php';

$authController = new AuthController(Database::getInstance()->getConnection());
$error = $authController->login();
?>
<body>
    <h2>Log ind</h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Adgangskode:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Log ind</button>
    </form>
</body>
</html>
