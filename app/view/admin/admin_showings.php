<h1>Administrer Filmvisninger</h1>

<table>
    <thead>
        <tr>
            <th>Film</th>
            <th>Skærm</th>
            <th>Dato</th>
            <th>Tid</th>
            <th>Pladser (i alt)</th>
            <th>Ledige Pladser</th>
            <th>Handling</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($showings as $showing): ?>
        <tr>
            <td><?= htmlspecialchars($showing['movie_title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($showing['screen'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($showing['show_date'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($showing['show_time'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($showing['total_spots'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($showing['available_spots'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
            <form method="get" action="index.php">
    <input type="hidden" name="page" value="admin_showings">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="showing_id" value="<?= $showing['id'] ?>">
    <button type="submit">Rediger</button>
</form>
<form method="get" action="index.php">
    <input type="hidden" name="page" value="admin_showings">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="showing_id" value="<?= $showing['id'] ?>">
    <button type="submit">Slet</button>
</form>

            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2><?= isset($editingShowing) ? "Rediger Visning" : "Tilføj Ny Visning" ?></h2>
<form method="post" action="index.php?page=admin_showings">
    <input type="hidden" name="action" value="<?= isset($editingShowing) ? 'update' : 'create' ?>">
    <input type="hidden" name="id" value="<?= $editingShowing['id'] ?? '' ?>">

    <label for="movie_id">Film:</label>
    <select name="movie_id" id="movie_id" required>
        <option value="">Vælg en film</option>
        <?php foreach ($movies as $movie): ?>
        <option value="<?= $movie['id'] ?>" <?= isset($editingShowing) && $editingShowing['movie_id'] === $movie['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label for="screen">Skærm:</label>
    <select name="screen" id="screen" required>
        <option value="Lille" <?= isset($editingShowing) && $editingShowing['screen'] === 'Lille' ? 'selected' : '' ?>>Lille</option>
        <option value="Stor" <?= isset($editingShowing) && $editingShowing['screen'] === 'Stor' ? 'selected' : '' ?>>Stor</option>
    </select>

    <label for="show_date">Dato:</label>
    <input type="date" name="show_date" id="show_date" value="<?= $editingShowing['show_date'] ?? '' ?>" required>

    <label for="show_time">Tid:</label>
    <input type="time" name="show_time" id="show_time" value="<?= $editingShowing['show_time'] ?? '' ?>" required>

    <button type="submit"><?= isset($editingShowing) ? 'Opdater' : 'Tilføj' ?></button>


</form>

