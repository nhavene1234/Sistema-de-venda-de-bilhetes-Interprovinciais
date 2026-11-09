<?php
// api/notificacoes.php - notificações do sino (resposta JSON, todos os perfis)
//   GET              -> lista completa {ok, novas, agora, desactivadas, itens[]}
//   GET ?contar=1    -> só o número de novas {ok, novas}
//   POST acao=vistas -> marca como vistas as notificações até "ate" (precisa de csrf)
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/notificacoes.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$u = utilizador_logado();
if (!$u) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'Sessão expirada.']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_valido()) {
            http_response_code(403);
            echo json_encode(['ok' => false]);
            exit;
        }
        notif_marcar_vistas($pdo, (int)$u['id_utilizador'], $_POST['ate'] ?? null);
        echo json_encode(['ok' => true]);
        exit;
    }

    session_write_close();   // não bloquear outras páginas enquanto se consulta
    $r = notif_lista($pdo, $u);
    if (isset($_GET['contar'])) {
        echo json_encode(['ok' => true, 'novas' => $r['novas']]);
    } else {
        echo json_encode(['ok' => true] + $r, JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Não foi possível carregar as notificações.']);
}
