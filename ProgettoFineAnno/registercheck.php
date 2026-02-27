<?php

ob_start();

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require 'PHP/connect.php';

$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$dataNascita = $_POST['dataNascita'] ?? '';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if($nome === '' || $cognome === '' || $dataNascita === '' || $email === '' || $password){
    header("Location: register.php?err=1");
}

$check = $conn->prepare("SELECT IdUtente FROM Utenti WHERE Email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if($check->fetch()){
    header("Location: register.php?err=2");
    exit;
}

$sql = 'INSERT INTO Utenti(Nome, Cognome, DataNascita, Email, PasswordUtente)
        VALUES (:nome, :cognome, :dataNascita, :email, :password)';

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cognome', $cognome);
$stmt->bindParam(':dataNascita', $dataNascita);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $paswword);

$stmt->execute();

$_SESSION['IdUtente'] = $conn->lastInsertId();
$_SESSION['Nome'] = $nome;
$_SESSION['Cognome'] = $cognome;
$_SESSION['loggato'] = true;

header("Location: index.php");
exit;

?>