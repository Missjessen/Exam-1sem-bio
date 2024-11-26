<?php
// Generér et bcrypt-hash for adgangskoden "testpassword"
$password = 'testpassword';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo $hashedPassword;
