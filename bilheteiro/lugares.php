<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'ver_dashboard');
libertar_reservas_expiradas($pdo);
$transp = transportadora_do_bilheteiro($pdo);

// viagens de hoje (ainda por partir) e de amanhã
$st = $pdo->prepare("SELECT v.*, ro.origem, ro.destino, t.nome AS transportadora, a.matricula
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                     JOIN transportadora t ON t.id_transportadora = ro.id_transportadora JOIN autocarro a ON a.id_autocarro = v.id_autocarro
                     WHERE ro.id_transportadora = ? AND v.estado = 'agendada' AND ((v.data = ? AND v.hora_partida > ?) OR v.data = ?)
                     ORDER BY v.data, v.hora_partida, t.nome, ro.destino LIMIT 80");
$st->execute([$transp['id'], date('Y-m-d'), date('H:i:s'), date('Y-m-d', strtotime('+1 day'))]);
$viagens = $st->fetchAll();

$idV = (int)($_GET['viagem'] ?? 0);
$v = null;
foreach ($viagens as $x) { if ((int)$x['id_viagem'] === $idV) $v = $x; }
if (!$v && $viagens) $v = $viagens[0];

$filas = []; $ocupados = []; $total = 0;
if ($v) {
    [$filas, $ocupados] = carregar_mapa($pdo, $v);
    foreach ($filas as $f) $total += count($f);
}
$rotuloDia = fn($d) => $d === date('Y-m-d') ? 'Hoje' : 'Amanhã';

$tituloPagina = 'Lugares disponíveis';
$migalhas = ['Bilheteira', 'Lugares Disponíveis'];
$paginaActiva = 'bilheteiro/lugares.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<?php if (!$v): ?>
  <div class="vazio">Não há viagens para hoje ou amanhã.</div>
<?php else: ?>
<form method="get" class="cartao seletor-viagem nao-imprimir">
  <label for="viagem"><b>Viagem</b></label>
  <select name="viagem" id="viagem" onchange="this.form.submit()">
    <?php foreach ($viagens as $x): ?>
      <option value="<?= (int)$x['id_viagem'] ?>" <?= (int)$x['id_viagem'] === (int)$v['id_viagem'] ? 'selected' : '' ?>>
        <?= e($rotuloDia($x['data'])) ?> · <?= e(hora_curta($x['hora_partida'])) ?> · <?= e($x['transportadora']) ?> · <?= e($x['origem']) ?> → <?= e($x['destino']) ?></option>
    <?php endforeach; ?>
  </select>
</form>

<section class="cartao cabecalho-viagem">
  <div class="cv-esq"><span class="cv-icone"><?= icone_svg('rota') ?></span>
    <div><h2><?= e($v['transportadora']) ?> • <?= e($v['matricula']) ?></h2>
      <small>Rota: <?= e($v['origem']) ?> → <?= e($v['destino']) ?> • Partida: <?= e(hora_curta($v['hora_partida'])) ?> (<?= e($rotuloDia($v['data'])) ?>)</small></div></div>
  <div class="cv-dir"><span class="contagem-vivo"><b class="vermelho" id="n-ocupados"><?= count($ocupados) ?> ocupados</b> • <b class="verde" id="n-livres"><?= $total - count($ocupados) ?> livres</b></span>
    <span class="ao-vivo">● AO VIVO</span></div>
</section>

<section class="cartao" style="text-align:center">
  <h2 style="font-size:17px;margin-bottom:14px">Ocupação em tempo real</h2>
  <div id="mapa-vivo" data-url="<?= e(url('api/bilheteiro_ocupacao.php?viagem=' . (int)$v['id_viagem'])) ?>">
    <?= mapa_autocarro_html($filas, $ocupados, false) ?>
  </div>
  <p class="pequeno" style="margin-top:12px">Actualiza automaticamente a cada poucos segundos.</p>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
