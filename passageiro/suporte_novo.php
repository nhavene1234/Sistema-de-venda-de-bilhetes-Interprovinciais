<?php
// passageiro/suporte_novo.php - novo pedido de ajuda (com sessão iniciada)
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/suporte.php';
$u = exigir_passageiro();
suporte_garantir_tabelas($pdo);
$id = (int)$u['id_utilizador'];

$st = $pdo->prepare('SELECT nome, email, telefone FROM utilizador WHERE id_utilizador = ?');
$st->execute([$id]);
$conta = $st->fetch();
$cat = $_REQUEST['cat'] ?? '';
if (!isset(SP_CATEGORIAS[$cat])) redirecionar('passageiro/suporte.php');
$reservas = sp_reservas_do_passageiro($pdo, $id);
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = sp_texto_valido($_POST['texto'] ?? '');
    $idRes = (int)($_POST['id_reserva'] ?? 0);
    $contacto = trim($_POST['contacto'] ?? '') ?: (string)($conta['telefone'] ?: $conta['email']);
    $minhas = array_column($reservas, 'id_reserva');
    $st = $pdo->prepare("SELECT COUNT(*) FROM suporte_pedido WHERE id_passageiro = ? AND estado <> 'resolvido'");
    $st->execute([$id]);
    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif (!$texto) {
        $erro = 'Descreva o problema (mínimo 5 caracteres).';
    } elseif ((int)$st->fetchColumn() >= 5) {
        $erro = 'Já tem vários pedidos em aberto. Aguarde pela resposta do suporte ou acrescente informação num deles.';
    } else {
        $idP = sp_criar_pedido($pdo, $id, $conta['nome'], mb_substr($contacto, 0, 120), $cat,
                               in_array($idRes, array_map('intval', $minhas), true) ? $idRes : null, $texto);
        registar_log($pdo, $id, 'Pedido de ajuda criado', 'suporte_pedido', $idP, SP_CATEGORIAS[$cat][0]);
        flash('ok', 'Pedido enviado. O suporte vai responder aqui.');
        redirecionar('passageiro/suporte_ver.php?id=' . $idP);
    }
}

app_abrir(['titulo' => 'Novo pedido', 'voltar' => 'passageiro/suporte.php', 'h1' => SP_CATEGORIAS[$cat][0], 'sub' => SP_CATEGORIAS[$cat][1],
           'head' => '<link rel="stylesheet" href="' . e(url('assets/css/suporte.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/suporte.css')) . '">']);
?>
<?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>
<form method="post" class="cartao-app sp-form">
  <?= csrf_campo() ?><input type="hidden" name="cat" value="<?= e($cat) ?>">
  <?php if (SP_CATEGORIAS[$cat][2]): ?><p class="sp-dica"><?= e(SP_CATEGORIAS[$cat][2]) ?></p><?php endif; ?>
  <?php if ($reservas && in_array($cat, ['pagamento', 'bilhete', 'reserva'], true)): ?>
    <div class="campo"><label for="id_reserva">Reserva em causa (opcional)</label>
      <select id="id_reserva" name="id_reserva"><option value="0">— Nenhuma em especial —</option>
        <?php foreach ($reservas as $r): ?><option value="<?= (int)$r['id_reserva'] ?>"><?= e($r['referencia'] . ' · ' . $r['origem'] . ' → ' . $r['destino'] . ' · ' . date('d/m', strtotime($r['data']))) ?></option><?php endforeach; ?>
      </select></div>
  <?php endif; ?>
  <div class="campo"><label for="texto">O que se passa?</label>
    <textarea id="texto" name="texto" rows="5" maxlength="2000" required placeholder="Explique com o máximo de detalhe possível..."><?= e($_POST['texto'] ?? '') ?></textarea></div>
  <div class="campo"><label for="contacto">Contacto para lhe responder</label>
    <input id="contacto" name="contacto" value="<?= e($_POST['contacto'] ?? ($conta['telefone'] ?: $conta['email'])) ?>" maxlength="120"></div>
  <button class="btn-app" type="submit">Enviar pedido</button>
</form>
<?php app_fechar(['nav' => 'perfil']); ?>
