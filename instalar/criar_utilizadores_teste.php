<?php
// instalar/criar_utilizadores_teste.php
// Cria os utilizadores de demonstração (iguais aos do protótipo). Executar UMA vez e depois APAGAR este ficheiro.
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/conexao.php';

if (!AMBIENTE_DEMO) { die('Desactivado fora do ambiente de demonstração.'); }

$senha = 'Demo@2026';
$hash  = password_hash($senha, PASSWORD_DEFAULT);

function id_transportadora(PDO $pdo, ?string $nome): ?int {
    if (!$nome) return null;
    $st = $pdo->prepare('SELECT id_transportadora FROM transportadora WHERE nome LIKE ?');
    $st->execute([$nome . '%']);
    $id = $st->fetchColumn();
    return $id ? (int)$id : null;
}

// nome, email, perfil, transportadora, agência
$utilizadores = [
    ['Ioramo Satar',          'ioramo.satar@moztransport.co.mz',    'passageiro',    null,        null],
    ['Manuel de Jesus Zoca',  'manuel.zoca@moztransport.co.mz',     'bilheteiro',    'Nagi',      'Maputo – Terminal Junta'],
    ['Mikael Chongo',         'mikael.chongo@moztransport.co.mz',   'cobrador',      'Lalgy',     'Maputo – Terminal Junta'],
    ['Peresterello Nhalicolo','p.nhalicolo@moztransport.co.mz',     'gestor',        'Lalgy',     'Maputo – Terminal Junta'],
    ['Nelson Nhaven',         'nelson.nhaven@moztransport.co.mz',   'administrador', null,        null],
    ['Jose Junior',           'jose.junior@moztransport.co.mz',     'bilheteiro',    'CityLink',  'Maputo – Terminal Junta'],
];

echo '<meta charset="UTF-8"><body style="font-family:sans-serif;max-width:640px;margin:40px auto">';
echo '<h2>Utilizadores de teste</h2><ul>';
foreach ($utilizadores as [$nome, $email, $perfil, $transp, $agencia]) {
    $st = $pdo->prepare('SELECT COUNT(*) FROM utilizador WHERE email = ?');
    $st->execute([$email]);
    if ($st->fetchColumn() > 0) { echo "<li>$email — já existia</li>"; continue; }
    $pdo->prepare('INSERT INTO utilizador (nome, email, senha, perfil, id_transportadora, agencia) VALUES (?,?,?,?,?,?)')
        ->execute([$nome, $email, $hash, $perfil, id_transportadora($pdo, $transp), $agencia]);
    echo "<li>$email — criado ($perfil)</li>";
}
echo "</ul><p>Palavra-passe de todos: <b>$senha</b></p>";
echo '<p style="color:#b00"><b>Importante:</b> apague este ficheiro depois de o executar.</p>';
echo '<p><a href="../auth/login.php">Ir para o login</a></p>';
