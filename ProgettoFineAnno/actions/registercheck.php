<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/include/connect.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Controllo campi vuoti
if ($email === '' || $password === '' || $confirmPassword === '') {
    header("Location: login.php?err=1");
    exit;
}

// Controllo email valida
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?err=2");
    exit;
}

// Controllo password uguali
if ($password !== $confirmPassword) {
    header("Location: login.php?err=3");
    exit;
}

// Controllo se email già esistente
$check = $conn->prepare("SELECT IdUtente FROM Utenti WHERE Email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header("Location: login.php?err=4");
    exit;
}

// Inserimento utente con dati mancanti vuoti
$sql = "INSERT INTO Utenti (Nome, Cognome, DataNascita, Email, PasswordUtente)
        VALUES (:nome, :cognome, :dataNascita, :email, :password)";

$nome = '';
$cognome = '';
$dataNascita = null;

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cognome', $cognome);
$stmt->bindParam(':dataNascita', $dataNascita);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);

$stmt->execute();

// Login automatico
$_SESSION['IdUtente'] = $conn->lastInsertId();
$_SESSION['Nome'] = $nome;
$_SESSION['Cognome'] = $cognome;
$_SESSION['loggato'] = true;

header("Location: account.php");
exit;
?>