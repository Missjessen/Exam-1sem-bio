<?php



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
public function createCustomer($data) {
    $stmt = $this->db->prepare("INSERT INTO customers (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    return $stmt->execute();
}


public function updateCustomer($id, $data) {
    try {
        $stmt = $this->db->prepare("UPDATE customers SET name = :name, email = :email WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Fejl under opdatering af kunde: " . $e->getMessage());
        return false;
    }
}



public function deleteCustomer($id) {
    try {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Fejl ved sletning af kunde: " . $e->getMessage());
        return false;
    }
}


public function getAllCustomers() {
    $stmt = $this->db->query("SELECT id, name, email FROM customers");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


   public function getCustomerById($id) {
    return $this->read('customers', 'id, name, email', ['id' => $id], true);
}

    // CRUD-metoder for ansatte
    public function createEmployee($data) {
        $stmt = $this->db->prepare("INSERT INTO employees (name, email, phone, role, address, password) VALUES (:name, :email, :phone, :role, :address, :password)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':password', $data['password']);
        return $stmt->execute();
    }

  public function updateEmployee($id, $data) {
    return $this->update('employees', $data, ['id' => $id]);
}

    public function deleteEmployee($id) {
    return $this->delete('employees', ['id' => $id]);
}

   public function getAllEmployees($limit = 10, $offset = 0) {
    return $this->read('employees', 'id, name, email, phone, role, address', [], false, $limit, $offset);
}

   public function getEmployeeById($id) {
    return $this->read('employees', 'id, name, email, phone, role, address', ['id' => $id], true);
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



}
   
   


