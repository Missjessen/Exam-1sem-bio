<?php

// Inkluder databaseforbindelse og autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

class AdminController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminModel($db);
    }

    // Customers methods
    public function getAllCustomers() {
        return $this->model->getAllCustomers();
    }

    public function getCustomerById($id) {
        return $this->model->getCustomerById($id);
    }

    public function saveCustomer($data) {
        if (!empty($data['id'])) {
            return $this->model->updateCustomer($data['id'], $data);
        } else {
            return $this->model->createCustomer($data);
        }
    }

    public function deleteCustomer($id) {
        return $this->model->deleteCustomer($id);
    }

    // Employees methods
    public function getAllEmployees() {
        return $this->model->getAllEmployees();
    }

    public function getEmployeeById($id) {
        return $this->model->getEmployeeById($id);
    }

    public function saveEmployee($data) {
        if (!empty($data['id'])) {
            return $this->model->updateEmployee($data['id'], $data);
        } else {
            return $this->model->createEmployee($data);
        }
    }

    public function deleteEmployee($id) {
        return $this->model->deleteEmployee($id);
    }

 // Method to retrieve specific settings
    public function getSettings($keys)
    {
        $settings = [];
        foreach ($keys as $key) {
            $result = $this->model->getItem('site_settings', ['setting_key' => $key]);
            if ($result && isset($result['setting_value'])) {
                $settings[$key] = $result['setting_value'];
            } else {
                $settings[$key] = ''; // Use empty string if no value exists
            }
        }
        return $settings;
    }

    // Method to update settings
    public function updateSettings($settings)
    {
        foreach ($settings as $key => $value) {
            $result = $this->model->updateItem('site_settings', ['setting_value' => $value], ['setting_key' => $key]);
            if (!$result) {
                echo "Fejl ved opdatering af indstilling: $key<br>";
            }
        }
    }

    // Method to create a movie
    public function createMovie($data)
    {
        return $this->model->createItem('Movies', $data);
    }

    // Method to update a movie
    public function updateMovie($id, $data)
    {
        return $this->model->updateItem('Movies', $data, ['movie_id' => $id]);
    }

    // Method to delete a movie
    public function deleteMovie($id)
    {
        return $this->model->deleteItem('Movies', ['movie_id' => $id]);
    }

    // Method to retrieve all movies
    public function getAllMovies()
    {
        return $this->model->getAllMovies();
    }

    
}
?>
