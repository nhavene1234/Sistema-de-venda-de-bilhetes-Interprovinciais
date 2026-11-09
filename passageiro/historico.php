<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

$f = $_GET['f'] ?? 'todas';
$filtros = ['todas' => 'Todas', 'concluidas' => 'Concluídas', 'canceladas' => 'Canceladas', 'expiradas' => 'Expiradas'];
if (!isset($filtros[$f])) $f = 'todas';

$st = $pdo->prepare("SELECT rs.*, v.data, v.hora_partida, v.preco, ro.origem, ro.destino
                     FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                     WHERE rs.id_passageiro = ? ORDER BY v.data DESC, v.hora_partida DESC");
$st->execute([$u['id_utilizador']]);
$grupos = [];
foreach ($st->fetchAll() as $r) {
    [$txt, $cls] = estado_reserva($r);
    if ($f === 'concluidas' && $txt !== 'Concluída') continue;
    if ($f === 'canceladas' && $txt !== 'Cancelada') continue;
    if ($f === 'expiradas' && $txt !== 'Expirada') continue;
    $r['_txt'] = $txt; $r['_cls'] = $cls;
    $grupos[date('Y', strtotime($r['data']))][] = $r;
}

app_abrir(['titulo' => 'Histórico', 'h1' => 'Histórico de viagens', 'sub' => 'Todas as suas deslocações']);
?>
<div class="filtro-hist">
  <?php foreach ($filtros as $k => $nome): ?>
    <a class="<?= $f === $k ? 'activo' : '' ?>" href="<?= e(url('passageiro/historico.php?f=' . $k)) ?>"><?= e($nome) ?></a>
  <?php endforeach; ?>
</div>

<?php if (!$grupos): ?><div class="cartao-app" style="text-align:center;color:var(--cinza)">Sem viagens para mostrar.</div><?php endif; ?>
<?php foreach ($grupos as $ano => $itens): ?>
  <div class="ano"><span><?= (int)$ano ?></span></div>
  <?php foreach ($itens as $r):
      $ligacao = $r['estado'] === 'confirmada' ? 'passageiro/bilhete.php?reserva=' . (int)$r['id_reserva']
               : ($r['estado'] === 'pendente' ? 'passageiro/pagamento.php?reserva=' . (int)$r['id_reserva'] : null);
      $icone = $r['_cls'] === 'etq-vermelha' ? 'x' : ($r['_cls'] === 'etq-amarela' ? 'relogio' : 'onibus'); ?>
    <<?= $ligacao ? 'a href="' . e(url($ligacao)) . '"' : 'div' ?> class="item-hist">
      <span class="icone <?= $r['_cls'] ?>"><?= ic($icone) ?></span>
      <span class="meio"><b><?= e($r['origem']) ?> → <?= e($r['destino']) ?></b>
        <small><?= e(data_com_ano($r['data'])) ?></small><span class="etq-app <?= $r['_cls'] ?>"><?= e($r['_txt']) ?></span></span>
      <span class="preco"><?= e(formatar_mt($r['preco'])) ?></span>
    </<?= $ligacao ? 'a' : 'div' ?>>
  <?php endforeach; ?>
<?php endforeach; ?>
<?php app_fechar(['nav' => 'historico']); ?>
