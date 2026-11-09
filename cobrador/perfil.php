<?php
require_once __DIR__ . '/_base.php';
$c = cb_ctx($pdo, $u);
$v = $c['viagem'];
$d = $c['destaque'];   // viagem de hoje ou, se não houver, a próxima atribuída
$msg = flash_obter();
$apoio = trim((string)config_valor($pdo, 'apoio_telefone', ''));
$ini = mb_strtoupper(mb_substr($u['nome'], 0, 1));

cb_abrir('Perfil');
?>
<header class="topo simples">
  <div class="topo-linha"><div><h1>Perfil</h1><p>Cobrador / Motorista</p></div>
    <a class="topo-btn" href="<?= e(url('cobrador/dados.php')) ?>" aria-label="Editar perfil"><?= cb_ic('gear') ?></a></div>
</header>

<?php if ($msg): ?><div class="aviso-cb <?= $msg['tipo'] === 'ok' ? 'ok' : 'erro' ?>"><?= e($msg['msg']) ?></div><?php endif; ?>
<div class="cartao-p">
  <div class="foto"><?php if (!empty($u['foto'])): ?><img src="<?= e(url($u['foto'])) ?>" alt=""><?php else: ?><?= e($ini) ?><?php endif; ?></div>
  <div><h2><?= e($u['nome']) ?></h2><p>Cobrador</p>
    <?php if ($v): ?><span class="pilula"><?= cb_ic('radio', 16) ?> Turno activo</span><?php endif; ?></div>
</div>

<div class="grupo">
  <div class="item"><span class="ico"><?= cb_ic('id') ?></span><div class="txt"><b>Motorista<?= $d && $d['data'] !== date('Y-m-d') ? ' (' . e(date('d/m', strtotime($d['data']))) . ')' : '' ?></b><small><?= e(($d['motorista'] ?? '') !== '' ? $d['motorista'] . ' · ' . $d['motorista_codigo'] : '—') ?></small></div><?= cb_ic('chev', 18) ?></div>
  <div class="item"><span class="ico"><?= cb_ic('building') ?></span><div class="txt"><b>Empresa</b><small><?= e($c['empresa'] ?: 'Ainda sem transportadora') ?></small></div><?= cb_ic('chev', 18) ?></div>
  <div class="item"><span class="ico"><?= cb_ic('bus') ?></span><div class="txt"><b>Autocarro atribuído</b><small class="mono"><?= e($d ? (($d['codigo_frota'] ? $d['codigo_frota'] . ' · ' : '') . $d['matricula']) : 'Sem autocarro atribuído') ?></small></div><?= cb_ic('chev', 18) ?></div>
</div>

<?php if ($apoio !== ''): ?>
<a class="apoio" href="tel:<?= e(preg_replace('/[^\d+]/', '', $apoio)) ?>"><?= cb_ic('headset') ?>
  <div class="txt"><b>Apoio à operação</b><small>Linha interna · 24 horas</small></div><?= cb_ic('phone') ?></a>
<?php endif; ?>

<a class="apoio senha-link" href="<?= e(url('cobrador/dados.php')) ?>"><?= cb_ic('id') ?>
  <div class="txt"><b>Dados pessoais e foto</b><small>Nome, email, telefone e foto de perfil</small></div><?= cb_ic('chev', 18) ?></a>

<a class="apoio senha-link" href="<?= e(url('cobrador/senha.php')) ?>"><?= cb_ic('lock') ?>
  <div class="txt"><b>Alterar palavra-passe</b><small>Mantenha a sua conta segura</small></div><?= cb_ic('chev', 18) ?></a>

<a class="sair" href="<?= e(url('auth/logout.php')) ?>"><span class="ico"><?= cb_ic('logout') ?></span>Terminar sessão</a>
<p class="versao"><?= e(APP_NOME) ?> Operações v1.0.0</p>
<?php cb_fechar('perfil'); cb_fim(); ?>
