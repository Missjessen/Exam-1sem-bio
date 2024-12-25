<body>
    <h1>404 - Page Not Found</h1>
    <?php if (!empty($additionalData['message'])): ?>
        <p><?= htmlspecialchars($additionalData['message'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Vi kunne ikke finde den side, du leder efter.</p>
    <?php endif; ?>
    <a href="?page=homePage">Return to Homepage</a>
</body>