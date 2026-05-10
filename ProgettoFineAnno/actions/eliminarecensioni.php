<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$idRecensione = $_GET['id'] ?? '';

if ($idRecensione === '') {
    header("Location: ../adminpages/recensioni.php?err=1");
    exit;
}

$sql = "DELETE FROM Recensioni
        WHERE IdRecensione = :idRecensione";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idRecensione', $idRecensione);
$stmt->execute();

header("Location: ../adminpages/recensioni.php?ok=1");
exit;
?>