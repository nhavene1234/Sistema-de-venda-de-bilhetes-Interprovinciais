<?php
// passageiro/suporte.php - ajuda e suporte: escolher o problema e ver os pedidos já feitos
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
require_once __DIR__ . '/../includes/suporte.php';
$u = exigir_passageiro();
suporte_garantir_tabelas($pdo);

$st = $pdo->prepare('SELECT id_pedido, assunto, estado, data_actualizacao,
                            (SELECT m.autor FROM suporte_mensagem m WHERE m.id_pedido = p.id_pedido ORDER BY m.id_mensagem DESC LIMIT 1) AS ultimo_autor
                     FROM suporte_pedido p WHERE id_passageiro = ? ORDER BY data_actualizacao DESC LIMIT 20');
$st->execute([$u['id_utilizador']]);
$pedidos = $st->fetchAll();

app_abrir(['titulo' => 'Ajuda e suporte', 'voltar' => 'passageiro/perfil.php', 'h1' => 'Ajuda e suporte',
           'sub' => 'Diga-nos o que se passa e ajudamos',
           'head' => '<link rel="stylesheet" href="' . e(url('assets/css/suporte.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/suporte.css')) . '">']);
?>
<div class="titulo-sec" style="margin-top:-4px"><h2>Com o que precisa de ajuda?</h2></div>
<div class="lista-op">
  <?php foreach (SP_CATEGORIAS as $k => $c): ?>
    <a class="op" href="<?= e(url('passageiro/suporte_novo.php?cat=' . $k)) ?>"><span class="icone"><?= ic('ajuda') ?></span>
      <span class="txt"><b><?= e($c[0]) ?></b><small><?= e($c[1]) ?></small></span><?= ic('direita', 'seta') ?></a>
  <?php endforeach; ?>
</div>

<div class="titulo-sec"><h2>Os meus pedidos</h2></div>
<?php if (!$pedidos): ?>
  <div class="cartao-app" style="text-align:center;color:var(--cinza)">Ainda não fez nenhum pedido de ajuda.</div>
<?php else: ?>
  <div class="lista-op">
  <?php foreach ($pedidos as $p): ?>
    <a class="op" href="<?= e(url('passageiro/suporte_ver.php?id=' . (int)$p['id_pedido'])) ?>">
      <span class="txt"><b><?= e($p['assunto']) ?></b><small>#<?= (int)$p['id_pedido'] ?> · <?= e(sp_data($p['data_actualizacao'])) ?><?= $p['ultimo_autor'] === 'administrador' && $p['estado'] !== 'resolvido' ? ' · resposta do suporte' : '' ?></small></span>
      <?= sp_etq($p['estado']) ?></a>
  <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php app_fechar(['nav' => 'perfil']); ?>
