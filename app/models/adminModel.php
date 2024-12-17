<?php
require_once dirname(__DIR__, 2) . '/init.php';


class AdminModel extends CrudBase {

    

    public function __construct() {
        parent::__construct(Database::getInstance()->getConnection());
    }


// settttings.php
    public function updateSettings(array $settings): void {
        parent::updateSettings($settings);
    }
     
    
   // Metode i AdminModel til at hente indstillinger fra databasen
// Hent eksisterende indstillinger
public function getSettings(array $keys): array {
    $settings = [];
    foreach ($keys as $key) {
        $result = $this->read('site_settings', '*', ['setting_key' => $key], true);
        $settings[$key] = $result['setting_value'] ?? ''; // Standardværdi, hvis ikke fundet
    }
    return $settings;
}


                            /* manages user section */


        // Customers methods
            // CRUD-metoder for kunder
    public function createCustomer($data) {
        $stmt = $this->db->prepare("INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        return $stmt->execute();
    }

    public function updateCustomer($id, $data) {
        $stmt = $this->db->prepare("UPDATE customers SET name = :name, email = :email, phone = :phone WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        return $stmt->execute();
    }

    public function deleteCustomer($id) {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllCustomers() {
        $stmt = $this->db->query("SELECT * FROM customers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CRUD-metoder for ansatte
    public function createEmployee($data) {
        $stmt = $this->db->prepare("INSERT INTO employees (name, email, phone, role, address) VALUES (:name, :email, :phone, :role, :address)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':address', $data['address']);
        return $stmt->execute();
    }

    public function updateEmployee($id, $data) {
        $stmt = $this->db->prepare("UPDATE employees SET name = :name, email = :email, phone = :phone, role = :role, address = :address WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':address', $data['address']);
        return $stmt->execute();
    }

    public function deleteEmployee($id) {
        $stmt = $this->db->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllEmployees() {
        $stmt = $this->db->query("SELECT * FROM employees");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
// Metode til at hente en admin ved email
public function getAdminByEmail($email) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM employees WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("Ingen admin fundet for email: $email");
        }

        return $result;
    } catch (Exception $e) {
        error_log("Fejl i getAdminByEmail: " . $e->getMessage());
        return null;
    }
}



   
   
   


