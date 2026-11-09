<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
libertar_reservas_expiradas($pdo);
$transp = transportadora_do_bilheteiro($pdo);

$origem  = trim($_GET['origem'] ?? 'Maputo');
$destino = trim($_GET['destino'] ?? '');
$data    = $_GET['data'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || $data < date('Y-m-d')) $data = date('Y-m-d');
$hoje = $data === date('Y-m-d');

$st = $pdo->prepare('SELECT DISTINCT origem FROM rota WHERE activa = 1 AND id_transportadora = ? ORDER BY origem');
$st->execute([$transp['id']]);
$origens = $st->fetchAll(PDO::FETCH_COLUMN);
$st = $pdo->prepare('SELECT DISTINCT destino FROM rota WHERE activa = 1 AND id_transportadora = ? ORDER BY destino');
$st->execute([$transp['id']]);
$destinos = $st->fetchAll(PDO::FETCH_COLUMN);

$sql = 'SELECT v.*, ro.origem, ro.destino, t.nome AS transportadora, a.capacidade_total, ' . lugares_livres_sql() . ' AS ocupados
        FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota AND ro.activa = 1
        JOIN transportadora t ON t.id_transportadora = ro.id_transportadora AND t.activa = 1
        JOIN autocarro a ON a.id_autocarro = v.id_autocarro
        WHERE ro.origem = ? AND v.data = ? AND v.estado = \'agendada\'';
$p = [$origem, $data];
$sql .= ' AND ro.id_transportadora = ?';      // só vende bilhetes da sua transportadora
$p[] = $transp['id'];
if ($destino !== '') { $sql .= ' AND ro.destino = ?'; $p[] = $destino; }
// A viagem fica disponível até ser realizada (estado deixa de ser 'agendada'), mesmo depois da hora de partida.
$st = $pdo->prepare($sql . ' ORDER BY v.hora_partida, t.nome LIMIT 60');
$st->execute($p);
$viagens = $st->fetchAll();

$tituloPagina = 'Vender Bilhete';
$migalhas = ['Bilheteira', 'Vender Bilhete'];
$paginaActiva = 'bilheteiro/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
echo passos_venda(1);
?>
<p class="etiqueta-transp nao-imprimir">A vender bilhetes da transportadora <b><?= e($transp['nome']) ?></b></p>
<?php if (!$origens): ?>
  <div class="alerta alerta-info"><b>A transportadora <?= e($transp['nome']) ?> ainda não tem rotas activas.</b>
    As rotas e viagens são criadas pelo Gestor da transportadora.
    <?php if (AMBIENTE_DEMO): ?>Em demonstração, execute <b>instalar/criar_dados_demo.php</b> para gerar rotas e viagens de teste.<?php endif; ?></div>
<?php endif; ?>
<section class="cartao">
  <form method="get" class="form-procura">
    <div class="campo"><label>Origem</label>
      <select name="origem"><?php foreach ($origens as $o): ?><option <?= $o === $origem ? 'selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label>Destino</label>
      <select name="destino"><option value="">Seleccione o destino...</option>
        <?php foreach ($destinos as $d): ?><option <?= $d === $destino ? 'selected' : '' ?>><?= e($d) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label>Data de partida</label>
      <input type="date" name="data" min="<?= date('Y-m-d') ?>" value="<?= e($data) ?>"></div>
    <button class="btn btn-primario" type="submit" style="margin-bottom:16px">Procurar</button>
  </form>
</section>

<section class="cartao" style="margin-top:18px">
  <div class="cabecalho-pagina" style="align-items:center;margin-bottom:8px">
    <h2>Viagens disponíveis para <?= $hoje ? 'hoje' : e(data_com_ano($data)) ?></h2>
    <span class="pequeno"><?= count($viagens) ?> rota<?= count($viagens) === 1 ? '' : 's' ?> encontrada<?= count($viagens) === 1 ? '' : 's' ?></span>
  </div>
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Transportadora</th><th>Rota</th><th>Partida</th><th>Lugares livres</th><th>Preço</th><th></th></tr></thead>
    <tbody>
    <?php if (!$viagens): ?><tr><td colspan="6" style="text-align:center;color:var(--cinza);padding:28px">Não há viagens para esta pesquisa.</td></tr><?php endif; ?>
    <?php foreach ($viagens as $v):
        $livres = max(0, (int)$v['capacidade_total'] - (int)$v['ocupados']); ?>
      <tr>
        <td><div class="empresa"><?= inicial_empresa($v['transportadora']) ?><b><?= e($v['transportadora']) ?></b></div></td>
        <td><?= e($v['origem']) ?> → <?= e($v['destino']) ?></td>
        <td><?= e(hora_curta($v['hora_partida'])) ?></td>
        <td><span class="livres <?= $livres === 0 ? 'livres-0' : ($livres <= 8 ? 'livres-poucos' : 'livres-ok') ?>"><?= $livres ?> livres</span></td>
        <td><b><?= e(formatar_mt($v['preco'])) ?></b></td>
        <td class="acoes"><?php if ($livres > 0): ?>
          <a class="btn btn-primario btn-pequeno" href="<?= e(url('bilheteiro/vender_lugares.php?viagem=' . (int)$v['id_viagem'])) ?>">Seleccionar</a>
          <?php else: ?><span class="btn btn-sec btn-pequeno" style="opacity:.6;cursor:not-allowed">Esgotado</span><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>