<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500</title>
</head>
<body>
    <h1>500 - Internal Server Error</h1>
    <?php if (isset($additionalData) && !empty($additionalData['message'])): ?>
        <p><?= htmlspecialchars($additionalData['message'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Der opstod en fejl på serveren. Prøv igen senere.</p>
    <?php endif; ?>
    <?php if (isset($additionalData['timestamp'])): ?>
        <p><em>Timestamp: <?= htmlspecialchars($additionalData['timestamp'], ENT_QUOTES, 'UTF-8') ?></em></p>
    <?php endif; ?>
    <a href="?page=homePage">Return to Homepage</a>
</body>
</html>
