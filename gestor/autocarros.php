<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();
$estados = ['disponivel' => 'Disponível', 'manutencao' => 'Em manutenção', 'inactivo' => 'Inactivo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/autocarros.php'); }
    $st = $pdo->prepare('SELECT * FROM autocarro WHERE id_autocarro = ? AND id_transportadora = ?');
    $st->execute([(int)($_POST['id'] ?? 0), $idT]);
    $a = $st->fetch();
    if (!$a) acesso_restrito('Só tem permissão para gerir autocarros da ' . $transp['nome'] . '.');

    $novo = $_POST['estado'] ?? '';
    if (!isset($estados[$novo])) {
        flash('erro', 'Estado inválido.');
    } elseif ($novo === 'disponivel' && $a['validade_inspecao'] !== null && $a['validade_inspecao'] < date('Y-m-d')) {
        flash('erro', 'A inspecção do autocarro ' . $a['codigo_frota'] . ' expirou. Renove-a antes de o disponibilizar.');
    } else {
        $pdo->prepare('UPDATE autocarro SET estado = ?, activo = ? WHERE id_autocarro = ?')
            ->execute([$novo, $novo === 'disponivel' ? 1 : 0, $a['id_autocarro']]);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Estado do autocarro alterado', 'autocarro', (int)$a['id_autocarro'], $a['codigo_frota'] . ' → ' . $estados[$novo]);
        flash('ok', 'Autocarro ' . $a['codigo_frota'] . ': ' . $estados[$novo] . '.');
    }
    redirecionar('gestor/autocarros.php');
}

$st = $pdo->prepare("SELECT a.*, (SELECT COUNT(*) FROM viagem v WHERE v.id_autocarro = a.id_autocarro AND v.estado = 'agendada' AND v.data >= CURRENT_DATE) AS viagens
                     FROM autocarro a WHERE a.id_transportadora = ? ORDER BY a.codigo_frota");

$st->execute([$idT]);
$lista = $st->fetchAll();

$tituloPagina = 'Autocarros';
$migalhas = ['Autocarros', 'Frota da ' . $transp['nome']];
$paginaActiva = 'gestor/autocarros.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Autocarros</h1>
    <p class="subtitulo-pagina">Frota da <?= e($transp['nome']) ?>: capacidade, comodidades, inspecção e disponibilidade.</p></div>
  <a class="btn btn-verde" href="<?= e(url('gestor/autocarro_novo.php')) ?>">+ Adicionar autocarro</a>
</div>

<section class="cartao">
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Código</th><th>Veículo</th><th>Serviço</th><th>Lugares</th><th>Comodidades</th><th>Inspecção</th><th>Estado</th><th>Viagens</th><th>Livrete</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="9" style="text-align:center;color:var(--cinza);padding:28px">Ainda não tem autocarros. Use «Adicionar autocarro».</td></tr><?php endif; ?>
    <?php foreach ($lista as $a):
        [$vt, $vc] = estado_validade($a['validade_inspecao']);
        $com = array_filter([$a['ar_condicionado'] ? 'Ar condicionado' : null, $a['wifi'] ? 'Wi-Fi' : null, $a['usb'] ? 'USB' : null]); ?>
      <tr>
        <td><b><?= e($a['codigo_frota']) ?></b><span class="sub-linha mono"><?= e($a['matricula']) ?></span></td>
        <td><b><?= e(trim(($a['marca'] ?? '') . ' ' . ($a['modelo'] ?? ''))) ?: '—' ?></b><span class="sub-linha"><?= $a['ano_fabrico'] ? (int)$a['ano_fabrico'] : '—' ?></span></td>
        <td><?= e($a['tipo_servico'] ?? '—') ?></td>
        <td><b><?= (int)$a['capacidade_total'] ?></b><span class="sub-linha"><?= e(str_replace('+', ' + ', $a['configuracao_assentos'])) ?></span></td>
        <td><?= $com ? e(implode(' · ', $com)) : '—' ?></td>
        <td><?= $a['validade_inspecao'] ? e(date('d/m/Y', strtotime($a['validade_inspecao']))) : '—' ?><span class="sub-linha"><span class="etq-g <?= $vc ?>"><?= e($vt) ?></span></span></td>
        <td><form method="post"><?= csrf_campo() ?><input type="hidden" name="id" value="<?= (int)$a['id_autocarro'] ?>">
          <select class="select-estado" name="estado" onchange="this.form.submit()">
            <?php foreach ($estados as $k => $n): ?><option value="<?= $k ?>" <?= $a['estado'] === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?>
          </select></form></td>
        <td><?= (int)$a['viagens'] ?></td>
        <td><?= $a['livrete'] ? '<a href="' . e(url('gestor/documento.php?tipo=livrete&id=' . (int)$a['id_autocarro'])) . '" target="_blank">Ver</a>' : '—' ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
