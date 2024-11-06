<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

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
    // Hvis `id` (UUID) ikke er inkluderet i dataene, generér et nyt
    if (!isset($data['id'])) {
        $data['id'] = $this->generateUUID(); // Generér en UUID
    }

    // Generér slug baseret på title, hvis slug ikke er angivet
    if (!isset($data['slug']) || empty($data['slug'])) {
        $data['slug'] = $this->generateSlug($data['title']);
    }
    return $this->model->create('movies', $data);
}

// UUID-generator metode
private function generateUUID()
{
    return bin2hex(random_bytes(16)); // Generér en 36-tegns UUID
}

    // Method to update a movie
    public function updateMovie($id, $data)
{
    return $this->model->updateItem('Movies', $data, ['id' => $id]); // Ændret til 'id'
}


    // Method to delete a movie
    public function deleteMovie($id)
{
    return $this->model->deleteItem('Movies', ['id' => $id]); // Ændret til 'id'
}


    // Method to retrieve all movies
    public function getAllMovies()
    {
        return $this->model->getAllMovies();
    }

    public function generateSlug($title)
{
    // Fjern specialtegn og lav alle bogstaver små
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
    return trim($slug, '-'); // Trim eventuelle ekstra bindestreger fra begyndelsen eller slutningen
}
    
}
?>
