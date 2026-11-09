<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_rotas');
$idT = $transp['id'];

$st = $pdo->prepare("SELECT v.*, ro.origem, ro.destino, au.codigo_frota, au.matricula, au.capacidade_total, m.nome AS motorista, cu.nome AS cobrador
                     FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro au ON au.id_autocarro = v.id_autocarro
                     LEFT JOIN motorista m ON m.id_motorista = v.id_motorista LEFT JOIN utilizador cu ON cu.id_utilizador = v.id_cobrador
                     WHERE v.id_viagem = ? AND ro.id_transportadora = ?");
$st->execute([(int)($_GET['id'] ?? 0), $idT]);
$v = $st->fetch();
if (!$v) acesso_restrito('Só tem permissão para ver viagens da ' . $transp['nome'] . '.');

libertar_reservas_expiradas($pdo);
[$filas, $ocupados] = carregar_mapa($pdo, $v);
$total = 0; foreach ($filas as $f) $total += count($f);

$st = $pdo->prepare("SELECT a.numero, rs.estado, COALESCE(rs.nome_passageiro, us.nome) AS passageiro
                     FROM reserva rs JOIN assento a ON a.id_assento = rs.id_assento LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                     WHERE rs.id_viagem = ? AND rs.estado IN ('pendente','confirmada')");
$st->execute([$v['id_viagem']]);
$reservas = $st->fetchAll();
usort($reservas, fn($a, $b) => strnatcmp($a['numero'], $b['numero']));
$livres = max(0, $total - count($ocupados));
$venda = $v['estado'] === 'agendada' && partida_ts($v) > time();

$tituloPagina = 'Lugares da viagem';
$migalhas = ['Viagens', 'Lugares'];
$paginaActiva = 'gestor/viagens.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina"><?= e($v['origem'] . ' → ' . $v['destino']) ?></h1>
    <p class="subtitulo-pagina"><?= e(date('d/m/Y', strtotime($v['data']))) ?> às <?= e(substr($v['hora_partida'], 0, 5)) ?> · autocarro <?= e($v['codigo_frota'] ?: $v['matricula']) ?>
      · <?= $venda ? 'venda aberta até à hora de partida' : 'venda encerrada' ?></p></div>
  <a class="btn btn-sec" href="<?= e(url('gestor/viagens.php?' . http_build_query(['inicio' => $v['data'], 'fim' => $v['data']]))) ?>">← Voltar às viagens</a>
</div>

<div class="duas-colunas">
  <section class="cartao" style="text-align:center">
    <h2 style="font-size:18px">Mapa de lugares</h2>
    <p class="pequeno"><b><?= $livres ?></b> livres · <b><?= count($ocupados) ?></b> ocupados · <?= $total ?> lugares</p>
    <?= mapa_autocarro_html($filas, $ocupados, false) ?>
  </section>
  <aside class="cartao">
    <h2 style="font-size:16px">Lugares ocupados</h2>
    <?php if (!$reservas): ?><p class="pequeno">Ainda não há lugares reservados nesta viagem.</p><?php else: ?>
    <table class="tabela"><thead><tr><th>Lugar</th><th>Passageiro</th><th>Estado</th></tr></thead><tbody>
      <?php foreach ($reservas as $r): ?>
        <tr><td><b><?= e($r['numero']) ?></b></td><td><?= e($r['passageiro'] ?: '—') ?></td>
          <td><span class="etq-g <?= $r['estado'] === 'confirmada' ? 'etq-pago' : 'etq-pendente' ?>"><?= $r['estado'] === 'confirmada' ? 'Confirmada' : 'Pendente' ?></span></td></tr>
      <?php endforeach; ?>
    </tbody></table>
    <?php endif; ?>
    <p class="pequeno" style="margin-top:12px">Motorista: <b><?= e($v['motorista'] ?: '—') ?></b> · Cobrador: <b><?= e($v['cobrador'] ?: '—') ?></b></p>
  </aside>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
