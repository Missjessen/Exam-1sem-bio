<?php
$action = $_GET['action'] ?? 'read';
$section = $_GET['section'] ?? null;
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Opret eller opdater side
    if ($action === 'create') {
        // Opret ny post baseret på sektionen
    } elseif ($action === 'update' && $id) {
        // Opdater post baseret på sektionen og id
    }
} elseif ($action === 'delete' && $id) {
    // Slet post baseret på sektionen og id
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($action); ?> Sektion</title>
</head>
<body>
    <h1><?php echo ucfirst($action); ?> Sektion: <?php echo ucfirst($section); ?></h1>
    <!-- Formular vises kun til opret og opdatering -->
    <?php if ($action === 'create' || $action === 'update'): ?>
        <form action="index.php?page=admin_crud&action=<?php echo $action; ?>&section=<?php echo $section; ?><?php echo $id ? '&id=' . $id : ''; ?>" method="post">
            <label for="title">Titel:</label>
            <input type="text" name="title" required>

            <!-- Resten af formularfelterne her -->
            
            <button type="submit">Gem</button>
        </form>
    <?php endif; ?>
</body>
</html>
