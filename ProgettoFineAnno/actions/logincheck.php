<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../include/connect.php';

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
    header("Location: ../login.php?err=1");
    exit;
}

if ($passwordInserita !== $utente['PasswordUtente']) {
    header("Location: ../login.php?err=2");
    exit;
}

$_SESSION['IdUtente'] = $utente['IdUtente'];
$_SESSION['Nome'] = $utente['Nome'];
$_SESSION['Cognome'] = $utente['Cognome'];
$_SESSION['loggato'] = true;

/* Controllo se l'utente è amministratore */
$checkAdmin = $conn->prepare("SELECT IdUtente FROM Amministratori WHERE IdUtente = :idUtente LIMIT 1");
$checkAdmin->bindParam(':idUtente', $utente['IdUtente']);
$checkAdmin->execute();

if ($checkAdmin->fetch()) {
    $_SESSION['admin_da_verificare'] = true;
    header("Location: ../adminpages/admincode.php");
    exit;
}

/* Se non è admin entra come utente normale */
$_SESSION['ruolo'] = 'utente';

header("Location: ../index.php");
exit;
?>