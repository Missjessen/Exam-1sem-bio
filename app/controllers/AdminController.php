<?php
require_once 'init.php';

class AdminController {
    private $model;

    public function __construct(AdminModel $model) {
        $this->model = $model;
    }

    
  /*   // Metode til at hente indstillinger
    public function getSettings(array $keys): array {
        return $this->model->getSettings($keys);
    } */
    public function handleSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hent data fra POST og send til model for opdatering
            $updatedSettings = [
                'site_title' => $_POST['site_title'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'opening_hours' => $_POST['opening_hours'] ?? '',
                'about_content' => $_POST['about_content'] ?? '',
            ];
    
            $this->model->updateSettings($updatedSettings);
    
            // Rediriger for at undgå gentagne POST-forespørgsler (PRG Pattern)
            header("Location: ?page=admin_settings");
            exit;
        }
    
        // Hent eksisterende indstillinger fra databasen
        $keys = ['site_title', 'contact_email', 'opening_hours', 'about_content'];
        return $this->model->getSettings($keys);
    }
    

     public function getSettings($keys)
    {
        $settings = [];
        foreach ($keys as $key) {
            $result = $this->model->getItem('site_settings', ['setting_key' => $key]);
            if ($result && isset($result['setting_value'])) {
                $settings[$key] = $result['setting_value'];
            } else {
                $settings[$key] = ''; // Brug en tom streng, hvis der ikke findes en værdi
            }
        }
        return $settings;
    } 


                      /* manege user  */


    // Customers methods
     // Håndter alle POST- og GET-anmodninger for kunder og ansatte
     public function handleCustomerAndEmployeeSubmission($postData, $getData) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tilføj eller opdater kunder
            if (isset($postData['add_or_update_customer'])) {
                $this->saveCustomer([
                    'id' => $postData['id'] ?? null,
                    'name' => $postData['name'],
                    'email' => $postData['email'],
                    'phone' => $postData['phone'],
                ]);
            }

            // Tilføj eller opdater ansatte
            if (isset($postData['add_or_update_employee'])) {
                $this->saveEmployee([
                    'id' => $postData['id'] ?? null,
                    'name' => $postData['employee_name'],
                    'email' => $postData['employee_email'],
                    'phone' => $postData['employee_phone'],
                    'role' => $postData['employee_role'],
                    'address' => $postData['employee_address'],
                ]);
            }
        }

        // Håndter GET-anmodninger for sletning
        if (isset($getData['delete_customer_id'])) {
            $this->deleteCustomer($getData['delete_customer_id']);
        }

        if (isset($getData['delete_employee_id'])) {
            $this->deleteEmployee($getData['delete_employee_id']);
        }
    }

    // Returnér data til visning
    public function getCustomersAndEmployeesData() {
        return [
            'customers' => $this->getAllCustomers(),
            'employees' => $this->getAllEmployees(),
            'editCustomer' => isset($_GET['edit_customer_id']) ? $this->getCustomerById($_GET['edit_customer_id']) : null,
            'editEmployee' => isset($_GET['edit_employee_id']) ? $this->getEmployeeById($_GET['edit_employee_id']) : null,
        ];
    }

    // CRUD-metoder
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

    public function getAllCustomers() {
        return $this->model->getAllCustomers();
    }

    public function getAllEmployees() {
        return $this->model->getAllEmployees();
    }

    public function getCustomerById($id) {
        return $this->model->getCustomerById($id);
    }

    public function getEmployeeById($id) {
        return $this->model->getEmployeeById($id);
    }
    
}



// Method to retrieve specific settings
   /*  public function getSettings($keys)
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
    }  */

    /* // Method to update settings
    public function updateSettings($settings)
    {
        foreach ($settings as $key => $value) {
            $result = $this->model->updateItem('site_settings', ['setting_value' => $value], ['setting_key' => $key]);
            if (!$result) {
                echo "Fejl ved opdatering af indstilling: $key<br>";
            }
        }
    }  */

  /*   // Method to create a movie
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
    
} */


