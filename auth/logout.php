<?php
session_start();

// Slet JWT-tokenet og brugeren fra cookies
setcookie('auth_token', '', time() - 3600, '/', '', true, true); // Slet cookie
setcookie('token_user', '', time() - 3600, '/', '', true, true); // Slet user-cookie

// Omdiriger til login-siden
header("Location: /auth/login.php");
exit();
?>
