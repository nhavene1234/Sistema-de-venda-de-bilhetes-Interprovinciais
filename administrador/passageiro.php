<?php
// administrador/passageiro.php - ficha do passageiro: procurar, ajudar (repor senha, desbloquear, corrigir telefone,
// reemitir bilhete) e registar pedidos de ajuda feitos por telefone ou presencialmente.
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/suporte.php';
exigir_perfil(['administrador']);
suporte_garantir_tabelas($pdo);
$eu = utilizador_logado();

function carregar_passageiro(PDO $pdo, int $id): ?array {
    $st = $pdo->prepare("SELECT * FROM utilizador WHERE id_utilizador = ? AND perfil = 'passageiro'");
    $st->execute([$id]);
    return $st->fetch() ?: null;
}

// ---------- acções ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $voltar = (string)($_POST['voltar'] ?? '');
    if (!preg_match('#^administrador/[a-z_]+\.php(\?[A-Za-z0-9=&_%.-]*)?$#', $voltar)) $voltar = 'administrador/passageiro.php?id=' . $id;
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }

    $p = carregar_passageiro($pdo, $id);
    if (!$p) { flash('erro', 'Passageiro não encontrado.'); redirecionar('administrador/passageiro.php'); }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'repor_senha') {
        $tmp = gerar_senha_temporaria();
        $pdo->prepare('UPDATE utilizador SET senha = ?, tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
            ->execute([password_hash($tmp, PASSWORD_DEFAULT), $id]);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Palavra-passe reposta', 'utilizador', $id, $p['nome'] . ' (suporte)');
        flash('ok', 'Palavra-passe temporária de ' . $p['nome'] . ': ' . $tmp . ' — comunique-a ao passageiro e peça-lhe para a alterar em Perfil.');

    } elseif ($acao === 'alternar_estado') {
        $bloq = !(int)$p['activo'] || (!empty($p['bloqueado_ate']) && strtotime($p['bloqueado_ate']) > time());
        if ($bloq) {
            $pdo->prepare('UPDATE utilizador SET activo = 1, bloqueado_ate = NULL, tentativas_falhadas = 0 WHERE id_utilizador = ?')->execute([$id]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Utilizador desbloqueado', 'utilizador', $id, $p['nome']);
            flash('ok', 'Conta de ' . $p['nome'] . ' desbloqueada.');
        } else {
            $pdo->prepare('UPDATE utilizador SET activo = 0 WHERE id_utilizador = ?')->execute([$id]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Utilizador bloqueado', 'utilizador', $id, $p['nome']);
            flash('ok', 'Conta de ' . $p['nome'] . ' bloqueada.');
        }

    } elseif ($acao === 'actualizar_telefone') {
        $tel = telefone_normalizar(trim($_POST['telefone'] ?? ''));
        if ($tel === null) {
            flash('erro', MSG_TELEFONE);
        } else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE telefone = ? AND id_utilizador <> ?');
            $st->execute([telefone_guardar($tel), $id]);
            if ($st->fetchColumn()) {
                flash('erro', 'Esse telefone já está associado a outra conta.');
            } else {
                $pdo->prepare('UPDATE utilizador SET telefone = ? WHERE id_utilizador = ?')->execute([telefone_guardar($tel), $id]);
                registar_log($pdo, (int)$eu['id_utilizador'], 'Telefone corrigido (suporte)', 'utilizador', $id, $p['nome']);
                flash('ok', 'Telefone actualizado.');
            }
        }

    } elseif ($acao === 'reemitir_qr') {
        $st = $pdo->prepare("SELECT rs.id_reserva, rs.referencia, v.data, v.hora_partida FROM reserva rs
                             JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN bilhete b ON b.id_reserva = rs.id_reserva
                             WHERE rs.id_reserva = ? AND rs.id_passageiro = ? AND rs.estado = 'confirmada' AND b.estado = 'valido'");
        $st->execute([(int)($_POST['id_reserva'] ?? 0), $id]);
        $r = $st->fetch();
        if (!$r || strtotime($r['data'] . ' ' . $r['hora_partida']) <= time()) {
            flash('erro', 'Este bilhete não pode ser reemitido (já foi usado, cancelado ou a viagem já partiu).');
        } else {
            $novo = $r['referencia'] . '-' . strtoupper(bin2hex(random_bytes(8)));
            $pdo->prepare("UPDATE bilhete SET codigo_qr = ? WHERE id_reserva = ? AND estado = 'valido'")->execute([$novo, $r['id_reserva']]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Bilhete reemitido (novo QR)', 'reserva', (int)$r['id_reserva'], $r['referencia'] . ' · ' . $p['nome']);
            flash('ok', 'Novo QR emitido para a reserva ' . $r['referencia'] . '. O código anterior deixou de ser válido.');
        }

    } elseif ($acao === 'registar_pedido') {
        $cat = $_POST['categoria'] ?? '';
        $texto = sp_texto_valido($_POST['texto'] ?? '');
        if (!isset(SP_CATEGORIAS[$cat]) || !$texto) {
            flash('erro', 'Escolha o tipo de problema e descreva-o (mínimo 5 caracteres).');
        } else {
            $idP = sp_criar_pedido($pdo, $id, $p['nome'], (string)($p['telefone'] ?: $p['email']), $cat, null,
                                   'Pedido registado pelo suporte (telefone/presencial): ' . $texto, 'administrador', (int)$eu['id_utilizador']);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Pedido de ajuda registado', 'suporte_pedido', $idP, $p['nome']);
            flash('ok', 'Pedido #' . $idP . ' registado.');
            redirecionar('administrador/suporte_ver.php?id=' . $idP);
        }
    }
    redirecionar($voltar);
}

// ---------- ecrã ----------
$id = (int)($_GET['id'] ?? 0);
$p = $id ? carregar_passageiro($pdo, $id) : null;
$tituloPagina = 'Passageiros';
$migalhas = ['Painel Admin', 'Passageiros'];
$paginaActiva = 'administrador/passageiro.php';
require __DIR__ . '/../includes/painel_inicio.php';

if ($p): ?>
<div class="cabecalho-pagina"><div>
  <h1 class="titulo-pagina">Ficha do passageiro</h1>
  <p class="subtitulo-pagina">Ajude o passageiro: acesso à conta, pagamentos e bilhetes.</p></div>
  <a class="btn btn-sec" href="<?= e(url('administrador/passageiro.php')) ?>">← Procurar outro</a></div>

<div class="sp-grelha">
  <div><?php sp_ficha_passageiro($pdo, $p, 'administrador/passageiro.php?id=' . $id); ?></div>
  <div>
    <section class="cartao">
      <h2>Registar pedido de ajuda</h2>
      <p class="sub">Para quando o passageiro liga ou aparece pessoalmente. Fica registado e ele vê as respostas na app.</p>
      <form method="post" class="sp-form">
        <?= csrf_campo() ?><input type="hidden" name="acao" value="registar_pedido"><input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="voltar" value="administrador/passageiro.php?id=<?= $id ?>">
        <div class="campo"><label>Tipo de problema</label><select name="categoria" required>
          <?php foreach (SP_CATEGORIAS as $k => $c): ?><option value="<?= e($k) ?>"><?= e($c[0]) ?></option><?php endforeach; ?></select></div>
        <div class="campo"><label>O que o passageiro relatou</label><textarea name="texto" rows="4" maxlength="2000" required></textarea></div>
        <button class="btn btn-primario" type="submit">Registar pedido</button>
      </form>
    </section>
    <?php
      $st = $pdo->prepare('SELECT id_pedido, assunto, estado, data_actualizacao FROM suporte_pedido WHERE id_passageiro = ? ORDER BY data_actualizacao DESC LIMIT 8');
      $st->execute([$id]); $ped = $st->fetchAll();
    ?>
    <section class="cartao" style="margin-top:20px">
      <h2>Pedidos de ajuda</h2>
      <?php if (!$ped): ?><p class="sp-nota">Sem pedidos até agora.</p><?php endif; ?>
      <?php foreach ($ped as $x): ?>
        <a class="sp-pedido-linha" href="<?= e(url('administrador/suporte_ver.php?id=' . (int)$x['id_pedido'])) ?>">
          <span><b>#<?= (int)$x['id_pedido'] ?></b> <?= e($x['assunto']) ?><small><?= e(sp_data($x['data_actualizacao'])) ?></small></span><?= sp_etq($x['estado']) ?></a>
      <?php endforeach; ?>
    </section>
  </div>
</div>

<?php else:
    $q = trim($_GET['q'] ?? '');
    $onde = "perfil = 'passageiro'"; $par = [];
    if ($q !== '') {
        $dig = preg_replace('/\D/', '', $q);
        $onde .= " AND (nome LIKE ? OR email LIKE ? OR REPLACE(telefone, ' ', '') LIKE ?)";
        $par = ["%$q%", "%$q%", $dig !== '' ? "%$dig%" : '%__nenhum__%'];
    }
    $st = $pdo->prepare("SELECT * FROM utilizador WHERE $onde ORDER BY data_criacao DESC LIMIT 15");
    $st->execute($par);
    $lista = $st->fetchAll();
?>
<div class="cabecalho-pagina"><div>
  <h1 class="titulo-pagina">Passageiros</h1>
  <p class="subtitulo-pagina">Procure um passageiro para o ajudar com a conta, pagamentos ou bilhetes.</p></div>
  <a class="btn btn-primario" href="<?= e(url('administrador/suporte.php')) ?>">Pedidos de ajuda</a></div>

<section class="cartao">
  <form method="get" class="filtros">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Nome, email ou telefone..." autofocus>
    <button class="btn btn-sec btn-pequeno" type="submit">Pesquisar</button>
  </form>
  <div class="tabela-rolar"><table class="tabela">
    <thead><tr><th>Passageiro</th><th>Telefone</th><th>Estado</th><th class="acoes">Acções</th></tr></thead><tbody>
    <?php if (!$lista): ?><tr><td colspan="4" style="text-align:center;color:var(--cinza);padding:30px">Nenhum passageiro encontrado.</td></tr><?php endif; ?>
    <?php foreach ($lista as $l): $bloq = !(int)$l['activo'] || (!empty($l['bloqueado_ate']) && strtotime($l['bloqueado_ate']) > time()); ?>
      <tr>
        <td><div class="celula-utilizador"><?= avatar_html($l) ?><div><strong><?= e($l['nome']) ?></strong><small><?= e($l['email']) ?></small></div></div></td>
        <td><?= e($l['telefone'] ?: '—') ?></td>
        <td><span class="etq <?= $bloq ? 'etq-bloqueado' : 'etq-activo' ?>"><?= $bloq ? 'Bloqueado' : 'Activo' ?></span></td>
        <td class="acoes"><a class="btn-tabela" href="<?= e(url('administrador/passageiro.php?id=' . (int)$l['id_utilizador'])) ?>">Abrir ficha</a></td>
      </tr>
    <?php endforeach; ?></tbody></table></div>
  <p class="sp-nota" style="margin-top:12px"><?= $q === '' ? 'A mostrar os passageiros mais recentes.' : count($lista) . ' resultado(s).' ?></p>
</section>
<?php endif;
require __DIR__ . '/../includes/painel_fim.php';
