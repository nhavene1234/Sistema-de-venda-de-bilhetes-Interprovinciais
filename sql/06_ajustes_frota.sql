-- ============================================================
-- Ajustes à base de dados: autocarros (frota), motoristas e reservas do Gestor
-- Executar DEPOIS de 05_ajustes_gestor.sql
-- ============================================================
USE bilhetes_interprovinciais;

-- Autocarro: identificação, capacidade, comodidades, inspecção e estado
ALTER TABLE autocarro
  ADD COLUMN codigo_frota          VARCHAR(20) NULL,
  ADD COLUMN marca                 VARCHAR(40) NULL,
  ADD COLUMN ano_fabrico           SMALLINT UNSIGNED NULL,
  ADD COLUMN tipo_servico          VARCHAR(30) NULL,
  ADD COLUMN configuracao_assentos VARCHAR(5) NOT NULL DEFAULT '2+2',
  ADD COLUMN ar_condicionado       TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN wifi                  TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN usb                   TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN validade_inspecao     DATE NULL,
  ADD COLUMN livrete               VARCHAR(255) NULL,
  ADD COLUMN estado ENUM('disponivel','manutencao','inactivo') NOT NULL DEFAULT 'disponivel',
  ADD UNIQUE KEY uq_autocarro_codigo (id_transportadora, codigo_frota);

-- Códigos de frota para os autocarros que já existem
UPDATE autocarro SET codigo_frota = CONCAT('AC-', LPAD(id_autocarro, 3, '0')) WHERE codigo_frota IS NULL;
UPDATE autocarro SET estado = IF(activo = 1, 'disponivel', 'inactivo');

-- Motoristas (dados pessoais, carta de condução e dados profissionais)
CREATE TABLE motorista (
  id_motorista      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_transportadora INT UNSIGNED NOT NULL,
  codigo            VARCHAR(20)  NOT NULL,
  nome              VARCHAR(100) NOT NULL,
  documento         VARCHAR(30)  NOT NULL,
  data_nascimento   DATE         NOT NULL,
  telefone          VARCHAR(30)  NOT NULL,
  email             VARCHAR(120) NULL,
  morada            VARCHAR(200) NULL,
  carta_numero      VARCHAR(30)  NOT NULL,
  carta_categoria   VARCHAR(40)  NOT NULL,
  carta_emissao     DATE         NOT NULL,
  carta_validade    DATE         NOT NULL,
  carta_ficheiro    VARCHAR(255) NULL,
  estado            ENUM('disponivel','folga','inactivo') NOT NULL DEFAULT 'disponivel',
  data_criacao      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_motorista),
  UNIQUE KEY uq_motorista_codigo (id_transportadora, codigo),
  UNIQUE KEY uq_motorista_documento (documento),
  UNIQUE KEY uq_motorista_carta (carta_numero),
  CONSTRAINT fk_motorista_transportadora FOREIGN KEY (id_transportadora)
    REFERENCES transportadora (id_transportadora) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Viagem: motorista atribuído
ALTER TABLE viagem
  ADD COLUMN id_motorista INT UNSIGNED NULL,
  ADD CONSTRAINT fk_viagem_motorista FOREIGN KEY (id_motorista)
    REFERENCES motorista (id_motorista) ON UPDATE CASCADE ON DELETE SET NULL;

-- Nova permissão na matriz do Administrador
INSERT INTO permissao (codigo, descricao, alerta_seguranca) VALUES ('gerir_frota', 'Gerir autocarros e motoristas', 0);
INSERT INTO perfil_permissao (perfil, id_permissao, concedida)
SELECT pf.perfil, pe.id_permissao, IF(pf.perfil IN ('gestor', 'administrador'), 1, 0)
FROM (SELECT 'bilheteiro' AS perfil UNION ALL SELECT 'cobrador' UNION ALL SELECT 'gestor' UNION ALL SELECT 'administrador') pf
CROSS JOIN permissao pe WHERE pe.codigo = 'gerir_frota';
