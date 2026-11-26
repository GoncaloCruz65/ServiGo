<?php

$servidor = "localhost";

$porta = "3306"; 
//Mudar para "3306" se estiver no XAMPP
//Mudar para "8889" se estiver no MAMP

$base_dados = "servigo";
$utilizador = "root";

$palavra_passe = ""; 
//Mudar para "" (vazio) se estiver no XAMPP 
//Mudar para "root" se estiver no MAMP

try {
    $pdo = new PDO(
        "mysql:host=$servidor;port=$porta;dbname=$base_dados;charset=utf8mb4",
        $utilizador,
        $palavra_passe
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    exit("Não foi possível ligar à base de dados.");
}