<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('bilheteiro/index.php');

$idV = (int)($_POST['viagem'] ?? 0);
$voltar = 'bilheteiro/vender_lugares.php?viagem=' . $idV;
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }
libertar_reservas_expiradas($pdo);

$lugares = array_values(array_unique(array_map('intval', (array)($_POST['lugares'] ?? []))));
if (count($lugares) < 1 || count($lugares) > 4) { flash('erro', 'Escolha entre 1 e 4 lugares.'); redirecionar($voltar); }

$transp = transportadora_do_bilheteiro($pdo);
$st = $pdo->prepare('SELECT v.*, ro.id_transportadora FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota WHERE v.id_viagem = ?');
$st->execute([$idV]);
$v = $st->fetch();
if (!$v || (int)$v['id_transportadora'] !== $transp['id'] || $v['estado'] !== 'agendada' || partida_ts($v) <= time()) {
    flash('erro', motivo_indisponivel($v ?: null));
    redirecionar('bilheteiro/index.php');
}
// todos os lugares têm de pertencer ao autocarro da viagem
$marcas = implode(',', array_fill(0, count($lugares), '?'));
$st = $pdo->prepare("SELECT COUNT(*) FROM assento WHERE id_autocarro = ? AND id_assento IN ($marcas)");
$st->execute(array_merge([$v['id_autocarro']], $lugares));
if ((int)$st->fetchColumn() !== count($lugares)) { flash('erro', 'Escolha lugares válidos.'); redirecionar($voltar); }

$minutos = (int)config_valor($pdo, 'bloqueio_assento_minutos', 10);
$grupo = 'V' . date('ymdHis') . strtoupper(bin2hex(random_bytes(2)));
try {
    $pdo->beginTransaction();
    // abandona bloqueios anteriores deste bilheteiro
    $pdo->prepare("UPDATE reserva SET estado = 'cancelada', data_cancelamento = ? WHERE id_bilheteiro = ? AND estado = 'pendente'")
        ->execute([date('Y-m-d H:i:s'), $eu['id_utilizador']]);
    $ins = $pdo->prepare("INSERT INTO reserva (estado, data_expiracao, id_viagem, id_assento, id_bilheteiro, grupo_venda)
                          VALUES ('pendente', ?, ?, ?, ?, ?)");
    $ref = $pdo->prepare('UPDATE reserva SET referencia = ? WHERE id_reserva = ?');
    foreach ($lugares as $idA) {
        $ins->execute([date('Y-m-d H:i:s', time() + $minutos * 60), $idV, $idA, $eu['id_utilizador'], $grupo]);
        $idR = (int)$pdo->lastInsertId();
        $ref->execute(['MT-' . date('Y') . '-' . str_pad((string)$idR, 6, '0', STR_PAD_LEFT), $idR]);
    }
    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('erro', ($e->errorInfo[1] ?? 0) === 1062
        ? 'Um dos lugares acabou de ser ocupado. Escolha outros lugares.'
        : 'Não foi possível bloquear os lugares. Tente novamente.');
    redirecionar($voltar);
}
redirecionar('bilheteiro/vender_dados.php?grupo=' . $grupo);
