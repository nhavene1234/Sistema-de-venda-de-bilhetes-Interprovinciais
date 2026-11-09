<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'ver_relatorios');
$idT = $transp['id'];

[$ini, $fim] = periodo_pedido(30);
$dias = (int)((strtotime($fim) - strtotime($ini)) / 86400) + 1;
$serie = serie_diaria($pdo, $idT, $ini, $fim);
$receita = array_sum(array_column($serie, 'receita'));
$bilhetes = array_sum(array_column($serie, 'n'));
$mediaDia = $dias ? $receita / $dias : 0;

// período anterior com a mesma duração, para o crescimento
$iniAnt = date('Y-m-d', strtotime("$ini -$dias days"));
$fimAnt = date('Y-m-d', strtotime("$ini -1 day"));
$ant = serie_diaria($pdo, $idT, $iniAnt, $fimAnt);
$crescimento = variacao_pct((float)$bilhetes, (float)array_sum(array_column($ant, 'n')));

$st = $pdo->prepare('SELECT ro.origem, ro.destino, COUNT(DISTINCT v.id_viagem) AS viagens, COUNT(*) AS bilhetes, SUM(p.valor) AS receita '
                    . SQL_PAGAMENTOS . ' AND DATE(p.data_pagamento) BETWEEN ? AND ? GROUP BY ro.origem, ro.destino ORDER BY receita DESC');
$st->execute([$idT, $ini, $fim]);
$porRota = $st->fetchAll();

if (isset($_GET['exportar'])) {   // "Excel": CSV com separador ; que o Excel abre directamente
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="relatorio_' . $ini . '_' . $fim . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Relatório financeiro', $transp['nome'], $ini . ' a ' . $fim], ';');
    fputcsv($out, ['Receita total (MT)', $receita], ';');
    fputcsv($out, ['Receita média diária (MT)', round($mediaDia, 2)], ';');
    fputcsv($out, ['Bilhetes vendidos', $bilhetes], ';');
    fputcsv($out, [], ';');
    fputcsv($out, ['Rota', 'Viagens', 'Bilhetes', 'Receita (MT)'], ';');
    foreach ($porRota as $r) fputcsv($out, [$r['origem'] . ' → ' . $r['destino'], $r['viagens'], $r['bilhetes'], (float)$r['receita']], ';');
    fputcsv($out, [], ';');
    fputcsv($out, ['Data', 'Bilhetes', 'Receita (MT)'], ';');
    foreach ($serie as $d => $x) fputcsv($out, [$d, $x['n'], $x['receita']], ';');
    fclose($out);
    exit;
}

$rotulos = array_map(fn($d) => date('d', strtotime($d)) . ' ' . MESES_CURTOS[(int)date('n', strtotime($d)) - 1], array_keys($serie));
$ano = (int)date('Y');
$atalhos = [
    'Hoje' => ['inicio' => date('Y-m-d'), 'fim' => date('Y-m-d')],
    'Ontem' => ['inicio' => date('Y-m-d', strtotime('-1 day')), 'fim' => date('Y-m-d', strtotime('-1 day'))],
    'Esta semana' => ['inicio' => date('Y-m-d', strtotime('monday this week')), 'fim' => date('Y-m-d')],
    '7 dias' => ['inicio' => date('Y-m-d', strtotime('-6 days')), 'fim' => date('Y-m-d')],
    '30 dias' => ['inicio' => date('Y-m-d', strtotime('-29 days')), 'fim' => date('Y-m-d')],
    'Este mês' => ['inicio' => date('Y-m-01'), 'fim' => date('Y-m-d')],
    'Este ano' => ['inicio' => date('Y-01-01'), 'fim' => date('Y-m-d')],
    'Época festiva' => ['inicio' => ($ano - (date('n') < 6 ? 1 : 0)) . '-12-01', 'fim' => min(date('Y-m-d'), ($ano + (date('n') < 6 ? 0 : 1)) . '-01-15')],
];

$tituloPagina = 'Relatórios';
$migalhas = ['Painel Gestão', 'Relatórios Financeiros'];
$paginaActiva = 'gestor/relatorios.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Relatórios e Facturação</h1>
    <p class="subtitulo-pagina">Gere análises de vendas, volume e facturamento consolidado <?= e($transp['nome']) ?>.</p></div>
  <div class="nao-imprimir" style="display:flex;gap:8px">
    <button type="button" class="btn btn-sec btn-pequeno" data-imprimir-pagina>Exportar PDF</button>
    <a class="btn btn-sec btn-pequeno" href="<?= e('?' . http_build_query(array_merge($_GET, ['exportar' => 1]))) ?>">Exportar Excel</a>
  </div>
</div>

<section class="cartao nao-imprimir">
  <form method="get" class="filtros-g" style="grid-template-columns:1fr 1fr auto">
    <div class="campo"><label>Data início</label><input type="date" name="inicio" value="<?= e($ini) ?>" max="<?= date('Y-m-d') ?>"></div>
    <div class="campo"><label>Data fim</label><input type="date" name="fim" value="<?= e($fim) ?>" max="<?= date('Y-m-d') ?>"></div>
    <button class="btn btn-verde" type="submit">Gerar relatório</button>
  </form>
  <div class="chips-periodo">
    <?php foreach ($atalhos as $nome => $per): ?><a href="<?= e('?' . http_build_query($per)) ?>"><?= e($nome) ?></a><?php endforeach; ?>
  </div>
</section>
<p class="pequeno" style="margin:10px 2px 0">Período: <?= e(date('d/m/Y', strtotime($ini))) ?> a <?= e(date('d/m/Y', strtotime($fim))) ?> (<?= $dias ?> dia<?= $dias === 1 ? '' : 's' ?>)</p>

<div class="kpis-3">
  <div class="kpi-grande"><small>Receita total</small><b><?= e(formatar_mt($receita)) ?></b><span class="var-pos">Facturação total no período</span></div>
  <div class="kpi-grande"><small>Receita média diária</small><b><?= e(formatar_mt($mediaDia)) ?></b><span class="var-pos">Média sobre <?= $dias ?> dia<?= $dias === 1 ? '' : 's' ?></span></div>
  <div class="kpi-grande"><small>Bilhetes vendidos</small><b><?= formatar_numero($bilhetes) ?> bilhetes</b><?= texto_variacao($crescimento, 'de crescimento') ?></div>
</div>

<div class="rel-grid">
  <section class="cartao"><h3>Evolução de receita diária</h3><p class="sub">Receita facturada por dia no período seleccionado.</p>
    <?= svg_linha(array_column($serie, 'receita'), $rotulos) ?></section>
  <section class="cartao"><h3>Detalhamento por rota</h3>
    <div class="detalhe-rota" style="margin-top:8px">
      <?php if (!$porRota): ?><p class="pequeno">Sem vendas no período.</p><?php endif; ?>
      <?php foreach ($porRota as $r): ?>
        <div class="item"><div><b><?= e($r['origem']) ?> → <?= e($r['destino']) ?></b>
          <small><?= (int)$r['viagens'] ?> viagens • <?= (int)$r['bilhetes'] ?> bilhetes</small></div><b><?= e(formatar_mt($r['receita'])) ?></b></div>
      <?php endforeach; ?>
    </div></section>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>