<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="CSS/header.css">
<header class="header">
    <a href="index.php">
        <img id="logo" src="IMG/cover.png" alt="Logo">
    </a>
    <div class="header-buttons">
        <a href="login.php" class="btn register">Login / Registrati</a>
    </div>
</header>