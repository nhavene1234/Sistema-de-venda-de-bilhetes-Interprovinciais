<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
if (!utilizador_logado()) redirecionar('passageiro/splash.php');
$u = exigir_passageiro();

$origens  = $pdo->query('SELECT DISTINCT origem FROM rota WHERE activa = 1 ORDER BY origem')->fetchAll(PDO::FETCH_COLUMN);
$destinos = $pdo->query('SELECT DISTINCT destino FROM rota WHERE activa = 1 ORDER BY destino')->fetchAll(PDO::FETCH_COLUMN);
$amanha = date('Y-m-d', strtotime('+1 day'));

// rotas mais reservadas (completa com sugestões, se ainda houver poucas)
$populares = $pdo->query("SELECT ro.origem, ro.destino, COUNT(*) n FROM reserva rs
                          JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                          WHERE rs.estado = 'confirmada' GROUP BY ro.origem, ro.destino ORDER BY n DESC LIMIT 3")->fetchAll();
$tem = array_map(fn($p) => $p['origem'] . '|' . $p['destino'], $populares);
foreach ([['Maputo', 'Beira'], ['Maputo', 'Nampula'], ['Maputo', 'Xai-Xai']] as [$o, $d]) {
    if (count($populares) >= 3) break;
    if (in_array($o, $origens, true) && in_array($d, $destinos, true) && !in_array("$o|$d", $tem, true)) {
        $populares[] = ['origem' => $o, 'destino' => $d];
    }
}
$epocaFestiva = in_array((int)date('n'), [10, 11, 12, 1], true);

app_abrir(['titulo' => 'Início', 'logo' => true, 'h1' => 'Olá, ' . primeiro_nome($u['nome']),
           'acao' => sino_notificacoes('btn-icone', ic('sino'))]);
?>
<?php if (!$origens): ?>
  <div class="cartao-app" style="text-align:center;color:var(--cinza)">Ainda não há rotas disponíveis.
    <?php if (AMBIENTE_DEMO): ?><br><small>Execute <b>instalar/criar_dados_demo.php</b> para criar viagens de teste.</small><?php endif; ?></div>
<?php else: ?>
<form class="cartao-app pesquisa" method="get" action="<?= e(url('passageiro/resultados.php')) ?>" style="margin-top:-18px">
  <div class="pesq-linha"><?= ic('pin') ?><label><small>Origem</small>
    <select name="origem"><?php foreach ($origens as $o): ?><option <?= $o === 'Maputo' ? 'selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?></select></label></div>
  <div class="pesq-linha"><?= ic('pin') ?><label><small>Destino</small>
    <select name="destino"><?php foreach ($destinos as $d): ?><option <?= $d === 'Beira' ? 'selected' : '' ?>><?= e($d) ?></option><?php endforeach; ?></select></label></div>
  <div class="pesq-meio">
    <div class="pesq-linha"><?= ic('calendario') ?><label><small>Data</small>
      <input type="date" name="data" min="<?= date('Y-m-d') ?>" value="<?= $amanha ?>" required></label></div>
    <div class="pesq-linha"><?= ic('pessoas') ?><label><small>Passageiros</small>
      <select name="passageiros"><option value="1">1 adulto</option></select></label></div>
  </div>
  <button class="btn-app" type="submit"><?= ic('pesquisa') ?> Pesquisar viagens</button>
</form>

<div class="titulo-sec"><h2>Rotas populares</h2></div>
<div class="chips-rotas">
  <?php foreach ($populares as $p): ?>
    <a class="chip-rota" href="<?= e(url('passageiro/resultados.php?' . http_build_query(['origem' => $p['origem'], 'destino' => $p['destino'], 'data' => $amanha]))) ?>">
      <?= ic('pin') ?><?= e($p['origem']) ?> →<b><?= e($p['destino']) ?></b></a>
  <?php endforeach; ?>
</div>
<?php if ($epocaFestiva): ?>
  <div class="banner-festa"><?= ic('estrela') ?><div><b>Época festiva</b><span>Reserve com antecedência e viaje tranquilo.</span></div></div>
<?php endif; ?>
<?php endif; ?>
<?php app_fechar(['nav' => 'inicio']); ?>
