<?php

    $host = 'localhost';
    $db = 'SitoWebRecensioniTuristiche';
    $user = 'root';
    $password = '';

    try{
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=UTF8", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
    }
?>