<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();
libertar_reservas_expiradas($pdo);
$transp = transportadora_do_bilheteiro($pdo);

$idV = (int)($_GET['viagem'] ?? 0);
$st = $pdo->prepare('SELECT v.*, ro.origem, ro.destino, ro.id_transportadora, t.nome AS transportadora
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                     JOIN transportadora t ON t.id_transportadora = ro.id_transportadora WHERE v.id_viagem = ?');
$st->execute([$idV]);
$v = $st->fetch();
if (!$v || (int)$v['id_transportadora'] !== $transp['id'] || $v['estado'] !== 'agendada' || partida_ts($v) <= time()) {
    flash('erro', motivo_indisponivel($v ?: null));
    redirecionar('bilheteiro/index.php');
}
[$filas, $ocupados] = carregar_mapa($pdo, $v, (int)$eu['id_utilizador']);

$tituloPagina = 'Escolher lugar';
$migalhas = ['Bilheteira', 'Vender Bilhete'];
$paginaActiva = 'bilheteiro/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
echo passos_venda(2);
?>
<form method="post" action="<?= e(url('bilheteiro/vender_bloquear.php')) ?>" class="duas-colunas" id="form-lugares" data-preco="<?= (float)$v['preco'] ?>">
  <?= csrf_campo() ?><input type="hidden" name="viagem" value="<?= $idV ?>">
  <section class="cartao" style="text-align:center">
    <h2 style="font-size:18px">Seleccione os lugares no mapa</h2>
    <p class="chip-info" id="chip-selecao"><i></i><span>Escolha até 4 lugares</span></p>
    <?= mapa_autocarro_html($filas, $ocupados, true) ?>
  </section>

  <aside class="cartao resumo-card">
    <small class="titulo-mini">RESUMO DA VIAGEM</small>
    <h2><?= e($v['origem']) ?> → <?= e($v['destino']) ?></h2>
    <ul class="detalhes">
      <li><?= icone_svg('rota') ?> <?= e($v['transportadora']) ?> • <?= e($v['classe']) ?></li>
      <li><?= icone_svg('documento') ?> <?= e(hora_curta($v['hora_partida'])) ?> (<?= $v['data'] === date('Y-m-d') ? 'Hoje' : e(data_curta($v['data'])) ?>)</li>
      <li><?= icone_svg('bilhete') ?> Lugares seleccionados: <b id="lista-lugares">—</b></li>
    </ul>
    <div class="linha-valor"><span>Preço unitário</span><b><?= e(formatar_mt($v['preco'])) ?></b></div>
    <div class="linha-valor"><span id="rotulo-total">Total (0 bilhetes)</span><b class="total-azul" id="valor-total">0 MT</b></div>
    <div id="campos-lugares"></div>
    <button class="btn btn-primario btn-bloco" type="submit" id="btn-passo3" disabled style="margin-top:auto">Continuar para passo 3</button>
    <a class="link-centro" style="font-size:12px;margin-top:10px" href="<?= e(url('bilheteiro/index.php')) ?>">← Voltar às viagens</a>
  </aside>
</form>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
