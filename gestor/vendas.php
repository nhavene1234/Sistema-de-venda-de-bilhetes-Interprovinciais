<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'ver_relatorios');
$idT = $transp['id'];
libertar_reservas_expiradas($pdo);

$periodo = periodo_escolhido('mes');
[$ini, $fim] = periodo_pedido(30, 'mes');
$idRota = (int)($_GET['rota'] ?? 0);
$fEstado = $_GET['estado'] ?? '';
$q = trim($_GET['q'] ?? '');
$mapaEstado = ['pago' => 'confirmada', 'expirado' => 'expirada', 'cancelado' => 'cancelada', 'pendente' => 'pendente'];

// rotas da própria transportadora (a rota pedida tem de lhe pertencer, RN13)
$st = $pdo->prepare('SELECT id_rota, origem, destino FROM rota WHERE id_transportadora = ? ORDER BY origem, destino');
$st->execute([$idT]);
$rotas = $st->fetchAll();
if ($idRota && !in_array($idRota, array_column($rotas, 'id_rota'), true)) {
    acesso_restrito('Só tem permissão para ver dados da ' . $transp['nome'] . '.');
}

$onde = ["ro.id_transportadora = ?", "DATE(COALESCE(p.data_pagamento, rs.data_reserva)) BETWEEN ? AND ?",
         "COALESCE(rs.nome_passageiro, us.nome) IS NOT NULL", "COALESCE(rs.nome_passageiro, '') NOT LIKE 'Reservado (demonstra%'"];
$p = [$idT, $ini, $fim];
if ($idRota) { $onde[] = 'ro.id_rota = ?'; $p[] = $idRota; }
if (isset($mapaEstado[$fEstado])) { $onde[] = 'rs.estado = ?'; $p[] = $mapaEstado[$fEstado]; }
if ($q !== '') { $onde[] = '(COALESCE(rs.nome_passageiro, us.nome) LIKE ? OR rs.referencia LIKE ?)'; $p[] = "%$q%"; $p[] = "%$q%"; }
$cond = implode(' AND ', $onde);

$de = "FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
       JOIN assento a ON a.id_assento = rs.id_assento LEFT JOIN pagamento p ON p.id_reserva = rs.id_reserva
       LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro WHERE $cond";
$colunas = "rs.referencia, rs.estado, COALESCE(p.data_pagamento, rs.data_reserva) AS quando, COALESCE(rs.nome_passageiro, us.nome) AS passageiro,
            ro.origem, ro.destino, a.numero AS lugar, v.preco";

$estadosTxt = ['confirmada' => ['Pago', 'etq-pago'], 'expirada' => ['Expirado', 'etq-expirado'],
               'cancelada' => ['Cancelado', 'etq-cancelado'], 'pendente' => ['Pendente', 'etq-pendente']];

if (isset($_GET['exportar'])) {
    $st = $pdo->prepare("SELECT $colunas $de ORDER BY quando DESC LIMIT 10000");
    $st->execute($p);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="vendas_' . $ini . '_' . $fim . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Código', 'Data/Hora', 'Passageiro', 'Rota', 'Assento', 'Valor (MT)', 'Estado'], ';');
    foreach ($st->fetchAll() as $l) {
        fputcsv($out, [$l['referencia'], $l['quando'], $l['passageiro'], $l['origem'] . ' → ' . $l['destino'], $l['lugar'],
                       (float)$l['preco'], $estadosTxt[$l['estado']][0] ?? $l['estado']], ';');
    }
    fclose($out);
    exit;
}

$st = $pdo->prepare("SELECT COUNT(*) $de");
$st->execute($p);
$total = (int)$st->fetchColumn();
$porPagina = 8;
$paginas = max(1, (int)ceil($total / $porPagina));
$pagina = min($paginas, max(1, (int)($_GET['pagina'] ?? 1)));
$offset = ($pagina - 1) * $porPagina;
$st = $pdo->prepare("SELECT $colunas $de ORDER BY quando DESC, rs.id_reserva DESC LIMIT $porPagina OFFSET $offset");
$st->execute($p);
$lista = $st->fetchAll();

function ligacao_g(array $extra): string { return '?' . http_build_query(array_merge($_GET, $extra)); }

$tituloPagina = 'Vendas';
$migalhas = ['Painel Gestão', 'Vendas de Passagens'];
$paginaActiva = 'gestor/vendas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Controlo de Vendas</h1>
    <p class="subtitulo-pagina">Visualize, filtre e exporte transacções da frota <?= e($transp['nome']) ?>.</p></div>
  <a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_g(['exportar' => 1])) ?>">⬇ Exportar CSV</a>
</div>

<section class="cartao">
  <style>.chips-periodo a.activo{background:#12a150;color:#fff;border-color:#12a150}</style>
  <div class="chips-periodo nao-imprimir" style="margin-bottom:14px">
    <?php foreach (PERIODOS as $k => $nome): ?>
      <a class="<?= $periodo === $k ? 'activo' : '' ?>" href="<?= e(ligacao_g(['periodo' => $k, 'inicio' => null, 'fim' => null, 'pagina' => 1])) ?>"><?= e($nome) ?></a>
    <?php endforeach; ?>
  </div>
  <p class="pequeno" style="margin:0 0 12px">Período: <?= e(date('d/m/Y', strtotime($ini))) ?><?= $ini !== $fim ? ' a ' . e(date('d/m/Y', strtotime($fim))) : '' ?></p>

  <form method="get" class="filtros-g">
    <div class="campo"><label>Intervalo de datas</label>
      <div class="intervalo"><input type="date" name="inicio" value="<?= e($ini) ?>" max="<?= date('Y-m-d') ?>"><input type="date" name="fim" value="<?= e($fim) ?>" max="<?= date('Y-m-d') ?>"></div></div>
    <div class="campo"><label>Rota</label>
      <select name="rota"><option value="0">Todas as rotas <?= e($transp['nome']) ?></option>
        <?php foreach ($rotas as $r): ?><option value="<?= (int)$r['id_rota'] ?>" <?= $idRota === (int)$r['id_rota'] ? 'selected' : '' ?>><?= e($r['origem']) ?> → <?= e($r['destino']) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label>Estado</label>
      <select name="estado"><option value="">Todos os estados</option>
        <?php foreach (['pago' => 'Pago', 'pendente' => 'Pendente', 'expirado' => 'Expirado', 'cancelado' => 'Cancelado'] as $k => $n): ?>
          <option value="<?= $k ?>" <?= $fEstado === $k ? 'selected' : '' ?>><?= $n ?></option><?php endforeach; ?></select></div>
    <button class="btn btn-verde" type="submit">Aplicar filtros</button>
    <div class="campo" style="grid-column:1/-1"><label>Pesquisar</label>
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Pesquisar passageiro ou código..."></div>
  </form>
</section>

<section class="cartao" style="margin-top:18px">
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Código</th><th>Data/Hora</th><th>Passageiro</th><th>Rota <?= e($transp['nome']) ?></th><th>Assento</th><th>Valor</th><th>Estado</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="7" style="text-align:center;color:var(--cinza);padding:28px">Sem transacções para os filtros escolhidos.</td></tr><?php endif; ?>
    <?php foreach ($lista as $l): [$txt, $cls] = $estadosTxt[$l['estado']] ?? ['—', 'etq-pendente']; ?>
      <tr><td class="mono"><b><?= e($l['referencia'] ?? '—') ?></b></td>
        <td class="nao-quebra"><?= e(date('j', strtotime($l['quando'])) . ' ' . MESES_CURTOS[(int)date('n', strtotime($l['quando'])) - 1] . ', ' . date('H:i', strtotime($l['quando']))) ?></td>
        <td><b><?= e($l['passageiro']) ?></b></td><td><?= e($l['origem']) ?> → <?= e($l['destino']) ?></td>
        <td><?= e($l['lugar']) ?></td><td><b><?= e(formatar_mt($l['preco'])) ?></b></td>
        <td><span class="etq-g <?= $cls ?>"><?= e($txt) ?></span></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <div class="rodape-tabela">
    <span><?= $total ? 'Mostrando ' . ($offset + 1) . '-' . ($offset + count($lista)) . ' de ' . formatar_numero($total) . ' transacções' : 'Sem transacções' ?></span>
    <div class="paginacao">
      <?php if ($pagina > 1): ?><a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_g(['pagina' => $pagina - 1])) ?>">Anterior</a><?php endif; ?>
      <?php if ($pagina < $paginas): ?><a class="btn btn-verde btn-pequeno" href="<?= e(ligacao_g(['pagina' => $pagina + 1])) ?>">Seguinte</a><?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>