<?php
    $host = "localhost";
    $dbname = "cadastro";
    $user = "root";
    $pass= "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        //ativar o modo de erros
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>