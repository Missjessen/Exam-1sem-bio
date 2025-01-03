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
        <h2><?= isset($editingShowing) ? "Rediger Visning" : "Tilføj Ny Visning" ?></h2>
        <form method="post" action="index.php?page=admin_showings" class="admin-form">
            <div class="form-group">
                <label for="movie_id">Film:</label>
                <select name="movie_id" id="movie_id" required>
                    <option value="">Vælg en film</option>
                    <?php foreach ($movies as $movie): ?>
                        <option value="<?= $movie['id'] ?>" <?= isset($editingShowing) && $editingShowing['movie_id'] === $movie['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="screen">Skærm:</label>
                <select name="screen" id="screen" required>
                    <option value="Lille" <?= isset($editingShowing) && $editingShowing['screen'] === 'Lille' ? 'selected' : '' ?>>Lille</option>
                    <option value="Stor" <?= isset($editingShowing) && $editingShowing['screen'] === 'Stor' ? 'selected' : '' ?>>Stor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="show_date">Dato:</label>
                <input type="date" name="show_date" id="show_date" value="<?= $editingShowing['show_date'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="show_time">Tid:</label>
                <input type="time" name="show_time" id="show_time" value="<?= $editingShowing['show_time'] ?? '' ?>" required>
            </div>

            <input type="hidden" name="action" value="<?= isset($editingShowing) ? 'update' : 'create' ?>">
            <input type="hidden" name="id" value="<?= $editingShowing['id'] ?? '' ?>">

            <div class="form-group">
                <button type="submit" class="btn btn-submit">
                    <?= isset($editingShowing) ? 'Opdater' : 'Tilføj' ?>
                </button>
            </div>
        </form>
    </div>
</div>


<style>
    .admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 16px;
    text-align: left;
    background-color: #fff;
}

.admin-table th, .admin-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.admin-table th {
    background-color: #f4f4f4;
    color: #555;
}

.inline-form {
    display: inline-block;
}

.btn {
    padding: 8px 12px;
    font-size: 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-edit {
    background-color: #007bff;
    color: white;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-submit {
    background-color: #28a745;
    color: white;
    width: 100%;
    padding: 10px;
}

.admin-form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-form .form-group {
    margin-bottom: 15px;
}

.admin-form label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
    color: #333;
}

.admin-form input, .admin-form select {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
}

</style>