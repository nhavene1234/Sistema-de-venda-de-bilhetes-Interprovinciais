-- ============================================================
-- Ajustes à base de dados para as telas comuns e do Administrador
-- Executar DEPOIS de bilhetes_interprovinciais.sql
-- ============================================================
USE bilhetes_interprovinciais;

-- Utilizador: foto, agência, bloqueio por tentativas falhadas, último acesso
ALTER TABLE utilizador
  ADD COLUMN foto                VARCHAR(255) NULL AFTER telefone,
  ADD COLUMN agencia             VARCHAR(100) NULL AFTER foto,
  ADD COLUMN tentativas_falhadas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  ADD COLUMN bloqueado_ate       DATETIME NULL,
  ADD COLUMN ultimo_acesso       DATETIME NULL;

-- Estado activo/inactivo (ecrãs de Transportadoras, Frota e Rotas)
ALTER TABLE transportadora ADD COLUMN activa  TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE autocarro      ADD COLUMN activo  TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE rota           ADD COLUMN activa  TINYINT(1) NOT NULL DEFAULT 1;

-- Auditoria: endereço IP de cada acção
ALTER TABLE log_transaccao ADD COLUMN ip VARCHAR(45) NULL;

-- Recuperação de palavra-passe por código de 6 dígitos (guardado cifrado)
DROP TABLE IF EXISTS recuperacao_senha;
CREATE TABLE recuperacao_senha (
  id_recuperacao INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_utilizador  INT UNSIGNED NOT NULL,
  codigo_hash    VARCHAR(255) NOT NULL,
  data_expiracao DATETIME     NOT NULL,
  tentativas     TINYINT UNSIGNED NOT NULL DEFAULT 0,
  usado          TINYINT(1)   NOT NULL DEFAULT 0,
  data_criacao   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_recuperacao),
  KEY idx_recuperacao_utilizador (id_utilizador, usado),
  CONSTRAINT fk_recuperacao_utilizador
    FOREIGN KEY (id_utilizador) REFERENCES utilizador (id_utilizador)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Configurações do sistema (ecrã Segurança)
CREATE TABLE configuracao (
  chave     VARCHAR(50)  NOT NULL,
  valor     VARCHAR(100) NOT NULL,
  descricao VARCHAR(200) NULL,
  PRIMARY KEY (chave)
) ENGINE=InnoDB;

INSERT INTO configuracao (chave, valor, descricao) VALUES
  ('tentativas_login',            '5',  'Tentativas de login falhadas antes do bloqueio'),
  ('bloqueio_minutos',            '5',  'Minutos de bloqueio após exceder as tentativas'),
  ('recuperacao_validade_minutos','10', 'Validade do código de recuperação de palavra-passe');

-- Matriz de permissões (ecrã Permissões)
CREATE TABLE permissao (
  id_permissao     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo           VARCHAR(40)  NOT NULL,
  descricao        VARCHAR(100) NOT NULL,
  alerta_seguranca TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (id_permissao),
  UNIQUE KEY uq_permissao_codigo (codigo)
) ENGINE=InnoDB;

CREATE TABLE perfil_permissao (
  perfil       ENUM('bilheteiro','cobrador','gestor','administrador') NOT NULL,
  id_permissao INT UNSIGNED NOT NULL,
  concedida    TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (perfil, id_permissao),
  CONSTRAINT fk_pp_permissao FOREIGN KEY (id_permissao)
    REFERENCES permissao (id_permissao) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO permissao (codigo, descricao, alerta_seguranca) VALUES
  ('ver_dashboard',          'Ver dashboard',                          0),
  ('vender_bilhetes',        'Vender bilhetes',                        0),
  ('gerir_rotas',            'Gerir rotas',                            0),
  ('ver_relatorios',         'Ver relatórios',                         0),
  ('gerir_utilizadores',     'Gerir utilizadores',                     0),
  ('ver_dados_todas',        'Ver dados de todas as transportadoras',  1),
  ('gerir_permissoes',       'Gerir permissões',                       0),
  ('gerir_transportadoras',  'Gerir transportadoras',                  0),
  ('ver_auditoria',          'Ver auditoria',                          0),
  ('gerir_seguranca',        'Gerir segurança',                        0);

INSERT INTO perfil_permissao (perfil, id_permissao, concedida)
SELECT pf.perfil, pe.id_permissao,
  CASE
    WHEN pf.perfil = 'administrador'                                          THEN 1
    WHEN pe.codigo = 'ver_dashboard'                                          THEN 1
    WHEN pe.codigo = 'vender_bilhetes' AND pf.perfil = 'bilheteiro'           THEN 1
    WHEN pe.codigo IN ('gerir_rotas','ver_relatorios') AND pf.perfil = 'gestor' THEN 1
    ELSE 0
  END
FROM (SELECT 'bilheteiro' AS perfil UNION ALL SELECT 'cobrador'
      UNION ALL SELECT 'gestor' UNION ALL SELECT 'administrador') pf
CROSS JOIN permissao pe;
