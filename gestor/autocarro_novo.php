<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
$transp = exigir_gestor($pdo, 'gerir_frota');
$idT = $transp['id'];
$eu = utilizador_logado();

$marcas = ['Yutong', 'Marcopolo', 'Scania', 'Mercedes-Benz', 'Volvo', 'Higer', 'King Long', 'Outra'];
$servicos = ['Convencional', 'Semi-luxo', 'Executivo · AC', 'Executivo nocturno'];
$configs = ['2+2' => '2 + 2', '2+1' => '2 + 1'];
$estados = ['disponivel' => 'Disponível', 'manutencao' => 'Em manutenção', 'inactivo' => 'Inactivo'];
$anoActual = (int)date('Y');

$prefixo = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $transp['nome']), 0, 2)) . '-';
$v = ['codigo_frota' => proximo_codigo($pdo, 'autocarro', 'codigo_frota', $prefixo, $idT), 'matricula' => '', 'marca' => 'Yutong', 'modelo' => '',
      'ano_fabrico' => $anoActual, 'tipo_servico' => 'Executivo · AC', 'assentos' => 48, 'configuracao' => '2+2',
      'ar' => 1, 'wifi' => 1, 'usb' => 1, 'inspecao' => date('Y-m-d', strtotime('+1 year')), 'estado' => 'disponivel'];
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = ['codigo_frota' => trim($_POST['codigo_frota'] ?? ''), 'matricula' => strtoupper(trim($_POST['matricula'] ?? '')), 'marca' => $_POST['marca'] ?? '',
          'modelo' => trim($_POST['modelo'] ?? ''), 'ano_fabrico' => (int)($_POST['ano_fabrico'] ?? 0), 'tipo_servico' => $_POST['tipo_servico'] ?? '',
          'assentos' => (int)($_POST['assentos'] ?? 0), 'configuracao' => $_POST['configuracao'] ?? '', 'ar' => !empty($_POST['ar']) ? 1 : 0,
          'wifi' => !empty($_POST['wifi']) ? 1 : 0, 'usb' => !empty($_POST['usb']) ? 1 : 0, 'inspecao' => $_POST['inspecao'] ?? '', 'estado' => $_POST['estado'] ?? ''];

    if (!csrf_valido()) $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    elseif (!preg_match('/^[A-Za-z0-9\-]{2,20}$/', $v['codigo_frota'])) $erro = 'O código da frota deve ter 2 a 20 letras, números ou hífenes.';
    elseif (!preg_match('/^[A-Z0-9\- ]{5,15}$/', $v['matricula'])) $erro = 'Indique uma matrícula válida (ex.: AAF-246-MP).';
    elseif (!in_array($v['marca'], $marcas, true)) $erro = 'Escolha a marca.';
    elseif (mb_strlen($v['modelo']) < 2 || mb_strlen($v['modelo']) > 40) $erro = 'Indique o modelo do autocarro.';
    elseif ($v['ano_fabrico'] < 2000 || $v['ano_fabrico'] > $anoActual + 1) $erro = 'Indique um ano de fabrico válido.';
    elseif (!in_array($v['tipo_servico'], $servicos, true)) $erro = 'Escolha o tipo de serviço.';
    elseif ($v['assentos'] < 10 || $v['assentos'] > 60) $erro = 'O número de assentos deve estar entre 10 e 60.';
    elseif (!isset($configs[$v['configuracao']])) $erro = 'Escolha a configuração dos assentos.';
    elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $v['inspecao'])) $erro = 'Indique a validade da inspecção.';
    elseif (!isset($estados[$v['estado']])) $erro = 'Escolha o estado inicial.';
    elseif ($v['estado'] === 'disponivel' && $v['inspecao'] < date('Y-m-d')) $erro = 'A inspecção está expirada: escolha «Em manutenção» ou indique uma validade futura.';
    else {
        $st = $pdo->prepare('SELECT COUNT(*) FROM autocarro WHERE (id_transportadora = ? AND codigo_frota = ?) OR matricula = ?');
        $st->execute([$idT, $v['codigo_frota'], $v['matricula']]);
        if ($st->fetchColumn() > 0) $erro = 'Já existe um autocarro com este código de frota ou esta matrícula.';
    }
    if (!$erro) {
        [$ok, $livrete] = guardar_documento($_FILES['livrete'] ?? null, 'livretes');
        if (!$ok) $erro = $livrete;
    }
    if (!$erro) {
        try {
                        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'pgsql') {
                $stIns = $pdo->prepare('INSERT INTO autocarro (matricula, modelo, capacidade_total, id_transportadora, codigo_frota, marca, ano_fabrico, tipo_servico,
                                        configuracao_assentos, ar_condicionado, wifi, usb, validade_inspecao, livrete, estado, activo)
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING id_autocarro');
                $stIns->execute([$v['matricula'], $v['modelo'], $v['assentos'], $idT, $v['codigo_frota'], $v['marca'], $v['ano_fabrico'], $v['tipo_servico'],
                                 $v['configuracao'], $v['ar'], $v['wifi'], $v['usb'], $v['inspecao'], $livrete, $v['estado'], $v['estado'] === 'disponivel' ? 1 : 0]);
                $idA = (int)$stIns->fetchColumn();
            } else {
                $pdo->prepare('INSERT INTO autocarro (matricula, modelo, capacidade_total, id_transportadora, codigo_frota, marca, ano_fabrico, tipo_servico,
                               configuracao_assentos, ar_condicionado, wifi, usb, validade_inspecao, livrete, estado, activo)
                               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
                    ->execute([$v['matricula'], $v['modelo'], $v['assentos'], $idT, $v['codigo_frota'], $v['marca'], $v['ano_fabrico'], $v['tipo_servico'],
                               $v['configuracao'], $v['ar'], $v['wifi'], $v['usb'], $v['inspecao'], $livrete, $v['estado'], $v['estado'] === 'disponivel' ? 1 : 0]);
                $idA = (int)$pdo->lastInsertId();
            }

            $idA = (int)$pdo->lastInsertId();
            // assentos no formato fila+letra (1A, 1B, 1C, 1D, 2A...): 2+2 usa A-D, 2+1 usa A-C
            $insA = $pdo->prepare("INSERT INTO assento (numero, tipo, id_autocarro) VALUES (?, 'normal', ?)");
            $letras = $v['configuracao'] === '2+1' ? ['A', 'B', 'C'] : ['A', 'B', 'C', 'D'];
            for ($n = 0; $n < $v['assentos']; $n++) {
                $insA->execute([(intdiv($n, count($letras)) + 1) . $letras[$n % count($letras)], $idA]);
            }
            $pdo->commit();
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $erro = 'Não foi possível guardar o autocarro. Tente novamente.';
        }
    }
    if (!$erro) {
        registar_log($pdo, (int)$eu['id_utilizador'], 'Autocarro adicionado', 'autocarro', $idA, $v['codigo_frota'] . ' · ' . $v['matricula']);
        flash('ok', 'Autocarro ' . $v['codigo_frota'] . ' adicionado. Já pode ser seleccionado ao programar viagens.');
        redirecionar('gestor/autocarros.php');
    }
}

$tituloPagina = 'Adicionar autocarro';
$migalhas = ['Autocarros', 'Adicionar autocarro'];
$paginaActiva = 'gestor/autocarros.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<style>
/* ===== Estilos desta página (autocarro_novo.php). Podes mover este bloco para o teu painel.css ===== */
.cabecalho-pagina{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:22px}
.chip-transp{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;background:#fff;border:1px solid #e3e9f1;border-radius:10px;font-weight:600;font-size:14px;color:#0f2447;white-space:nowrap}
.chip-transp svg{width:18px;height:18px}

/* Layout em 2 colunas */
.form-frota{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:24px;align-items:start}
.resumo-lateral{position:sticky;top:20px}

/* Secções do formulário */
.sec-form{background:#fff;border:1px solid #e3e9f1;border-radius:16px;padding:26px 28px;margin-bottom:22px}
.sec-form h2{display:flex;align-items:center;gap:12px;margin:0 0 22px;font-size:20px;font-weight:700;color:#0f2447}
.sec-form h2 .num{width:36px;height:36px;display:grid;place-items:center;border-radius:9px;background:#e6f7ee;color:#0a7a45;font-size:15px;font-weight:700}
.linha-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;margin-bottom:18px}
.sec-form .campo{margin:0}
.sec-form .campo label{display:block;margin-bottom:8px;font-size:15px;font-weight:600;color:#0f2447}
.sec-form .obrigatorio{color:inherit}
.sec-form .campo input:not([type=checkbox]):not([type=file]),.sec-form .campo select{width:100%;height:52px;padding:0 16px;border:1px solid #dfe6ef;border-radius:10px;background:#fff;font:inherit;font-size:16px;color:#0f2447}
.sec-form .campo input:focus,.sec-form .campo select:focus{outline:2px solid #bfe9d2;border-color:#16a864}
.sec-form label input[type=checkbox]{width:18px;height:18px;margin-right:8px;vertical-align:-4px;accent-color:#16a864}
.pequeno{font-size:13px;color:#6b7a90}
.sec-form .pequeno{font-size:14px}

/* Upload do livrete */
.upload-caixa{display:flex;justify-content:space-between;align-items:center;gap:16px;border:1px solid #dfe6ef;border-radius:12px;padding:18px 22px;margin-top:6px}
.upload-caixa b{display:block;font-size:16px;color:#0f2447}
.upload-caixa small{display:block;margin-top:4px;font-size:14px;color:#7a8799}
.upload-caixa input[type=file]{max-width:240px;font-size:13px;color:#6b7a90}
.upload-caixa input[type=file]::file-selector-button{background:none;border:0;padding:0 8px 0 0;color:#16a864;font:inherit;font-weight:700;cursor:pointer}

/* Resumo lateral */
.resumo-box{background:#fff;border:1px solid #e3e9f1;border-radius:16px;padding:26px}
.resumo-box h2{margin:0 0 20px;font-size:20px;font-weight:700;color:#0f2447}
.resumo-bus{position:relative;background:#f1f6f4;border-radius:14px;padding:20px 20px 20px 84px;min-height:78px}
.resumo-bus::before{content:"";position:absolute;left:22px;top:22px;width:40px;height:40px;border-radius:10px;background:#e1f5ea url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2316a864' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'><rect x='5' y='3' width='14' height='15' rx='2'/><path d='M5 11h14M8 21v-3M16 21v-3'/><circle cx='9' cy='14.5' r='.6'/><circle cx='15' cy='14.5' r='.6'/></svg>") center/24px no-repeat}
.resumo-bus b{display:block;font-size:24px;font-weight:700;color:#0f2447}
.resumo-bus small{display:block;margin-top:2px;font-size:15px;color:#6b7a90}
.resumo-bus .etq-g{display:inline-block;padding:3px 10px;border-radius:6px;font-size:13px;font-weight:700}
.resumo-bus .etq-pago{background:#e6f7ee;color:#0a7a45}
.linhas-resumo{margin-top:14px}
.linhas-resumo .l{display:flex;justify-content:space-between;align-items:center;padding:11px 0;font-size:15px}
.linhas-resumo .l span{color:#6b7a90}
.linhas-resumo .l b{color:#0f2447;font-weight:600}
.lugares-grande{font-size:44px;line-height:1.1;font-weight:800;color:#16a864}
.config-chip{padding:6px 10px;border-radius:8px;background:#f1f4f9;font-size:13px;font-weight:600;color:#52617a}

/* Prévia dos assentos */
.previa-bus{margin-top:20px;background:#f4f7fb;border-radius:14px;padding:18px 20px}
.previa-bus .topo{margin-bottom:12px;font-size:12px;letter-spacing:.02em;text-transform:uppercase;color:#6b7a90}
.previa-fila{display:flex;justify-content:center;gap:6px;margin-bottom:6px}
.previa-fila .s{width:46px;height:25px;display:grid;place-items:center;border:1px solid #dfe6ef;border-radius:5px;background:#fff;font-size:11px;color:#7a8799}
.previa-fila .corredor{width:30px}
.previa-nota{margin-top:10px;font-size:12.5px;color:#6b7a90}

.aviso-verde{margin-top:20px;background:#e9f8f0;border-radius:14px;padding:18px 22px;font-size:15px;line-height:1.5;color:#0a7a45}
.aviso-verde b{display:block;margin-bottom:6px;font-size:17px}

/* Barra de acções */
.barra-form{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-top:28px;padding-top:26px;border-top:1px solid #e3e9f1}
.barra-form .nota{font-size:15px;color:#6b7a90}
.barra-form .botoes{display:flex;gap:14px}
.barra-form .btn{height:54px;padding:0 28px;font-size:16px;display:inline-flex;align-items:center}

@media (max-width:1100px){.form-frota{grid-template-columns:1fr}.resumo-lateral{position:static}}
@media (max-width:640px){.linha-2{grid-template-columns:1fr}.cabecalho-pagina,.barra-form{flex-direction:column;align-items:stretch}}
</style>
<div class="cabecalho-pagina">
  <div><h1 class="titulo-pagina">Adicionar autocarro</h1>
    <p class="subtitulo-pagina">Cadastre o veículo e defina a capacidade para as próximas viagens.</p></div>
  <span class="chip-transp"><?= icone_svg('edificio') ?> Transportadora <?= e($transp['nome']) ?></span>
</div>
<?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" id="form-autocarro">
  <?= csrf_campo() ?>
  <div class="form-frota">
    <div>
      <section class="sec-form"><h2><span class="num">1</span>Identificação do veículo</h2>
        <div class="linha-2">
          <div class="campo"><label>Código da frota <span class="obrigatorio">*</span></label><input type="text" name="codigo_frota" value="<?= e($v['codigo_frota']) ?>" required></div>
          <div class="campo"><label>Matrícula <span class="obrigatorio">*</span></label><input type="text" name="matricula" value="<?= e($v['matricula']) ?>" placeholder="AAF-246-MP" required></div>
        </div>
        <div class="linha-2">
          <div class="campo"><label>Marca <span class="obrigatorio">*</span></label>
            <select name="marca"><?php foreach ($marcas as $m): ?><option <?= $v['marca'] === $m ? 'selected' : '' ?>><?= e($m) ?></option><?php endforeach; ?></select></div>
          <div class="campo"><label>Modelo <span class="obrigatorio">*</span></label><input type="text" name="modelo" value="<?= e($v['modelo']) ?>" placeholder="ZK6122H9" required></div>
        </div>
        <div class="linha-2">
          <div class="campo"><label>Ano de fabrico <span class="obrigatorio">*</span></label>
            <select name="ano_fabrico"><?php for ($a = $anoActual + 1; $a >= 2000; $a--): ?><option <?= $v['ano_fabrico'] === $a ? 'selected' : '' ?>><?= $a ?></option><?php endfor; ?></select></div>
          <div class="campo"><label>Tipo de serviço <span class="obrigatorio">*</span></label>
            <select name="tipo_servico"><?php foreach ($servicos as $s): ?><option <?= $v['tipo_servico'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select></div>
        </div>
      </section>

      <section class="sec-form"><h2><span class="num">2</span>Capacidade e comodidades</h2>
        <div class="linha-2">
          <div class="campo"><label>Número de assentos <span class="obrigatorio">*</span></label><input type="number" name="assentos" min="10" max="60" value="<?= (int)$v['assentos'] ?>" required></div>
          <div class="campo"><label>Configuração dos assentos <span class="obrigatorio">*</span></label>
            <select name="configuracao"><?php foreach ($configs as $k => $n): ?><option value="<?= $k ?>" <?= $v['configuracao'] === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?></select></div>
        </div>
        <p class="pequeno" style="margin:-6px 0 14px">A capacidade não inclui o lugar do motorista. Os assentos serão identificados por fila e letra (1A, 1B, 1C…); total: <span id="rs-num-max"><?= (int)$v['assentos'] ?></span>.</p>
        <div style="display:flex;gap:22px;flex-wrap:wrap;font-size:15px">
          <label><input type="checkbox" name="ar" value="1" <?= $v['ar'] ? 'checked' : '' ?>> Ar condicionado</label>
          <label><input type="checkbox" name="wifi" value="1" <?= $v['wifi'] ? 'checked' : '' ?>> Wi-Fi</label>
          <label><input type="checkbox" name="usb" value="1" <?= $v['usb'] ? 'checked' : '' ?>> Portas USB</label>
        </div>
      </section>

      <section class="sec-form"><h2><span class="num">3</span>Documentação e disponibilidade</h2>
        <div class="linha-2">
          <div class="campo"><label>Validade da inspecção <span class="obrigatorio">*</span></label><input type="date" name="inspecao" value="<?= e($v['inspecao']) ?>" required></div>
          <div class="campo"><label>Estado inicial <span class="obrigatorio">*</span></label>
            <select name="estado"><?php foreach ($estados as $k => $n): ?><option value="<?= $k ?>" <?= $v['estado'] === $k ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="upload-caixa"><div><b>Livrete do veículo</b><small>PDF, JPG ou PNG · Até 5 MB</small></div>
          <input type="file" name="livrete" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"></div>
      </section>
      <p class="pequeno">* Campos obrigatórios</p>
    </div>

    <aside class="resumo-lateral">
      <div class="resumo-box"><h2>Resumo do autocarro</h2>
        <div class="resumo-bus"><b id="rs-codigo">—</b><small id="rs-veiculo">—</small><div style="margin-top:8px"><span class="etq-g etq-pago" id="rs-estado">Disponível</span></div></div>
        <div class="linhas-resumo">
          <div class="l"><span>Matrícula</span><b id="rs-matricula">—</b></div>
          <div class="l"><span>Ano de fabrico</span><b id="rs-ano">—</b></div>
          <div class="l"><span>Serviço</span><b id="rs-servico">—</b></div>
          <div class="l"><span>Transportadora</span><b><?= e($transp['nome']) ?></b></div>
        </div>
        <hr style="border:0;border-top:1px solid var(--borda, #e3e9f1);margin:12px 0">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div><span class="lugares-grande" id="rs-lugares">0</span><br><small class="pequeno">lugares para passageiros</small></div>
          <span class="config-chip" id="rs-config">2 + 2</span></div>
        <div class="previa-bus"><div class="topo"><span>FRENTE DO AUTOCARRO</span></div><div id="rs-previa"></div><div class="previa-nota" id="rs-previa-nota"></div></div>
      </div>
      <div class="aviso-verde"><b>Pronto para integrar a frota</b>Após o cadastro, este autocarro poderá ser seleccionado na criação de novas viagens.</div>
    </aside>
  </div>

  <div class="barra-form">
    <span class="nota">ⓘ Confirme a matrícula e a capacidade antes de guardar.</span>
    <div class="botoes"><a class="btn btn-sec" href="<?= e(url('gestor/autocarros.php')) ?>">Cancelar</a>
      <button class="btn btn-verde" type="submit">+ Adicionar autocarro</button></div>
  </div>
</form>

<script>
/* Preenche o resumo e a prévia dos assentos em tempo real */
(function () {
  var f = document.getElementById('form-autocarro');
  if (!f) return;
  var $ = function (id) { return document.getElementById(id); };
  var estados = {
    disponivel: ['Disponível', 'etq-pago'],
    manutencao: ['Em manutenção', 'etq-expirado'],
    inactivo:   ['Inactivo', 'etq-cancelado']
  };
  function pad(n) { return n < 10 ? '0' + n : '' + n; }

  function actualizar() {
    var el = f.elements;
    var marca = el.marca.value, modelo = el.modelo.value.trim();
    $('rs-codigo').textContent = el.codigo_frota.value.trim() || '—';
    $('rs-veiculo').textContent = (marca + (modelo ? ' · ' + modelo : '')) || '—';
    $('rs-matricula').textContent = el.matricula.value.trim().toUpperCase() || '—';
    $('rs-ano').textContent = el.ano_fabrico.value || '—';
    $('rs-servico').textContent = el.tipo_servico.value || '—';

    var est = estados[el.estado.value] || estados.disponivel;
    $('rs-estado').textContent = est[0];
    $('rs-estado').className = 'etq-g ' + est[1];

    var n = parseInt(el.assentos.value, 10) || 0;
    n = Math.max(0, Math.min(60, n));
    var cfg = el.configuracao.value === '2+1' ? '2+1' : '2+2';
    var porFila = cfg === '2+1' ? 3 : 4;
    var filas = Math.ceil(n / porFila);

    $('rs-lugares').textContent = n;
    $('rs-config').textContent = cfg === '2+1' ? '2 + 1' : '2 + 2';
    $('rs-num-max').textContent = n;

    var html = '', num = 1;
    for (var r = 0; r < filas; r++) {
      html += '<div class="previa-fila">';
      for (var c = 0; c < porFila; c++) {
        if (c === 2) html += '<span class="corredor"></span>';
        if (num <= n) { html += '<span class="s">' + (r + 1) + 'ABCD'.charAt(c) + '</span>'; num++; }
        else html += '<span class="s" style="visibility:hidden"></span>';
      }
      html += '</div>';
    }
    $('rs-previa').innerHTML = html;
    $('rs-previa-nota').textContent = 'Prévia · ' + filas + ' filas de ' + porFila + ' assentos';
  }

  f.addEventListener('input', actualizar);
  f.addEventListener('change', actualizar);
  actualizar();
})();
</script>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>