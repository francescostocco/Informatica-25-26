<?php
ob_start(); // evita "headers already sent" se viene stampato qualcosa prima

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'connect.php';

$email = $_POST['email'] ?? '';
$passwordInserita = $_POST['password'] ?? '';

$sql = "SELECT IdUtente, Nome, Cognome, PasswordUtente
        FROM Utenti
        WHERE Email = :email
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utente) {
    // email non trovata
    header("Location: login.php?err=1");
    exit;
}

// Confronto password (per ora in chiaro, coerente con VARCHAR(15))
if ($passwordInserita !== $utente['PasswordUtente']) {
    header("Location: login.php?err=2");
    exit;
}

// Login OK
$_SESSION['IdUtente'] = $utente['IdUtente'];
$_SESSION['Nome'] = $utente['Nome'];
$_SESSION['Cognome'] = $utente['Cognome'];
$_SESSION['loggato'] = true;

header("Location: index.php");
exit;
?>