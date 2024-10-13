<?php 
// Hvis function.php allerede er inkluderet i header.php, fjern linjen her:
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/header.php';
?>
<main>
    <?php

    ?>
</main>

<body class="moviebody">
    <div class="card">
        <!-- Header med filmplakat og detaljer -->
        <div class="film-header">
            <img src="movie-poster.jpg" alt="Film Plakat">
            <h1 class="film-title">Film Titel: Spider Man: Far From Home</h1>
            <div class="film-info">
                <div><strong>Genre:</strong> Action, Sci-Fi</div>
                <div><strong>Instruktør:</strong> Jon Watts</div>
                <div><strong>Udgivelsesdato:</strong> 2019-07-02</div>
                <div><strong>Aldersgrænse:</strong> PG-13</div>
            </div>
            <p class="film-description">
                Efter begivenhederne i Avengers: Endgame, må Spider-Man træde op og forsvare sin verden mod nye farer, samtidig med at han prøver at finde balancen mellem at være teenager og superhelt.
            </p>
            <div class="btn-container">
                <button class="btn">Se Trailer</button>
                <button class="btn">Tilføj til liste</button>
            </div>
        </div>

        <!-- Yderligere filmoplysninger -->
        <div class="extra-info">
            <h2>Skuespillere:</h2>
            <p>Tom Holland, Samuel L. Jackson, Jake Gyllenhaal, Zendaya</p>
            <h2>Handlingsforløb:</h2>
            <p>Spider-Man forsøger at finde balance i sit liv som teenager og superhelt, da nye trusler dukker op i kølvandet på de begivenheder, der rystede hans verden.</p>
        </div>
    </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php';?>
