<?php
require_once 'includes/connection.php';
require_once 'oop/Security.php';

Security::startSession();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, username, email FROM users WHERE is_validated = 0";
$stmt = $db->query($sql);
$pending_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = Security::validateInt($_POST['user_id']);
    if ($user_id) {
        $sql = "UPDATE users SET is_validated = 1 WHERE id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        header("Location: validate_users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Valider Bruger</title>
</head>
<body>
    <h2>Valider Brugere</h2>
    <table>
        <tr>
            <th>Brugernavn</th>
            <th>Email</th>
            <th>Handling</th>
        </tr>
        <?php foreach ($pending_users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit">Godkend</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
