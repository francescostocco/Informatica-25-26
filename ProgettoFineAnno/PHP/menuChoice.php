<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageName = basename($_SERVER['PHP_SELF']);

$noHeaderPages = [
    'logincheck.php',
    'registercheck.php',
    'logout.php'
];

if (!in_array($pageName, $noHeaderPages)) {

    if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === true) {
        require __DIR__ . '/header_logged.php';
    } else {
        require __DIR__ . '/header.php';
    }
}