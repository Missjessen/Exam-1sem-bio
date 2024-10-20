<?php

// Forbind til databasen
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';

// Forbered og udfør forespørgslen

$stmt = $db->prepare("SELECT * FROM pages WHERE LOWER(page_name) = LOWER(:page)");
$stmt->bindParam(':page', $page, PDO::PARAM_STR);
$stmt->execute();
$pageData = $stmt->fetch(PDO::FETCH_ASSOC);

// Kontrollér, om siden blev fundet i databasen
if ($pageData) {
    // Indlæs sidens CSS, titel, og indhold
    echo '<link rel="stylesheet" href="/Exam-1sem-bio/assets/css/' . $pageData['css_file'] . '">';
    echo '<title>' . htmlspecialchars($pageData['title']) . '</title>';
    echo '<div>' . htmlspecialchars($pageData['content']) . '</div>';

    // Inkluder skabelonfilen, hvis den findes
    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . $pageData['template_file'];
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "Skabelonfilen blev ikke fundet.";
    }
} else {
    // Vis fejlmeddelelse, hvis siden ikke blev fundet i databasen
    echo "Siden blev ikke fundet.";
}

function updateSettings($conn, $settings) {
    // Gennemløb alle indstillinger i arrayet $settings
    foreach ($settings as $key => $value) {
        // Beskytter nøglen mod SQL-injection ved at escape specielle tegn
        $key = $conn->real_escape_string($key);
        // Beskytter værdien mod SQL-injection ved at escape specielle tegn
        $value = $conn->real_escape_string($value);

        // SQL-forespørgsel for at indsætte eller opdatere indstillingen
        $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value='$value'";
        
        // Udfør forespørgslen og tjek for fejl
        if (!$conn->query($sql)) {
            // Hvis forespørgslen fejler, vis en fejlmeddelelse
            echo "Fejl ved opdatering af $key: " . $conn->error;
        }
    }
    // Overvej at returnere en succesmeddelelse eller håndtere succes på en anden måde
}

function createContent($conn, $table, $data) {
    // Samler alle kolonnenavne fra $data arrayet til en kommasepareret streng
    $columns = implode(", ", array_keys($data));
    
    // Samler alle værdierne fra $data arrayet og escaper specielle tegn for at beskytte mod SQL-injection
    $values = implode(", ", array_map(function($value) use ($conn) {
        return "'" . $conn->real_escape_string($value) . "'";
    }, array_values($data)));
    
    // Bygger SQL-forespørgslen til at indsætte data i den angivne tabel
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    
    // Udfører forespørgslen og returnerer resultatet (true eller false)
    return $conn->query($sql);
    
    // Overvej at tilføje fejlhåndtering her, hvis forespørgslen fejle
}

function deleteContent($conn, $table, $id) {
    // Konverterer id til et heltal for at beskytte mod SQL-injection
    $id = intval($id);
    
    // Bygger SQL-forespørgslen til at slette en post fra den angivne tabel baseret på id
    $sql = "DELETE FROM $table WHERE id = $id";
    
    // Udfører forespørgslen og returnerer resultatet (true eller false)
    return $conn->query($sql);
    
    // Overvej at tilføje fejlhåndtering her, hvis forespørgslen fejler, for at give mere specifik feedback
}

function getSetting($conn, $key) {
    // Beskytter input mod SQL-injection ved at escape specielle tegn
    $key = $conn->real_escape_string($key);
    
    // SQL-forespørgsel for at hente værdien af en specifik indstilling baseret på nøglen
    $sql = "SELECT setting_value FROM site_settings WHERE setting_key = '$key'";
    
    // Udfører forespørgslen mod databasen
    $result = $conn->query($sql);
    
    // Kontrollerer om forespørgslen returnerede nogle resultater
    if ($result && $result->num_rows > 0) {
        // Henter resultatet som en associerende array
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }
    
    // Returnerer null, hvis ingen resultater blev fundet
    return null;
}

function updateContent($conn, $table, $id, $data) {
    // Bygger SQL-forespørgslen dynamisk baseret på input data
    $updates = [];
    foreach ($data as $key => $value) {
        // Escape både nøglen og værdien for at beskytte mod SQL-injection
        $key = $conn->real_escape_string($key);
        $value = $conn->real_escape_string($value);
        $updates[] = "$key='$value'";
    }
    // Samler alle updates som kommasepareret streng
    $updates_str = implode(", ", $updates);

    // Bygger den endelige SQL-forespørgsel
    $sql = "UPDATE $table SET $updates_str WHERE id=$id";

    // Udfører forespørgslen og returnerer resultatet
    if (!$conn->query($sql)) {
        // Hvis forespørgslen fejler, vis en fejlmeddelelse
        echo "Fejl ved opdatering af id $id i tabel $table: " . $conn->error;
    }
}

?>
