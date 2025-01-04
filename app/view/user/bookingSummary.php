<div class="booking-summary">
    <h1>Din Booking Oversigt</h1>
    <p><strong>Film:</strong> <?= htmlspecialchars($movie_title) ?></p>
    <p><strong>Dato:</strong> <?= htmlspecialchars($show_date) ?></p>
    <p><strong>Tid:</strong> <?= htmlspecialchars($show_time) ?></p>
    <p><strong>Antal pladser:</strong> <?= htmlspecialchars($spots) ?></p>
    <p><strong>Total Pris:</strong> <?= htmlspecialchars($total_price) ?> DKK</p>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="login-register">
            <h2>Log ind eller registrer for at bekræfte din booking</h2>

            <!-- Login Form -->
            <div class="login-form">
                <h3>Log ind</h3>
                <form method="POST" action="index.php?page=login">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                    
                    <label for="password">Adgangskode:</label>
                    <input type="password" name="password" id="password" required>
                    
                    <button type="submit">Log ind</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="register-form">
                <h3>Registrer</h3>
                <form method="POST" action="index.php?page=register">
                    <label for="name">Navn:</label>
                    <input type="text" name="name" id="name" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                    
                    <label for="password">Adgangskode:</label>
                    <input type="password" name="password" id="password" required>
                    
                    <button type="submit">Registrer</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <form method="POST" action="index.php?page=confirm_booking">
            <button type="submit">Bekræft Booking</button>
        </form>
        <form method="POST" action="index.php?page=cancel_booking">
            <button type="submit">Annuller Booking</button>
        </form>
    <?php endif; ?>
</div>
