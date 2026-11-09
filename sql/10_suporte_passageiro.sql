-- ============================================================
-- Suporte ao passageiro: pedidos de ajuda e conversa com o administrador.
-- Executar DEPOIS de 09_notificacoes.sql.  É seguro executar mais do que uma vez.
-- (Se não o executar, o sistema cria estas tabelas sozinho na primeira utilização.)
-- ============================================================
USE bilhetes_interprovinciais;

CREATE TABLE IF NOT EXISTS suporte_pedido (
  id_pedido         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_passageiro     INT UNSIGNED NULL,                -- NULL = visitante sem sessão (pedido feito antes de entrar)
  nome_contacto     VARCHAR(100) NOT NULL,
  contacto          VARCHAR(120) NOT NULL,            -- telefone ou email para responder
  categoria         ENUM('senha','pagamento','bilhete','reserva','transportadora','conta','outro') NOT NULL,
  id_reserva        INT UNSIGNED NULL,
  assunto           VARCHAR(150) NOT NULL,
  estado            ENUM('aberto','em_curso','resolvido') NOT NULL DEFAULT 'aberto',
  id_admin          INT UNSIGNED NULL,
  ip                VARCHAR(45)  NULL,
  data_criacao      DATETIME     NOT NULL,
  data_actualizacao DATETIME     NOT NULL,
  PRIMARY KEY (id_pedido),
  KEY idx_sp_estado (estado, data_actualizacao),
  KEY idx_sp_passageiro (id_passageiro),
  KEY idx_sp_ip (ip, data_criacao),
  CONSTRAINT fk_sp_passageiro FOREIGN KEY (id_passageiro) REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_sp_admin      FOREIGN KEY (id_admin)      REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_sp_reserva    FOREIGN KEY (id_reserva)    REFERENCES reserva (id_reserva)       ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS suporte_mensagem (
  id_mensagem   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_pedido     INT UNSIGNED NOT NULL,
  autor         ENUM('passageiro','administrador') NOT NULL,
  id_utilizador INT UNSIGNED NULL,
  texto         TEXT         NOT NULL,
  data_hora     DATETIME     NOT NULL,
  PRIMARY KEY (id_mensagem),
  KEY idx_spm_pedido (id_pedido, id_mensagem),
  KEY idx_spm_data (autor, data_hora),
  CONSTRAINT fk_spm_pedido FOREIGN KEY (id_pedido) REFERENCES suporte_pedido (id_pedido) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
