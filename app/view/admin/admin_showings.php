<div class="admin-container">
    <h1 class="admin-title">Administrer Filmvisninger</h1>

    <table class="admin-table">
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
                        <form method="get" action="index.php" class="inline-form">
                            <input type="hidden" name="page" value="admin_showings">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="showing_id" value="<?= $showing['id'] ?>">
                            <button class="btn btn-edit" type="submit">Rediger</button>
                        </form>
                        <form method="get" action="index.php" class="inline-form">
                            <input type="hidden" name="page" value="admin_showings">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="showing_id" value="<?= $showing['id'] ?>">
                            <button class="btn btn-delete" type="submit">Slet</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="admin-form-container">
    <form method="post" action="index.php?page=admin_showings" class="admin-form">
    <input type="hidden" name="action" value="<?= isset($editingShowing) ? 'update' : 'create' ?>">
<?php if (isset($editingShowing)): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($editingShowing['id'], ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>

    <!-- Film -->
    <div class="form-group">
        <label for="movie_id">Film:</label>
        <select name="movie_id" id="movie_id" required>
            <option value="">Vælg en film</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?= htmlspecialchars($movie['id'], ENT_QUOTES, 'UTF-8') ?>" <?= isset($editingShowing) && $editingShowing['movie_id'] === $movie['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Skærm -->
    <div class="form-group">
        <label for="screen">Skærm:</label>
        <select name="screen" id="screen" required>
            <option value="Lille" <?= isset($editingShowing) && $editingShowing['screen'] === 'Lille' ? 'selected' : '' ?>>Lille</option>
            <option value="Stor" <?= isset($editingShowing) && $editingShowing['screen'] === 'Stor' ? 'selected' : '' ?>>Stor</option>
        </select>
    </div>

    <!-- Dato -->
    <div class="form-group">
        <label for="show_date">Dato:</label>
        <input type="date" name="show_date" id="show_date" value="<?= htmlspecialchars($editingShowing['show_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <!-- Tid -->
    <div class="form-group">
        <label for="show_time">Tid:</label>
        <input type="time" name="show_time" id="show_time" value="<?= htmlspecialchars($editingShowing['show_time'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-submit">
            <?= isset($editingShowing) ? 'Opdater' : 'Tilføj' ?>
        </button>
    </div>
</form>

</div>

<style>.admin-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.admin-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    text-align: left;
}

.admin-table th,
.admin-table td {
    padding: 10px 15px;
    border: 1px solid #ddd;
}

.admin-table th {
    background-color: #333;
    color: #fff;
    text-align: center;
}

.admin-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.admin-table tbody tr:nth-child(odd) {
    background-color: #fff;
}

.admin-table tbody tr:hover {
    background-color: #f1f1f1;
}

.inline-form {
    display: inline-block;
    margin-right: 5px;
}

.btn {
    display: inline-block;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-edit {
    background-color: #5cb85c;
}

.btn-edit:hover {
    background-color: #4cae4c;
}

.btn-delete {
    background-color: #d9534f;
}

.btn-delete:hover {
    background-color: #c9302c;
}

.btn-submit {
    background-color: #0275d8;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #025aa5;
}

.admin-form-container {
    margin-top: 20px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-form .form-group {
    margin-bottom: 15px;
}

.admin-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.admin-form input,
.admin-form select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.admin-form button {
    display: block;
    width: 100%;
    padding: 10px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    background-color: #0275d8;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.admin-form button:hover {
    background-color: #025aa5;
}
</style>
