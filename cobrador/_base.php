<?php
/* Base comum do módulo do cobrador (guarda de acesso, contexto, ícones e layout). */
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/notificacoes.php';

$u = utilizador_logado();
if (!$u || !in_array($u['perfil'], ['cobrador', 'motorista'], true)) {
    if (defined('CB_API')) {
        http_response_code(401); header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'titulo' => 'Sessão expirada', 'msg' => 'Inicie sessão novamente.']); exit;
    }
    redirecionar('auth/login.php');
}

/**
 * Contexto do cobrador: transportadora, viagens de hoje que o gestor lhe atribuiu e contagem de embarques.
 * A associação é feita pelo Gestor (depois do login), por isso lê-se sempre da BD e nunca da sessão.
 * Só conta o que pertence à transportadora actual do cobrador.
 */
function cb_ctx(PDO $pdo, array $u): array {
    $st = $pdo->prepare('SELECT id_transportadora, activo FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    $conta = $st->fetch();
    $idT = ($conta && (int)$conta['activo']) ? (int)$conta['id_transportadora'] : 0;

    $empresa = ''; $viagens = []; $proximas = []; $viagem = null; $tot = 0; $emb = 0;
    if ($idT) {
        $st = $pdo->prepare('SELECT nome FROM transportadora WHERE id_transportadora = ?');
        $st->execute([$idT]);
        $empresa = (string)$st->fetchColumn();

        // todas as viagens (de hoje em diante) que o gestor atribuiu a este cobrador
        $hoje = date('Y-m-d');
        $st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, ro.id_transportadora, ro.origem, ro.destino, au.matricula, au.codigo_frota,
                                    au.marca, au.modelo, au.tipo_servico, au.capacidade_total, m.nome AS motorista, m.codigo AS motorista_codigo,
                                    m.telefone AS motorista_telefone
                             FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro au ON au.id_autocarro = v.id_autocarro
                             LEFT JOIN motorista m ON m.id_motorista = v.id_motorista
                             WHERE v.id_cobrador = ? AND ro.id_transportadora = ? AND v.data >= ? AND v.estado IN ('agendada','em_curso')
                             ORDER BY v.data, v.hora_partida LIMIT 30");
        $st->execute([$u['id_utilizador'], $idT, $hoje]);
        $proximas = $st->fetchAll();
        // a validação de bilhetes só abre no dia da viagem
        $viagens = array_values(array_filter($proximas, fn($x) => $x['data'] === $hoje));

        // viagem em ecrã: a escolhida (?v=) ou a anterior da sessão, se ainda for sua; senão, a primeira do dia
        $pedida = isset($_GET['v']) ? (int)$_GET['v'] : (int)($_SESSION['cb_viagem'] ?? 0);
        foreach ($viagens as $x) { if ((int)$x['id_viagem'] === $pedida) { $viagem = $x; break; } }
        if (!$viagem && $viagens) $viagem = $viagens[0];
                if ($viagem) {
            $_SESSION['cb_viagem'] = (int)$viagem['id_viagem'];
            $st = $pdo->prepare("SELECT COUNT(*), COUNT(embarcado_em) FROM reserva WHERE id_viagem = ? AND estado = 'confirmada'");
            $st->execute([$viagem['id_viagem']]);
            [$tot, $emb] = array_map('intval', $st->fetch(PDO::FETCH_NUM));
        }

    }
    return ['id_transp' => $idT, 'empresa' => $empresa, 'viagem' => $viagem, 'viagens' => $viagens, 'proximas' => $proximas,
            'destaque' => $viagem ?? ($proximas[0] ?? null),   // viagem a mostrar (motorista/autocarro), mesmo que não seja hoje
            'total' => $tot, 'emb' => $emb, 'falta' => max(0, $tot - $emb)];
}

function cb_ic(string $n, int $s = 22): string {
    static $p = [
        'bus' => '<rect x="5" y="3" width="14" height="15" rx="2.5"/><path d="M5 11h14M8 21v-3M16 21v-3"/><circle cx="9" cy="14.5" r=".7"/><circle cx="15" cy="14.5" r=".7"/>',
        'scan' => '<path d="M4 8V6a2 2 0 012-2h2M16 4h2a2 2 0 012 2v2M20 16v2a2 2 0 01-2 2h-2M8 20H6a2 2 0 01-2-2v-2M4 12h16"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-5.5 6-5.5s6 2.2 6 5.5"/><path d="M16 5a3 3 0 010 6M18 14.8c1.8.6 3 2.2 3 5.2"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'x' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'radio' => '<circle cx="12" cy="12" r="2"/><path d="M8 8a5.7 5.7 0 000 8M16 8a5.7 5.7 0 010 8M5 5a10 10 0 000 14M19 5a10 10 0 010 14"/>',
        'torch' => '<path d="M8 3h8l-1 5H9zM9 8v11a2 2 0 002 2h2a2 2 0 002-2V8M12 11v3"/>',
        'kbd' => '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M7 10h.01M11 10h.01M15 10h.01M7 14h10"/>',
        'gear' => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/>',
        'dots' => '<circle cx="6" cy="12" r="1.2"/><circle cx="12" cy="12" r="1.2"/><circle cx="18" cy="12" r="1.2"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/>',
        'headset' => '<path d="M4 15v-3a8 8 0 0116 0v3"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/>',
        'building' => '<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M9 7h.01M13 7h.01M9 11h.01M13 11h.01M10 21v-4h4v4"/>',
        'id' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="3"/>',
        'logout' => '<path d="M9 4H6a2 2 0 00-2 2v12a2 2 0 002 2h3M15 8l4 4-4 4M19 12H9"/>',
        'lock' => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/>',
        'chev' => '<path d="M9 6l6 6-6 6"/>',
        'bell' => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
    ];
    return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$n] ?? '') . '</svg>';
}

function cb_abrir(string $titulo, string $classeApp = ''): void { ?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e($titulo) ?> · <?= e(APP_NOME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('cobrador/cobrador.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/notificacoes.css') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/css/notificacoes.css')) ?>">
</head>
<body class="cb">
<div class="app <?= e($classeApp) ?>">
<?php }

function cb_fechar(?string $activa = null): void {
    if ($activa !== null) {
        $itens = ['viagem' => ['Viagem', 'bus', 'index.php'], 'validar' => ['Validar', 'scan', 'validar.php'],
                  'passageiros' => ['Passageiros', 'users', 'passageiros.php'], 'perfil' => ['Perfil', 'user', 'perfil.php']];
        echo '<nav class="nav">';
        foreach ($itens as $k => [$nome, $ico, $f]) {
            echo '<a href="' . e(url('cobrador/' . $f)) . '" class="' . ($k === $activa ? 'activo' : '') . '">' . cb_ic($ico) . '<span>' . e($nome) . '</span></a>';
        }
        echo '</nav>';
    }
    echo '</div>'; // .app
}
function cb_fim(): void {
    echo '<script src="' . e(url('assets/js/contacto.js')) . '"></script>';
    echo '<script src="' . e(url('assets/js/notificacoes.js') . '?v=' . (int)@filemtime(__DIR__ . '/../assets/js/notificacoes.js')) . '"></script></body></html>';
}