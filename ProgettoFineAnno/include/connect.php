<?php

    require 'config.php';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

    try {
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        $conn = new PDO($dsn, $user, $password, $options);

        if($conn){
            // connessione ok
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

?>