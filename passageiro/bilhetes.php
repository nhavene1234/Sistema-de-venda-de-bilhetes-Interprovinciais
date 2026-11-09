<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

$aba = ($_GET['aba'] ?? 'proximas') === 'passadas' ? 'passadas' : 'proximas';
$st = $pdo->prepare("SELECT rs.*, v.data, v.hora_partida, ro.origem, ro.destino, t.nome AS transportadora, a.numero AS lugar
                     FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                     JOIN transportadora t ON t.id_transportadora = ro.id_transportadora JOIN assento a ON a.id_assento = rs.id_assento
                     WHERE rs.id_passageiro = ? AND rs.estado = 'confirmada' ORDER BY v.data, v.hora_partida, a.numero");
$st->execute([$u['id_utilizador']]);

// um cartão por viagem: os lugares comprados juntos (família, amigos...) aparecem agrupados
$grupos = [];
foreach ($st->fetchAll() as $r) {
    $k = (int)$r['id_viagem'];
    if (!isset($grupos[$k])) { $r['lugares'] = []; $grupos[$k] = $r; }
    $grupos[$k]['lugares'][] = $r['lugar'];
}

$proximas = []; $passadas = [];
foreach ($grupos as $g) { if (partida_ts($g) >= time()) $proximas[] = $g; else array_unshift($passadas, $g); }
$lista = $aba === 'proximas' ? $proximas : $passadas;

app_abrir(['titulo' => 'Meus bilhetes', 'h1' => 'Meus bilhetes', 'sub' => 'As suas próximas viagens',
           'acao' => '<a class="btn-icone" href="' . e(url('passageiro/index.php')) . '" aria-label="Nova viagem">' . ic('mais') . '</a>']);
?>
<div class="abas">
  <a class="<?= $aba === 'proximas' ? 'activo' : '' ?>" href="<?= e(url('passageiro/bilhetes.php')) ?>">Próximas</a>
  <a class="<?= $aba === 'passadas' ? 'activo' : '' ?>" href="<?= e(url('passageiro/bilhetes.php?aba=passadas')) ?>">Passadas</a>
</div>

<?php if (!$lista): ?>
  <div class="cartao-app" style="text-align:center;color:var(--cinza)"><?= $aba === 'proximas' ? 'Ainda não tem viagens marcadas.' : 'Ainda não tem viagens passadas.' ?><br>
    <a href="<?= e(url('passageiro/index.php')) ?>"><b>Pesquisar viagens</b></a></div>
<?php endif; ?>
<?php foreach ($lista as $r): [$txt, $cls] = estado_reserva($r); $n = count($r['lugares']); ?>
  <a class="item-bilhete" href="<?= e(url('passageiro/bilhete.php?reserva=' . (int)$r['id_reserva'])) ?>">
    <div class="linha1"><span><?= e($r['transportadora']) ?></span><span class="etq-app <?= $cls ?>"><?= e($txt) ?></span></div>
    <h3><?= e($r['origem']) ?> → <?= e($r['destino']) ?></h3>
    <div class="linha2"><span><?= ic('calendario') ?><?= e(data_com_ano($r['data'])) ?> · <?= e(hora_curta($r['hora_partida'])) ?></span>
      <span><?= $n > 1 ? e($n . ' bilhetes · lugares ' . implode(', ', $r['lugares'])) : e($r['lugar'] . ' · ' . $r['referencia']) ?></span></div>
  </a>
<?php endforeach; ?>
<?php if ($lista && $aba === 'proximas'): ?>
  <div class="dica"><?= ic('bilhete') ?><span>Use «Guardar» no bilhete para o ter sempre à mão no embarque.</span></div>
<?php endif; ?>
<?php app_fechar(['nav' => 'bilhetes']); ?>