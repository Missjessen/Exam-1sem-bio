<table>
    <thead>
        <tr>
            <th>Ordrenummer</th>
            <th>Kunde</th>
            <th>Film</th>
            <th>Dato</th>
            <th>Tid</th>
            <th>Antal Spots</th>
            <th>Status</th>
            <th>Handlinger</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= htmlspecialchars($booking['order_number']) ?></td>
                <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                <td><?= htmlspecialchars($booking['movie_title']) ?></td>
                <td><?= htmlspecialchars($booking['show_date']) ?></td>
                <td><?= htmlspecialchars($booking['show_time']) ?></td>
                <td><?= htmlspecialchars($booking['spots_reserved']) ?></td>
                <td><?= htmlspecialchars($booking['status']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="order_number" value="<?= htmlspecialchars($booking['order_number']) ?>">
                        <label>
                            Spots:
                            <input type="number" name="spots_reserved" value="<?= htmlspecialchars($booking['spots_reserved']) ?>" required>
                        </label>
                        <label>
                            Status:
                            <select name="status">
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Afventer</option>
                                <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Bekræftet</option>
                                <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Annulleret</option>
                            </select>
                        </label>
                        <button type="submit">Opdater</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="order_number" value="<?= htmlspecialchars($booking['order_number']) ?>">
                        <button type="submit" onclick="return confirm('Er du sikker på, at du vil slette denne booking?')">Slet</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
