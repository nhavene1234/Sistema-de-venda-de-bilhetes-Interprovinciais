-- ============================================================
-- Sistema de Venda de Bilhetes Interprovinciais
-- Base de dados: bilhetes_interprovinciais (MySQL/MariaDB - XAMPP)
-- Baseado no Modelo Entidade-Relacionamento do documento
-- ============================================================

CREATE DATABASE IF NOT EXISTS bilhetes_interprovinciais
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bilhetes_interprovinciais;

-- Ordem de remoção inversa às dependências (útil para recriar a BD)
DROP TABLE IF EXISTS log_transaccao;
DROP TABLE IF EXISTS recuperacao_senha;
DROP TABLE IF EXISTS bilhete;
DROP TABLE IF EXISTS pagamento;
DROP TABLE IF EXISTS reserva;
DROP TABLE IF EXISTS viagem;
DROP TABLE IF EXISTS rota;
DROP TABLE IF EXISTS assento;
DROP TABLE IF EXISTS autocarro;
DROP TABLE IF EXISTS utilizador;
DROP TABLE IF EXISTS transportadora;

-- ------------------------------------------------------------
-- TRANSPORTADORA
-- ------------------------------------------------------------
CREATE TABLE transportadora (
  id_transportadora INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome              VARCHAR(100) NOT NULL,
  nuit              VARCHAR(20)  NOT NULL,
  contacto          VARCHAR(30)  NOT NULL,
  endereco          VARCHAR(200) NULL,
  PRIMARY KEY (id_transportadora),
  UNIQUE KEY uq_transportadora_nuit (nuit)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- UTILIZADOR (todos os perfis)
-- id_transportadora: vincula gestores, bilheteiros e cobradores
-- à sua transportadora (RN13). Fica NULL para passageiros e admin.
-- ------------------------------------------------------------
CREATE TABLE utilizador (
  id_utilizador     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome              VARCHAR(100) NOT NULL,
  email             VARCHAR(120) NOT NULL,
  senha             VARCHAR(255) NOT NULL,  -- guardar com password_hash()
  telefone          VARCHAR(30)  NULL,
  perfil            ENUM('passageiro','bilheteiro','cobrador','gestor','administrador')
                    NOT NULL DEFAULT 'passageiro',
  id_transportadora INT UNSIGNED NULL,
  activo            TINYINT(1)   NOT NULL DEFAULT 1,
  data_criacao      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_utilizador),
  UNIQUE KEY uq_utilizador_email (email),
  KEY idx_utilizador_perfil (perfil),
  CONSTRAINT fk_utilizador_transportadora
    FOREIGN KEY (id_transportadora) REFERENCES transportadora (id_transportadora)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- AUTOCARRO
-- ------------------------------------------------------------
CREATE TABLE autocarro (
  id_autocarro      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  matricula         VARCHAR(20)  NOT NULL,
  modelo            VARCHAR(60)  NULL,
  capacidade_total  SMALLINT UNSIGNED NOT NULL,
  id_transportadora INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_autocarro),
  UNIQUE KEY uq_autocarro_matricula (matricula),
  CONSTRAINT fk_autocarro_transportadora
    FOREIGN KEY (id_transportadora) REFERENCES transportadora (id_transportadora)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- ASSENTO (pertence a um autocarro)
-- ------------------------------------------------------------
CREATE TABLE assento (
  id_assento   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  numero       VARCHAR(5)   NOT NULL,   -- ex.: 1A, 2B
  tipo         VARCHAR(20)  NOT NULL DEFAULT 'normal',
  id_autocarro INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_assento),
  UNIQUE KEY uq_assento_autocarro_numero (id_autocarro, numero),
  CONSTRAINT fk_assento_autocarro
    FOREIGN KEY (id_autocarro) REFERENCES autocarro (id_autocarro)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- ROTA
-- ------------------------------------------------------------
CREATE TABLE rota (
  id_rota           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  origem            VARCHAR(80)  NOT NULL,
  destino           VARCHAR(80)  NOT NULL,
  distancia_km      DECIMAL(8,2) NULL,
  duracao_estimada  TIME         NULL,
  id_transportadora INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_rota),
  KEY idx_rota_origem_destino (origem, destino),
  CONSTRAINT fk_rota_transportadora
    FOREIGN KEY (id_transportadora) REFERENCES transportadora (id_transportadora)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- VIAGEM
-- id_cobrador: utilizador com perfil 'cobrador'
-- ------------------------------------------------------------
CREATE TABLE viagem (
  id_viagem    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  data         DATE         NOT NULL,
  hora_partida TIME         NOT NULL,
  preco        DECIMAL(10,2) NOT NULL,
  estado       ENUM('agendada','em_curso','concluida','cancelada')
               NOT NULL DEFAULT 'agendada',
  id_rota      INT UNSIGNED NOT NULL,
  id_autocarro INT UNSIGNED NOT NULL,
  id_cobrador  INT UNSIGNED NULL,
  PRIMARY KEY (id_viagem),
  KEY idx_viagem_data (data, hora_partida),
  CONSTRAINT fk_viagem_rota
    FOREIGN KEY (id_rota) REFERENCES rota (id_rota)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_viagem_autocarro
    FOREIGN KEY (id_autocarro) REFERENCES autocarro (id_autocarro)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_viagem_cobrador
    FOREIGN KEY (id_cobrador) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- RESERVA
-- * id_passageiro é NULL quando o bilheteiro vende a alguém sem conta;
--   nesse caso usam-se nome_passageiro / telefone_passageiro.
-- * id_bilheteiro só é preenchido nas vendas presenciais.
-- * data_expiracao: fim do bloqueio temporário do assento (RN03/RN04).
-- * assento_ocupado: coluna calculada que vale 1 enquanto a reserva
--   está 'pendente' ou 'confirmada' e NULL caso contrário. Com o índice
--   único abaixo, o MySQL impede a venda duplicada do mesmo assento na
--   mesma viagem (RN02/RNF01), mas permite reutilizar o assento depois
--   de um cancelamento ou expiração.
-- ------------------------------------------------------------
CREATE TABLE reserva (
  id_reserva         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  data_reserva       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  data_expiracao     DATETIME     NULL,
  estado             ENUM('pendente','confirmada','cancelada','expirada')
                     NOT NULL DEFAULT 'pendente',
  id_passageiro      INT UNSIGNED NULL,
  nome_passageiro    VARCHAR(100) NULL,
  telefone_passageiro VARCHAR(30) NULL,
  id_viagem          INT UNSIGNED NOT NULL,
  id_assento         INT UNSIGNED NOT NULL,
  id_bilheteiro      INT UNSIGNED NULL,
  assento_ocupado    TINYINT GENERATED ALWAYS AS
                     (IF(estado IN ('pendente','confirmada'), 1, NULL)) STORED,
  PRIMARY KEY (id_reserva),
  UNIQUE KEY uq_reserva_assento_viagem (id_viagem, id_assento, assento_ocupado),
  KEY idx_reserva_passageiro (id_passageiro),
  KEY idx_reserva_estado (estado, data_expiracao),
  CONSTRAINT fk_reserva_passageiro
    FOREIGN KEY (id_passageiro) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_reserva_viagem
    FOREIGN KEY (id_viagem) REFERENCES viagem (id_viagem)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_reserva_assento
    FOREIGN KEY (id_assento) REFERENCES assento (id_assento)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_reserva_bilheteiro
    FOREIGN KEY (id_bilheteiro) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- PAGAMENTO
-- ------------------------------------------------------------
CREATE TABLE pagamento (
  id_pagamento         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  valor                DECIMAL(10,2) NOT NULL,
  metodo               ENUM('mpesa','emola','cartao','numerario') NOT NULL,
  estado               ENUM('pendente','confirmado','falhado') NOT NULL DEFAULT 'pendente',
  data_pagamento       DATETIME     NULL,
  referencia_transacao VARCHAR(60)  NULL,
  id_reserva           INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_pagamento),
  UNIQUE KEY uq_pagamento_reserva (id_reserva),
  UNIQUE KEY uq_pagamento_referencia (referencia_transacao),
  CONSTRAINT fk_pagamento_reserva
    FOREIGN KEY (id_reserva) REFERENCES reserva (id_reserva)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- BILHETE (1:1 com a reserva; só existe após pagamento confirmado)
-- ------------------------------------------------------------
CREATE TABLE bilhete (
  id_bilhete   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo_qr    VARCHAR(64)  NOT NULL,
  data_emissao DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado       ENUM('valido','usado','cancelado') NOT NULL DEFAULT 'valido',
  id_reserva   INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_bilhete),
  UNIQUE KEY uq_bilhete_codigo_qr (codigo_qr),
  UNIQUE KEY uq_bilhete_reserva (id_reserva),
  CONSTRAINT fk_bilhete_reserva
    FOREIGN KEY (id_reserva) REFERENCES reserva (id_reserva)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- RECUPERAÇÃO DE PALAVRA-PASSE (RF03 / RN18)
-- ------------------------------------------------------------
CREATE TABLE recuperacao_senha (
  id_recuperacao INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_utilizador  INT UNSIGNED NOT NULL,
  token          VARCHAR(64)  NOT NULL,
  data_expiracao DATETIME     NOT NULL,
  usado          TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (id_recuperacao),
  UNIQUE KEY uq_recuperacao_token (token),
  CONSTRAINT fk_recuperacao_utilizador
    FOREIGN KEY (id_utilizador) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- LOG DE TRANSACÇÕES / AUDITORIA (RNF10)
-- ------------------------------------------------------------
CREATE TABLE log_transaccao (
  id_log        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_utilizador INT UNSIGNED NULL,
  accao         VARCHAR(60)  NOT NULL,   -- ex.: login, reserva_criada, pagamento_confirmado
  entidade      VARCHAR(40)  NULL,       -- ex.: reserva, bilhete, utilizador
  id_entidade   INT UNSIGNED NULL,
  detalhes      VARCHAR(255) NULL,
  data_hora     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_log),
  KEY idx_log_data (data_hora),
  CONSTRAINT fk_log_utilizador
    FOREIGN KEY (id_utilizador) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- DADOS INICIAIS (apenas transportadoras; utilizadores e viagens
-- serão criados pelo próprio sistema / scripts de teste)
-- ------------------------------------------------------------
INSERT INTO transportadora (nome, nuit, contacto, endereco) VALUES
  ('Nagi Investimentos', '400000001', '+258 84 000 0001', 'Maputo'),
  ('CityLink',           '400000002', '+258 84 000 0002', 'Maputo'),
  ('Lalgy',              '400000003', '+258 84 000 0003', 'Maputo');
