
<?php


// Inkluder header og databaseforbindelse
 
// Inkluder header og databaseforbindelse ved hjælp af absolut sti
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php'; 

// (rest of your code here)

// Inkluder funktioner
$page = $_GET['page'] ?? 'spots';
?>


<body>
    <h1>Drive-In Bio - Parkering</h1>
    <div class="container">
        <!-- Film Information Section -->
        <div class="film-info">
            <h2>Film Information</h2>
            <img src="movie-poster.jpg" alt="Film Plakat" style="width:100%; height: auto; border-radius: 5px;">
            <p><strong>Film Navn:</strong> Filmens Titel</p>
            <p><strong>Beskrivelse:</strong> Kort beskrivelse af filmen.</p>
            <p><strong>Tidspunkt:</strong> 20:30</p>
        </div>

        <!-- Parking Grid Section -->
        <div class="parking-grid">
            <?php
            require_once '../includes/connection.php';

            $currentFilmId = 1;

            // Hent bookede pladser fra databasen
            $stmt = $db->prepare("SELECT spot_id FROM bookings WHERE film_id = :film_id");
            $stmt->bindParam(':film_id', $currentFilmId, PDO::PARAM_INT);
            $stmt->execute();
            $bookedSpots = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Håndter reservation af en plads
            if (isset($_POST['selected_spot'])) {
                $selectedSpot = $_POST['selected_spot'];
                $price = 0;

                if (!in_array($selectedSpot, $bookedSpots)) {
                    // Beregn prisen afhængig af række (for eksempel)
                    if ($selectedSpot <= 10) { // Forreste række
                        $price = 200;
                    } elseif ($selectedSpot <= 30) { // Midterste rækker
                        $price = 150;
                    } else { // Bagerste rækker
                        $price = 100;
                    }

                    $insertStmt = $db->prepare("INSERT INTO bookings (film_id, spot_id, price) VALUES (:film_id, :spot_id, :price)");
                    $insertStmt->bindParam(':film_id', $currentFilmId, PDO::PARAM_INT);
                    $insertStmt->bindParam(':spot_id', $selectedSpot, PDO::PARAM_INT);
                    $insertStmt->bindParam(':price', $price, PDO::PARAM_INT);

                    if ($insertStmt->execute()) {
                        echo "<p>Reservation bekræftet for parkeringsplads: $selectedSpot! Pris: $price DKK</p>";
                        $bookedSpots[] = $selectedSpot;
                    } else {
                        echo "<p>Fejl ved reservation: " . $insertStmt->errorInfo()[2] . "</p>";
                    }
                } else {
                    echo "<p>Pladsen er allerede booket.</p>";
                }
            }

            // Generér parkeringspladser og marker dem, der er booket
            echo '<form method="POST" action="">';
            echo '<div class="screen">Screen</div>';
            echo '<div class="parking-rows">';
            
            for ($i = 1; $i <= 50; $i++) {
                if ($i % 10 == 1) {
                    echo '<div class="parking-row">';
                }

                $class = in_array($i, $bookedSpots) ? 'booked' : 'available';
                $price = ($i <= 10) ? 200 : (($i <= 30) ? 150 : 100);

                echo '<label>';
                echo '<input type="radio" name="selected_spot" value="' . $i . '" style="display: none;"' . (in_array($i, $bookedSpots) ? ' disabled' : '') . '>';
                echo '<div class="parking-spot ' . $class . '" data-spot="' . $i . '" data-price="' . $price . '">';
                echo '<img src="images/car2.png" alt="Parking Icon" class="parking-icon">';
                echo '<p class="spot-price">' . $price . ' DKK</p>';
                echo '</div>';
                echo '</label>';

                if ($i % 10 == 0) {
                    echo '</div>';
                }
            }
            
            echo '</div>';
            echo '<br><input type="submit" value="Bekræft Reservation">';
            echo '</form>';
            ?>
        </div>

            </table>
            <div>

<!-- Calendar and Price Information Section -->
<div class="calendar-section">
   <h3>Film Kalender</h3>
   <!-- Kalenderen kan genereres baseret på filmens visningstider fra databasen -->
   <table class="calendar">
       <tr><th>Dato</th><th>Tidspunkt</th><th>Film Titel</th></tr>
       <?php
       // Hent filmvisninger for de kommende dage
       $calStmt = $db->prepare("SELECT show_date, show_time, title FROM films INNER JOIN showtimes ON films.film_id = showtimes.film_id");
       $calStmt->execute();
       while ($row = $calStmt->fetch(PDO::FETCH_ASSOC)) {
           echo "<tr><td>{$row['show_date']}</td><td>{$row['show_time']}</td><td>{$row['title']}</td></tr>";
       }
       ?>
</div>
        </div>
        <div>

         <!-- Calendar and Price Information Section -->
         <div class="calendar-section">
            <h3>Film Kalender</h3>
            <!-- Kalenderen kan genereres baseret på filmens visningstider fra databasen -->
            <table class="calendar">
                <tr><th>Dato</th><th>Tidspunkt</th><th>Film Titel</th></tr>
                <?php
                // Hent filmvisninger for de kommende dage
                $calStmt = $db->prepare("SELECT show_date, show_time, title FROM films INNER JOIN showtimes ON films.film_id = showtimes.film_id");
                $calStmt->execute();
                while ($row = $calStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>{$row['show_date']}</td><td>{$row['show_time']}</td><td>{$row['title']}</td></tr>";
                }
                ?>
    </div>

</body>



