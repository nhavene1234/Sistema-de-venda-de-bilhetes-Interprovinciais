<?php
// config/conexao.php - ligação à base de dados PostgreSQL (Neon / Render) com PDO

$databaseUrl = getenv('DATABASE_URL');

try {
    if ($databaseUrl) {
        // Ambiente de Produção (Render + Neon)
        $db = parse_url($databaseUrl);
        
        $servidor = $db['host'];
        $porta    = $db['port'] ?? 5432;
        $base     = ltrim($db['path'], '/');
        $usuario  = $db['user'];
        $senha    = $db['pass'];

        $dsn = "pgsql:host=$servidor;port=$porta;dbname=$base;sslmode=require";

        $pdo = new PDO(
            $dsn,
            $usuario,
            $senha,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        // Define o fuso horário de Maputo (CAT / UTC+2) no PostgreSQL
        $pdo->exec("SET TIME ZONE 'Africa/Maputo'");

    } else {
        // Ambiente Local (XAMPP / MySQL)
        $servidor = 'localhost';
        $base     = 'bilhetes_interprovinciais';
        $usuario  = 'root';
        $senha    = '';

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
        $pdo->exec("SET time_zone = '+02:00'");
    }
} catch (PDOException $e) {
    die('Não foi possível ligar à base de dados.');
}
