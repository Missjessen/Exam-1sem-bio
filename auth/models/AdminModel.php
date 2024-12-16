<?php
class UserModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }
  // Hent admin-bruger baseret på email
  public function getAdminByEmail($email) {
    return $this->read('employees', '*', ['email' => $email], true);
}

// Tjek om en email allerede eksisterer
public function emailExists($email) {
    $admin = $this->getAdminByEmail($email);
    return $admin !== null;
}

// Opret en ny admin-bruger (hvis nødvendigt)
public function createAdmin($name, $email, $password, $role = 'admin', $phone = '', $address = '') {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role,
        'phone' => $phone,
        'address' => $address,
    ];
    return $this->create('employees', $data);
}
}

