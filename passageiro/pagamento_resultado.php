<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();

$idR = (int)($_GET['reserva'] ?? 0);
$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r) redirecionar('passageiro/index.php');
if (!$r['pagamento_estado'] || $r['pagamento_estado'] === 'pendente') redirecionar('passageiro/pagamento.php?reserva=' . $idR);

app_abrir(['titulo' => 'Pagamento', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
if ($r['pagamento_estado'] === 'falhado'):
    $nomeMetodo = ['mpesa' => 'M-Pesa', 'emola' => 'e-Mola', 'cartao' => 'do cartão'][$r['metodo']] ?? '';
?>
<div class="estado-ecra">
  <div class="circulo circulo-erro"><?= ic('x') ?></div>
  <h1>Não foi possível pagar</h1>
  <p>A transacção <?= e($nomeMetodo) ?> foi recusada. Verifique o saldo ou escolha outro meio de pagamento.</p>
  <span class="chip chip-erro"><?= ic('x') ?> <?= e($r['motivo_falha']) ?></span>
  <div class="estado-acoes">
    <a class="btn-app" href="<?= e(url('passageiro/pagamento.php?reserva=' . $idR)) ?>"><?= ic('troca') ?> Tentar outro meio de pagamento</a>
    <a class="btn-app btn-app-sec" href="<?= e(url('passageiro/pagamento.php?reserva=' . $idR)) ?>">Voltar ao resumo</a>
  </div>
</div>
<?php else: ?>
<div class="estado-ecra">
  <div class="circulo circulo-ok"><?= ic('check') ?></div>
  <h1>Pagamento concluído!</h1>
  <p>O seu lugar <?= e($r['lugar']) ?> está confirmado. O bilhete digital já está disponível.</p>
  <span class="chip chip-ok"><?= ic('escudo-ok') ?> Referência <?= e($r['referencia']) ?></span>
  <div class="estado-acoes">
    <a class="btn-app" href="<?= e(url('passageiro/bilhete.php?reserva=' . $idR)) ?>"><?= ic('bilhete') ?> Ver bilhete digital</a>
    <a class="btn-app btn-app-sec" href="<?= e(url('passageiro/index.php')) ?>">Voltar ao início</a>
  </div>
</div>
<?php endif;
app_fechar(); ?>
