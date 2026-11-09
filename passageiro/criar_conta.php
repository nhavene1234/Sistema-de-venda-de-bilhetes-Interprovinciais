<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
if ($u = utilizador_logado()) redirecionar(pagina_inicial_perfil($u['perfil']));

$erro = null;
$v = ['nome' => '', 'telefone' => '', 'email' => '', 'documento' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v['nome'] = trim($_POST['nome'] ?? '');
    $v['telefone'] = trim($_POST['telefone'] ?? '');
    $v['email'] = trim($_POST['email'] ?? '');
    $v['documento'] = strtoupper(trim($_POST['documento'] ?? ''));
    $senha = $_POST['senha'] ?? '';
    $conf = $_POST['confirmar'] ?? '';

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif (mb_strlen($v['nome']) < 3) {
        $erro = 'Indique o seu nome completo.';
    } elseif (($nove = telefone_normalizar($v['telefone'])) === null) {
        $erro = MSG_TELEFONE;
    } elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $erro = 'Indique um email válido.';
    } elseif (!preg_match('/^[A-Z0-9]{5,20}$/', $v['documento'])) {
        $erro = 'O número de documento deve ter entre 5 e 20 letras ou números.';
    } elseif ($falha = validar_nova_senha($senha)) {
        $erro = $falha;
    } elseif ($senha !== $conf) {
        $erro = 'As palavras-passe não coincidem.';
    } elseif (empty($_POST['termos'])) {
        $erro = 'Tem de aceitar os termos de utilização e a política de privacidade.';
    } else {
        $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ? OR documento = ?');
        $st->execute([$v['email'], $v['documento']]);
        if ($st->fetchColumn() > 0) {
            $erro = 'Já existe uma conta com este email ou número de documento.';
        } else {
            $pdo->prepare('INSERT INTO utilizador (nome, email, senha, telefone, documento, perfil) VALUES (?,?,?,?,?,\'passageiro\')')
                ->execute([$v['nome'], $v['email'], password_hash($senha, PASSWORD_DEFAULT), telefone_guardar($nove), $v['documento']]);
            $id = (int)$pdo->lastInsertId();
            registar_log($pdo, $id, 'Conta de passageiro criada', 'utilizador', $id);
            $_SESSION['conta_criada'] = primeiro_nome($v['nome']);
            redirecionar('passageiro/conta_criada.php');
        }
    }
}

app_abrir(['titulo' => 'Criar conta', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="auth-app">
  <div class="auth-app-topo">
    <a class="btn-icone" style="background:#eef2f8;color:var(--texto)" href="<?= e(url('passageiro/entrar.php')) ?>" aria-label="Voltar"><?= ic('voltar') ?></a>
    <span class="selo"><?= ic('escudo-ok') ?> Seguro</span>
  </div>
  <h1>Criar conta</h1>
  <p class="sub">Preencha os dados para começar a viajar.</p>

  <?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>

  <form method="post" autocomplete="on">
    <?= csrf_campo() ?>
    <div class="campo"><label for="nome">Nome completo</label>
      <div class="campo-ic"><?= ic('utilizador') ?><input type="text" id="nome" name="nome" value="<?= e($v['nome']) ?>" required></div></div>
    <div class="dois">
      <div class="campo"><label for="telefone">Telefone</label>
        <div class="campo-ic com-prefixo"><span class="prefixo">+258</span>
          <input type="tel" id="telefone" name="telefone" data-contacto value="<?= e($v['telefone']) ?>" inputmode="numeric" maxlength="12" placeholder="84 123 4567" required></div></div>
      <div class="campo"><label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($v['email']) ?>" placeholder="nome@email.co.mz" required></div>
    </div>
    <div class="campo"><label for="documento">Número de documento</label>
      <div class="campo-ic"><?= ic('documento') ?><input type="text" id="documento" name="documento" value="<?= e($v['documento']) ?>" maxlength="20" placeholder="BI ou passaporte" required></div></div>
    <div class="dois">
      <div class="campo"><label for="senha">Palavra-passe</label>
        <div class="campo-ic"><input type="password" id="senha" name="senha" style="padding-left:12px" minlength="8" required>
          <button type="button" class="olho" data-alternar-senha="senha" aria-label="Mostrar"><?= ic('olho') ?></button></div></div>
      <div class="campo"><label for="confirmar">Confirmar</label>
        <div class="campo-ic"><input type="password" id="confirmar" name="confirmar" style="padding-left:12px" minlength="8" required>
          <button type="button" class="olho" data-alternar-senha="confirmar" aria-label="Mostrar"><?= ic('olho') ?></button></div></div>
    </div>
    <p class="pequeno" style="margin:-6px 0 12px">Mínimo de 8 caracteres, com letras e números.</p>
    <label class="termos"><input type="checkbox" name="termos" value="1" <?= !empty($_POST['termos']) ? 'checked' : '' ?>>
      <span>Aceito os termos de utilização e a política de privacidade.</span></label>
    <button class="btn-app" type="submit"><?= ic('mais-user') ?> Criar conta</button>
  </form>
</div>
<?php app_fechar(); ?>
