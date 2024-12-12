<?php
class CustomerModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    // Opret en ny kunde
    public function createCustomer($name, $email, $hashedPassword) {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ];
        return $this->create('customers', $data);
    }

    // Hent kunde efter email
    public function getCustomerByEmail($email) {
        return $this->read('customers', '*', ['email' => $email], true);
    }

    // Hent kunde efter ID
    public function getCustomerById($id) {
        return $this->read('customers', '*', ['id' => $id], true);
    }
}
