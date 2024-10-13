
<?php
header("Content-type: text/css");


?>
@import url('variables.css');
body {
    background-color: var(--bg-color, #121212);
    color: var(--text-color, #FFFFFF);
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    min-height: 100vh;
}

/* Header */
header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--header-bg, #333);
    padding: 10px 20px;
    color: var(--header-color, white);
    z-index: 1000;
}

nav ul {
    list-style-type: none;
    display: flex;
    gap: 15px;
    margin: 0;
    padding: 0;
}

nav ul li a {
    color: var(--link-color, white);
    text-decoration: none;
}

/* Main content */
main {
    margin-top: 70px; /* plads under headeren */
    padding: 20px;
    background-color: var(--main-bg, #2b2b2b);
    color: var(--main-text-color, #fff);
}

/* Movie Page Styles */
.moviebody {
    background-color: var(--movie-bg, #1e1e1e);
    color: var(--accent-color, #f3b96e);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.card {
    background-color: var(--card-bg, #333);
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 800px;
    margin: 20px;
    padding: 20px;
}

.film-header img {
    width: 100%;
    max-width: 400px;
    height: auto;
    border-radius: 15px;
    margin-bottom: 20px;
}

.film-title {
    font-size: 36px;
    color: var(--film-title-color, #f6f6f6);
    text-align: center;
    margin-bottom: 10px;
}

/* Parking Grid Styles */
.parking-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 10px;
    margin: 20px 0;
}

.parking-spot {
    width: 40px;
    height: 40px;
    background: url('car-icon.png') no-repeat center;
    background-size: contain;
    border-radius: 5px;
    cursor: pointer;
}

.parking-spot.available {
    filter: grayscale(100%);
}

.parking-spot.booked {
    filter: brightness(0.5);
}

.parking-spot.selected {
    filter: brightness(1.2) sepia(1) hue-rotate(330deg);
}

/* Ticket styles */
.ticket {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    background-color: var(--ticket-bg, #1e1e1e);
}

.ticket h3 {
    color: var(--ticket-header-color, #f3b96e);
    margin-bottom: 10px;
}

.ticket p {
    color: var(--ticket-text-color, #b9b2a7);
    font-size: 14px;
}

/* Footer */
footer {
    background-color: var(--footer-bg, #333);
    padding: 20px;
    text-align: center;
    color: var(--footer-text-color, #f0e6d2);
}
