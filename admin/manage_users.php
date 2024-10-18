<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

include 'includes/headeradmin.php';
?>
<h1>Administrer Brugere</h1>
<p>Tilføj nye brugere eller opdater eksisterende konti.</p>

<table>
    <tr>
        <th>Brugernavn</th>
        <th>Rolle</th>
        <th>Handlinger</th>
    </tr>
    <!-- Eksempel på rækker -->
    <tr>
        <td>admin</td>
        <td>Administrator</td>
        <td>
            <a href="edit_user.php?id=1">Rediger</a> |
            <a href="delete_user.php?id=1">Slet</a>
        </td>
    </tr>
</table>

