<?php
// auth/cadastro.php - mantido por compatibilidade: leva ao cadastro de passageiros
require_once __DIR__ . '/../config/app.php';
header('Location: ' . BASE_URL . '/passageiro/registar.php');
exit;