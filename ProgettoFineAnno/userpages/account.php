<?php
// Partenza sessione se non ancora avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Se utente non è loggato viene mandato automaticamente al login
if (empty($_SESSION['IdUtente'])) {
    header("Location: ../login.php");
    exit;
}

// Richiede il file connect.php per connessione al database
require __DIR__ . '/../include/connect.php';

// Salva ID Utente in una variabile
$idUtente = $_SESSION['IdUtente'];

// Query per prendere i dati dell'utente
$sql = "SELECT Nome, Cognome, DataNascita, Email
        FROM Utenti
        WHERE IdUtente = :idUtente
        LIMIT 1"; // Limit 1 -> massimo una tupla

$stmt = $conn->prepare($sql); // Prepara la query (contro SQL Injection)
$stmt->bindParam(':idUtente', $idUtente); // Collega il valore della variabile IdUtente al parametro
$stmt->execute(); // Esegue la query 

// Prende il risultato dell'esecuzione della query, lo prende come array
$utente = $stmt->fetch(PDO::FETCH_ASSOC); 

// Controlla che il profilo sia completo, cioè se tutti i dati sono stati inseriti
// Serve perché quando l'utente viene registrato nella pagina dell'account dovrà completare la registrazione 
// con nome, cognome, data di nascita
$profiloCompleto = !empty($utente['Nome']) && !empty($utente['Cognome']) && !empty($utente['DataNascita']);
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

<div class="account-container">
    <h2>Area personale</h2>
    <!-- Parte dinamica: se l'utente non ha completato il profilo viene mostrato il form per completarlo -->
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
    <?php else: ?> <!-- Altrimenti (se il profilo è completo) mostra i dati dell'utente, tranne password -->
        <div class="account-info">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($utente['Nome']); ?></p>
            <p><strong>Cognome:</strong> <?php echo htmlspecialchars($utente['Cognome']); ?></p>
            <p><strong>Data di nascita:</strong> <?php echo htmlspecialchars($utente['DataNascita']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($utente['Email']); ?></p>
        </div>
    <?php endif; ?> <!-- Fine della parte dinamica, chiusura if php-->
</div>

</body>
</html>