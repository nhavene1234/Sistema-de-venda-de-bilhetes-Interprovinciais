<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/recuperacao.php';
if ($u = utilizador_logado()) redirecionar(pagina_inicial_perfil($u['perfil']));

if (isset($_GET['reiniciar'])) { unset($_SESSION['recp']); redirecionar('passageiro/recuperar.php'); }

$validade = (int)config_valor($pdo, 'recuperacao_validade_minutos', 10);
$rec = $_SESSION['recp'] ?? ['passo' => 1];
$erro = null; $aviso = null;
$canal = $_POST['canal'] ?? 'email';
$ident = trim($_POST['identificador'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';

    } elseif ($acao === 'enviar') {
        $id = 0; $email = null;
        if ($canal === 'telefone') {
            $nove = telefone_digitos($ident);
            if (!preg_match('/^8[2-7]\d{7}$/', $nove)) { $erro = 'Indique um número de telemóvel válido.'; }
            else {
                $st = $pdo->prepare("SELECT id_utilizador, email FROM utilizador
                                     WHERE REPLACE(REPLACE(telefone,' ',''),'+','') LIKE ? AND activo = 1 LIMIT 1");
                $st->execute(['%' . $nove]);
                if ($l = $st->fetch()) { $id = (int)$l['id_utilizador']; $email = $l['email']; }
                $destino = '+258 ' . substr($nove, 0, 2) . '•••' . substr($nove, -4);
            }
        } else {
            if (!filter_var($ident, FILTER_VALIDATE_EMAIL)) { $erro = 'Indique um email válido.'; }
            else {
                $st = $pdo->prepare('SELECT id_utilizador, email FROM utilizador WHERE email = ? AND activo = 1');
                $st->execute([$ident]);
                if ($l = $st->fetch()) { $id = (int)$l['id_utilizador']; $email = $l['email']; }
                [$antes, $dominio] = explode('@', $ident, 2);
                $destino = mb_substr($antes, 0, 2) . '•••@' . $dominio;
            }
        }
        if (!$erro) {
            $codigoDemo = null;
            if ($id) {
                $codigo = criar_codigo_recuperacao($pdo, $id, $validade);
                if ($email) enviar_codigo_email($email, $codigo);
                registar_log($pdo, $id, 'Recuperação de palavra-passe solicitada', 'utilizador', $id);
                $codigoDemo = AMBIENTE_DEMO ? $codigo : null;
            }
            // avança sempre, para não revelar se a conta existe
            $_SESSION['recp'] = ['passo' => 2, 'id' => $id, 'destino' => $destino, 'enviado_em' => time(),
                                 'expira' => time() + $validade * 60, 'codigo_demo' => $codigoDemo];
            redirecionar('passageiro/recuperar.php');
        }

    } elseif ($acao === 'reenviar' && ($rec['passo'] ?? 1) === 2) {
        $espera = 60 - (time() - (int)$rec['enviado_em']);
        if ($espera > 0) { $aviso = "Aguarde $espera segundos para pedir um novo código."; }
        elseif (!empty($rec['id'])) {
            $codigo = criar_codigo_recuperacao($pdo, (int)$rec['id'], $validade);
            $st = $pdo->prepare('SELECT email FROM utilizador WHERE id_utilizador = ?');
            $st->execute([$rec['id']]);
            if ($mail = $st->fetchColumn()) enviar_codigo_email($mail, $codigo);
            $_SESSION['recp']['enviado_em'] = time();
            $_SESSION['recp']['expira'] = time() + $validade * 60;
            $_SESSION['recp']['codigo_demo'] = AMBIENTE_DEMO ? $codigo : null;
            flash('ok', 'Enviámos um novo código.');
            redirecionar('passageiro/recuperar.php');
        }

    } elseif ($acao === 'actualizar' && ($rec['passo'] ?? 1) === 2) {
        $digitos = implode('', array_map('strval', $_POST['d'] ?? []));
        $nova = $_POST['nova_senha'] ?? '';
        $conf = $_POST['confirmar_senha'] ?? '';
        $linha = null;
        if (preg_match('/^\d{6}$/', $digitos) && !empty($rec['id'])) {
            $st = $pdo->prepare('SELECT * FROM recuperacao_senha WHERE id_utilizador = ? AND usado = 0 ORDER BY id_recuperacao DESC LIMIT 1');
            $st->execute([$rec['id']]);
            $linha = $st->fetch();
        }
        if (!preg_match('/^\d{6}$/', $digitos)) {
            $erro = 'Introduza os 6 dígitos do código.';
        } elseif ($f = validar_nova_senha($nova)) {
            $erro = $f;
        } elseif ($nova !== $conf) {
            $erro = 'As palavras-passe não coincidem.';
        } elseif (!$linha || strtotime($linha['data_expiracao']) < time() || (int)$linha['tentativas'] >= 5) {
            $erro = 'Código inválido ou expirado. Peça um novo código.';
        } elseif (!password_verify($digitos, $linha['codigo_hash'])) {
            $pdo->prepare('UPDATE recuperacao_senha SET tentativas = tentativas + 1 WHERE id_recuperacao = ?')->execute([$linha['id_recuperacao']]);
            $erro = 'Código incorrecto. Tente novamente.';
        } else {
            $pdo->prepare('UPDATE utilizador SET senha = ?, tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
                ->execute([password_hash($nova, PASSWORD_DEFAULT), $rec['id']]);
            $pdo->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE id_recuperacao = ?')->execute([$linha['id_recuperacao']]);
            registar_log($pdo, (int)$rec['id'], 'Palavra-passe redefinida', 'utilizador', (int)$rec['id']);
            unset($_SESSION['recp']);
            flash('ok', 'Palavra-passe actualizada. Já pode entrar.');
            redirecionar('passageiro/entrar.php');
        }
    }
}

$rec = $_SESSION['recp'] ?? ['passo' => 1];
$passo = (int)$rec['passo'];

app_abrir(['titulo' => 'Recuperar palavra-passe', 'cabecalho' => 'nenhum', 'classe_ecra' => 'ecra-branco']);
?>
<div class="auth-app">
  <div class="auth-app-topo">
    <a class="btn-icone" style="background:#eef2f8;color:var(--texto)" href="<?= e(url('passageiro/entrar.php')) ?>" aria-label="Voltar"><?= ic('voltar') ?></a>
    <span class="selo"><?= ic('escudo-ok') ?> Seguro</span>
  </div>

  <?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>
  <?php if ($aviso): ?><div class="alerta alerta-info"><?= e($aviso) ?></div><?php endif; ?>

<?php if ($passo === 1): ?>
  <h1>Recuperar palavra-passe</h1>
  <p class="sub">Indique o email ou telefone associado à sua conta.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="enviar">
    <div class="seletor-canal">
      <label class="<?= $canal === 'email' ? 'escolhido' : '' ?>"><input type="radio" name="canal" value="email" <?= $canal === 'email' ? 'checked' : '' ?>><?= ic('mail') ?> Email</label>
      <label class="<?= $canal === 'telefone' ? 'escolhido' : '' ?>"><input type="radio" name="canal" value="telefone" <?= $canal === 'telefone' ? 'checked' : '' ?>><?= ic('telefone') ?> Telefone</label>
    </div>
    <div class="campo"><label for="identificador">Email ou telefone</label>
      <input type="<?= $canal === 'telefone' ? 'tel' : 'email' ?>" id="identificador" name="identificador" value="<?= e($ident) ?>"
             placeholder="<?= $canal === 'telefone' ? '84 123 4567' : 'nome@email.co.mz' ?>" required></div>
    <button class="btn-app" type="submit"><?= ic('enviar') ?> Enviar código</button>
  </form>
  <p class="link-centro">Lembrou-se? <a href="<?= e(url('passageiro/entrar.php')) ?>">Voltar ao login</a></p>

<?php else: ?>
  <h1>Defina uma nova palavra-passe</h1>
  <p class="sub">Introduza o código enviado para <?= e($rec['destino']) ?> e crie um novo acesso.</p>

  <?php if (AMBIENTE_DEMO && !empty($rec['codigo_demo'])): ?>
    <div class="alerta alerta-ok">Demonstração: o seu código é <b class="mono"><?= e($rec['codigo_demo']) ?></b> (em produção seria enviado por email/SMS).</div>
  <?php endif; ?>

  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="actualizar">
    <div class="campo" style="margin-bottom:0"><label>Código de 6 dígitos</label></div>
    <div class="codigo-caixas">
      <?php for ($i = 0; $i < 6; $i++): ?>
        <input class="codigo-caixa" type="text" name="d[]" inputmode="numeric" maxlength="1" autocomplete="one-time-code" required>
      <?php endfor; ?>
    </div>
    <div class="campo"><label for="nova_senha">Nova palavra-passe</label>
      <div class="campo-ic"><?= ic('cadeado') ?><input type="password" id="nova_senha" name="nova_senha" minlength="8" required>
        <button type="button" class="olho" data-alternar-senha="nova_senha" aria-label="Mostrar"><?= ic('olho') ?></button></div></div>
    <div class="campo"><label for="confirmar_senha">Confirmar palavra-passe</label>
      <div class="campo-ic"><?= ic('cadeado') ?><input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" required>
        <button type="button" class="olho" data-alternar-senha="confirmar_senha" aria-label="Mostrar"><?= ic('olho') ?></button></div></div>
    <span class="aviso-tempo"><?= ic('relogio') ?> O código expira em <span data-contagem="<?= max(0, (int)$rec['expira'] - time()) ?>"></span></span>
    <button class="btn-app" type="submit"><?= ic('enviar') ?> Actualizar palavra-passe</button>
  </form>
  <form method="post" id="form-reenviar"><?= csrf_campo() ?><input type="hidden" name="acao" value="reenviar"></form>
  <p class="link-centro">Não recebeu? <button type="submit" form="form-reenviar">Reenviar código</button></p>
<?php endif; ?>
</div>
<?php app_fechar(); ?>
