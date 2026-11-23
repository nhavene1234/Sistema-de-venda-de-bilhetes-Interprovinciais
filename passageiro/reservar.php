<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('passageiro/index.php');

$idV = (int)($_POST['viagem'] ?? 0);
$voltar = 'passageiro/lugares.php?viagem=' . $idV;
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }

// lugares escolhidos: assentos[] (vários) ou assento (um só)
$idsAssentos = lista_ids($_POST['assentos'] ?? ($_POST['assento'] ?? ''), 100);
if (!$idsAssentos) { flash('erro', 'Escolha pelo menos um lugar.'); redirecionar($voltar); }
if (count($idsAssentos) > MAX_LUGARES) {
    flash('erro', 'Pode reservar no máximo ' . MAX_LUGARES . ' lugares de cada vez.');
    redirecionar($voltar);
}

libertar_reservas_expiradas($pdo);

$st = $pdo->prepare('SELECT v.* FROM viagem v WHERE v.id_viagem = ?');
$st->execute([$idV]);
$v = $st->fetch();
if (!$v || $v['estado'] !== 'agendada' || partida_ts($v) <= time()) {
    flash('erro', motivo_indisponivel($v ?: null));
    redirecionar('passageiro/index.php');
}

// todos os lugares têm de ser do autocarro desta viagem
$marcas = implode(',', array_fill(0, count($idsAssentos), '?'));
$st = $pdo->prepare("SELECT COUNT(*) FROM assento WHERE id_autocarro = ? AND id_assento IN ($marcas)");
$st->execute(array_merge([$v['id_autocarro']], $idsAssentos));
if ((int)$st->fetchColumn() !== count($idsAssentos)) { flash('erro', 'Escolha lugares válidos.'); redirecionar($voltar); }

$minutos = (int)config_valor($pdo, 'bloqueio_assento_minutos', 10);
$expira = date('Y-m-d H:i:s', time() + $minutos * 60);
$idsReservas = [];
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

try {
    $pdo->beginTransaction();
    // cancela bloqueios anteriores do mesmo passageiro (só pode ter um grupo de lugares à espera de pagamento)
    $pdo->prepare("UPDATE reserva SET estado = 'cancelada', data_cancelamento = ? WHERE id_passageiro = ? AND estado = 'pendente'")
        ->execute([date('Y-m-d H:i:s'), $u['id_utilizador']]);

    if ($driver === 'pgsql') {
        $ins = $pdo->prepare("INSERT INTO reserva (estado, data_expiracao, id_passageiro, id_viagem, id_assento) VALUES ('pendente', ?, ?, ?, ?) RETURNING id_reserva");
    } else {
        $ins = $pdo->prepare("INSERT INTO reserva (estado, data_expiracao, id_passageiro, id_viagem, id_assento) VALUES ('pendente', ?, ?, ?, ?)");
    }
    $ref = $pdo->prepare('UPDATE reserva SET referencia = ? WHERE id_reserva = ?');

    foreach ($idsAssentos as $idA) {
        $ins->execute([$expira, $u['id_utilizador'], $idV, $idA]);
        $idR = $driver === 'pgsql' ? (int)$ins->fetchColumn() : (int)$pdo->lastInsertId();
        $ref->execute(['MT-' . date('Y') . '-' . str_pad((string)$idR, 6, '0', STR_PAD_LEFT), $idR]);
        $idsReservas[] = $idR;
    }
    $pdo->commit();   // tudo ou nada: se um lugar falhar, nenhum fica reservado
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('erro', 'Erro SQL: ' . $e->getMessage());
    redirecionar($voltar);
}

foreach ($idsReservas as $idR) {
    registar_log($pdo, (int)$u['id_utilizador'], 'Lugar reservado (a aguardar pagamento)', 'reserva', $idR);
}
redirecionar('passageiro/pagamento.php?reservas=' . implode(',', $idsReservas));
