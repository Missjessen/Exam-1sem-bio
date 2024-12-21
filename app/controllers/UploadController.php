<?php
require_once dirname(__DIR__, 2) . '/init.php';

require_once '/../services/fileUploadService.php';

class UploadController {
    private $fileUploadService;
    private $db;

    public function __construct($db) {
        $this->fileUploadService = new FileUploadService();
        $this->db = $db;
    }

    public function handleFileUpload($file, $description) {
        try {
            // Upload filen og få stien tilbage
            $imagePath = $this->fileUploadService->uploadFile($file);

            // Gem billedet og beskrivelsen i databasen
            $query = "INSERT INTO img (poster, description) VALUES (:poster, :description)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':poster', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            echo "<h1>Billedet blev uploadet med succes!</h1>";
        } catch (Exception $e) {
            echo "<h1>{$e->getMessage()}</h1>";
            error_log("Fejl under filupload: " . $e->getMessage());
        }
    }
}
