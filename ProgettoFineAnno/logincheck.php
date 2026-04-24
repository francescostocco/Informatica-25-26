<?php
ob_start();

// Partenza sessione se non ancora avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Richiede il file connect.php per connessione al database
require 'PHP/connect.php';

// Salvo nelle variabili la mail e la password inserite dall'utente
$email = $_POST['email'] ?? '';
$passwordInserita = $_POST['password'] ?? '';

// Query che cerca nel database utenti con mail uguale a quella inserita dall'utente 
$sql = "SELECT IdUtente, Nome, Cognome, PasswordUtente
        FROM Utenti
        WHERE Email = :email
        LIMIT 1";

// Esegue tutta la query
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Crea array 
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

// Se non trova utente manda alla pagina login con errore 1
if (!$utente) {
    header("Location: login.php?err=1");
    exit;
}

// Se password inserita è diversa dalla password dell'utente manda alla pagina login con errore 2
if ($passwordInserita !== $utente['PasswordUtente']) {
    header("Location: login.php?err=2");
    exit;
}

// Salva i dati dell'utente nella sessione
$_SESSION['IdUtente'] = $utente['IdUtente'];
$_SESSION['Nome'] = $utente['Nome'];
$_SESSION['Cognome'] = $utente['Cognome'];
$_SESSION['loggato'] = true;

// Una volta loggato rimanda alla pagina iniziale/principale del sito
header("Location: index.php");
exit;
?>