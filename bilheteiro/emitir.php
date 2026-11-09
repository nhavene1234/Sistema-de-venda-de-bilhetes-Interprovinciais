<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();

$grupo = $_GET['grupo'] ?? '';
$bilhetes = carregar_venda($pdo, $grupo, (int)$eu['id_utilizador'], 'confirmada');
if (!$bilhetes) { flash('erro', 'Venda não encontrada.'); redirecionar('bilheteiro/index.php'); }
$total = array_sum(array_map(fn($b) => (float)$b['valor'], $bilhetes));
$plural = count($bilhetes) > 1;

$tituloPagina = 'Emitir bilhete';
$migalhas = ['Bilheteira', 'Emitir Bilhete'];
$paginaActiva = 'bilheteiro/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<div class="banner-sucesso nao-imprimir"><span class="tick"><?= icone_svg('escudo') ?></span>
  <div><b>Venda registada com sucesso!</b>
    <?= $plural ? 'Os bilhetes foram emitidos e estão prontos' : 'O bilhete foi emitido e está pronto' ?> para impressão ou envio por SMS.</div></div>

<div class="emitir-grid">
  <?php foreach ($bilhetes as $b): ?>
    <article class="cartao-bilhete">
      <div class="logo-centro"><?= logo_html() ?></div>
      <p class="sub-bilhete">Comprovativo de viagem oficial</p>
      <div style="text-align:center;margin:2px 0 12px">
        <small style="display:block;color:#6b7a90;letter-spacing:.08em;font-size:11px">TRANSPORTADORA</small>
        <b style="display:block;font-size:20px;line-height:1.2"><?= e($b['transportadora']) ?></b>
      </div>
      <div class="qr-moldura"><div class="qr-item" data-codigo="<?= e($b['codigo_qr']) ?>"></div></div>
      <div class="ref-chip"><?= e($b['referencia']) ?></div>
      <dl class="dados-bilhete">
        <dt>Transportadora</dt><dd><b><?= e($b['transportadora']) ?></b></dd>
        <dt>Rota</dt><dd><b><?= e($b['origem']) ?> → <?= e($b['destino']) ?></b></dd>
        <dt>Data de partida</dt><dd><?= e(date('d/m/Y', strtotime($b['data']))) ?></dd>
        <dt>Hora de embarque</dt><dd><?= e(hora_curta($b['hora_partida'])) ?></dd>
        <dt>Lugar / assento</dt><dd><b>Assento <?= e($b['lugar']) ?></b></dd>
        <dt>Passageiro</dt><dd><?= e($b['nome_passageiro']) ?></dd>
        <dt>Método pagamento</dt><dd><?= e(nome_metodo($b['metodo'])) ?></dd>
        <dt>Valor</dt><dd><?= e(formatar_mt($b['valor'])) ?></dd>
      </dl>
    </article>
  <?php endforeach; ?>
</div>

<div class="cartao-bilhete rodape-emitir">
  <div class="total-pago"><span>Total pago</span><b><?= e(formatar_mt($total)) ?></b></div>
  <div class="acoes-emitir nao-imprimir">
    <button type="button" class="btn btn-sec" data-imprimir><?= icone_svg('documento') ?> Imprimir</button>
    <form method="post" action="<?= e(url('bilheteiro/enviar_sms.php')) ?>" style="display:contents">
      <?= csrf_campo() ?><input type="hidden" name="grupo" value="<?= e($grupo) ?>">
      <button class="btn btn-primario" type="submit"><?= icone_svg('sino') ?> Enviar por SMS</button>
    </form>
  </div>
  <a class="link-centro nao-imprimir" href="<?= e(url('bilheteiro/index.php')) ?>">+ Nova venda</a>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>