<h1>Din Profil</h1>
<p>Velkommen, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

<!-- Besked, hvis der er en -->
<?php if (!empty($_SESSION['message'])): ?>
    <div class="message">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message']); // Ryd beskeden efter visning ?>
<?php endif; ?>

<!-- Bookings -->
<?php if (!empty($customerBookings)): ?>
    <h1>Mine Bookinger</h1>
    <table>
        <thead>
            <tr>
                <th>Film</th>
                <th>Dato</th>
                <th>Tid</th>
                <th>Plads</th>
                <th>Pris</th>
                <th>Handling</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customerBookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                    <td><?= htmlspecialchars($booking['show_date']) ?></td>
                    <td><?= htmlspecialchars($booking['show_time']) ?></td>
                    <td><?= htmlspecialchars($booking['spot_id']) ?></td>
                    <td><?= htmlspecialchars($booking['price']) ?> DKK</td>
                    <td>
                        <form method="POST" action="<?= htmlspecialchars(BASE_URL . 'delete_booking.php') ?>">
                            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id']) ?>">
                            <button type="submit">Slet</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Du har ingen bookinger endnu.</p>
<?php endif; ?>

<!-- Log ud -->
<p><a href="<?= htmlspecialchars(BASE_URL . 'index.php?page=logout') ?>">Log ud</a></p>


<style>.message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    margin: 15px 0;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

.user-menu, .auth-links {
    margin: 15px 0;
}</style>