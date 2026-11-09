-- ============================================================
-- Cobradores: associação cobrador -> transportadora -> motorista -> viagem (feita pelo Gestor)
-- Executar DEPOIS de 06_ajustes_frota.sql.  É seguro executar mais do que uma vez.
--
-- Não são necessárias tabelas novas:
--   * cobrador -> transportadora : utilizador.id_transportadora (perfil 'cobrador')
--   * cobrador -> viagem         : viagem.id_cobrador
--   * motorista -> viagem        : viagem.id_motorista
-- Este script só garante as colunas de embarque (usadas na validação dos bilhetes) e um índice.
-- ============================================================
USE bilhetes_interprovinciais;

-- reserva.embarcado_em / embarcado_por (registo de quem validou o bilhete e quando)
SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE reserva ADD COLUMN embarcado_em DATETIME NULL', 'SELECT 1')
          FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reserva' AND COLUMN_NAME = 'embarcado_em');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;

SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE reserva ADD COLUMN embarcado_por INT UNSIGNED NULL', 'SELECT 1')
          FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reserva' AND COLUMN_NAME = 'embarcado_por');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;

SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE reserva ADD INDEX idx_reserva_embarque (id_viagem, embarcado_em)', 'SELECT 1')
          FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'reserva' AND INDEX_NAME = 'idx_reserva_embarque');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;

-- Índice para "viagens deste cobrador" (ecrã do cobrador e atribuição do gestor)
SET @s = (SELECT IF(COUNT(*) = 0, 'ALTER TABLE viagem ADD INDEX idx_viagem_cobrador_data (id_cobrador, data)', 'SELECT 1')
          FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'viagem' AND INDEX_NAME = 'idx_viagem_cobrador_data');
PREPARE q FROM @s; EXECUTE q; DEALLOCATE PREPARE q;
