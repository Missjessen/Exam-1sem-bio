<?php
require_once dirname(__DIR__, 2) . '/init.php';

require_once '/../services/fileUploadService.php';

class UploadController {
    private $fileUploadService;

    public function __construct() {
        $this->fileUploadService = new FileUploadService();
    }

    public function handleFileUpload($file, $description) {
        global $db; // Brug den globale databaseforbindelse

        try {
            // Upload filen og få stien tilbage
            $imagePath = $this->fileUploadService->uploadFile($file);

            // Gem billedet og beskrivelsen i databasen
            $query = "INSERT INTO img (poster, description) VALUES (:poster, :description)";
            $stmt = $db->prepare($query);
            $stmt->execute(['poster' => $imagePath, 'description' => $description]);

            echo "<h1>Billedet blev uploadet med succes!</h1>";
        } catch (Exception $e) {
            echo "<h1>{$e->getMessage()}</h1>";
        }
    }
}

