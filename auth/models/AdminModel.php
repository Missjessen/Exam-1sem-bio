<?php
class AdminModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAdminByEmail($email) {
        return $this->read('employees', '*', ['email' => $email], true);
    }
}

