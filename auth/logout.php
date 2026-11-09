<?php
require_once __DIR__ . '/../includes/sessao.php';
$u = utilizador_logado();
if ($u) {
    registar_log($pdo, (int)$u['id_utilizador'], 'Logout realizado', 'utilizador', (int)$u['id_utilizador']);
}
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();
header('Location: ' . url('auth/login.php'));
exit;
