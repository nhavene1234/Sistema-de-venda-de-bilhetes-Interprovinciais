<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
require_once __DIR__ . '/../includes/cobradores.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();

$idC = (int)($_GET['id'] ?? 0);
$cob = cobrador_da_transportadora($pdo, $idC, $idT);
if (!$cob) acesso_restrito('Só tem permissão para gerir cobradores da ' . $transp['nome'] . '.');

// ---------- filtros (GET) ----------
$hoje = date('Y-m-d');
$dataOk = fn($d) => is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
$fIni = $dataOk($_GET['inicio'] ?? '') ? $_GET['inicio'] : $hoje;
if ($fIni < $hoje) $fIni = $hoje;                                   // só se atribuem viagens de hoje em diante
$fFim = $dataOk($_GET['fim'] ?? '') ? $_GET['fim'] : date('Y-m-d', strtotime("$fIni +6 days"));
if ($fFim < $fIni) $fFim = $fIni;
if (strtotime($fFim) - strtotime($fIni) > 31 * 86400) $fFim = date('Y-m-d', strtotime("$fIni +31 days"));
$fMot  = (int)($_GET['motorista'] ?? 0);                            // 0 = todos · -1 = sem motorista · >0 = esse motorista
$fRota = (int)($_GET['rota'] ?? 0);
$qs = http_build_query(['id' => $idC, 'inicio' => $fIni, 'fim' => $fFim, 'motorista' => $fMot, 'rota' => $fRota]);

/** Carrega as viagens (abertas, da transportadora) com os ids indicados. */
function viagens_do_gestor(PDO $pdo, array $ids, int $idT): array {
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
    if (!$ids) return [];
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, v.id_cobrador, ro.origem, ro.destino, ro.duracao_estimada
                         FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                         WHERE v.id_viagem IN ($in) AND ro.id_transportadora = ? AND v.data >= CURRENT_DATE AND v.estado IN " . VIAGEM_ESTADOS_ABERTOS . "
                         ORDER BY v.data, v.hora_partida");
    $st->execute(array_merge($ids, [$idT]));
    return $st->fetchAll();
}

// ---------- acções (POST) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/cobrador_viagens.php?' . $qs); }
    $acao = $_POST['acao'] ?? '';
    $pedidas = is_array($_POST['viagens'] ?? null) ? $_POST['viagens'] : [];
    $viagens = viagens_do_gestor($pdo, $pedidas, $idT);   // ignora tudo o que não for desta transportadora

    if (!$viagens) {
        flash('erro', 'Seleccione pelo menos uma viagem.');

    } elseif ($acao === 'remover') {
        $in = implode(',', array_fill(0, count($viagens), '?'));
        $st = $pdo->prepare("UPDATE viagem SET id_cobrador = NULL WHERE id_cobrador = ? AND id_viagem IN ($in)");
        $st->execute(array_merge([$idC], array_map('intval', array_column($viagens, 'id_viagem'))));
        registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador removido de viagens', 'utilizador', $idC, $cob['nome'] . ' · ' . $st->rowCount() . ' viagem(ns)');
        flash('ok', $cob['nome'] . ' foi removido de ' . $st->rowCount() . ' viagem(ns).');

    } elseif ($acao === 'atribuir') {
        $idM = (int)($_POST['definir_motorista'] ?? 0);
        $erro = null;
        if (!(int)$cob['activo']) $erro = 'A conta de ' . $cob['nome'] . ' está desactivada.';

        if (!$erro && $idM) {
            $ultima = max(array_column($viagens, 'data'));
            $st = $pdo->prepare('SELECT nome, estado, carta_validade FROM motorista WHERE id_motorista = ? AND id_transportadora = ?');
            $st->execute([$idM, $idT]);
            $m = $st->fetch();
            if (!$m) $erro = 'O motorista escolhido não pertence à sua transportadora.';
            elseif ($m['estado'] !== 'disponivel') $erro = 'O motorista ' . $m['nome'] . ' não está disponível.';
            elseif ($m['carta_validade'] < $ultima) $erro = 'A carta de condução de ' . $m['nome'] . ' expira em ' . date('d/m/Y', strtotime($m['carta_validade'])) . ', antes da última viagem seleccionada.';
        }
        if (!$erro) {
            $conflitos = conflitos_do_cobrador($pdo, $idC, $viagens);
            if ($conflitos) {
                $erro = 'Um cobrador não pode estar em duas viagens ao mesmo tempo: ' . implode(' ', array_slice($conflitos, 0, 3)) . (count($conflitos) > 3 ? ' (…)' : '');
            }
        }
        if ($erro) {
            flash('erro', $erro);
        } else {
            $ids = array_map('intval', array_column($viagens, 'id_viagem'));
            $in = implode(',', array_fill(0, count($ids), '?'));
            $pdo->beginTransaction();
            try {
                if ($idM) $pdo->prepare("UPDATE viagem SET id_cobrador = ?, id_motorista = ? WHERE id_viagem IN ($in)")->execute(array_merge([$idC, $idM], $ids));
                else      $pdo->prepare("UPDATE viagem SET id_cobrador = ? WHERE id_viagem IN ($in)")->execute(array_merge([$idC], $ids));
                $pdo->commit();
            } catch (Throwable $ex) {
                $pdo->rollBack();
                flash('erro', 'Não foi possível guardar a atribuição. Tente novamente.');
                redirecionar('gestor/cobrador_viagens.php?' . $qs);
            }
            registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador atribuído a viagens', 'utilizador', $idC,
                         $cob['nome'] . ' · ' . count($ids) . ' viagem(ns)' . ($idM ? ' · motorista ' . ($m['nome'] ?? $idM) : ''));
            flash('ok', $cob['nome'] . ' foi atribuído a ' . count($ids) . ' viagem(ns)' . ($idM ? ' com o motorista ' . $m['nome'] : '') . '.');
        }
    }
    redirecionar('gestor/cobrador_viagens.php?' . $qs);
}

// ---------- dados para o ecrã ----------
$onde = ['ro.id_transportadora = ?', "v.estado IN " . VIAGEM_ESTADOS_ABERTOS, 'v.data BETWEEN ? AND ?'];
$p = [$idT, $fIni, $fFim];
if ($fMot > 0)  { $onde[] = 'v.id_motorista = ?'; $p[] = $fMot; }
if ($fMot < 0)  { $onde[] = 'v.id_motorista IS NULL'; }
if ($fRota > 0) { $onde[] = 'v.id_rota = ?'; $p[] = $fRota; }
$st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, v.id_cobrador, ro.origem, ro.destino, au.matricula, au.codigo_frota,
                            m.nome AS motorista, m.codigo AS motorista_codigo, cu.nome AS cobrador_nome
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro au ON au.id_autocarro = v.id_autocarro
                     LEFT JOIN motorista m ON m.id_motorista = v.id_motorista LEFT JOIN utilizador cu ON cu.id_utilizador = v.id_cobrador
                     WHERE " . implode(' AND ', $onde) . " ORDER BY v.data, v.hora_partida, ro.origem LIMIT 301");
$st->execute($p);
$lista = $st->fetchAll();
$cortada = count($lista) > 300;
if ($cortada) array_pop($lista);

$st = $pdo->prepare('SELECT id_motorista, codigo, nome, estado FROM motorista WHERE id_transportadora = ? ORDER BY codigo');
$st->execute([$idT]);
$motoristas = $st->fetchAll();
$st = $pdo->prepare('SELECT id_rota, origem, destino FROM rota WHERE id_transportadora = ? ORDER BY origem, destino');
$st->execute([$idT]);
$rotas = $st->fetchAll();

$st = $pdo->prepare("SELECT COUNT(*) FROM viagem WHERE id_cobrador = ? AND data >= CURRENT_DATE AND estado IN " . VIAGEM_ESTADOS_ABERTOS);
$st->execute([$idC]);
$jaAtribuidas = (int)$st->fetchColumn();

$tituloPagina = 'Atribuir viagens';
$migalhas = ['Cobradores', $cob['nome'], 'Atribuir viagens'];
$paginaActiva = 'gestor/cobradores.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Atribuir viagens a <?= e($cob['nome']) ?></h1>
    <p class="subtitulo-pagina">Escolha as viagens em que este cobrador valida bilhetes e, se quiser, o motorista que as conduz.
      Actualmente tem <b><?= $jaAtribuidas ?></b> viagem(ns) atribuída(s).</p></div>
  <div class="botoes-cab">
    <a class="btn btn-sec" href="<?= e(url('gestor/motorista_novo.php')) ?>">+ Novo motorista</a>
    <a class="btn btn-sec" href="<?= e(url('gestor/cobradores.php')) ?>">← Voltar aos cobradores</a>
  </div>
</div>

<section class="cartao">
  <form method="get" class="filtros">
    <input type="hidden" name="id" value="<?= $idC ?>">
    <select name="motorista" aria-label="Motorista">
      <option value="0">Motorista: todos</option>
      <option value="-1" <?= $fMot === -1 ? 'selected' : '' ?>>Sem motorista</option>
      <?php foreach ($motoristas as $m): ?><option value="<?= (int)$m['id_motorista'] ?>" <?= $fMot === (int)$m['id_motorista'] ? 'selected' : '' ?>><?= e($m['codigo'] . ' · ' . $m['nome']) ?></option><?php endforeach; ?>
    </select>
    <select name="rota" aria-label="Rota">
      <option value="0">Rota: todas</option>
      <?php foreach ($rotas as $r): ?><option value="<?= (int)$r['id_rota'] ?>" <?= $fRota === (int)$r['id_rota'] ? 'selected' : '' ?>><?= e($r['origem'] . ' → ' . $r['destino']) ?></option><?php endforeach; ?>
    </select>
    <label class="pequeno">De <input type="date" name="inicio" value="<?= e($fIni) ?>" min="<?= e($hoje) ?>"></label>
    <label class="pequeno">Até <input type="date" name="fim" value="<?= e($fFim) ?>" min="<?= e($hoje) ?>"></label>
    <button class="btn btn-sec btn-pequeno" type="submit">Filtrar</button>
  </form>

  <form method="post" action="<?= e(url('gestor/cobrador_viagens.php?' . $qs)) ?>">
    <?= csrf_campo() ?>
    <div class="tabela-rolar">
    <table class="tabela">
      <thead><tr><th style="width:34px"><input type="checkbox" data-todos-viagens aria-label="Seleccionar todas"></th>
        <th>Data</th><th>Partida</th><th>Rota</th><th>Autocarro</th><th>Motorista</th><th>Cobrador</th></tr></thead>
      <tbody>
      <?php if (!$lista): ?><tr><td colspan="7" style="text-align:center;color:var(--cinza);padding:28px">Não há viagens abertas com estes filtros.</td></tr><?php endif; ?>
      <?php foreach ($lista as $v): $meu = (int)$v['id_cobrador'] === $idC; ?>
        <tr>
          <td><input type="checkbox" name="viagens[]" value="<?= (int)$v['id_viagem'] ?>" data-viagem-sel aria-label="Seleccionar viagem"></td>
          <td><b><?= e(date('d/m/Y', strtotime($v['data']))) ?></b></td>
          <td class="mono"><?= e(substr($v['hora_partida'], 0, 5)) ?></td>
          <td><?= e($v['origem'] . ' → ' . $v['destino']) ?></td>
          <td class="mono"><?= e($v['codigo_frota'] ?: $v['matricula']) ?></td>
          <td><?= $v['motorista'] ? e($v['motorista']) . '<span class="sub-linha">' . e($v['motorista_codigo']) . '</span>' : '<span class="sub-linha" style="margin:0">Sem motorista</span>' ?></td>
          <td><?php if ($meu): ?><span class="etq-g etq-pago">Este cobrador</span>
              <?php elseif ($v['id_cobrador']): ?><span class="etq-g etq-expirado" title="Será substituído">Substitui <?= e($v['cobrador_nome']) ?></span>
              <?php else: ?><span class="sub-linha" style="margin:0">Livre</span><?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php if ($cortada): ?><p class="pequeno" style="margin-top:10px">A mostrar as primeiras 300 viagens. Reduza o período ou use os filtros.</p><?php endif; ?>

    <div class="barra-atribuir">
      <div class="campo" style="margin:0">
        <label for="definir_motorista">Motorista nas viagens seleccionadas</label>
        <select name="definir_motorista" id="definir_motorista">
          <option value="0">Manter o motorista de cada viagem</option>
          <?php foreach ($motoristas as $m): if ($m['estado'] !== 'disponivel') continue; ?>
            <option value="<?= (int)$m['id_motorista'] ?>"><?= e($m['codigo'] . ' · ' . $m['nome']) ?></option>
          <?php endforeach; ?>
        </select>
        <small class="pequeno">Se escolher um motorista, passa a conduzir todas as viagens seleccionadas (substitui o actual).</small>
      </div>
      <div class="botoes">
        <button class="btn btn-sec" type="submit" name="acao" value="remover" data-precisa-sel>Remover seleccionadas</button>
        <button class="btn btn-verde" type="submit" name="acao" value="atribuir" data-precisa-sel>Atribuir <span data-n-sel>0</span> viagem(ns)</button>
      </div>
    </div>
  </form>
</section>
<p class="nota-rodape">ⓘ Só as viagens atribuídas aqui ficam disponíveis para este cobrador validar bilhetes (no dia da viagem). Um cobrador não pode estar em duas viagens que se sobreponham no tempo.</p>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
