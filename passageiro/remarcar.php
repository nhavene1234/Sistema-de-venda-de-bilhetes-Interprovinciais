<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

$idR = (int)($_GET['id'] ?? 0);
$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r || $r['estado'] !== 'confirmada') redirecionar('passageiro/bilhetes.php');
if (!dentro_do_prazo($pdo, $r) || $r['bilhete_estado'] !== 'valido') {
    flash('erro', 'Já não é possível remarcar esta reserva.');
    redirecionar('passageiro/reserva.php?id=' . $idR);
}

$data = $_GET['data'] ?? date('Y-m-d', strtotime($r['data'] . ' +1 day'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || $data < date('Y-m-d')) $data = date('Y-m-d', strtotime('+1 day'));

// mesma rota, mesma transportadora e mesmo preço (a remarcação não tem custo adicional)
$params = [$r['origem'], $r['destino'], $r['id_transportadora'], $r['preco'], $data, $r['id_viagem']];
$sqlHora = '';
if ($data === date('Y-m-d')) { $sqlHora = ' AND v.hora_partida > ?'; $params[] = date('H:i:s'); }
$st = $pdo->prepare('SELECT v.*, a.capacidade_total, ' . lugares_livres_sql() . ' AS ocupados
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro a ON a.id_autocarro = v.id_autocarro
                     WHERE ro.origem = ? AND ro.destino = ? AND ro.id_transportadora = ? AND v.preco = ? AND v.data = ?
                       AND v.id_viagem <> ? AND v.estado = \'agendada\'' . $sqlHora . ' ORDER BY v.hora_partida');
$st->execute($params);
$viagens = $st->fetchAll();

app_abrir(['titulo' => 'Remarcar', 'voltar' => 'passageiro/reserva.php?id=' . $idR, 'h1' => 'Remarcar viagem',
           'sub' => $r['origem'] . ' → ' . $r['destino'] . ' · ' . $r['transportadora']]);
?>
<div class="dica" style="margin-bottom:12px"><?= ic('remarcar') ?><span>Reserva actual: <?= e(data_curta($r['data'])) ?> às <?= e(hora_curta($r['hora_partida'])) ?>, lugar <?= e($r['lugar']) ?>. Só mostramos viagens da mesma empresa e rota, ao mesmo preço.</span></div>

<div class="dias">
  <?php for ($i = 0; $i < 10; $i++): $d = date('Y-m-d', strtotime("+$i day")); $t = strtotime($d); ?>
    <a class="<?= $d === $data ? 'activo' : '' ?>" href="<?= e(url('passageiro/remarcar.php?' . http_build_query(['id' => $idR, 'data' => $d]))) ?>">
      <?= e(DIAS_CURTOS[(int)date('w', $t)]) ?><b><?= date('j', $t) ?></b><?= e(MESES_CURTOS[(int)date('n', $t) - 1]) ?></a>
  <?php endfor; ?>
</div>

<?php if (!$viagens): ?>
  <div class="cartao-app" style="text-align:center;color:var(--cinza)">Não há viagens disponíveis neste dia.<br>Escolha outra data na faixa acima.</div>
<?php endif; ?>
<?php foreach ($viagens as $v): $livres = max(0, (int)$v['capacidade_total'] - (int)$v['ocupados']); ?>
  <a class="viagem <?= $livres === 0 ? 'esgotado' : ($livres <= 8 ? 'poucos' : '') ?>"
     href="<?= e(url('passageiro/lugares.php?viagem=' . (int)$v['id_viagem'] . '&remarcar=' . $idR)) ?>">
    <div class="viagem-topo"><strong><?= e($r['transportadora']) ?></strong>
      <span class="etq-app <?= $livres === 0 ? 'etq-vermelha' : ($livres <= 8 ? 'etq-amarela' : 'etq-verde') ?>"><?= $livres === 0 ? 'Esgotado' : $livres . ' lugares livres' ?></span></div>
    <div class="viagem-corpo">
      <div class="viagem-hora"><b><?= e(hora_curta($v['hora_partida'])) ?></b><small><?= e($r['origem']) ?></small></div>
      <div class="viagem-meio"><b><?= e(duracao_texto($v['hora_partida'], $v['hora_chegada'] ?? $v['hora_partida'])) ?></b><?= e($v['classe']) ?></div>
      <div class="viagem-hora"><b><?= e(hora_curta($v['hora_chegada'])) ?></b><small><?= e($r['destino']) ?></small></div>
      <div class="viagem-preco"><?= e(formatar_mt($v['preco'])) ?></div>
    </div>
  </a>
<?php endforeach; ?>
<?php app_fechar(); ?>
