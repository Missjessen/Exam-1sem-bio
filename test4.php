<?php
require_once 'Database.php';

try {
    $db = Database::getInstance()->getConnection();
    if ($db instanceof PDO) {
        echo "✅ Databaseforbindelsen er korrekt.";
    } else {
        echo "❌ Databaseforbindelsen er ikke en gyldig PDO-instans.";
    }
} catch (Exception $e) {
    echo "❌ Fejl: " . $e->getMessage();
}