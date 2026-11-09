<?php
// includes/painel_inicio.php - abre o layout do painel (menu lateral + topo)
// Variáveis esperadas: $tituloPagina, $migalhas (array), $paginaActiva (caminho do menu)
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/notificacoes.php';

$u        = utilizador_logado();
$menu     = menu_do_perfil($u['perfil']);
$migalhas = $migalhas ?? [];
$mensagem = flash_obter();
$nomeTransp = null;   // o gestor vê sempre a transportadora a que está vinculado (RN13)
if ($u['perfil'] === 'gestor') {
    $stT = $pdo->prepare('SELECT t.nome FROM utilizador x JOIN transportadora t ON t.id_transportadora = x.id_transportadora WHERE x.id_utilizador = ?');
    $stT->execute([$u['id_utilizador']]);
    $nomeTransp = $stT->fetchColumn() ?: null;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($tituloPagina ?? 'Painel') ?> · <?= e(APP_NOME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/base.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/painel.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/painel.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/admin.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/admin.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/bilheteiro.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/bilheteiro.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/notificacoes.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/notificacoes.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/suporte.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/suporte.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/gestor.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/gestor.css')) ?>">
</head>
<body class="painel perfil-<?= e($u['perfil']) ?>">

<aside class="lateral">
  <div class="faixa-bandeira"></div>
  <div class="lateral-logo"><?= logo_html('logo-claro') ?></div>

  <nav class="lateral-menu">
    <?php foreach ($menu as [$rotulo, $caminho, $icone]): ?>
      <a href="<?= e(url($caminho)) ?>" class="<?= ($paginaActiva ?? '') === $caminho ? 'activo' : '' ?>">
        <?= icone_svg($icone) ?><span><?= e($rotulo) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="lateral-rodape">
    <a class="utilizador-cartao" href="<?= e(url('conta/dados_pessoais.php')) ?>" title="Dados pessoais">
      <?= avatar_html($u) ?>
      <span class="utilizador-texto">
        <strong><?= e($u['nome']) ?></strong>
        <small><?= e($u['perfil'] === 'administrador' ? 'Administrador Geral' : nome_perfil($u['perfil'])) ?><?= $nomeTransp ? ' • ' . e($nomeTransp) : (!empty($u['agencia']) ? ' • ' . e(explode(' ', $u['agencia'])[0]) : '') ?></small>
      </span>
    </a>
    <a class="sair" href="<?= e(url('auth/logout.php')) ?>" title="Terminar sessão"><?= icone_svg('sair') ?></a>
  </div>
</aside>

<div class="conteudo-geral">
  <header class="topo">
    <nav class="migalhas" aria-label="Localização">
      <?php foreach ($migalhas as $i => $m): ?>
        <?php if ($i > 0): ?><span class="sep">›</span><?php endif; ?>
        <span class="<?= $i === count($migalhas) - 1 ? 'actual' : '' ?>"><?= e($m) ?></span>
      <?php endforeach; ?>
    </nav>
    <div class="topo-direita">
      <?php if ($u['perfil'] !== 'administrador'): ?>
        <label class="pesquisa-topo"><?= icone_svg('pesquisa') ?>
          <input type="search" placeholder="Pesquisar bilhetes, rotas..." aria-label="Pesquisar">
        </label>
      <?php endif; ?>
      <?php if ($u['perfil'] === 'administrador'):
        $filtroActual = filtro_transportadora();
        $listaTransp  = $pdo->query('SELECT id_transportadora, nome FROM transportadora ORDER BY nome')->fetchAll();
      ?>
        <form method="get" class="filtro-topo">
          <select name="transportadora" onchange="this.form.submit()" aria-label="Filtrar por transportadora">
            <option value="0">Todas as transportadoras</option>
            <?php foreach ($listaTransp as $t): ?>
              <option value="<?= (int)$t['id_transportadora'] ?>" <?= $filtroActual === (int)$t['id_transportadora'] ? 'selected' : '' ?>><?= e($t['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      <?php endif; ?>
      <?= sino_notificacoes('sino', icone_svg('sino')) ?>
      <?php if ($nomeTransp): ?>
        <span class="etiqueta-restrita">🔒 <?= e($nomeTransp) ?> · Dados restritos à sua transportadora</span>
      <?php elseif (!empty($u['agencia'])): ?>
        <span class="etiqueta-agencia"><?= e($u['agencia']) ?></span>
      <?php endif; ?>
    </div>
  </header>

  <main class="conteudo">
    <?php if ($mensagem): ?>
      <div class="alerta alerta-<?= e($mensagem['tipo']) ?>"><?= e($mensagem['msg']) ?></div>
    <?php endif; ?>
