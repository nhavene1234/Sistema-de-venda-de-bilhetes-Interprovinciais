<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_rotas');
$idT = $transp['id'];
$eu = utilizador_logado();
$classes = ['Convencional', 'Executivo · AC', 'Semi-luxo', 'Executivo nocturno'];

/** Garante que a rota pertence à transportadora do gestor (RN13). */
function rota_propria(PDO $pdo, int $idRota, int $idT, array $transp): array {
    $st = $pdo->prepare('SELECT * FROM rota WHERE id_rota = ? AND id_transportadora = ?');
    $st->execute([$idRota, $idT]);
    $r = $st->fetch();
    if (!$r) acesso_restrito('Só tem permissão para gerir rotas da ' . $transp['nome'] . '.');
    return $r;
}
function minutos_da_rota(?string $duracao): int {
    [$h, $m] = array_map('intval', explode(':', $duracao ?: '00:00'));
    return $h * 60 + $m;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/rotas.php'); }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        $origem = trim($_POST['origem'] ?? ''); $destino = trim($_POST['destino'] ?? '');
        $km = (float)str_replace(',', '.', $_POST['distancia'] ?? '0');
        $min = duracao_para_minutos($_POST['duracao'] ?? '');
        $preco = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
        $hora = trim($_POST['hora'] ?? '');
        $nDias = max(1, min(120, (int)($_POST['dias'] ?? 60)));
        $classe = in_array($_POST['classe'] ?? '', $classes, true) ? $_POST['classe'] : 'Convencional';

        if (mb_strlen($origem) < 2 || mb_strlen($destino) < 2) $erro = 'Indique a origem e o destino.';
        elseif (mb_strtolower($origem) === mb_strtolower($destino)) $erro = 'A origem e o destino têm de ser diferentes.';
        elseif ($km <= 0 || $km > 9999) $erro = 'Indique uma distância válida (em km).';
        elseif ($min === null || $min <= 0) $erro = 'Indique a duração estimada, por exemplo 26h 00m.';
        elseif ($preco <= 0) $erro = 'Indique o preço base (em MT).';
        elseif ($hora !== '' && !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) $erro = 'Horário de partida inválido.';
        else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM rota WHERE id_transportadora = ? AND origem = ? AND destino = ?');
            $st->execute([$idT, $origem, $destino]);
            $erro = $st->fetchColumn() > 0 ? 'Esta rota já existe na ' . $transp['nome'] . '.' : null;
        }
        if (!empty($erro)) { flash('erro', $erro); redirecionar('gestor/rotas.php'); }

                $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'pgsql') {
            $stIns = $pdo->prepare('INSERT INTO rota (origem, destino, distancia_km, duracao_estimada, id_transportadora, preco_base) VALUES (?,?,?,?,?,?) RETURNING id_rota');
            $stIns->execute([$origem, $destino, $km, sprintf('%02d:%02d:00', intdiv($min, 60), $min % 60), $idT, $preco]);
            $idR = (int)$stIns->fetchColumn();
        } else {
            $pdo->prepare('INSERT INTO rota (origem, destino, distancia_km, duracao_estimada, id_transportadora, preco_base) VALUES (?,?,?,?,?,?)')
                ->execute([$origem, $destino, $km, sprintf('%02d:%02d:00', intdiv($min, 60), $min % 60), $idT, $preco]);
            $idR = (int)$pdo->lastInsertId();
        }


        $msg = "Rota $origem → $destino criada.";
        if ($hora !== '') {
            $res = gerar_viagens($pdo, $idT, $idR, $hora . ':00', $nDias, $preco, $min, $classe,
                                 (int)($_POST['autocarro'] ?? 0) ?: null, (int)($_POST['motorista'] ?? 0) ?: null);
            $msg .= isset($res['erro']) ? ' Não foram criadas viagens: ' . $res['erro']
                                        : ' Foram programadas ' . $res['n'] . " viagens diárias às $hora.";
        } else {
            $msg .= ' Use «Viagens» para programar os horários.';
        }
        flash('ok', $msg);

    } elseif ($acao === 'editar') {
        $r = rota_propria($pdo, (int)($_POST['id'] ?? 0), $idT, $transp);
        $km = (float)str_replace(',', '.', $_POST['distancia'] ?? '0');
        $min = duracao_para_minutos($_POST['duracao'] ?? '');
        $preco = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
        if ($km <= 0 || $km > 9999 || $min === null || $min <= 0 || $preco <= 0) {
            flash('erro', 'Verifique a distância, a duração e o preço base.');
        } else {
            $pdo->prepare('UPDATE rota SET distancia_km = ?, duracao_estimada = ?, preco_base = ? WHERE id_rota = ?')
                ->execute([$km, sprintf('%02d:%02d:00', intdiv($min, 60), $min % 60), $preco, $r['id_rota']]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Rota actualizada', 'rota', (int)$r['id_rota'], $r['origem'] . ' → ' . $r['destino']);
            flash('ok', 'Rota actualizada. O novo preço base aplica-se às viagens programadas a partir de agora.');
        }

    } elseif ($acao === 'alternar') {
        $r = rota_propria($pdo, (int)($_POST['id'] ?? 0), $idT, $transp);
        $nova = (int)$r['activa'] ? 0 : 1;
        $pdo->prepare('UPDATE rota SET activa = ? WHERE id_rota = ?')->execute([$nova, $r['id_rota']]);
        registar_log($pdo, (int)$eu['id_utilizador'], $nova ? 'Rota activada' : 'Rota desactivada', 'rota', (int)$r['id_rota'], $r['origem'] . ' → ' . $r['destino']);
        flash('ok', 'Rota ' . $r['origem'] . ' → ' . $r['destino'] . ($nova ? ' activada.' : ' desactivada. As reservas já confirmadas mantêm-se válidas.'));
    } elseif ($acao === 'programar') {
        $r = rota_propria($pdo, (int)($_POST['id'] ?? 0), $idT, $transp);
        $hora = trim($_POST['hora'] ?? '');
        $nDias = max(1, min(120, (int)($_POST['dias'] ?? 60)));
        $classe = in_array($_POST['classe'] ?? '', $classes, true) ? $_POST['classe'] : 'Convencional';
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) {
            flash('erro', 'Indique um horário de partida válido.');
        } elseif (!$r['preco_base']) {
            flash('erro', 'Defina primeiro o preço base da rota (botão Editar).');
        } else {
            $res = gerar_viagens($pdo, $idT, (int)$r['id_rota'], $hora . ':00', $nDias, (float)$r['preco_base'], minutos_da_rota($r['duracao_estimada']), $classe,
                                 (int)($_POST['autocarro'] ?? 0) ?: null, (int)($_POST['motorista'] ?? 0) ?: null);
            if (isset($res['erro'])) {
                flash('erro', 'Não foram criadas viagens: ' . $res['erro']);
            } else {
                registar_log($pdo, (int)$eu['id_utilizador'], 'Viagens programadas', 'rota', (int)$r['id_rota'], $res['n'] . " viagens às $hora");
                flash('ok', 'Foram programadas ' . $res['n'] . " viagens às $hora para " . $r['origem'] . ' → ' . $r['destino'] . '.');
            }
        }
    }
    redirecionar('gestor/rotas.php');
}

$st = $pdo->prepare("SELECT ro.*, (SELECT COUNT(*) FROM viagem v WHERE v.id_rota = ro.id_rota AND v.estado = 'agendada' AND v.data >= CURRENT_DATE) AS viagens
                     FROM rota ro WHERE ro.id_transportadora = ? ORDER BY ro.origem, ro.destino");
$st->execute([$idT]);
$rotas = $st->fetchAll();


$st = $pdo->prepare("SELECT id_autocarro, codigo_frota, matricula, capacidade_total, tipo_servico FROM autocarro
                     WHERE id_transportadora = ? AND estado = 'disponivel' AND EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = autocarro.id_autocarro)
                     ORDER BY codigo_frota");
$st->execute([$idT]);
$autocarrosOpc = $st->fetchAll();
$st = $pdo->prepare("SELECT id_motorista, codigo, nome FROM motorista WHERE id_transportadora = ? AND estado = 'disponivel' AND carta_validade >= CURRENT_DATE ORDER BY codigo");

$st->execute([$idT]);
$motoristasOpc = $st->fetchAll();

$tituloPagina = 'Rotas';
$migalhas = ['Painel Gestão', 'Gestão de Rotas'];
$paginaActiva = 'gestor/rotas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Rotas da <?= e($transp['nome']) ?></h1>
    <p class="subtitulo-pagina">Gerir rotas, durações e preços base das ligações activas.</p></div>
  <button class="btn btn-verde" type="button" data-abrir="modal-nova-rota">+ Adicionar rota</button>
</div>

<section class="cartao">
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Origem</th><th>Destino</th><th>Distância (km)</th><th>Duração</th><th>Preço base</th><th>Estado</th><th>Viagens</th><th class="acoes">Acções</th></tr></thead>
    <tbody>
    <?php if (!$rotas): ?><tr><td colspan="8" style="text-align:center;color:var(--cinza);padding:28px">Ainda não tem rotas. Use «Adicionar rota».</td></tr><?php endif; ?>
    <?php foreach ($rotas as $r):
        $min = minutos_da_rota($r['duracao_estimada']);
        $durTxt = sprintf('%dh %02dm', intdiv($min, 60), $min % 60); ?>
      <tr>
        <td><b><?= e($r['origem']) ?></b></td><td><?= e($r['destino']) ?></td>
        <td><?= $r['distancia_km'] !== null ? formatar_numero($r['distancia_km']) : '—' ?></td><td><?= e($durTxt) ?></td>
        <td><b><?= $r['preco_base'] !== null ? e(formatar_mt($r['preco_base'])) : '—' ?></b></td>
        <td><span class="etq <?= $r['activa'] ? 'etq-estado-activa' : 'etq-estado-inactiva' ?>"><?= $r['activa'] ? 'Activa' : 'Desactivada' ?></span></td>
        <td><?= (int)$r['viagens'] ?></td>
        <td class="acoes nao-quebra">
          <button type="button" class="btn-link-e" data-editar-rota data-id="<?= (int)$r['id_rota'] ?>" data-origem="<?= e($r['origem']) ?>" data-destino="<?= e($r['destino']) ?>"
                  data-distancia="<?= e($r['distancia_km']) ?>" data-duracao="<?= e($durTxt) ?>" data-preco="<?= e($r['preco_base']) ?>">Editar</button>
          <button type="button" class="btn-link-e verde" data-programar-rota data-id="<?= (int)$r['id_rota'] ?>" data-origem="<?= e($r['origem']) ?>" data-destino="<?= e($r['destino']) ?>">Viagens</button>
          <form method="post" style="display:inline" data-confirmar="<?= $r['activa'] ? 'Desactivar' : 'Activar' ?> a rota <?= e($r['origem'] . ' → ' . $r['destino']) ?>?<?= $r['activa'] ? ' Deixa de ser possível vender novos bilhetes; as reservas já confirmadas mantêm-se válidas.' : '' ?>">
            <?= csrf_campo() ?><input type="hidden" name="acao" value="alternar"><input type="hidden" name="id" value="<?= (int)$r['id_rota'] ?>">
            <button class="btn-link-e <?= $r['activa'] ? 'perigo' : 'verde' ?>" type="submit"><?= $r['activa'] ? 'Desactivar' : 'Activar' ?></button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>

<!-- Nova rota -->
<dialog class="modal" id="modal-nova-rota">
  <h2>Nova rota <?= e($transp['nome']) ?></h2>
  <p class="sub">Insira os dados da nova rota para a frota autorizada.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="criar">
    <div class="linha-2">
      <div class="campo"><label>Origem</label><input type="text" name="origem" value="Maputo" required></div>
      <div class="campo"><label>Destino</label><input type="text" name="destino" placeholder="Ex.: Pemba" required></div>
    </div>
    <div class="linha-2">
      <div class="campo"><label>Distância (km)</label><input type="number" name="distancia" step="0.1" min="1" max="9999" required></div>
      <div class="campo"><label>Duração estimada</label><input type="text" name="duracao" placeholder="Ex.: 26h 00m" required></div>
    </div>
    <div class="campo"><label>Preço base (MT)</label><input type="number" name="preco" step="0.01" min="1" required></div>
    <div class="bloco-opcional"><h4>Programar viagens diárias (opcional)</h4>
      <div class="tres">
        <div class="campo" style="margin:0"><label>Partida</label><input type="time" name="hora"></div>
        <div class="campo" style="margin:0"><label>Dias</label><input type="number" name="dias" value="60" min="1" max="120"></div>
        <div class="campo" style="margin:0"><label>Classe</label>
          <select name="classe"><?php foreach ($classes as $c): ?><option><?= e($c) ?></option><?php endforeach; ?></select></div>
      </div>
    <div class="linha-2" style="margin-top:10px">
      <div class="campo" style="margin:0"><label>Autocarro</label>
        <select name="autocarro"><option value="0">Automático (primeiro disponível)</option>
          <?php foreach ($autocarrosOpc as $a): ?><option value="<?= (int)$a['id_autocarro'] ?>"><?= e($a['codigo_frota'] . ' · ' . $a['matricula'] . ' · ' . $a['capacidade_total'] . ' lugares') ?></option><?php endforeach; ?></select></div>
      <div class="campo" style="margin:0"><label>Motorista</label>
        <select name="motorista"><option value="0">Sem motorista atribuído</option>
          <?php foreach ($motoristasOpc as $m): ?><option value="<?= (int)$m['id_motorista'] ?>"><?= e($m['codigo'] . ' · ' . $m['nome']) ?></option><?php endforeach; ?></select></div>
    </div>
    </div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Guardar</button></div>
  </form>
</dialog>

<!-- Editar rota -->
<dialog class="modal" id="modal-editar-rota">
  <h2>Editar rota</h2>
  <p class="sub"><b data-rota-nome></b> — a origem e o destino não podem ser alterados.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="editar"><input type="hidden" name="id" value="">
    <div class="linha-2">
      <div class="campo"><label>Origem</label><input type="text" name="origem" readonly></div>
      <div class="campo"><label>Destino</label><input type="text" name="destino" readonly></div>
    </div>
    <div class="linha-2">
      <div class="campo"><label>Distância (km)</label><input type="number" name="distancia" step="0.1" min="1" max="9999" required></div>
      <div class="campo"><label>Duração estimada</label><input type="text" name="duracao" required></div>
    </div>
    <div class="campo"><label>Preço base (MT)</label><input type="number" name="preco" step="0.01" min="1" required></div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Guardar</button></div>
  </form>
</dialog>

<!-- Programar viagens -->
<dialog class="modal" id="modal-programar">
  <h2>Programar viagens</h2>
  <p class="sub"><b data-rota-nome></b> — cria uma viagem por dia, a partir de amanhã.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="programar"><input type="hidden" name="id" value="">
    <div class="tres">
      <div class="campo"><label>Partida</label><input type="time" name="hora" required></div>
      <div class="campo"><label>Dias</label><input type="number" name="dias" value="60" min="1" max="120"></div>
      <div class="campo"><label>Classe</label><select name="classe"><?php foreach ($classes as $c): ?><option><?= e($c) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="linha-2" style="margin-bottom:6px">
      <div class="campo" style="margin:0"><label>Autocarro</label>
        <select name="autocarro"><option value="0">Automático (primeiro disponível)</option>
          <?php foreach ($autocarrosOpc as $a): ?><option value="<?= (int)$a['id_autocarro'] ?>"><?= e($a['codigo_frota'] . ' · ' . $a['matricula'] . ' · ' . $a['capacidade_total'] . ' lugares') ?></option><?php endforeach; ?></select></div>
      <div class="campo" style="margin:0"><label>Motorista</label>
        <select name="motorista"><option value="0">Sem motorista atribuído</option>
          <?php foreach ($motoristasOpc as $m): ?><option value="<?= (int)$m['id_motorista'] ?>"><?= e($m['codigo'] . ' · ' . $m['nome']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Programar</button></div>
  </form>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
