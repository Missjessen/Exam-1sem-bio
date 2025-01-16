<h1>Login</h1>
<form method="POST" action="<?= BASE_URL ?>index.php?page=login">
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Log ind</button>
</form>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<style>.register-form {
    max-width: 400px !important;
    margin: 50px auto !important;
    padding: 20px !important;
    background-color: #f9f9f9 !important; /* Lys baggrund */
    color: #333 !important; /* Mørk tekst */
    border-radius: 8px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4) !important;
}

.register-form h1 {
    text-align: center !important;
    margin-bottom: 20px !important;
    color: #f39c12 !important;
}

.register-form label {
    display: block !important;
    margin: 10px 0 5px !important;
    font-weight: bold !important;
    color: #333 !important; /* Sørger for at labels er læsbare */
}

.register-form input {
    width: 100% !important;
    padding: 10px !important;
    margin-bottom: 15px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    background-color: #fff !important; /* Lys baggrund */
    color: #000 !important; /* Mørk tekst for høj kontrast */
    font-size: 1rem !important;
}

.register-form input::placeholder {
    color: #aaa !important; /* Lysere tekst til placeholder */
}

.register-form input:focus {
    border-color: #f39c12 !important; /* Fremhæv inputfelt ved fokus */
    outline: none !important;
    background-color: #fef5e7 !important; /* Lys fremhævning ved fokus */
    color: #333 !important; /* Tekstfarve forbliver læsbar */
}

.register-form button {
    width: 100% !important;
    padding: 10px !important;
    background-color: #f39c12 !important;
    color: #fff !important;
    border: none !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    font-weight: bold !important;
    font-size: 1rem !important;
}

.register-form button:hover {
    background-color: #e67e22 !important;
}

.register-form .error {
    color: red !important;
    text-align: center !important;
    margin-bottom: 10px !important;
}
</style>