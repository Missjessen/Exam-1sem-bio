<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php'; // Inkluder konfigurationsfil


// Hent slug fra URL'en
 if (isset($movie)): ?>
    <main class="moviebody">
        <div class="card">
            <!-- Header med filmplakat og detaljer -->
            <div class="film-header">
                <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="Film Plakat">
                <h1 class="film-title">Film Titel: <?php echo htmlspecialchars($movie['title']); ?></h1>
                <div class="film-info">
                    <div><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></div>
                    <div><strong>Instruktør:</strong> <?php echo htmlspecialchars($movie['director']); ?></div>
                    <div><strong>Udgivelsesdato:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></div>
                    <div><strong>Aldersgrænse:</strong> <?php echo htmlspecialchars($movie['age_rating']); ?></div>
                </div>
                <p class="film-description">
                    <?php echo htmlspecialchars($movie['description']); ?>
                </p>
                <div class="btn-container">
                    <button class="btn">Se Trailer</button>
                    <button class="btn">Tilføj til liste</button>
                </div>
            </div>

            <!-- Yderligere filmoplysninger -->
            <div class="extra-info">
                <h2>Skuespillere:</h2>
                <p><?php echo htmlspecialchars($movie['actors']); ?></p>
                <h2>Handlingsforløb:</h2>
                <p><?php echo htmlspecialchars($movie['plot']); ?></p>
            </div>
        </div>
    </main>
<?php else: ?>
    <p>Film detaljer ikke tilgængelige.</p>
<?php endif; ?>



