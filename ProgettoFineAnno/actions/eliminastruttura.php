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

$codStruttura = $_GET['id'] ?? '';

if ($codStruttura === '') {
    header("Location: ../adminpages/strutture.php?err=1");
    exit;
}

try {
    $conn->beginTransaction();

    /* Recupero foto per eliminarle anche dalla cartella */
    $sqlFotoSelect = "SELECT UrlFoto FROM FotoStrutture WHERE CodStruttura = :codStruttura";
    $stmtFotoSelect = $conn->prepare($sqlFotoSelect);
    $stmtFotoSelect->bindParam(':codStruttura', $codStruttura);
    $stmtFotoSelect->execute();
    $foto = $stmtFotoSelect->fetchAll(PDO::FETCH_ASSOC);

    /* Elimina recensioni */
    $sql = "DELETE FROM Recensioni WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    /* Elimina foto dal database */
    $sql = "DELETE FROM FotoStrutture WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    /* Elimina dati specifici */
    $sql = "DELETE FROM Alberghi WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    $sql = "DELETE FROM BnB WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    $sql = "DELETE FROM CaseVacanze WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    /* Elimina struttura principale */
    $sql = "DELETE FROM Strutture WHERE CodStruttura = :codStruttura";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codStruttura', $codStruttura);
    $stmt->execute();

    $conn->commit();

    /* Elimina fisicamente le immagini dalla cartella */
    foreach ($foto as $immagine) {
        $percorsoFile = __DIR__ . '/../' . $immagine['UrlFoto'];

        if (file_exists($percorsoFile)) {
            unlink($percorsoFile);
        }
    }

    header("Location: ../adminpages/strutture.php?ok=1");
    exit;

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo "Errore durante l'eliminazione della struttura: " . $e->getMessage();
}
?>