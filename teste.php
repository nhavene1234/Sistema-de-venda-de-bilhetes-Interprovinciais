<?php
require 'config/conexao.php';
$total = $pdo->query('SELECT COUNT(*) FROM transportadora')->fetchColumn();
echo "Ligação OK. Transportadoras registadas: $total";