<?php
// instalar/criar_vendas_demo.php
// Transforma os lugares "Reservado (demonstração)" em vendas reais de demonstração (pagamento + bilhete),
// espalhadas pelos últimos 45 dias, para os ecrãs do Gestor terem dados. Executar UMA vez e depois APAGAR.
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/conexao.php';
if (!AMBIENTE_DEMO) { die('Desactivado fora do ambiente de demonstração.'); }
set_time_limit(0);

$nomes = ['Sulemane Issufo', 'Custódia Banze', 'Albertina Chivite', 'Geraldo Alface', 'Filomena Tembe', 'Amílcar Nhantumbo', 'Cláudio Chirindza',
          'Amélia Macuácua', 'Zacarias Gune', 'Sérgio Langa', 'Lurdes Cossa', 'Hélio Matsinhe', 'Neusa Uamusse', 'Orlando Sitoe', 'Dulce Mabunda',
          'Aurélio Zandamela', 'Celeste Bila', 'Inácio Mavie', 'Berta Nhaca', 'Délcio Manhiça', 'Ivone Tivane', 'Teodoro Massingue'];
$metodos = array_merge(array_fill(0, 56, 'mpesa'), array_fill(0, 20, 'numerario'), array_fill(0, 14, 'emola'), array_fill(0, 10, 'cartao'));
$horas = [5, 6, 7, 7, 8, 8, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 17, 18, 18, 19, 20, 21, 22];

$bilheteiros = [];
foreach ($pdo->query("SELECT id_utilizador, id_transportadora FROM utilizador WHERE perfil = 'bilheteiro' AND activo = 1 AND id_transportadora IS NOT NULL")->fetchAll() as $b) {
    $bilheteiros[(int)$b['id_transportadora']][] = (int)$b['id_utilizador'];
}

$linhas = $pdo->query("SELECT rs.id_reserva, rs.referencia, v.preco, ro.id_transportadora
                       FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                       WHERE rs.estado = 'confirmada' AND rs.nome_passageiro LIKE 'Reservado (demonstra%'
                         AND NOT EXISTS (SELECT 1 FROM pagamento p WHERE p.id_reserva = rs.id_reserva)")->fetchAll();

echo '<meta charset="UTF-8"><body style="font-family:sans-serif;max-width:680px;margin:40px auto"><h2>Vendas de demonstração</h2>';
if (!$linhas) { echo '<p>Não há reservas de demonstração por converter. Execute primeiro <b>criar_dados_demo.php</b>.</p>'; exit; }

$upd = $pdo->prepare("UPDATE reserva SET nome_passageiro = ?, telefone_passageiro = ?, tipo_documento = 'BI', documento_passageiro = ?, referencia = ?,
                      data_reserva = ?, id_bilheteiro = ?, estado = ?, data_cancelamento = ? WHERE id_reserva = ?");
$pag = $pdo->prepare('INSERT INTO pagamento (valor, metodo, estado, data_pagamento, referencia_transacao, id_reserva, telefone_pagamento) VALUES (?,?,?,?,?,?,?)');
$bil = $pdo->prepare('INSERT INTO bilhete (codigo_qr, estado, id_reserva, data_emissao) VALUES (?,?,?,?)');
$cont = ['confirmada' => 0, 'cancelada' => 0, 'expirada' => 0];

try {
    $pdo->beginTransaction();
    foreach ($linhas as $l) {
        $dias = (int)floor(45 * (mt_rand() / mt_getrandmax()) ** 2);       // mais vendas recentes do que antigas
        $quando = date('Y-m-d', strtotime("-$dias days")) . sprintf(' %02d:%02d:00', $horas[array_rand($horas)], mt_rand(0, 59));
        if ($quando > date('Y-m-d H:i:s')) $quando = date('Y-m-d H:i:s', strtotime('-1 hour'));

        $r = mt_rand(1, 100);
        $estado = $r <= 5 ? 'cancelada' : ($r <= 9 ? 'expirada' : 'confirmada');
        $metodo = $metodos[array_rand($metodos)];
        $ref = $l['referencia'] ?: 'MT-' . date('Y') . '-' . str_pad((string)$l['id_reserva'], 6, '0', STR_PAD_LEFT);
        $idB = (!empty($bilheteiros[(int)$l['id_transportadora']]) && mt_rand(1, 100) <= 35)
             ? $bilheteiros[(int)$l['id_transportadora']][array_rand($bilheteiros[(int)$l['id_transportadora']])] : null;
        $nove = ($metodo === 'emola' ? '8' . mt_rand(6, 7) : '8' . mt_rand(4, 5)) . sprintf('%07d', mt_rand(0, 9999999));
        $tel = '+258 ' . substr($nove, 0, 2) . ' ' . substr($nove, 2, 3) . ' ' . substr($nove, 5);
        $doc = sprintf('%012d', mt_rand(100000000, 999999999)) . chr(mt_rand(65, 90));
        $cancelEm = $estado === 'cancelada' ? date('Y-m-d H:i:s', strtotime($quando) + 3 * 3600) : null;
        if ($cancelEm && $cancelEm > date('Y-m-d H:i:s')) $cancelEm = date('Y-m-d H:i:s');

        $upd->execute([$nomes[array_rand($nomes)], $tel, $doc, $ref, $quando, $idB, $estado, $cancelEm, $l['id_reserva']]);
        if ($estado !== 'expirada') {
            $pag->execute([$l['preco'], $metodo, $estado === 'cancelada' ? 'reembolsado' : 'confirmado', $quando, $ref, $l['id_reserva'],
                           in_array($metodo, ['mpesa', 'emola'], true) ? $nove : null]);
            $bil->execute([$ref . '-' . strtoupper(bin2hex(random_bytes(8))), $estado === 'cancelada' ? 'cancelado' : 'valido', $l['id_reserva'], $quando]);
        }
        $cont[$estado]++;
    }
    $pdo->commit();
    echo '<p>Vendas criadas: <b>' . $cont['confirmada'] . '</b> pagas, <b>' . $cont['cancelada'] . '</b> canceladas (reembolsadas) e <b>' . $cont['expirada'] . '</b> expiradas.</p>';
    echo '<p style="color:#b00"><b>Importante:</b> apague este ficheiro depois de o executar.</p><p><a href="../auth/login.php">Ir para o login</a></p>';
} catch (Throwable $e) {
    $pdo->rollBack();
    echo '<p style="color:#b00">Erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
