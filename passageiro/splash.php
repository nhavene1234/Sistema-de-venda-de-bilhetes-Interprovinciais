<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
if ($u = utilizador_logado()) redirecionar(pagina_inicial_perfil($u['perfil']));

app_abrir(['titulo' => 'Bem-vindo', 'cabecalho' => 'nenhum', 'classe' => 'splash', 'classe_ecra' => 'ecra-splash']);
?>
<div class="splash-icone"><?= ic('onibus') ?></div>
<?= logo_html('logo-claro') ?>
<p>Moçambique em movimento. A sua viagem começa aqui.</p>
<span class="splash-rodape">Bilhetes interprovinciais</span>
<span data-redirecionar="<?= e(url('passageiro/entrar.php')) ?>" data-atraso="1800"></span>
<?php app_fechar(); ?>
