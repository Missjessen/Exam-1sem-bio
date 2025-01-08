<?php if (!empty($currentBookings)): ?>
    <h3>Aktuelle Bookinger</h3>
    <table>
        <thead>
            <tr>
                <th>Film</th>
                <th>Dato</th>
                <th>Tid</th>
                <th>Antal Pladser</th>
                <th>Total Pris</th>
                <th>Status</th>
                <th>Handling</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($currentBookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                    <td><?= htmlspecialchars($booking['show_date']) ?></td>
                    <td><?= htmlspecialchars($booking['show_time']) ?></td>
                    <td><?= htmlspecialchars($booking['spots_reserved']) ?></td>
                    <td><?= htmlspecialchars($booking['total_price']) ?> DKK</td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <a href="index.php?page=booking_receipt&order_number=<?= htmlspecialchars($booking['order_number']) ?>" class="btn">
                            Se Kvittering
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Du har ingen aktuelle bookinger.</p>
<?php endif; ?>

<?php if (!empty($pastBookings)): ?>
    <h3>Tidligere Bookinger</h3>
    <table>
        <thead>
            <tr>
                <th>Film</th>
                <th>Dato</th>
                <th>Tid</th>
                <th>Antal Pladser</th>
                <th>Total Pris</th>
                <th>Status</th>
                <th>Handling</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pastBookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                    <td><?= htmlspecialchars($booking['show_date']) ?></td>
                    <td><?= htmlspecialchars($booking['show_time']) ?></td>
                    <td><?= htmlspecialchars($booking['spots_reserved']) ?></td>
                    <td><?= htmlspecialchars($booking['total_price']) ?> DKK</td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <a href="index.php?page=booking_receipt&order_number=<?= htmlspecialchars($booking['order_number']) ?>" class="btn">
                            Se Kvittering
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Du har ingen tidligere bookinger.</p>
<?php endif; ?>
