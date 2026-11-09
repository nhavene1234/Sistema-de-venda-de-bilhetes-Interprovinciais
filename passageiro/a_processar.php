<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();

$idR = (int)($_GET['reserva'] ?? 0);
$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r || !$r['pagamento_estado'] || $r['pagamento_estado'] === 'pendente') redirecionar('passageiro/index.php');

$texto = ['mpesa' => 'Confirme a solicitação M-Pesa no seu telefone.', 'emola' => 'Confirme a solicitação e-Mola no seu telefone.',
          'cartao' => 'A validar os dados do seu cartão.'][$r['metodo']] ?? 'A confirmar o pagamento.';

app_abrir(['titulo' => 'A processar pagamento', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="estado-ecra">
  <div class="spinner"></div>
  <h1>A processar pagamento</h1>
  <p><?= e($texto) ?> Não feche esta janela.</p>
  <span class="chip chip-ok"><?= ic('escudo-ok') ?> Pagamento seguro · <?= e(formatar_mt($r['preco'])) ?></span>
  <p style="margin-top:auto;font-size:12px;padding-top:30px">Isto pode demorar alguns segundos.</p>
</div>
<span data-redirecionar="<?= e(url('passageiro/pagamento_resultado.php?reserva=' . $idR)) ?>" data-atraso="2800"></span>
<?php app_fechar(); ?>
