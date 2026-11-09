<?php
// api/permissao_alternar.php - guarda uma alteração da matriz de permissões (resposta JSON)
require_once __DIR__ . '/../includes/sessao.php';
header('Content-Type: application/json; charset=utf-8');

$u = utilizador_logado();
if (!$u || $u['perfil'] !== 'administrador' || $_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    http_response_code(403);
    echo json_encode(['ok' => false]);
    exit;
}

$perfil = $_POST['perfil'] ?? '';
$idPerm = (int)($_POST['id_permissao'] ?? 0);
$concedida = ($_POST['concedida'] ?? '0') === '1' ? 1 : 0;

if (!in_array($perfil, ['bilheteiro', 'cobrador', 'gestor'], true) || $idPerm <= 0) {
    http_response_code(422);
    echo json_encode(['ok' => false]);
    exit;
}

$pdo->prepare('UPDATE perfil_permissao SET concedida = ? WHERE perfil = ? AND id_permissao = ?')
    ->execute([$concedida, $perfil, $idPerm]);

$st = $pdo->prepare('SELECT descricao FROM permissao WHERE id_permissao = ?');
$st->execute([$idPerm]);
registar_log($pdo, (int)$u['id_utilizador'], 'Permissão alterada', 'permissao', $idPerm,
    nome_perfil($perfil) . ': ' . $st->fetchColumn() . ' = ' . ($concedida ? 'sim' : 'não'));

echo json_encode(['ok' => true]);
