<h1>Admin Bookinger</h1>

<?php if (!empty($bookings)): ?>
<table>
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Kunde</th>
            <th>Film</th>
            <th>Spot</th>
            <th>Dato</th>
            <th>Pris</th>
            <th>Status</th>
            <th>Handlinger</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                <td><?= htmlspecialchars($booking['spot_number']) ?></td>
                <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                <td><?= htmlspecialchars($booking['price']) ?></td>
                <td><?= htmlspecialchars($booking['status'] ?? 'Ikke angivet') ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id']) ?>">
                        <button type="submit" name="action" value="edit">Rediger</button>
                        <button type="submit" name="action" value="delete" onclick="return confirm('Er du sikker?')">Slet</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Ingen bookinger fundet.</p>
<?php endif; ?>
