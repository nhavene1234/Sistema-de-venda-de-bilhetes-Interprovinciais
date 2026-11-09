<?php
// api/bilheteiro_ocupacao.php - lugares ocupados de uma viagem, em tempo real (resposta JSON)
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
header('Content-Type: application/json; charset=utf-8');

$u = utilizador_logado();
if (!$u || !in_array($u['perfil'], ['bilheteiro', 'gestor', 'administrador'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false]);
    exit;
}
libertar_reservas_expiradas($pdo);

$idV = (int)($_GET['viagem'] ?? 0);
$st = $pdo->prepare('SELECT v.id_autocarro, ro.id_transportadora FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota WHERE v.id_viagem = ?');
$st->execute([$idV]);
$viagem = $st->fetch();
if (!$viagem) { http_response_code(404); echo json_encode(['ok' => false]); exit; }
if ($u['perfil'] !== 'administrador') {   // RN13: cada utilizador só acede à sua transportadora
    $st = $pdo->prepare('SELECT id_transportadora FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    if ((int)$st->fetchColumn() !== (int)$viagem['id_transportadora']) { http_response_code(403); echo json_encode(['ok' => false]); exit; }
}
$bus = $viagem['id_autocarro'];

$st = $pdo->prepare('SELECT COUNT(*) FROM assento WHERE id_autocarro = ?');
$st->execute([$bus]);
$total = (int)$st->fetchColumn();

$st = $pdo->prepare("SELECT id_assento FROM reserva WHERE id_viagem = ? AND estado IN ('pendente','confirmada')");
$st->execute([$idV]);
$ocupados = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));

echo json_encode(['ok' => true, 'ocupados' => $ocupados, 'n_ocupados' => count($ocupados), 'n_livres' => $total - count($ocupados)]);
