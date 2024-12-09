<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="dashboard-cards">
        <!-- Daily Showings Card -->
        <div class="dashboard-card">
            <h2>Daily Showings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Genres</th>
                        <th>Showing Time</th>
                        <th>Poster</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dailyShowings as $showing): ?>
                        <tr>
                            <td><?= htmlspecialchars($showing['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($showing['genres'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($showing['showing_time'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($showing['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Poster">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- News Movies Card -->
        <div class="dashboard-card">
            <h2>News Movies</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Release Date</th>
                        <th>Poster</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newsMovies as $movie): ?>
                        <tr>
                            <td><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($movie['release_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($movie['poster'], ENT_QUOTES, 'UTF-8') ?>" alt="Poster">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
