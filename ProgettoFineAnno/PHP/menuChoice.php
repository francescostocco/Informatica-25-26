<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Legge il file json
$json = file_get_contents(__DIR__ . '/pages.json');
$obj = json_decode($json);

// Nome della pagina in cui si trova l'utente
$pageName = basename($_SERVER['PHP_SELF']);

/* Se la pagina è un'azione php, non stampare nulla */
if (in_array($pageName, $obj->noHeaderPages ?? [])) {
    return;
}

/* Se la pagina richiede login e non sei loggato ti manda al login */
if (in_array($pageName, $obj->protectedPages ?? []) && empty($_SESSION['loggato'])) {
    header("Location: login.php");
    exit;
}

/* Se serve DB, includi connessione */
if (in_array($pageName, $obj->dbPages ?? [])) {
    require_once __DIR__ . '/connect.php';
}

/* Header in base al ruolo */
if (!empty($_SESSION['loggato'])) {
    if (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') {
        require __DIR__ . '/header_owner.php';
    } else {
        require __DIR__ . '/header_logged.php';
    }
} else {
    require __DIR__ . '/header.php';
}