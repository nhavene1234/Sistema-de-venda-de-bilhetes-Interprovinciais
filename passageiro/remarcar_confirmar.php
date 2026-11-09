<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('passageiro/bilhetes.php');
libertar_reservas_expiradas($pdo);

$idR = (int)($_POST['reserva'] ?? 0);
$idV = (int)($_POST['viagem'] ?? 0);
$idA = (int)($_POST['assento'] ?? 0);
$voltar = 'passageiro/lugares.php?viagem=' . $idV . '&remarcar=' . $idR;
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }

$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r || $r['estado'] !== 'confirmada' || !dentro_do_prazo($pdo, $r) || $r['bilhete_estado'] !== 'valido') {
    flash('erro', 'Já não é possível remarcar esta reserva.');
    redirecionar('passageiro/bilhetes.php');
}

$st = $pdo->prepare('SELECT v.*, ro.origem, ro.destino, ro.id_transportadora FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota WHERE v.id_viagem = ?');
$st->execute([$idV]);
$v = $st->fetch();
if (!$v || $v['estado'] !== 'agendada' || partida_ts($v) <= time() || $v['origem'] !== $r['origem'] || $v['destino'] !== $r['destino']
    || (int)$v['id_transportadora'] !== (int)$r['id_transportadora'] || (float)$v['preco'] !== (float)$r['preco']) {
    flash('erro', 'Esta viagem não pode ser usada para remarcar.');
    redirecionar('passageiro/remarcar.php?id=' . $idR);
}
$st = $pdo->prepare('SELECT COUNT(*) FROM assento WHERE id_assento = ? AND id_autocarro = ?');
$st->execute([$idA, $v['id_autocarro']]);
if (!$st->fetchColumn()) { flash('erro', 'Escolha um lugar válido.'); redirecionar($voltar); }

try {
    $pdo->prepare('UPDATE reserva SET id_viagem = ?, id_assento = ? WHERE id_reserva = ?')->execute([$idV, $idA, $idR]);
} catch (PDOException $e) {
    flash('erro', ($e->errorInfo[1] ?? 0) === 1062 ? 'Esse lugar acabou de ser reservado por outro passageiro. Escolha outro.' : 'Não foi possível remarcar. Tente novamente.');
    redirecionar($voltar);
}
registar_log($pdo, (int)$u['id_utilizador'], 'Reserva remarcada', 'reserva', $idR, $r['referencia'] . ' → ' . $v['data']);
flash('ok', 'Reserva remarcada para ' . data_curta($v['data']) . ' às ' . hora_curta($v['hora_partida']) . '.');
redirecionar('passageiro/bilhete.php?reserva=' . $idR);
