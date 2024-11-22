<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
?>

<form action="/admin/showtimes/create" method="POST">
    <label for="movie">Film:</label>
    <select name="movie_id" id="movie">
        <!-- Fyldes dynamisk med film fra databasen -->
    </select>

    <label for="date">Dato:</label>
    <input type="date" name="date" id="date" required>

    <label for="time">Tidspunkt:</label>
    <input type="time" name="time" id="time" required>

    <button type="submit">Opret Showtime</button>
</form>
