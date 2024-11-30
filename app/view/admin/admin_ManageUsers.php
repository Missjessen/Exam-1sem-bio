<h1>Administrer Kunder</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($pageData['editCustomer']['id'] ?? ''); ?>">
    <label>Navn:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($pageData['editCustomer']['name'] ?? ''); ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($pageData['editCustomer']['email'] ?? ''); ?>" required>
    <label>Telefon:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($pageData['editCustomer']['phone'] ?? ''); ?>" required>
    <button type="submit" name="add_or_update_customer">Gem Kunde</button>
</form>

<table>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Handling</th>
    </tr>
    <?php foreach ($pageData['customers'] as $customer): ?>
        <tr>
            <td><?= htmlspecialchars($customer['name']); ?></td>
            <td><?= htmlspecialchars($customer['email']); ?></td>
            <td><?= htmlspecialchars($customer['phone']); ?></td>
            <td>
                <a href="?edit_customer_id=<?= $customer['id']; ?>">Rediger</a>
                <a href="?delete_customer_id=<?= $customer['id']; ?>" onclick="return confirm('Er du sikker?');">Slet</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h1>Administrer Ansatte</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($pageData['editEmployee']['id'] ?? ''); ?>">
    <label>Navn:</label>
    <input type="text" name="employee_name" value="<?= htmlspecialchars($pageData['editEmployee']['name'] ?? ''); ?>" required>
    <label>Email:</label>
    <input type="email" name="employee_email" value="<?= htmlspecialchars($pageData['editEmployee']['email'] ?? ''); ?>" required>
    <label>Telefon:</label>
    <input type="text" name="employee_phone" value="<?= htmlspecialchars($pageData['editEmployee']['phone'] ?? ''); ?>" required>
    <label>Rolle:</label>
    <input type="text" name="employee_role" value="<?= htmlspecialchars($pageData['editEmployee']['role'] ?? ''); ?>" required>
    <label>Adresse:</label>
    <input type="text" name="employee_address" value="<?= htmlspecialchars($pageData['editEmployee']['address'] ?? ''); ?>" required>
    <button type="submit" name="add_or_update_employee">Gem Ansat</button>
</form>

<table>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Rolle</th>
        <th>Adresse</th>
        <th>Handling</th>
    </tr>
    <?php foreach ($pageData['employees'] as $employee): ?>
        <tr>
            <td><?= htmlspecialchars($employee['name']); ?></td>
            <td><?= htmlspecialchars($employee['email']); ?></td>
            <td><?= htmlspecialchars($employee['role']); ?></td>
            <td><?= htmlspecialchars($employee['address']); ?></td>
            <td>
                <a href="?edit_employee_id=<?= $employee['id']; ?>">Rediger</a>
                <a href="?delete_employee_id=<?= $employee['id']; ?>" onclick="return confirm('Er du sikker?');">Slet</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>