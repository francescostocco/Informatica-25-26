<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../include/connect.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$nomeAttivita = trim($_POST['nomeAttivita'] ?? '');
$sedeLegale = trim($_POST['sedeLegale'] ?? '');
$partitaIVA = trim($_POST['partitaIVA'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

if ($email === '' || $password === '' || $confirmPassword === '' || $nomeAttivita === '' || $sedeLegale === '' || $partitaIVA === '' || $telefono === '') {
    header("Location: ../ownerpages/ownerlogin.php?err=1");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../ownerpages/ownerlogin.php?err=2");
    exit;
}

if ($password !== $confirmPassword) {
    header("Location: ../ownerpages/ownerlogin.php?err=3");
    exit;
}

$check = $conn->prepare("SELECT IdUtente FROM Utenti WHERE Email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header("Location: ../ownerpages/ownerlogin.php?err=4");
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$nome = null;
$cognome = null;
$dataNascita = null;

$sqlUtente = "INSERT INTO Utenti (Nome, Cognome, DataNascita, Email, PasswordUtente)
              VALUES (:nome, :cognome, :dataNascita, :email, :password)";

$stmtUtente = $conn->prepare($sqlUtente);
$stmtUtente->bindParam(':nome', $nome);
$stmtUtente->bindParam(':cognome', $cognome);
$stmtUtente->bindParam(':dataNascita', $dataNascita);
$stmtUtente->bindParam(':email', $email);
$stmtUtente->bindParam(':password', $passwordHash);
$stmtUtente->execute();

$idUtente = $conn->lastInsertId();

$sqlProp = "INSERT INTO Proprietari (IdUtente, NomeAttività, SedeLegale, PartitaIVA, Telefono)
            VALUES (:idUtente, :nomeAttivita, :sedeLegale, :partitaIVA, :telefono)";

$stmtProp = $conn->prepare($sqlProp);
$stmtProp->bindParam(':idUtente', $idUtente);
$stmtProp->bindParam(':nomeAttivita', $nomeAttivita);
$stmtProp->bindParam(':sedeLegale', $sedeLegale);
$stmtProp->bindParam(':partitaIVA', $partitaIVA);
$stmtProp->bindParam(':telefono', $telefono);
$stmtProp->execute();

$_SESSION['IdUtente'] = $idUtente;
$_SESSION['Email'] = $email;
$_SESSION['loggato'] = true;
$_SESSION['ruolo'] = 'proprietario';

header("Location: ../ownerpages/owneraccount.php");
exit;
?>