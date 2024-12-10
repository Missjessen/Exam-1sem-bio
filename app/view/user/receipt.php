<?php
$success = $_GET['booking_success'] ?? 'false'; // Tjek om booking_success er sat
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Kvittering</title>
    <link rel="stylesheet" href="styles.css"> <!-- Tilføj evt. dine egne styles -->
</head>
<body>
    <h1>Booking Kvittering</h1>
    <?php if ($success === 'true'): ?>
        <p>Din booking blev oprettet succesfuldt! Tak for din reservation.</p>
    <?php else: ?>
        <p>Der opstod en fejl under oprettelsen af din booking. Prøv igen.</p>
    <?php endif; ?>
</body>
</html>
