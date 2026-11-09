<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$nome = $_SESSION['conta_criada'] ?? null;
if (!$nome) redirecionar('passageiro/entrar.php');
unset($_SESSION['conta_criada']);

app_abrir(['titulo' => 'Conta criada', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="estado-ecra">
  <div class="circulo circulo-ok"><?= ic('check') ?></div>
  <h1>Conta criada com sucesso!</h1>
  <p>Bem-vindo à <?= e(APP_NOME) ?>, <?= e($nome) ?>. Já pode reservar a sua primeira viagem.</p>
  <div class="estado-acoes"><a class="btn-app" href="<?= e(url('passageiro/entrar.php')) ?>"><?= ic('entrar') ?> Entrar na minha conta</a></div>
</div>
<?php app_fechar(); ?>
