<?php if (isset($_SESSION['success_message'])): ?>
    <p class="success"><?= $_SESSION['success_message'] ?></p>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <p class="error"><?= $_SESSION['error_message'] ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>


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
                    <a href="<?= BASE_URL ?>index.php?page=admin_edit_booking&order_number=<?= htmlspecialchars($booking['order_number']) ?>">Rediger</a>
                    <a href="<?= BASE_URL ?>index.php?page=admin_delete_booking&order_number=<?= htmlspecialchars($booking['order_number']) ?>" onclick="return confirm('Er du sikker på, at du vil slette denne booking?')">Slet</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Update Booking Form -->
<h3>Opdater Booking</h3>
<form method="POST" action="<?= BASE_URL ?>index.php?page=admin_update_booking">
    <label for="order_number">Ordrenummer:</label>
    <select name="order_number" required>
        <option value="" disabled selected>Vælg en booking</option>
        <?php foreach ($bookings as $booking): ?>
            <option value="<?= htmlspecialchars($booking['order_number']) ?>"><?= htmlspecialchars($booking['order_number']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="spots_reserved">Antal Spots:</label>
    <input type="number" name="spots_reserved" required>

    <label for="status">Status:</label>
    <select name="status" required>
        <option value="pending">Afventer</option>
        <option value="confirmed">Bekræftet</option>
        <option value="cancelled">Annulleret</option>
    </select>

    <button type="submit">Opdater Booking</button>
</form>
