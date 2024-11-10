<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

class AdminModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

        // Customers methods
        public function getAllCustomers($limit = 50, $offset = 0) {
            return $this->read('customers', '*', [], false, $limit, $offset);
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

        // Employees methods
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


       // Method to retrieve a specific item from a table
       public function getItem($table, $where)
       {
           return $this->read($table, '*', $where, true);
       }
   
       // Method to retrieve all items from a table
       public function getAllItems($table)
       {
           return $this->read($table);
       }
   
       // Method to create a new item in a table
       public function createItem($table, $data)
       {
           return $this->create($table, $data);
       }
   
       // Method to update a specific item in a table
       public function updateItem($table, $data, $where)
       {
           return $this->update($table, $data, $where);
       }
   
       // Method to delete a specific item from a table
       public function deleteItem($table, $where)
       {
           return $this->delete($table, $where);
       }
   
       // Method to retrieve a specific movie
       public function getMovie($movieUUID)
       {
           return $this->read('Movies', '*', ['id' => $movieUUID], true); // Opdateret til at bruge 'id' (UUID)
       }
   
       public function getAllMovies() {
        // Eksempel på SQL forespørgsel
        $stmt = $this->db->query("SELECT * FROM movies");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   }


?>