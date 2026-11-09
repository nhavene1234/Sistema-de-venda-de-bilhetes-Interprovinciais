<?php
// passageiro/ajuda.php - pedido de ajuda SEM sessão (esqueci a palavra-passe, conta bloqueada, não consigo entrar...)
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/suporte.php';
if ($u = utilizador_logado()) redirecionar($u['perfil'] === 'passageiro' ? 'passageiro/suporte.php' : pagina_inicial_perfil($u['perfil']));
suporte_garantir_tabelas($pdo);

$catsPublicas = ['senha', 'conta', 'pagamento', 'transportadora', 'outro'];
$erro = null;
$enviado = (int)($_GET['enviado'] ?? 0);
$v = ['nome' => '', 'contacto' => '', 'cat' => $_GET['cat'] ?? 'senha', 'texto' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = ['nome' => trim($_POST['nome'] ?? ''), 'contacto' => trim($_POST['contacto'] ?? ''),
          'cat' => $_POST['cat'] ?? '', 'texto' => (string)($_POST['texto'] ?? '')];
    $texto = sp_texto_valido($v['texto']);
    $tel = telefone_normalizar($v['contacto']);
    $contacto = filter_var($v['contacto'], FILTER_VALIDATE_EMAIL) ? mb_strtolower($v['contacto']) : ($tel ? telefone_guardar($tel) : null);
    $st = $pdo->prepare('SELECT COUNT(*) FROM suporte_pedido WHERE ip = ? AND data_criacao >= ?');
    $st->execute([ip_cliente(), date('Y-m-d H:i:s', strtotime('-1 hour'))]);

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif (mb_strlen($v['nome']) < 3) {
        $erro = 'Indique o seu nome.';
    } elseif (!$contacto) {
        $erro = 'Indique um email válido ou um telefone com 9 dígitos (ex.: 84 123 4567) para o podermos contactar.';
    } elseif (!in_array($v['cat'], $catsPublicas, true)) {
        $erro = 'Escolha o tipo de problema.';
    } elseif (!$texto) {
        $erro = 'Descreva o problema (mínimo 5 caracteres).';
    } elseif ((int)$st->fetchColumn() >= 3) {
        $erro = 'Já enviou vários pedidos há pouco. Aguarde um pouco ou espere pelo nosso contacto.';
    } else {
        $idP = sp_criar_pedido($pdo, null, mb_substr($v['nome'], 0, 100), $contacto, $v['cat'], null, $texto);
        redirecionar('passageiro/ajuda.php?enviado=' . $idP);
    }
}

app_abrir(['titulo' => 'Ajuda', 'voltar' => 'passageiro/entrar.php', 'h1' => 'Precisa de ajuda?', 'sub' => 'Fale com o suporte MozTransport',
           'head' => '<link rel="stylesheet" href="' . e(url('assets/css/suporte.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/suporte.css')) . '">']);
?>
<?php if ($enviado): ?>
  <div class="cartao-app" style="text-align:center">
    <h2 style="margin-bottom:6px">Pedido #<?= $enviado ?> enviado</h2>
    <p class="sp-dica">A nossa equipa vai contactá-lo(a) pelo contacto que indicou. Guarde o número do pedido.</p>
    <a class="btn-app" href="<?= e(url('passageiro/entrar.php')) ?>">Voltar a entrar</a>
  </div>
<?php else: ?>
  <?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>
  <form method="post" class="cartao-app sp-form">
    <?= csrf_campo() ?>
    <p class="sp-dica">Para quem não consegue entrar na conta. Se já tem sessão iniciada, use Perfil › Ajuda e suporte.</p>
    <div class="campo"><label for="cat">Qual é o problema?</label>
      <select id="cat" name="cat"><?php foreach ($catsPublicas as $k): ?><option value="<?= e($k) ?>" <?= $v['cat'] === $k ? 'selected' : '' ?>><?= e(SP_CATEGORIAS[$k][0]) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label for="nome">O seu nome</label><input id="nome" name="nome" value="<?= e($v['nome']) ?>" maxlength="100" required></div>
    <div class="campo"><label for="contacto">Telefone ou email para o contactarmos</label><input id="contacto" name="contacto" value="<?= e($v['contacto']) ?>" maxlength="120" required></div>
    <div class="campo"><label for="texto">O que se passa?</label><textarea id="texto" name="texto" rows="4" maxlength="2000" required><?= e($v['texto']) ?></textarea></div>
    <button class="btn-app" type="submit">Enviar pedido</button>
  </form>
<?php endif; ?>
<?php app_fechar(); ?>
