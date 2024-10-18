<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

$pageTitle = "Tilføj Ny Side";
$cssFile = "/admin/css/admin_styles.css";
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/includes/headeradmin.php';
?>

<h1>Tilføj Ny Side</h1>

<form action="insert_page.php" method="post">
    <label for="title">Sidens Titel:</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Indhold:</label>
    <textarea id="content" name="content" required></textarea>

    <label for="css_file">CSS-fil:</label>
    <input type="text" id="css_file" name="css_file" placeholder="For eksempel 'homePage.css'">

    <label for="template_file">Skabelonfil:</label>
    <input type="text" id="template_file" name="template_file" placeholder="For eksempel 'homePage.php'">

    <button type="submit">Tilføj Side</button>
</form>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php'; ?>
