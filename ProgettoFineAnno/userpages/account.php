<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente'])) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../include/connect.php';

$idUtente = $_SESSION['IdUtente'];

/* DATI UTENTE */
$sql = "SELECT Nome, Cognome, DataNascita, Email FROM Utenti WHERE IdUtente = :idUtente LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idUtente', $idUtente);
$stmt->execute();

$utente = $stmt->fetch(PDO::FETCH_ASSOC);

$profiloCompleto = !empty($utente['Nome']) && !empty($utente['Cognome']) && !empty($utente['DataNascita']);

/* RECENSIONI SCRITTE DALL'UTENTE */
$sqlRecensioni = "SELECT R.IdRecensione, R.Titolo, R.Commento, R.NumStelle, S.CodStruttura, S.NomeStruttura, S.`Città`FROM Recensioni R
                  INNER JOIN Strutture S ON R.CodStruttura = S.CodStruttura WHERE R.IdUtente = :idUtente ORDER BY R.IdRecensione DESC";

$stmtRecensioni = $conn->prepare($sqlRecensioni);
$stmtRecensioni->bindParam(':idUtente', $idUtente);
$stmtRecensioni->execute();

$recensioni = $stmtRecensioni->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area personale - OpinioniVacanze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/account.css">
</head>
<body>

<div class="account-page">

    <div class="account-container">
        <h2>Area personale</h2>

        <?php if (!$profiloCompleto): ?>
            <p class="account-subtitle">Completa la registrazione inserendo i tuoi dati personali.</p>

            <form action="updateaccount.php" method="POST" class="account-form">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>

                <label for="cognome">Cognome</label>
                <input type="text" id="cognome" name="cognome" required>

                <label for="dataNascita">Data di nascita</label>
                <input type="date" id="dataNascita" name="dataNascita" required>

                <button type="submit">Salva dati</button>
            </form>
        <?php else: ?>
            <div class="account-info">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($utente['Nome']); ?></p>
                <p><strong>Cognome:</strong> <?php echo htmlspecialchars($utente['Cognome']); ?></p>
                <p><strong>Data di nascita:</strong> <?php echo htmlspecialchars($utente['DataNascita']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['Email']); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="account-container reviews-account-container">
        <h2>Le tue recensioni</h2>

        <?php if (count($recensioni) > 0): ?>
            <div class="account-reviews-list">
                <?php foreach ($recensioni as $recensione): ?>
                    <div class="account-review-card">
                        <div class="account-review-top">
                            <div>
                                <h3><?php echo htmlspecialchars($recensione['Titolo']); ?></h3>
                                <a href="../structurepages/struttura.php?id=<?php echo $recensione['CodStruttura']; ?>">
                                    <?php echo htmlspecialchars($recensione['NomeStruttura']); ?> · 
                                    <?php echo htmlspecialchars($recensione['Città']); ?>
                                </a>
                            </div>

                            <div class="account-review-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?php echo $i <= $recensione['NumStelle'] ? 'active' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <p><?php echo nl2br(htmlspecialchars($recensione['Commento'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="account-subtitle">Non hai ancora scritto recensioni.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>