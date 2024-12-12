<?php
class CustomerModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    // Create a new customer
    public function createUser($name, $email, $password) {
        if ($this->emailExists($email)) {
            throw new Exception("Email already exists.");
        }

        $hashedPassword = $this->hashPassword($password);
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ];

        return $this->create('customers', $data);
    }

    // Get customer by email
    public function getCustomerByEmail($email) {
        return $this->read('customers', '*', ['email' => $email], true);
    }

    // Get customer by ID
    public function getCustomerById($id) {
        return $this->read('customers', '*', ['id' => $id], true);
    }

    // Check if an email already exists
    public function emailExists($email) {
        return $this->getCustomerByEmail($email) !== null;
    }

    // Update a customer's details
    public function updateCustomer($id, $data) {
        return $this->update('customers', $data, ['id' => $id]);
    }

    // Hash password securely
    private function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}

  

