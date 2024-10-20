<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';

$page_id = $_GET['id'] ?? null;

if ($page_id) {
    $stmt = $db->prepare("SELECT * FROM pages WHERE id = :id");
    $stmt->bindParam(':id', $page_id, PDO::PARAM_INT);
    $stmt->execute();
    $pageData = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<p>Side ikke fundet.</p>";
    exit;
}
?>
<h1>Rediger Side</h1>
<form action="update_page.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($pageData['id']); ?>">

    <label for="title">Titel:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($pageData['title']); ?>" required>

    <label for="content">Indhold:</label>
    <textarea id="content" name="content" required><?php echo htmlspecialchars($pageData['content']); ?></textarea>

    <label for="css_file">CSS-fil:</label>
    <input type="text" id="css_file" name="css_file" value="<?php echo htmlspecialchars($pageData['css_file']); ?>">

    <button type="submit">Opdater Side</button>
</form>
