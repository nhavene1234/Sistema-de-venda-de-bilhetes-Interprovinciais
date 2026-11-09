<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
libertar_reservas_expiradas($pdo);

// Aceita ?reservas=12,13,14 (compra com vários lugares) ou ?reserva=12 (um só lugar).
$bruto = (string)($_GET['reservas'] ?? ($_GET['reserva'] ?? ''));
$ids = lista_ids($bruto);
if (!$ids) redirecionar('passageiro/index.php');

// cada reserva tem de pertencer a este passageiro
$reservas = [];
foreach ($ids as $id) {
    $r = carregar_reserva($pdo, $id, (int)$u['id_utilizador']);
    if (!$r) redirecionar('passageiro/index.php');
    $reservas[] = $r;
}
$primeira = $reservas[0];
$idViagem = (int)$primeira['id_viagem'];

// todas têm de ser da mesma viagem
foreach ($reservas as $r) {
    if ((int)$r['id_viagem'] !== $idViagem) redirecionar('passageiro/index.php');
}

// já pagas: vai para os bilhetes
$confirmadas = array_filter($reservas, fn($r) => $r['estado'] === 'confirmada');
if ($confirmadas) {
    if (count($reservas) === 1) redirecionar('passageiro/bilhete.php?reserva=' . $primeira['id_reserva']);
    redirecionar('passageiro/bilhetes.php');
}
// se algum bloqueio expirou, é preciso escolher os lugares de novo
foreach ($reservas as $r) {
    if ($r['estado'] !== 'pendente') {
        flash('erro', 'O bloqueio dos lugares expirou. Escolha os lugares novamente.');
        redirecionar('passageiro/lugares.php?viagem=' . $idViagem);
    }
}

$n = count($reservas);
$listaIds = implode(',', array_map(fn($r) => (int)$r['id_reserva'], $reservas));
$total = array_sum(array_map(fn($r) => (float)$r['preco'], $reservas));
$lugares = array_map(fn($r) => $r['lugar'], $reservas);
$textoBilhetes = $n . ' bilhete' . ($n === 1 ? '' : 's');

// o bloqueio acaba quando expira o primeiro lugar do grupo
$restante = max(0, min(array_map(fn($r) => strtotime($r['data_expiracao']), $reservas)) - time());

$st = $pdo->prepare('SELECT telefone FROM utilizador WHERE id_utilizador = ?');
$st->execute([$u['id_utilizador']]);
$tel = telefone_digitos($st->fetchColumn());
$metodoActual = $primeira['metodo'] ?? 'mpesa';

app_abrir(['titulo' => 'Resumo e pagamento', 'voltar' => 'passageiro/lugares.php?viagem=' . $idViagem,
           'h1' => 'Resumo e pagamento', 'sub' => 'Confirme antes de pagar']);
?>
<div class="aviso-reserva"><span><?= ic('relogio') ?> Lugar<?= $n === 1 ? '' : 'es' ?> reservado<?= $n === 1 ? '' : 's' ?> por</span>
  <span class="mono" data-contagem="<?= $restante ?>" data-ao-terminar="<?= e(url('passageiro/pagamento.php?reservas=' . $listaIds)) ?>"></span></div>

<div class="cartao-app">
  <div class="resumo-viagem">
    <div><h3><?= e($primeira['origem']) ?> → <?= e($primeira['destino']) ?></h3>
      <small><?= e($primeira['transportadora']) ?> · <?= e(data_curta($primeira['data'])) ?></small></div>
    <div style="text-align:right">
      <?php foreach ($lugares as $l): ?><span class="badge-lugar" style="margin-left:4px"><?= e($l) ?></span><?php endforeach; ?>
      <div style="font-weight:800;margin-top:6px"><?= e(hora_curta($primeira['hora_partida'])) ?></div></div>
  </div>
  <?php if ($n > 1): ?>
    <p class="pequeno" style="margin:12px 0 0;padding-top:10px;border-top:1px solid #e6ebf3">
      <?= e($textoBilhetes) ?> × <?= e(formatar_mt($primeira['preco'])) ?> — lugares <b><?= e(implode(', ', $lugares)) ?></b></p>
  <?php endif; ?>
</div>

<div class="titulo-sec"><h2>Como quer pagar?</h2></div>
<form method="post" action="<?= e(url('passageiro/processar.php')) ?>">
  <?= csrf_campo() ?>
  <input type="hidden" name="reserva" value="<?= (int)$primeira['id_reserva'] ?>">
  <input type="hidden" name="reservas" value="<?= e($listaIds) ?>">
  <?php foreach ([['mpesa', 'M-Pesa', 'Pagamento móvel Vodacom', 'telefone'], ['emola', 'e-Mola', 'Pagamento móvel Movitel', 'cartao'],
                  ['cartao', 'Cartão', 'Visa ou Mastercard', 'cartao']] as [$cod, $nome, $desc, $icone]): ?>
    <label class="opcao-pag">
      <input type="radio" name="metodo" value="<?= $cod ?>" <?= $metodoActual === $cod ? 'checked' : '' ?>>
      <span class="icone-pag"><?= ic($icone) ?></span>
      <span class="txt"><b><?= $nome ?></b><small><?= $desc ?></small></span><span class="marca"></span>
    </label>
  <?php endforeach; ?>

  <div class="campo" id="campo-telefone" style="margin-top:14px">
    <label for="telefone">Telefone para pagamento</label>
    <div class="campo-ic com-prefixo"><span class="prefixo">+258</span>
      <input type="tel" id="telefone" name="telefone" value="<?= e($tel) ?>" inputmode="numeric" maxlength="9" placeholder="841234567" autocomplete="tel-national"></div>
    <p class="pequeno" id="dica-telefone" style="margin:6px 0 0">Digite os 9 dígitos do número.</p>
    <?php if (AMBIENTE_DEMO): ?><p class="pequeno" style="margin:6px 0 0">Demonstração: um número a terminar em <b>00</b> simula um pagamento recusado.</p><?php endif; ?>
  </div>

  <div class="total-linha"><span>Total a pagar<?= $n > 1 ? ' (' . e($textoBilhetes) . ')' : '' ?></span><b><?= e(formatar_mt($total)) ?></b></div>
  <button class="btn-app" type="submit"><?= ic('cadeado') ?> Pagar <?= e(formatar_mt($total)) ?></button>
</form>
<?php app_fechar(); ?>