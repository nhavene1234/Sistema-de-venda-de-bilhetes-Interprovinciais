<?php
// health.php - mantém o servidor Render e a base de dados Neon activos
require_once __DIR__ . '/config/conexao.php';

try {
    $pdo->query('SELECT 1');
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'OK - Render & Neon acordados';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Erro BD';
}
