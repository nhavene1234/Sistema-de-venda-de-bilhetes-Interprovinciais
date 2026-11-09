<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('passageiro/bilhetes.php');

$idR = (int)($_POST['reserva'] ?? 0);
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('passageiro/reserva.php?id=' . $idR); }

$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r || $r['estado'] !== 'confirmada') redirecionar('passageiro/bilhetes.php');
if (!dentro_do_prazo($pdo, $r) || $r['bilhete_estado'] !== 'valido') {   // RN06
    flash('erro', 'Já não é possível cancelar esta reserva.');
    redirecionar('passageiro/reserva.php?id=' . $idR);
}

$agora = date('Y-m-d H:i:s');
$pdo->beginTransaction();
$pdo->prepare("UPDATE reserva SET estado = 'cancelada', data_cancelamento = ? WHERE id_reserva = ?")->execute([$agora, $idR]);   // liberta o lugar
$pdo->prepare("UPDATE bilhete SET estado = 'cancelado' WHERE id_reserva = ?")->execute([$idR]);
$pdo->prepare("UPDATE pagamento SET estado = 'reembolsado' WHERE id_reserva = ? AND estado = 'confirmado'")->execute([$idR]);
$pdo->commit();
registar_log($pdo, (int)$u['id_utilizador'], 'Reserva cancelada', 'reserva', $idR, $r['referencia']);
redirecionar('passageiro/cancelada.php?id=' . $idR);
