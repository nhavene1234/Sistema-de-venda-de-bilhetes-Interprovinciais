<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'ver_relatorios');
$idT = $transp['id'];

$ini90 = date('Y-m-d', strtotime('-89 days'));
$hoje = date('Y-m-d');
$juncao = 'FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
           WHERE ro.id_transportadora = ? AND DATE(rs.data_reserva) BETWEEN ? AND ?';

// rotas mais procuradas (quota das reservas nos últimos 90 dias)
$st = $pdo->prepare("SELECT ro.origem, ro.destino, COUNT(*) AS n $juncao GROUP BY ro.origem, ro.destino ORDER BY n DESC LIMIT 4");
$st->execute([$idT, $ini90, $hoje]);
$top = $st->fetchAll();
$st = $pdo->prepare("SELECT COUNT(*) $juncao");
$st->execute([$idT, $ini90, $hoje]);
$totalReservas = max(1, (int)$st->fetchColumn());

// densidade por dia da semana (1=Dom .. 7=Sáb, igual ao DAYOFWEEK do MySQL) e hora do dia
$st = $pdo->prepare("SELECT (EXTRACT(DOW FROM rs.data_reserva)::int + 1) AS d, EXTRACT(HOUR FROM rs.data_reserva)::int AS h, COUNT(*) AS n $juncao GROUP BY d, h");
$st->execute([$idT, $ini90, $hoje]);
$dias = ['Seg' => 2, 'Ter' => 3, 'Qua' => 4, 'Qui' => 5, 'Sex' => 6, 'Sáb' => 7, 'Dom' => 1];
$linhasHora = ['05:00', '08:00', '12:00', '15:00', '18:00', '22:00'];
$balde = function (int $h): int {
    if ($h >= 5 && $h <= 7) return 0;
    if ($h <= 11 && $h >= 8) return 1;
    if ($h >= 12 && $h <= 14) return 2;
    if ($h >= 15 && $h <= 17) return 3;
    if ($h >= 18 && $h <= 21) return 4;
    return 5;
};
$matriz = [];
foreach ($st->fetchAll() as $r) {
    $b = $balde((int)$r['h']);
    $matriz[$b][(int)$r['d']] = ($matriz[$b][(int)$r['d']] ?? 0) + (int)$r['n'];
}
$maxCalor = 1;
foreach ($matriz as $l) $maxCalor = max($maxCalor, max($l));
$cor = function (int $n) use ($maxCalor): string {
    if ($n === 0) return '#eef2f7';
    $t = $n / $maxCalor;
    return sprintf('rgb(%d,%d,%d)', 230 + (18 - 230) * $t, 247 + (161 - 247) * $t, 238 + (80 - 238) * $t);
};

// épocas de maior procura: reservas confirmadas por mês de viagem, no ano actual
$ano = (int)date('Y');
$st = $pdo->prepare("SELECT EXTRACT(MONTH FROM v.data)::int AS m, COUNT(*) AS n FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem
                     JOIN rota ro ON ro.id_rota = v.id_rota WHERE ro.id_transportadora = ? AND rs.estado = 'confirmada' AND EXTRACT(YEAR FROM v.data)::int = ? GROUP BY m");
$st->execute([$idT, $ano]);

$st->execute([$idT, $ano]);
$meses = array_fill(1, 12, 0);
foreach ($st->fetchAll() as $r) $meses[(int)$r['m']] = (int)$r['n'];
$pico = max($meses) > 0 ? array_search(max($meses), $meses, true) : null;

$tituloPagina = 'Estatísticas';
$migalhas = ['Painel Gestão', 'Estatísticas'];
$paginaActiva = 'gestor/estatisticas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina">Estatísticas e Procura de Rotas</h1>
<p class="subtitulo-pagina">Monitore a densidade de tráfego, horários de pico e sazonalidade das rotas exclusivas <?= e($transp['nome']) ?>.</p>

<div class="est-grid">
  <section class="cartao ranking">
    <h3>Rotas mais procuradas</h3>
    <p class="sub">Quota das reservas por rota (últimos 90 dias).</p>
    <?php if (!$top): ?><p class="pequeno">Ainda não há reservas neste período.</p><?php endif; ?>
    <?php foreach ($top as $i => $r): $pct = (int)round($r['n'] / $totalReservas * 100); ?>
      <div class="linha"><span class="pos"><?= $i + 1 ?>º</span><span><?= e($r['origem']) ?> → <?= e($r['destino']) ?></span><b><?= $pct ?>%</b>
        <div class="barra-verde"><i style="width:<?= max(2, $pct) ?>%"></i></div></div>
    <?php endforeach; ?>
  </section>

  <section class="cartao">
    <h3>Densidade de procura por horário e dia</h3>
    <p class="sub">Momento em que os passageiros reservam (tons mais escuros representam pico).</p>
    <table class="calor">
      <thead><tr><th class="h"></th><?php foreach ($dias as $nome => $d): ?><th><?= e($nome) ?></th><?php endforeach; ?></tr></thead>
      <tbody>
      <?php foreach ($linhasHora as $b => $rotulo): ?>
        <tr><th class="h"><?= e($rotulo) ?></th>
          <?php foreach ($dias as $nome => $d): $n = $matriz[$b][$d] ?? 0; ?>
            <td style="background:<?= $cor($n) ?>" title="<?= e($nome . ' · ' . $rotulo . ': ' . $n . ' reservas') ?>"></td>
          <?php endforeach; ?></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</div>

<section class="cartao">
  <h3>Épocas de maior procura (anual)</h3>
  <p class="sub">Bilhetes confirmados por mês de viagem em <?= $ano ?>.</p>
  <?= svg_linha(array_values($meses), array_map(fn($m) => MESES_CURTOS[$m - 1], array_keys($meses)), '#12a150',
                $pico ? 'PICO DE ' . mb_strtoupper(MESES_LONGOS[$pico - 1]) : null, 'num') ?>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
