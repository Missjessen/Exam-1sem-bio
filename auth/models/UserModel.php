<?php 

class UserModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getUserByEmail($email) {
        return $this->read('customers', '*', ['email' => $email], true);
    }

    public function emailExists($email) {
        $user = $this->getUserByEmail($email);
        return !empty($user);
    }

    public function createUser($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->create('customers', [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }
}
