<?php
// Antag at $customerBookings er hentet fra BookingController
if (!empty($customerBookings)): ?>
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
                        <form method="POST" action="?page=delete_booking">
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
