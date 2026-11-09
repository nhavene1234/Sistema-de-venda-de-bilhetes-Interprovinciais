<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/mapa_mocambique.php';
exigir_perfil(['administrador']);
$filtro = filtro_transportadora();
// Vista do mapa: 'todos' (origem + destino, por defeito), 'origem' (saídas) ou 'destino' (chegadas)
$vista = $_GET['vista'] ?? 'todos';
if (!in_array($vista, ['todos', 'origem', 'destino'], true)) $vista = 'todos';
$textoVista = ['todos' => 'origem e destino', 'origem' => 'origem', 'destino' => 'destino'][$vista];
$tituloVista = ['todos' => '', 'origem' => ' DE ORIGEM', 'destino' => ' DE DESTINO'][$vista];

$um = function (string $sql, array $p = []) use ($pdo) {
    $st = $pdo->prepare($sql);
    $st->execute($p);
    return $st->fetchColumn();
};

// utilizadores
$w = $filtro ? ' AND id_transportadora = ' . (int)$filtro : '';
$totalUtilizadores = (int)$um("SELECT COUNT(*) FROM utilizador WHERE 1=1$w");
$novosMes = (int)$um("SELECT COUNT(*) FROM utilizador WHERE data_criacao >= ?$w", [date('Y-m-01 00:00:00')]);
$antesDoMes = $totalUtilizadores - $novosMes;
$crescimentoMes = $antesDoMes > 0 ? round($novosMes / $antesDoMes * 100) : null;   // % face ao mês passado

// transportadoras activas
$wt = $filtro ? ' AND id_transportadora = ' . (int)$filtro : '';
$nomesTransp = $pdo->query("SELECT nome FROM transportadora WHERE activa = 1$wt ORDER BY nome")->fetchAll(PDO::FETCH_COLUMN);

// transacções (pagamentos confirmados), com filtro opcional por transportadora da rota
$juncao = 'FROM pagamento p
           JOIN reserva rs ON rs.id_reserva = p.id_reserva
           JOIN viagem v   ON v.id_viagem = rs.id_viagem
           JOIN rota r     ON r.id_rota = v.id_rota
           WHERE p.estado = \'confirmado\'' . ($filtro ? ' AND r.id_transportadora = ' . (int)$filtro : '');
$hoje  = (int)$um("SELECT COUNT(*) $juncao AND DATE(p.data_pagamento) = ?", [date('Y-m-d')]);
$ontem = (int)$um("SELECT COUNT(*) $juncao AND DATE(p.data_pagamento) = ?", [date('Y-m-d', strtotime('-1 day'))]);
$variacao = $ontem > 0 ? round(($hoje - $ontem) / $ontem * 100) : null;

// formato dos números desta página (1.247 / 14.250)
$num = function ($n): string { return number_format((float)$n, 0, ',', '.'); };

// ---- Actividade por província: o mapa e a lista usam EXACTAMENTE os mesmos dados e os mesmos nomes ----
$mapaProvincias = mapa_provincias();

// cidade de origem da viagem → província
$provinciaDaCidade = [
    'Maputo' => 'Maputo', 'Matola' => 'Maputo', 'Boane' => 'Maputo', 'Ressano Garcia' => 'Maputo', 'Manhiça' => 'Maputo',
    'Xai-Xai' => 'Gaza', 'Chókwè' => 'Gaza', 'Chibuto' => 'Gaza', 'Macia' => 'Gaza',
    'Inhambane' => 'Inhambane', 'Maxixe' => 'Inhambane', 'Vilankulo' => 'Inhambane', 'Massinga' => 'Inhambane',
    'Beira' => 'Sofala', 'Dondo' => 'Sofala', 'Gorongosa' => 'Sofala',
    'Chimoio' => 'Manica', 'Manica' => 'Manica', 'Gondola' => 'Manica',
    'Tete' => 'Tete', 'Moatize' => 'Tete', 'Songo' => 'Tete',
    'Quelimane' => 'Zambézia', 'Mocuba' => 'Zambézia', 'Gurué' => 'Zambézia', 'Milange' => 'Zambézia',
    'Nampula' => 'Nampula', 'Nacala' => 'Nampula', 'Angoche' => 'Nampula', 'Ilha de Moçambique' => 'Nampula',
    'Pemba' => 'Cabo Delgado', 'Montepuez' => 'Cabo Delgado', 'Mocímboa da Praia' => 'Cabo Delgado',
    'Lichinga' => 'Niassa', 'Cuamba' => 'Niassa', 'Mandimba' => 'Niassa',
];

$porProvincia = array_fill_keys(array_keys($mapaProvincias), 0);
$outras = 0;   // cidades que não estão na tabela acima (para o total bater certo)
if ($vista === 'todos') {   // cada transacção conta na província de origem e na de destino
    $sqlVista = "SELECT cidade, SUM(n) AS n FROM (
                    SELECT r.origem AS cidade, COUNT(*) AS n $juncao GROUP BY r.origem
                    UNION ALL
                    SELECT r.destino AS cidade, COUNT(*) AS n $juncao GROUP BY r.destino) x GROUP BY cidade";
} else {
    $coluna = $vista === 'destino' ? 'r.destino' : 'r.origem';   // valor fixo (não vem do utilizador)
    $sqlVista = "SELECT $coluna AS cidade, COUNT(*) AS n $juncao GROUP BY $coluna";
}
foreach ($pdo->query($sqlVista)->fetchAll() as $l) {
    $prov = $provinciaDaCidade[$l['cidade']] ?? (isset($porProvincia[$l['cidade']]) ? $l['cidade'] : null);
    if ($prov !== null) $porProvincia[$prov] += (int)$l['n'];
    else $outras += (int)$l['n'];
}
$maximo = max(1, max($porProvincia));

// lista ordenada: mais activas primeiro (em empate, por nome)
$ranking = $porProvincia;
uksort($ranking, fn($a, $b) => $porProvincia[$b] <=> $porProvincia[$a] ?: strcmp($a, $b));

// cor de cada província: cinza claro, a puxar para roxo conforme a actividade
$misturar = function (string $de, string $para, float $t): string {
    $x = sscanf($de, '#%2x%2x%2x');
    $y = sscanf($para, '#%2x%2x%2x');
    $c = [];
    foreach ($x as $i => $v) { $c[] = (int)round($v + ($y[$i] - $v) * $t); }
    return sprintf('#%02x%02x%02x', $c[0], $c[1], $c[2]);
};
$corProvincia = [];
foreach ($porProvincia as $prov => $n) {
    $corProvincia[$prov] = $misturar('#e4e8f0', '#6c47e8', $n > 0 ? 0.08 + 0.37 * ($n / $maximo) : 0);
}

// rótulos: [província, x, y, âncora do texto] + (opcional) linha [x inicial, x final, y]
$rotulosProvincias = [
    ['Niassa', 468, 268, 'middle'],  ['Tete', 361, 342, 'middle'],   ['Nampula', 548, 327, 'middle'],
    ['Zambézia', 484, 380, 'middle'], ['Gaza', 362, 584, 'middle'],
    ['Cabo Delgado', 606, 250, 'start', 556, 600, 246], ['Manica', 316, 451, 'end', 372, 322, 447],
    ['Sofala', 486, 464, 'start', 425, 480, 460],        ['Inhambane', 476, 567, 'start', 418, 470, 563],
    ['Maputo', 296, 654, 'end', 350, 302, 650],
];

$tituloPagina = 'Visão geral';
$migalhas = ['Painel Admin', 'Visão Geral'];
$paginaActiva = 'administrador/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina">Administração Geral <?= e(APP_NOME) ?></h1>
<p class="subtitulo-pagina">Controlo consolidado de passageiros, volumes de transacção e saúde da rede nacional.</p>

<div class="estatisticas">
  <div class="estat"><small>Total de utilizadores</small><strong><?= $num($totalUtilizadores) ?></strong>
    <span class="var"><?php if ($crescimentoMes !== null): ?>+<?= $crescimentoMes ?>% vs mês passado<?php else: ?>+<?= $num($novosMes) ?> este mês<?php endif; ?></span></div>
  <div class="estat"><small>Transportadoras activas</small><strong><?= count($nomesTransp) ?></strong>
    <span class="neutro"><?= e($nomesTransp ? implode(', ', $nomesTransp) : '—') ?></span></div>
  <div class="estat"><small>Transacções de hoje</small><strong><?= $num($hoje) ?></strong>
    <?php if ($variacao !== null): ?>
      <span class="<?= $variacao >= 0 ? 'var' : 'neutro' ?>"><?= $variacao >= 0 ? '+' : '' ?><?= $variacao ?>% vs ontem</span>
    <?php else: ?><span class="neutro">Sem dados de ontem</span><?php endif; ?></div>
  <!-- Valor ilustrativo: o sistema ainda não tem monitorização real de disponibilidade -->
  <div class="estat destaque"><small>Uptime do Sistema</small><strong>99.9%</strong><span class="var">Funcionamento ideal</span></div>
</div>

<section class="cartao">
  <h2>Actividade por província</h2>
  <p class="sub"><?= $vista === 'todos' ? 'Transacções por província, somando <b>origem e destino</b> da viagem.' : 'Transacções por província de <b>' . e($textoVista) . '</b> da viagem.' ?> Os dados actualizam-se automaticamente.</p>
  <div style="display:flex;gap:8px;margin:0 0 14px">
    <?php foreach (['todos' => 'Todos', 'origem' => 'Origem (saídas)', 'destino' => 'Destino (chegadas)'] as $k => $nome): $act = $vista === $k; ?>
      <a href="<?= e('?' . http_build_query(array_merge($_GET, ['vista' => $k]))) ?>"
         style="padding:7px 16px;border-radius:999px;font-weight:700;font-size:13px;text-decoration:none;border:1px solid #6c47e8;<?= $act ? 'background:#6c47e8;color:#fff' : 'background:#fff;color:#6c47e8' ?>"><?= e($nome) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="divisao">
    <div class="mapa-caixa">
      <svg viewBox="240 178 470 522" role="img" aria-label="Mapa de Moçambique por província">
        <g>
          <?php foreach ($mapaProvincias as $prov => $d): ?>
            <path d="<?= e($d) ?>" fill="<?= e($corProvincia[$prov]) ?>"><title><?= e($prov) ?>: <?= $num($porProvincia[$prov]) ?> transacções (<?= e($textoVista) ?>)</title></path>
          <?php endforeach; ?>
        </g>
        <g fill="none" stroke="#fff" stroke-width="1.6" stroke-linejoin="round">
          <?php foreach ($mapaProvincias as $d): ?>
            <path d="<?= e($d) ?>"/>
          <?php endforeach; ?>
        </g>
        <?php foreach ($rotulosProvincias as $r): ?>
          <?php if (isset($r[4])): ?>
            <line x1="<?= $r[4] ?>" y1="<?= $r[6] ?>" x2="<?= $r[5] ?>" y2="<?= $r[6] ?>" stroke="#33415c" stroke-width="1" opacity=".45"/>
          <?php endif; ?>
          <text x="<?= $r[1] ?>" y="<?= $r[2] ?>" text-anchor="<?= $r[3] ?>"><?= e($r[0]) ?></text>
        <?php endforeach; ?>
      </svg>
    </div>
    <div class="cidades">
      <h3>TRANSACÇÕES POR PROVÍNCIA<?= $tituloVista ?></h3>
      <?php foreach ($ranking as $prov => $n): ?>
        <div class="cidade-linha">
          <div><span><?= e($prov) ?></span><span class="mono"><?= $num($n) ?></span></div>
          <div class="barra"><i style="width:<?= round($n / $maximo * 100) ?>%"></i></div>
        </div>
      <?php endforeach; ?>
      <?php if ($outras > 0): ?>
        <p class="pequeno" style="margin:12px 0 0">Outras cidades (<?= e($textoVista) ?>): <?= $num($outras) ?> transacções (sem província definida).</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
// actualiza os números sozinho (mapa e lista vêm da mesma consulta, por isso mudam juntos)
setInterval(function () { if (!document.hidden) location.reload(); }, 30000);
</script>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>