<?php
require_once __DIR__ . '/_base.php';
$c = cb_ctx($pdo, $u);
$v = $c['viagem'];
$h = (int)date('G');
$saud = $h < 12 ? 'Bom dia' : ($h < 19 ? 'Boa tarde' : 'Boa noite');
$primeiro = explode(' ', trim($u['nome']))[0];
$pct = $c['total'] ? $c['emb'] / $c['total'] : 0;
$circ = 2 * M_PI * 52;
$hoje = date('Y-m-d');
$futuras = array_values(array_filter($c['proximas'], fn($x) => $x['data'] !== $hoje));

cb_abrir('Viagem');
?>
<header class="topo">
  <div class="topo-linha"><?= logo_html('logo-claro') ?>
    <div class="topo-acoes"><span class="pilula"><?= cb_ic('radio', 16) ?> <?= $v ? 'Em serviço' : 'Sem viagem hoje' ?></span>
      <?= sino_notificacoes('topo-btn', cb_ic('bell', 20)) ?>
      <a class="topo-btn" href="<?= e(url('auth/logout.php')) ?>" aria-label="Terminar sessão" title="Terminar sessão"><?= cb_ic('logout', 20) ?></a></div></div>
  <h1><?= e($saud) ?>, <?= e($primeiro) ?></h1>
  <p>Cobrador · <?= e($c['empresa']) ?></p>
</header>

<div class="sec">
  <div class="sec-cab"><h2>Viagem de hoje</h2><?php if ($v): ?><a href="<?= e(url('cobrador/passageiros.php')) ?>">Ver detalhes</a><?php endif; ?></div>
<?php if (!$c['id_transp']): ?>
  <div class="vazio" style="margin:0">A sua conta ainda não está associada a uma transportadora. Contacte o gestor.</div>
<?php elseif (!$v): ?>
  <div class="vazio" style="margin:0"><?= $futuras ? 'Não tem viagem para hoje. As suas próximas viagens estão abaixo.' : 'Não tem nenhuma viagem atribuída. Contacte o gestor da transportadora.' ?></div>
<?php else: ?>
<?php if (count($c['viagens']) > 1): ?>
  <div class="viagens-hoje">
    <?php foreach ($c['viagens'] as $x): ?>
      <a class="<?= (int)$x['id_viagem'] === (int)$v['id_viagem'] ? 'activo' : '' ?>" href="<?= e(url('cobrador/index.php?v=' . (int)$x['id_viagem'])) ?>">
        <b><?= e(substr($x['hora_partida'], 0, 5)) ?></b><span><?= e($x['destino']) ?></span></a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
  <div class="cartao-v">
    <div class="linha1">
      <div><h3><?= e($v['origem']) ?> → <?= e($v['destino']) ?></h3>
        <small><?= e($c['empresa']) ?> · <?= e(explode(' · ', (string)$v['tipo_servico'])[0]) ?></small>
        <small>Motorista: <b><?= e($v['motorista'] ? $v['motorista'] . ' · ' . $v['motorista_codigo'] : 'por definir') ?></b></small>
        <small>Autocarro: <b><?= e(($v['codigo_frota'] ? $v['codigo_frota'] . ' · ' : '') . $v['matricula']) ?></b></small></div>
      <span class="etq"><?= cb_ic('clock', 15) ?> <?= $c['falta'] > 0 ? 'A embarcar' : 'Completo' ?></span>
    </div>
    <div class="linha2">
      <div class="partida"><small>Partida</small><b><?= e(substr($v['hora_partida'], 0, 5)) ?></b><span class="mono" style="font-size:12px;color:#667085"><?= e($v['matricula']) ?></span></div>
      <div class="anel">
        <svg width="132" height="132" viewBox="0 0 132 132"><circle cx="66" cy="66" r="52" fill="none" stroke="#e8f7fc" stroke-width="12"/>
          <circle cx="66" cy="66" r="52" fill="none" stroke="#1ba6e0" stroke-width="12" stroke-linecap="round" stroke-dasharray="<?= round($circ, 1) ?>" stroke-dashoffset="<?= round($circ * (1 - $pct), 1) ?>"/></svg>
        <div class="centro"><b><?= $c['emb'] ?> / <?= $c['total'] ?></b><span>embarcados</span></div>
      </div>
    </div>
  </div>
  <a class="btn-validar" href="<?= e(url('cobrador/validar.php')) ?>"><?= cb_ic('scan', 20) ?> Validar bilhete (QR)</a>
  <div class="kpis">
    <div class="kpi verde"><b><?= $c['emb'] ?></b><span>Embarcados</span></div>
    <div class="kpi amarelo"><b><?= $c['falta'] ?></b><span>Em falta</span></div>
  </div>
<?php endif; ?>
</div>

<?php if ($c['id_transp'] && $futuras): ?>
<div class="sec">
  <div class="sec-cab"><h2>Próximas viagens</h2></div>
  <?php foreach ($futuras as $x): ?>
    <div class="prox">
      <div class="prox-cab"><b><?= e(date('d/m', strtotime($x['data']))) ?> · <?= e(substr($x['hora_partida'], 0, 5)) ?></b><span><?= e($x['origem'] . ' → ' . $x['destino']) ?></span></div>
      <div class="prox-linha"><?= cb_ic('user', 16) ?> <span>Motorista</span><b><?= e($x['motorista'] ?: 'por definir') ?></b></div>
      <div class="prox-linha"><?= cb_ic('bus', 16) ?> <span>Autocarro</span><b><?= e(($x['codigo_frota'] ? $x['codigo_frota'] . ' · ' : '') . $x['matricula']) ?></b></div>
    </div>
  <?php endforeach; ?>
  <p class="nota-prox">A validação de bilhetes abre no dia de cada viagem.</p>
</div>
<?php endif; ?>
<?php cb_fechar('viagem'); cb_fim(); ?>