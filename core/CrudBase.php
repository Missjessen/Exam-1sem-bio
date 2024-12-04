<?php
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
    
        return $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
  
   public function updateSettings(array $settings): void {
    try {
        $this->db->beginTransaction();

        $sql = "INSERT INTO site_settings (setting_key, setting_value)
                VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :value";
        $stmt = $this->db->prepare($sql);

        foreach ($settings as $key => $value) {
            $stmt->bindValue(':key', $key, PDO::PARAM_STR);
            $stmt->bindValue(':value', $value, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Fejl ved opdatering af $key: " . implode(", ", $stmt->errorInfo()));
            }
        }

        $this->db->commit();
    } catch (Exception $e) {
        $this->db->rollBack();
        error_log("Fejl i updateSettings: " . $e->getMessage());
        throw new Exception("En fejl opstod under opdateringen.");
    }
}



// Method to retrieve a specific item from a table
public function getItem($table, $where) {
    return $this->read($table, '*', $where, true);
}

// Method to retrieve all items from a table
public function getAllItems($table) {
    return $this->read($table);
}

// Method to update a specific item in a table
public function updateItem($table, $data, $where) {
    $updates = implode(", ", array_map(function ($key) {
        return "$key = :$key";
    }, array_keys($data)));

    $whereClause = implode(" AND ", array_map(function ($key) {
        return "$key = :$key";
    }, array_keys($where)));

    $sql = "UPDATE $table SET $updates WHERE $whereClause";
    $stmt = $this->db->prepare($sql);

    foreach (array_merge($data, $where) as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    if (!$stmt->execute()) {
        error_log("Fejl ved opdatering af $table: " . implode(", ", $stmt->errorInfo()));
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