<?php
require_once __DIR__ . '/_base.php';
$id = (int)$u['id_utilizador'];
$erro = null;

function cb_carregar(PDO $pdo, int $id): array {
    $st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$id]);
    return $st->fetch();
}
function cb_apagar_foto(?string $caminho): void {
    if ($caminho && str_starts_with($caminho, 'assets/img/perfis/')) {
        $f = __DIR__ . '/../' . $caminho;
        if (is_file($f)) @unlink($f);
    }
}
/** Valida e guarda a foto. Devolve [sucesso, caminho_ou_mensagem_de_erro]. */
function cb_guardar_foto(array $f, int $id, ?string $antiga): array {
    if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Não foi possível carregar a foto.'];
    if ($f['size'] > MAX_FOTO_BYTES)  return [false, 'A foto excede o limite de 5MB.'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;
    if (!$ext) return [false, 'Formato inválido. Use PNG ou JPG.'];
    $pasta = __DIR__ . '/../assets/img/perfis/';
    if (!is_dir($pasta)) mkdir($pasta, 0775, true);
    $nome = 'u' . $id . '_' . time() . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $pasta . $nome)) return [false, 'Não foi possível guardar a foto.'];
    cb_apagar_foto($antiga);
    return [true, 'assets/img/perfis/' . $nome];
}

$dados = cb_carregar($pdo, $id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Tente novamente.';
    } elseif (($_POST['acao'] ?? '') === 'remover_foto') {
        cb_apagar_foto($dados['foto']);
        $pdo->prepare('UPDATE utilizador SET foto = NULL WHERE id_utilizador = ?')->execute([$id]);
        $_SESSION['utilizador']['foto'] = null;
        registar_log($pdo, $id, 'Foto de perfil removida', 'utilizador', $id);
        flash('ok', 'Foto removida.');
        redirecionar('cobrador/dados.php');
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['telefone'] ?? '');
        $telN = $tel === '' ? null : telefone_normalizar($tel);
        if (mb_strlen($nome) < 3) {
            $erro = 'Indique o seu nome completo.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'Indique um email válido.';
        } elseif ((CONTACTO_OBRIGATORIO || $tel !== '') && $telN === null) {
            $erro = MSG_TELEFONE;
        } else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ? AND id_utilizador <> ?');
            $st->execute([$email, $id]);
            if ($st->fetchColumn() > 0) {
                $erro = 'Este email já está a ser usado por outra conta.';
            } else {
                $foto = $dados['foto'];
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    [$ok, $res] = cb_guardar_foto($_FILES['foto'], $id, $dados['foto']);
                    if ($ok) $foto = $res; else $erro = $res;
                }
                if (!$erro) {
                    $pdo->prepare('UPDATE utilizador SET nome = ?, email = ?, telefone = ?, foto = ? WHERE id_utilizador = ?')
                        ->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, $foto, $id]);
                    $_SESSION['utilizador'] = array_merge($_SESSION['utilizador'], ['nome' => $nome, 'email' => $email, 'foto' => $foto]);
                    registar_log($pdo, $id, 'Dados pessoais actualizados', 'utilizador', $id);
                    flash('ok', 'Alterações guardadas com sucesso.');
                    redirecionar('cobrador/perfil.php');
                }
            }
        }
    }
    $dados = cb_carregar($pdo, $id);
}
$val = fn(string $c) => $_POST[$c] ?? ($dados[$c] ?? '');
$ini = mb_strtoupper(mb_substr($dados['nome'], 0, 1));

cb_abrir('Dados pessoais');
?>
<header class="topo simples">
  <div class="topo-linha"><div><h1>Dados pessoais</h1><p>Nome, contacto e foto de perfil</p></div>
    <a class="topo-btn" href="<?= e(url('cobrador/perfil.php')) ?>" aria-label="Voltar"><?= cb_ic('x', 20) ?></a></div>
</header>

<?php if ($erro): ?><div class="aviso-cb erro"><?= e($erro) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form-cb">
  <?= csrf_campo() ?>
  <div class="foto-edit">
    <div class="foto" id="foto-previa"><?php if (!empty($dados['foto'])): ?><img src="<?= e(url($dados['foto'])) ?>" alt="Foto de perfil"><?php else: ?><?= e($ini) ?><?php endif; ?></div>
    <div>
      <label class="btn-foto" for="foto-input">Alterar foto</label>
      <input type="file" id="foto-input" name="foto" accept="image/png,image/jpeg" hidden>
      <p class="dica" style="margin:6px 0 0">PNG ou JPG até 5MB.</p>
    </div>
  </div>

  <label for="nome">Nome completo</label>
  <div class="campo-s"><input type="text" id="nome" name="nome" value="<?= e($val('nome')) ?>" required style="padding-right:14px"></div>
  <label for="email">Endereço de email</label>
  <div class="campo-s"><input type="email" id="email" name="email" value="<?= e($val('email')) ?>" required style="padding-right:14px"></div>
  <label for="telefone">Contacto telefónico</label>
  <div class="campo-s"><input type="tel" id="telefone" name="telefone" value="<?= e($val('telefone')) ?>" placeholder="841234567" style="padding-right:14px" data-contacto <?= CONTACTO_OBRIGATORIO ? 'required' : '' ?>></div>

  <button class="btn-validar" type="submit" style="width:100%;border:0;font-family:inherit">Guardar alterações</button>
</form>

<?php if (!empty($dados['foto'])): ?>
<form method="post" class="form-cb" style="margin-top:12px;padding:12px 18px" data-conf="Remover a foto de perfil?">
  <?= csrf_campo() ?><input type="hidden" name="acao" value="remover_foto">
  <button type="submit" class="btn-remover">Remover foto</button>
</form>
<?php endif; ?>
<script>
(function () {
  var i = document.getElementById('foto-input'), p = document.getElementById('foto-previa');
  if (i) i.addEventListener('change', function () {
    var f = i.files[0]; if (!f) return;
    var r = new FileReader();
    r.onload = function (e) { p.innerHTML = ''; var im = document.createElement('img'); im.src = e.target.result; p.appendChild(im); };
    r.readAsDataURL(f);
  });
  document.querySelectorAll('form[data-conf]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!confirm(f.dataset.conf)) e.preventDefault(); });
  });
})();
</script>
<?php cb_fechar('perfil'); cb_fim(); ?>
