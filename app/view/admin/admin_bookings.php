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
                    <a href="<?= BASE_URL ?>index.php?page=admin_edit_booking&order_number=<?= $booking['order_number'] ?>">Rediger</a>
                    <a href="<?= BASE_URL ?>index.php?page=admin_delete_booking&order_number=<?= $booking['order_number'] ?>" onclick="return confirm('Er du sikker på, at du vil slette denne booking?')">Slet</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<form method="POST" action="<?= BASE_URL ?>index.php?page=admin_update_booking">
    <input type="hidden" name="order_number" value="<?= htmlspecialchars($booking['order_number']) ?>">
    <label for="spots_reserved">Antal Spots:</label>
    <input type="number" name="spots_reserved" value="<?= htmlspecialchars($booking['spots_reserved']) ?>" required>

    <label for="status">Status:</label>
    <select name="status">
        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Afventer</option>
        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Bekræftet</option>
        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Annulleret</option>
    </select>

    <button type="submit">Opdater Booking</button>
</form>
