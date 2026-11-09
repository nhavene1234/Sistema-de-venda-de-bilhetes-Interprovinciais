<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();

$idR = (int)($_GET['reserva'] ?? 0);
$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r) redirecionar('passageiro/bilhetes.php');
if ($r['estado'] !== 'confirmada' || !$r['codigo_qr']) {
    flash('erro', 'Este bilhete não está activo.');
    redirecionar('passageiro/historico.php');
}
$usado = $r['bilhete_estado'] === 'usado';
$partilha = "Bilhete {$r['transportadora']} (MozTransport) {$r['referencia']}: {$r['origem']} → {$r['destino']}, " . data_com_ano($r['data']) . ' às ' . hora_curta($r['hora_partida']) . ", lugar {$r['lugar']}.";

// outros bilhetes pagos pelo mesmo passageiro nesta viagem (família, amigos...): cada lugar tem o seu QR
$st = $pdo->prepare("SELECT rs.id_reserva, a.numero AS lugar
                     FROM reserva rs JOIN assento a ON a.id_assento = rs.id_assento
                     WHERE rs.id_passageiro = ? AND rs.id_viagem = ? AND rs.estado = 'confirmada'");
$st->execute([$u['id_utilizador'], $r['id_viagem']]);
$irmaos = $st->fetchAll();
usort($irmaos, fn($x, $y) => strnatcmp($x['lugar'], $y['lugar']));
$posicao = 1;
foreach ($irmaos as $i => $b) { if ((int)$b['id_reserva'] === $idR) $posicao = $i + 1; }

app_abrir(['titulo' => 'Bilhete digital', 'voltar' => 'passageiro/bilhetes.php', 'h1' => 'Bilhete digital',
           'sub' => 'Apresente este QR no embarque',
           'head' => '<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>']);
?>
<?php if (count($irmaos) > 1): ?>
  <p class="pequeno" style="margin:0 0 8px;text-align:center">Bilhete <b><?= $posicao ?> de <?= count($irmaos) ?></b> desta viagem. Cada lugar tem o seu QR.</p>
  <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin:0 0 14px">
    <?php foreach ($irmaos as $b): $activo = (int)$b['id_reserva'] === $idR; ?>
      <a href="<?= e(url('passageiro/bilhete.php?reserva=' . (int)$b['id_reserva'])) ?>"
         style="padding:6px 14px;border-radius:999px;font-weight:700;font-size:13px;text-decoration:none;border:1px solid var(--azul);<?= $activo ? 'background:var(--azul);color:#fff' : 'background:#fff;color:var(--azul)' ?>">
        Lugar <?= e($b['lugar']) ?></a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="bilhete-cartao" style="margin-top:-6px">
  <div class="bilhete-topo"><?= logo_html('logo-pequeno') ?>
    <span class="etq-app <?= $usado ? 'etq-amarela' : 'etq-verde' ?>"><?= $usado ? 'Usado' : 'Pago' ?></span></div>
  <div class="bilhete-rota">
    <div><b><?= e($r['origem']) ?></b></div><?= ic('onibus') ?><div style="text-align:right"><b><?= e($r['destino']) ?></b></div>
  </div>
  <div style="text-align:center;margin:4px 0 10px">
    <small style="display:block;color:var(--cinza);letter-spacing:.08em;font-size:11px">TRANSPORTADORA</small>
    <b style="display:block;font-size:20px;line-height:1.2"><?= e($r['transportadora']) ?></b>
  </div>
  <div class="qr-caixa"><div id="qr" data-codigo="<?= e($r['codigo_qr']) ?>"></div></div>
  <div class="qr-ref"><?= e($r['referencia']) ?></div>
  <p class="qr-dica">Aumente o brilho do ecrã para facilitar a leitura.</p>
  <div class="bilhete-dados">
    <div><small>PASSAGEIRO</small><b><?= e($u['nome']) ?></b></div>
    <div class="dir"><small>ASSENTO</small><span class="grande"><?= e($r['lugar']) ?></span></div>
    <div><b><?= e(data_com_ano($r['data'])) ?> · <?= e(hora_curta($r['hora_partida'])) ?></b></div>
    <div class="dir"><small style="color:var(--cinza)"><?= e($r['transportadora']) ?></small></div>
  </div>
</div>

<div class="acoes-bilhete">
  <button class="btn-app" type="button" id="btn-guardar"><?= ic('descarregar') ?> Guardar</button>
  <button class="btn-app btn-app-claro" type="button" id="btn-partilhar" data-texto="<?= e($partilha) ?>"><?= ic('partilhar') ?> Partilhar</button>
</div>
<?php if (!$usado && partida_ts($r) > time()): ?>
  <p class="link-centro"><a href="<?= e(url('passageiro/reserva.php?id=' . $idR)) ?>" style="color:#d92d3a">Cancelar reserva</a></p>
<?php endif; ?>
<?php app_fechar(['nav' => 'bilhetes']); ?>