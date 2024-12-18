<?php 

class FileUploadService {
    private $upload_dir;
    const MAX_SIZE = 300 * 1024; // Maksimal størrelse i bytes (300 KB)
    private $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct() {
        // Brug DOCUMENT_ROOT til at pege på roden
        $this->upload_dir = htmlspecialchars(BASE_URL . '/uploads') ;
        error_log("Upload-sti: " . $this->upload_dir); // Debug for at kontrollere stien
    }

    public function uploadFile($file) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Fejl: Ingen fil blev uploadet, eller der opstod en fejl.");
        }

        // Ekstra tjek for at sikre, at upload-mappen eksisterer
        if (!is_dir($this->upload_dir) && !mkdir($this->upload_dir, 0777, true)) {
            throw new Exception("Fejl: Kunne ikke oprette upload-mappen: " . $this->upload_dir);
        }

        $poster_name = basename($file['name']);
        $poster_path = $this->upload_dir . $poster_name;
        $extension = strtolower(pathinfo($poster_name, PATHINFO_EXTENSION));

        // Filtypekontrol
        if (!in_array($extension, $this->allowed_extensions)) {
            throw new Exception("Fejl: Ukendt filtype! Kun jpg, jpeg, png, og gif er tilladt.");
        }

        // Filstørrelseskontrol
        if ($file['size'] > self::MAX_SIZE) {
            throw new Exception("Fejl: Billedet er for stort! Maksimal størrelse er " . (self::MAX_SIZE / 1024) . " KB.");
        }

        // Flytning af den uploadede fil
        if (!move_uploaded_file($file['tmp_name'], $poster_path)) {
            throw new Exception("Fejl: Kunne ikke flytte den uploadede fil til $poster_path. Kontroller mappeindstillingerne.");
        }

        // Returnér en relativ sti til brug i HTML
        return '/uploads' . $poster_name;
    }
}
