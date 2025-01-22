<?php

function generatePossibleFiatRadioCode($serial)
{
    // Fjern mellemrum og gør store bogstaver
    $serial = strtoupper(trim($serial));

    // Tjek om serienummeret følger mønsteret BP + 12 cifre
    if (!preg_match('/^BP\d{12}$/', $serial)) {
        return "Ugyldigt serienummer!";
    }

    // Udtræk de sidste 6 cifre som grundlag for kodegenerering
    $keyPart = substr($serial, -6);

    // Algoritme 1: Brug modulo 6 + 1 for at sikre cifre mellem 1-6
    $code1 = '';
    for ($i = 0; $i < strlen($keyPart); $i++) {
        $digit = (intval($keyPart[$i]) % 6) + 1;
        $code1 .= $digit;
    }

    // Algoritme 2: Brug en sum-baseret tilgang til at skabe et mønster
    $sum = array_sum(str_split($keyPart));
    $code2 = str_pad(($sum % 6) + 1, 4, '1', STR_PAD_LEFT);

    // Algoritme 3: Enkel forskydning af talværdier begrænset til 1-6
    $code3 = '';
    for ($i = 0; $i < strlen($keyPart); $i++) {
        $digit = (($keyPart[$i] + $i) % 6) + 1;
        $code3 .= $digit;
    }

    return [
        'Serial' => $serial,
        'Code 1 (modulo)': $code1,
        'Code 2 (sum)': $code2,
        'Code 3 (shifted)': $code3,
    ];
}

// Test med dit serienummer
$serialNumber = "BP638381940682"; // Indsæt dit serienummer her
$generatedCodes = generatePossibleFiatRadioCode($serialNumber);

foreach ($generatedCodes as $method => $code) {
    echo "$method: $code\n";
}
?>

<body>
     <!-- Hero Image Section -->
     <header class="hero-image">
    <div class="hero-overlay">
        <div class="hero-text">
            <h1>Welcome to Cruise Nights Cinema</h1>
            <p>Experience the best movies from the comfort of your car.</p>
        </div>
    </div>
</header>


    <section class="hero">
    <h2>Upcoming Movies</h2>
        <div class="hero-grid">
            <?php foreach ($upcomingMovies as $movie): ?>
                <div class="hero-slide">
                <a href="/movie_details/<?= htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($movie['poster'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Title', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Premiere: <?= htmlspecialchars($movie['release_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

        <!-- News Section -->
        <section class="news">
        <h2>Latest Releases</h2>
        <div class="news-grid">
            <?php foreach ($newsMovies as $movie): ?>
                <div class="news-item">
                <a href="/movie_details/<?= htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') ?>">
                        <img src="<?= htmlspecialchars($movie['poster'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                        <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Title', ENT_QUOTES, 'UTF-8') ?></h3>
                        <p>Premiere Date: <?= htmlspecialchars($movie['release_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

   <section class="daily-showings">
        <h2>Daily Showings</h2>
        <div class="movie-grid">
            <?php foreach ($dailyMovies as $movie): ?>
                <div class="movie-card">
                <a href="/movie_details/<?= htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') ?>">

                    <img src="<?= htmlspecialchars($movie['poster'] ?? '/default_poster.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Show Time: <?= htmlspecialchars($movie['show_time'] ?? 'TBD', ENT_QUOTES, 'UTF-8') ?></p>
                </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

     <footer>
        <div class="footer-container">
        <!-- Virksomhedsinfo -->
        <div class="company-info">
            <h3>Om Virksomheden</h3>
            <p><?= htmlspecialchars($settings['site_title'] ?? 'Drive-In Bio') ?></p> <!-- Rettet -->
            <p>Email: <a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"><?= htmlspecialchars($settings['contact_email'] ?? 'Ikke angivet') ?></a></p> <!-- Rettet -->
            <p>Åbningstider: <?= htmlspecialchars($settings['opening_hours'] ?? 'Ikke angivet') ?></p> <!-- Rettet -->
            <p><?= htmlspecialchars($settings['about_content'] ?? '') ?></p> <!-- Rettet -->
        </div>
    


        <div class="contact-form" id="contact">
    <h3>Kontakt Os</h3>

    <!-- Vis besked, hvis den findes -->
    <?php
    if (session_status() === PHP_SESSION_NONE) { 
        session_start(); 
    }

    if (!empty($_SESSION['contactMessage'])): ?>
        <div class="<?= strpos($_SESSION['contactMessage'], 'Tak') !== false ? 'contact-message' : 'error-message' ?>">
            <?= htmlspecialchars($_SESSION['contactMessage']) ?>
        </div>
        <?php
      
        error_log("Besked vist: " . $_SESSION['contactMessage']);
        unset($_SESSION['contactMessage']); 
        ?>
    <?php endif; ?>
</div>

    <form method="POST" action="<?= htmlspecialchars(BASE_URL . 'contact-handler.php') ?>">
        <div class="form-group">
            <label for="name">Navn:</label>
            <input type="text" id="name" name="name" required placeholder="Dit navn">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Din emailadresse">
        </div>
        <div class="form-group">
            <label for="subject">Emne:</label>
            <input type="text" id="subject" name="subject" required placeholder="Emnet for din besked">
        </div>
        <div class="form-group">
            <label for="message">Besked:</label>
            <textarea id="message" name="message" rows="4" required placeholder="Skriv din besked her..."></textarea>
        </div>
        <button type="submit" name="submit">Send besked</button>
    </form>
</div>

    
</footer>

    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash === '#contact') {
            const contactSection = document.querySelector('#contact');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>


<style>
.contact-message {
    color: green;
    margin-top: 10px;
    font-weight: bold;
}

.error-message {
    color: red;
    margin-top: 10px;
    font-weight: bold;
}
</style>
