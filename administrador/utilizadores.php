<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_perfil(['administrador']);
$eu = utilizador_logado();
$filtro = filtro_transportadora();
$perfisValidos = ['passageiro', 'bilheteiro', 'cobrador', 'gestor', 'administrador'];

// ---------- acções ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        flash('erro', 'Pedido inválido. Tente novamente.');
        redirecionar('administrador/utilizadores.php');
    }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        $nome   = trim($_POST['nome'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $perfil = $_POST['perfil'] ?? '';
        $idT    = (int)($_POST['id_transportadora'] ?? 0);
        $tel    = trim($_POST['telefone'] ?? '');
        $telN   = $tel === '' ? null : telefone_normalizar($tel);

        if (mb_strlen($nome) < 3) {
            flash('erro', 'Indique o nome completo do utilizador.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('erro', 'Indique um email válido.');
        } elseif ((CONTACTO_OBRIGATORIO || $tel !== '') && $telN === null) {
            flash('erro', MSG_TELEFONE);
        } elseif (!in_array($perfil, ['bilheteiro', 'cobrador', 'gestor', 'administrador'], true)) {
            flash('erro', 'Escolha um perfil válido.');
        } else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ?');
            $st->execute([$email]);
            if ($st->fetchColumn() > 0) {
                flash('erro', 'Já existe um utilizador com este email.');
            } else {
                if ($perfil === 'administrador') {
                    $idT = null;
                } elseif ($perfil === 'cobrador' && $idT <= 0) {
                    $idT = null;   // fica sem transportadora: é o gestor que o associa (menu Cobradores)
                } else {
                    $st = $pdo->prepare('SELECT COUNT(*) FROM transportadora WHERE id_transportadora = ?');
                    $st->execute([$idT]);
                    if (!$st->fetchColumn()) { flash('erro', 'Escolha a transportadora do utilizador.'); redirecionar('administrador/utilizadores.php'); }
                }
                                $tmp = gerar_senha_temporaria();
                $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
                if ($driver === 'pgsql') {
                    $stIns = $pdo->prepare('INSERT INTO utilizador (nome, email, telefone, senha, perfil, id_transportadora) VALUES (?,?,?,?,?,?) RETURNING id_utilizador');
                    $stIns->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, password_hash($tmp, PASSWORD_DEFAULT), $perfil, $idT]);
                    $novoId = (int)$stIns->fetchColumn();
                } else {
                    $pdo->prepare('INSERT INTO utilizador (nome, email, telefone, senha, perfil, id_transportadora) VALUES (?,?,?,?,?,?)')
                        ->execute([$nome, $email, $telN ? telefone_guardar($telN) : null, password_hash($tmp, PASSWORD_DEFAULT), $perfil, $idT]);
                    $novoId = (int)$pdo->lastInsertId();
                }
                registar_log($pdo, (int)$eu['id_utilizador'], 'Utilizador criado', 'utilizador', $novoId, "$nome ($perfil)");

                flash('ok', "Utilizador criado. Palavra-passe temporária: $tmp — comunique-a ao utilizador e peça-lhe para a alterar no primeiro acesso."
                    . ($perfil === 'cobrador' && $idT === null ? ' O cobrador ainda não tem transportadora: o gestor da transportadora deve associá-lo (menu Cobradores).' : ''));
            }
        }

    } elseif (in_array($acao, ['alternar_estado', 'repor_senha'], true)) {
        $id = (int)($_POST['id'] ?? 0);
        $st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
        $st->execute([$id]);
        $alvo = $st->fetch();

        if (!$alvo) {
            flash('erro', 'Utilizador não encontrado.');
        } elseif ($id === (int)$eu['id_utilizador']) {
            flash('erro', 'Não pode aplicar esta acção à sua própria conta.');
        } elseif ($acao === 'alternar_estado') {
            $bloqueado = !(int)$alvo['activo'] || ($alvo['bloqueado_ate'] && strtotime($alvo['bloqueado_ate']) > time());
            if ($bloqueado) {
                $pdo->prepare('UPDATE utilizador SET activo = 1, bloqueado_ate = NULL, tentativas_falhadas = 0 WHERE id_utilizador = ?')->execute([$id]);
                registar_log($pdo, (int)$eu['id_utilizador'], 'Utilizador desbloqueado', 'utilizador', $id, $alvo['nome']);
                flash('ok', 'Utilizador ' . $alvo['nome'] . ' desbloqueado.');
            } else {
                $pdo->prepare('UPDATE utilizador SET activo = 0 WHERE id_utilizador = ?')->execute([$id]);
                registar_log($pdo, (int)$eu['id_utilizador'], 'Utilizador bloqueado', 'utilizador', $id, $alvo['nome']);
                flash('ok', 'Utilizador ' . $alvo['nome'] . ' bloqueado.');
            }
        } else { // repor_senha
            $tmp = gerar_senha_temporaria();
            $pdo->prepare('UPDATE utilizador SET senha = ?, tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
                ->execute([password_hash($tmp, PASSWORD_DEFAULT), $id]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Palavra-passe reposta', 'utilizador', $id, $alvo['nome']);
            flash('ok', 'Nova palavra-passe temporária de ' . $alvo['nome'] . ': ' . $tmp);
        }
    }
    redirecionar('administrador/utilizadores.php');
}

// ---------- listagem com filtros ----------
$q = trim($_GET['q'] ?? '');
$fPerfil  = $_GET['perfil'] ?? '';
$fEstado  = $_GET['estado'] ?? '';
$agora = date('Y-m-d H:i:s');

$onde = ['1=1'];
$p = [];
if ($q !== '') { $onde[] = '(u.nome LIKE ? OR u.email LIKE ?)'; $p[] = "%$q%"; $p[] = "%$q%"; }
if (in_array($fPerfil, $perfisValidos, true)) { $onde[] = 'u.perfil = ?'; $p[] = $fPerfil; }
if ($filtro) { $onde[] = 'u.id_transportadora = ?'; $p[] = $filtro; }
if ($fEstado === 'activo') { $onde[] = 'u.activo = 1 AND (u.bloqueado_ate IS NULL OR u.bloqueado_ate <= ?)'; $p[] = $agora; }
if ($fEstado === 'bloqueado') { $onde[] = '(u.activo = 0 OR u.bloqueado_ate > ?)'; $p[] = $agora; }
$cond = implode(' AND ', $onde);

$st = $pdo->prepare("SELECT COUNT(*) FROM utilizador u WHERE $cond");
$st->execute($p);
$total = (int)$st->fetchColumn();

$porPagina = 10;
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$paginas = max(1, (int)ceil($total / $porPagina));
$pagina = min($pagina, $paginas);
$offset = ($pagina - 1) * $porPagina;

$st = $pdo->prepare("SELECT u.*, t.nome AS transportadora FROM utilizador u
                     LEFT JOIN transportadora t ON t.id_transportadora = u.id_transportadora
                     WHERE $cond ORDER BY u.data_criacao DESC, u.id_utilizador DESC LIMIT $porPagina OFFSET $offset");
$st->execute($p);
$lista = $st->fetchAll();
$transportadoras = $pdo->query('SELECT id_transportadora, nome FROM transportadora WHERE activa = 1 ORDER BY nome')->fetchAll();
$todasTransp = $pdo->query('SELECT id_transportadora, nome FROM transportadora ORDER BY nome')->fetchAll();

function ligacao_pagina(int $n): string {
    $qs = $_GET; $qs['pagina'] = $n;
    return '?' . http_build_query($qs);
}

$tituloPagina = 'Utilizadores';
$migalhas = ['Painel Admin', 'Gerir utilizadores'];
$paginaActiva = 'administrador/utilizadores.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div>
    <h1 class="titulo-pagina">Utilizadores do Sistema</h1>
    <p class="subtitulo-pagina">Gerencie permissões, crie contas para funcionários e controle acessos.</p>
  </div>
  <button class="btn btn-primario" type="button" data-abrir="modal-utilizador">+ Novo utilizador</button>
</div>

<section class="cartao">
  <form method="get" class="filtros">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Pesquisar por nome ou email...">
    <select name="perfil">
      <option value="">Perfil: Todos</option>
      <?php foreach ($perfisValidos as $pf): ?>
        <option value="<?= $pf ?>" <?= $fPerfil === $pf ? 'selected' : '' ?>><?= e(nome_perfil($pf)) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="transportadora">
      <option value="0">Transportadora: Todas</option>
      <?php foreach ($todasTransp as $t): ?>
        <option value="<?= (int)$t['id_transportadora'] ?>" <?= $filtro === (int)$t['id_transportadora'] ? 'selected' : '' ?>><?= e($t['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="estado">
      <option value="">Estado: Todos</option>
      <option value="activo" <?= $fEstado === 'activo' ? 'selected' : '' ?>>Activo</option>
      <option value="bloqueado" <?= $fEstado === 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
    </select>
    <button class="btn btn-sec btn-pequeno" type="submit">Pesquisar</button>
  </form>

  <div class="tabela-rolar">
  <table class="tabela">
    <thead><tr><th>Nome / Email</th><th>Perfil</th><th>Transportadora</th><th>Estado</th><th class="acoes">Acções</th></tr></thead>
    <tbody>
    <?php if (!$lista): ?>
      <tr><td colspan="5" style="text-align:center;color:var(--cinza);padding:30px">Nenhum utilizador encontrado.</td></tr>
    <?php endif; ?>
    <?php foreach ($lista as $l):
        $bloq = !(int)$l['activo'] || ($l['bloqueado_ate'] && strtotime($l['bloqueado_ate']) > time());
        $eMeu = (int)$l['id_utilizador'] === (int)$eu['id_utilizador'];
    ?>
      <tr>
        <td><div class="celula-utilizador"><?= avatar_html($l) ?>
          <div><strong><?= e($l['nome']) ?></strong><small><?= e($l['email']) ?></small></div></div></td>
        <td><span class="etq <?= classe_perfil($l['perfil']) ?>"><?= e(nome_perfil($l['perfil'])) ?></span></td>
        <td><?= e($l['transportadora'] ?? '—') ?></td>
        <td><span class="etq <?= $bloq ? 'etq-bloqueado' : 'etq-activo' ?>"><?= $bloq ? 'Bloqueado' : 'Activo' ?></span></td>
        <td class="acoes">
          <?php if (!$eMeu): ?>
            <form method="post" data-confirmar="<?= $bloq ? 'Desbloquear' : 'Bloquear' ?> <?= e($l['nome']) ?>?">
              <?= csrf_campo() ?><input type="hidden" name="acao" value="alternar_estado"><input type="hidden" name="id" value="<?= (int)$l['id_utilizador'] ?>">
              <button class="btn-tabela" type="submit"><?= $bloq ? 'Desbloquear' : 'Bloquear' ?></button>
            </form>
            <form method="post" data-confirmar="Repor a palavra-passe de <?= e($l['nome']) ?>? Será gerada uma palavra-passe temporária.">
              <?= csrf_campo() ?><input type="hidden" name="acao" value="repor_senha"><input type="hidden" name="id" value="<?= (int)$l['id_utilizador'] ?>">
              <button class="btn-tabela" type="submit">Repor Senha</button>
            </form>
          <?php else: ?><small class="pequeno">(a sua conta)</small><?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>

  <div class="rodape-tabela">
    <span><?= $total ? 'Mostrando ' . ($offset + 1) . '-' . ($offset + count($lista)) . ' de ' . formatar_numero($total) . ' utilizadores' : 'Sem resultados' ?></span>
    <div class="paginacao">
      <?php if ($pagina > 1): ?><a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_pagina($pagina - 1)) ?>">Anterior</a><?php endif; ?>
      <?php if ($pagina < $paginas): ?><a class="btn btn-sec btn-pequeno" href="<?= e(ligacao_pagina($pagina + 1)) ?>">Seguinte</a><?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal: novo utilizador -->
<dialog class="modal" id="modal-utilizador">
  <h2>Novo utilizador</h2>
  <p class="sub">Preencha os dados abaixo para configurar uma nova credencial no sistema.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="criar">
    <div class="campo"><label>Nome completo <span class="requerido">*</span></label>
      <input type="text" name="nome" placeholder="Ex: Manuel Francisco Zoca" required></div>
    <div class="campo"><label>Endereço de Email <span class="requerido">*</span></label>
      <input type="email" name="email" placeholder="Ex: m.zoca@moztransport.co.mz" required></div>
    <div class="campo"><label>Contacto telefónico <?= CONTACTO_OBRIGATORIO ? '<span class="requerido">*</span>' : '' ?></label>
      <input type="tel" name="telefone" placeholder="Ex: 841234567" data-contacto <?= CONTACTO_OBRIGATORIO ? 'required' : '' ?>></div>
    <div class="campo"><label>Perfil <span class="requerido">*</span></label>
      <select name="perfil" id="novo-perfil">
        <option value="bilheteiro">Bilheteiro</option>
        <option value="cobrador">Cobrador</option>
        <option value="gestor" selected>Gestor</option>
        <option value="administrador">Administrador</option>
      </select></div>
    <div class="campo" id="grupo-transp"><label>Transportadora <span class="requerido">*</span></label>
      <select name="id_transportadora" id="nova-transp">
        <option value="">Seleccione...</option>
        <?php foreach ($transportadoras as $t): ?>
          <option value="<?= (int)$t['id_transportadora'] ?>"><?= e($t['nome']) ?></option>
        <?php endforeach; ?>
      </select></div>
    <div class="caixa-info" id="info-perfil"></div>
    <div class="modal-acoes">
      <button class="btn btn-sec" type="button" data-fechar>Cancelar</button>
      <button class="btn btn-primario" type="submit">Guardar</button>
    </div>
  </form>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
