<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();

$idR = (int)($_GET['id'] ?? 0);
$r = carregar_reserva($pdo, $idR, (int)$u['id_utilizador']);
if (!$r || $r['estado'] !== 'confirmada') redirecionar('passageiro/bilhetes.php');
$prazoOk = dentro_do_prazo($pdo, $r) && $r['bilhete_estado'] === 'valido';
$horas = (int)config_valor($pdo, 'cancelamento_horas', 24);

app_abrir(['titulo' => 'Detalhes da reserva', 'voltar' => 'passageiro/bilhete.php?reserva=' . $idR,
           'h1' => 'Detalhes da reserva', 'sub' => $r['referencia']]);
?>
<div class="cartao-app">
  <div class="resumo-viagem">
    <div><small><?= e($r['transportadora']) ?></small><h3><?= e($r['origem']) ?> → <?= e($r['destino']) ?></h3>
      <small><?= e(data_com_ano($r['data'])) ?> · <?= e(hora_curta($r['hora_partida'])) ?></small></div>
    <div style="text-align:right"><span class="etq-app etq-verde">Confirmado</span>
      <div style="margin-top:8px"><span class="badge-lugar"><?= e($r['lugar']) ?></span></div></div>
  </div>
</div>

<div class="folha">
  <h2>O que pretende fazer?</h2>
  <p>A reserva é para <?= e(data_curta($r['data'])) ?> às <?= e(hora_curta($r['hora_partida'])) ?>. O cancelamento pode estar sujeito às condições da empresa.</p>
  <div class="politica"><b>Política da reserva</b>
    Pode cancelar ou remarcar até <?= $horas ?> horas antes da partida. O reembolso é processado em até 48 horas.</div>

  <?php if ($prazoOk): ?>
    <a class="btn-app btn-app-claro" href="<?= e(url('passageiro/remarcar.php?id=' . $idR)) ?>"><?= ic('remarcar') ?> Remarcar para outra data</a>
    <form method="post" action="<?= e(url('passageiro/cancelar.php')) ?>" data-confirmar="Cancelar esta reserva? O lugar será libertado.">
      <?= csrf_campo() ?><input type="hidden" name="reserva" value="<?= $idR ?>">
      <button class="btn-app btn-app-perigo" type="submit"><?= ic('lixo') ?> Cancelar reserva</button>
    </form>
  <?php else: ?>
    <div class="alerta alerta-info">O prazo para cancelar ou remarcar (<?= $horas ?> horas antes da partida) já terminou.</div>
  <?php endif; ?>
  <a class="link-centro" href="<?= e(url('passageiro/bilhete.php?reserva=' . $idR)) ?>">Manter a reserva</a>
</div>
<?php app_fechar(); ?>
