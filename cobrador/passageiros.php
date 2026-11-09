<?php
require_once __DIR__ . '/_base.php';
$c = cb_ctx($pdo, $u);
$v = $c['viagem'];
$lista = [];
if ($v) {
    $st = $pdo->prepare("SELECT COALESCE(rs.nome_passageiro, us.nome) AS nome, rs.referencia, a.numero AS assento, rs.embarcado_em
                         FROM reserva rs JOIN assento a ON a.id_assento = rs.id_assento LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                         WHERE rs.id_viagem = ? AND rs.estado = 'confirmada' ORDER BY a.numero");
    $st->execute([$v['id_viagem']]);
    $lista = $st->fetchAll();
}
$pct = $c['total'] ? (int)round($c['emb'] * 100 / $c['total']) : 0;
$iniciais = function (string $n): string {
    $p = preg_split('/\s+/', trim($n)); $r = mb_substr($p[0], 0, 1);
    if (count($p) > 1) $r .= mb_substr(end($p), 0, 1);
    return mb_strtoupper($r);
};

cb_abrir('Passageiros');
?>
<header class="topo simples">
  <div class="topo-linha"><div><h1>Passageiros</h1><p><?= $v ? e($v['origem'] . ' → ' . $v['destino'] . ' · ' . substr($v['hora_partida'], 0, 5)) : 'Sem viagem hoje' ?></p></div>
    <button class="topo-btn" type="button" aria-label="Mais opções"><?= cb_ic('dots') ?></button></div>
</header>

<label class="pesq-rot" for="q">Pesquisar passageiro</label>
<div class="pesq"><?= cb_ic('search', 20) ?><input id="q" type="search" placeholder="Nome, assento ou código" autocomplete="off"></div>
<div class="tabs" id="tabs">
  <button type="button" class="activo" data-f="todos">Todos <?= $c['total'] ?></button>
  <button type="button" data-f="emb">Embarcados <?= $c['emb'] ?></button>
  <button type="button" data-f="falta">Em falta <?= $c['falta'] ?></button>
</div>
<div class="lista-cab"><h2><?= $c['total'] ?> passageiros</h2><span class="etq verde"><?= $pct ?>% embarcados</span></div>
<div class="lista" id="lista">
<?php foreach ($lista as $p): $emb = !empty($p['embarcado_em']); ?>
  <div class="pax" data-e="<?= $emb ? 'emb' : 'falta' ?>" data-t="<?= e(mb_strtolower($p['nome'] . ' ' . $p['assento'] . ' ' . $p['referencia'])) ?>">
    <div class="ini"><?= e($iniciais($p['nome'])) ?></div>
    <div class="nome"><b><?= e($p['nome']) ?></b><small class="mono">Assento <?= e($p['assento']) ?></small></div>
    <?php if ($emb): ?><span class="etq verde"><?= cb_ic('check', 15) ?> Embarcado</span>
    <?php else: ?><span class="etq"><?= cb_ic('clock', 15) ?> Em falta</span><?php endif; ?>
  </div>
<?php endforeach; ?>
<?php if (!$lista): ?><div class="vazio" style="margin:10px 0;box-shadow:none">Sem passageiros confirmados.</div><?php endif; ?>
</div>
<script>
(function(){
  var f='todos', q=document.getElementById('q');
  function aplicar(){
    var t=q.value.trim().toLowerCase();
    document.querySelectorAll('#lista .pax').forEach(function(l){
      l.style.display=((f==='todos'||l.dataset.e===f)&&l.dataset.t.indexOf(t)>-1)?'':'none';
    });
  }
  q.addEventListener('input',aplicar);
  document.getElementById('tabs').addEventListener('click',function(e){
    var b=e.target.closest('button'); if(!b) return;
    this.querySelectorAll('button').forEach(function(x){x.classList.remove('activo')});
    b.classList.add('activo'); f=b.dataset.f; aplicar();
  });
})();
</script>
<?php cb_fechar('passageiros'); cb_fim(); ?>
