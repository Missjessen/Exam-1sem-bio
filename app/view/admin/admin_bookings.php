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
<h2>Rediger Booking</h2>
<form method="POST" action="<?= BASE_URL ?>index.php?page=admin_update_booking">
    <input type="hidden" name="order_number" value="<?= htmlspecialchars($booking['order_number']) ?>">
    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($booking['customer_id']) ?>">
    <input type="hidden" name="showing_id" value="<?= htmlspecialchars($booking['showing_id']) ?>">

    <label for="customer_name">Kunde:</label>
    <input type="text" id="customer_name" name="customer_name" value="<?= htmlspecialchars($booking['customer_name']) ?>" readonly>

    <label for="movie_title">Film:</label>
    <input type="text" id="movie_title" name="movie_title" value="<?= htmlspecialchars($booking['movie_title']) ?>" readonly>

    <label for="show_date">Dato:</label>
    <input type="date" id="show_date" name="show_date" value="<?= htmlspecialchars($booking['show_date']) ?>" readonly>

    <label for="show_time">Tid:</label>
    <input type="time" id="show_time" name="show_time" value="<?= htmlspecialchars($booking['show_time']) ?>" readonly>

    <label for="spots_reserved">Antal Spots:</label>
    <input type="number" id="spots_reserved" name="spots_reserved" value="<?= htmlspecialchars($booking['spots_reserved']) ?>" required>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Afventer</option>
        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Bekræftet</option>
        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Annulleret</option>
    </select>

    <button type="submit">Opdater Booking</button>
</form>
