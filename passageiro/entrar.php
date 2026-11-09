<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/auth.php';
if ($u = utilizador_logado()) redirecionar(pagina_inicial_perfil($u['perfil']));

$erro = null; $bloqueio = 0; $emailPost = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailPost = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif ($emailPost === '' || $senha === '') {
        $erro = 'Preencha o email e a palavra-passe.';
    } else {
        $r = tentar_login($pdo, $emailPost, $senha, true);
        if ($r['ok']) redirecionar(pagina_inicial_perfil($r['perfil']));
        $erro = $r['erro']; $bloqueio = (int)$r['bloqueio'];
    }
}

app_abrir(['titulo' => 'Entrar', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="auth-app">
  <div class="auth-app-topo"><?= logo_html() ?><span class="selo"><?= ic('escudo-ok') ?> Seguro</span></div>
  <h1>Bem-vindo de volta</h1>
  <p class="sub">Entre para reservar e gerir as suas viagens.</p>

  <?php if ($bloqueio > 0): ?>
    <div class="alerta alerta-erro app-alerta"><?= ic('cadeado') ?>
      <div><strong>Acesso temporariamente bloqueado</strong>
        Muitas tentativas falhadas. Tente novamente em <b class="mono" data-contagem="<?= $bloqueio ?>" data-ao-terminar="recarregar"></b>.</div></div>
  <?php elseif ($erro): ?>
    <div class="alerta alerta-erro app-alerta"><?= ic('cadeado') ?><div><?= e($erro) ?></div></div>
  <?php endif; ?>

  <form method="post" autocomplete="on">
    <?= csrf_campo() ?>
    <div class="campo"><label for="email">Email</label>
      <div class="campo-ic"><?= ic('mail') ?><input type="email" id="email" name="email" value="<?= e($emailPost) ?>" placeholder="nome@email.co.mz" required></div></div>
    <div class="campo"><label for="senha">Palavra-passe</label>
      <div class="campo-ic"><?= ic('cadeado') ?><input type="password" id="senha" name="senha" placeholder="••••••••" required>
        <button type="button" class="olho" data-alternar-senha="senha" aria-label="Mostrar palavra-passe"><?= ic('olho') ?></button></div></div>
    <div class="campo-direita"><a href="<?= e(url('passageiro/recuperar.php?reiniciar=1')) ?>">Esqueci a palavra-passe</a></div>

    <?php if ($bloqueio > 0): ?>
      <button class="btn-app" type="button" disabled><?= ic('entrar') ?> Aguardar <span data-contagem="<?= $bloqueio ?>"></span></button>
    <?php else: ?>
      <button class="btn-app" type="submit"><?= ic('entrar') ?> Entrar</button>
    <?php endif; ?>
  </form>

  <p class="link-centro">Ainda não tem conta? <a href="<?= e(url('passageiro/criar_conta.php')) ?>">Criar conta</a></p>
  <p class="link-centro">Precisa de ajuda para entrar? <a href="<?= e(url('passageiro/ajuda.php')) ?>">Falar com o suporte</a></p>
  <p class="nota-segura"><?= ic('escudo-ok') ?> Os seus dados estão protegidos e encriptados.</p>

  <?php if (AMBIENTE_DEMO): ?>
    <div class="demo-nota"><b>Demonstração:</b> ioramo.satar@moztransport.co.mz · Demo@2026<br>
      Funcionário? <a href="<?= e(url('auth/login.php')) ?>"><b>Aceda ao painel</b></a></div>
  <?php endif; ?>
</div>
<?php app_fechar(); ?>
