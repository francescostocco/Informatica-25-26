<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$sql = "SELECT 
            R.IdRecensione,
            R.Titolo,
            R.Commento,
            R.NumStelle,
            U.Email,
            S.NomeStruttura
        FROM Recensioni R
        INNER JOIN Utenti U ON R.IdUtente = U.IdUtente
        INNER JOIN Strutture S ON R.CodStruttura = S.CodStruttura
        ORDER BY R.IdRecensione DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione recensioni - Admin</title>
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
        <h1>Gestione recensioni</h1>
        <p>Visualizza ed elimina le recensioni pubblicate dagli utenti.</p>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Struttura</th>
                        <th>Utente</th>
                        <th>Titolo</th>
                        <th>Stelle</th>
                        <th>Commento</th>
                        <th>Azione</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($recensioni as $recensione): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($recensione['IdRecensione']); ?></td>
                            <td><?php echo htmlspecialchars($recensione['NomeStruttura']); ?></td>
                            <td><?php echo htmlspecialchars($recensione['Email']); ?></td>
                            <td><?php echo htmlspecialchars($recensione['Titolo']); ?></td>
                            <td><?php echo htmlspecialchars($recensione['NumStelle']); ?> ★</td>
                            <td><?php echo htmlspecialchars($recensione['Commento']); ?></td>
                            <td>
                                <a 
                                    class="admin-delete-btn"
                                    href="../actions/eliminarecensione.php?id=<?php echo $recensione['IdRecensione']; ?>"
                                    onclick="return confirm('Sei sicuro di voler eliminare questa recensione?');"
                                >
                                    Elimina
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($recensioni) === 0): ?>
                        <tr>
                            <td colspan="7">Nessuna recensione presente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>