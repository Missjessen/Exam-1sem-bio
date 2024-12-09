<div class="admin-booking">
    <h1>Booking Administration</h1>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Film</th>
                <th>Kunde</th>
                <th>Plads</th>
                <th>Dato</th>
                <th>Pris</th>
                <th>Handlinger</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                    <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                    <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                    <td><?= htmlspecialchars($booking['spot_number']) ?></td>
                    <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                    <td><?= htmlspecialchars($booking['price']) ?> DKK</td>
                    <td>
                        <a href="?page=admin_bookings&action=edit&id=<?= $booking['booking_id'] ?>">Rediger</a>
                        <a href="?page=admin_bookings&action=delete&id=<?= $booking['booking_id'] ?>" onclick="return confirm('Er du sikker på, at du vil slette denne booking?')">Slet</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
