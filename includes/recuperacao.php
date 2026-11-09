<?php
// includes/recuperacao.php - código de recuperação de palavra-passe (6 dígitos, guardado cifrado)

function criar_codigo_recuperacao(PDO $pdo, int $idUtilizador, int $validadeMin): string {
    $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE id_utilizador = ? AND usado = 0')->execute([$idUtilizador]);
    $pdo->prepare('INSERT INTO recuperacao_senha (id_utilizador, codigo_hash, data_expiracao) VALUES (?, ?, ?)')
        ->execute([$idUtilizador, password_hash($codigo, PASSWORD_DEFAULT), date('Y-m-d H:i:s', time() + $validadeMin * 60)]);
    return $codigo;
}

function enviar_codigo_email(string $email, string $codigo): void {
    // Em produção: email/SMS reais. No XAMPP o mail() normalmente não está configurado.
    @mail($email, 'Código de recuperação - ' . APP_NOME, "O seu código de verificação é: $codigo");
}
