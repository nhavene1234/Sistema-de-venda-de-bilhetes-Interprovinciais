<?php
require_once __DIR__ . '/_base.php';
$id = (int)$u['id_utilizador'];
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
        redirecionar('cobrador/perfil.php');
    }
}

cb_abrir('Alterar palavra-passe');
?>
<header class="topo simples">
  <div class="topo-linha"><div><h1>Palavra-passe</h1><p>Mantenha a sua conta segura</p></div>
    <a class="topo-btn" href="<?= e(url('cobrador/perfil.php')) ?>" aria-label="Voltar"><?= cb_ic('x', 20) ?></a></div>
</header>

<?php if ($erro): ?><div class="aviso-cb erro"><?= e($erro) ?></div><?php endif; ?>
<form method="post" class="form-cb">
  <?= csrf_campo() ?>
  <?php foreach ([['senha_actual', 'Palavra-passe actual', 'current-password'], ['nova_senha', 'Nova palavra-passe', 'new-password'], ['confirmar_senha', 'Confirmar nova palavra-passe', 'new-password']] as [$nome, $rotulo, $auto]): ?>
    <label for="<?= $nome ?>"><?= $rotulo ?></label>
    <div class="campo-s"><input type="password" id="<?= $nome ?>" name="<?= $nome ?>" autocomplete="<?= $auto ?>" required>
      <button type="button" data-ver="<?= $nome ?>">Mostrar</button></div>
  <?php endforeach; ?>
  <p class="dica">Mínimo de 8 caracteres, com letras e números.</p>
  <button class="btn-validar" type="submit" style="width:100%;border:0;font-family:inherit"><?= cb_ic('lock', 20) ?> Actualizar palavra-passe</button>
</form>
<script>
document.querySelectorAll('[data-ver]').forEach(function (b) {
  b.addEventListener('click', function () {
    var i = document.getElementById(b.dataset.ver), ver = i.type === 'password';
    i.type = ver ? 'text' : 'password'; b.textContent = ver ? 'Ocultar' : 'Mostrar';
  });
});
</script>
<?php cb_fechar('perfil'); cb_fim(); ?>
