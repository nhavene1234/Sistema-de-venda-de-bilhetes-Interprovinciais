<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();
$estados = ['disponivel' => 'Disponível', 'folga' => 'De folga', 'inactivo' => 'Inactivo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/motoristas.php'); }
    $st = $pdo->prepare('SELECT * FROM motorista WHERE id_motorista = ? AND id_transportadora = ?');
    $st->execute([(int)($_POST['id'] ?? 0), $idT]);
    $m = $st->fetch();
    if (!$m) acesso_restrito('Só tem permissão para gerir motoristas da ' . $transp['nome'] . '.');

    $novo = $_POST['estado'] ?? '';
    if (!isset($estados[$novo])) {
        flash('erro', 'Estado inválido.');
    } elseif ($novo === 'disponivel' && $m['carta_validade'] < date('Y-m-d')) {
        flash('erro', 'A carta de condução de ' . $m['nome'] . ' está expirada. Não pode ficar disponível para viagens.');
    } else {
        $pdo->prepare('UPDATE motorista SET estado = ? WHERE id_motorista = ?')->execute([$novo, $m['id_motorista']]);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Estado do motorista alterado', 'motorista', (int)$m['id_motorista'], $m['codigo'] . ' → ' . $estados[$novo]);
        flash('ok', 'Motorista ' . $m['nome'] . ': ' . $estados[$novo] . '.');
    }
    redirecionar('gestor/motoristas.php');
}

$st = $pdo->prepare("SELECT m.*, (SELECT COUNT(*) FROM viagem v WHERE v.id_motorista = m.id_motorista AND v.estado = 'agendada' AND v.data >= CURDATE()) AS viagens
                     FROM motorista m WHERE m.id_transportadora = ? ORDER BY m.codigo");
$st->execute([$idT]);
$lista = $st->fetchAll();

$tituloPagina = 'Motoristas';
$migalhas = ['Motoristas', 'Equipa da ' . $transp['nome']];
$paginaActiva = 'gestor/motoristas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Motoristas</h1>
    <p class="subtitulo-pagina">Equipa de condução da <?= e($transp['nome']) ?> e validade das cartas de condução.</p></div>
  <a class="btn btn-verde" href="<?= e(url('gestor/motorista_novo.php')) ?>">+ Adicionar motorista</a>
</div>

<section class="cartao">
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Código</th><th>Motorista</th><th>Categoria</th><th>Carta de condução</th><th>Estado</th><th>Viagens</th><th>Carta (cópia)</th><th class="acoes">Acções</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="8" style="text-align:center;color:var(--cinza);padding:28px">Ainda não tem motoristas. Use «Adicionar motorista».</td></tr><?php endif; ?>
    <?php foreach ($lista as $m): [$vt, $vc] = estado_validade($m['carta_validade']); ?>
      <tr>
        <td><b><?= e($m['codigo']) ?></b></td>
        <td><b><?= e($m['nome']) ?></b><span class="sub-linha"><?= e($m['telefone']) ?></span></td>
        <td><?= e($m['carta_categoria']) ?></td>
        <td class="mono"><?= e($m['carta_numero']) ?><span class="sub-linha">Válida até <?= e(date('d/m/Y', strtotime($m['carta_validade']))) ?> · <span class="etq-g <?= $vc ?>"><?= e($vt) ?></span></span></td>
        <td><form method="post"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$m['id_motorista'] ?>">
          <select class="select-estado" name="estado" onchange="this.form.submit()">
            <?php foreach ($estados as $k => $n): ?><option value="<?= $k ?>" <?= $m['estado'] === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?>
          </select></form></td>
        <td><?= (int)$m['viagens'] ?></td>
        <td><?= $m['carta_ficheiro'] ? '<a href="' . e(url('gestor/documento.php?tipo=carta&id=' . (int)$m['id_motorista'])) . '" target="_blank">Ver</a>' : '—' ?></td>
        <td class="acoes nao-quebra"><a class="btn-link-e verde" href="<?= e(url('gestor/motorista_novo.php?id=' . (int)$m['id_motorista'])) ?>">Editar</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
<p class="nota-rodape">ⓘ Os dados pessoais e documentos ficam disponíveis apenas à equipa de gestão da <?= e($transp['nome']) ?>.</p>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
