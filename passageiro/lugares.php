<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

$idV = (int)($_GET['viagem'] ?? 0);
$remarcar = (int)($_GET['remarcar'] ?? 0);

// Compra normal: até MAX_LUGARES por vez (definido em includes/passageiro.php). Remarcação mexe numa só reserva, por isso 1 lugar.
$maxLugares = $remarcar ? 1 : MAX_LUGARES;

$st = $pdo->prepare('SELECT v.*, r.origem, r.destino, t.nome AS transportadora
                     FROM viagem v JOIN rota r ON r.id_rota = v.id_rota
                     JOIN transportadora t ON t.id_transportadora = r.id_transportadora WHERE v.id_viagem = ?');
$st->execute([$idV]);
$v = $st->fetch();
if (!$v || $v['estado'] !== 'agendada' || partida_ts($v) <= time()) {
    flash('erro', motivo_indisponivel($v ?: null));
    redirecionar('passageiro/index.php');
}

// lugares do autocarro e lugares ocupados (o próprio bloqueio pendente do passageiro conta como livre)
$st = $pdo->prepare('SELECT id_assento, numero FROM assento WHERE id_autocarro = ?');
$st->execute([$v['id_autocarro']]);
$ocupados = $pdo->prepare("SELECT id_assento FROM reserva WHERE id_viagem = ? AND estado IN ('pendente','confirmada')
                           AND NOT (estado = 'pendente' AND id_passageiro = ?)");
$ocupados->execute([$idV, $u['id_utilizador']]);
$ocupados = array_flip($ocupados->fetchAll(PDO::FETCH_COLUMN));

$cfg = $pdo->prepare('SELECT configuracao_assentos FROM autocarro WHERE id_autocarro = ?');
$cfg->execute([$v['id_autocarro']]);
$filas = organizar_filas($st->fetchAll(), (string)($cfg->fetchColumn() ?: '2+2'));

$voltar = 'passageiro/resultados.php?' . http_build_query(['origem' => $v['origem'], 'destino' => $v['destino'], 'data' => $v['data']]);
$accao = $remarcar ? 'passageiro/remarcar_confirmar.php' : 'passageiro/reservar.php';

app_abrir(['titulo' => $remarcar ? 'Escolha o seu lugar' : 'Escolha os seus lugares', 'voltar' => $voltar,
           'h1' => $remarcar ? 'Escolha o seu lugar' : 'Escolha os seus lugares',
           'sub' => $v['transportadora'] . ' · ' . $v['origem'] . ' → ' . $v['destino'] . ' · ' . data_curta($v['data']) . ' ' . hora_curta($v['hora_partida'])]);

function celula_lugar(?array $a, array $ocupados): string {
    if (!$a) return '<span></span>';
    $oc = isset($ocupados[$a['id_assento']]);
    return '<button type="button" class="lugar' . ($oc ? ' ocupado' : '') . '" data-id="' . (int)$a['id_assento']
         . '" data-num="' . e($a['numero']) . '"' . ($oc ? ' disabled' : '') . '>' . e($a['numero']) . '</button>';
}
?>
<?php if (!$remarcar): ?>
  <p class="pequeno" style="margin:0 0 10px;text-align:center">Pode escolher até <b><?= $maxLugares ?> lugares</b> na mesma compra (família, amigos...).</p>
<?php endif; ?>
<div class="legenda">
  <span><i style="background:#e9f9ef;border:1px solid #9bdcb4"></i>Livre</span>
  <span><i style="background:#fdeaec;border:1px solid #f1b6bc"></i>Ocupado</span>
  <span><i style="background:var(--azul)"></i>Seleccionado</span>
</div>

<form method="post" action="<?= e(url($accao)) ?>" id="form-lugares">
  <?= csrf_campo() ?>
  <input type="hidden" name="viagem" value="<?= $idV ?>">
  <input type="hidden" name="reserva" value="<?= $remarcar ?>">
  <input type="hidden" id="campo-assento" value="">
  <div id="campos-lugares"></div>

  <div class="mapa" id="mapa-lugares">
    <div class="mapa-frente"><span>FRENTE</span><?= ic('volante') ?></div>
    <?php foreach ($filas as $n => $f): ?>
      <div class="fila">
        <?= celula_lugar($f['A'] ?? null, $ocupados) ?><?= celula_lugar($f['B'] ?? null, $ocupados) ?>
        <span class="num"><?= $n ?></span>
        <?= celula_lugar($f['C'] ?? null, $ocupados) ?><?= celula_lugar($f['D'] ?? null, $ocupados) ?>
      </div>
    <?php endforeach; ?>
  </div>

  <p class="pequeno" id="aviso-max" style="display:none;text-align:center;color:#c0392b;margin:8px 0 0">
    O máximo é de <?= $maxLugares ?> lugar<?= $maxLugares === 1 ? '' : 'es' ?> por compra.</p>

  <div class="barra-fixa">
    <div class="info">
      <small><span id="lugar-titulo">Lugar</span> <span id="lugar-rotulo">—</span></small>
      <b id="total-valor"><?= e(formatar_mt($v['preco'])) ?></b>
      <?php if (!$remarcar): ?><small id="preco-unit" style="display:none"><?= e(formatar_mt($v['preco'])) ?> por lugar</small><?php endif; ?>
    </div>
    <button class="btn-app" type="submit" id="btn-continuar" disabled>
      <span id="btn-texto"><?= $remarcar ? 'Remarcar' : 'Continuar' ?></span> <?= ic('direita') ?></button>
  </div>
</form>

<script>
(function () {
  var MAX = <?= (int)$maxLugares ?>;
  var PRECO = <?= json_encode((float)$v['preco']) ?>;
  var REMARCAR = <?= $remarcar ? 'true' : 'false' ?>;
  var NOME_CAMPO = REMARCAR ? 'assento' : 'assentos[]';

  var mapa = document.getElementById('mapa-lugares');
  var campos = document.getElementById('campos-lugares');
  var btn = document.getElementById('btn-continuar');
  var btnTexto = document.getElementById('btn-texto');
  var rotulo = document.getElementById('lugar-rotulo');
  var titulo = document.getElementById('lugar-titulo');
  var total = document.getElementById('total-valor');
  var unit = document.getElementById('preco-unit');
  var aviso = document.getElementById('aviso-max');
  var totalInicial = total.textContent;
  var escolhidos = [];   // [{id, num, el}]

  function mt(v) {
    return v.toLocaleString('pt-PT', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' MT';
  }

  function pintar(el, activo) {
    el.classList.toggle('seleccionado', activo);
    el.classList.toggle('selecionado', activo);
    el.classList.toggle('sel', activo);
    el.style.background = activo ? 'var(--azul)' : '';
    el.style.color = activo ? '#fff' : '';
    el.style.borderColor = activo ? 'var(--azul)' : '';
  }

  function actualizar() {
    campos.innerHTML = '';
    escolhidos.forEach(function (l) {
      var i = document.createElement('input');
      i.type = 'hidden'; i.name = NOME_CAMPO; i.value = l.id;
      campos.appendChild(i);
    });
    var n = escolhidos.length;
    rotulo.textContent = n ? escolhidos.map(function (l) { return l.num; }).join(', ') : '—';
    titulo.textContent = n > 1 ? 'Lugares' : 'Lugar';
    total.textContent = n ? mt(PRECO * n) : totalInicial;
    if (unit) unit.style.display = n > 1 ? '' : 'none';
    btnTexto.textContent = (REMARCAR ? 'Remarcar' : 'Continuar') + (n > 1 ? ' (' + n + ' lugares)' : '');
    btn.disabled = n === 0;
  }

  // Captura o clique antes de qualquer outro script da página (app.js) tratar o lugar como escolha única.
  mapa.addEventListener('click', function (ev) {
    var el = ev.target.closest ? ev.target.closest('.lugar') : null;
    if (!el || !mapa.contains(el)) return;
    ev.stopPropagation();
    ev.preventDefault();
    if (el.disabled) return;
    aviso.style.display = 'none';

    var pos = -1;
    escolhidos.forEach(function (l, i) { if (l.el === el) pos = i; });
    if (pos >= 0) {                       // já escolhido: retira
      escolhidos.splice(pos, 1);
      pintar(el, false);
    } else if (MAX === 1) {               // remarcação: troca de lugar
      escolhidos.forEach(function (l) { pintar(l.el, false); });
      escolhidos = [{id: el.getAttribute('data-id'), num: el.getAttribute('data-num'), el: el}];
      pintar(el, true);
    } else if (escolhidos.length >= MAX) {
      aviso.style.display = '';
      return;
    } else {
      escolhidos.push({id: el.getAttribute('data-id'), num: el.getAttribute('data-num'), el: el});
      pintar(el, true);
    }
    actualizar();
  }, true);

  actualizar();
})();
</script>
<?php app_fechar(); ?>