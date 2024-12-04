<?php

class Database {
    private static $instance = null; // Singleton-instansen
    private $connection; // PDO-forbindelsen

    private function __construct() {
        // Definér stien til miljøfilerne i roden
        $host = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'unknown';
        $environment = (strpos($host, 'localhost') !== false || $host == '127.0.0.1') ? '.env.local' : '.env.production';
        
        echo "Host: $host<br>";
        echo "Valgt miljøfil: $environment<br>";
        
        $filePath = dirname(__DIR__) . '/' . $environment;
        echo "Miljøfil sti: $filePath<br>";


        // Brug miljøvariabler eller standardværdier
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbName = $_ENV['DB_NAME'] ?? 'drive-in-1sem';
        $user = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        // Data Source Name (DSN)
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";

        try {
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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

    // Indlæser miljøvariabler fra en .env-fil
    public function loadEnvFile($filePath) {
        if (!file_exists($filePath)) {
            die("Miljøfilen $filePath blev ikke fundet.");
        }

        // Debugging: Vis, at filen blev fundet
        echo "Miljøfil fundet: $filePath<br>";

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Ignorer kommentarer
            }

            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}
