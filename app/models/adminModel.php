<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';

class AdminModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    // Generisk metode til at hente en specifik indstilling eller post fra en hvilken som helst tabel
    public function getItem($table, $where) {
        return $this->read($table, '*', $where, true);
    }

    // Generisk metode til at hente alle poster fra en given tabel
    public function getAllItems($table) {
        return $this->read($table);
    }

    // Generisk metode til at opdatere en specifik post i en tabel
    public function updateSettings($table, $data, $where) {
        return $this->updateSettings($table, $data, $where);
    }

    // Generisk metode til at slette en specifik post fra en tabel
    public function deleteItem($table, $where) {
        return $this->delete($table, $where);
    }

    // Generisk metode til at oprette en post i en given tabel
    public function createItem($table, $data) {
        return $this->create($table, $data);
    }

    public function updateItem($table, $data, $where) {
        $updates = implode(", ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));
    
        $whereClause = implode(" AND ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($where)));
    
        $sql = "UPDATE $table SET $updates WHERE $whereClause";
        $stmt = $this->db->prepare($sql);
    
        // Debugging for at se SQL-forespørgsel og de aktuelle værdier
        echo "SQL Forespørgsel: $sql<br>";
        echo "Data til opdatering: " . json_encode(array_merge($data, $where)) . "<br>";
    
        foreach (array_merge($data, $where) as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    
        if (!$stmt->execute()) {
            // Log fejl, hvis eksekvering fejler
            error_log("Fejl ved opdatering af $table: " . implode(", ", $stmt->errorInfo()), 3, $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');
            return false;
        }
    
        return true;
    }
        // Eventuel specifik funktion til Movies (hvis der er særlige regler for Movies)
        public function getMovie($movieId) {
            return $this->read('Movies', '*', ['movie_id' => $movieId], true);
        }
    
        public function getAllMovies() {
            return $this->read('Movies');
        }
    }

?>
