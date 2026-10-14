<?php

$servidor = "localhost";
$base_dados = "servigo";
$utilizador = "root";
$palavra_passe = "";

try {
    $pdo = new PDO(
        "mysql:host=$servidor;dbname=$base_dados;charset=utf8mb4",
        $utilizador,
        $palavra_passe
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Não foi possível ligar à base de dados.");
}