<?php 

class FileUploadService {
    private $upload_dir;
    const MAX_SIZE = 300 * 1024; // Maksimal størrelse i bytes (300 KB)
    private $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct() {
        $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';

        error_log("Upload-sti: " . $this->upload_dir); // Debug
    }

    public function uploadFile($file) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Fejl: Ingen fil blev uploadet, eller der opstod en fejl.");
        }

        // Tjek om mappen findes eller opret den
        if (!is_dir($this->upload_dir) && !mkdir($this->upload_dir, 0777, true)) {
            throw new Exception("Fejl: Kunne ikke oprette upload-mappen.");
        }

        $poster_name = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
        $poster_path = $this->upload_dir . '/' . $poster_name;
        $extension = strtolower(pathinfo($poster_name, PATHINFO_EXTENSION));

        // Tjek filtype
        if (!in_array($extension, $this->allowed_extensions)) {
            throw new Exception("Fejl: Ukendt filtype. Kun jpg, jpeg, png og gif er tilladt.");
        }

        // Tjek filstørrelse
        if ($file['size'] > self::MAX_SIZE) {
            throw new Exception("Fejl: Filen er for stor. Maksimal størrelse er " . (self::MAX_SIZE / 1024) . " KB.");
        }

        // Flyt filen
        if (!move_uploaded_file($file['tmp_name'], $poster_path)) {
            throw new Exception("Fejl: Kunne ikke flytte den uploadede fil.");
        }

        return '/uploads/' . $poster_name; // Returner relativ sti
    }
}
