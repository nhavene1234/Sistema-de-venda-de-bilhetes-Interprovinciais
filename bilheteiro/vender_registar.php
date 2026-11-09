<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('bilheteiro/index.php');

$grupo = $_POST['grupo'] ?? '';
$voltar = 'bilheteiro/vender_dados.php?grupo=' . urlencode($grupo);
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }
libertar_reservas_expiradas($pdo);

$linhas = carregar_venda($pdo, $grupo, (int)$eu['id_utilizador'], 'pendente');
if (!$linhas) { flash('erro', 'O bloqueio dos lugares expirou. Comece a venda novamente.'); redirecionar('bilheteiro/index.php'); }

$nome = trim($_POST['nome'] ?? '');
$tipoDoc = $_POST['tipo_documento'] ?? 'BI';
$doc = strtoupper(trim($_POST['documento'] ?? ''));
$bruto = trim($_POST['telefone'] ?? '');
$nove = telefone_normalizar($bruto);
$metodo = $_POST['metodo'] ?? '';
$_SESSION['venda_form'] = ['nome' => $nome, 'tipo_documento' => $tipoDoc, 'documento' => $doc, 'telefone' => $bruto, 'metodo' => $metodo];

$erro = null;
if (mb_strlen($nome) < 3) $erro = 'Indique o nome completo do passageiro.';
elseif (!in_array($tipoDoc, ['BI', 'Passaporte', 'Carta de Condução', 'DIRE'], true)) $erro = 'Escolha o tipo de documento.';
elseif (!preg_match('/^[A-Z0-9]{5,20}$/', $doc)) $erro = 'O número do documento deve ter entre 5 e 20 letras ou números.';
elseif ($nove === null) $erro = 'Telefone inválido: digite 9 dígitos, começando por 82, 83, 84, 85, 86 ou 87.';
elseif (!in_array($metodo, ['numerario', 'mpesa', 'emola', 'cartao'], true)) $erro = 'Escolha o método de pagamento.';
elseif ($metodo === 'mpesa' && !in_array(substr($nove, 0, 2), PREFIXOS_MPESA, true)) $erro = 'Número M-Pesa inválido: tem de começar por 84 ou 85 e ter 9 dígitos.';
elseif ($metodo === 'emola' && !in_array(substr($nove, 0, 2), PREFIXOS_EMOLA, true)) $erro = 'Número e-Mola inválido: tem de começar por 86 ou 87 e ter 9 dígitos.';
if ($erro) { flash('erro', $erro); redirecionar($voltar); }

// simulação da operadora: M-Pesa / e-Mola com número a terminar em 00 são recusados
if (in_array($metodo, ['mpesa', 'emola'], true) && substr($nove, -2) === '00') {
    $codigo = ($metodo === 'mpesa' ? 'MPESA' : 'EMOLA') . '-DECLINED-04';
    registar_log($pdo, (int)$eu['id_utilizador'], 'Pagamento recusado (venda presencial)', 'reserva', (int)$linhas[0]['id_reserva'], $codigo);
    flash('erro', "Pagamento recusado ($codigo). Verifique o saldo ou escolha outro método de pagamento.");
    redirecionar($voltar);
}

// se o documento pertencer a um passageiro registado, o bilhete aparece também na conta dele
$st = $pdo->prepare("SELECT id_utilizador FROM utilizador WHERE documento = ? AND perfil = 'passageiro' LIMIT 1");
$st->execute([$doc]);
$idPassageiro = $st->fetchColumn() ?: null;

$agora = date('Y-m-d H:i:s');
try {
    $pdo->beginTransaction();
    $upd = $pdo->prepare("UPDATE reserva SET nome_passageiro = ?, telefone_passageiro = ?, tipo_documento = ?, documento_passageiro = ?,
                          id_passageiro = ?, estado = 'confirmada', data_expiracao = NULL WHERE id_reserva = ?");
    $pag = $pdo->prepare("INSERT INTO pagamento (valor, metodo, estado, data_pagamento, referencia_transacao, id_reserva, telefone_pagamento)
                          VALUES (?, ?, 'confirmado', ?, ?, ?, ?)");
    $bil = $pdo->prepare("INSERT INTO bilhete (codigo_qr, estado, id_reserva) VALUES (?, 'valido', ?)");
    $refs = [];
    foreach ($linhas as $l) {
        $upd->execute([$nome, telefone_formatado($nove), $tipoDoc, $doc, $idPassageiro, $l['id_reserva']]);
        $pag->execute([$l['preco'], $metodo, $agora, $l['referencia'], $l['id_reserva'], in_array($metodo, ['mpesa', 'emola'], true) ? $nove : null]);
        $bil->execute([$l['referencia'] . '-' . strtoupper(bin2hex(random_bytes(8))), $l['id_reserva']]);   // RN05/RN10: QR único
        $refs[] = $l['referencia'];
    }
    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('erro', 'Não foi possível registar a venda. Tente novamente.');
    redirecionar($voltar);
}
unset($_SESSION['venda_form']);
registar_log($pdo, (int)$eu['id_utilizador'], 'Venda presencial registada', 'reserva', (int)$linhas[0]['id_reserva'], implode(', ', $refs));
redirecionar('bilheteiro/emitir.php?grupo=' . urlencode($grupo));
