<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_login();

$sessao = utilizador_logado();
$id = (int)$sessao['id_utilizador'];

function carregar_dados(PDO $pdo, int $id): array {
    $st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$id]);
    return $st->fetch();
}

function apagar_foto(?string $caminho): void {
    if ($caminho && str_starts_with($caminho, 'assets/img/perfis/')) {
        $f = __DIR__ . '/../' . $caminho;
        if (is_file($f)) @unlink($f);
    }
}

/** Valida e guarda a foto. Devolve [sucesso, caminho_ou_mensagem_de_erro]. */
function guardar_foto(array $f, int $id, ?string $antiga): array {
    if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Não foi possível carregar a foto.'];
    if ($f['size'] > MAX_FOTO_BYTES)  return [false, 'A foto excede o limite de 5MB.'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;
    if (!$ext) return [false, 'Formato inválido. Use PNG ou JPG.'];
    $pasta = __DIR__ . '/../assets/img/perfis/';
    if (!is_dir($pasta)) mkdir($pasta, 0775, true);
    $nome = 'u' . $id . '_' . time() . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $pasta . $nome)) return [false, 'Não foi possível guardar a foto.'];
    apagar_foto($antiga);
    return [true, 'assets/img/perfis/' . $nome];
}

$erroDados = null;
$erroSenha = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        flash('erro', 'Pedido inválido. Tente novamente.');
        redirecionar('conta/dados_pessoais.php');
    }
    $acao  = $_POST['acao'] ?? '';
    $dados = carregar_dados($pdo, $id);

    if ($acao === 'gravar_dados') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['telefone'] ?? '');
        $telN = $tel === '' ? null : telefone_normalizar($tel);
        $agencia = trim($_POST['agencia'] ?? '');

        if (mb_strlen($nome) < 3) {
            $erroDados = 'Indique o seu nome completo.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroDados = 'Indique um email válido.';
        } elseif ((CONTACTO_OBRIGATORIO || $tel !== '') && $telN === null) {
            $erroDados = MSG_TELEFONE;
        } else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ? AND id_utilizador <> ?');
            $st->execute([$email, $id]);
            if ($st->fetchColumn() > 0) {
                $erroDados = 'Este email já está a ser usado por outra conta.';
            } else {
                $foto = $dados['foto'];
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    [$ok, $res] = guardar_foto($_FILES['foto'], $id, $dados['foto']);
                    if ($ok) $foto = $res; else $erroDados = $res;
                }
                if (!$erroDados) {
                    $pdo->prepare('UPDATE utilizador SET nome = ?, email = ?, telefone = ?, agencia = ?, foto = ? WHERE id_utilizador = ?')
                        ->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, $agencia ?: null, $foto, $id]);
                    $_SESSION['utilizador'] = array_merge($sessao, [
                        'nome' => $nome, 'email' => $email, 'foto' => $foto, 'agencia' => $agencia ?: null,
                    ]);
                    registar_log($pdo, $id, 'Dados pessoais actualizados', 'utilizador', $id);
                    flash('ok', 'Alterações guardadas com sucesso.');
                    redirecionar('conta/dados_pessoais.php');
                }
            }
        }

    } elseif ($acao === 'remover_foto') {
        apagar_foto($dados['foto']);
        $pdo->prepare('UPDATE utilizador SET foto = NULL WHERE id_utilizador = ?')->execute([$id]);
        $_SESSION['utilizador']['foto'] = null;
        registar_log($pdo, $id, 'Foto de perfil removida', 'utilizador', $id);
        flash('ok', 'Foto removida.');
        redirecionar('conta/dados_pessoais.php');

    } elseif ($acao === 'actualizar_senha') {
        $actual = $_POST['senha_actual'] ?? '';
        $nova = $_POST['nova_senha'] ?? '';
        $conf = $_POST['confirmar_senha'] ?? '';
        if (!password_verify($actual, $dados['senha'])) {
            $erroSenha = 'A palavra-passe actual está incorrecta.';
        } elseif ($falha = validar_nova_senha($nova)) {
            $erroSenha = $falha;
        } elseif ($nova !== $conf) {
            $erroSenha = 'As palavras-passe não coincidem.';
        } else {
            $pdo->prepare('UPDATE utilizador SET senha = ? WHERE id_utilizador = ?')
                ->execute([password_hash($nova, PASSWORD_DEFAULT), $id]);
            registar_log($pdo, $id, 'Palavra-passe alterada', 'utilizador', $id);
            flash('ok', 'Palavra-passe actualizada com sucesso.');
            redirecionar('conta/dados_pessoais.php');
        }
    }
}

$dados = carregar_dados($pdo, $id);
$valor = fn(string $c) => $_POST[$c] ?? ($dados[$c] ?? '');

$tituloPagina = 'Dados Pessoais';
$migalhas = [nome_secao($sessao['perfil']), 'Perfil do Utilizador'];
$paginaActiva = 'conta/dados_pessoais.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina">Gerir Dados Pessoais</h1>
<p class="subtitulo-pagina">Actualize as suas informações básicas e credenciais de acesso ao painel de bilhetagem.</p>

<div class="cartoes-2">

  <!-- Informações básicas -->
  <section class="cartao">
    <h2>Informações Básicas</h2>
    <p class="sub"></p>
    <?php if ($erroDados): ?><div class="alerta alerta-erro"><?= e($erroDados) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <?= csrf_campo() ?>
      <input type="hidden" name="acao" value="gravar_dados">

      <div class="foto-linha">
        <span id="foto-previa" class="avatar avatar-grande" style="<?= empty($dados['foto']) ? 'background:#0ea5e9' : '' ?>">
          <?php if (!empty($dados['foto'])): ?><img src="<?= e(url($dados['foto'])) ?>" alt="Foto de perfil">
          <?php else: ?><?= e(iniciais($dados['nome'])) ?><?php endif; ?>
        </span>
        <div>
          <div class="foto-acoes">
            <label class="btn btn-primario btn-pequeno" for="foto-input">Alterar Foto</label>
            <input type="file" id="foto-input" name="foto" accept="image/png,image/jpeg">
            <?php if (!empty($dados['foto'])): ?>
              <button type="button" class="btn btn-sec btn-pequeno" data-acao="remover_foto">Remover</button>
            <?php endif; ?>
          </div>
          <p class="foto-nota">PNG ou JPG até 5MB. Resolução recomendada: 400×400px.</p>
        </div>
      </div>

      <div class="campo">
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" value="<?= e($valor('nome')) ?>" required>
      </div>
      <div class="campo">
        <label for="email">Endereço de Email</label>
        <input type="email" id="email" name="email" value="<?= e($valor('email')) ?>" required>
      </div>
      <div class="linha-2">
        <div class="campo">
          <label for="telefone">Contacto Telefónico</label>
          <input type="tel" id="telefone" name="telefone" value="<?= e($valor('telefone')) ?>" placeholder="841234567" data-contacto <?= CONTACTO_OBRIGATORIO ? 'required' : '' ?>>
        </div>
        <div class="campo">
          <label for="agencia">Agência Associada</label>
          <input type="text" id="agencia" name="agencia" value="<?= e($valor('agencia')) ?>" placeholder="Maputo – Terminal Junta">
        </div>
      </div>
      <div class="acoes-form">
        <a class="btn btn-sec" href="<?= e(url('conta/dados_pessoais.php')) ?>">Descartar</a>
        <button class="btn btn-primario" type="submit">Gravar Alterações</button>
      </div>
    </form>
  </section>

  <!-- Segurança da conta -->
  <section class="cartao">
    <h2>Segurança da Conta</h2>
    <p class="sub">Recomendamos que altere a sua palavra-passe de 3 em 3 meses para manter os seus dados e vendas seguras.</p>
    <?php if ($erroSenha): ?><div class="alerta alerta-erro"><?= e($erroSenha) ?></div><?php endif; ?>

    <form method="post">
      <?= csrf_campo() ?>
      <input type="hidden" name="acao" value="actualizar_senha">
      <div class="campo">
        <label for="senha_actual">Palavra-passe Actual</label>
        <input type="password" id="senha_actual" name="senha_actual" required autocomplete="current-password">
      </div>
      <div class="campo">
        <label for="nova_senha">Nova Palavra-passe</label>
        <div class="campo-senha">
          <input type="password" id="nova_senha" name="nova_senha" minlength="8" required autocomplete="new-password">
          <button type="button" class="alternar" data-alternar-senha="nova_senha">Mostrar</button>
        </div>
      </div>
      <div class="forca"><span></span><span></span><span></span><span></span></div>
      <div class="forca-info"><b id="forca-texto"></b><span id="forca-pct"></span></div>
      <div class="campo">
        <label for="confirmar_senha">Confirmar Nova Palavra-passe</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" required autocomplete="new-password">
      </div>
      <button class="btn btn-primario btn-bloco" type="submit">Actualizar Palavra-passe</button>
    </form>
  </section>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
