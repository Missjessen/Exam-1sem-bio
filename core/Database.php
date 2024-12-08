<?php

class Database {
    private static $instance = null; // Singleton instans
    private $connection; // PDO-forbindelsen

    private function __construct() {
        // Databaseforbindelsesdetaljer fra miljøvariabler
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbName = getenv('DB_NAME') ?: 'drive-in-1sem';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";

        try {
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            error_log("PDO-forbindelse oprettet.");
        } catch (PDOException $e) {
            error_log("PDO-fejl: " . $e->getMessage());
            throw new Exception("Kunne ikke oprette databaseforbindelse.");
        }
        
    }

    // Singleton instansmetode
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Hent PDO-forbindelse
    public function getConnection(): PDO {
        return $this->connection;
    }

    // Forhindrer kloning
    private function __clone() {}

    // Forhindrer unserialization
    public function __wakeup() {}
}
