-- ============================================================
-- Ajustes à base de dados para a área do Passageiro
-- Executar DEPOIS de 02_ajustes_telas_comuns_admin.sql
-- ============================================================
USE bilhetes_interprovinciais;

-- Utilizador: número de documento (BI) e preferência de notificações
ALTER TABLE utilizador
  ADD COLUMN documento    VARCHAR(30) NULL AFTER telefone,
  ADD COLUMN notificacoes TINYINT(1)  NOT NULL DEFAULT 1,
  ADD UNIQUE KEY uq_utilizador_documento (documento);

-- Viagem: classe do autocarro e hora de chegada
ALTER TABLE viagem
  ADD COLUMN classe        VARCHAR(30) NOT NULL DEFAULT 'Convencional' AFTER preco,
  ADD COLUMN hora_chegada  TIME NULL AFTER hora_partida;

-- Reserva: referência legível (ex.: MT-2026-004821) e data de cancelamento
ALTER TABLE reserva
  ADD COLUMN referencia         VARCHAR(20) NULL,
  ADD COLUMN data_cancelamento  DATETIME NULL,
  ADD UNIQUE KEY uq_reserva_referencia (referencia);

-- Pagamento: estado "reembolsado", telefone usado e motivo de falha
ALTER TABLE pagamento
  MODIFY estado ENUM('pendente','confirmado','falhado','reembolsado') NOT NULL DEFAULT 'pendente',
  ADD COLUMN telefone_pagamento VARCHAR(30) NULL,
  ADD COLUMN motivo_falha       VARCHAR(40) NULL;

-- Regras configuráveis (RN03/RN04/RN06)
INSERT INTO configuracao (chave, valor, descricao) VALUES
  ('bloqueio_assento_minutos', '10', 'Minutos que o assento fica bloqueado até ao pagamento'),
  ('cancelamento_horas',       '24', 'Horas antes da partida até às quais se pode cancelar ou remarcar')
ON DUPLICATE KEY UPDATE descricao = VALUES(descricao);
