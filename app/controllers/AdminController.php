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
    public function handleSettings($postData = null) {
        if ($postData) {
            $updatedSettings = [
                'site_title' => $postData['site_title'] ?? '',
                'contact_email' => $postData['contact_email'] ?? '',
                'opening_hours' => $postData['opening_hours'] ?? '',
                'about_content' => $postData['about_content'] ?? '',
            ];
    
            error_log("Opdaterer indstillinger: " . print_r($updatedSettings, true));
            $this->model->updateSettings($updatedSettings);
        }
    
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

    public function updateSettings(array $settings): void {
        $this->model->updateSettings($settings);
    }
    
    


                      /* manege user  */


    // Customers methods
     // Håndter alle POST- og GET-anmodninger for kunder og ansatte
     public function handleCustomerAndEmployeeSubmission($postData, $getData) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tilføj eller opdater kunder
            if (isset($postData['add_or_update_customer'])) {
                $password = !empty($postData['password']) ? password_hash($postData['password'], PASSWORD_BCRYPT) : null;
                $this->saveCustomer([
                    'id' => $postData['id'] ?? null,
                    'name' => $postData['name'],
                    'email' => $postData['email'],
                    'password' => $password['password'],
                ]);
            }
    
            // Tilføj eller opdater ansatte
            if (isset($postData['add_or_update_employee'])) {
                $password = !empty($postData['password']) ? password_hash($postData['password'], PASSWORD_BCRYPT) : null;
                $this->saveEmployee([
                    'id' => $postData['id'] ?? null,
                    'name' => $postData['employee_name'],
                    'email' => $postData['employee_email'],
                    'phone' => $postData['employee_phone'],
                    'role' => $postData['employee_role'],
                    'address' => $postData['employee_address'],
                    'password' => $password['password'],
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




