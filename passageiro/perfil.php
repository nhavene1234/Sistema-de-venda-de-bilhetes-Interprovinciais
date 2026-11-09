<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$sessao = exigir_passageiro();

$st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
$st->execute([$sessao['id_utilizador']]);
$u = $st->fetch();

app_abrir(['titulo' => 'Perfil', 'h1' => 'Perfil', 'sub' => 'Conta e preferências']);
?>
<div class="perfil-cartao" style="margin-top:-6px">
  <?= avatar_html($u) ?>
  <div style="flex:1"><h3><?= e($u['nome']) ?></h3><small><?= e($u['telefone'] ?? $u['email']) ?></small>
    <span class="etq-app etq-verde">Conta activa</span></div>
  <a href="<?= e(url('passageiro/dados.php')) ?>" aria-label="Editar" style="color:var(--azul-forte)"><?= ic('editar') ?></a>
</div>

<div class="lista-op">
  <a class="op" href="<?= e(url('passageiro/dados.php')) ?>"><span class="icone"><?= ic('utilizador') ?></span>
    <span class="txt"><b>Dados pessoais</b><small>Nome, telefone e documento</small></span><?= ic('direita', 'seta') ?></a>
  <a class="op" href="<?= e(url('passageiro/senha.php')) ?>"><span class="icone"><?= ic('cadeado') ?></span>
    <span class="txt"><b>Alterar palavra-passe</b><small>Mantenha a sua conta segura</small></span><?= ic('direita', 'seta') ?></a>
  <a class="op" href="<?= e(url('passageiro/suporte.php')) ?>"><span class="icone"><?= ic('ajuda') ?></span>
    <span class="txt"><b>Ajuda e suporte</b><small>Pagamento, bilhete, palavra-passe...</small></span><?= ic('direita', 'seta') ?></a>
  <div class="op" style="cursor:default"><span class="icone"><?= ic('idioma') ?></span>
    <span class="txt"><b>Idioma</b><small>Português</small></span></div>
</div>

<div class="lista-op">
  <div class="op" style="cursor:default"><span class="icone"><?= ic('sino') ?></span>
    <span class="txt"><b>Notificações de viagem</b><small>Lembretes e alterações</small></span>
    <label class="sw"><input type="checkbox" id="sw-notificacoes" <?= (int)$u['notificacoes'] ? 'checked' : '' ?>
      data-url="<?= e(url('api/passageiro_notificacoes.php')) ?>" data-csrf="<?= e(csrf_token()) ?>"><span></span></label></div>
</div>

<div class="lista-op">
  <a class="op perigo" href="<?= e(url('auth/logout.php')) ?>"><span class="icone"><?= ic('sair') ?></span>
    <span class="txt"><b>Terminar sessão</b></span><?= ic('direita', 'seta') ?></a>
</div>
<p class="versao"><?= e(APP_NOME) ?> v1.0.0 · Feito em Moçambique</p>
<?php app_fechar(['nav' => 'perfil']); ?>
