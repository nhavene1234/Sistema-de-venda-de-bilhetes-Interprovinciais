<?php
// config/app.php - definições gerais do projecto
date_default_timezone_set('Africa/Maputo');   // hora de Moçambique (CAT, UTC+2)

define('APP_NOME', 'MozTransport');
define('BASE_URL', '/bilhetes');            // pasta do projecto dentro de htdocs
define('AMBIENTE_DEMO', true);              // true: mostra perfis de teste no login e o código de recuperação no ecrã
define('MAX_FOTO_BYTES', 5 * 1024 * 1024);  // 5 MB