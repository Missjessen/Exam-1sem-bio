<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Drive-in Bio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Sidebar til navigation -->
    <aside class="sidebar">
        <h2>Admin</h2>
        <nav>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Filmoversigt</a></li>
                <li><a href="#">Bookingoversigt</a></li>
                <li><a href="#">Nyheder</a></li>
                <li><a href="#">Kontaktinformation</a></li>
            </ul>
        </nav>
        <button class="logout-button">Log ud</button>
    </aside>

    <!-- Hovedindhold -->
    <main class="content">
        <header>
            <h1>Admin Dashboard</h1>
        </header>

        <!-- Store kort til forskellige funktionaliteter -->
        <div class="card-grid">

            <!-- Oversigt -->
            <section class="card large-card" id="overview">
                <h2>Oversigt</h2>
                <p>Se dagens bookinger og ledige parkeringspladser.</p>
                <!-- Vis liste over dagens bookinger og ledige parkeringspladser her -->
            </section>

            <!-- Tilføj ny film -->
            <section class="card" id="add-movie">
                <h2>Tilføj ny film</h2>
                <p>Opret en ny film med billeder og beskrivelser.</p>
                <section class="card" id="add-movie">
    <h2>Tilføj ny film</h2>
    <form action="add_movie.php" method="post" enctype="multipart/form-data">
        <label for="title">Filmtitel:</label>
        <input type="text" id="title" name="title" placeholder="Indtast filmtitel" required>

        <label for="director">Instruktør:</label>
        <input type="text" id="director" name="director" placeholder="Indtast instruktørens navn" required>

        <label for="release-date">Udgivelsesdato:</label>
        <input type="date" id="release-date" name="release_date" required>

        <label for="description">Beskrivelse:</label>
        <textarea id="description" name="description" rows="4" placeholder="Skriv en kort beskrivelse af filmen" required></textarea>

        <label for="movie-poster">Filmplakat:</label>
        <input type="file" id="movie-poster" name="movie_poster" accept="image/*">

        <button type="submit" class="add-button">Tilføj Film</button>
    </form>
</section>

            </section>

            <!-- Kunderedigeringssektion -->
            <section class="card" id="edit-customer">
                <h2>Administrer Kunder</h2>
                <p>Opret og rediger eksisterende kunder.</p>
                <!-- Funktion til kundeadministration -->
            </section>

            <!-- Tilføj aktører -->
            <section class="card" id="add-actor">
                <h2>Tilføj Aktører</h2>
                <p>Administrer og tilføj nye aktører.</p>
                <section class="card" id="add-actor">
    <h2>Tilføj Aktører</h2>
    <form action="add_actor.php" method="post">
        <label for="actor-name">Skuespillerens Navn:</label>
        <input type="text" id="actor-name" name="actor_name" placeholder="Indtast navn" required>

        <label for="birth-date">Fødselsdato:</label>
        <input type="date" id="birth-date" name="birth_date" required>

        <label for="biography">Biografi:</label>
        <textarea id="biography" name="biography" rows="4" placeholder="Kort beskrivelse af skuespilleren"></textarea>

        <button type="submit" class="add-button">Tilføj Skuespiller</button>
    </form>
</section>

            </section>

            <!-- Aktiviteter og validering -->
            <section class="card" id="activities">
                <h2>Aktiviteter</h2>
                <p>Godkend og valider kundeoplysninger som admin.</p>
                <!-- Liste over aktiviteter, der kræver godkendelse -->
            </section>

            <!-- Nyheder -->
            <section class="card" id="news">
                <h2>Nyheder</h2>
                <p>Tilføj nye film og kommende opdateringer.</p>
                <!-- Formular til nyhedstilføjelse -->
            </section>

            <!-- Information til hjemmeside -->
            <section class="card" id="info">
                <h2>Information til Hjemmesiden</h2>
                <p>Rediger åbningstider, om os, og kontaktinfo.</p>
                <!-- Formular til opdatering af generel information -->
                <section class="card" id="info">
    <h2>Information til Hjemmesiden</h2>
    <form action="update_info.php" method="post">
        <label for="opening-hours">Åbningstider:</label>
        <input type="text" id="opening-hours" name="opening_hours" placeholder="10:00 - 22:00" required>

        <label for="about-us">Om Os:</label>
        <textarea id="about-us" name="about_us" rows="4" placeholder="Skriv en kort beskrivelse..." required></textarea>

        <label for="contact-email">Kontakt Email:</label>
        <input type="email" id="contact-email" name="contact_email" placeholder="info@driveinbio.dk" required>

        <button type="submit" class="add-button">Opdater Information</button>
    </form>
</section>

            </section>
        </div>
    </main>

</body>
</html>
