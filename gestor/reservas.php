<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'ver_relatorios');
$idT = $transp['id'];
libertar_reservas_expiradas($pdo);

$mapaEstado = ['confirmada' => ['Confirmada', 'etq-pago'], 'pendente' => ['Pendente', 'etq-expirado'],
               'cancelada' => ['Cancelada', 'etq-cancelado'], 'expirada' => ['Expirada', 'etq-pendente']];
$validaData = fn($d) => is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;

// filtros (por defeito: partidas de hoje até ao fim do mês)
$de  = $validaData($_GET['de'] ?? '') ? $_GET['de'] : date('Y-m-d');
$ate = $validaData($_GET['ate'] ?? '') ? $_GET['ate'] : date('Y-m-t');
if ($ate < $de) $ate = $de;
$q = trim($_GET['q'] ?? '');
$idRota = (int)($_GET['rota'] ?? 0);
$fEstado = $_GET['estado'] ?? '';

$st = $pdo->prepare('SELECT id_rota, origem, destino FROM rota WHERE id_transportadora = ? ORDER BY origem, destino');
$st->execute([$idT]);
$rotas = $st->fetchAll();
if ($idRota && !in_array($idRota, array_column($rotas, 'id_rota'), true)) {
    acesso_restrito('Só tem permissão para ver dados da ' . $transp['nome'] . '.');   // RN13
}

$nomeSql = "COALESCE(rs.nome_passageiro, us.nome)";
$telSql = "COALESCE(rs.telefone_passageiro, us.telefone)";
$onde = ['ro.id_transportadora = ?', "$nomeSql IS NOT NULL", "COALESCE(rs.nome_passageiro, '') NOT LIKE 'Reservado (demonstra%'", 'v.data BETWEEN ? AND ?'];
$p = [$idT, $de, $ate];
if ($idRota) { $onde[] = 'ro.id_rota = ?'; $p[] = $idRota; }
if (isset($mapaEstado[$fEstado])) { $onde[] = 'rs.estado = ?'; $p[] = $fEstado; }
if ($q !== '') {
    $busca = "($nomeSql LIKE ? OR rs.referencia LIKE ?";
    $p[] = "%$q%"; $p[] = "%$q%";
    $digitos = preg_replace('/\D/', '', $q);
    if (strlen($digitos) >= 4) { $busca .= " OR REPLACE(REPLACE($telSql, ' ', ''), '+', '') LIKE ?"; $p[] = "%$digitos%"; }
    $onde[] = $busca . ')';
}
$cond = implode(' AND ', $onde);

$de_sql = "FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
           JOIN assento a ON a.id_assento = rs.id_assento JOIN autocarro au ON au.id_autocarro = v.id_autocarro
           LEFT JOIN motorista mo ON mo.id_motorista = v.id_motorista
           LEFT JOIN pagamento pg ON pg.id_reserva = rs.id_reserva
           LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
           LEFT JOIN utilizador bl ON bl.id_utilizador = rs.id_bilheteiro";
$colunas = "rs.id_reserva, rs.referencia, rs.estado, $nomeSql AS passageiro, $telSql AS telefone, us.email, rs.tipo_documento, rs.documento_passageiro,
            v.id_viagem, v.data, v.hora_partida, v.preco, ro.origem, ro.destino, a.numero AS lugar, au.codigo_frota,
            mo.nome AS motorista, mo.codigo AS cod_motorista, pg.metodo, pg.estado AS pg_estado, bl.nome AS bilheteiro";

if (isset($_GET['exportar'])) {
    $extra = '';
    if (!empty($_GET['ids'])) {
        $ids = array_filter(array_map('intval', explode(',', $_GET['ids'])));
        if ($ids) $extra = ' AND rs.id_reserva IN (' . implode(',', $ids) . ')';
    }
    $st = $pdo->prepare("SELECT $colunas $de_sql WHERE $cond $extra ORDER BY v.data, v.hora_partida, rs.id_reserva LIMIT 10000");
    $st->execute($p);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="reservas_' . $de . '_' . $ate . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Referência', 'Passageiro', 'Telefone', 'Rota', 'Viagem', 'Autocarro', 'Data', 'Hora', 'Assento', 'Valor (MZN)', 'Estado'], ';');
    foreach ($st->fetchAll() as $l) {
        fputcsv($out, [$l['referencia'], $l['passageiro'], $l['telefone'], $l['origem'] . ' → ' . $l['destino'], 'VG-' . str_pad($l['id_viagem'], 4, '0', STR_PAD_LEFT),
                       $l['codigo_frota'], $l['data'], substr($l['hora_partida'], 0, 5), $l['lugar'], formatar_mzn($l['preco']), $mapaEstado[$l['estado']][0]], ';');
    }
    fclose($out);
    exit;
}

// indicadores do mês (independentes dos filtros)
$st = $pdo->prepare("SELECT rs.estado, COUNT(*) AS n FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                     LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                     WHERE ro.id_transportadora = ? AND $nomeSql IS NOT NULL AND COALESCE(rs.nome_passageiro, '') NOT LIKE 'Reservado (demonstra%'
                       AND rs.data_reserva >= ? GROUP BY rs.estado");
$st->execute([$idT, date('Y-m-01 00:00:00')]);
$k = ['confirmada' => 0, 'pendente' => 0, 'cancelada' => 0, 'expirada' => 0];
foreach ($st->fetchAll() as $r) $k[$r['estado']] = (int)$r['n'];
$kTotal = array_sum($k);
$kCanc = $k['cancelada'] + $k['expirada'];

$st = $pdo->prepare("SELECT COUNT(*) $de_sql WHERE $cond");
$st->execute($p);
$total = (int)$st->fetchColumn();
$porPagina = 7;
$paginas = max(1, (int)ceil($total / $porPagina));
$pagina = min($paginas, max(1, (int)($_GET['pagina'] ?? 1)));
$offset = ($pagina - 1) * $porPagina;
$st = $pdo->prepare("SELECT $colunas $de_sql WHERE $cond ORDER BY v.data, v.hora_partida, rs.id_reserva LIMIT $porPagina OFFSET $offset");
$st->execute($p);
$lista = $st->fetchAll();

function ligacao_r(array $extra): string { return '?' . http_build_query(array_merge($_GET, $extra)); }

// legenda compacta: "Partidas de 12 a 31 de outubro de 2026"
$t1 = strtotime($de); $t2 = strtotime($ate);
$m1 = strtolower(MESES_LONGOS[(int)date('n', $t1) - 1]);
$m2 = strtolower(MESES_LONGOS[(int)date('n', $t2) - 1]);
if ($de === $ate) {
    $legenda = 'Partidas de ' . date('j', $t1) . ' de ' . $m1 . ' de ' . date('Y', $t1);
} elseif (date('Y-n', $t1) === date('Y-n', $t2)) {
    $legenda = 'Partidas de ' . date('j', $t1) . ' a ' . date('j', $t2) . ' de ' . $m2 . ' de ' . date('Y', $t2);
} elseif (date('Y', $t1) === date('Y', $t2)) {
    $legenda = 'Partidas de ' . date('j', $t1) . ' de ' . $m1 . ' a ' . date('j', $t2) . ' de ' . $m2 . ' de ' . date('Y', $t2);
} else {
    $legenda = 'Partidas de ' . date('j', $t1) . ' de ' . $m1 . ' de ' . date('Y', $t1) . ' a ' . date('j', $t2) . ' de ' . $m2 . ' de ' . date('Y', $t2);
}

$tituloPagina = 'Reservas';
$migalhas = ['Reservas', 'Visualizar reservas'];
$paginaActiva = 'gestor/reservas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<style>
/* ===== Estilos desta página (reservas.php). Podes mover este bloco para o teu painel.css ===== */
.cabecalho-pagina{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:22px}
.chip-transp{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;background:#fff;border:1px solid #e3e9f1;border-radius:10px;font-weight:600;font-size:14px;color:#0f2447;white-space:nowrap}
.chip-transp svg{width:18px;height:18px}

/* KPIs */
.kpis-reservas{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:22px}
.kpi-r{display:flex;justify-content:space-between;align-items:center;gap:12px;background:#fff;border:1px solid #e3e9f1;border-radius:16px;padding:20px 22px}
.kpi-r small{display:block;font-size:13px;color:#6b7a90}
.kpi-r b{display:block;font-size:32px;line-height:1.2;font-weight:800;color:#0f2447;margin:4px 0}
.kpi-r .ico,.kpi-r .ico.amarelo,.kpi-r .ico.vermelho{width:46px;height:46px;flex:none;border-radius:12px;display:grid;place-items:center;background:#e6f7ee;color:#16a864}
.kpi-r .ico svg{width:24px;height:24px}

/* Filtros */
.filtros-r{display:grid;grid-template-columns:minmax(0,2.2fr) minmax(0,1.5fr) minmax(0,1.8fr) minmax(0,1.3fr) auto;gap:16px;align-items:end}
.filtros-r .campo{margin:0}
.filtros-r label{display:block;font-weight:600;font-size:14px;margin-bottom:8px;color:#0f2447}
.filtros-r input,.filtros-r select{width:100%;height:48px;padding:0 14px;border:1px solid #dfe6ef;border-radius:10px;background:#fff;font:inherit;font-size:14px;color:#0f2447}
.filtros-r .btn{height:48px;padding:0 22px;white-space:nowrap}
.intervalo{display:flex;align-items:center;height:48px;border:1px solid #dfe6ef;border-radius:10px;background:#fff;overflow:hidden}
.intervalo input,.filtros-r .intervalo input{flex:1;min-width:0;height:100%;border:0;border-radius:0;background:transparent;padding:0 10px}
.intervalo input+input{border-left:1px solid #dfe6ef}
.linha-filtros{display:flex;justify-content:space-between;align-items:center;margin-top:16px;font-size:14px;color:#6b7a90}
.linha-filtros a{color:#16a864;font-weight:700;text-decoration:none}

/* Lista */
.lista-cab{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
.lista-cab h2{display:flex;align-items:center;gap:12px;margin:0;font-size:20px}
.contador{font-size:13px;font-weight:600;color:#6b7a90;background:#f1f4f9;border-radius:8px;padding:4px 10px}
#tabela-reservas{width:100%;border-collapse:collapse}
#tabela-reservas th{background:#f4f7fb;color:#6b7a90;font-size:13px;font-weight:600;text-align:left;padding:16px 12px}
#tabela-reservas td{padding:16px 12px;border-bottom:1px solid #e9eef5;font-size:14px;color:#0f2447;vertical-align:middle}
#tabela-reservas .sub-linha{display:block;font-size:12.5px;color:#7a8799;margin-top:4px}
.btn-mais{background:none;border:0;font-size:22px;line-height:1;cursor:pointer;color:#52617a;padding:4px 10px}
.rodape-tabela{display:flex;justify-content:space-between;align-items:center;gap:12px;padding-top:18px;font-size:14px;color:#6b7a90}
.pag-num{display:flex;align-items:center;gap:8px}
.pag-num a,.pag-num span{min-width:36px;height:36px;padding:0 6px;display:grid;place-items:center;border:1px solid #e3e9f1;border-radius:8px;color:#0f2447;font-size:14px;font-weight:600;text-decoration:none;background:#fff}
.pag-num a.activa{background:#16a864;border-color:#16a864;color:#fff}
.pag-num span[aria-disabled]{opacity:.45}
.nota-rodape{margin-top:16px;font-size:14px;color:#6b7a90}

@media (max-width:1100px){.kpis-reservas{grid-template-columns:repeat(2,minmax(0,1fr))}.filtros-r{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:640px){.kpis-reservas,.filtros-r{grid-template-columns:1fr}.cabecalho-pagina{flex-direction:column}}
</style>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Visualizar reservas</h1>
    <p class="subtitulo-pagina">Acompanhe os passageiros, os pagamentos e os estados das reservas.</p></div>
  <span class="chip-transp"><?= icone_svg('edificio') ?> Transportadora <?= e($transp['nome']) ?></span>
</div>

<div class="kpis-reservas">
  <div class="kpi-r"><div><small>Total de reservas</small><b><?= formatar_numero($kTotal) ?></b><small>Neste mês</small></div><span class="ico"><?= icone_svg('bilhete') ?></span></div>
  <div class="kpi-r"><div><small>Confirmadas</small><b><?= formatar_numero($k['confirmada']) ?></b><small>Pagamento recebido</small></div><span class="ico">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16 9.5"/></svg></span></div>
  <div class="kpi-r"><div><small>Pendentes</small><b><?= formatar_numero($k['pendente']) ?></b><small>A aguardar pagamento</small></div><span class="ico amarelo">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span></div>
  <div class="kpi-r"><div><small>Canceladas</small><b><?= formatar_numero($kCanc) ?></b><small>Lugares libertados</small></div><span class="ico vermelho">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg></span></div>
</div>

<section class="cartao">
  <form method="get" class="filtros-r">
    <div class="campo"><label>Pesquisar reserva</label><input type="search" name="q" value="<?= e($q) ?>" placeholder="Nome, código ou telemóvel"></div>
    <div class="campo"><label>Rota</label>
      <select name="rota"><option value="0">Todas as rotas</option>
        <?php foreach ($rotas as $r): ?><option value="<?= (int)$r['id_rota'] ?>" <?= $idRota === (int)$r['id_rota'] ? 'selected' : '' ?>><?= e($r['origem']) ?> → <?= e($r['destino']) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label>Data da viagem</label><div class="intervalo"><input type="date" name="de" value="<?= e($de) ?>"><input type="date" name="ate" value="<?= e($ate) ?>"></div></div>
    <div class="campo"><label>Estado</label>
      <select name="estado"><option value="">Todos os estados</option>
        <?php foreach ($mapaEstado as $kk => [$nome]): ?><option value="<?= $kk ?>" <?= $fEstado === $kk ? 'selected' : '' ?>><?= e($nome) ?></option><?php endforeach; ?></select></div>
    <button class="btn btn-verde" type="submit">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:-3px;margin-right:6px"><path d="M4 7h10M18 7h2M4 17h2M10 17h10"/><circle cx="16" cy="7" r="2"/><circle cx="8" cy="17" r="2"/></svg>Filtrar</button>
  </form>
  <div class="linha-filtros"><span><?= e($legenda) ?></span><a href="<?= e(url('gestor/reservas.php')) ?>">Limpar filtros</a></div>
</section>

<section class="cartao" style="margin-top:18px">
  <div class="lista-cab"><h2>Lista de reservas <span class="contador"><?= formatar_numero($total) ?> reservas</span></h2>
    <a class="btn btn-sec btn-pequeno" id="btn-exportar" href="<?= e(ligacao_r(['exportar' => 1])) ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:-3px;margin-right:6px"><path d="M12 4v11M7.5 11L12 15.5 16.5 11M5 20h14"/></svg>Exportar CSV</a></div>
  <div class="tabela-rolar">
  <table class="tabela" id="tabela-reservas">
    <thead><tr><th style="width:30px"><input type="checkbox" data-todos aria-label="Seleccionar todas"></th><th>Passageiro</th><th>Rota</th><th>Data / Horário</th><th>Assento</th><th>Valor (MZN)</th><th>Estado</th><th></th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="8" style="text-align:center;color:var(--cinza);padding:28px">Nenhuma reserva encontrada para os filtros escolhidos.</td></tr><?php endif; ?>
    <?php foreach ($lista as $l):
        [$txt, $cls] = $mapaEstado[$l['estado']];
        $dt = strtotime($l['data']);
        $detalhes = [
            ['Referência', $l['referencia'] ?? '—'], ['Passageiro', $l['passageiro']],
            ['Documento', $l['documento_passageiro'] ? trim(($l['tipo_documento'] ?? '') . ' ' . $l['documento_passageiro']) : '—'],
            ['Telefone', $l['telefone'] ?? '—'], ['E-mail', $l['email'] ?? '—'],
            ['Viagem', 'VG-' . str_pad($l['id_viagem'], 4, '0', STR_PAD_LEFT) . ' · ' . $l['codigo_frota']],
            ['Rota', $l['origem'] . ' → ' . $l['destino']],
            ['Partida', date('d/m/Y', $dt) . ' às ' . substr($l['hora_partida'], 0, 5)],
            ['Assento', $l['lugar']], ['Valor', formatar_mzn($l['preco']) . ' MZN'],
            ['Pagamento', $l['metodo'] ? nome_metodo($l['metodo']) . ' (' . ($l['pg_estado'] === 'confirmado' ? 'confirmado' : ($l['pg_estado'] === 'reembolsado' ? 'reembolsado' : $l['pg_estado'])) . ')' : 'Sem pagamento'],
            ['Canal', $l['bilheteiro'] ? 'Balcão · ' . $l['bilheteiro'] : 'Aplicação do passageiro'],
            ['Motorista', $l['motorista'] ? $l['cod_motorista'] . ' · ' . $l['motorista'] : 'Não atribuído'],
            ['Estado', $txt],
        ]; ?>
      <tr>
        <td><input type="checkbox" data-sel value="<?= (int)$l['id_reserva'] ?>" aria-label="Seleccionar reserva"></td>
        <td><b><?= e($l['passageiro']) ?></b><span class="sub-linha"><?= e(($l['referencia'] ?? '—') . ' · ' . ($l['telefone'] ?? '—')) ?></span></td>
        <td><b><?= e($l['origem']) ?> → <?= e($l['destino']) ?></b><span class="sub-linha">VG-<?= e(str_pad($l['id_viagem'], 4, '0', STR_PAD_LEFT)) ?> · <?= e($l['codigo_frota']) ?></span></td>
        <td><?= e(date('j', $dt) . ' ' . MESES_CURTOS[(int)date('n', $dt) - 1] . '. ' . date('Y', $dt)) ?><span class="sub-linha"><?= e(substr($l['hora_partida'], 0, 5)) ?></span></td>
        <td><?= e($l['lugar']) ?></td><td><b><?= e(formatar_mzn($l['preco'])) ?></b></td>
        <td><span class="etq-g <?= $cls ?>"><?= e($txt) ?></span></td>
        <td><button type="button" class="btn-mais" data-detalhes="<?= e(json_encode($detalhes, JSON_UNESCAPED_UNICODE)) ?>" aria-label="Ver detalhes">⋯</button></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <div class="rodape-tabela">
    <span><?= $total ? 'A mostrar ' . ($offset + 1) . '–' . ($offset + count($lista)) . ' de ' . formatar_numero($total) . ' reservas' : 'Sem reservas' ?></span>
    <div class="pag-num">
      <?php if ($pagina > 1): ?><a href="<?= e(ligacao_r(['pagina' => $pagina - 1])) ?>">‹</a><?php else: ?><span aria-disabled="true">‹</span><?php endif; ?>
      <?php foreach (paginas_lista($pagina, $paginas) as $n): ?>
        <?php if ($n === '…'): ?><span>…</span><?php else: ?><a class="<?= $n === $pagina ? 'activa' : '' ?>" href="<?= e(ligacao_r(['pagina' => $n])) ?>"><?= $n ?></a><?php endif; ?>
      <?php endforeach; ?>
      <?php if ($pagina < $paginas): ?><a href="<?= e(ligacao_r(['pagina' => $pagina + 1])) ?>">›</a><?php else: ?><span aria-disabled="true">›</span><?php endif; ?>
    </div>
  </div>
</section>
<p class="nota-rodape">ⓘ Reservas pendentes só ficam confirmadas após a validação do pagamento.</p>

<dialog class="modal" id="modal-reserva">
  <h2>Detalhes da reserva</h2>
  <p class="sub">Informação de leitura. Só a equipa de gestão da <?= e($transp['nome']) ?> tem acesso.</p>
  <dl class="detalhes-reserva"></dl>
  <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Fechar</button></div>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>