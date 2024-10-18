<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

$pageTitle = "Administrer Sider";
$cssFile = "/admin/css/admin_styles.css";
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/includes/headeradmin.php';

// Hent alle sider fra databasen
$stmt = $db->query("SELECT * FROM pages");
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Administrer Sider</h1>

<table>
    <tr>
        <th>Titel</th>
        <th>Handlinger</th>
    </tr>
    <?php foreach ($pages as $page): ?>
    <tr>
        <td><?php echo htmlspecialchars($page['title']); ?></td>
        <td>
            <a href="edit_page.php?id=<?php echo $page['id']; ?>">Rediger</a> |
            <a href="delete_page.php?id=<?php echo $page['id']; ?>" onclick="return confirm('Er du sikker på, at du vil slette denne side?');">Slet</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="add_page.php">Tilføj Ny Side</a>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php'; ?>
