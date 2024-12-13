<?php
class UserModel extends CrudBase {
    public function createUser($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $data = ['name' => $name, 'email' => $email, 'password' => $hashedPassword];
        return $this->create('customers', $data);
    }

    public function getUserByEmail($email) {
        return $this->read('customers', '*', ['email' => $email], true);
    }

    public function emailExists($email) {
        return !empty($this->getUserByEmail($email));
    }
}
