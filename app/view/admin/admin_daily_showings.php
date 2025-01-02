<body>
    <h1>Administrer Filmvisninger</h1>

    <!-- Liste over eksisterende visninger -->
    <table border="1">
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
                    <td><?= htmlspecialchars($showing['movie_title'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($showing['screen'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($showing['show_date'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($showing['show_time'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($showing['total_spots'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($showing['available_spots'], ENT_QUOTES) ?></td>
                    <td>
                        <form action="index.php?page=admin_showings&action=update&id=<?= $showing['id'] ?>" method="post">
                            <button type="submit">Rediger</button>
                        </form>
                        <form action="index.php?page=admin_showings&action=delete&id=<?= $showing['id'] ?>" method="post" onsubmit="return confirm('Er du sikker?')">
                            <button type="submit">Slet</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formular til at tilføje ny visning -->
    <h2>Tilføj Ny Visning</h2>
    <form action="index.php?page=admin_showings&action=create" method="post">
        <label for="movie_id">Film ID:</label>
        <input type="text" id="movie_id" name="movie_id" required>

        <label for="screen">Skærm:</label>
        <select id="screen" name="screen">
            <option value="Lille">Lille</option>
            <option value="Stor">Stor</option>
        </select>

        <label for="show_date">Dato:</label>
        <input type="date" id="show_date" name="show_date" required>

        <label for="show_time">Tid:</label>
        <input type="time" id="show_time" name="show_time" required>

        <label for="total_spots">Pladser (i alt):</label>
        <input type="number" id="total_spots" name="total_spots" required>

        <label for="available_spots">Ledige Pladser:</label>
        <input type="number" id="available_spots" name="available_spots" required>

        <button type="submit">Tilføj</button>
    </form>

    <h2>Opret Ny Filmvisning</h2>
<form action="index.php?page=admin_showings&action=create" method="post">
    <label for="movie_id">Film ID:</label>
    <input type="text" id="movie_id" name="movie_id" required>

    <label for="screen">Skærm:</label>
    <select id="screen" name="screen">
        <option value="Lille">Lille</option>
        <option value="Stor">Stor</option>
    </select>

    <label for="show_date">Dato:</label>
    <input type="date" id="show_date" name="show_date" required>

    <label for="show_time">Tid:</label>
    <input type="time" id="show_time" name="show_time" required>

    <label for="total_spots">Pladser (i alt):</label>
    <input type="number" id="total_spots" name="total_spots" required>

    <label for="available_spots">Ledige Pladser:</label>
    <input type="number" id="available_spots" name="available_spots" required>

    <button type="submit">Opret</button>
</form>

<h2>Opdater Filmvisning</h2>
<form action="index.php?page=admin_showings&action=update&id=<?= $showing['id'] ?>" method="post">
    <label for="movie_id">Film ID:</label>
    <input type="text" id="movie_id" name="movie_id" value="<?= htmlspecialchars($showing['movie_id'], ENT_QUOTES) ?>" required>

    <label for="screen">Skærm:</label>
    <select id="screen" name="screen">
        <option value="Lille" <?= $showing['screen'] === 'Lille' ? 'selected' : '' ?>>Lille</option>
        <option value="Stor" <?= $showing['screen'] === 'Stor' ? 'selected' : '' ?>>Stor</option>
    </select>

    <label for="show_date">Dato:</label>
    <input type="date" id="show_date" name="show_date" value="<?= htmlspecialchars($showing['show_date'], ENT_QUOTES) ?>" required>

    <label for="show_time">Tid:</label>
    <input type="time" id="show_time" name="show_time" value="<?= htmlspecialchars($showing['show_time'], ENT_QUOTES) ?>" required>

    <label for="total_spots">Pladser (i alt):</label>
    <input type="number" id="total_spots" name="total_spots" value="<?= htmlspecialchars($showing['total_spots'], ENT_QUOTES) ?>" required>

    <label for="available_spots">Ledige Pladser:</label>
    <input type="number" id="available_spots" name="available_spots" value="<?= htmlspecialchars($showing['available_spots'], ENT_QUOTES) ?>" required>

    <button type="submit">Opdater</button>
</form>

