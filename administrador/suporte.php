<?php
// administrador/suporte.php - pedidos de ajuda dos passageiros
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/suporte.php';
exigir_perfil(['administrador']);
suporte_garantir_tabelas($pdo);

$q = trim($_GET['q'] ?? '');
$fEstado = $_GET['estado'] ?? 'pendentes';
$fCat = $_GET['categoria'] ?? '';

$cont = ['aberto' => 0, 'em_curso' => 0, 'resolvido' => 0];
foreach ($pdo->query('SELECT estado, COUNT(*) n FROM suporte_pedido GROUP BY estado') as $r) $cont[$r['estado']] = (int)$r['n'];
$st = $pdo->prepare("SELECT COUNT(*) FROM suporte_pedido WHERE estado = 'resolvido' AND data_actualizacao >= ?");
$st->execute([date('Y-m-d') . ' 00:00:00']);
$resolvidosHoje = (int)$st->fetchColumn();

$onde = ['1=1']; $p = [];
if ($fEstado === 'pendentes') $onde[] = "p.estado IN ('aberto','em_curso')";
elseif (isset(SP_ESTADOS[$fEstado])) { $onde[] = 'p.estado = ?'; $p[] = $fEstado; }
if (isset(SP_CATEGORIAS[$fCat])) { $onde[] = 'p.categoria = ?'; $p[] = $fCat; }
if ($q !== '') {
    $onde[] = '(p.nome_contacto LIKE ? OR p.contacto LIKE ? OR p.assunto LIKE ? OR p.id_pedido = ?)';
    array_push($p, "%$q%", "%$q%", "%$q%", (int)ltrim($q, '#'));
}
$cond = implode(' AND ', $onde);

$st = $pdo->prepare("SELECT COUNT(*) FROM suporte_pedido p WHERE $cond");
$st->execute($p);
$total = (int)$st->fetchColumn();
$porPagina = 12;
$paginas = max(1, (int)ceil($total / $porPagina));
$pagina = min(max(1, (int)($_GET['pagina'] ?? 1)), $paginas);
$offset = ($pagina - 1) * $porPagina;

$st = $pdo->prepare("SELECT p.*, (SELECT m.texto FROM suporte_mensagem m WHERE m.id_pedido = p.id_pedido ORDER BY m.id_mensagem DESC LIMIT 1) AS ultima,
                            (SELECT m.autor FROM suporte_mensagem m WHERE m.id_pedido = p.id_pedido ORDER BY m.id_mensagem DESC LIMIT 1) AS ultimo_autor
                     FROM suporte_pedido p WHERE $cond
                     ORDER BY FIELD(p.estado, 'aberto', 'em_curso', 'resolvido'), p.data_actualizacao DESC LIMIT $porPagina OFFSET $offset");
$st->execute($p);
$lista = $st->fetchAll();

function sp_ligacao_pagina(int $n): string { $qs = $_GET; $qs['pagina'] = $n; return '?' . http_build_query($qs); }

$tituloPagina = 'Suporte';
$migalhas = ['Painel Admin', 'Suporte ao passageiro'];
$paginaActiva = 'administrador/suporte.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina"><div>
  <h1 class="titulo-pagina">Suporte ao passageiro</h1>
  <p class="subtitulo-pagina">Responda a quem tem dificuldades com a conta, pagamentos, bilhetes ou na procura de uma transportadora.</p></div>
  <a class="btn btn-primario" href="<?= e(url('administrador/passageiro.php')) ?>">Procurar passageiro</a></div>

<div class="estatisticas" style="grid-template-columns:repeat(3,1fr)">
  <div class="estat destaque"><small>Por atender</small><strong><?= $cont['aberto'] ?></strong><span class="neutro">pedidos abertos</span></div>
  <div class="estat"><small>Em curso</small><strong><?= $cont['em_curso'] ?></strong><span class="neutro">a ser tratados</span></div>
  <div class="estat"><small>Resolvidos</small><strong><?= $cont['resolvido'] ?></strong><span class="var"><?= $resolvidosHoje ?> hoje</span></div>
</div>

<section class="cartao">
  <form method="get" class="filtros">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Nome, contacto, assunto ou #número...">
    <select name="estado">
      <option value="pendentes" <?= $fEstado === 'pendentes' ? 'selected' : '' ?>>Estado: Por tratar</option>
      <option value="todos" <?= $fEstado === 'todos' ? 'selected' : '' ?>>Estado: Todos</option>
      <?php foreach (SP_ESTADOS as $k => $n): ?><option value="<?= e($k) ?>" <?= $fEstado === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?>
    </select>
    <select name="categoria"><option value="">Tipo: Todos</option>
      <?php foreach (SP_CATEGORIAS as $k => $c): ?><option value="<?= e($k) ?>" <?= $fCat === $k ? 'selected' : '' ?>><?= e($c[0]) ?></option><?php endforeach; ?></select>
    <button class="btn btn-sec btn-pequeno" type="submit">Filtrar</button>
  </form>

  <div class="tabela-rolar"><table class="tabela">
    <thead><tr><th>#</th><th>Passageiro</th><th>Pedido</th><th>Estado</th><th>Actualizado</th><th class="acoes">Acção</th></tr></thead><tbody>
    <?php if (!$lista): ?><tr><td colspan="6" style="text-align:center;color:var(--cinza);padding:30px">Nenhum pedido encontrado.</td></tr><?php endif; ?>
    <?php foreach ($lista as $r): ?>
      <tr>
        <td><b>#<?= (int)$r['id_pedido'] ?></b></td>
        <td><strong><?= e($r['nome_contacto']) ?></strong><br><small><?= e($r['contacto']) ?></small>
          <?php if (!$r['id_passageiro']): ?><br><small class="sp-sem-conta">Sem conta associada</small><?php endif; ?></td>
        <td style="max-width:320px"><b><?= e($r['assunto']) ?></b><br>
          <small class="sp-trecho"><?= $r['ultimo_autor'] === 'passageiro' && $r['estado'] !== 'resolvido' ? '● ' : '' ?><?= e(mb_strimwidth((string)$r['ultima'], 0, 90, '…')) ?></small></td>
        <td><?= sp_etq($r['estado']) ?></td>
        <td><small><?= e(sp_data($r['data_actualizacao'])) ?></small></td>
        <td class="acoes"><a class="btn-tabela" href="<?= e(url('administrador/suporte_ver.php?id=' . (int)$r['id_pedido'])) ?>">Abrir</a></td>
      </tr>
    <?php endforeach; ?></tbody></table></div>

  <div class="rodape-tabela">
    <span><?= $total ? 'Mostrando ' . ($offset + 1) . '-' . ($offset + count($lista)) . ' de ' . $total . ' pedidos' : 'Sem resultados' ?></span>
    <div class="paginacao">
      <?php if ($pagina > 1): ?><a class="btn btn-sec btn-pequeno" href="<?= e(sp_ligacao_pagina($pagina - 1)) ?>">Anterior</a><?php endif; ?>
      <?php if ($pagina < $paginas): ?><a class="btn btn-sec btn-pequeno" href="<?= e(sp_ligacao_pagina($pagina + 1)) ?>">Seguinte</a><?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php';
