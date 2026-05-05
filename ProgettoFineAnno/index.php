<?php
require __DIR__ . '/include/connect.php';

$sql = "SELECT 
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
        LEFT JOIN FotoStrutture F ON S.CodStruttura = F.CodStruttura
        LEFT JOIN Alberghi A ON S.CodStruttura = A.CodStruttura
        LEFT JOIN BnB B ON S.CodStruttura = B.CodStruttura
        LEFT JOIN CaseVacanze C ON S.CodStruttura = C.CodStruttura
        GROUP BY S.CodStruttura
        ORDER BY S.CodStruttura DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$strutture = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../IMG/favicon.ico">
    <link rel="stylesheet" href="CSS/index.css">
    <title>Homepage - OpinioniVacanze</title>
</head>
<body class="<?php echo (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') ? 'owner-theme' : ''; ?>">
    <main class="main-content">
    <h1>Opinioni Vacanze</h1>

    <div class="search-bar">
        <input type="text" placeholder="Cerca strutture...">
        <button><i class="fas fa-magnifying-glass"></i></button>
    </div>

    <!-- Sezione per vedere tutte le strutture -->
    <div class="home-structures">

        <h2>Strutture disponibili</h2>

        <div class="structures-grid">

            <?php foreach ($strutture as $struttura): ?>
                
                <a href="structurepages/struttura.php?id=<?php echo $struttura['CodStruttura']; ?>" class="structure-card">

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

                </a>

            <?php endforeach; ?>

            </div>
        </div>

    </main>
</body>
</html> 