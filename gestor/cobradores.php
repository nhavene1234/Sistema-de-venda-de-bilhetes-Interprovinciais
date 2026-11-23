<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
require_once __DIR__ . '/../includes/cobradores.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar('gestor/cobradores.php'); }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        // o gestor cria a conta do cobrador já ligada à sua transportadora (não depende do Administrador)
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['telefone'] ?? '');
        $telN = $tel === '' ? null : telefone_normalizar($tel);
        $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ?');
        $st->execute([$email]);
        if (mb_strlen($nome) < 3) {
            flash('erro', 'Indique o nome completo do cobrador.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('erro', 'Indique um email válido.');
        } elseif ((CONTACTO_OBRIGATORIO || $tel !== '') && $telN === null) {
            flash('erro', MSG_TELEFONE);
        } elseif ($st->fetchColumn() > 0) {
            flash('erro', 'Já existe uma conta com este email. Se for um cobrador sem transportadora, use «Associar existente».');
        } else {
                        $tmp = gerar_senha_temporaria();
            $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'pgsql') {
                $stIns = $pdo->prepare("INSERT INTO utilizador (nome, email, telefone, senha, perfil, id_transportadora) VALUES (?,?,?,?,'cobrador',?) RETURNING id_utilizador");
                $stIns->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, password_hash($tmp, PASSWORD_DEFAULT), $idT]);
                $novoIdC = (int)$stIns->fetchColumn();
            } else {
                $pdo->prepare("INSERT INTO utilizador (nome, email, telefone, senha, perfil, id_transportadora) VALUES (?,?,?,?,'cobrador',?)")
                    ->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, password_hash($tmp, PASSWORD_DEFAULT), $idT]);
                $novoIdC = (int)$pdo->lastInsertId();
            }
            registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador criado', 'utilizador', $novoIdC, $nome . ' → ' . $transp['nome']);

            flash('ok', "Cobrador $nome criado. Palavra-passe temporária: $tmp — comunique-a ao cobrador; ele pode alterá-la no seu perfil. Atribua-lhe agora as viagens.");
        }

    } elseif ($acao === 'editar') {
        $c = cobrador_da_transportadora($pdo, (int)($_POST['id'] ?? 0), $idT);
        if (!$c) acesso_restrito('Só tem permissão para gerir cobradores da ' . $transp['nome'] . '.');
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['telefone'] ?? '');
        $telN = $tel === '' ? null : telefone_normalizar($tel);
        $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ? AND id_utilizador <> ?');
        $st->execute([$email, $c['id_utilizador']]);
        if (mb_strlen($nome) < 3) {
            flash('erro', 'Indique o nome completo do cobrador.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('erro', 'Indique um email válido.');
        } elseif ((CONTACTO_OBRIGATORIO || $tel !== '') && $telN === null) {
            flash('erro', MSG_TELEFONE);
        } elseif ($st->fetchColumn() > 0) {
            flash('erro', 'Este email já está a ser usado por outra conta.');
        } else {
            $pdo->prepare('UPDATE utilizador SET nome = ?, email = ?, telefone = ? WHERE id_utilizador = ? AND id_transportadora = ?')
                ->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, $c['id_utilizador'], $idT]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Dados de cobrador actualizados', 'utilizador', (int)$c['id_utilizador'], $nome);
            flash('ok', 'Dados de ' . $nome . ' actualizados.');
        }

    } elseif ($acao === 'alternar_estado') {
        $c = cobrador_da_transportadora($pdo, (int)($_POST['id'] ?? 0), $idT);
        if (!$c) acesso_restrito('Só tem permissão para gerir cobradores da ' . $transp['nome'] . '.');
        $st = $pdo->prepare('SELECT activo, bloqueado_ate FROM utilizador WHERE id_utilizador = ?');
        $st->execute([$c['id_utilizador']]);
        $e = $st->fetch();
        $bloqueado = !(int)$e['activo'] || ($e['bloqueado_ate'] && strtotime($e['bloqueado_ate']) > time());
        if ($bloqueado) {
            $pdo->prepare('UPDATE utilizador SET activo = 1, bloqueado_ate = NULL, tentativas_falhadas = 0 WHERE id_utilizador = ?')->execute([$c['id_utilizador']]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador desbloqueado', 'utilizador', (int)$c['id_utilizador'], $c['nome']);
            flash('ok', $c['nome'] . ' foi desbloqueado e já pode iniciar sessão.');
        } else {
            $pdo->prepare('UPDATE utilizador SET activo = 0 WHERE id_utilizador = ?')->execute([$c['id_utilizador']]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador bloqueado', 'utilizador', (int)$c['id_utilizador'], $c['nome']);
            flash('ok', $c['nome'] . ' foi bloqueado e deixou de poder iniciar sessão.');
        }

    } elseif ($acao === 'repor_senha') {
        $c = cobrador_da_transportadora($pdo, (int)($_POST['id'] ?? 0), $idT);
        if (!$c) acesso_restrito('Só tem permissão para gerir cobradores da ' . $transp['nome'] . '.');
        $tmp = gerar_senha_temporaria();
        $pdo->prepare('UPDATE utilizador SET senha = ?, tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
            ->execute([password_hash($tmp, PASSWORD_DEFAULT), $c['id_utilizador']]);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Palavra-passe de cobrador reposta', 'utilizador', (int)$c['id_utilizador'], $c['nome']);
        flash('ok', 'Nova palavra-passe temporária de ' . $c['nome'] . ': ' . $tmp);

    } elseif ($acao === 'associar') {
        // a conta é criada pelo Administrador; o gestor liga-a à sua transportadora através do email
        $email = trim($_POST['email'] ?? '');
        $st = $pdo->prepare('SELECT id_utilizador, nome, perfil, id_transportadora FROM utilizador WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $c = $st->fetch();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('erro', 'Indique o email da conta do cobrador.');
        } elseif (!$c || $c['perfil'] !== 'cobrador') {
            flash('erro', 'Não existe nenhuma conta de cobrador com este email. A conta tem de ser criada primeiro pelo Administrador.');
        } elseif ((int)$c['id_transportadora'] === $idT) {
            flash('erro', $c['nome'] . ' já pertence à ' . $transp['nome'] . '.');
        } elseif ($c['id_transportadora'] !== null) {
            flash('erro', $c['nome'] . ' já está associado a outra transportadora. Peça ao Administrador para o libertar.');
        } else {
            $st = $pdo->prepare('UPDATE utilizador SET id_transportadora = ? WHERE id_utilizador = ? AND perfil = \'cobrador\' AND id_transportadora IS NULL');
            $st->execute([$idT, $c['id_utilizador']]);
            if ($st->rowCount() === 1) {
                registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador associado à transportadora', 'utilizador', (int)$c['id_utilizador'], $c['nome'] . ' → ' . $transp['nome']);
                flash('ok', $c['nome'] . ' foi associado à ' . $transp['nome'] . '. Atribua-lhe agora as viagens em «Atribuir viagens».');
            } else {
                flash('erro', 'Não foi possível associar o cobrador. Tente novamente.');
            }
        }

    } elseif ($acao === 'desassociar') {
        $c = cobrador_da_transportadora($pdo, (int)($_POST['id'] ?? 0), $idT);
        if (!$c) acesso_restrito('Só tem permissão para gerir cobradores da ' . $transp['nome'] . '.');
        $pdo->beginTransaction();
        try {
            // as viagens que ainda não aconteceram ficam sem cobrador; o histórico (viagens passadas) mantém-se
            $st = $pdo->prepare('UPDATE viagem SET id_cobrador = NULL WHERE id_cobrador = ? AND data >= CURRENT_DATE AND estado IN ' . VIAGEM_ESTADOS_ABERTOS);
            $st->execute([$c['id_utilizador']]);
            $libertadas = $st->rowCount();
            $pdo->prepare('UPDATE utilizador SET id_transportadora = NULL WHERE id_utilizador = ?')->execute([$c['id_utilizador']]);
            $pdo->commit();
        } catch (Throwable $ex) {
            $pdo->rollBack();
            flash('erro', 'Não foi possível desassociar o cobrador. Tente novamente.');
            redirecionar('gestor/cobradores.php');
        }
        registar_log($pdo, (int)$eu['id_utilizador'], 'Cobrador desassociado da transportadora', 'utilizador', (int)$c['id_utilizador'],
                     $c['nome'] . ' · ' . $libertadas . ' viagem(ns) libertada(s)');
        flash('ok', $c['nome'] . ' deixou de pertencer à ' . $transp['nome'] . ($libertadas ? ' e ' . $libertadas . ' viagem(ns) ficaram sem cobrador.' : '.'));
    }
    redirecionar('gestor/cobradores.php');
}

// ---------- listagem ----------
$st = $pdo->prepare("SELECT u.id_utilizador, u.nome, u.email, u.telefone, u.foto, u.activo, u.bloqueado_ate,
                            (SELECT COUNT(*) FROM viagem v WHERE v.id_cobrador = u.id_utilizador AND v.data >= CURRENT_DATE
                                AND v.estado IN " . VIAGEM_ESTADOS_ABERTOS . ") AS viagens
                     FROM utilizador u WHERE u.perfil = 'cobrador' AND u.id_transportadora = ? ORDER BY u.nome");
$st->execute([$idT]);
$lista = $st->fetchAll();

// próxima viagem (e motorista) de cada cobrador
$proxima = [];
if ($lista) {
    $ids = array_map('intval', array_column($lista, 'id_utilizador'));
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st = $pdo->prepare("SELECT v.id_cobrador, v.data, v.hora_partida, ro.origem, ro.destino, m.nome AS motorista
                         FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota LEFT JOIN motorista m ON m.id_motorista = v.id_motorista
                         WHERE v.id_cobrador IN ($in) AND v.data >= CURRENT_DATE AND v.estado IN " . VIAGEM_ESTADOS_ABERTOS . "
                         ORDER BY v.data, v.hora_partida");
    $st->execute($ids);
    foreach ($st->fetchAll() as $r) {
        if (!isset($proxima[(int)$r['id_cobrador']])) $proxima[(int)$r['id_cobrador']] = $r;
    }
}

$tituloPagina = 'Cobradores';
$migalhas = ['Painel Gestão', 'Cobradores'];
$paginaActiva = 'gestor/cobradores.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Cobradores</h1>
    <p class="subtitulo-pagina">Associe os cobradores à <?= e($transp['nome']) ?> e atribua-lhes o motorista e as viagens em que validam bilhetes.</p></div>
  <div class="botoes-cab">
    <button class="btn btn-sec" type="button" data-abrir="modal-associar">Associar existente</button>
    <button class="btn btn-verde" type="button" data-abrir="modal-novo">+ Novo cobrador</button>
  </div>
</div>

<section class="cartao">
  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Cobrador</th><th>Estado</th><th>Próxima viagem</th><th>Motorista</th><th>Viagens</th><th class="acoes">Acções</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?><tr><td colspan="6" style="text-align:center;color:var(--cinza);padding:28px">Ainda não tem cobradores. Use «Novo cobrador».</td></tr><?php endif; ?>
    <?php foreach ($lista as $c):
        $bloq = !(int)$c['activo'] || ($c['bloqueado_ate'] && strtotime($c['bloqueado_ate']) > time());
        $p = $proxima[(int)$c['id_utilizador']] ?? null; ?>
      <tr>
        <td><div class="celula-utilizador"><?= avatar_html($c) ?>
          <div><strong><?= e($c['nome']) ?></strong><small><?= e($c['email']) ?><?= $c['telefone'] ? ' · ' . e($c['telefone']) : '' ?></small></div></div></td>
        <td><span class="etq <?= $bloq ? 'etq-bloqueado' : 'etq-activo' ?>"><?= $bloq ? 'Bloqueado' : 'Activo' ?></span></td>
        <td><?php if ($p): ?><b><?= e(date('d/m', strtotime($p['data'])) . ' · ' . substr($p['hora_partida'], 0, 5)) ?></b>
              <span class="sub-linha"><?= e($p['origem'] . ' → ' . $p['destino']) ?></span><?php else: ?><span class="sub-linha" style="margin:0">Sem viagem atribuída</span><?php endif; ?></td>
        <td><?= $p && $p['motorista'] ? e($p['motorista']) : '<span class="sub-linha" style="margin:0">—</span>' ?></td>
        <td><?= (int)$c['viagens'] ?></td>
        <td class="acoes nao-quebra">
          <a class="btn-link-e verde" href="<?= e(url('gestor/cobrador_viagens.php?id=' . (int)$c['id_utilizador'])) ?>">Atribuir viagens</a>
          <button class="btn-link-e" type="button" data-abrir="modal-editar" data-editar data-id="<?= (int)$c['id_utilizador'] ?>"
                  data-nome="<?= e($c['nome']) ?>" data-email="<?= e($c['email']) ?>" data-telefone="<?= e((string)$c['telefone']) ?>">Editar</button>
          <form method="post" style="display:inline" data-confirmar="<?= $bloq ? 'Desbloquear' : 'Bloquear' ?> <?= e($c['nome']) ?>?<?= $bloq ? '' : ' Deixa de poder iniciar sessão e de validar bilhetes.' ?>">
            <?= csrf_campo() ?><input type="hidden" name="acao" value="alternar_estado"><input type="hidden" name="id" value="<?= (int)$c['id_utilizador'] ?>">
            <button class="btn-link-e <?= $bloq ? '' : 'perigo' ?>" type="submit"><?= $bloq ? 'Desbloquear' : 'Bloquear' ?></button></form>
          <form method="post" style="display:inline" data-confirmar="Gerar uma nova palavra-passe temporária para <?= e($c['nome']) ?>?">
            <?= csrf_campo() ?><input type="hidden" name="acao" value="repor_senha"><input type="hidden" name="id" value="<?= (int)$c['id_utilizador'] ?>">
            <button class="btn-link-e" type="submit">Repor senha</button></form>
          <form method="post" style="display:inline" data-confirmar="Desassociar <?= e($c['nome']) ?> da <?= e($transp['nome']) ?>? As viagens futuras ficam sem cobrador.">
            <?= csrf_campo() ?><input type="hidden" name="acao" value="desassociar"><input type="hidden" name="id" value="<?= (int)$c['id_utilizador'] ?>">
            <button class="btn-link-e perigo" type="submit">Desassociar</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
<p class="nota-rodape">ⓘ Crie aqui os seus cobradores e decida em que viagens (e com que motorista) cada um valida bilhetes. Os motoristas são adicionados em Motoristas.</p>

<!-- Editar cobrador -->
<dialog class="modal" id="modal-editar">
  <h2>Editar cobrador</h2>
  <p class="sub">Actualize os dados de contacto do cobrador.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="editar"><input type="hidden" name="id" id="ed-id">
    <div class="campo"><label>Nome completo <span class="requerido">*</span></label><input type="text" name="nome" id="ed-nome" required minlength="3"></div>
    <div class="campo"><label>Email <span class="requerido">*</span></label><input type="email" name="email" id="ed-email" required></div>
    <div class="campo"><label>Telemóvel</label><input type="tel" name="telefone" id="ed-telefone" placeholder="841234567" data-contacto <?= CONTACTO_OBRIGATORIO ? 'required' : '' ?>></div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Guardar alterações</button></div>
  </form>
</dialog>
<script>
document.querySelectorAll('[data-editar]').forEach(function (b) {
  b.addEventListener('click', function () {
    document.getElementById('ed-id').value = b.dataset.id;
    document.getElementById('ed-nome').value = b.dataset.nome;
    document.getElementById('ed-email').value = b.dataset.email;
    var edTel = document.getElementById('ed-telefone'); edTel.value = b.dataset.telefone; edTel.dispatchEvent(new Event('change'));
  });
});
</script>

<!-- Novo cobrador -->
<dialog class="modal" id="modal-novo">
  <h2>Novo cobrador</h2>
  <p class="sub">Cria a conta já ligada à <?= e($transp['nome']) ?>. É gerada uma palavra-passe temporária.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="criar">
    <div class="campo"><label>Nome completo <span class="requerido">*</span></label><input type="text" name="nome" required minlength="3"></div>
    <div class="campo"><label>Email <span class="requerido">*</span></label><input type="email" name="email" placeholder="cobrador@empresa.co.mz" required></div>
    <div class="campo"><label>Telemóvel</label><input type="tel" name="telefone" placeholder="841234567" data-contacto <?= CONTACTO_OBRIGATORIO ? 'required' : '' ?>></div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Criar cobrador</button></div>
  </form>
</dialog>

<!-- Associar cobrador -->
<dialog class="modal" id="modal-associar">
  <h2>Associar cobrador existente</h2>
  <p class="sub">Para uma conta de cobrador já criada pelo Administrador e sem transportadora. Indique o email; ele passa a pertencer à <?= e($transp['nome']) ?>.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="associar">
    <div class="campo"><label>Email do cobrador <span class="requerido">*</span></label>
      <input type="email" name="email" placeholder="Ex: cobrador@moztransport.co.mz" required></div>
    <div class="modal-acoes"><button class="btn btn-sec" type="button" data-fechar>Cancelar</button><button class="btn btn-verde" type="submit">Associar</button></div>
  </form>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
