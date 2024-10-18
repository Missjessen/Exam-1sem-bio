<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

 include 'includes/headeradmin.php'; 

?>
<h1>Administrer Indhold</h1>
<p>Tilføj nyt indhold eller rediger eksisterende indhold.</p>

<table>
    <tr>
        <th>Titel</th>
        <th>Handlinger</th>
    </tr>
    <!-- Eksempel på rækker -->
    <tr>
        <td>Introduktionstekst</td>
        <td>
            <a href="edit_content.php?id=1">Rediger</a> |
            <a href="delete_content.php?id=1">Slet</a>
        </td>
    </tr>
</table>

