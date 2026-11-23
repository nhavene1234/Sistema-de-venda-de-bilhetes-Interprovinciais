<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
require_once __DIR__ . '/../includes/viagens.php';
$transp = exigir_gestor($pdo, 'gerir_rotas');
$idT = $transp['id'];
$eu = utilizador_logado();
$dataOk = fn($d) => is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
$hoje = date('Y-m-d');

// ---------- acções ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/viagens.php'); }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        // uma viagem só se cria sobre uma rota já existente (e activa) da própria transportadora
        $st = $pdo->prepare('SELECT * FROM rota WHERE id_rota = ? AND id_transportadora = ? AND activa = 1');
        $st->execute([(int)($_POST['rota'] ?? 0), $idT]);
        $rota = $st->fetch();
        $data = $_POST['data'] ?? '';
        $hora = trim($_POST['hora'] ?? '');
        $preco = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
        $classe = in_array($_POST['classe'] ?? '', CLASSES_VIAGEM, true) ? $_POST['classe'] : 'Convencional';

        if (!$rota) {
            flash('erro', 'Seleccione uma das rotas já criadas. Não é possível criar uma viagem sem rota.');
        } elseif (!$dataOk($data) || $data < $hoje) {
            flash('erro', 'Indique uma data de viagem válida (hoje ou posterior).');
        } elseif (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) {
            flash('erro', 'Indique uma hora de partida válida.');
        } elseif ($preco <= 0) {
            flash('erro', 'Indique o preço do bilhete (em MT).');
        } else {
            $res = criar_viagens($pdo, $idT, $rota, ['data' => $data, 'hora' => $hora, 'dias' => (int)($_POST['dias'] ?? 1), 'classe' => $classe, 'preco' => $preco,
                'autocarro' => (int)($_POST['autocarro'] ?? 0), 'motorista' => (int)($_POST['motorista'] ?? 0), 'cobrador' => (int)($_POST['cobrador'] ?? 0)]);
            if (isset($res['erro'])) {
                flash('erro', 'Não foi possível criar a viagem: ' . $res['erro']);
            } else {
                $rotulo = $rota['origem'] . ' → ' . $rota['destino'];
                registar_log($pdo, (int)$eu['id_utilizador'], 'Viagens criadas', 'rota', (int)$rota['id_rota'], $res['n'] . " viagem(ns) $rotulo às $hora a partir de $data");
                flash('ok', ($res['n'] === 1 ? 'Viagem criada: ' : $res['n'] . ' viagens criadas: ') . "$rotulo, " . date('d/m/Y', strtotime($data)) . " às $hora (autocarro " . $res['autocarro'] . ').'
                            . ($res['ignoradas'] ? ' ' . $res['ignoradas'] . ' dia(s) já tinham esta viagem e foram ignorados.' : ''));
                $fim = date('Y-m-d', strtotime($data . ' +' . max(0, (int)($_POST['dias'] ?? 1) - 1) . ' day'));
                redirecionar('gestor/viagens.php?' . http_build_query(['inicio' => $data, 'fim' => $fim, 'rota' => (int)$rota['id_rota']]));
            }
        }

    } elseif ($acao === 'cancelar') {
        $st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, ro.origem, ro.destino,
                                    (SELECT COUNT(*) FROM reserva r WHERE r.id_viagem = v.id_viagem AND r.estado IN ('pendente','confirmada')) AS reservas
                             FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                             WHERE v.id_viagem = ? AND ro.id_transportadora = ? AND v.estado = 'agendada'");
        $st->execute([(int)($_POST['id'] ?? 0), $idT]);
        $v = $st->fetch();
        if (!$v) {
            flash('erro', 'Viagem não encontrada ou já não pode ser cancelada.');
        } elseif ((int)$v['reservas'] > 0) {
            flash('erro', 'Esta viagem já tem ' . (int)$v['reservas'] . ' reserva(s) activa(s) e não pode ser cancelada aqui.');
        } else {
            $pdo->prepare("UPDATE viagem SET estado = 'cancelada' WHERE id_viagem = ? AND estado = 'agendada'")->execute([$v['id_viagem']]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Viagem cancelada', 'viagem', (int)$v['id_viagem'], descricao_viagem($v));
            flash('ok', 'Viagem cancelada: ' . descricao_viagem($v) . '.');
        }
    }
    redirecionar('gestor/viagens.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . http_build_query($_GET) : ''));
}

// ---------- filtros e listagem ----------
$fRota = (int)($_GET['rota'] ?? 0);
$fIni = $dataOk($_GET['inicio'] ?? '') ? $_GET['inicio'] : $hoje;
$fFim = $dataOk($_GET['fim'] ?? '') ? $_GET['fim'] : date('Y-m-d', strtotime("$fIni +14 days"));
if ($fFim < $fIni) $fFim = $fIni;
if (strtotime($fFim) - strtotime($fIni) > 130 * 86400) $fFim = date('Y-m-d', strtotime("$fIni +130 days"));
$fEstado = in_array($_GET['estado'] ?? '', ['agendada', 'em_curso', 'concluida', 'cancelada'], true) ? $_GET['estado'] : '';

$onde = ['ro.id_transportadora = ?', 'v.data BETWEEN ? AND ?'];
$p = [$idT, $fIni, $fFim];
if ($fRota) { $onde[] = 'v.id_rota = ?'; $p[] = $fRota; }
if ($fEstado) { $onde[] = 'v.estado = ?'; $p[] = $fEstado; }
$st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, v.hora_chegada, v.preco, v.classe, v.estado, ro.origem, ro.destino, au.codigo_frota, au.matricula, au.capacidade_total,
                            m.nome AS motorista, cu.nome AS cobrador,
                            (SELECT COUNT(*) FROM reserva r WHERE r.id_viagem = v.id_viagem AND r.estado IN ('pendente','confirmada')) AS reservas
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro au ON au.id_autocarro = v.id_autocarro
                     LEFT JOIN motorista m ON m.id_motorista = v.id_motorista LEFT JOIN utilizador cu ON cu.id_utilizador = v.id_cobrador
                     WHERE " . implode(' AND ', $onde) . " ORDER BY v.data, v.hora_partida, ro.origem LIMIT 301");
$st->execute($p);
$lista = $st->fetchAll();
$cortada = count($lista) > 300;
if ($cortada) array_pop($lista);

// ---------- dados do formulário «Nova viagem» ----------
$st = $pdo->prepare('SELECT id_rota, origem, destino, distancia_km, duracao_estimada, preco_base, activa FROM rota WHERE id_transportadora = ? ORDER BY origem, destino');
$st->execute([$idT]);
$todasRotas = $st->fetchAll();
$rotasActivas = array_values(array_filter($todasRotas, fn($r) => (int)$r['activa']));

$st = $pdo->prepare("SELECT id_autocarro, codigo_frota, matricula, capacidade_total FROM autocarro
                     WHERE id_transportadora = ? AND estado = 'disponivel' AND EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = autocarro.id_autocarro) ORDER BY codigo_frota");
$st->execute([$idT]);
$autocarrosOpc = $st->fetchAll();
$st = $pdo->prepare("SELECT id_motorista, codigo, nome FROM motorista WHERE id_transportadora = ? AND estado = 'disponivel' AND carta_validade >= CURRENT_DATE ORDER BY codigo");

$st->execute([$idT]);
$motoristasOpc = $st->fetchAll();
$st = $pdo->prepare("SELECT id_utilizador, nome FROM utilizador WHERE perfil = 'cobrador' AND id_transportadora = ? AND activo = 1 ORDER BY nome");
$st->execute([$idT]);
$cobradoresOpc = $st->fetchAll();

$estadosV = ['agendada' => ['Agendada', 'etq-pendente'], 'em_curso' => ['Em curso', 'etq-pago'], 'concluida' => ['Concluída', 'etq-expirado'], 'cancelada' => ['Cancelada', 'etq-cancelado']];
$minutos = function (?string $d): string { [$h, $m] = array_map('intval', explode(':', $d ?: '00:00')); return sprintf('%dh %02dm', $h, $m); };

$tituloPagina = 'Viagens';
$migalhas = ['Painel Gestão', 'Viagens'];
$paginaActiva = 'gestor/viagens.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Viagens da <?= e($transp['nome']) ?></h1>
    <p class="subtitulo-pagina">Crie viagens a partir das rotas já registadas e acompanhe os horários, autocarros e equipas.</p></div>
  <button class="btn btn-verde" type="button" data-abrir="modal-nova-viagem">+ Nova viagem</button>
</div>

<section class="cartao">
  <form method="get" class="filtros">
    <select name="rota" aria-label="Rota">
      <option value="0">Rota: todas</option>
      <?php foreach ($todasRotas as $r): ?><option value="<?= (int)$r['id_rota'] ?>" <?= $fRota === (int)$r['id_rota'] ? 'selected' : '' ?>><?= e($r['origem'] . ' → ' . $r['destino']) ?></option><?php endforeach; ?>
    </select>
    <select name="estado" aria-label="Estado">
      <option value="">Estado: todos</option>
      <?php foreach ($estadosV as $k => [$n]): ?><option value="<?= $k ?>" <?= $fEstado === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?>
    </select>
    <label class="pequeno">De <input type="date" name="inicio" value="<?= e($fIni) ?>"></label>
    <label class="pequeno">Até <input type="date" name="fim" value="<?= e($fFim) ?>"></label>
    <button class="btn btn-sec btn-pequeno" type="submit">Filtrar</button>
  </form>

  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Data</th><th>Partida</th><th>Rota</th><th>Classe</th><th>Preço</th><th>Autocarro</th><th>Motorista</th><th>Cobrador</th><th>Reservas</th><th>Estado</th><th class="acoes">Acções</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="11" style="text-align:center;color:var(--cinza);padding:28px">Não há viagens neste período. Use «Nova viagem».</td></tr><?php endif; ?>
    <?php foreach ($lista as $v): [$et, $ec] = $estadosV[$v['estado']]; ?>
      <tr>
        <td><b><?= e(date('d/m/Y', strtotime($v['data']))) ?></b></td>
        <td class="mono"><?= e(substr($v['hora_partida'], 0, 5)) ?><?= $v['hora_chegada'] ? '<span class="sub-linha">chega ' . e(substr($v['hora_chegada'], 0, 5)) . '</span>' : '' ?></td>
        <td><?= e($v['origem'] . ' → ' . $v['destino']) ?></td>
        <td><?= e($v['classe']) ?></td>
        <td><b><?= e(formatar_mt($v['preco'])) ?></b></td>
        <td class="mono"><?= e($v['codigo_frota'] ?: $v['matricula']) ?></td>
        <td><?= $v['motorista'] ? e($v['motorista']) : '<span class="sub-linha" style="margin:0">—</span>' ?></td>
        <td><?= $v['cobrador'] ? e($v['cobrador']) : '<span class="sub-linha" style="margin:0">—</span>' ?></td>
        <td><?= (int)$v['reservas'] ?> / <?= (int)$v['capacidade_total'] ?></td>
        <td><span class="etq-g <?= $ec ?>"><?= e($et) ?></span></td>
        <td class="acoes nao-quebra">
          <a class="btn-link-e verde" href="<?= e(url('gestor/viagem_lugares.php?id=' . (int)$v['id_viagem'])) ?>">Lugares</a>
          <?php if ($v['estado'] === 'agendada' && !(int)$v['reservas']): ?>
          <form method="post" style="display:inline" data-confirmar="Cancelar a viagem <?= e(date('d/m', strtotime($v['data'])) . ' ' . substr($v['hora_partida'], 0, 5) . ' · ' . $v['origem'] . ' → ' . $v['destino']) ?>?">
            <?= csrf_campo() ?><input type="hidden" name="acao" value="cancelar"><input type="hidden" name="id" value="<?= (int)$v['id_viagem'] ?>">
            <button class="btn-link-e perigo" type="submit">Cancelar</button></form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php if ($cortada): ?><p class="pequeno" style="margin-top:10px">A mostrar as primeiras 300 viagens. Reduza o período ou use os filtros.</p><?php endif; ?>
</section>
<p class="nota-rodape">ⓘ Cada viagem pertence a uma rota. Para atribuir cobradores às viagens já criadas, use «Cobradores → Atribuir viagens».</p>

<!-- Nova viagem -->
<style>
#modal-nova-viagem{width:min(700px,94vw);max-width:700px;max-height:92vh;overflow:auto;padding:28px 30px}
#modal-nova-viagem .rotulo-seccao{display:flex;align-items:center;gap:10px;margin:22px 0 12px;font-size:14px;font-weight:800;color:#0f1b34}
#modal-nova-viagem .rotulo-seccao.primeiro{margin-top:6px}
#modal-nova-viagem .rotulo-seccao i{width:22px;height:22px;border-radius:50%;background:#16a34a;color:#fff;font-style:normal;font-size:12px;display:grid;place-items:center}
#modal-nova-viagem .lista-rotas{display:grid;grid-template-columns:1fr 1fr;gap:10px;max-height:240px;overflow:auto;padding:3px}
#modal-nova-viagem .lista-rotas label{display:flex;align-items:flex-start;gap:10px;margin:0;padding:12px 14px;background:#fff;border:1px solid var(--borda);border-radius:12px;cursor:pointer;transition:border-color .15s,background .15s}
#modal-nova-viagem .lista-rotas label:hover{border-color:#86d3a8}
#modal-nova-viagem .lista-rotas label:has(input:checked){border-color:#16a34a;background:#eaf7f0;box-shadow:0 0 0 3px rgba(22,163,74,.14)}
#modal-nova-viagem .lista-rotas input{width:auto;flex:none;margin:3px 0 0;accent-color:#16a34a}
#modal-nova-viagem .lista-rotas span{display:block;min-width:0}
#modal-nova-viagem .lista-rotas b{display:block;font-size:14px;font-weight:700;color:#0f1b34}
#modal-nova-viagem .lista-rotas small{display:block;margin-top:3px;font-size:12px;line-height:1.4;color:var(--cinza)}
#modal-nova-viagem .campo{margin-bottom:14px}
#modal-nova-viagem .tres{gap:14px}
#modal-nova-viagem .linha-2{gap:14px}
#modal-nova-viagem .modal-acoes{margin-top:22px;padding-top:16px;border-top:1px solid var(--borda)}
@media(max-width:600px){#modal-nova-viagem .lista-rotas,#modal-nova-viagem .tres,#modal-nova-viagem .linha-2{grid-template-columns:1fr}}
</style>
<dialog class="modal" id="modal-nova-viagem">
  <h2>Nova viagem</h2>
  <?php if (!$rotasActivas): ?>
    <p class="sub">Ainda não tem rotas activas. Uma viagem só pode ser criada sobre uma rota existente.</p>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Fechar</button><a class="btn btn-verde" href="<?= e(url('gestor/rotas.php')) ?>">Ir para Rotas</a></div>
  <?php else: ?>
  <p class="sub">Escolha uma das rotas já criadas e defina a data, o horário e a equipa.</p>
  <form method="post" id="form-nova-viagem">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="criar">
    <div class="rotulo-seccao primeiro"><i>1</i>Escolha a rota</div>
    <div class="lista-rotas">
      <?php foreach ($rotasActivas as $r): ?>
        <label><input type="radio" name="rota" value="<?= (int)$r['id_rota'] ?>" data-preco="<?= e((string)$r['preco_base']) ?>" required>
          <span><b><?= e($r['origem'] . ' → ' . $r['destino']) ?></b>
            <small><?= $r['distancia_km'] !== null ? e(formatar_numero($r['distancia_km'])) . ' km · ' : '' ?><?= e($minutos($r['duracao_estimada'])) ?><br>Preço base <?= $r['preco_base'] !== null ? e(formatar_mt($r['preco_base'])) : '—' ?></small></span></label>
      <?php endforeach; ?>
    </div>

    <div class="rotulo-seccao"><i>2</i>Data e horário</div>
    <div class="tres">
      <div class="campo"><label>Data</label><input type="date" name="data" min="<?= e($hoje) ?>" value="<?= e(date('Y-m-d', strtotime('tomorrow'))) ?>" required></div>
      <div class="campo"><label>Partida</label><input type="time" name="hora" required></div>
      <div class="campo"><label>Repetir (dias)</label><input type="number" name="dias" value="1" min="1" max="120" title="1 = só nesta data; mais = uma viagem por dia"></div>
    </div>
    <div class="linha-2">
      <div class="campo"><label>Classe</label><select name="classe"><?php foreach (CLASSES_VIAGEM as $c): ?><option><?= e($c) ?></option><?php endforeach; ?></select></div>
      <div class="campo"><label>Preço do bilhete (MT)</label><input type="number" name="preco" step="0.01" min="1" required></div>
    </div>

    <div class="rotulo-seccao"><i>3</i>Autocarro e equipa</div>
    <div class="campo"><label>Autocarro</label>
      <select name="autocarro"><option value="0">Automático (primeiro livre nesse horário)</option>
        <?php foreach ($autocarrosOpc as $a): ?><option value="<?= (int)$a['id_autocarro'] ?>"><?= e($a['codigo_frota'] . ' · ' . $a['matricula'] . ' · ' . $a['capacidade_total'] . ' lugares') ?></option><?php endforeach; ?></select></div>
    <div class="linha-2">
      <div class="campo"><label>Motorista</label>
        <select name="motorista"><option value="0">Sem motorista (definir depois)</option>
          <?php foreach ($motoristasOpc as $m): ?><option value="<?= (int)$m['id_motorista'] ?>"><?= e($m['codigo'] . ' · ' . $m['nome']) ?></option><?php endforeach; ?></select></div>
      <div class="campo"><label>Cobrador</label>
        <select name="cobrador"><option value="0">Sem cobrador (definir depois)</option>
          <?php foreach ($cobradoresOpc as $c): ?><option value="<?= (int)$c['id_utilizador'] ?>"><?= e($c['nome']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Criar viagem</button></div>
  </form>
  <script>
  document.querySelectorAll('#form-nova-viagem input[name=rota]').forEach(function (r) {
    r.addEventListener('change', function () {
      var preco = document.querySelector('#form-nova-viagem input[name=preco]');
      if (r.dataset.preco) preco.value = r.dataset.preco;
    });
  });
  </script>
  <?php endif; ?>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
