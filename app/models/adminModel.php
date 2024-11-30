<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

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
        public function getAllCustomers() {
            return $this->read('customers');
        }
    
        public function getCustomerById($id) {
            return $this->read('customers', '*', ['id' => $id], true);
        }
    
        public function createCustomer($data) {
            return $this->create('customers', $data);
        }
    
        public function updateCustomer($id, $data) {
            return $this->update('customers', $data, ['id' => $id]);
        }
    
        public function deleteCustomer($id) {
            return $this->delete('customers', ['id' => $id]);
        }
    
        public function getAllEmployees() {
            return $this->read('employees');
        }
    
        public function getEmployeeById($id) {
            return $this->read('employees', '*', ['id' => $id], true);
        }
    
        public function createEmployee($data) {
            return $this->create('employees', $data);
        }
    
        public function updateEmployee($id, $data) {
            return $this->update('employees', $data, ['id' => $id]);
        }
    
        public function deleteEmployee($id) {
            return $this->delete('employees', ['id' => $id]);
        }
    }



   
   
   


