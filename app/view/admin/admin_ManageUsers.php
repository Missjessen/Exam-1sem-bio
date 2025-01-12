<h1>Administrer Kunder</h1>
<!-- Formular til at oprette/redigere kunder -->
<form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=admin_ManageUsers') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['editCustomer']['id'] ?? '') ?>">
    <label>Navn:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['editCustomer']['name'] ?? '') ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['editCustomer']['email'] ?? '') ?>" required>
    <button type="submit" name="action" value="update_customer">Gem Kunde</button>
</form>

<!-- Tabel over eksisterende kunder -->
<table>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Handling</th>
    </tr>
    <?php foreach ($data['customers'] as $customer): ?>
    <tr>
        <td><?= htmlspecialchars($customer['name']); ?></td>
        <td><?= htmlspecialchars($customer['email']); ?></td>
        <td>
            <a href="?page=admin_ManageUsers&edit_customer_id=<?= $customer['id']; ?>">Rediger</a>
            <a href="?page=admin_ManageUsers&delete_customer_id=<?= $customer['id']; ?>" onclick="return confirm('Er du sikker på, at du vil slette denne kunde?');">Slet</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h1>Administrer Ansatte</h1>
<!-- Formular til at oprette/redigere ansatte -->
<form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=admin_ManageUsers') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['editEmployee']['id'] ?? '') ?>">
    <label>Navn:</label>
    <input type="text" name="employee_name" value="<?= htmlspecialchars($data['editEmployee']['name'] ?? '') ?>" required>
    <label>Email:</label>
    <input type="email" name="employee_email" value="<?= htmlspecialchars($data['editEmployee']['email'] ?? '') ?>" required>
    <label>Telefon:</label>
    <input type="text" name="employee_phone" value="<?= htmlspecialchars($data['editEmployee']['phone'] ?? '') ?>" required>
    <label>Rolle:</label>
    <input type="text" name="employee_role" value="<?= htmlspecialchars($data['editEmployee']['role'] ?? '') ?>" required>
    <label>Adresse:</label>
    <input type="text" name="employee_address" value="<?= htmlspecialchars($data['editEmployee']['address'] ?? '') ?>" required>
    <button type="submit" name="action" value="update_employee">Gem Ansat</button>
</form>

<!-- Tabel over eksisterende ansatte -->
<table>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Rolle</th>
        <th>Adresse</th>
        <th>Handling</th>
    </tr>
    <?php foreach ($data['employees'] as $employee): ?>
    <tr>
        <td><?= htmlspecialchars($employee['name']); ?></td>
        <td><?= htmlspecialchars($employee['email']); ?></td>
        <td><?= htmlspecialchars($employee['phone']); ?></td>
        <td><?= htmlspecialchars($employee['role']); ?></td>
        <td><?= htmlspecialchars($employee['address']); ?></td>
        <td>
            <a href="?page=admin_ManageUsers&edit_employee_id=<?= $employee['id']; ?>">Rediger</a>
            <a href="?page=admin_ManageUsers&delete_employee_id=<?= $employee['id']; ?>" onclick="return confirm('Er du sikker på, at du vil slette denne ansat?');">Slet</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
