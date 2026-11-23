<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'ver_dashboard');
$idT = $transp['id'];
libertar_reservas_expiradas($pdo);

$u = utilizador_logado();
$hoje = date('Y-m-d');
$ontem = date('Y-m-d', strtotime('-1 day'));
$ini30 = date('Y-m-d', strtotime('-29 days'));
$s30 = serie_diaria($pdo, $idT, $ini30, $hoje);
$s7 = array_slice($s30, -7, null, true);

$receitaHoje = $s30[$hoje]['receita'];
$bilhetesHoje = $s30[$hoje]['n'];
$mediaDia = (array_sum(array_column($s30, 'receita')) - $receitaHoje) / 29;
$varReceita = variacao_pct($receitaHoje, $mediaDia);
$varBilhetes = variacao_pct((float)$bilhetesHoje, (float)$s30[$ontem]['n']);

// ocupação dos próximos 7 dias (lugares reservados / lugares disponíveis)
$st = $pdo->prepare('SELECT v.data, SUM(a.capacidade_total) AS cap, SUM(' . lugares_livres_sql() . ') AS oc
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro a ON a.id_autocarro = v.id_autocarro
                     WHERE ro.id_transportadora = ? AND v.estado = \'agendada\' AND v.data BETWEEN ? AND ? GROUP BY v.data ORDER BY v.data');
$st->execute([$idT, $hoje, date('Y-m-d', strtotime('+6 days'))]);
$ocup = []; $capTotal = 0; $ocTotal = 0;
foreach ($st->fetchAll() as $r) {
    $ocup[] = $r['cap'] > 0 ? round($r['oc'] / $r['cap'] * 100) : 0;
    $capTotal += (int)$r['cap']; $ocTotal += (int)$r['oc'];
}
$ocupMedia = $capTotal ? (int)round($ocTotal / $capTotal * 100) : 0;
$textoOcup = !$capTotal ? 'Sem viagens programadas' : ($ocupMedia >= 85 ? 'Quase lotado' : ($ocupMedia >= 60 ? 'Capacidade ideal' : 'Lugares livres'));

// viagens nas próximas 24 horas (compatível com PostgreSQL e MySQL)
$agora = date('Y-m-d H:i:s'); $limite = date('Y-m-d H:i:s', strtotime('+24 hours'));
$st = $pdo->prepare("SELECT COUNT(*) AS n, COALESCE(SUM(CASE WHEN v.estado = 'cancelada' THEN 1 ELSE 0 END), 0) AS canc
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                     WHERE ro.id_transportadora = ? AND v.estado IN ('agendada','em_curso','cancelada')
                       AND CONCAT(CAST(v.data AS VARCHAR), ' ', CAST(v.hora_partida AS VARCHAR)) BETWEEN ? AND ?");
$st->execute([$idT, $agora, $limite]);
$vi = $st->fetch();
$viagensActivas = (int)$vi['n'] - (int)$vi['canc'];
$cancelamentos = (int)$vi['canc'];

// receita por rota (últimos 30 dias)
$st = $pdo->prepare('SELECT ro.origem, ro.destino, SUM(p.valor) AS receita ' . SQL_PAGAMENTOS
                    . ' AND DATE(p.data_pagamento) BETWEEN ? AND ? GROUP BY ro.origem, ro.destino ORDER BY receita DESC LIMIT 4');
$st->execute([$idT, $ini30, $hoje]);
$porRota = $st->fetchAll();
$maxRota = $porRota ? max(1, (float)$porRota[0]['receita']) : 1;

// últimas vendas registadas
$st = $pdo->prepare("SELECT rs.estado, rs.id_reserva, COALESCE(p.data_pagamento, rs.data_reserva) AS quando,
                            COALESCE(rs.nome_passageiro, us.nome) AS passageiro, ro.origem, ro.destino, v.preco
                     FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                     LEFT JOIN pagamento p ON p.id_reserva = rs.id_reserva LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                     WHERE ro.id_transportadora = ? AND COALESCE(rs.nome_passageiro, us.nome) IS NOT NULL
                       AND COALESCE(rs.nome_passageiro, '') NOT LIKE 'Reservado (demonstra%'
                     ORDER BY quando DESC, rs.id_reserva DESC LIMIT 6");
$st->execute([$idT]);
$ultimas = $st->fetchAll();
$estados = ['confirmada' => ['Confirmado', 'etq-pago'], 'pendente' => ['Pendente', 'etq-pendente'],
            'cancelada' => ['Cancelado', 'etq-cancelado'], 'expirada' => ['Expirado', 'etq-expirado']];

$h = (int)date('G');
$saudacao = $h < 12 ? 'Bom dia' : ($h < 19 ? 'Boa tarde' : 'Boa noite');
$rotulos30 = array_map(fn($d) => date('j', strtotime($d)) . ' ' . MESES_CURTOS[(int)date('n', strtotime($d)) - 1], array_keys($s30));

$tituloPagina = 'Dashboard';
$migalhas = ['Painel Gestão', 'Painel de Monitorização'];
$paginaActiva = 'gestor/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina"><?= e($saudacao) ?>, <?= e($u['nome']) ?></h1>
<p class="subtitulo-pagina">Hoje, <?= e(data_extensa($hoje)) ?> • Visão geral do desempenho da frota <?= e($transp['nome']) ?></p>

<div class="kpis">
  <div class="kpi"><small>Receita hoje</small>
    <div class="kpi-linha"><b><?= e(formatar_mt($receitaHoje)) ?></b><?= svg_sparkline(array_column($s7, 'receita')) ?></div>
    <?= texto_variacao($varReceita, 'vs média') ?></div>
  <div class="kpi"><small>Bilhetes vendidos</small>
    <div class="kpi-linha"><b><?= $bilhetesHoje ?> bilhete<?= $bilhetesHoje === 1 ? '' : 's' ?></b><?= svg_sparkline(array_column($s7, 'n')) ?></div>
    <?= texto_variacao($varBilhetes, 'vs ontem') ?></div>
  <div class="kpi"><small>Ocupação média (7 dias)</small>
    <div class="kpi-linha"><b><?= $ocupMedia ?>%</b><?= svg_sparkline($ocup, '#e69500') ?></div>
    <span class="var-aviso"><?= e($textoOcup) ?></span></div>
  <div class="kpi"><small>Viagens activas (24 h)</small>
    <div class="kpi-linha"><b><?= $viagensActivas ?> activas</b></div>
    <span class="<?= $cancelamentos ? 'var-neg' : 'var-neutra' ?>"><?= $cancelamentos ? $cancelamentos . ' cancelada(s)' : 'Sem incidentes' ?></span></div>
</div>

<div class="dash-grid">
  <section class="cartao">
    <h3>Vendas dos últimos 30 dias</h3>
    <p class="sub">Valores totais de facturação diária em meticais (MT).</p>
    <?= svg_linha(array_column($s30, 'receita'), $rotulos30) ?>
  </section>
  <section class="cartao">
    <h3>Receita por rota</h3>
    <p class="sub">Volume total facturado por principais destinos (30 dias).</p>
    <div class="barras-rota">
      <?php if (!$porRota): ?><p class="pequeno">Ainda sem vendas neste período.</p><?php endif; ?>
      <?php foreach ($porRota as $r): ?>
        <div class="linha"><div><span><?= e($r['origem']) ?> → <?= e($r['destino']) ?></span><b><?= e(formatar_mt($r['receita'])) ?></b></div>
          <div class="barra-verde"><i style="width:<?= round($r['receita'] / $maxRota * 100) ?>%"></i></div></div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

<section class="cartao">
  <h3 style="margin-bottom:8px">Últimas vendas registadas</h3>
  <div class="lista-vendas">
    <?php if (!$ultimas): ?><p class="pequeno">Ainda não há vendas registadas.</p><?php endif; ?>
    <?php foreach ($ultimas as $l): [$txt, $cls] = $estados[$l['estado']] ?? ['—', 'etq-pendente']; ?>
      <div class="item"><b><?= e($l['passageiro']) ?></b><span class="rota"><?= e($l['origem']) ?> → <?= e($l['destino']) ?></span>
        <b><?= e(formatar_mt($l['preco'])) ?></b><span class="etq-g <?= $cls ?>"><?= e($txt) ?></span></div>
    <?php endforeach; ?>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
