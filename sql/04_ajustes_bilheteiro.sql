-- ============================================================
-- Ajustes à base de dados para a área do Bilheteiro (venda presencial)
-- Executar DEPOIS de 03_ajustes_passageiro.sql
-- ============================================================
USE bilhetes_interprovinciais;

-- Reserva: dados do passageiro no balcão e agrupamento de vendas com vários lugares
ALTER TABLE reserva
  ADD COLUMN tipo_documento       VARCHAR(30) NULL,
  ADD COLUMN documento_passageiro VARCHAR(30) NULL,
  ADD COLUMN grupo_venda          VARCHAR(24) NULL,
  ADD KEY idx_reserva_grupo (grupo_venda);
