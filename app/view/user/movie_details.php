<div class="movie-details">
    <h1><?= htmlspecialchars($movie['title']) ?></h1>
    <div class="movie-info">
        <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster">
        <p><strong>Beskrivelse:</strong> <?= nl2br(htmlspecialchars($movie['description'])) ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
        <p><strong>Skuespillere:</strong> <?= htmlspecialchars($movie['actors']) ?></p>
    </div>

    <h1>Bookingformular</h1>
    <form action="booking.php" method="POST">
        <label for="screen">Vælg skærm:</label>
        <select name="screen" id="screen" required>
            <option value="small">Lille skærm</option>
            <option value="large">Stor skærm</option>
        </select>

        <label for="rowType">Vælg række:</label>
        <select name="rowType" id="rowType" required>
            <option value="front">Forreste række</option>
            <option value="middle">Midterste række</option>
            <option value="back">Bagerste række</option>
        </select>

        <label for="spots">Antal pladser:</label>
        <input type="number" name="spots" id="spots" min="1" max="10" required>

        <p>Total pris: <span id="totalPrice">0 DKK</span></p>

        <button type="submit">Book nu</button>
    </form>

    <script>
        // Hent HTML-elementer
        const screenInput = document.getElementById('screen');
        const rowTypeInput = document.getElementById('rowType');
        const spotsInput = document.getElementById('spots');
        const totalPriceElement = document.getElementById('totalPrice');

        // Definer priser
        const prices = {
            small: { front: 75, middle: 50, back: 40 },
            large: { front: 100, middle: 75, back: 50 }
        };

        // Funktion til at opdatere prisen
        function updatePrice() {
            const screen = screenInput.value;
            const rowType = rowTypeInput.value;
            const spots = parseInt(spotsInput.value, 10) || 0;

            const pricePerSpot = prices[screen]?.[rowType] || 0;
            totalPriceElement.textContent = (spots * pricePerSpot) + " DKK";
        }

        // Event listeners
        screenInput.addEventListener('change', updatePrice);
        rowTypeInput.addEventListener('change', updatePrice);
        spotsInput.addEventListener('input', updatePrice);

        // Initial opdatering
        updatePrice();
    </script>

<style> form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #totalPrice {
            font-weight: bold;
            color: green;
        }
    </style>
</style>