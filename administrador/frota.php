<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_perfil(['administrador']);

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT * FROM transportadora WHERE id_transportadora = ?');
$st->execute([$id]);
$transpFrota = $st->fetch();
if (!$transpFrota) { flash('erro', 'Transportadora não encontrada.'); redirecionar('administrador/transportadoras.php'); }

$st = $pdo->prepare('SELECT * FROM autocarro WHERE id_transportadora = ? ORDER BY matricula');
$st->execute([$id]);
$autocarros = $st->fetchAll();
$st = $pdo->prepare('SELECT * FROM rota WHERE id_transportadora = ? ORDER BY origem, destino');
$st->execute([$id]);
$rotas = $st->fetchAll();

$tituloPagina = 'Frota · ' . $transpFrota['nome'];
$migalhas = ['Painel Admin', 'Transportadoras', $transpFrota['nome']];
$paginaActiva = 'administrador/transportadoras.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina">Frota e rotas · <?= e($transpFrota['nome']) ?></h1>
<p class="subtitulo-pagina">NUIT <?= e($transpFrota['nuit']) ?> · <?= e($transpFrota['contacto']) ?><?= $transpFrota['endereco'] ? ' · ' . e($transpFrota['endereco']) : '' ?>.
  <a href="<?= e(url('administrador/transportadoras.php')) ?>">← Voltar às transportadoras</a></p>

<div class="cartoes-2" style="grid-template-columns:1fr 1fr">
  <section class="cartao">
    <h2>Autocarros (<?= count($autocarros) ?>)</h2>
    <p class="sub">A gestão da frota será feita pelo Gestor de cada transportadora.</p>
    <table class="tabela">
      <thead><tr><th>Matrícula</th><th>Modelo</th><th>Lugares</th><th>Estado</th></tr></thead>
      <tbody>
      <?php if (!$autocarros): ?><tr><td colspan="4" style="color:var(--cinza)">Sem autocarros registados.</td></tr><?php endif; ?>
      <?php foreach ($autocarros as $a): ?>
        <tr><td class="mono"><?= e($a['matricula']) ?></td><td><?= e($a['modelo'] ?? '—') ?></td><td><?= (int)$a['capacidade_total'] ?></td>
          <td><span class="etq <?= $a['activo'] ? 'etq-estado-activa' : 'etq-estado-inactiva' ?>"><?= $a['activo'] ? 'Activo' : 'Inactivo' ?></span></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>
  <section class="cartao">
    <h2>Rotas (<?= count($rotas) ?>)</h2>
    <p class="sub">Rotas concessionadas a esta transportadora.</p>
    <table class="tabela">
      <thead><tr><th>Origem → Destino</th><th>Distância</th><th>Estado</th></tr></thead>
      <tbody>
      <?php if (!$rotas): ?><tr><td colspan="3" style="color:var(--cinza)">Sem rotas registadas.</td></tr><?php endif; ?>
      <?php foreach ($rotas as $r): ?>
        <tr><td><?= e($r['origem']) ?> → <?= e($r['destino']) ?></td><td><?= $r['distancia_km'] !== null ? formatar_numero($r['distancia_km']) . ' km' : '—' ?></td>
          <td><span class="etq <?= $r['activa'] ? 'etq-estado-activa' : 'etq-estado-inactiva' ?>"><?= $r['activa'] ? 'Activa' : 'Inactiva' ?></span></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>