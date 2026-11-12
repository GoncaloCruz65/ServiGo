<?php

$servidor = "localhost";
$base_dados = "servigo";
$utilizador = "root";

try {
    // 1. Tenta ligar com password vazia (Padrão do XAMPP)
    $pdo = new PDO(
        "mysql:host=$servidor;dbname=$base_dados;charset=utf8mb4",
        $utilizador,
        ""
    );
} catch (PDOException $e) {
    try {
        // 2. Se falhar, tenta ligar com password "root" (Padrão do MAMP)
        $pdo = new PDO(
            "mysql:host=$servidor;dbname=$base_dados;charset=utf8mb4",
            $utilizador,
            "root"
        );
    } catch (PDOException $e2) {
        error_log($e2->getMessage());
        exit("Não foi possível ligar à base de dados.");
    }
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);