<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../include/connect.php';

$email = trim($_POST['email'] ?? '');
$passwordInserita = $_POST['password'] ?? '';

/**Prendo i dati dell'utente e verifico che sia proprietario */
$sql = "SELECT U.IdUtente, U.Nome, U.Cognome, U.PasswordUtente FROM Utenti U
        INNER JOIN Proprietari P ON U.IdUtente = P.IdUtente WHERE U.Email = :email LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utente) {
    header("Location: ../ownerpages/ownerlogin.php?err=1");
    exit;
}

if (!password_verify($passwordInserita, $utente['PasswordUtente'])) {
    header("Location: ../ownerpages/ownerlogin.php?err=2");
    exit;
}

$_SESSION['IdUtente'] = $utente['IdUtente'];
$_SESSION['Nome'] = $utente['Nome'];
$_SESSION['Cognome'] = $utente['Cognome'];
$_SESSION['loggato'] = true;
$_SESSION['ruolo'] = 'proprietario';

header("Location: ../index.php");
exit;
?>