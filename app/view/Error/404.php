<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 50px;
            color: #ff0000;
        }
        p {
            font-size: 18px;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <?php if (!empty($additionalData['message'])): ?>
        <p><?= htmlspecialchars($additionalData['message'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Vi kunne ikke finde den side, du leder efter.</p>
    <?php endif; ?>
    <?php if (!empty($additionalData['timestamp'])): ?>
        <p><em>Timestamp: <?= htmlspecialchars($additionalData['timestamp'], ENT_QUOTES, 'UTF-8') ?></em></p>
    <?php endif; ?>
    <a href="?page=homePage">Return to Homepage</a>
</body>
</html>
