<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$json = file_get_contents(__DIR__ . '/pages.json');
$obj = json_decode($json);

$pageName = basename($_SERVER['PHP_SELF']);

if (strpos($_SERVER['PHP_SELF'], '/adminpages/') !== false) {
    return;
}

/* Percorso base del progetto */
$basePath = "/ProgettoFineAnno/";

/* Se la pagina fa parte delle actionpages, non viene stampato header */
if (in_array($pageName, $obj->noHeaderPages ?? [])) {
    return;
}

/* Se la pagina richiede login e non sei loggato ti rimanda al login */
if (in_array($pageName, $obj->protectedPages ?? []) && empty($_SESSION['loggato'])) {
    header("Location: " . $basePath . "login.php");
    exit;
}

/* Se la pagina fa parte delle DBPages */
if (in_array($pageName, $obj->dbPages ?? [])) {
    require_once __DIR__ . '/connect.php';
}

/* Header in base al ruolo di chi si regsitra: utente, proprietario, amministratore */
if (!empty($_SESSION['loggato'])) {
    if (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') {
        require __DIR__ . '/header_owner.php';
    } else {
        require __DIR__ . '/header_logged.php';
    }
} else {
    require __DIR__ . '/header.php';
}