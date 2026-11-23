<?php
require_once __DIR__ . '/../includes/sessao.php';

if ($u = utilizador_logado()) {
    redirecionar(pagina_inicial_perfil($u['perfil']));
}
if (isset($_GET['reiniciar'])) {
    unset($_SESSION['rec']);
    redirecionar('auth/recuperar_senha.php');
}

$validade = (int)config_valor($pdo, 'recuperacao_validade_minutos', 10);
$rec  = $_SESSION['rec'] ?? ['passo' => 1];
$erro = null;

/** Cria um código de 6 dígitos (guardado cifrado) e invalida os anteriores. */
function gerar_codigo(PDO $pdo, int $idUtilizador, int $validadeMin): string {
    $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE id_utilizador = ? AND usado = 0')
        ->execute([$idUtilizador]);
    $pdo->prepare('INSERT INTO recuperacao_senha (id_utilizador, codigo_hash, data_expiracao) VALUES (?, ?, ?)')
        ->execute([$idUtilizador, password_hash($codigo, PASSWORD_DEFAULT), date('Y-m-d H:i:s', time() + $validadeMin * 60)]);
    return $codigo;
}

function enviar_codigo(string $email, string $codigo): void {
    // Em produção: enviar por email/SMS. No XAMPP o mail() normalmente não está configurado.
    @mail($email, 'Código de recuperação - ' . APP_NOME, "O seu código de verificação é: $codigo");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } else {
        $acao = $_POST['acao'] ?? '';

        // ---------- PASSO 1: email ----------
        if ($acao === 'email') {
            $email = trim($_POST['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erro = 'Indique um email válido.';
            } else {
                $st = $pdo->prepare('SELECT id_utilizador FROM utilizador WHERE email = ? AND activo = 1');
                $st->execute([$email]);
                $id = (int)$st->fetchColumn();
                $codigoDemo = null;
                if ($id) {
                    $codigo = gerar_codigo($pdo, $id, $validade);
                    enviar_codigo($email, $codigo);
                    registar_log($pdo, $id, 'Recuperação de palavra-passe solicitada', 'utilizador', $id);
                    $codigoDemo = $codigo;
                }
                // avança sempre, para não revelar se o email existe
                $_SESSION['rec'] = ['passo' => 2, 'email' => $email, 'id' => $id, 'enviado_em' => time(), 'codigo_demo' => $codigoDemo];
                redirecionar('auth/recuperar_senha.php');
            }

        // ---------- PASSO 2: código ----------
        } elseif ($acao === 'codigo' && ($rec['passo'] ?? 1) === 2) {
            $digitos = implode('', array_map('strval', $_POST['d'] ?? []));
            $linha = null;
            if (preg_match('/^\d{6}$/', $digitos) && !empty($rec['id'])) {
                $st = $pdo->prepare('SELECT * FROM recuperacao_senha WHERE id_utilizador = ? AND usado = 0
                                     ORDER BY id_recuperacao DESC LIMIT 1');
                $st->execute([$rec['id']]);
                $linha = $st->fetch();
            }
            if (!preg_match('/^\d{6}$/', $digitos)) {
                $erro = 'Introduza os 6 dígitos do código.';
            } elseif (!$linha || strtotime($linha['data_expiracao']) < time() || (int)$linha['tentativas'] >= 5) {
                $erro = 'Código inválido ou expirado. Peça um novo código.';
            } elseif (!password_verify($digitos, $linha['codigo_hash'])) {
                $pdo->prepare('UPDATE recuperacao_senha SET tentativas = tentativas + 1 WHERE id_recuperacao = ?')
                    ->execute([$linha['id_recuperacao']]);
                $erro = 'Código incorrecto. Tente novamente.';
            } else {
                $_SESSION['rec']['passo'] = 3;
                $_SESSION['rec']['id_recuperacao'] = (int)$linha['id_recuperacao'];
                redirecionar('auth/recuperar_senha.php');
            }

        // ---------- reenviar código ----------
        } elseif ($acao === 'reenviar' && ($rec['passo'] ?? 1) === 2) {
            if (time() - (int)$rec['enviado_em'] >= 60 && !empty($rec['id'])) {
                $codigo = gerar_codigo($pdo, (int)$rec['id'], $validade);
                enviar_codigo($rec['email'], $codigo);
                $_SESSION['rec']['enviado_em'] = time();
                $_SESSION['rec']['codigo_demo'] = $codigo;
            }
            redirecionar('auth/recuperar_senha.php');

        // ---------- PASSO 3: nova palavra-passe ----------
        } elseif ($acao === 'senha' && ($rec['passo'] ?? 1) === 3) {
            $nova = $_POST['nova_senha'] ?? '';
            $conf = $_POST['confirmar_senha'] ?? '';
            if ($falha = validar_nova_senha($nova)) {
                $erro = $falha;
            } elseif ($nova !== $conf) {
                $erro = 'As palavras-passe não coincidem.';
            } else {
                $pdo->prepare('UPDATE utilizador SET senha = ?, tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
                    ->execute([password_hash($nova, PASSWORD_DEFAULT), $rec['id']]);
                $pdo->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE id_recuperacao = ?')
                    ->execute([$rec['id_recuperacao']]);
                registar_log($pdo, (int)$rec['id'], 'Palavra-passe redefinida', 'utilizador', (int)$rec['id']);
                unset($_SESSION['rec']);
                flash('ok', 'Palavra-passe redefinida com sucesso. Já pode entrar.');
                redirecionar('auth/login.php');
            }
        }
    }
}

$rec = $_SESSION['rec'] ?? ['passo' => 1];
$passo = (int)$rec['passo'];
$espera = $passo === 2 ? max(0, 60 - (time() - (int)$rec['enviado_em'])) : 0;

function classe_passo(int $n, int $actual): string {
    return $n < $actual ? 'feito' : ($n === $actual ? 'actual' : '');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar palavra-passe · <?= e(APP_NOME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/auth.css')) ?>">
</head>
<body class="auth">
<div class="auth-centro">
  <div class="cartao-rec">
    <?= logo_html() ?>
    <h1>Recuperar Palavra-passe</h1>
    <p class="auth-sub">Verifique a sua identidade em 3 passos para redefinir as credenciais.</p>

    <div class="passos">
      <span class="passo <?= classe_passo(1, $passo) ?>"><b><?= $passo > 1 ? '✓' : '1' ?></b> Email</span>
      <span class="passo-linha"></span>
      <span class="passo <?= classe_passo(2, $passo) ?>"><b><?= $passo > 2 ? '✓' : '2' ?></b> Código</span>
      <span class="passo-linha"></span>
      <span class="passo <?= classe_passo(3, $passo) ?>"><b>3</b> Nova Senha</span>
    </div>

    <?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>

    <?php if ($passo === 1): ?>
      <form method="post">
        <?= csrf_campo() ?><input type="hidden" name="acao" value="email">
        <div class="campo">
          <label for="email">Endereço de Email</label>
          <input type="email" id="email" name="email" placeholder="nome@empresa.co.mz" required autofocus>
        </div>
        <button class="btn btn-primario btn-bloco" type="submit">Enviar código</button>
        <a class="btn btn-sec btn-bloco" href="<?= e(url('auth/login.php')) ?>">Voltar ao Login</a>
      </form>

    <?php elseif ($passo === 2): ?>
      <div class="alerta alerta-info">
        Enviámos um código de 6 dígitos para o email:<br>
        <span class="mono"><?= e($rec['email']) ?></span>
      </div>
      <?php if (!empty($rec['codigo_demo'])): ?>
        <div class="alerta alerta-ok">Ambiente de demonstração: o seu código é
          <strong class="mono" style="display:inline"><?= e($rec['codigo_demo']) ?></strong>
          (em produção seria enviado por email/SMS).</div>
      <?php endif; ?>

      <form method="post">
        <?= csrf_campo() ?><input type="hidden" name="acao" value="codigo">
        <div class="campo" style="margin-bottom:0"><label>Código de Verificação</label></div>
        <div class="codigo-caixas">
          <?php for ($i = 0; $i < 6; $i++): ?>
            <input class="codigo-caixa" type="text" name="d[]" inputmode="numeric" maxlength="1" autocomplete="one-time-code" required>
          <?php endfor; ?>
        </div>
        <div class="rec-linha">
          <span>Não recebeu o código?</span>
          <?php if ($espera > 0): ?>
            <span id="reenviar-espera" data-segundos="<?= $espera ?>">Reenviar código (<b><?= $espera ?></b>s)</span>
            <button type="submit" form="form-reenviar" id="btn-reenviar" class="btn-link" hidden>Reenviar código</button>
          <?php else: ?>
            <button type="submit" form="form-reenviar" class="btn-link">Reenviar código</button>
          <?php endif; ?>
        </div>
        <button class="btn btn-primario btn-bloco" type="submit">Validar Código e Continuar</button>
        <a class="btn btn-sec btn-bloco" href="<?= e(url('auth/login.php')) ?>">Voltar ao Login</a>
      </form>
      <form method="post" id="form-reenviar"><?= csrf_campo() ?><input type="hidden" name="acao" value="reenviar"></form>

    <?php else: ?>
      <form method="post">
        <?= csrf_campo() ?><input type="hidden" name="acao" value="senha">
        <div class="campo">
          <label for="nova_senha">Nova palavra-passe</label>
          <div class="campo-senha">
            <input type="password" id="nova_senha" name="nova_senha" minlength="8" required autofocus>
            <button type="button" class="alternar" data-alternar-senha="nova_senha">Mostrar</button>
          </div>
        </div>
        <div class="campo">
          <label for="confirmar_senha">Confirmar nova palavra-passe</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" required>
        </div>
        <p class="auth-sub" style="margin-bottom:14px">Mínimo de 8 caracteres, com letras e números.</p>
        <button class="btn btn-primario btn-bloco" type="submit">Guardar nova palavra-passe</button>
        <a class="btn btn-sec btn-bloco" href="<?= e(url('auth/recuperar_senha.php?reiniciar=1')) ?>">Cancelar</a>
      </form>
    <?php endif; ?>
  </div>
</div>
<script src="<?= e(url('assets/js/auth.js')) ?>"></script>
</body>
</html>
