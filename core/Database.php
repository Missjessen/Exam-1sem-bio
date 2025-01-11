<?php 
class Database {
    private static $instance = null; // Singleton instans
    private $connection; // PDO-forbindelsen

    private function __construct() {
        // Databaseforbindelsesdetaljer fra miljøvariabler
       // $host = getenv('DB_HOST') ?: 'localhost';
       // $dbName = getenv('DB_NAME') ?: 'cjsfkt3sf_cruisenightscinema';
       // $user = getenv('DB_USER') ?: 'root';
        //$password = getenv('DB_PASS') ?: '';
       // $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
   /*     private function __construct() {
        // Hent miljøvariabler fra .env
        $envFile = __DIR__ . '/../.env'; // Sørg for, at stien til .env er korrekt
        if (!file_exists($envFile)) {
            throw new Exception(".env-filen blev ikke fundet.");
        }

        // Læs .env-filen
        $envVariables = parse_ini_file($envFile);
        $host = $envVariables['DB_HOST'];
        $dbName = $envVariables['DB_NAME'];
        $user = $envVariables['DB_USER'];
        $password = $envVariables['DB_PASS'];
        $charset = $envVariables['DB_CHARSET'];

        // Opsæt DSN
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset"; */
        
        $host = 'localhost';
$dbName = 'cjsfkt3sf_cruisenightscinema';
$user = 'cjsfkt3sf_cruisenightscinema'; 
$password = '123456'; 
$charset = 'utf8mb4';


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
