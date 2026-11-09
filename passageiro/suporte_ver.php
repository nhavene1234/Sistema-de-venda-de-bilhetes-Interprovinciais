<?php
// passageiro/suporte_ver.php - conversa com o suporte
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/suporte.php';
$u = exigir_passageiro();
suporte_garantir_tabelas($pdo);
$id = (int)($_GET['id'] ?? $_POST['id_pedido'] ?? 0);

$st = $pdo->prepare('SELECT * FROM suporte_pedido WHERE id_pedido = ? AND id_passageiro = ?');
$st->execute([$id, $u['id_utilizador']]);
$ped = $st->fetch();
if (!$ped) { flash('erro', 'Pedido não encontrado.'); redirecionar('passageiro/suporte.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = sp_texto_valido($_POST['texto'] ?? '');
    if (!csrf_valido()) {
        flash('erro', 'Pedido inválido. Tente novamente.');
    } elseif (!$texto) {
        flash('erro', 'Escreva a mensagem (mínimo 5 caracteres).');
    } else {
        sp_add_mensagem($pdo, $id, 'passageiro', (int)$u['id_utilizador'], $texto);
        $pdo->prepare("UPDATE suporte_pedido SET estado = 'aberto' WHERE id_pedido = ? AND estado = 'resolvido'")->execute([$id]);
        flash('ok', 'Mensagem enviada.');
    }
    redirecionar('passageiro/suporte_ver.php?id=' . $id);
}

app_abrir(['titulo' => 'Pedido #' . $id, 'voltar' => 'passageiro/suporte.php', 'h1' => $ped['assunto'],
           'sub' => 'Pedido #' . $id . ' · ' . SP_ESTADOS[$ped['estado']],
           'head' => '<link rel="stylesheet" href="' . e(url('assets/css/suporte.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/suporte.css')) . '">']);
?>
<div class="cartao-app">
  <?php sp_conversa($pdo, $id, 'passageiro'); ?>
  <?php if ($ped['estado'] === 'resolvido'): ?><p class="sp-dica">Este pedido foi marcado como resolvido. Se o problema continua, escreva abaixo para o reabrir.</p><?php endif; ?>
  <form method="post" class="sp-form">
    <?= csrf_campo() ?><input type="hidden" name="id_pedido" value="<?= $id ?>">
    <div class="campo"><label for="texto"><?= $ped['estado'] === 'resolvido' ? 'Reabrir com uma mensagem' : 'Acrescentar mensagem' ?></label>
      <textarea id="texto" name="texto" rows="3" maxlength="2000" required></textarea></div>
    <button class="btn-app" type="submit">Enviar</button>
  </form>
</div>
<?php app_fechar(['nav' => 'perfil']); ?>
