<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['IdUtente']) || empty($_SESSION['admin_da_verificare'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Verifica Admin - OpinioniVacanze</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

<div class="admin-login-box">
    <h1>Verifica amministratore</h1>
    <p>Inserisci il codice di accesso admin.</p>

    <form action="../actions/admincodecheck.php" method="POST">
        <input type="text" name="codiceAccesso" placeholder="Codice admin" required>
        <button type="submit">Entra</button>
    </form>
</div>

</body>
</html>