<?php
require_once 'constants.php';

try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseforbindelse fejlede: " . $e->getMessage());
}

if (isset($_POST['title']) && isset($_FILES['movie_poster'])) {
    $title = $_POST['title'];
    $director = $_POST['director'];
    $release_date = $_POST['release_date'];
    $description = $_POST['description'];

    // Håndtering af filupload
    $file = $_FILES['movie_poster'];
    $fileName = basename($file["name"]);
    $fileTmpName = $file["tmp_name"];
    $fileError = $file["error"];
    $fileSize = $file["size"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = array("jpg", "jpeg", "png", "gif");

    if (in_array($fileExt, $allowed) && $fileError === 0 && $fileSize < 5000000) {
        $newFileName = uniqid('', true) . "." . $fileExt;
        $targetFilePath = "uploads/" . $newFileName;

        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            try {
                // Forbered SQL-sætningen
                $sql = "INSERT INTO movies (title, director, release_date, description, poster_path) VALUES (:title, :director, :release_date, :description, :poster_path)";
                $stmt = $db->prepare($sql);

                // Bind parametrene til SQL-sætningen
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':director', $director);
                $stmt->bindParam(':release_date', $release_date);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':poster_path', $targetFilePath);

                // Eksekver forespørgslen og tjek om det lykkedes
                if ($stmt->execute()) {
                    echo "Filmen er oprettet med billedet uploadet!";
                }
            } catch (PDOException $e) {
                echo "Fejl i forberedelsen eller eksekveringen af forespørgslen: " . $e->getMessage();
            }
        } else {
            echo "Fejl ved upload af fil.";
        }
    } else {
        echo "Ugyldig filtype eller størrelse for stor.";
    }
} else {
    echo "Manglende data. Udfyld venligst alle felter.";
}
