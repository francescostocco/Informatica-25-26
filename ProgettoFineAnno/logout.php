<?php
// Avvia la sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cancella tutte le variabili di sessione
session_unset();

// Distrugge la sessione
session_destroy();

// Torna alla homepage
header("Location: index.php");
exit;
?>