
<div class="booking-summary-container">
    <!-- Booking Card -->
    <div class="booking-card">
        <h2>Din Booking Oversigt</h2>
        <?php if (!isset($_SESSION['pending_booking'])): ?>
            <p>Ingen bookingdata fundet. Start en ny booking.</p>
            <a href="<?= BASE_URL ?>index.php?page=program" class="btn-go-to-program">Se Program</a>

        <?php else: ?>
            <?php $booking = $_SESSION['pending_booking']; ?>
            <p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title']) ?></p>
            <p><strong>Dato:</strong> <?= htmlspecialchars($booking['show_date']) ?></p>
            <p><strong>Tid:</strong> <?= htmlspecialchars($booking['show_time']) ?></p>
            <p><strong>Antal pladser:</strong> <?= htmlspecialchars($booking['spots']) ?></p>
            <p><strong>Total Pris:</strong> <?= htmlspecialchars($booking['total_price']) ?> DKK</p>
        <?php endif; ?>
    </div>

    <!-- Login/Register Section -->
    <div class="user-auth-card">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h3>Log ind eller registrer for at bekræfte din booking</h3>

            <!-- Login Form -->
            <div class="login-form">
                <h4>Log ind</h4>
                <form method="POST" action="index.php?page=login">
                <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <label for="email-login">Email:</label>
                    <input type="email" name="email" id="email-login" required>
                    
                    <label for="password-login">Adgangskode:</label>
                    <input type="password" name="password" id="password-login" required>
                    
                    <button type="submit" class="btn-login">Log ind</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="register-form">
                <h4>Registrer</h4>
                <form method="POST" action="index.php?page=register">
                <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <label for="name-register">Navn:</label>
                    <input type="text" name="name" id="name-register" required>
                    
                    <label for="email-register">Email:</label>
                    <input type="email" name="email" id="email-register" required>
                    
                    <label for="password-register">Adgangskode:</label>
                    <input type="password" name="password" id="password-register" required>
                    
                    <button type="submit" class="btn-register">Registrer</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Logged-In Section -->
            <div class="logged-in-message">
                <h3>Du er logget ind som: <?= htmlspecialchars($_SESSION['user_name']) ?></h3>
                <p>Klik på knappen nedenfor for at bekræfte din booking.</p>
                <form method="POST" action="index.php?page=confirm_booking">
                    <button type="submit" class="btn-confirm">Bekræft Booking</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

 <style>.booking-summary-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #000;
}

.booking-card, .user-auth-card {
    width: 48%;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.booking-card h2, .user-auth-card h3 {
    margin-top: 0;
    color: #f6f6f6;
}

.booking-card p {
    margin: 5px 0;
    font-size: 16px;
}

.user-auth-card .login-form, .user-auth-card .register-form {
    margin-top: 15px;
}

.user-auth-card label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 14px;
}

.user-auth-card input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.user-auth-card .btn-login, .user-auth-card .btn-register, .user-auth-card .btn-confirm {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.user-auth-card .btn-login:hover, .user-auth-card .btn-register:hover, .user-auth-card .btn-confirm:hover {
    background-color: #0056b3;
}

.logged-in-message h3 {
    color: #4caf50;
    font-size: 18px;
    margin-bottom: 10px;
}
.booking-summary-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #000;
}

.booking-card, .user-auth-card {
    width: 48%;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
   background-color: #000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.booking-card h2, .user-auth-card h3 {
    margin-top: 0;
    color: #f6f6f6;
}

.booking-card p {
    margin: 5px 0;
    font-size: 16px;
}

.user-auth-card .login-form, .user-auth-card .register-form {
    margin-top: 15px;
}

.user-auth-card label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 14px;
}

.user-auth-card input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    background-color: #f6f6f6;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.user-auth-card input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    outline: none;
    background-color: #fff;
}

.user-auth-card .btn-login, .user-auth-card .btn-register, .user-auth-card .btn-confirm {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #000;
    color: #f6f6f6;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.user-auth-card .btn-login:hover, .user-auth-card .btn-register:hover, .user-auth-card .btn-confirm:hover {
    background-color: #0056b3;
}

.logged-in-message h3 {
    color: #4caf50;
    font-size: 18px;
    margin-bottom: 10px;
}

</style>