
<h1>Admin Bookinger</h1>
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
                <td><?= $booking['admin_booking_id'] ?></td>
                <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                <td><?= htmlspecialchars($booking['spot_number']) ?></td>
                <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                <td><?= htmlspecialchars($booking['price']) ?></td>
                <td><?= htmlspecialchars($booking['status']) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="admin_booking_id" value="<?= $booking['admin_booking_id'] ?>">
                        <button type="submit" name="action" value="edit">Rediger</button>
                        <button type="submit" name="action" value="delete" onclick="return confirm('Er du sikker?')">Slet</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
