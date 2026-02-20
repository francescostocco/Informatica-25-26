<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="CSS/header.css">
<header class="header">
    <img id="logo" src="IMG/cover.jpg" alt="Logo">
    <div class="header-buttons">
        <a class="btn login" href="login.php">Login</a>
        <a class="btn register" href="register.php">Registrati</a>
    </div>
</header>