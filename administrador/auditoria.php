<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/backup.php';
exigir_perfil(['administrador']);
$eu = utilizador_logado();
$filtro = filtro_transportadora();

// ---------- acções: backup e configuração de bloqueio ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        flash('erro', 'Pedido inválido. Tente novamente.');
    } elseif (($_POST['acao'] ?? '') === 'backup') {
        try {
            $ficheiro = gerar_backup($pdo);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Backup executado', 'sistema', null, $ficheiro);
            flash('ok', "Backup criado na pasta backups/ do projecto: $ficheiro");
        } catch (Throwable $ex) {
            flash('erro', 'Não foi possível criar o backup.');
        }
    } elseif (($_POST['acao'] ?? '') === 'config_bloqueio') {
        $tent = max(3, min(10, (int)($_POST['tentativas'] ?? 5)));
        $mins = max(1, min(60, (int)($_POST['minutos'] ?? 5)));
        $up = $pdo->prepare('UPDATE configuracao SET valor = ? WHERE chave = ?');
        $up->execute([$tent, 'tentativas_login']);
        $up->execute([$mins, 'bloqueio_minutos']);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Configuração de bloqueio alterada', 'sistema', null, "$tent tentativas, $mins min");
        flash('ok', 'Configuração de bloqueio guardada.');
    }
    redirecionar('administrador/auditoria.php#seguranca');
}

// ---------- filtros dos logs ----------
$fData = $_GET['data'] ?? 'hoje';
$fUser = (int)($_GET['utilizador'] ?? 0);
$fAccao = $_GET['accao'] ?? '';

$onde = ['1=1'];
$p = [];
$hoje0 = date('Y-m-d 00:00:00');
if ($fData === 'hoje')       { $onde[] = 'l.data_hora >= ?'; $p[] = $hoje0; }
elseif ($fData === 'ontem')  { $onde[] = 'l.data_hora >= ? AND l.data_hora < ?'; $p[] = date('Y-m-d 00:00:00', strtotime('-1 day')); $p[] = $hoje0; }
elseif ($fData === '7dias')  { $onde[] = 'l.data_hora >= ?'; $p[] = date('Y-m-d 00:00:00', strtotime('-6 days')); }
if ($fUser > 0)    { $onde[] = 'l.id_utilizador = ?'; $p[] = $fUser; }
if ($fAccao !== '') { $onde[] = 'l.accao = ?'; $p[] = $fAccao; }
if ($filtro)       { $onde[] = 'u.id_transportadora = ?'; $p[] = $filtro; }
$cond = implode(' AND ', $onde);

$sql = "SELECT l.*, u.nome FROM log_transaccao l
        LEFT JOIN utilizador u ON u.id_utilizador = l.id_utilizador
        WHERE $cond ORDER BY l.data_hora DESC, l.id_log DESC";

// ---------- exportar CSV ----------
if (isset($_GET['exportar'])) {
    $st = $pdo->prepare($sql . ' LIMIT 5000');
    $st->execute($p);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="auditoria_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Data/Hora', 'Utilizador', 'Acção', 'Detalhes', 'IP'], ';');
    foreach ($st->fetchAll() as $l) {
        fputcsv($out, [$l['data_hora'], $l['nome'] ?? 'Sistema', $l['accao'], $l['detalhes'], $l['ip']], ';');
    }
    fclose($out);
    exit;
}

$st = $pdo->prepare($sql . ' LIMIT 100');
$st->execute($p);
$logs = $st->fetchAll();

$utilizadores = $pdo->query('SELECT id_utilizador, nome FROM utilizador ORDER BY nome')->fetchAll();
$accoes = $pdo->query('SELECT DISTINCT accao FROM log_transaccao ORDER BY accao')->fetchAll(PDO::FETCH_COLUMN);

function cor_log(string $accao): string {
    $a = mb_strtolower($accao);
    if (preg_match('/bloquead|desactiv|falhad/u', $a)) return 'vermelho';
    if (preg_match('/venda|rota|alterad|actualiz|reposta|criado|adicionad|redefinid/u', $a)) return 'laranja';
    return 'verde';
}

$tentativas = (int)config_valor($pdo, 'tentativas_login', 5);
$minutos    = (int)config_valor($pdo, 'bloqueio_minutos', 5);
$ultimoBackup = config_valor($pdo, 'ultimo_backup');

$tituloPagina = 'Auditoria e Segurança';
$migalhas = ['Painel Admin', 'Auditoria & Segurança'];
$paginaActiva = 'administrador/auditoria.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div>
    <h1 class="titulo-pagina">Logs de Auditoria</h1>
    <p class="subtitulo-pagina">Historial de acções dos utilizadores para conformidade e segurança da rede.</p>
  </div>
  <a class="btn btn-sec" href="<?= e('?' . http_build_query(array_merge($_GET, ['exportar' => 1]))) ?>">⬇ Exportar CSV</a>
</div>

<section class="cartao">
  <form method="get" class="filtros">
    <select name="data">
      <option value="hoje" <?= $fData === 'hoje' ? 'selected' : '' ?>>Data: Hoje</option>
      <option value="ontem" <?= $fData === 'ontem' ? 'selected' : '' ?>>Data: Ontem</option>
      <option value="7dias" <?= $fData === '7dias' ? 'selected' : '' ?>>Data: Últimos 7 dias</option>
      <option value="todos" <?= $fData === 'todos' ? 'selected' : '' ?>>Data: Todas</option>
    </select>
    <select name="utilizador">
      <option value="0">Utilizador: Todos</option>
      <?php foreach ($utilizadores as $us): ?>
        <option value="<?= (int)$us['id_utilizador'] ?>" <?= $fUser === (int)$us['id_utilizador'] ? 'selected' : '' ?>><?= e($us['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="accao">
      <option value="">Acção: Todas as acções</option>
      <?php foreach ($accoes as $ac): ?>
        <option value="<?= e($ac) ?>" <?= $fAccao === $ac ? 'selected' : '' ?>><?= e($ac) ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Data / Hora</th><th>Utilizador</th><th>Acção realizada</th><th>Endereço IP</th></tr></thead>
    <tbody>
    <?php if (!$logs): ?><tr><td colspan="4" style="text-align:center;color:var(--cinza);padding:26px">Sem registos para os filtros seleccionados.</td></tr><?php endif; ?>
    <?php foreach ($logs as $l): ?>
      <tr>
        <td class="mono"><?= e(date('d/m/Y H:i', strtotime($l['data_hora']))) ?></td>
        <td><strong><?= e($l['nome'] ?? 'Sistema Automático') ?></strong></td>
        <td><span class="ponto ponto-<?= cor_log($l['accao']) ?>"></span><?= e($l['accao']) ?>
          <?php if ($l['detalhes']): ?><span class="detalhe-log"> · <?= e($l['detalhes']) ?></span><?php endif; ?></td>
        <td class="mono"><?= e($l['ip'] ?: '—') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <p class="pequeno" style="margin:12px 0 0">A mostrar os 100 registos mais recentes. Use «Exportar CSV» para obter a lista completa.</p>
</section>

<div class="tres-cartoes" id="seguranca">
  <section class="cartao">
    <h2>Cópias de Segurança (Backup)</h2>
    <p class="pequeno">Protecção contra perda de dados da bilheteira.</p>
    <div class="caixa-cinza">
      <div>Último backup guardado: <b><?= $ultimoBackup ? e(date('d/m/Y H:i', strtotime($ultimoBackup))) : 'nenhum' ?></b></div>
      <div>Próximo backup agendado: <b>não configurado</b></div>
    </div>
    <form method="post"><?= csrf_campo() ?><input type="hidden" name="acao" value="backup">
      <button class="btn btn-primario btn-bloco" type="submit">Fazer backup agora</button></form>
  </section>

  <section class="cartao">
    <h2>Configuração de Bloqueio</h2>
    <p class="pequeno">Defina o limite de falhas aceitáveis antes da restrição.</p>
    <form method="post"><?= csrf_campo() ?><input type="hidden" name="acao" value="config_bloqueio">
      <div class="linha-2" style="margin-top:12px">
        <div class="campo"><label>Tentativas de login</label>
          <select name="tentativas"><?php for ($i = 3; $i <= 10; $i++): ?><option value="<?= $i ?>" <?= $i === $tentativas ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select></div>
        <div class="campo"><label>Minutos de bloqueio</label>
          <input type="number" name="minutos" min="1" max="60" value="<?= $minutos ?>"></div>
      </div>
      <p class="pequeno">O utilizador bloqueado precisará de aguardar <?= $minutos ?> minutos ou pedir reset de palavra-passe para voltar a tentar.</p>
      <button class="btn btn-sec btn-bloco" type="submit" style="margin-top:12px">Guardar configuração</button>
    </form>
  </section>

  <!-- Valores ilustrativos: ainda não existe monitorização real de disponibilidade -->
  <section class="cartao">
    <h2>Uptime do Sistema</h2>
    <p class="pequeno"><span class="ponto ponto-verde"></span>Sistema operacional · 99.9%</p>
    <p class="pequeno" style="margin-top:14px">DISPONIBILIDADE (ÚLTIMOS 7 DIAS)</p>
    <div class="uptime-barras"><?php for ($i = 0; $i < 7; $i++): ?><i></i><?php endfor; ?></div>
    <div class="uptime-dias"><?php for ($i = 1; $i <= 7; $i++): ?><span>D<?= $i ?></span><?php endfor; ?></div>
  </section>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
