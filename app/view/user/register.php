<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?page=register">
    <label for="name">Navn:</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Adgangskode:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Registrer</button>
</form>


<script>
    document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        e.preventDefault(); // Stop formularen fra at blive sendt
        alert('Adgangskoderne matcher ikke. Prøv igen.');
    }
});

</script>