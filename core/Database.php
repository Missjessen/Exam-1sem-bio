<?php 

class Database {
    private static $instance = null; // Holder singleton-instansen
    private $connection; // PDO-forbindelsen

    // Privat konstruktor forhindrer direkte oprettelse
    private function __construct() {
        // Hent miljøvariabler eller brug standardværdier
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbName = $_ENV['DB_NAME'] ?? 'drive-in-1sem';
        $user = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        // Data Source Name (DSN)
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";

        try {
            // Opret PDO-forbindelse
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Undtagelser på fejl
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Standard fetch-mode
        } catch (PDOException $e) {
            // Log fejl og afslut applikationen
            error_log("Databaseforbindelse fejlede: " . $e->getMessage());
            die("Databaseforbindelse fejlede. Kontakt administratoren.");
        }
    }

    // Forhindrer kloning af instansen
    private function __clone() {}

    // Forhindrer unserialization af instansen
    public function __wakeup() {}

    // Offentlig metode til at få singleton-instansen
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Offentlig metode til at få PDO-forbindelsen
    public function getConnection(): PDO {
        return $this->connection;
    }

    // Test forbindelsen (valgfri til debugging)
    public static function testConnection() {
        try {
            $db = self::getInstance()->getConnection();
            echo "Forbindelse til databasen er vellykket.";
        } catch (Exception $e) {
            error_log("Test af databaseforbindelse fejlede: " . $e->getMessage());
            echo "Kunne ikke forbinde til databasen.";
        }
    }
}