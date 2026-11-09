<?php
// index.php - ponto de entrada: leva cada utilizador para o seu painel
require_once __DIR__ . '/includes/sessao.php';
$u = utilizador_logado();
redirecionar($u ? pagina_inicial_perfil($u['perfil']) : 'auth/login.php');
