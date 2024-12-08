<div class="container">
    <h2>Daglige Visninger</h2>

    <!-- Feedback fra URL-parametre -->
    <?php if (isset($_GET['success'])): ?>
        <p class="success">Visning tilføjet med succes!</p>
    <?php elseif (isset($_GET['deleted'])): ?>
        <p class="success">Visning slettet med succes!</p>
    <?php endif; ?>

    <!-- Link til at tilføje ny visning -->
    <a href="?page=admin_daily_showings&action=add">Tilføj ny visning</a>

    <!-- Liste over visninger -->
    <?php if (!empty($showings)): ?>
        <table>
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Tidspunkt</th>
                    <th>Scene</th>
                    <th>Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($showings as $showing): ?>
                    <tr>
                        <td><?= htmlspecialchars($showing['movie_title']) ?></td>
                        <td><?= htmlspecialchars($showing['showing_time']) ?></td>
                        <td><?= htmlspecialchars($showing['scene']) ?></td>
                        <td>
                            <!-- Link til redigering af visning -->
                            <a href="?page=admin_daily_showings&action=edit&showing_id=<?= htmlspecialchars($showing['id']) ?>">Rediger</a> |
                            <!-- Link til sletning af visning -->
                            <a href="?page=admin_daily_showings&action=delete&showing_id=<?= htmlspecialchars($showing['id']) ?>" onclick="return confirm('Er du sikker på, at du vil slette denne visning?')">Slet</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen visninger fundet.</p>
    <?php endif; ?>

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
