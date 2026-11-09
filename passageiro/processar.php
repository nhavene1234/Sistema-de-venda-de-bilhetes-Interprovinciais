<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/passageiro.php';
$u = exigir_passageiro();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('passageiro/index.php');
libertar_reservas_expiradas($pdo);

// reservas a pagar: reservas=12,13,14 (vários lugares) ou reserva=12 (um só)
$ids = lista_ids($_POST['reservas'] ?? ($_POST['reserva'] ?? ''));
if (!$ids) redirecionar('passageiro/index.php');
$listaIds = implode(',', $ids);
$voltar = 'passageiro/pagamento.php?reservas=' . $listaIds;
if (!csrf_valido()) { flash('erro', 'Pedido inválido. Tente novamente.'); redirecionar($voltar); }

// cada reserva tem de ser deste passageiro e da mesma viagem
$reservas = [];
foreach ($ids as $id) {
    $r = carregar_reserva($pdo, $id, (int)$u['id_utilizador']);
    if (!$r) redirecionar('passageiro/index.php');
    $reservas[] = $r;
}
$idViagem = (int)$reservas[0]['id_viagem'];
foreach ($reservas as $r) {
    if ((int)$r['id_viagem'] !== $idViagem) redirecionar('passageiro/index.php');
}
if (array_filter($reservas, fn($r) => $r['estado'] === 'confirmada')) {
    redirecionar(count($reservas) === 1 ? 'passageiro/bilhete.php?reserva=' . $reservas[0]['id_reserva'] : 'passageiro/bilhetes.php');
}
foreach ($reservas as $r) {
    if ($r['estado'] !== 'pendente') {
        flash('erro', 'O bloqueio dos lugares expirou. Escolha os lugares novamente.');
        redirecionar('passageiro/lugares.php?viagem=' . $idViagem);
    }
}

$metodo = $_POST['metodo'] ?? '';
if (!in_array($metodo, ['mpesa', 'emola', 'cartao'], true)) { flash('erro', 'Escolha um meio de pagamento.'); redirecionar($voltar); }

$telefone = null;
if ($metodo !== 'cartao') {
    $telefone = telefone_normalizar($_POST['telefone'] ?? '', $metodo === 'mpesa' ? PREFIXOS_MPESA : PREFIXOS_EMOLA);
    if ($telefone === null) {
        flash('erro', $metodo === 'mpesa'
            ? 'Número M-Pesa inválido: digite os 9 dígitos, começando por 84 ou 85 (ex.: 84 123 4567).'
            : 'Número e-Mola inválido: digite os 9 dígitos, começando por 86 ou 87 (ex.: 87 123 4567).');
        redirecionar($voltar);
    }
}

// ----- simulação da operadora: números a terminar em 00 são recusados -----
$recusado = $telefone !== null && substr($telefone, -2) === '00';
$agora = date('Y-m-d H:i:s');

try {
    $pdo->beginTransaction();   // pagamento de todos os lugares: ou todos ficam pagos, ou nenhum
    $stPag = $pdo->prepare('SELECT id_pagamento FROM pagamento WHERE id_reserva = ?');
    $stBil = $pdo->prepare('SELECT COUNT(*) FROM bilhete WHERE id_reserva = ?');

    foreach ($reservas as $r) {
        $idR = (int)$r['id_reserva'];
        $valor = (float)$r['preco'];

        $stPag->execute([$idR]);
        if (!(int)$stPag->fetchColumn()) {
            $pdo->prepare("INSERT INTO pagamento (valor, metodo, estado, id_reserva) VALUES (?, ?, 'pendente', ?)")->execute([$valor, $metodo, $idR]);
        }

        if ($recusado) {
            $codigo = strtoupper($metodo === 'mpesa' ? 'MPESA' : 'EMOLA') . '-DECLINED-04';
            $pdo->prepare("UPDATE pagamento SET estado = 'falhado', metodo = ?, telefone_pagamento = ?, motivo_falha = ?, valor = ? WHERE id_reserva = ?")
                ->execute([$metodo, $telefone, $codigo, $valor, $idR]);
            registar_log($pdo, (int)$u['id_utilizador'], 'Pagamento recusado', 'reserva', $idR, $codigo);
        } else {
            $pdo->prepare("UPDATE pagamento SET estado = 'confirmado', metodo = ?, telefone_pagamento = ?, motivo_falha = NULL,
                           valor = ?, data_pagamento = ?, referencia_transacao = ? WHERE id_reserva = ?")
                ->execute([$metodo, $telefone, $valor, $agora, $r['referencia'], $idR]);
            $pdo->prepare("UPDATE reserva SET estado = 'confirmada', data_expiracao = NULL WHERE id_reserva = ?")->execute([$idR]);
            $stBil->execute([$idR]);
            if (!$stBil->fetchColumn()) {   // RN05: bilhete só após confirmação; um código único por lugar
                $pdo->prepare("INSERT INTO bilhete (codigo_qr, estado, id_reserva) VALUES (?, 'valido', ?)")
                    ->execute([$r['referencia'] . '-' . strtoupper(bin2hex(random_bytes(8))), $idR]);
            }
            registar_log($pdo, (int)$u['id_utilizador'], 'Pagamento confirmado', 'reserva', $idR, $r['referencia']);
        }
    }
    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('erro', 'Não foi possível processar o pagamento. Tente novamente.');
    redirecionar($voltar);
}
redirecionar('passageiro/a_processar.php?reserva=' . $ids[0] . (count($ids) > 1 ? '&reservas=' . $listaIds : ''));