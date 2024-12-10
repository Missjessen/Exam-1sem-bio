<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="dashboard-cards">

        <!-- Daily Showings Card -->
        <div class="dashboard-card">
            <h2>Daily Showings</h2>
            <?php if (!empty($dailyShowings)): ?>
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
                                <td><?= htmlspecialchars($showing['title']) ?></td>
                                <td><?= htmlspecialchars($showing['genres'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($showing['showing_time']) ?></td>
                                <td><img src="<?= htmlspecialchars($showing['image']) ?>" alt="Poster"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Ingen daglige forestillinger fundet.</p>
            <?php endif; ?>
        </div>

        <!-- News Movies Card -->
        <div class="dashboard-card">
            <h2>News Movies</h2>
            <?php if (!empty($newsMovies)): ?>
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
                                <td><?= htmlspecialchars($movie['title']) ?></td>
                                <td><?= htmlspecialchars($movie['release_date']) ?></td>
                                <td><img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Poster"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Ingen nyheder fundet.</p>
            <?php endif; ?>
        </div>

    </div>
</div>
