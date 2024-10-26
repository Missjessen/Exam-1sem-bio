<?php
// Inkluder nødvendige filer
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

class AdminModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

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
       public function getMovie($movieId)
       {
           return $this->read('Movies', '*', ['movie_id' => $movieId], true);
       }
   
       // Method to retrieve all movies
       public function getAllMovies()
       {
           return $this->read('Movies');
       }
   }


?>
