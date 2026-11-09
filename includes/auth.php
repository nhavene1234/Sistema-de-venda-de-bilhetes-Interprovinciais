<?php
// includes/auth.php - tentativa de login (partilhada pelo login do passageiro)

/** Devolve ['ok'=>bool, 'erro'=>?string, 'bloqueio'=>segundos, 'perfil'=>?string] */
function tentar_login(PDO $pdo, string $email, string $senha, bool $lembrar): array {
    $max = (int)config_valor($pdo, 'tentativas_login', 5);
    $minutosBloqueio = (int)config_valor($pdo, 'bloqueio_minutos', 5);
    $agora = time();

    $st = $pdo->prepare('SELECT * FROM utilizador WHERE email = ? LIMIT 1');
    $st->execute([$email]);
    $u = $st->fetch();
    if (!$u) return ['ok' => false, 'erro' => 'Email ou palavra-passe incorrectos.', 'bloqueio' => 0];

    if ($u['bloqueado_ate'] && strtotime($u['bloqueado_ate']) <= $agora) {
        $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
            ->execute([$u['id_utilizador']]);
        $u['tentativas_falhadas'] = 0;
        $u['bloqueado_ate'] = null;
    }
    if ($u['bloqueado_ate']) {
        return ['ok' => false, 'erro' => null, 'bloqueio' => strtotime($u['bloqueado_ate']) - $agora];
    }
    if (!(int)$u['activo']) {
        return ['ok' => false, 'erro' => 'A sua conta foi desactivada. Contacte o suporte.', 'bloqueio' => 0];
    }

    if (password_verify($senha, $u['senha'])) {
        session_regenerate_id(true);
        $_SESSION['utilizador'] = [
            'id_utilizador' => (int)$u['id_utilizador'], 'nome' => $u['nome'], 'email' => $u['email'],
            'perfil' => $u['perfil'], 'id_transportadora' => $u['id_transportadora'],
            'foto' => $u['foto'], 'agencia' => $u['agencia'],
        ];
        if ($lembrar) {
            setcookie(session_name(), session_id(), [
                'expires' => time() + 30 * 86400, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
            ]);
        }
        $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = NULL, ultimo_acesso = ? WHERE id_utilizador = ?')
            ->execute([date('Y-m-d H:i:s'), $u['id_utilizador']]);
        registar_log($pdo, (int)$u['id_utilizador'], 'Login realizado', 'utilizador', (int)$u['id_utilizador']);
        return ['ok' => true, 'erro' => null, 'bloqueio' => 0, 'perfil' => $u['perfil']];
    }

    $n = (int)$u['tentativas_falhadas'] + 1;
    if ($n >= $max) {
        $ate = date('Y-m-d H:i:s', $agora + $minutosBloqueio * 60);
        $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = ? WHERE id_utilizador = ?')
            ->execute([$ate, $u['id_utilizador']]);
        registar_log($pdo, (int)$u['id_utilizador'], 'Conta bloqueada por tentativas falhadas', 'utilizador', (int)$u['id_utilizador']);
        return ['ok' => false, 'erro' => null, 'bloqueio' => $minutosBloqueio * 60];
    }
    $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = ? WHERE id_utilizador = ?')->execute([$n, $u['id_utilizador']]);
    return ['ok' => false, 'erro' => 'Email ou palavra-passe incorrectos.', 'bloqueio' => 0];
}
