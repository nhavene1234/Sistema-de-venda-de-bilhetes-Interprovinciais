<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();
libertar_reservas_expiradas($pdo);

$grupo = $_GET['grupo'] ?? '';
$linhas = carregar_venda($pdo, $grupo, (int)$eu['id_utilizador'], 'pendente');
if (!$linhas) {
    flash('erro', 'O bloqueio dos lugares expirou. Comece a venda novamente.');
    redirecionar('bilheteiro/index.php');
}
$v = $linhas[0];
$lugares = array_column($linhas, 'lugar');
$total = (float)$v['preco'] * count($linhas);
$restante = max(0, min(array_map(fn($l) => strtotime($l['data_expiracao']), $linhas)) - time());

$f = $_SESSION['venda_form'] ?? [];
unset($_SESSION['venda_form']);
$nome = $f['nome'] ?? ''; $tipoDoc = $f['tipo_documento'] ?? 'BI'; $doc = $f['documento'] ?? '';
$tel = $f['telefone'] ?? ''; $metodo = $f['metodo'] ?? 'numerario';

$tituloPagina = 'Dados e pagamento';
$migalhas = ['Bilheteira', 'Vender Bilhete'];
$paginaActiva = 'bilheteiro/index.php';
require __DIR__ . '/../includes/painel_inicio.php';
echo passos_venda(3);
?>
<div class="aviso-reserva-b nao-imprimir">⏱ Lugares reservados por <b class="mono" data-contagem="<?= $restante ?>" data-ao-terminar="recarregar"></b></div>
<form method="post" action="<?= e(url('bilheteiro/vender_registar.php')) ?>" class="duas-colunas" id="form-venda">
  <?= csrf_campo() ?><input type="hidden" name="grupo" value="<?= e($grupo) ?>">
  <section class="cartao">
    <h2 style="margin-bottom:16px">Dados do passageiro e pagamento</h2>
    <div class="campo"><label for="nome">Nome completo</label>
      <input type="text" id="nome" name="nome" value="<?= e($nome) ?>" required autofocus></div>
    <div class="linha-2">
      <div class="campo"><label for="tipo_documento">Tipo de documento</label>
        <select id="tipo_documento" name="tipo_documento">
          <?php foreach (['BI' => 'BI (Bilhete de Identidade)', 'Passaporte' => 'Passaporte', 'Carta de Condução' => 'Carta de Condução', 'DIRE' => 'DIRE'] as $k => $r): ?>
            <option value="<?= e($k) ?>" <?= $tipoDoc === $k ? 'selected' : '' ?>><?= e($r) ?></option>
          <?php endforeach; ?></select></div>
      <div class="campo"><label for="documento">N.º do documento</label>
        <input type="text" id="documento" name="documento" value="<?= e($doc) ?>" maxlength="20" required></div>
    </div>
    <div class="campo"><label for="telefone">Telefone</label>
      <div class="campo-prefixo"><span>+258</span>
        <input type="tel" id="telefone" name="telefone" value="<?= e($tel) ?>" inputmode="numeric" maxlength="9" placeholder="841234567" required></div>
      <p class="pequeno" id="dica-telefone" style="margin:6px 0 0">Digite os 9 dígitos (82, 83, 84, 85, 86 ou 87 + 7 dígitos).</p>
      <?php if (AMBIENTE_DEMO): ?><p class="pequeno" style="margin:6px 0 0">Demonstração: M-Pesa ou e-Mola com número a terminar em <b>00</b> simula pagamento recusado.</p><?php endif; ?></div>

    <div class="campo"><label>Método de pagamento</label>
      <div class="metodos">
        <?php foreach (['numerario' => 'Numerário', 'mpesa' => 'M-Pesa', 'emola' => 'e-Mola', 'cartao' => 'Cartão'] as $k => $r): ?>
          <label class="metodo <?= $metodo === $k ? 'escolhido' : '' ?>"><input type="radio" name="metodo" value="<?= $k ?>" <?= $metodo === $k ? 'checked' : '' ?>><?= e($r) ?></label>
        <?php endforeach; ?>
      </div></div>
  </section>

  <aside class="cartao resumo-card">
    <small class="titulo-mini">CONFIRMAÇÃO FINAL</small>
    <h2><?= e($v['origem']) ?> → <?= e($v['destino']) ?></h2>
    <p class="pequeno" style="margin:0 0 14px"><?= e($v['transportadora']) ?> • Partida às <?= e(hora_curta($v['hora_partida'])) ?> (<?= e(data_curta($v['data'])) ?>)</p>
    <div class="linha-valor"><span>Passageiro</span><b id="res-nome">—</b></div>
    <div class="linha-valor"><span>Lugares seleccionados</span><b><?= e(implode(', ', $lugares)) ?> (<?= e(texto_bilhetes(count($lugares))) ?>)</b></div>
    <div class="linha-valor"><span>Método</span><b class="verde" id="res-metodo">—</b></div>
    <div class="linha-valor" style="margin-top:12px"><span>Valor total</span><b class="total-grande"><?= e(formatar_mt($total)) ?></b></div>
    <button class="btn btn-primario btn-bloco" type="submit" style="margin-top:auto">Registar venda</button>
    <a class="link-centro" style="font-size:12px;margin-top:10px" href="<?= e(url('bilheteiro/index.php')) ?>">Cancelar venda</a>
  </aside>
</form>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
