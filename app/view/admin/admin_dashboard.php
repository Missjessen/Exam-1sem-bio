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

<style>
    /* Generel styling */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f4f4f4;
    color: #333;
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  
    border-radius: 8px;
   
}

/* Card layout */
.dashboard-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.dashboard-card {
    flex: 1 1 calc(50% - 20px); /* To kort ved siden af hinanden med mellemrum */
    background-color: #1a1a1a;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
    padding: 20px;
}

.dashboard-card h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #dbd8d4;
    border-bottom: 2px solid #f39c12;
    padding-bottom: 5px;
}

/* Tabeller i kort */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background-color: #1a1a1a;
    border: 1px solid #444;
    border-radius: 8px;
    overflow: hidden;
}

thead th {
    background-color: #f39c12;
    color: #fff;
    padding: 10px;
    text-align: left;
}

tbody tr:nth-child(even) {
    background-color: #2c2c2c;
}

tbody tr:nth-child(odd) {
    background-color: #1a1a1a;
}

tbody tr:hover {
    background-color: #444;
}

td, th {
    padding: 10px;
    text-align: left;
    vertical-align: middle;
    border-bottom: 1px solid #444;
    color: #f6f6f6;
}

table img {
    width: 50px;
    height: auto;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

/* Responsiv styling */
@media (max-width: 768px) {
    .dashboard-cards {
        flex-direction: column;
    }

    .dashboard-card {
        flex: 1 1 100%;
    }

    table img {
        width: 70px;
    }
}

</style>