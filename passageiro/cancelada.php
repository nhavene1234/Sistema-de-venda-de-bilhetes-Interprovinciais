<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
$r = carregar_reserva($pdo, (int)($_GET['id'] ?? 0), (int)$u['id_utilizador']);
if (!$r || $r['estado'] !== 'cancelada') redirecionar('passageiro/bilhetes.php');

app_abrir(['titulo' => 'Reserva cancelada', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="estado-ecra">
  <div class="circulo circulo-ok"><?= ic('check') ?></div>
  <h1>Reserva cancelada</h1>
  <p>O cancelamento foi confirmado. O reembolso de <?= e(formatar_mt($r['preco'])) ?> será processado em até 48 horas.</p>
  <div class="estado-acoes"><a class="btn-app" href="<?= e(url('passageiro/bilhetes.php')) ?>"><?= ic('bilhete') ?> Voltar aos bilhetes</a></div>
</div>
<?php app_fechar(); ?>
