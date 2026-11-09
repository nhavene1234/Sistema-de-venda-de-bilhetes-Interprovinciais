<?php
// includes/backup.php - cópia de segurança da base de dados em ficheiro .sql (RNF11)

function gerar_backup(PDO $pdo): string {
    $pasta = __DIR__ . '/../backups/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
        file_put_contents($pasta . '.htaccess', "Require all denied\n");  // impede o download pelo navegador
    }
    $nome = 'backup_' . date('Ymd_His') . '.sql';
    $f = fopen($pasta . $nome, 'w');
    fwrite($f, "-- Backup " . APP_NOME . " - " . date('Y-m-d H:i:s') . "\nSET FOREIGN_KEY_CHECKS=0;\n\n");

    $tabelas = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tabelas as $t) {
        $criar = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_NUM)[1];
        fwrite($f, "DROP TABLE IF EXISTS `$t`;\n$criar;\n\n");

        // colunas calculadas não podem ser inseridas
        $cols = [];
        foreach ($pdo->query("SHOW COLUMNS FROM `$t`")->fetchAll() as $c) {
            if (stripos($c['Extra'], 'GENERATED') === false) $cols[] = $c['Field'];
        }
        $lista = '`' . implode('`,`', $cols) . '`';
        foreach ($pdo->query("SELECT $lista FROM `$t`")->fetchAll(PDO::FETCH_NUM) as $lin) {
            $vals = array_map(fn($v) => $v === null ? 'NULL' : $pdo->quote((string)$v), $lin);
            fwrite($f, "INSERT INTO `$t` ($lista) VALUES (" . implode(',', $vals) . ");\n");
        }
        fwrite($f, "\n");
    }
    fwrite($f, "SET FOREIGN_KEY_CHECKS=1;\n");
    fclose($f);

    $pdo->prepare("INSERT INTO configuracao (chave, valor, descricao) VALUES ('ultimo_backup', ?, 'Data do último backup')
                   ON DUPLICATE KEY UPDATE valor = VALUES(valor)")->execute([date('Y-m-d H:i:s')]);
    return $nome;
}
