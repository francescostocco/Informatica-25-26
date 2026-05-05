<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente'])) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$idUtente = $_SESSION['IdUtente'];

$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$dataNascita = $_POST['dataNascita'] ?? '';

if ($nome === '' || $cognome === '' || $dataNascita === '') {
    header("Location: account.php?err=1");
    exit;
}

$sql = "UPDATE Utenti
        SET Nome = :nome,
            Cognome = :cognome,
            DataNascita = :dataNascita
        WHERE IdUtente = :idUtente";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cognome', $cognome);
$stmt->bindParam(':dataNascita', $dataNascita);
$stmt->bindParam(':idUtente', $idUtente);

$stmt->execute();

$_SESSION['Nome'] = $nome;
$_SESSION['Cognome'] = $cognome;

header("Location: ../index.php");
exit;
?>