<div class="admin-login">
    <h1>Registrering</h1>
    <form method="POST" action="<?= htmlspecialchars(BASE_URL . 'index.php?page=register') ?>">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" placeholder="Indtast dit navn" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" placeholder="Indtast din e-mail" required>

        <label for="password">Adgangskode:</label>
        <input type="password" id="password" name="password" placeholder="Vælg en adgangskode" required>

        <button type="submit">Registrer</button>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
</div>


<style>body {
    font-family: Arial, sans-serif;
    background-color: #000; /* Mørk baggrund */
    color: #f6f6f6; /* Lys tekst */
    margin: 0;
    padding: 0;
}

.admin-login {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #1a1a1a; /* Mørk grå */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    text-align: center;
}

.admin-login h1 {
    margin-bottom: 20px;
    color: #f39c12; /* Fremhæv med orange */
}

.admin-login label {
    display: block;
    margin-bottom: 10px;
    text-align: left;
    font-weight: bold;
    color: #f6f6f6;
}

.admin-login input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff; /* Lys baggrund */
    color: #000; /* Mørk tekst */
}

.admin-login input:focus {
    border-color: #f39c12;
    outline: none;
}

.admin-login button {
    width: 100%;
    padding: 10px;
    background-color: #f39c12;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.admin-login button:hover {
    background-color: #e67e22;
}

.error-message {
    color: red;
    margin-top: 10px;
    font-weight: bold;
}
</style>