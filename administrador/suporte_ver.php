<?php
// administrador/suporte_ver.php - um pedido de ajuda: conversa, resposta, ficha do passageiro e directório de transportadoras
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/suporte.php';
exigir_perfil(['administrador']);
suporte_garantir_tabelas($pdo);
$eu = utilizador_logado();

$id = (int)($_GET['id'] ?? $_POST['id_pedido'] ?? 0);
$st = $pdo->prepare('SELECT * FROM suporte_pedido WHERE id_pedido = ?');
$st->execute([$id]);
$ped = $st->fetch();
if (!$ped) { flash('erro', 'Pedido de ajuda não encontrado.'); redirecionar('administrador/suporte.php'); }
$aqui = 'administrador/suporte_ver.php?id=' . $id;

// ---------- acções ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($aqui); }
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'responder') {
        $texto = sp_texto_valido($_POST['texto'] ?? '');
        $novo = $_POST['novo_estado'] ?? '';
        if (!$texto) {
            flash('erro', 'Escreva a resposta (mínimo 5 caracteres).');
        } else {
            sp_add_mensagem($pdo, $id, 'administrador', (int)$eu['id_utilizador'], $texto);
            $estado = in_array($novo, ['em_curso', 'resolvido'], true) ? $novo : ($ped['estado'] === 'aberto' ? 'em_curso' : $ped['estado']);
            $pdo->prepare('UPDATE suporte_pedido SET estado = ?, id_admin = COALESCE(id_admin, ?) WHERE id_pedido = ?')
                ->execute([$estado, (int)$eu['id_utilizador'], $id]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Pedido de ajuda respondido', 'suporte_pedido', $id, SP_ESTADOS[$estado]);
            flash('ok', $ped['id_passageiro'] ? 'Resposta enviada. O passageiro recebe uma notificação na app.'
                                              : 'Resposta registada. Este pedido não tem conta associada: contacte o passageiro por ' . $ped['contacto'] . '.');
        }
    } elseif ($acao === 'estado' && isset(SP_ESTADOS[$_POST['novo_estado'] ?? ''])) {
        $pdo->prepare('UPDATE suporte_pedido SET estado = ?, id_admin = COALESCE(id_admin, ?), data_actualizacao = ? WHERE id_pedido = ?')
            ->execute([$_POST['novo_estado'], (int)$eu['id_utilizador'], date('Y-m-d H:i:s'), $id]);
        registar_log($pdo, (int)$eu['id_utilizador'], 'Pedido de ajuda: estado alterado', 'suporte_pedido', $id, SP_ESTADOS[$_POST['novo_estado']]);
        flash('ok', 'Pedido marcado como ' . mb_strtolower(SP_ESTADOS[$_POST['novo_estado']]) . '.');
    } elseif ($acao === 'associar') {
        $st = $pdo->prepare("SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador = ? AND perfil = 'passageiro'");
        $st->execute([(int)($_POST['id_passageiro'] ?? 0)]);
        if ($c = $st->fetch()) {
            $pdo->prepare('UPDATE suporte_pedido SET id_passageiro = ? WHERE id_pedido = ?')->execute([$c['id_utilizador'], $id]);
            registar_log($pdo, (int)$eu['id_utilizador'], 'Pedido de ajuda associado a conta', 'suporte_pedido', $id, $c['nome']);
            flash('ok', 'Pedido associado à conta de ' . $c['nome'] . '. Agora ele vê as respostas na app.');
        }
    }
    redirecionar($aqui);
}

// ---------- dados do ecrã ----------
$passageiro = null;
if ($ped['id_passageiro']) {
    $st = $pdo->prepare('SELECT * FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$ped['id_passageiro']]);
    $passageiro = $st->fetch() ?: null;
}
$possiveis = [];
if (!$passageiro) {   // pedido de visitante: procurar contas com o mesmo email/telefone
    $dig = preg_replace('/\D/', '', $ped['contacto']);
    $st = $pdo->prepare("SELECT * FROM utilizador WHERE perfil = 'passageiro' AND (email = ? OR (? <> '' AND REPLACE(telefone, ' ', '') LIKE ?)) LIMIT 5");
    $st->execute([$ped['contacto'], $dig, $dig !== '' ? '%' . substr($dig, -9) : '%__nenhum__%']);
    $possiveis = $st->fetchAll();
}

$tq = trim($_GET['tq'] ?? '');
$abrirDir = $ped['categoria'] === 'transportadora' || $tq !== '';
$sqlT = 'SELECT id_transportadora, nome, contacto, endereco, activa FROM transportadora';
$parT = [];
if ($tq !== '') {
    $sqlT .= ' WHERE nome LIKE ? OR endereco LIKE ? OR id_transportadora IN (SELECT id_transportadora FROM rota WHERE origem LIKE ? OR destino LIKE ?)';
    $parT = array_fill(0, 4, "%$tq%");
}
$st = $pdo->prepare($sqlT . ' ORDER BY activa DESC, nome LIMIT 8');
$st->execute($parT);
$transp = $st->fetchAll();
$rotasT = $pdo->prepare('SELECT DISTINCT origem, destino FROM rota WHERE id_transportadora = ? AND activa = 1 LIMIT 5');

$primeiro = explode(' ', trim($ped['nome_contacto']))[0];
$respostas = [];
foreach (SP_RESPOSTAS as $k => $t) $respostas[$k] = str_replace('{nome}', $primeiro, $t);

$tituloPagina = 'Pedido #' . $id;
$migalhas = ['Painel Admin', 'Suporte ao passageiro', 'Pedido #' . $id];
$paginaActiva = 'administrador/suporte.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina"><div>
  <h1 class="titulo-pagina">Pedido #<?= $id ?> · <?= e($ped['assunto']) ?></h1>
  <p class="subtitulo-pagina">Aberto em <?= e(sp_data($ped['data_criacao'])) ?> por <?= e($ped['nome_contacto']) ?> · <?= e($ped['contacto']) ?> · <?= sp_etq($ped['estado']) ?></p></div>
  <a class="btn btn-sec" href="<?= e(url('administrador/suporte.php')) ?>">← Todos os pedidos</a></div>

<div class="sp-grelha">
  <div>
    <section class="cartao">
      <h2>Conversa</h2>
      <?php sp_conversa($pdo, $id, 'administrador'); ?>

      <form method="post" class="sp-form">
        <?= csrf_campo() ?><input type="hidden" name="acao" value="responder"><input type="hidden" name="id_pedido" value="<?= $id ?>">
        <div class="campo"><label for="rapida">Resposta rápida</label>
          <select id="rapida"><option value="">— escolher um modelo (pode editar) —</option>
            <?php foreach ($respostas as $k => $t): ?><option value="<?= e($t) ?>"><?= e(SP_CATEGORIAS[$k][0]) ?></option><?php endforeach; ?></select></div>
        <div class="campo"><label for="texto">Resposta ao passageiro</label>
          <textarea id="texto" name="texto" rows="5" maxlength="2000" required placeholder="Escreva a resposta..."></textarea></div>
        <?php if (!$ped['id_passageiro']): ?><div class="alerta alerta-info">Este pedido não está ligado a uma conta, por isso o passageiro não vê a resposta na app. Contacte-o por <b><?= e($ped['contacto']) ?></b> e registe aqui o que foi dito.</div><?php endif; ?>
        <div class="sp-linha-form">
          <select name="novo_estado" aria-label="Depois de enviar">
            <option value="">Depois de enviar: manter</option>
            <option value="em_curso">Marcar como em curso</option>
            <option value="resolvido">Marcar como resolvido</option></select>
          <button class="btn btn-primario" type="submit">Enviar resposta</button>
        </div>
      </form>

      <div class="sp-acoes" style="margin-top:14px">
        <?php foreach (SP_ESTADOS as $k => $n): if ($k === $ped['estado']) continue; ?>
          <form method="post"><?= csrf_campo() ?><input type="hidden" name="acao" value="estado"><input type="hidden" name="id_pedido" value="<?= $id ?>">
            <input type="hidden" name="novo_estado" value="<?= e($k) ?>"><button class="btn btn-sec btn-pequeno" type="submit">Marcar <?= e(mb_strtolower($n)) ?></button></form>
        <?php endforeach; ?>
      </div>
    </section>

    <details class="cartao sp-dir" style="margin-top:20px" <?= $abrirDir ? 'open' : '' ?>>
      <summary><b>Directório de transportadoras</b> <small>— para ajudar a localizar uma transportadora</small></summary>
      <form method="get" class="filtros" style="margin-top:12px"><input type="hidden" name="id" value="<?= $id ?>">
        <input type="search" name="tq" value="<?= e($tq) ?>" placeholder="Nome, cidade ou rota (ex.: Beira)...">
        <button class="btn btn-sec btn-pequeno" type="submit">Procurar</button></form>
      <?php if (!$transp): ?><p class="sp-nota">Nenhuma transportadora encontrada.</p><?php endif; ?>
      <?php foreach ($transp as $t):
          $rotasT->execute([$t['id_transportadora']]);
          $rr = array_map(fn($x) => $x['origem'] . ' → ' . $x['destino'], $rotasT->fetchAll());
          $linhaTxt = $t['nome'] . ' — contacto: ' . $t['contacto'] . ($t['endereco'] ? '; endereço: ' . $t['endereco'] : '') . ($rr ? '; rotas: ' . implode(', ', $rr) : '') . '.';
      ?>
        <div class="sp-transp">
          <div><b><?= e($t['nome']) ?></b> <?= (int)$t['activa'] ? '' : '<span class="etq etq-bloqueado">Inactiva</span>' ?>
            <small>📞 <?= e($t['contacto']) ?><?= $t['endereco'] ? ' · 📍 ' . e($t['endereco']) : '' ?></small>
            <?php if ($rr): ?><small>Rotas: <?= e(implode(' · ', $rr)) ?></small><?php endif; ?></div>
          <button type="button" class="btn btn-sec btn-pequeno" data-inserir="<?= e($linhaTxt) ?>">Inserir na resposta</button>
        </div>
      <?php endforeach; ?>
    </details>
  </div>

  <div>
    <?php if ($passageiro): ?>
      <?php sp_ficha_passageiro($pdo, $passageiro, $aqui, $ped['id_reserva'] ? (int)$ped['id_reserva'] : null); ?>
    <?php else: ?>
      <section class="cartao">
        <h2>Pedido sem conta associada</h2>
        <p class="sub">Feito por um visitante (por exemplo, quem não consegue entrar). Contacto: <b><?= e($ped['contacto']) ?></b></p>
        <?php if ($possiveis): ?>
          <p class="sp-nota">Contas com o mesmo contacto:</p>
          <?php foreach ($possiveis as $c): ?>
            <div class="sp-transp"><div><b><?= e($c['nome']) ?></b><small><?= e($c['email']) ?> · <?= e($c['telefone'] ?: 'sem telefone') ?></small></div>
              <form method="post" data-confirmar="Associar este pedido à conta de <?= e($c['nome']) ?>?"><?= csrf_campo() ?>
                <input type="hidden" name="acao" value="associar"><input type="hidden" name="id_pedido" value="<?= $id ?>">
                <input type="hidden" name="id_passageiro" value="<?= (int)$c['id_utilizador'] ?>">
                <button class="btn btn-sec btn-pequeno" type="submit">Associar</button></form></div>
          <?php endforeach; ?>
        <?php else: ?><p class="sp-nota">Não foi encontrada nenhuma conta com este contacto.</p><?php endif; ?>
        <a class="btn btn-sec btn-pequeno" style="margin-top:10px" href="<?= e(url('administrador/passageiro.php')) ?>">Procurar passageiro</a>
      </section>
    <?php endif; ?>
  </div>
</div>

<script>
(function () {
  const t = document.getElementById('texto'), r = document.getElementById('rapida');
  if (r) r.addEventListener('change', () => { if (r.value) { t.value = r.value; t.focus(); } });
  document.querySelectorAll('[data-inserir]').forEach(b => b.addEventListener('click', () => {
    t.value = (t.value ? t.value.replace(/\s*$/, ' ') : '') + b.dataset.inserir;
    t.focus(); t.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }));
})();
</script>
<?php require __DIR__ . '/../includes/painel_fim.php';
