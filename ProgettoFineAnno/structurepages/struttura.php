<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../include/connect.php';

$codStruttura = $_GET['id'] ?? '';

if ($codStruttura === '') {
    header("Location: ../index.php");
    exit;
}

/* DATI PRINCIPALI STRUTTURA */
$sqlStruttura = "SELECT S.*, T.`TipoLocalità`
                 FROM Strutture S
                 LEFT JOIN `TipoLocalità` T ON S.`IdTipoLocalità` = T.`IdTipoLocalità`
                 WHERE S.CodStruttura = :codStruttura
                 LIMIT 1";

$stmtStruttura = $conn->prepare($sqlStruttura);
$stmtStruttura->bindParam(':codStruttura', $codStruttura);
$stmtStruttura->execute();

$struttura = $stmtStruttura->fetch(PDO::FETCH_ASSOC);

if (!$struttura) {
    header("Location: ../index.php");
    exit;
}

/* FOTO STRUTTURA */
$sqlFoto = "SELECT UrlFoto
            FROM FotoStrutture
            WHERE CodStruttura = :codStruttura";

$stmtFoto = $conn->prepare($sqlFoto);
$stmtFoto->bindParam(':codStruttura', $codStruttura);
$stmtFoto->execute();

$foto = $stmtFoto->fetchAll(PDO::FETCH_ASSOC);

/* TIPOLOGIA + DETTAGLI SPECIFICI */
$tipologia = 'Struttura';
$dettagli = [];

/* Se è Albergo */
$sqlAlbergo = "SELECT * FROM Alberghi WHERE CodStruttura = :codStruttura LIMIT 1";
$stmtAlbergo = $conn->prepare($sqlAlbergo);
$stmtAlbergo->bindParam(':codStruttura', $codStruttura);
$stmtAlbergo->execute();
$albergo = $stmtAlbergo->fetch(PDO::FETCH_ASSOC);

if ($albergo) {
    $tipologia = 'Albergo';
    $dettagli = $albergo;
} else {
    /* Se è B&B */
    $sqlBnb = "SELECT * FROM BnB WHERE CodStruttura = :codStruttura LIMIT 1";
    $stmtBnb = $conn->prepare($sqlBnb);
    $stmtBnb->bindParam(':codStruttura', $codStruttura);
    $stmtBnb->execute();
    $bnb = $stmtBnb->fetch(PDO::FETCH_ASSOC);

    if ($bnb) {
        $tipologia = 'B&B';
        $dettagli = $bnb;
    } else {
        /* Se è una Casa vacanza */
        $sqlCasa = "SELECT * FROM CaseVacanze WHERE CodStruttura = :codStruttura LIMIT 1";
        $stmtCasa = $conn->prepare($sqlCasa);
        $stmtCasa->bindParam(':codStruttura', $codStruttura);
        $stmtCasa->execute();
        $casa = $stmtCasa->fetch(PDO::FETCH_ASSOC);

        if ($casa) {
            $tipologia = 'Casa vacanze';
            $dettagli = $casa;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($struttura['NomeStruttura']); ?> - OpinioniVacanze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/struttura.css">
</head>
<body class="<?php echo (!empty($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'proprietario') ? 'owner-theme' : ''; ?>">

<div class="structure-page">

    <div class="structure-top">
        <div>
            <span class="structure-badge"><?php echo htmlspecialchars($tipologia); ?></span>
            <h1><?php echo htmlspecialchars($struttura['NomeStruttura']); ?></h1>
            <p class="structure-subtitle">
                <?php echo htmlspecialchars($struttura['Città']); ?>
                <?php if (!empty($struttura['TipoLocalità'])): ?>
                    · <?php echo htmlspecialchars($struttura['TipoLocalità']); ?>
                <?php endif; ?>
            </p>
        </div>

        <a href="javascript:history.back()" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Torna indietro
        </a>
    </div>

    <div class="structure-layout">
        <div class="structure-main">

            <div class="structure-gallery">
                <?php if (count($foto) > 0): ?>
                    <?php foreach ($foto as $immagine): ?>
                        <div class="gallery-item">
                            <img src="../<?php echo htmlspecialchars($immagine['UrlFoto']); ?>" alt="Foto struttura">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="gallery-empty">
                        <i class="fa-regular fa-image"></i>
                        <p>Nessuna foto disponibile</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="structure-section">
                <h2>Descrizione</h2>
                <p><?php echo nl2br(htmlspecialchars($struttura['Descrizione'])); ?></p>
            </div>

        </div>

        <aside class="structure-sidebar">

            <div class="info-card">
                <h3>Informazioni principali</h3>

                <div class="info-row">
                    <span>Indirizzo</span>
                    <strong><?php echo htmlspecialchars($struttura['Indirizzo']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Città</span>
                    <strong><?php echo htmlspecialchars($struttura['Città']); ?></strong>
                </div>

                <div class="info-row">
                    <span>Tipo località</span>
                    <strong> <?php echo !empty($struttura['TipoLocalità']) ? htmlspecialchars(mb_convert_case($struttura['TipoLocalità'], MB_CASE_TITLE, "UTF-8")) : '-'; ?> </strong>
                </div>

                <div class="info-row">
                    <span>Tipologia</span>
                    <strong><?php echo htmlspecialchars($tipologia); ?></strong>
                </div>
            </div>

            <div class="info-card">
                <h3>Dettagli specifici</h3>

                <?php if ($tipologia === 'Albergo'): ?>
                    <div class="info-row">
                        <span>Catena</span>
                        <strong><?php echo !empty($dettagli['Catena']) ? htmlspecialchars($dettagli['Catena']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Numero camere</span>
                        <strong><?php echo isset($dettagli['NumeroCamere']) ? htmlspecialchars($dettagli['NumeroCamere']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Numero stelle</span>
                        <strong><?php echo isset($dettagli['NumeroStelle']) ? htmlspecialchars($dettagli['NumeroStelle']) : '-'; ?></strong>
                    </div>

                <?php elseif ($tipologia === 'B&B'): ?>
                    <div class="info-row">
                        <span>Categoria</span>
                        <strong><?php echo !empty($dettagli['Categoria']) ? htmlspecialchars($dettagli['Categoria']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Numero camere</span>
                        <strong><?php echo isset($dettagli['NumeroCamere']) ? htmlspecialchars($dettagli['NumeroCamere']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Colazione inclusa</span>
                        <strong>
                            <?php
                            if (isset($dettagli['ColazioneInclusa'])) {
                                echo $dettagli['ColazioneInclusa'] ? 'Sì' : 'No';
                            } else {
                                echo '-';
                            }
                            ?>
                        </strong>
                    </div>

                <?php elseif ($tipologia === 'Casa vacanze'): ?>
                    <div class="info-row">
                        <span>Posti letto</span>
                        <strong><?php echo isset($dettagli['NumPostiLetto']) ? htmlspecialchars($dettagli['NumPostiLetto']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Superficie</span>
                        <strong>
                            <?php
                            if (isset($dettagli['Superficie']) && $dettagli['Superficie'] !== null && $dettagli['Superficie'] !== '') {
                                echo htmlspecialchars($dettagli['Superficie']) . ' mq';
                            } else {
                                echo '-';
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="info-row">
                        <span>Numero bagni</span>
                        <strong><?php echo isset($dettagli['NumBagni']) ? htmlspecialchars($dettagli['NumBagni']) : '-'; ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Animali ammessi</span>
                        <strong>
                            <?php
                            if (isset($dettagli['AnimaliAmmessi'])) {
                                echo $dettagli['AnimaliAmmessi'] ? 'Sì' : 'No';
                            } else {
                                echo '-';
                            }
                            ?>
                        </strong>
                    </div>

                <?php else: ?>
                    <p class="no-details">Nessun dettaglio specifico disponibile.</p>
                <?php endif; ?>
            </div>

        </aside>
    </div>
</div>

</body>
</html>