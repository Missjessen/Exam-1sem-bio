<?php
// Inkluder databaseforbindelse og autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
class FileUploadService {
    private $upload_dir;

    public function __construct() {
        $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/uploads/';
    }

    public function uploadFile($file) {
        $poster_path = '';

        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $file['tmp_name'];
            $poster_name = basename($file['name']);
            $poster_path = $this->upload_dir . $poster_name;

            // Flyt filen til uploads-mappen
            if (move_uploaded_file($tmp_name, $poster_path)) {
                return '/Exam-1sem-bio/uploads/' . $poster_name; // Returner relativ sti til lagring i databasen
            } else {
                throw new Exception("Fejl: Kunne ikke flytte den uploadede fil.");
            }
        }

        return $poster_path;
    }
}
?>
