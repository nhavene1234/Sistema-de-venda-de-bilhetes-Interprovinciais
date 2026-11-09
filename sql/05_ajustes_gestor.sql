-- ============================================================
-- Ajustes à base de dados para a área do Gestor
-- Executar DEPOIS de 04_ajustes_bilheteiro.sql
-- ============================================================
USE bilhetes_interprovinciais;

-- Preço base da rota (usado ao programar novas viagens)
ALTER TABLE rota ADD COLUMN preco_base DECIMAL(10,2) NULL;

-- Preenche o preço base das rotas existentes com o preço da primeira viagem
UPDATE rota ro
SET ro.preco_base = (SELECT v.preco FROM viagem v WHERE v.id_rota = ro.id_rota ORDER BY v.data, v.hora_partida LIMIT 1)
WHERE ro.preco_base IS NULL;
