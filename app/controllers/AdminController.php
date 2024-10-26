<?php

// Inkluder databaseforbindelse og autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

class AdminController {
    private $model;

    public function __construct($db) {
        if ($db instanceof PDO) {
            $this->model = new AdminModel($db);
        } else {
            die("Databaseforbindelse er ikke korrekt oprettet.");
        }
    }
    // Hent specifikke indstillinger
    public function getSettings($keys) {
        $settings = [];
        foreach ($keys as $key) {
            $result = $this->model->getItem('site_settings', ['setting_key' => $key]);
            if ($result && isset($result['setting_value'])) {
                $settings[$key] = $result['setting_value'];
            } else {
                $settings[$key] = ''; // Brug tom streng, hvis der ikke findes en værdi
            }
        }
        return $settings;
    }

    // Opdater indstillinger
    public function updateSettings($settings) {
        foreach ($settings as $key => $value) {
            $result = $this->model->updateItem('site_settings', ['setting_value' => $value], ['setting_key' => $key]);
            if (!$result) {
                echo "Fejl ved opdatering af indstilling: $key<br>";
            }
        }
    }

   // Generiske metoder til Movies
   public function createMovie($data) {
       return $this->model->create('Movies', $data);
   }

   public function updateMovie($id, $data) {
       return $this->model->update('Movies', $data, ['movie_id' => $id]);
   }

   public function deleteMovie($id) {
       return $this->model->delete('Movies', ['movie_id' => $id]);
   }

   public function getAllMovies() {
       return $this->model->getAllMovies();
   }
}
?>
