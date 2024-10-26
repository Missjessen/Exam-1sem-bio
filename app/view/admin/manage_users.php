<?php
session_start();
require_once '/Applications/XAMPP/xamppfiles/htdocs/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

// Instantiate necessary classes
$adminController = new AdminController($db);
$customers = $adminController->getAllCustomers(); // Retrieve all customers from the controller
$employees = $adminController->getAllEmployees(); // Retrieve all employees from the controller

// Handling add, edit, and delete actions for customers and employees
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_or_update_customer'])) {
        $data = [
            'id' => $_POST['id'] ?? null,
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
        ];
        $adminController->saveCustomer($data);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['add_or_update_employee'])) {
        $data = [
            'id' => $_POST['id'] ?? null,
            'name' => $_POST['employee_name'],
            'email' => $_POST['employee_email'],
            'phone' => $_POST['employee_phone'],
            'role' => $_POST['employee_role'],
            'address' => $_POST['employee_address'],
        ];
        $adminController->saveEmployee($data);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
} elseif (isset($_GET['delete_customer_id'])) {
    $adminController->deleteCustomer($_GET['delete_customer_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} elseif (isset($_GET['delete_employee_id'])) {
    $adminController->deleteEmployee($_GET['delete_employee_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Retrieve customer or employee data if editing
$editCustomer = isset($_GET['edit_customer_id']) ? $adminController->getCustomerById($_GET['edit_customer_id']) : null;
$editEmployee = isset($_GET['edit_employee_id']) ? $adminController->getEmployeeById($_GET['edit_employee_id']) : null;

?>

<!-- HTML for Customers Section -->
<h1>Administrer Kunder</h1>
<p>Tilføj nye kunder eller opdater eksisterende konti.</p>

<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $editCustomer['id'] ?? ''; ?>">
    <label for="name">Navn:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($editCustomer['name'] ?? ''); ?>" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($editCustomer['email'] ?? ''); ?>" required>
    <br>
    <label for="phone">Telefon:</label>
    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($editCustomer['phone'] ?? ''); ?>" required>
    <br>
    <button type="submit" name="add_or_update_customer"><?php echo isset($editCustomer) ? 'Opdater Kunde' : 'Tilføj Kunde'; ?></button>
</form>

<table>
    <tr>
        <th>Navn</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Handlinger</th>
    </tr>
    <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?php echo htmlspecialchars($customer['name']); ?></td>
            <td><?php echo htmlspecialchars($customer['email']); ?></td>
            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
            <td>
                <a href="?edit_customer_id=<?php echo $customer['id']; ?>">Rediger</a> |
                <a href="?delete_customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Er du sikker på, at du vil slette denne kunde?');">Slet</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- HTML for Employees Section (Cards) -->
<h1>Administrer Ansatte</h1>
<div class="employee-cards-container">
    <?php foreach ($employees as $employee): ?>
        <div class="employee-card">
            <img src="<?php echo htmlspecialchars($employee['image_path']); ?>" alt="Billede af <?php echo htmlspecialchars($employee['name']); ?>" class="employee-image">
            <div class="employee-details">
                <h3><?php echo htmlspecialchars($employee['name']); ?></h3>
                <p>Email: <?php echo htmlspecialchars($employee['email']); ?></p>
                <p>Telefon: <?php echo htmlspecialchars($employee['phone']); ?></p>
                <p>Rolle: <?php echo htmlspecialchars($employee['role']); ?></p>
                <p>Adresse: <?php echo htmlspecialchars($employee['address']); ?></p>
                <a href="?edit_employee_id=<?php echo $employee['id']; ?>">Rediger</a> |
                <a href="?delete_employee_id=<?php echo $employee['id']; ?>" onclick="return confirm('Er du sikker på, at du vil slette denne ansat?');">Slet</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- CSS for Employee Cards -->
<style>
    .employee-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .employee-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 250px;
        text-align: center;
    }

    .employee-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .employee-details {
        padding: 15px;
    }

    .employee-details h3 {
        margin: 10px 0;
    }

    .employee-details p {
        margin: 5px 0;
    }

    .employee-details a {
        color: #5bc0de;
        text-decoration: none;
    }

    .employee-details a:hover {
        text-decoration: underline;
    }
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 20px;
}

h1 {
    color: #444;
}

form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

form input {
    width: calc(100% - 22px);
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form button {
    background-color: #5cb85c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #4cae4c;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f2f2f2;
}

a {
    color: #5bc0de;
    text-decoration: none;
    margin-right: 10px;
}

a:hover {
    text-decoration: underline;
}
</style>
