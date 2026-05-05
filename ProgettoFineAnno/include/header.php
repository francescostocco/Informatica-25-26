<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="/ProgettoFineAnno/CSS/header.css">

<header class="header">
    <a href="/ProgettoFineAnno/index.php">
        <img id="logo" src="/ProgettoFineAnno/IMG/cover.png" alt="Logo">
    </a>

    <div class="header-buttons">
        <a href="/ProgettoFineAnno/login.php" class="btn register">Inizia ora!</a>
    </div>
</header>