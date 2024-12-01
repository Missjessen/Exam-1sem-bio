<table>
    <thead>
        <tr>
            <th>Film</th>
            <th>Dato</th>
            <th>Tid</th>
            <th>Skærm</th>
            <th>Tilgængelige pladser</th>
            <th>Handlinger</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($showings as $showing): ?>
            <tr>
                <td><?= htmlspecialchars($showing['movie_title']) ?></td>
                <td><?= htmlspecialchars($showing['show_date']) ?></td>
                <td><?= htmlspecialchars($showing['show_time']) ?></td>
                <td><?= htmlspecialchars($showing['screen']) ?></td>
                <td><?= htmlspecialchars($showing['available_spots']) ?></td>
                <td>
                    <a href="?edit_showing=<?= $showing['id'] ?>">Rediger</a> |
                    <a href="?delete_showing=<?= $showing['id'] ?>" onclick="return confirm('Er du sikker?')">Slet</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form method="POST" action="">
    <label for="movie_id">Film:</label>
    <select name="movie_id" id="movie_id">
        <?php foreach ($movies as $movie): ?>
            <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="show_date">Dato:</label>
    <input type="date" name="show_date" id="show_date" required>

    <label for="show_time">Tid:</label>
    <input type="time" name="show_time" id="show_time" required>

    <label for="screen">Skærm:</label>
    <select name="screen" id="screen">
        <option value="small">Lille</option>
        <option value="large">Stor</option>
    </select>

    <label for="repeat_pattern">Gentagelse:</label>
    <select name="repeat_pattern" id="repeat_pattern">
        <option value="none">Ingen</option>
        <option value="daily">Dagligt</option>
        <option value="weekly">Ugentligt</option>
    </select>

    <label for="repeat_until">Gentag indtil (valgfrit):</label>
    <input type="date" name="repeat_until" id="repeat_until">

    <label for="total_spots">Antal pladser:</label>
    <input type="number" name="total_spots" id="total_spots" required>

    <button type="submit">Gem visning</button>
</form>
