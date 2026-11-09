<?php
/* Endpoint JSON do scanner: verificar | confirmar | recusar */
define('CB_API', true);
require_once __DIR__ . '/_base.php';
header('Content-Type: application/json; charset=utf-8');

function saida(array $d): void { echo json_encode($d, JSON_UNESCAPED_UNICODE); exit; }
function normalizar(string $c): string {
    $c = trim($c);
    if (preg_match('/[?&](?:ref|codigo)=([^&]+)/i', $c, $m)) $c = urldecode($m[1]);
    elseif (preg_match('#^https?://#i', $c)) $c = basename(parse_url($c, PHP_URL_PATH) ?: $c);
    $c = preg_replace('/[\x{2010}-\x{2015}\x{2212}]/u', '-', $c);   // travessões -> hífen
    $c = preg_replace('/\s+/u', '', $c);                              // sem espaços
    return strtoupper($c);
}

/**
 * Códigos aceites: o conteúdo completo do QR (MT-2026-000015-A1B2C3D4E5F60718), a referência
 * impressa no bilhete (MT-2026-000015) ou só 2026-000015 (sem o "MT-").
 * Devolve [código completo do QR, referência].
 */
function cb_candidatos(string $c): array {
    $ref = $c;
    if (preg_match('/^(MT-\d{4}-\d{6})-[0-9A-F]{16}$/', $c, $m)) $ref = $m[1];          // QR completo
    elseif (preg_match('/^\d{4}-\d{1,6}$/', $c)) {                                      // 2026-15 / 2026-000015
        [$ano, $n] = explode('-', $c);
        $ref = 'MT-' . $ano . '-' . str_pad($n, 6, '0', STR_PAD_LEFT);
    }
    return [$c, $ref];
}

function cb_verificar(PDO $pdo, array $u, array $c, string $cod): array {
    $base = ['ok' => false, 'codigo' => $cod];
    if ($cod === '') return $base + ['titulo' => 'Código vazio', 'msg' => 'Indique ou leia um código de bilhete.'];
    $st = $pdo->prepare("SELECT rs.id_reserva, rs.estado, rs.embarcado_em, rs.id_viagem, COALESCE(rs.nome_passageiro, us.nome) AS passageiro,
                                a.numero AS assento, v.data, v.hora_partida, ro.origem, ro.destino, ro.id_transportadora
                         FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN assento a ON a.id_assento = rs.id_assento LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                         LEFT JOIN bilhete b ON b.id_reserva = rs.id_reserva
                         WHERE b.codigo_qr = ? OR rs.referencia = ? LIMIT 1");
    $st->execute(cb_candidatos($cod));
    $r = $st->fetch();
    if (!$r) return $base + ['titulo' => 'Bilhete não encontrado', 'msg' => 'O código lido não corresponde a nenhuma reserva.'];
    if ((int)$r['id_transportadora'] !== (int)$c['id_transp'])
        return $base + ['titulo' => 'Bilhete de outra transportadora', 'msg' => 'Este bilhete não pertence à sua empresa.'];
    // só se validam bilhetes das viagens que o gestor da transportadora atribuiu a este cobrador
    $minhas = array_map(fn($x) => (int)$x['id_viagem'], $c['viagens']);
    if (!in_array((int)$r['id_viagem'], $minhas, true))
        return $base + ['titulo' => 'Bilhete de outra viagem', 'msg' => 'Este bilhete é para ' . $r['origem'] . ' → ' . $r['destino'] . ' em ' . date('d/m', strtotime($r['data'])) . ' às '
                        . substr($r['hora_partida'], 0, 5) . ', que não está atribuída a si. Fale com o gestor da transportadora.'];
    if ($r['estado'] !== 'confirmada') {
        $t = ['pendente' => 'Pagamento por confirmar', 'cancelada' => 'Reserva cancelada', 'expirada' => 'Reserva expirada'][$r['estado']] ?? 'Reserva inválida';
        return $base + ['titulo' => $t, 'msg' => 'Só reservas confirmadas podem embarcar.'];
    }
    if ($r['embarcado_em'])
        return $base + ['titulo' => 'Bilhete já utilizado', 'msg' => 'Validação anterior às ' . date('H:i', strtotime($r['embarcado_em'])) . '. Confirme também se é um bilhete de outra viagem.'];
    return ['ok' => true, 'codigo' => $cod, 'id_reserva' => (int)$r['id_reserva'], 'passageiro' => $r['passageiro'], 'assento' => $r['assento'],
            'rota' => $r['origem'] . ' → ' . $r['destino'], 'hora' => substr($r['hora_partida'], 0, 5)];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) saida(['ok' => false, 'titulo' => 'Pedido inválido', 'msg' => 'Actualize a página e tente novamente.']);

$c = cb_ctx($pdo, $u);
if (!$c['id_transp']) saida(['ok' => false, 'titulo' => 'Conta não associada', 'msg' => 'A sua conta ainda não está associada a uma transportadora. Contacte o gestor.']);
if (!$c['viagens']) saida(['ok' => false, 'titulo' => 'Sem viagem atribuída', 'msg' => 'Não tem nenhuma viagem atribuída para hoje. Contacte o gestor da transportadora.']);
$acao = $_POST['acao'] ?? '';
$cod = normalizar($_POST['codigo'] ?? '');
$idU = (int)$u['id_utilizador'];

if ($acao === 'verificar') saida(cb_verificar($pdo, $u, $c, $cod));

if ($acao === 'confirmar') {
    $r = cb_verificar($pdo, $u, $c, $cod);
    if (!$r['ok']) saida($r);
    $st = $pdo->prepare('UPDATE reserva SET embarcado_em = NOW(), embarcado_por = ? WHERE id_reserva = ? AND embarcado_em IS NULL');
    $st->execute([$idU, $r['id_reserva']]);
    if ($st->rowCount() !== 1) saida(['ok' => false, 'codigo' => $cod, 'titulo' => 'Bilhete já utilizado', 'msg' => 'Este bilhete acabou de ser validado.']);
    registar_log($pdo, $idU, 'Embarque confirmado', 'reserva', $r['id_reserva'], $cod);
    saida(['ok' => true, 'confirmado' => true]);
}

if ($acao === 'recusar') {
    registar_log($pdo, $idU, 'Embarque recusado', 'reserva', 0, $cod . ' · ' . mb_substr($_POST['motivo'] ?? '', 0, 120));
    saida(['ok' => true]);
}
saida(['ok' => false, 'titulo' => 'Acção desconhecida', 'msg' => '']);