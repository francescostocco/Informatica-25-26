<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente']) || empty($_SESSION['loggato']) || ($_SESSION['ruolo'] ?? '') !== 'utente') {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$idUtente = $_SESSION['IdUtente'];
$codStruttura = $_POST['codStruttura'] ?? '';
$titolo = trim($_POST['titolo'] ?? '');
$voto = $_POST['voto'] ?? '';
$testo = trim($_POST['testo'] ?? '');

if ($codStruttura === '' || $titolo === '' || $voto === '' || $testo === '') {
    header("Location: ../structurepages/struttura.php?id=" . $codStruttura . "&err=recensione");
    exit;
}

if ($voto < 1 || $voto > 5) {
    header("Location: ../structurepages/struttura.php?id=" . $codStruttura . "&err=voto");
    exit;
}

$sql = "INSERT INTO Recensioni (IdUtente, CodStruttura, Titolo, Commento, NumStelle)
        VALUES (:idUtente, :codStruttura, :titolo, :commento, :numStelle)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idUtente', $idUtente);
$stmt->bindParam(':codStruttura', $codStruttura);
$stmt->bindParam(':titolo', $titolo);
$stmt->bindParam(':commento', $testo);
$stmt->bindParam(':numStelle', $voto);
$stmt->execute();

header("Location: ../structurepages/struttura.php?id=" . $codStruttura . "&ok=recensione");
exit;
?>