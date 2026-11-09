<?php
// includes/sessao.php - início de sessão e controlo de acesso por perfil
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/funcoes.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', (string)(30 * 86400));
    session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function utilizador_logado(): ?array {
    return $_SESSION['utilizador'] ?? null;
}

function exigir_login(): void {
    global $pdo;
    $u = utilizador_logado();
    if (!$u) {
        redirecionar('auth/login.php');
    }
    // se o administrador bloqueou a conta entretanto, termina a sessão
    $st = $pdo->prepare('SELECT activo FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    $activo = $st->fetchColumn();
    if ($activo === false || !(int)$activo) {
        $_SESSION = [];
        session_destroy();
        session_start();
        flash('erro', 'A sua conta foi desactivada. Contacte o administrador.');
        redirecionar('auth/login.php');
    }
}

function exigir_perfil(array $perfis): void {
    exigir_login();
    if (!in_array($_SESSION['utilizador']['perfil'], $perfis, true)) {
        http_response_code(403);
        die('Acesso negado: não tem permissão para aceder a esta página.');
    }
}

function tem_permissao(PDO $pdo, string $codigo): bool {
    $u = utilizador_logado();
    if (!$u) return false;
    $st = $pdo->prepare(
        'SELECT pp.concedida FROM perfil_permissao pp
         JOIN permissao p ON p.id_permissao = pp.id_permissao
         WHERE pp.perfil = ? AND p.codigo = ?'
    );
    $st->execute([$u['perfil'], $codigo]);
    return (bool)$st->fetchColumn();
}

/** Garante que quem acede é um passageiro autenticado (senão vai para o login do passageiro). */
function exigir_passageiro(): array {
    global $pdo;
    $u = utilizador_logado();
    if (!$u) {
        redirecionar('passageiro/entrar.php');
    }
    if ($u['perfil'] !== 'passageiro') {
        redirecionar(pagina_inicial_perfil($u['perfil']));
    }
    $st = $pdo->prepare('SELECT activo FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    $activo = $st->fetchColumn();
    if ($activo === false || !(int)$activo) {
        $_SESSION = [];
        session_destroy();
        session_start();
        flash('erro', 'A sua conta foi desactivada. Contacte o suporte.');
        redirecionar('passageiro/entrar.php');
    }
    return $u;
}

/** Bloqueia o acesso se o perfil do utilizador não tiver a permissão (matriz de permissões do Administrador). */
function exigir_permissao(PDO $pdo, string $codigo): void {
    if (!tem_permissao($pdo, $codigo)) {
        http_response_code(403);
        die('Acesso negado: o seu perfil não tem permissão para esta operação. Contacte o administrador.');
    }
}
