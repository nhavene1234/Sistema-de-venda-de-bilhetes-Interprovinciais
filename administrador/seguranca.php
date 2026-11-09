<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_perfil(['administrador']);
// Backup, bloqueio de contas e uptime ficam no mesmo ecrã da Auditoria, como no protótipo
redirecionar('administrador/auditoria.php#seguranca');
