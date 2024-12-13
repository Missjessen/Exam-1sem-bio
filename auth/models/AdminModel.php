<?php
class AdminModel extends CrudBase {
    public function getAdminByEmail($email) {
        return $this->read('employees', '*', ['email' => $email], true);
    }
}
