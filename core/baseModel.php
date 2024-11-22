<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
// /core/CrudBase.php
class CrudBase {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Generisk funktion til at indsætte data
    public function create($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_map(function ($key) {
                return ":$key";
            }, array_keys($data)));
    
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Fejl ved oprettelse i $table: " . $e->getMessage());
            return false;
        }
    }
    

   
    public function update($table, $data, $where) {
        try {
            $updates = implode(", ", array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($data)));
    
            $whereClause = implode(" AND ", array_map(function ($key) {
                return "$key = :where_$key";
            }, array_keys($where)));
    
            $sql = "UPDATE $table SET $updates WHERE $whereClause";
            $stmt = $this->db->prepare($sql);
    
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            foreach ($where as $key => $value) {
                $stmt->bindValue(":where_$key", $value);
            }
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Fejl ved opdatering i $table: " . $e->getMessage());
            return false;
        }
    }

    // Generisk funktion til at slette data
    public function delete($table, $where) {
        try {
            $whereClause = implode(" AND ", array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($where)));
    
            $sql = "DELETE FROM $table WHERE $whereClause";
            $stmt = $this->db->prepare($sql);
    
            foreach ($where as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Fejl ved sletning i $table: " . $e->getMessage());
            return false;
        }
    }
    

    // Generisk funktion til at læse data
    public function read($table, $columns = '*', $where = [], $single = false) {
        $whereClause = '';
        if (!empty($where)) {
            $whereConditions = [];
            foreach ($where as $key => $value) {
                $whereConditions[] = "$key = :$key";
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
    
        $sql = "SELECT $columns FROM $table $whereClause";
        $stmt = $this->db->prepare($sql);
    
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    
        $stmt->execute();
        if ($single) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function updateSettings($db, $settings) {
        try {
            // Start en transaktion
            $db->beginTransaction();
    
            // Forbered SQL-forespørgslen
            $sql = "INSERT INTO site_settings (setting_key, setting_value) 
                    VALUES (:key, :value) 
                    ON DUPLICATE KEY UPDATE setting_value = :value";
            $stmt = $db->prepare($sql);
    
            foreach ($settings as $key => $value) {
                // Bind parametre for hver iteration
                $stmt->bindValue(':key', $key, PDO::PARAM_STR);
                $stmt->bindValue(':value', $value, PDO::PARAM_STR);
    
                // Udfør forespørgslen
                if (!$stmt->execute()) {
                    throw new Exception("Fejl ved opdatering af $key: " . implode(", ", $stmt->errorInfo()));
                }
            }
    
            // Commit transaktionen
            $db->commit();
            echo "Indstillinger opdateret med succes!";
        } catch (Exception $e) {
            // Rollback hvis der opstår en fejl
            $db->rollBack();
            error_log($e->getMessage(), 3, $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');
            echo "En fejl opstod under opdateringen. Kontakt venligst administrator.";
        }
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


// Tilføj denne funktion til CrudBase
public function readWithJoin($table, $columns, $joins, $where = [], $single = false) {
    try {
        // Bygger JOIN-klausulen
        $joinClause = implode(' ', $joins); // Fx: ["INNER JOIN other_table ON table.id = other_table.foreign_key"]

        // Bygger WHERE-klausulen
        $whereClause = '';
        if (!empty($where)) {
            $whereConditions = array_map(fn($key) => "$key = :$key", array_keys($where));
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }

        // SQL-sætning
        $sql = "SELECT $columns FROM $table $joinClause $whereClause";
        $stmt = $this->db->prepare($sql);

        // Binder parametre
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        // Returnerer resultatet
        return $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fejl ved læsning med JOIN: " . $e->getMessage());
        return false;
    }
}

 // Funktion til at administrere mange-til-mange relationer
 public function manageManyToMany($pivotTable, $foreignKeys, $id, $relatedIds) {
    try {
        // Fjern eksisterende relationer
        $deleteSql = "DELETE FROM $pivotTable WHERE {$foreignKeys['main']} = :main_id";
        $deleteStmt = $this->db->prepare($deleteSql);
        $deleteStmt->execute(['main_id' => $id]);

        // Tilføj nye relationer
        $insertSql = "INSERT INTO $pivotTable ({$foreignKeys['main']}, {$foreignKeys['related']}) VALUES (:main_id, :related_id)";
        $insertStmt = $this->db->prepare($insertSql);
        foreach ($relatedIds as $relatedId) {
            $insertStmt->execute(['main_id' => $id, 'related_id' => $relatedId]);
        }

        return true;
    } catch (PDOException $e) {
        error_log("Fejl ved mange-til-mange opdatering: " . $e->getMessage());
        return false;
    }
}
 

}



?>