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

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Strutture");
$stmt->execute();
$totaleStrutture = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Recensioni");
$stmt->execute();
$totaleRecensioni = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];

$stmt = $conn->prepare("SELECT COUNT(*) AS totale FROM Proprietari");
$stmt->execute();
$totaleProprietari = $stmt->fetch(PDO::FETCH_ASSOC)['totale'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - OpinioniVacanze</title>
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
        <h1>Pannello amministratore</h1>
        <p>Benvenuto nell’area di gestione del sito OpinioniVacanze.</p>

        <div class="admin-cards">
            <div class="admin-card">
                <h3>Utenti registrati</h3>
                <p><?php echo $totaleUtenti; ?> utenti totali</p>
            </div>

            <div class="admin-card">
                <h3>Proprietari</h3>
                <p><?php echo $totaleProprietari; ?> proprietari totali</p>
            </div>

            <div class="admin-card">
                <h3>Strutture</h3>
                <p><?php echo $totaleStrutture; ?> strutture pubblicate</p>
            </div>

            <div class="admin-card">
                <h3>Recensioni</h3>
                <p><?php echo $totaleRecensioni; ?> recensioni pubblicate</p>
            </div>
        </div>

        <div class="admin-cards">
            <div class="admin-card">
                <h3>Gestione strutture</h3>
                <p>Visualizza ed elimina le strutture inserite dai proprietari.</p>
                <br>
                <a href="strutture.php" class="admin-action-link">Apri gestione</a>
            </div>

            <div class="admin-card">
                <h3>Gestione recensioni</h3>
                <p>Controlla ed elimina le recensioni pubblicate dagli utenti.</p>
                <br>
                <a href="recensioni.php" class="admin-action-link">Apri gestione</a>
            </div>

            <div class="admin-card">
                <h3>Statistiche</h3>
                <p>Consulta i dati principali e le strutture più apprezzate.</p>
                <br>
                <a href="statistiche.php" class="admin-action-link">Vai alle statistiche</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>