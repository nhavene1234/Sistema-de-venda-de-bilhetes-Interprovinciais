<?php
// config/conexao.php - ligação à base de dados com PDO (XAMPP, configuração por defeito)
$servidor = 'localhost';
$base     = 'bilhetes_interprovinciais';
$usuario  = 'root';
$senha    = '';   // no XAMPP, o root não tem palavra-passe por defeito

try {
    $pdo = new PDO(
        "mysql:host=$servidor;dbname=$base;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    $pdo->exec("SET time_zone = '+02:00'");   // MySQL com a mesma hora de Maputo (CAT)
} catch (PDOException $e) {
    die('Não foi possível ligar à base de dados.');
}