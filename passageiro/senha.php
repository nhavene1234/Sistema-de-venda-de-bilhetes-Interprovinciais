<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$sessao = exigir_passageiro();
$id = (int)$sessao['id_utilizador'];
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['senha_actual'] ?? '';
    $nova = $_POST['nova_senha'] ?? '';
    $conf = $_POST['confirmar_senha'] ?? '';
    $st = $pdo->prepare('SELECT senha FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$id]);

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Tente novamente.';
    } elseif (!password_verify($actual, (string)$st->fetchColumn())) {
        $erro = 'A palavra-passe actual está incorrecta.';
    } elseif ($f = validar_nova_senha($nova)) {
        $erro = $f;
    } elseif ($nova !== $conf) {
        $erro = 'As palavras-passe não coincidem.';
    } else {
        $pdo->prepare('UPDATE utilizador SET senha = ? WHERE id_utilizador = ?')->execute([password_hash($nova, PASSWORD_DEFAULT), $id]);
        registar_log($pdo, $id, 'Palavra-passe alterada', 'utilizador', $id);
        flash('ok', 'Palavra-passe actualizada com sucesso.');
        redirecionar('passageiro/perfil.php');
    }
}

app_abrir(['titulo' => 'Alterar palavra-passe', 'voltar' => 'passageiro/perfil.php', 'h1' => 'Alterar palavra-passe', 'sub' => 'Mantenha a sua conta segura']);
?>
<?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>
<form method="post" class="cartao-app auth-app">
  <?= csrf_campo() ?>
  <?php foreach ([['senha_actual', 'Palavra-passe actual'], ['nova_senha', 'Nova palavra-passe'], ['confirmar_senha', 'Confirmar nova palavra-passe']] as [$nome, $rotulo]): ?>
    <div class="campo"><label for="<?= $nome ?>"><?= $rotulo ?></label>
      <div class="campo-ic"><?= ic('cadeado') ?><input type="password" id="<?= $nome ?>" name="<?= $nome ?>" required>
        <button type="button" class="olho" data-alternar-senha="<?= $nome ?>" aria-label="Mostrar"><?= ic('olho') ?></button></div></div>
  <?php endforeach; ?>
  <p class="pequeno" style="margin:-4px 0 14px">Mínimo de 8 caracteres, com letras e números.</p>
  <button class="btn-app" type="submit"><?= ic('cadeado') ?> Actualizar palavra-passe</button>
</form>
<?php app_fechar(['nav' => 'perfil']); ?>
