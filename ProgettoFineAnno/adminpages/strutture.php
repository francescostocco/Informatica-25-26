<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$sql = "SELECT S.CodStruttura, S.NomeStruttura, S.`Città`, U.Email, P.`NomeAttività`,
        CASE
            WHEN A.CodStruttura IS NOT NULL THEN 'Albergo'
            WHEN B.CodStruttura IS NOT NULL THEN 'B&B'
            ELSE 'Casa vacanze'
        END AS Tipologia
        FROM Strutture S
        INNER JOIN Proprietari P ON S.IdProprietario = P.IdProprietario
        INNER JOIN Utenti U ON P.IdUtente = U.IdUtente
        LEFT JOIN Alberghi A ON S.CodStruttura = A.CodStruttura
        LEFT JOIN BnB B ON S.CodStruttura = B.CodStruttura
        LEFT JOIN CaseVacanze C ON S.CodStruttura = C.CodStruttura
        ORDER BY S.CodStruttura DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$strutture = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione strutture - Admin</title>
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
        <h1>Gestione strutture</h1>
        <p>Visualizza ed elimina le strutture presenti nel sito.</p>

        <div class="admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome struttura</th>
                        <th>Città</th>
                        <th>Tipologia</th>
                        <th>Proprietario</th>
                        <th>Email</th>
                        <th>Azione</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($strutture as $struttura): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($struttura['CodStruttura']); ?></td>
                            <td><?php echo htmlspecialchars($struttura['NomeStruttura']); ?></td>
                            <td><?php echo htmlspecialchars($struttura['Città']); ?></td>
                            <td><?php echo htmlspecialchars($struttura['Tipologia']); ?></td>
                            <td><?php echo htmlspecialchars($struttura['NomeAttività']); ?></td>
                            <td><?php echo htmlspecialchars($struttura['Email']); ?></td>
                            <td>
                                <a 
                                    class="admin-delete-btn"
                                    href="../actions/eliminastruttura.php?id=<?php echo $struttura['CodStruttura']; ?>"
                                    onclick="return confirm('Sei sicuro di voler eliminare questa struttura? Verranno eliminate anche foto e recensioni.');"
                                >
                                    Elimina
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($strutture) === 0): ?>
                        <tr>
                            <td colspan="7">Nessuna struttura presente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>