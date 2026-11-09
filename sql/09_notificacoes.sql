-- ============================================================
-- Notificações (sino): guarda quando cada utilizador viu as notificações pela última vez.
-- Executar DEPOIS de 08_renomear_assentos_1A.sql.  É seguro executar mais do que uma vez.
-- (Se não o executar, o sistema cria esta coluna sozinho na primeira utilização do sino.)
-- ============================================================
USE bilhetes_interprovinciais;

SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE utilizador ADD COLUMN notificacoes_vistas_em DATETIME NULL', 'SELECT 1')
          FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'utilizador' AND COLUMN_NAME = 'notificacoes_vistas_em');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;

-- Índice para o log (as notificações consultam por acção e data)
SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE log_transaccao ADD INDEX idx_log_accao_data (accao, data_hora)', 'SELECT 1')
          FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'log_transaccao' AND INDEX_NAME = 'idx_log_accao_data');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;
