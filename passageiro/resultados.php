<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

$origem  = trim($_GET['origem'] ?? '');
$destino = trim($_GET['destino'] ?? '');
$data    = $_GET['data'] ?? date('Y-m-d');
$ordem   = $_GET['ordem'] ?? 'hora';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || $data < date('Y-m-d')) {
    flash('erro', 'Escolha uma data a partir de hoje.');
    redirecionar('passageiro/index.php');
}
if ($origem === '' || $destino === '' || $origem === $destino) {
    flash('erro', 'Escolha uma origem e um destino diferentes.');
    redirecionar('passageiro/index.php');
}

$ordenacoes = ['hora' => 'v.hora_partida', 'preco' => 'v.preco, v.hora_partida', 'empresa' => 't.nome, v.hora_partida'];
$orderBy = $ordenacoes[$ordem] ?? $ordenacoes['hora'];
$nomesOrdem = ['hora' => 'hora', 'preco' => 'preço', 'empresa' => 'empresa'];

// A viagem fica visível até ser realizada (estado deixa de ser 'agendada'), mesmo que a hora de partida já tenha passado hoje.
$params = [$origem, $destino, $data];

$st = $pdo->prepare('SELECT v.*, r.origem, r.destino, t.nome AS transportadora, a.capacidade_total, ' . lugares_livres_sql() . ' AS ocupados
                     FROM viagem v
                     JOIN rota r ON r.id_rota = v.id_rota AND r.activa = 1
                     JOIN transportadora t ON t.id_transportadora = r.id_transportadora AND t.activa = 1
                     JOIN autocarro a ON a.id_autocarro = v.id_autocarro
                     WHERE r.origem = ? AND r.destino = ? AND v.data = ? AND v.estado = \'agendada\'
                     ORDER BY ' . $orderBy);
$st->execute($params);
$viagens = $st->fetchAll();

$base = ['origem' => $origem, 'destino' => $destino];
app_abrir(['titulo' => "$origem → $destino", 'voltar' => 'passageiro/index.php', 'h1' => "$origem → $destino",
           'sub' => data_curta($data) . ' · 1 passageiro']);
?>
<div class="filtros-ord">
  <?php foreach (['hora' => 'relogio', 'preco' => 'cartao', 'empresa' => 'onibus'] as $k => $icone): ?>
    <a class="<?= $ordem === $k ? 'activo' : '' ?>" href="<?= e(url('passageiro/resultados.php?' . http_build_query($base + ['data' => $data, 'ordem' => $k]))) ?>">
      <?= ic($icone, '') ?><?= e(ucfirst($nomesOrdem[$k])) ?></a>
  <?php endforeach; ?>
</div>

<div class="dias">
  <?php for ($i = 0; $i < 7; $i++): $d = date('Y-m-d', strtotime("+$i day")); $t = strtotime($d); ?>
    <a class="<?= $d === $data ? 'activo' : '' ?>" href="<?= e(url('passageiro/resultados.php?' . http_build_query($base + ['data' => $d, 'ordem' => $ordem]))) ?>">
      <?= e(DIAS_CURTOS[(int)date('w', $t)]) ?><b><?= date('j', $t) ?></b><?= e(MESES_CURTOS[(int)date('n', $t) - 1]) ?></a>
  <?php endfor; ?>
</div>

<p class="contagem-res"><?= count($viagens) ?> viage<?= count($viagens) === 1 ? 'm encontrada' : 'ns encontradas' ?> · ordenado por <?= e($nomesOrdem[$ordem] ?? 'hora') ?></p>

<?php if (!$viagens): ?>
  <div class="cartao-app" style="text-align:center;color:var(--cinza)">Não há viagens para esta rota nesta data.<br>Experimente outro dia na faixa acima.</div>
<?php endif; ?>

<?php foreach ($viagens as $v):
    $livres = max(0, (int)$v['capacidade_total'] - (int)$v['ocupados']);
    $estado = $livres === 0 ? 'esgotado' : ($livres <= 8 ? 'poucos' : '');
?>
  <a class="viagem <?= $estado ?>" href="<?= e(url('passageiro/lugares.php?viagem=' . (int)$v['id_viagem'])) ?>">
    <div class="viagem-topo">
      <strong><?= e($v['transportadora']) ?></strong>
      <?php if ($livres === 0): ?><span class="etq-app etq-vermelha">Esgotado</span>
      <?php elseif ($livres <= 8): ?><span class="etq-app etq-amarela">Poucos lugares</span>
      <?php else: ?><span class="etq-app etq-verde"><?= $livres ?> lugares livres</span><?php endif; ?>
    </div>
    <div class="viagem-corpo">
      <div class="viagem-hora"><b><?= e(hora_curta($v['hora_partida'])) ?></b><small><?= e($v['origem']) ?></small></div>
      <div class="viagem-meio"><b><?= e(duracao_texto($v['hora_partida'], $v['hora_chegada'] ?? $v['hora_partida'])) ?></b><?= e($v['classe']) ?></div>
      <div class="viagem-hora"><b><?= e(hora_curta($v['hora_chegada'])) ?></b><small><?= e($v['destino']) ?></small></div>
      <div class="viagem-preco"><?= e(formatar_mt($v['preco'])) ?></div>
    </div>
  </a>
<?php endforeach; ?>
<?php app_fechar(['nav' => 'inicio']); ?>