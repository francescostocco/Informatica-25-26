<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
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
        <a href="statistiche.php">Statistiche</a>
        <a href="../actions/logout.php" class="danger">Logout</a>
    </aside>

    <main class="admin-main">
        <h1>Pannello amministratore</h1>
        <p>Benvenuto nell’area admin di OpinioniVacanze.</p>
    </main>
</div>

</body>
</html>