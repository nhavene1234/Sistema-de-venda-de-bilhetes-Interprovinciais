<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_perfil(['administrador']);
$eu = utilizador_logado();
$filtro = filtro_transportadora();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        flash('erro', 'Pedido inválido. Tente novamente.');
        redirecionar('administrador/transportadoras.php');
    }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        $nome = trim($_POST['nome'] ?? '');
        $nuit = preg_replace('/\s+/', '', $_POST['nuit'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');

        if (mb_strlen($nome) < 2) {
            flash('erro', 'Indique o nome da transportadora.');
        } elseif (!preg_match('/^\d{9}$/', $nuit)) {
            flash('erro', 'O NUIT deve ter 9 dígitos.');
        } elseif (!preg_match('/^\+?[0-9 ]{7,20}$/', $contacto)) {
            flash('erro', 'Indique um contacto telefónico válido.');
        } else {
            $st = $pdo->prepare('SELECT COUNT(*) FROM transportadora WHERE nuit = ?');
            $st->execute([$nuit]);
            if ($st->fetchColumn() > 0) {
                flash('erro', 'Já existe uma transportadora com este NUIT.');
                        } else {
                $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
                if ($driver === 'pgsql') {
                    $stIns = $pdo->prepare('INSERT INTO transportadora (nome, nuit, contacto, endereco) VALUES (?,?,?,?) RETURNING id_transportadora');
                    $stIns->execute([$nome, $nuit, $contacto, $endereco ?: null]);
                    $novoId = (int)$stIns->fetchColumn();
                } else {
                    $pdo->prepare('INSERT INTO transportadora (nome, nuit, contacto, endereco) VALUES (?,?,?,?)')
                        ->execute([$nome, $nuit, $contacto, $endereco ?: null]);
                    $novoId = (int)$pdo->lastInsertId();
                }
                registar_log($pdo, (int)$eu['id_utilizador'], 'Transportadora adicionada', 'transportadora', $novoId, $nome);
                flash('ok', "Transportadora $nome adicionada.");
            }

        }
    } elseif ($acao === 'alternar') {
        $id = (int)($_POST['id'] ?? 0);
        $st = $pdo->prepare('SELECT nome, activa FROM transportadora WHERE id_transportadora = ?');
        $st->execute([$id]);
        if ($t = $st->fetch()) {
            $nova = (int)$t['activa'] ? 0 : 1;
            $pdo->prepare('UPDATE transportadora SET activa = ? WHERE id_transportadora = ?')->execute([$nova, $id]);
            registar_log($pdo, (int)$eu['id_utilizador'], $nova ? 'Transportadora activada' : 'Transportadora desactivada', 'transportadora', $id, $t['nome']);
            flash('ok', 'Transportadora ' . $t['nome'] . ($nova ? ' activada.' : ' desactivada.'));
        }
    }
    redirecionar('administrador/transportadoras.php');
}

$sql = "SELECT t.*,
          (SELECT COUNT(*) FROM autocarro a WHERE a.id_transportadora = t.id_transportadora AND a.activo = 1) AS autocarros,
          (SELECT COUNT(*) FROM rota r WHERE r.id_transportadora = t.id_transportadora AND r.activa = 1) AS rotas,
          (SELECT u.nome FROM utilizador u WHERE u.id_transportadora = t.id_transportadora AND u.perfil = 'gestor' AND u.activo = 1
           ORDER BY u.id_utilizador LIMIT 1) AS gestor
        FROM transportadora t " . ($filtro ? 'WHERE t.id_transportadora = ' . (int)$filtro : '') . ' ORDER BY t.nome';
$lista = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Transportadoras';
$migalhas = ['Painel Admin', 'Transportadoras'];
$paginaActiva = 'administrador/transportadoras.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div>
    <h1 class="titulo-pagina">Transportadoras Registadas</h1>
    <p class="subtitulo-pagina">Faça a gestão das operadoras autorizadas a operar rotas e vender bilhetes no <?= e(APP_NOME) ?>.</p>
  </div>
  <button class="btn btn-primario" type="button" data-abrir="modal-transp">+ Adicionar transportadora</button>
</div>

<div class="grelha-transp">
  <?php if (!$lista): ?><div class="vazio" style="grid-column:1/-1">Nenhuma transportadora registada.</div><?php endif; ?>
  <?php foreach ($lista as $t): ?>
    <article class="cartao-transp">
      <header>
        <h3><?= e($t['nome']) ?></h3>
        <span class="etq <?= $t['activa'] ? 'etq-estado-activa' : 'etq-estado-inactiva' ?>"><?= $t['activa'] ? 'Activa' : 'Inactiva' ?></span>
      </header>
      <p class="tipo">Operadora Nacional</p>
      <div class="dados-transp">
        <div>Autocarros activos: <b><?= (int)$t['autocarros'] ?></b></div>
        <div>Rotas concessionadas: <b><?= (int)$t['rotas'] ?></b></div>
        <div>Gestor de Frota: <b><?= e($t['gestor'] ?? '—') ?></b></div>
      </div>
      <div class="rodape-cartao">
        <a href="<?= e(url('administrador/frota.php?id=' . (int)$t['id_transportadora'])) ?>"><b>Ver detalhes da frota →</b></a>
        <form method="post" data-confirmar="<?= $t['activa'] ? 'Desactivar' : 'Activar' ?> a transportadora <?= e($t['nome']) ?>?">
          <?= csrf_campo() ?><input type="hidden" name="acao" value="alternar"><input type="hidden" name="id" value="<?= (int)$t['id_transportadora'] ?>">
          <button class="btn-tabela" type="submit"><?= $t['activa'] ? 'Desactivar' : 'Activar' ?></button>
        </form>
      </div>
    </article>
  <?php endforeach; ?>
</div>

<dialog class="modal" id="modal-transp">
  <h2>Adicionar transportadora</h2>
  <p class="sub">Registe uma nova operadora autorizada a vender bilhetes na plataforma.</p>
  <form method="post">
    <?= csrf_campo() ?><input type="hidden" name="acao" value="criar">
    <div class="campo"><label>Nome <span class="requerido">*</span></label><input type="text" name="nome" required></div>
    <div class="linha-2">
      <div class="campo"><label>NUIT <span class="requerido">*</span></label><input type="text" name="nuit" inputmode="numeric" maxlength="9" placeholder="9 dígitos" required></div>
      <div class="campo"><label>Contacto <span class="requerido">*</span></label><input type="text" name="contacto" placeholder="+258 84 000 0000" required></div>
    </div>
    <div class="campo"><label>Endereço</label><input type="text" name="endereco"></div>
    <div class="modal-acoes">
      <button class="btn btn-sec" type="button" data-fechar>Cancelar</button>
      <button class="btn btn-primario" type="submit">Guardar</button>
    </div>
  </form>
</dialog>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
