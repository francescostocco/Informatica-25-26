<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['IdUtente']) ||
    empty($_SESSION['ruolo']) ||
    $_SESSION['ruolo'] !== 'proprietario'
) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$codStruttura = $_GET['id'] ?? '';

if ($codStruttura === '') {
    header("Location: ../ownerpages/owneraccount.php");
    exit;
}

/* Recupera proprietario */
$sqlProp = "SELECT IdProprietario
            FROM Proprietari
            WHERE IdUtente = :idUtente
            LIMIT 1";

$stmtProp = $conn->prepare($sqlProp);
$stmtProp->bindParam(':idUtente', $_SESSION['IdUtente']);
$stmtProp->execute();

$proprietario = $stmtProp->fetch(PDO::FETCH_ASSOC);

if (!$proprietario) {
    header("Location: ../login.php");
    exit;
}

$idProprietario = $proprietario['IdProprietario'];

/* Controlla che la struttura appartenga al proprietario */
$check = $conn->prepare("SELECT CodStruttura
                         FROM Strutture
                         WHERE CodStruttura = :codStruttura
                         AND IdProprietario = :idProprietario
                         LIMIT 1");

$check->bindParam(':codStruttura', $codStruttura);
$check->bindParam(':idProprietario', $idProprietario);
$check->execute();

if (!$check->fetch()) {
    header("Location: ../ownerpages/owneraccount.php");
    exit;
}

/* Elimina dati collegati */

$tables = [
    "FotoStrutture",
    "Recensioni",
    "Alberghi",
    "BnB",
    "CaseVacanze"
];

foreach ($tables as $table) {
    $sqlDelete = "DELETE FROM $table WHERE CodStruttura = :codStruttura";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':codStruttura', $codStruttura);
    $stmtDelete->execute();
}

/* Elimina struttura */
$sqlStruttura = "DELETE FROM Strutture
                 WHERE CodStruttura = :codStruttura";

$stmtStruttura = $conn->prepare($sqlStruttura);
$stmtStruttura->bindParam(':codStruttura', $codStruttura);
$stmtStruttura->execute();

header("Location: ../ownerpages/owneraccount.php");
exit;
?>