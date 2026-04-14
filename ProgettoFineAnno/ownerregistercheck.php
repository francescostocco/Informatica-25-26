<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/PHP/connect.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$nomeAttivita = trim($_POST['nomeAttivita'] ?? '');
$sedeLegale = trim($_POST['sedeLegale'] ?? '');
$partitaIVA = trim($_POST['partitaIVA'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

// Controllo campi vuoti
if (
    $email === '' || $password === '' || $confirmPassword === '' ||
    $nomeAttivita === '' || $sedeLegale === '' || $partitaIVA === '' || $telefono === ''
) {
    header("Location: ownerlogin.php?err=1");
    exit;
}

// Controllo email valida
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ownerlogin.php?err=2");
    exit;
}

// Controllo password uguali
if ($password !== $confirmPassword) {
    header("Location: ownerlogin.php?err=3");
    exit;
}

// Controllo se email già esistente
$check = $conn->prepare("SELECT IdUtente FROM Utenti WHERE Email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header("Location: ownerlogin.php?err=4");
    exit;
}

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Dati utente inizialmente vuoti
$nome = null;
$cognome = null;
$dataNascita = null;

// 1. Inserimento in Utenti
$sqlUtente = "INSERT INTO Utenti (Nome, Cognome, DataNascita, Email, PasswordUtente)
              VALUES (:nome, :cognome, :dataNascita, :email, :password)";

$stmtUtente = $conn->prepare($sqlUtente);
$stmtUtente->bindParam(':nome', $nome);
$stmtUtente->bindParam(':cognome', $cognome);
$stmtUtente->bindParam(':dataNascita', $dataNascita);
$stmtUtente->bindParam(':email', $email);
$stmtUtente->bindParam(':password', $passwordHash);
$stmtUtente->execute();

// Recupero IdUtente appena creato
$idUtente = $conn->lastInsertId();

// 2. Inserimento in Proprietari
$sqlProp = "INSERT INTO Proprietari (IdUtente, NomeAttività, SedeLegale, PartitaIVA, Telefono)
            VALUES (:idUtente, :nomeAttivita, :sedeLegale, :partitaIVA, :telefono)";

$stmtProp = $conn->prepare($sqlProp);
$stmtProp->bindParam(':idUtente', $idUtente);
$stmtProp->bindParam(':nomeAttivita', $nomeAttivita);
$stmtProp->bindParam(':sedeLegale', $sedeLegale);
$stmtProp->bindParam(':partitaIVA', $partitaIVA);
$stmtProp->bindParam(':telefono', $telefono);
$stmtProp->execute();

// Login automatico
$_SESSION['IdUtente'] = $idUtente;
$_SESSION['Email'] = $email;
$_SESSION['loggato'] = true;
$_SESSION['ruolo'] = 'proprietario';

// Reindirizzamento
header("Location: owneraccount.php");
exit;
?>