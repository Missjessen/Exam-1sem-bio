<h1>Administrer Kunder</h1>

<h1>Administrer Kunder</h1>
<form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=admin_ManageUsers') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['editCustomer']['id'] ?? ''); ?>">
    <label>Navn:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['editCustomer']['name'] ?? ''); ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['editCustomer']['email'] ?? ''); ?>" required>
    <label>Adgangskode:</label>
    <input type="password" name="password">
    <button type="submit" name="add_or_update_customer">Gem Kunde</button>
</form>


<table>
<?php if (!empty($data['customers'])): ?>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Handling</th>
        <th>password</th>
    </tr>
    <?php foreach ($data['customers'] as $customer): ?>
        <tr>
            <td><?= htmlspecialchars($customer['name']); ?></td>
            <td><?= htmlspecialchars($customer['email']); ?></td>
            <td><?= htmlspecialchars($customer['password']); ?></td>
            <td>
            <a href="?page=admin_ManageUsers&edit_customer_id=<?= $customer['id']; ?>">Rediger</a>
                <a href="?page=admin_ManageUsers&delete_customer_id=<?= $customer['id']; ?>" onclick="return confirm('Er du sikker?');">Slet</a>

            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <p>Ingen kunder fundet.</p>
<?php endif; ?>
</table>

<h1>Administrer Ansatte</h1>
<form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=admin_ManageUsers') ?>">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['editEmployee']['id'] ?? ''); ?>">
    <label>Navn:</label>
    <input type="text" name="employee_name" value="<?= htmlspecialchars($data['editEmployee']['name'] ?? ''); ?>" required>
    <label>Email:</label>
    <input type="email" name="employee_email" value="<?= htmlspecialchars($data['editEmployee']['email'] ?? ''); ?>" required>
    <label>Telefon:</label>
    <input type="text" name="employee_phone" value="<?= htmlspecialchars($data['editEmployee']['phone'] ?? ''); ?>" required>
    <label>Rolle:</label>
    <input type="text" name="employee_role" value="<?= htmlspecialchars($data['editEmployee']['role'] ?? ''); ?>" required>
    <label>Adresse:</label>
    <input type="text" name="employee_address" value="<?= htmlspecialchars($data['editEmployee']['address'] ?? ''); ?>" required>
    <label>Adgangskode:</label>
    <input type="password" name="password">
    <button type="submit" name="add_or_update_employee">Gem Ansat</button>
</form>

<table>
<?php if (!empty($data['employees'])): ?>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Rolle</th>
        <th>Adresse</th>
        <th>Handling</th>
        <th>password</th>
    </tr>
    <?php foreach ($data['employees'] as $employee): ?>
        <tr>
            <td><?= htmlspecialchars($employee['name']); ?></td>
            <td><?= htmlspecialchars($employee['email']); ?></td>
            <td><?= htmlspecialchars($employee['role']); ?></td>
            <td><?= htmlspecialchars($employee['address']); ?></td>
            <td><?= htmlspecialchars($employee['password']); ?></td>
            <td>
            <a href="?page=admin_ManageUsers&edit_employee_id=<?= $employee['id']; ?>">Rediger</a>
                <a href="?page=admin_ManageUsers&delete_employee_id=<?= $employee['id']; ?>" onclick="return confirm('Er du sikker?');">Slet</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <p>Ingen kunder fundet.</p>
<?php endif; ?>


</table>

<style>   body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 30px 40px; /* Mere margin i siderne */
    }

    h1 {
        color: #444;
    }

    form {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
        max-width: 600px;
    }

    form label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }

    form input, form textarea {
        width: calc(100% - 20px);
        padding: 8px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    form textarea {
        resize: vertical;
    }

    form button {
        background-color: #5cb85c;
        color: white;
        padding: 8px 18px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    form button:hover {
        background-color: #4cae4c;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
        background: #fff;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    a {
        color: #5bc0de;
        text-decoration: none;
        margin-right: 8px;
        font-size: 14px;
    }

    a:hover {
        text-decoration: underline;
    }

    /* styling for employee Cards */
    .employee-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 25px;
        justify-content: center;
    }

    .employee-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 220px; /* Mindre bredde */
        text-align: center;
    }

    .employee-image {
        width: 100%;
        height: 150px; /* Mindre højde */
        object-fit: cover;
    }

    .employee-details {
        padding: 12px;
    }

    .employee-details h3 {
        margin: 8px 0;
        font-size: 16px;
    }

    .employee-details p {
        margin: 4px 0;
        font-size: 14px;
    }

    .employee-details a {
        color: #5bc0de;
        text-decoration: none;
        font-size: 14px;
    }

    .employee-details a:hover {
        text-decoration: underline;
    }</style>