<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();
$categorias = ['D · Transporte de passageiros'];
$estados = ['disponivel' => 'Disponível', 'folga' => 'De folga', 'inactivo' => 'Inactivo'];

$v = ['nome' => '', 'documento' => '', 'nascimento' => '', 'telefone' => '', 'email' => '', 'morada' => '', 'carta_numero' => '',
      'categoria' => $categorias[0], 'emissao' => '', 'validade' => '', 'codigo' => proximo_codigo($pdo, 'motorista', 'codigo', 'MT-', $idT), 'estado' => 'disponivel'];
$erro = null;

// modo edição: ?id=<motorista>  (só motoristas da própria transportadora)
$idEd = (int)($_GET['id'] ?? 0);
$ed = null;
if ($idEd) {
    $st = $pdo->prepare('SELECT * FROM motorista WHERE id_motorista = ? AND id_transportadora = ?');
    $st->execute([$idEd, $idT]);
    $ed = $st->fetch();
    if (!$ed) acesso_restrito('Só tem permissão para editar motoristas da ' . $transp['nome'] . '.');
    $v = ['nome' => $ed['nome'], 'documento' => $ed['documento'], 'nascimento' => $ed['data_nascimento'], 'telefone' => (string)$ed['telefone'],
          'email' => (string)$ed['email'], 'morada' => (string)$ed['morada'], 'carta_numero' => $ed['carta_numero'], 'categoria' => $ed['carta_categoria'],
          'emissao' => $ed['carta_emissao'], 'validade' => $ed['carta_validade'], 'codigo' => $ed['codigo'], 'estado' => $ed['estado']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = ['nome' => trim($_POST['nome'] ?? ''), 'documento' => strtoupper(trim($_POST['documento'] ?? '')), 'nascimento' => $_POST['nascimento'] ?? '',
          'telefone' => trim($_POST['telefone'] ?? ''), 'email' => trim($_POST['email'] ?? ''), 'morada' => trim($_POST['morada'] ?? ''),
          'carta_numero' => strtoupper(trim($_POST['carta_numero'] ?? '')), 'categoria' => $_POST['categoria'] ?? '', 'emissao' => $_POST['emissao'] ?? '',
          'validade' => $_POST['carta_validade'] ?? '', 'codigo' => trim($_POST['codigo'] ?? ''), 'estado' => $_POST['estado'] ?? ''];
    $data = fn($d) => preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
    $nove = telefone_normalizar($v['telefone']);
    $hoje = date('Y-m-d');

    if (!csrf_valido()) $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    elseif (mb_strlen($v['nome']) < 3) $erro = 'Indique o nome completo do motorista.';
    elseif (!preg_match('/^[A-Z0-9]{5,20}$/', $v['documento'])) $erro = 'O número do BI deve ter entre 5 e 20 letras ou números.';
    elseif (!$data($v['nascimento'])) $erro = 'Indique a data de nascimento.';
    elseif ($v['nascimento'] > date('Y-m-d', strtotime('-21 years')) || $v['nascimento'] < date('Y-m-d', strtotime('-70 years'))) $erro = 'O motorista deve ter entre 21 e 70 anos.';
    elseif ($nove === null) $erro = MSG_TELEFONE;
    elseif ($v['email'] !== '' && !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $erro = 'O e-mail indicado não é válido.';
    elseif (!preg_match('/^[A-Z0-9\-\/]{5,30}$/', $v['carta_numero'])) $erro = 'Indique o número da carta de condução.';
    elseif (!in_array($v['categoria'], $categorias, true)) $erro = 'Só são aceites cartas de categoria D (transporte de passageiros).';
    elseif (!$data($v['emissao']) || $v['emissao'] > $hoje) $erro = 'A data de emissão da carta é inválida.';
    elseif (!$data($v['validade']) || $v['validade'] <= $v['emissao']) $erro = 'A validade da carta tem de ser posterior à emissão.';
    elseif ($v['validade'] < $hoje && (!$ed || $v['estado'] === 'disponivel')) $erro = $ed
        ? 'A carta de condução está expirada: actualize a validade ou ponha o motorista como Inactivo.'
        : 'A carta de condução está expirada: não é possível registar o motorista.';
    elseif (!preg_match('/^[A-Za-z0-9\-]{2,20}$/', $v['codigo'])) $erro = 'O código do motorista deve ter 2 a 20 letras, números ou hífenes.';
    elseif (!isset($estados[$v['estado']])) $erro = 'Escolha o estado inicial.';
    else {
        $st = $pdo->prepare('SELECT COUNT(*) FROM motorista WHERE ((id_transportadora = ? AND codigo = ?) OR documento = ? OR carta_numero = ?) AND id_motorista <> ?');
        $st->execute([$idT, $v['codigo'], $v['documento'], $v['carta_numero'], $idEd]);
        if ($st->fetchColumn() > 0) $erro = 'Já existe um motorista com este código, BI ou número de carta.';
    }
    if (!$erro) {
        [$ok, $ficheiro] = guardar_documento($_FILES['carta_ficheiro'] ?? null, 'cartas');
        if (!$ok) $erro = $ficheiro;
    }
    if (!$erro && $ed) {
        $pdo->prepare('UPDATE motorista SET codigo = ?, nome = ?, documento = ?, data_nascimento = ?, telefone = ?, email = ?, morada = ?, carta_numero = ?,
                       carta_categoria = ?, carta_emissao = ?, carta_validade = ?, carta_ficheiro = ?, estado = ? WHERE id_motorista = ? AND id_transportadora = ?')
            ->execute([$v['codigo'], $v['nome'], $v['documento'], $v['nascimento'], telefone_formatado($nove), $v['email'] ?: null, $v['morada'] ?: null,
                       $v['carta_numero'], $v['categoria'], $v['emissao'], $v['validade'], $ficheiro ?: $ed['carta_ficheiro'], $v['estado'], $idEd, $idT]);
        if ($ficheiro && $ed['carta_ficheiro']) { $antigo = __DIR__ . '/../armazenamento/' . $ed['carta_ficheiro']; if (is_file($antigo)) @unlink($antigo); }
        registar_log($pdo, (int)$eu['id_utilizador'], 'Motorista actualizado', 'motorista', $idEd, $v['codigo'] . ' · ' . $v['nome']);
        flash('ok', 'Dados de ' . $v['nome'] . ' actualizados.');
        redirecionar('gestor/motoristas.php');
    }
    if (!$erro) {
               $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'pgsql') {
            $stIns = $pdo->prepare('INSERT INTO motorista (id_transportadora, codigo, nome, documento, data_nascimento, telefone, email, morada, carta_numero,
                                    carta_categoria, carta_emissao, carta_validade, carta_ficheiro, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING id_motorista');
            $stIns->execute([$idT, $v['codigo'], $v['nome'], $v['documento'], $v['nascimento'], telefone_formatado($nove), $v['email'] ?: null, $v['morada'] ?: null,
                             $v['carta_numero'], $v['categoria'], $v['emissao'], $v['validade'], $ficheiro, $v['estado']]);
            $novoIdM = (int)$stIns->fetchColumn();
        } else {
            $pdo->prepare('INSERT INTO motorista (id_transportadora, codigo, nome, documento, data_nascimento, telefone, email, morada, carta_numero,
                           carta_categoria, carta_emissao, carta_validade, carta_ficheiro, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([$idT, $v['codigo'], $v['nome'], $v['documento'], $v['nascimento'], telefone_formatado($nove), $v['email'] ?: null, $v['morada'] ?: null,
                           $v['carta_numero'], $v['categoria'], $v['emissao'], $v['validade'], $ficheiro, $v['estado']]);
            $novoIdM = (int)$pdo->lastInsertId();
        }
        registar_log($pdo, (int)$eu['id_utilizador'], 'Motorista adicionado', 'motorista', $novoIdM, $v['codigo'] . ' · ' . $v['nome']);

        flash('ok', 'Motorista ' . $v['nome'] . ' adicionado. Já pode ser atribuído a viagens.');
        redirecionar('gestor/motoristas.php');
    }
}

$tituloPagina = $ed ? 'Editar motorista' : 'Adicionar motorista';
$migalhas = ['Motoristas', $tituloPagina];
$paginaActiva = 'gestor/motoristas.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina"><?= $ed ? 'Editar motorista' : 'Adicionar motorista' ?></h1>
    <p class="subtitulo-pagina"><?= $ed ? 'Actualize os dados do motorista e da sua carta de condução.' : 'Registe os dados do motorista e a sua carta de condução.' ?></p></div>
  <span class="chip-transp"><?= icone_svg('edificio') ?> Transportadora <?= e($transp['nome']) ?></span>
</div>
<?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" id="form-motorista" data-transportadora="<?= e($transp['nome']) ?>">
  <?= csrf_campo() ?>
  <div class="form-frota">
    <div>
      <section class="sec-form"><h2><span class="num">1</span>Dados pessoais</h2>
        <div class="campo"><label>Nome completo <span class="obrigatorio">*</span></label><input type="text" name="nome" value="<?= e($v['nome']) ?>" required></div>
        <div class="linha-2">
          <div class="campo"><label>Número do BI <span class="obrigatorio">*</span></label><input type="text" name="documento" value="<?= e($v['documento']) ?>" maxlength="20" required></div>
          <div class="campo"><label>Data de nascimento <span class="obrigatorio">*</span></label><input type="date" name="nascimento" value="<?= e($v['nascimento']) ?>" required></div>
        </div>
        <div class="linha-2">
          <div class="campo"><label>Telemóvel <span class="obrigatorio">*</span></label><input type="tel" name="telefone" value="<?= e($v['telefone']) ?>" placeholder="841234567" required data-contacto></div>
          <div class="campo"><label>E-mail</label><input type="email" name="email" value="<?= e($v['email']) ?>"></div>
        </div>
        <div class="campo"><label>Morada</label><input type="text" name="morada" value="<?= e($v['morada']) ?>" placeholder="Bairro da Matola, Maputo"></div>
      </section>

      <section class="sec-form"><h2><span class="num">2</span>Carta de condução</h2>
        <div class="linha-2">
          <div class="campo"><label>Número da carta <span class="obrigatorio">*</span></label><input type="text" name="carta_numero" value="<?= e($v['carta_numero']) ?>" required></div>
          <div class="campo"><label>Categoria <span class="obrigatorio">*</span></label>
            <select name="categoria"><?php foreach ($categorias as $c): ?><option <?= $v['categoria'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="linha-2">
          <div class="campo"><label>Data de emissão <span class="obrigatorio">*</span></label><input type="date" name="emissao" value="<?= e($v['emissao']) ?>" max="<?= date('Y-m-d') ?>" required></div>
          <div class="campo"><label>Data de validade <span class="obrigatorio">*</span></label><input type="date" name="carta_validade" value="<?= e($v['validade']) ?>" required></div>
        </div>
        <div class="upload-caixa"><div><b>Cópia da carta de condução</b><small>Frente e verso · PDF, JPG ou PNG · Até 5 MB</small></div>
          <input type="file" name="carta_ficheiro" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png">
          <?php if ($ed && $ed['carta_ficheiro']): ?><small style="display:block;margin-top:6px">Já existe uma cópia carregada. Só escolha um ficheiro para a substituir.</small><?php endif; ?></div>
      </section>

      <section class="sec-form"><h2><span class="num">3</span>Dados profissionais</h2>
        <div class="linha-2">
          <div class="campo"><label>Código do motorista <span class="obrigatorio">*</span></label><input type="text" name="codigo" value="<?= e($v['codigo']) ?>" required></div>
          <div class="campo"><label><?= $ed ? 'Estado' : 'Estado inicial' ?> <span class="obrigatorio">*</span></label>
            <select name="estado"><?php foreach ($estados as $k => $n): ?><option value="<?= $k ?>" <?= $v['estado'] === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?></select></div>
        </div>
      </section>
      <p class="pequeno">* Campos obrigatórios</p>
    </div>

    <aside class="resumo-lateral">
      <div class="resumo-box"><h2>Resumo do motorista</h2>
        <div class="resumo-cartao"><span class="iniciais" id="rs-iniciais">—</span><b id="rs-nome">Nome do motorista</b>
          <small id="rs-codigo">—</small><span class="etq-g etq-pago" id="rs-estado">Disponível</span></div>
        <div class="linhas-resumo">
          <div class="l"><span>Telemóvel</span><b id="rs-telefone">—</b></div>
          <div class="l"><span>Documento</span><b>Bilhete de Identidade</b></div>
          <div class="l"><span>Localidade</span><b id="rs-localidade">—</b></div>
        </div>
        <hr style="border:0;border-top:1px solid var(--borda);margin:12px 0">
        <b style="font-size:13px">Categoria D</b>
        <p class="pequeno" style="margin:4px 0 10px">Habilitado para conduzir veículos de transporte de passageiros.</p>
        <div class="linhas-resumo">
          <div class="l"><span>Carta de condução</span><b id="rs-carta">—</b></div>
          <div class="l"><span>Válida até</span><b id="rs-validade">—</b></div>
        </div>
        <span id="rs-validade-badge"></span>
      </div>
      <div class="aviso-verde" id="aviso-doc"><b>Documentação válida</b>A carta de condução deve estar válida na data da viagem para permitir a alocação do motorista.</div>
      <div class="nota-restrita">🛡 <span><b>Dados de acesso restrito</b><br>Os dados pessoais e documentos ficam disponíveis apenas à equipa de gestão da <?= e($transp['nome']) ?>.</span></div>
    </aside>
  </div>

  <div class="barra-form">
    <span class="nota"><?= $ed ? 'ⓘ As alterações aplicam-se de imediato.' : 'ⓘ O motorista ficará disponível para alocação em viagens.' ?></span>
    <div class="botoes"><a class="btn btn-sec" href="<?= e(url('gestor/motoristas.php')) ?>">Cancelar</a>
      <button class="btn btn-verde" type="submit"><?= $ed ? 'Guardar alterações' : '+ Adicionar motorista' ?></button></div>
  </div>
</form>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
