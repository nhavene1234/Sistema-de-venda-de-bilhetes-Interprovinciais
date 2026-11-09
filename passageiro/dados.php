<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$sessao = exigir_passageiro();
$id = (int)$sessao['id_utilizador'];

$st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
$st->execute([$id]);
$u = $st->fetch();
$erro = null;
$v = ['nome' => $u['nome'], 'telefone' => telefone_digitos($u['telefone']), 'email' => $u['email'], 'documento' => $u['documento'] ?? ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v['nome'] = trim($_POST['nome'] ?? '');
    $v['telefone'] = trim($_POST['telefone'] ?? '');
    $v['email'] = trim($_POST['email'] ?? '');
    $v['documento'] = strtoupper(trim($_POST['documento'] ?? ''));

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Tente novamente.';
    } elseif (mb_strlen($v['nome']) < 3) {
        $erro = 'Indique o seu nome completo.';
    } elseif (($nove = telefone_normalizar($v['telefone'])) === null) {
        $erro = MSG_TELEFONE;
    } elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $erro = 'Indique um email válido.';
    } elseif (!preg_match('/^[A-Z0-9]{5,20}$/', $v['documento'])) {
        $erro = 'O número de documento deve ter entre 5 e 20 letras ou números.';
    } else {
        $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE (email = ? OR documento = ?) AND id_utilizador <> ?');
        $st->execute([$v['email'], $v['documento'], $id]);
        if ($st->fetchColumn() > 0) {
            $erro = 'Já existe outra conta com este email ou número de documento.';
        } else {
            $pdo->prepare('UPDATE utilizador SET nome = ?, email = ?, telefone = ?, documento = ? WHERE id_utilizador = ?')
                ->execute([$v['nome'], $v['email'], telefone_guardar($nove), $v['documento'], $id]);
            $_SESSION['utilizador']['nome'] = $v['nome'];
            $_SESSION['utilizador']['email'] = $v['email'];
            registar_log($pdo, $id, 'Dados pessoais actualizados', 'utilizador', $id);
            flash('ok', 'Dados guardados com sucesso.');
            redirecionar('passageiro/perfil.php');
        }
    }
}

app_abrir(['titulo' => 'Dados pessoais', 'voltar' => 'passageiro/perfil.php', 'h1' => 'Dados pessoais', 'sub' => 'Nome, telefone e documento']);
?>
<?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>
<form method="post" class="cartao-app auth-app">
  <?= csrf_campo() ?>
  <div class="campo"><label for="nome">Nome completo</label>
    <div class="campo-ic"><?= ic('utilizador') ?><input type="text" id="nome" name="nome" value="<?= e($v['nome']) ?>" required></div></div>
  <div class="campo"><label for="telefone">Telefone</label>
    <div class="campo-ic com-prefixo"><span class="prefixo">+258</span>
      <input type="tel" id="telefone" name="telefone" data-contacto value="<?= e($v['telefone']) ?>" inputmode="numeric" maxlength="12" required></div></div>
  <div class="campo"><label for="email">Email</label>
    <div class="campo-ic"><?= ic('mail') ?><input type="email" id="email" name="email" value="<?= e($v['email']) ?>" required></div></div>
  <div class="campo"><label for="documento">Número de documento</label>
    <div class="campo-ic"><?= ic('documento') ?><input type="text" id="documento" name="documento" value="<?= e($v['documento']) ?>" maxlength="20" required></div></div>
  <button class="btn-app" type="submit"><?= ic('check') ?> Guardar alterações</button>
</form>
<?php app_fechar(['nav' => 'perfil']); ?>
