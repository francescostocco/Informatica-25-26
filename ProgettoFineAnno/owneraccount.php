<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['IdUtente']) ||
    empty($_SESSION['ruolo']) ||
    $_SESSION['ruolo'] !== 'proprietario'
) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/PHP/connect.php';

$idUtente = $_SESSION['IdUtente'];

/* DATI PROPRIETARIO */
$sql = "SELECT U.Email, P.IdProprietario, P.`NomeAttività`, P.SedeLegale, P.PartitaIVA, P.Telefono
        FROM Utenti U
        INNER JOIN Proprietari P ON U.IdUtente = P.IdUtente
        WHERE U.IdUtente = :idUtente
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idUtente', $idUtente);
$stmt->execute();

$proprietario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proprietario) {
    header("Location: login.php");
    exit;
}

$idProprietario = $proprietario['IdProprietario'];

/* STRUTTURE DEL PROPRIETARIO + PRIMA FOTO */
$sqlStrutture = "SELECT 
                    S.CodStruttura,
                    S.NomeStruttura,
                    S.`Città`,
                    F.UrlFoto,
                    CASE
                        WHEN A.CodStruttura IS NOT NULL THEN 'Albergo'
                        WHEN B.CodStruttura IS NOT NULL THEN 'B&B'
                        WHEN C.CodStruttura IS NOT NULL THEN 'Casa vacanze'
                        ELSE 'Struttura'
                    END AS Tipologia
                 FROM Strutture S
                 LEFT JOIN (
                    SELECT CodStruttura, MIN(IdFoto) AS PrimaFoto
                    FROM FotoStrutture
                    GROUP BY CodStruttura
                 ) FP ON S.CodStruttura = FP.CodStruttura
                 LEFT JOIN FotoStrutture F ON FP.PrimaFoto = F.IdFoto
                 LEFT JOIN Alberghi A ON S.CodStruttura = A.CodStruttura
                 LEFT JOIN BnB B ON S.CodStruttura = B.CodStruttura
                 LEFT JOIN CaseVacanze C ON S.CodStruttura = C.CodStruttura
                 WHERE S.IdProprietario = :idProprietario
                 ORDER BY S.CodStruttura DESC";

$stmtStrutture = $conn->prepare($sqlStrutture);
$stmtStrutture->bindParam(':idProprietario', $idProprietario);
$stmtStrutture->execute();

$strutture = $stmtStrutture->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Proprietario - OpinioniVacanze</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/owner.css">
</head>
<body class="owner-theme">

<div class="owner-dashboard">
    <aside class="owner-sidebar">
        <div class="owner-profile-card">
            <div class="owner-profile-top">
                <div class="owner-avatar">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <h2>Area Proprietario</h2>
                    <p>Gestisci le tue strutture</p>
                </div>
            </div>

            <div class="owner-profile-info">
                <div class="info-row">
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($proprietario['Email']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Nome attività</span>
                    <strong><?php echo htmlspecialchars($proprietario['NomeAttività']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Sede legale</span>
                    <strong><?php echo htmlspecialchars($proprietario['SedeLegale']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Partita IVA</span>
                    <strong><?php echo htmlspecialchars($proprietario['PartitaIVA']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Telefono</span>
                    <strong><?php echo htmlspecialchars($proprietario['Telefono']); ?></strong>
                </div>
            </div>
        </div>
    </aside>

    <main class="owner-main">
        <div class="owner-main-header">
            <div>
                <h1>Le tue strutture</h1>
                <p>Qui trovi tutte le strutture che hai inserito.</p>
            </div>
            <a href="addstruttura.php" class="add-structure-btn">
                <i class="fa-solid fa-plus"></i>
                Aggiungi struttura
            </a>
        </div>

        <?php if (count($strutture) > 0): ?>
            <div class="structures-grid">
                <?php foreach ($strutture as $struttura): ?>
                    <div class="structure-card">
                        <div class="structure-image">
                            <?php if (!empty($struttura['UrlFoto'])): ?>
                                <img src="<?php echo htmlspecialchars($struttura['UrlFoto']); ?>" alt="Foto struttura">
                            <?php else: ?>
                                <div class="structure-placeholder">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="structure-body">
                            <span class="structure-type"><?php echo htmlspecialchars($struttura['Tipologia']); ?></span>
                            <h3><?php echo htmlspecialchars($struttura['NomeStruttura']); ?></h3>
                            <p><?php echo htmlspecialchars($struttura['Città']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-structures">
                <i class="fa-regular fa-folder-open"></i>
                <h3>Non hai ancora inserito strutture</h3>
                <p>Inizia aggiungendo la tua prima struttura.</p>
                <a href="addstruttura.php" class="add-structure-btn">Aggiungi struttura</a>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>