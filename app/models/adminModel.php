<?php
// Inkluder nødvendige filer
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

class AdminModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    /**
     * Hent specifik post fra en tabel
     * @param string $table Tabellen at hente fra
     * @param array $where Betingelser for forespørgsel
     * @return mixed Post fra tabellen
     */
    public function getItem($table, $where) {
        return $this->read($table, '*', $where, true);
    }

    /**
     * Hent alle poster fra en tabel
     * @param string $table Tabellen at hente fra
     * @return array Alle poster fra tabellen
     */
    public function getAllItems($table) {
        return $this->read($table);
    }

    /**
     * Opdater specifik post i en tabel
     * @param string $table Tabellen at opdatere
     * @param array $data Data der skal opdateres
     * @param array $where Betingelser for opdatering
     * @return bool Om opdateringen lykkedes
     */
    public function updateItem($table, $data, $where) {
        return $this->update($table, $data, $where);
    }

    /**
     * Slet specifik post fra en tabel
     * @param string $table Tabellen at slette fra
     * @param array $where Betingelser for sletning
     * @return bool Om sletningen lykkedes
     */
    public function deleteItem($table, $where) {
        return $this->delete($table, $where);
    }

    /**
     * Opret en ny post i en tabel
     * @param string $table Tabellen at indsætte i
     * @param array $data Data der skal indsættes
     * @return bool Om oprettelsen lykkedes
     */
    public function createItem($table, $data) {
        return $this->create($table, $data);
    }

    /**
     * Hent specifik film
     * @param int $movieId Filmens ID
     * @return mixed Filmdata
     */
    public function getMovie($movieId) {
        return $this->read('Movies', '*', ['movie_id' => $movieId], true);
    }

    /**
     * Hent alle film
     * @return array Alle film
     */
    public function getAllMovies() {
        return $this->read('Movies');
    }
}
?>
