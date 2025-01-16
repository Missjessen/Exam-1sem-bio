<div class="form-container">
    <h1><?= $pageTitle ?></h1>
    <form method="POST" action="<?= $formAction ?>">
        <label for="name" <?= $showNameField ? '' : 'style="display:none;"' ?>>Navn:</label>
        <input type="text" id="name" name="name" placeholder="Dit navn" <?= $showNameField ? 'required' : 'style="display:none;"' ?>>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" placeholder="Din e-mail" required>

        <label for="password">Adgangskode:</label>
        <input type="password" id="password" name="password" placeholder="Din adgangskode" required>

        <button type="submit"><?= $buttonText ?></button>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
</div>


<style>body {
    font-family: Arial, sans-serif;
    background-color: #000;
    color: #f6f6f6;
    margin: 0;
    padding: 0;
}

.form-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #1a1a1a;
    color: #f6f6f6;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

.form-container h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #f39c12;
}

.form-container label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #f6f6f6;
}

.form-container input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    color: #000;
    font-size: 1rem;
}

.form-container input::placeholder {
    color: #aaa;
}

.form-container input:focus {
    border-color: #f39c12;
    outline: none;
    background-color: #fef5e7;
    color: #333;
}

.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #f39c12;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1rem;
}

.form-container button:hover {
    background-color: #e67e22;
}

.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>