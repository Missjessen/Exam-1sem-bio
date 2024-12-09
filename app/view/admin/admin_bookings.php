<div class="admin-bookings">
    <h1>Administrer Bookinger</h1>

    <?php if (!empty($bookings)): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Film</th>
                    <th>Kunde</th>
                    <th>Spot</th>
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
                        <td><?= htmlspecialchars($booking['price']) ?></td>
                        <td>
                            <a href="?page=admin_booking&action=edit&id=<?= htmlspecialchars($booking['booking_id']) ?>">Rediger</a>
                            <a href="?page=admin_booking&action=delete&id=<?= htmlspecialchars($booking['booking_id']) ?>" onclick="return confirm('Er du sikker?')">Slet</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen bookinger fundet.</p>
    <?php endif; ?>
</div>
