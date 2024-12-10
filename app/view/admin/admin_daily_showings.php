<div class="container">
    <h2>Daglige Visninger</h2>

    <?php if (!empty($showings)): ?>
        <table>
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Dato</th>
                    <th>Tid</th>
                    <th>Skærm</th>
                    <th>Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($showings as $showing): ?>
                    <tr>
                        <td><?= htmlspecialchars($showing['movie_title']) ?></td>
                        <td><?= htmlspecialchars($showing['show_date']) ?></td>
                        <td><?= htmlspecialchars($showing['show_time']) ?></td>
                        <td><?= htmlspecialchars($showing['screen']) ?></td>
                        <td>
                            <a href="?page=admin_daily_showings&action=edit&showing_id=<?= $showing['id'] ?>">Rediger</a>
                            <a href="?page=admin_daily_showings&action=delete&showing_id=<?= $showing['id'] ?>" onclick="return confirm('Slet denne visning?')">Slet</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen visninger fundet.</p>
    <?php endif; ?>
</div>



    <!-- Form til at tilføje en ny visning -->
    <h2>Tilføj Visning</h2>
    <form method="POST" action="?page=admin_daily_showings&action=add">
    <label for="movie_id">Vælg film:</label>
    <select name="movie_id" id="movie_id" required>
        <?php foreach ($movies as $movie): ?>
            <option value="<?= htmlspecialchars($movie['id']) ?>"><?= htmlspecialchars($movie['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="showing_time">Tidspunkt:</label>
    <input type="datetime-local" name="showing_time" id="showing_time" required>

    <label for="scene">Scene:</label>
    <select name="scene" id="scene" required>
        <option value="Lille">Lille</option>
        <option value="Stor">Stor</option>
    </select>

    <button type="submit">Tilføj Visning</button>
</form>

</div>
