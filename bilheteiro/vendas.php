<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();

$data = $_GET['data'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || $data > date('Y-m-d')) $data = date('Y-m-d');
$ontem = date('Y-m-d', strtotime($data . ' -1 day'));

$base = "FROM pagamento p JOIN reserva rs ON rs.id_reserva = p.id_reserva
         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
         JOIN assento a ON a.id_assento = rs.id_assento
         WHERE rs.id_bilheteiro = ? AND p.estado = 'confirmado' AND DATE(p.data_pagamento) = ?";
$um = function (string $sql, array $p) use ($pdo) { $st = $pdo->prepare($sql); $st->execute($p); return $st->fetch(); };

$res  = $um("SELECT COUNT(*) AS n, COALESCE(SUM(p.valor),0) AS total $base", [$eu['id_utilizador'], $data]);
$resO = $um("SELECT COALESCE(SUM(p.valor),0) AS total $base", [$eu['id_utilizador'], $ontem]);
$totalHoje = (float)$res['total']; $nBilhetes = (int)$res['n']; $totalOntem = (float)$resO['total'];
$variacao = $totalOntem > 0 ? round(($totalHoje - $totalOntem) / $totalOntem * 100) : null;
$media = $nBilhetes ? $totalHoje / $nBilhetes : 0;

$st = $pdo->prepare("SELECT p.metodo, COUNT(*) AS n $base GROUP BY p.metodo ORDER BY n DESC LIMIT 1");
$st->execute([$eu['id_utilizador'], $data]);
$topo = $st->fetch();

$sqlLista = "SELECT p.data_pagamento, p.valor, p.metodo, rs.nome_passageiro, rs.referencia, ro.origem, ro.destino, a.numero AS lugar $base ORDER BY p.data_pagamento DESC, rs.id_reserva DESC";

if (isset($_GET['exportar'])) {
    $st = $pdo->prepare($sqlLista);
    $st->execute([$eu['id_utilizador'], $data]);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="vendas_' . $data . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Hora', 'Passageiro', 'Rota', 'Assento', 'Valor (MT)', 'Método', 'Referência'], ';');
    foreach ($st->fetchAll() as $l) {
        fputcsv($out, [date('H:i', strtotime($l['data_pagamento'])), $l['nome_passageiro'], $l['origem'] . ' → ' . $l['destino'],
                       $l['lugar'], (float)$l['valor'], nome_metodo($l['metodo']), $l['referencia']], ';');
    }
    fclose($out);
    exit;
}

$porPagina = 7;
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$paginas = max(1, (int)ceil($nBilhetes / $porPagina));
$pagina = min($pagina, $paginas);
$offset = ($pagina - 1) * $porPagina;
$st = $pdo->prepare($sqlLista . " LIMIT $porPagina OFFSET $offset");
$st->execute([$eu['id_utilizador'], $data]);
$lista = $st->fetchAll();

function ligacao_vendas(array $extra): string {
    return '?' . http_build_query(array_merge($_GET, $extra));
}

$tituloPagina = 'Vendas do dia';
$migalhas = ['Bilheteira', 'Vendas do Dia'];
$paginaActiva = 'bilheteiro/vendas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="stats-3">
  <div class="stat"><small>Total vendido <?= $data === date('Y-m-d') ? 'hoje' : 'em ' . e(data_curta($data)) ?></small>
    <b><?= e(formatar_mt($totalHoje)) ?></b>
    <?php if ($variacao !== null): ?><span class="<?= $variacao >= 0 ? 'verde' : 'vermelho' ?>"><?= $variacao >= 0 ? '↗ +' : '↘ ' ?><?= $variacao ?>% comparado ao dia anterior</span>
    <?php else: ?><span class="pequeno">Sem vendas no dia anterior</span><?php endif; ?></div>
  <div class="stat"><small>N.º de bilhetes emitidos</small><b><?= $nBilhetes ?> bilhete<?= $nBilhetes === 1 ? '' : 's' ?></b>
    <span class="pequeno">Média de <?= e(formatar_mt($media)) ?> por bilhete</span></div>
  <div class="stat"><small>Método mais usado</small>
    <b><?= $topo ? e(nome_metodo($topo['metodo'])) . ' (' . (int)$topo['n'] . ')' : '—' ?></b>
    <span class="pequeno"><?= $topo && $nBilhetes ? 'Corresponde a ' . round($topo['n'] / $nBilhetes * 100) . '% das vendas' : 'Ainda sem vendas' ?></span></div>
</div>

<section class="cartao" style="margin-top:18px">
  <div class="cabecalho-pagina" style="align-items:center">
    <div><h2>Vendas <?= $data === date('Y-m-d') ? 'de hoje' : 'de ' . e(data_com_ano($data)) ?></h2>
      <p class="pequeno" style="margin:2px 0 12px">Lista de transacções e bilhetes emitidos em <?= e(data_com_ano($data)) ?>.</p></div>
    <div class="nao-imprimir" style="display:flex;gap:8px;align-items:center">
      <form method="get"><input type="date" name="data" value="<?= e($data) ?>" max="<?= date('Y-m-d') ?>" onchange="this.form.submit()"
        style="border:1px solid var(--borda);border-radius:8px;padding:7px 10px;font:500 13px var(--fonte)"></form>
      <a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_vendas(['exportar' => 1])) ?>">⬇ Exportar CSV</a>
    </div>
  </div>
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Hora</th><th>Passageiro</th><th>Rota</th><th>Assento</th><th>Valor (MT)</th><th>Método</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="6" style="text-align:center;color:var(--cinza);padding:28px">Sem vendas neste dia.</td></tr><?php endif; ?>
    <?php foreach ($lista as $l): ?>
      <tr><td class="mono"><?= e(date('H:i', strtotime($l['data_pagamento']))) ?></td><td><b><?= e($l['nome_passageiro']) ?></b></td>
        <td><?= e($l['origem']) ?> → <?= e($l['destino']) ?></td><td>Assento <?= e($l['lugar']) ?></td>
        <td><b><?= e(formatar_mt($l['valor'])) ?></b></td>
        <td><span class="etq-metodo <?= classe_metodo($l['metodo']) ?>"><?= e(nome_metodo($l['metodo'])) ?></span></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <div class="rodape-tabela">
    <span><?= $nBilhetes ? 'A mostrar ' . ($offset + 1) . '-' . ($offset + count($lista)) . ' de ' . $nBilhetes . ' transacções' : 'Sem transacções' ?></span>
    <div class="paginacao">
      <?php if ($pagina > 1): ?><a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_vendas(['pagina' => $pagina - 1])) ?>">Anterior</a><?php endif; ?>
      <?php if ($pagina < $paginas): ?><a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_vendas(['pagina' => $pagina + 1])) ?>">Seguinte</a><?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
