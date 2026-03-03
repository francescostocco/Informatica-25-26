<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/PHP/connect.php';

$id = $_SESSION['IdUtente'];

$sql = "SELECT Nome, Cognome, DataNascita, Email
        FROM Utenti
        WHERE IdUtente = :id
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

$utente = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale - OpinioniVacanze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/account.css">
</head>
<body>

<div class="account-container">
    <h2>Area Personale</h2>

    <div class="account-info">
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($utente['Nome']); ?></p>
        <p><strong>Cognome:</strong> <?php echo htmlspecialchars($utente['Cognome']); ?></p>
        <p><strong>Data di nascita:</strong> <?php echo htmlspecialchars($utente['DataNascita']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['Email']); ?></p>
    </div>
</div>

</body>
</html>