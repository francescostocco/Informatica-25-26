<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente']) || empty($_SESSION['admin_da_verificare'])) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$idUtente = $_SESSION['IdUtente'];
$codiceAccesso = trim($_POST['codiceAccesso'] ?? '');

if ($codiceAccesso === '') {
    header("Location: ../adminpages/admincode.php?err=1");
    exit;
}

$sql = "SELECT IdUtente FROM Amministratori WHERE IdUtente = :idUtente AND CodiceAccesso = :codiceAccesso LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idUtente', $idUtente);
$stmt->bindParam(':codiceAccesso', $codiceAccesso);
$stmt->execute();

if (!$stmt->fetch()) {
    header("Location: ../adminpages/admincode.php?err=2");
    exit;
}

unset($_SESSION['admin_da_verificare']);

$_SESSION['ruolo'] = 'admin';

header("Location: ../adminpages/dashboard.php");
exit;
?>