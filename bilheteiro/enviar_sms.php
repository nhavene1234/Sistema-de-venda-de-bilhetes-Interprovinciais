<?php
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/bilheteiro.php';
exigir_perfil(['bilheteiro']);
exigir_permissao($pdo, 'vender_bilhetes');
$eu = utilizador_logado();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) redirecionar('bilheteiro/index.php');

$grupo = $_POST['grupo'] ?? '';
$bilhetes = carregar_venda($pdo, $grupo, (int)$eu['id_utilizador'], 'confirmada');
if (!$bilhetes) redirecionar('bilheteiro/index.php');

// Não existe ligação a um serviço de SMS: o envio é apenas registado (simulação).
registar_log($pdo, (int)$eu['id_utilizador'], 'Bilhete enviado por SMS (simulado)', 'reserva', (int)$bilhetes[0]['id_reserva'], $bilhetes[0]['telefone_passageiro']);
flash('ok', 'SMS enviado para ' . $bilhetes[0]['telefone_passageiro'] . ' (simulação: ainda não há ligação a um serviço de SMS).');
redirecionar('bilheteiro/emitir.php?grupo=' . urlencode($grupo));
