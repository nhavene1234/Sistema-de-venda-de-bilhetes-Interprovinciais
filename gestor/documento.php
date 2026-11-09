<?php
// gestor/documento.php - entrega documentos (carta de condução, livrete) só a quem tem acesso
require_once __DIR__ . '/../includes/sessao.php';
require_once __DIR__ . '/../includes/gestor.php';
exigir_login();
$u = utilizador_logado();
if (!in_array($u['perfil'], ['gestor', 'administrador'], true)) { http_response_code(403); die('Acesso negado.'); }

$tipo = $_GET['tipo'] ?? '';
$id = (int)($_GET['id'] ?? 0);
if ($tipo === 'carta') { $sql = 'SELECT carta_ficheiro AS f, id_transportadora FROM motorista WHERE id_motorista = ?'; }
elseif ($tipo === 'livrete') { $sql = 'SELECT livrete AS f, id_transportadora FROM autocarro WHERE id_autocarro = ?'; }
else { http_response_code(404); die('Documento não encontrado.'); }

$st = $pdo->prepare($sql);
$st->execute([$id]);
$d = $st->fetch();
if (!$d || !$d['f']) { http_response_code(404); die('Documento não encontrado.'); }

if ($u['perfil'] === 'gestor') {   // RN13: só documentos da própria transportadora
    $st = $pdo->prepare('SELECT id_transportadora FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    if ((int)$st->fetchColumn() !== (int)$d['id_transportadora']) { http_response_code(403); die('Acesso negado.'); }
}

$caminho = realpath(__DIR__ . '/../armazenamento/' . $d['f']);
$base = realpath(__DIR__ . '/../armazenamento');
if (!$caminho || !$base || strpos($caminho, $base) !== 0 || !is_file($caminho)) { http_response_code(404); die('Ficheiro em falta.'); }

$tipos = ['pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'png' => 'image/png'];
header('Content-Type: ' . ($tipos[strtolower(pathinfo($caminho, PATHINFO_EXTENSION))] ?? 'application/octet-stream'));
header('Content-Disposition: inline; filename="' . basename($caminho) . '"');
header('X-Content-Type-Options: nosniff');
readfile($caminho);
