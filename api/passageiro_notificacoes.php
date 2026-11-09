<?php
// api/passageiro_notificacoes.php - guarda a preferência de notificações (resposta JSON)
require_once __DIR__ . '/../includes/sessao.php';
header('Content-Type: application/json; charset=utf-8');

$u = utilizador_logado();
if (!$u || $u['perfil'] !== 'passageiro' || $_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    http_response_code(403);
    echo json_encode(['ok' => false]);
    exit;
}
$activo = ($_POST['activo'] ?? '1') === '1' ? 1 : 0;
$pdo->prepare('UPDATE utilizador SET notificacoes = ? WHERE id_utilizador = ?')->execute([$activo, $u['id_utilizador']]);
echo json_encode(['ok' => true]);
