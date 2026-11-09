<?php
// includes/app_layout.php - estrutura das páginas da aplicação do passageiro (ecrã de telemóvel)
require_once __DIR__ . '/app_icones.php';
require_once __DIR__ . '/notificacoes.php';

/**
 * Opções: titulo, cabecalho ('gradiente'|'nenhum'), h1, sub, voltar (caminho), logo (bool),
 *         acao (html no canto), classe, classe_ecra, head (html extra)
 */
function app_abrir(array $o = []): void {
    $cab = $o['cabecalho'] ?? 'gradiente';
    $mensagem = flash_obter();
    ?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e($o['titulo'] ?? APP_NOME) ?> · <?= e(APP_NOME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/app.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/notificacoes.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/notificacoes.css')) ?>">
  <?= $o['head'] ?? '' ?>
</head>
<body class="app <?= e($o['classe'] ?? '') ?>">
<div class="telefone">
<?php if ($cab === 'gradiente'): ?>
  <header class="app-topo">
    <div class="app-topo-linha">
      <?php if (!empty($o['voltar'])): ?>
        <a class="btn-icone" href="<?= e(url($o['voltar'])) ?>" aria-label="Voltar"><?= ic('voltar') ?></a>
      <?php elseif (!empty($o['logo'])): ?>
        <?= logo_html('logo-claro logo-pequeno') ?>
      <?php else: ?><span></span><?php endif; ?>
      <span class="app-topo-acao"><?= $o['acao'] ?? '' ?></span>
    </div>
    <?php if (!empty($o['h1'])): ?><h1><?= e($o['h1']) ?></h1><?php endif; ?>
    <?php if (!empty($o['sub'])): ?><p><?= e($o['sub']) ?></p><?php endif; ?>
  </header>
<?php endif; ?>
<main class="ecra <?= e($o['classe_ecra'] ?? '') ?>">
  <?php if ($mensagem): ?>
    <div class="alerta alerta-<?= e($mensagem['tipo']) ?>"><?= e($mensagem['msg']) ?></div>
  <?php endif; ?>
<?php
}

/** Opções: nav (inicio|bilhetes|historico|perfil), scripts (array de URLs) */
function app_fechar(array $o = []): void {
    echo "</main>\n";
    if (!empty($o['nav'])) {
        $itens = [
            'inicio'    => ['Início',    'passageiro/index.php',     'casa'],
            'bilhetes'  => ['Bilhetes',  'passageiro/bilhetes.php',  'bilhete'],
            'historico' => ['Histórico', 'passageiro/historico.php', 'historico'],
            'perfil'    => ['Perfil',    'passageiro/perfil.php',    'utilizador'],
        ];
        echo '<nav class="app-nav">';
        foreach ($itens as $k => [$rotulo, $caminho, $icone]) {
            echo '<a href="' . e(url($caminho)) . '" class="' . ($o['nav'] === $k ? 'activo' : '') . '">'
               . ic($icone) . '<span>' . e($rotulo) . '</span></a>';
        }
        echo '</nav>';
    }
    echo "</div>\n";
    foreach ($o['scripts'] ?? [] as $s) {
        echo '<script src="' . e($s) . '"></script>' . "\n";
    }
    echo '<script src="' . e(url('assets/js/contacto.js')) . '"></script>' . "\n";
    if (utilizador_logado()) {
        echo '<script src="' . e(url('assets/js/notificacoes.js') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/js/notificacoes.js')) . '"></script>' . "\n";
    }
    echo '<script src="' . e(url('assets/js/app.js')) . '"></script>' . "\n</body>\n</html>";
}
