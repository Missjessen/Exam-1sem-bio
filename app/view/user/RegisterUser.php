<form method="POST" action="?page=register">
    <h2>Opret Bruger</h2>
    <label for="username">Brugernavn:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Adgangskode:</label>
    <input type="password" name="password" id="password" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <button type="submit">Opret</button>
</form>

<form method="POST" action="?page=login">
    <h2>Login</h2>
    <label for="username">Brugernavn:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Adgangskode:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Login</button>
</form>
