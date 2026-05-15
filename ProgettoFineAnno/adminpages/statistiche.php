<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Utenti");
$stmt->execute();
$totaleUtenti = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Proprietari");
$stmt->execute();
$totaleProprietari = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Strutture");
$stmt->execute();
$totaleStrutture = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Recensioni");
$stmt->execute();
$totaleRecensioni = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$sqlMigliore = "SELECT S.NomeStruttura, AVG(R.NumStelle) AS mediaStelle FROM Recensioni R
                INNER JOIN Strutture S ON R.CodStruttura = S.CodStruttura GROUP BY S.CodStruttura ORDER BY mediaStelle DESC LIMIT 1";

$stmt = $conn->prepare($sqlMigliore);
$stmt->execute();
$migliore = $stmt->fetch(PDO::FETCH_ASSOC);

/* Prendo struttura con più recensioni */
$sqlPiuRecensita = "SELECT S.NomeStruttura, COUNT(R.IdRecensione) AS numeroRecensioni FROM Recensioni R
                    INNER JOIN Strutture S ON R.CodStruttura = S.CodStruttura GROUP BY S.CodStruttura ORDER BY numeroRecensioni DESC LIMIT 1";

$stmt = $conn->prepare($sqlPiuRecensita);
$stmt->execute();
$piuRecensita = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Statistiche - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h2>Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="strutture.php">Gestione strutture</a>
        <a href="recensioni.php">Gestione recensioni</a>
        <a href="statistiche.php">Statistiche</a>
        <a href="../actions/logout.php" class="danger">Logout</a>
    </aside>

    <main class="admin-main">
        <h1>Statistiche</h1>
        <p>Panoramica generale dei dati presenti nel sito.</p>

        <div class="admin-cards">
            <div class="admin-card">
                <h3>Utenti</h3>
                <p><?php echo $totaleUtenti; ?> utenti registrati</p>
            </div>

            <div class="admin-card">
                <h3>Proprietari</h3>
                <p><?php echo $totaleProprietari; ?> proprietari registrati</p>
            </div>

            <div class="admin-card">
                <h3>Strutture</h3>
                <p><?php echo $totaleStrutture; ?> strutture inserite</p>
            </div>

            <div class="admin-card">
                <h3>Recensioni</h3>
                <p><?php echo $totaleRecensioni; ?> recensioni pubblicate</p>
            </div>

            <div class="admin-card">
                <h3>Valutazione migliore</h3>
                <?php if ($migliore): ?>
                    <p>
                        <?php echo htmlspecialchars($migliore['NomeStruttura']); ?><br>
                        Media: <?php echo round($migliore['mediaStelle'], 1); ?> ★
                    </p>
                <?php else: ?>
                    <p>Nessuna recensione disponibile.</p>
                <?php endif; ?>
            </div>

            <div class="admin-card">
                <h3>Più recensita</h3>
                <?php if ($piuRecensita): ?>
                    <p>
                        <?php echo htmlspecialchars($piuRecensita['NomeStruttura']); ?><br>
                        <?php echo $piuRecensita['numeroRecensioni']; ?> recensioni
                    </p>
                <?php else: ?>
                    <p>Nessuna recensione disponibile.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

</body>
</html>