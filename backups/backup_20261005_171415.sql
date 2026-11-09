-- Backup MozTransport - 2026-10-05 17:14:15
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `assento`;
CREATE TABLE `assento` (
  `id_assento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(5) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'normal',
  `id_autocarro` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_assento`),
  UNIQUE KEY `uq_assento_autocarro_numero` (`id_autocarro`,`numero`),
  CONSTRAINT `fk_assento_autocarro` FOREIGN KEY (`id_autocarro`) REFERENCES `autocarro` (`id_autocarro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `autocarro`;
CREATE TABLE `autocarro` (
  `id_autocarro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `matricula` varchar(20) NOT NULL,
  `modelo` varchar(60) DEFAULT NULL,
  `capacidade_total` smallint(5) unsigned NOT NULL,
  `id_transportadora` int(10) unsigned NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_autocarro`),
  UNIQUE KEY `uq_autocarro_matricula` (`matricula`),
  KEY `fk_autocarro_transportadora` (`id_transportadora`),
  CONSTRAINT `fk_autocarro_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bilhete`;
CREATE TABLE `bilhete` (
  `id_bilhete` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_qr` varchar(64) NOT NULL,
  `data_emissao` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('valido','usado','cancelado') NOT NULL DEFAULT 'valido',
  `id_reserva` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_bilhete`),
  UNIQUE KEY `uq_bilhete_codigo_qr` (`codigo_qr`),
  UNIQUE KEY `uq_bilhete_reserva` (`id_reserva`),
  CONSTRAINT `fk_bilhete_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `configuracao`;
CREATE TABLE `configuracao` (
  `chave` varchar(50) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`chave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('bloqueio_minutos','5','Minutos de bloqueio após exceder as tentativas');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('recuperacao_validade_minutos','10','Validade do código de recuperação de palavra-passe');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('tentativas_login','5','Tentativas de login falhadas antes do bloqueio');

DROP TABLE IF EXISTS `log_transaccao`;
CREATE TABLE `log_transaccao` (
  `id_log` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(10) unsigned DEFAULT NULL,
  `accao` varchar(60) NOT NULL,
  `entidade` varchar(40) DEFAULT NULL,
  `id_entidade` int(10) unsigned DEFAULT NULL,
  `detalhes` varchar(255) DEFAULT NULL,
  `data_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `idx_log_data` (`data_hora`),
  KEY `fk_log_utilizador` (`id_utilizador`),
  CONSTRAINT `fk_log_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('1','5','Login realizado','utilizador','5',NULL,'2026-10-05 11:58:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('2','5','Logout realizado','utilizador','5',NULL,'2026-10-05 11:59:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('3','6','Login realizado','utilizador','6',NULL,'2026-10-05 11:59:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('4','6','Logout realizado','utilizador','6',NULL,'2026-10-05 12:00:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('5','4','Login realizado','utilizador','4',NULL,'2026-10-05 12:01:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('6','4','Logout realizado','utilizador','4',NULL,'2026-10-05 12:01:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('7','3','Login realizado','utilizador','3',NULL,'2026-10-05 12:02:14','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('8','3','Logout realizado','utilizador','3',NULL,'2026-10-05 12:09:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('9','5','Login realizado','utilizador','5',NULL,'2026-10-05 12:09:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('10','5','Permissão alterada','permissao','6','Bilheteiro: Ver dados de todas as transportadoras = sim','2026-10-05 12:12:25','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('11','5','Permissão alterada','permissao','6','Bilheteiro: Ver dados de todas as transportadoras = não','2026-10-05 12:12:26','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('12','5','Permissão alterada','permissao','1','Bilheteiro: Ver dashboard = não','2026-10-05 12:12:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('13','5','Permissão alterada','permissao','1','Bilheteiro: Ver dashboard = sim','2026-10-05 12:12:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('14','5','Permissão alterada','permissao','2','Bilheteiro: Vender bilhetes = não','2026-10-05 12:12:41','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('15','5','Permissão alterada','permissao','2','Bilheteiro: Vender bilhetes = sim','2026-10-05 12:12:41','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('16','5','Permissão alterada','permissao','10','Gestor: Gerir segurança = sim','2026-10-05 12:12:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('17','5','Permissão alterada','permissao','10','Gestor: Gerir segurança = não','2026-10-05 12:12:57','::1');

DROP TABLE IF EXISTS `pagamento`;
CREATE TABLE `pagamento` (
  `id_pagamento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,2) NOT NULL,
  `metodo` enum('mpesa','emola','cartao','numerario') NOT NULL,
  `estado` enum('pendente','confirmado','falhado') NOT NULL DEFAULT 'pendente',
  `data_pagamento` datetime DEFAULT NULL,
  `referencia_transacao` varchar(60) DEFAULT NULL,
  `id_reserva` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_pagamento`),
  UNIQUE KEY `uq_pagamento_reserva` (`id_reserva`),
  UNIQUE KEY `uq_pagamento_referencia` (`referencia_transacao`),
  CONSTRAINT `fk_pagamento_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `perfil_permissao`;
CREATE TABLE `perfil_permissao` (
  `perfil` enum('bilheteiro','cobrador','gestor','administrador') NOT NULL,
  `id_permissao` int(10) unsigned NOT NULL,
  `concedida` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`perfil`,`id_permissao`),
  KEY `fk_pp_permissao` (`id_permissao`),
  CONSTRAINT `fk_pp_permissao` FOREIGN KEY (`id_permissao`) REFERENCES `permissao` (`id_permissao`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','1','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','2','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','3','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','4','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','5','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','6','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','7','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','8','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','9','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','10','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','1','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','2','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','3','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','4','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','5','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','6','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','7','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','8','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','9','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','10','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','1','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','2','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','3','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','4','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','5','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','6','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','7','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','8','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','9','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','10','0');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','1','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','2','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','3','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','4','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','5','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','6','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','7','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','8','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','9','1');
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','10','1');

DROP TABLE IF EXISTS `permissao`;
CREATE TABLE `permissao` (
  `id_permissao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(40) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `alerta_seguranca` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_permissao`),
  UNIQUE KEY `uq_permissao_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('1','ver_dashboard','Ver dashboard','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('2','vender_bilhetes','Vender bilhetes','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('3','gerir_rotas','Gerir rotas','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('4','ver_relatorios','Ver relatórios','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('5','gerir_utilizadores','Gerir utilizadores','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('6','ver_dados_todas','Ver dados de todas as transportadoras','1');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('7','gerir_permissoes','Gerir permissões','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('8','gerir_transportadoras','Gerir transportadoras','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('9','ver_auditoria','Ver auditoria','0');
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('10','gerir_seguranca','Gerir segurança','0');

DROP TABLE IF EXISTS `recuperacao_senha`;
CREATE TABLE `recuperacao_senha` (
  `id_recuperacao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(10) unsigned NOT NULL,
  `codigo_hash` varchar(255) NOT NULL,
  `data_expiracao` datetime NOT NULL,
  `tentativas` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_recuperacao`),
  KEY `idx_recuperacao_utilizador` (`id_utilizador`,`usado`),
  CONSTRAINT `fk_recuperacao_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reserva`;
CREATE TABLE `reserva` (
  `id_reserva` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `data_expiracao` datetime DEFAULT NULL,
  `estado` enum('pendente','confirmada','cancelada','expirada') NOT NULL DEFAULT 'pendente',
  `id_passageiro` int(10) unsigned DEFAULT NULL,
  `nome_passageiro` varchar(100) DEFAULT NULL,
  `telefone_passageiro` varchar(30) DEFAULT NULL,
  `id_viagem` int(10) unsigned NOT NULL,
  `id_assento` int(10) unsigned NOT NULL,
  `id_bilheteiro` int(10) unsigned DEFAULT NULL,
  `assento_ocupado` tinyint(4) GENERATED ALWAYS AS (if(`estado` in ('pendente','confirmada'),1,NULL)) STORED,
  PRIMARY KEY (`id_reserva`),
  UNIQUE KEY `uq_reserva_assento_viagem` (`id_viagem`,`id_assento`,`assento_ocupado`),
  KEY `idx_reserva_passageiro` (`id_passageiro`),
  KEY `idx_reserva_estado` (`estado`,`data_expiracao`),
  KEY `fk_reserva_assento` (`id_assento`),
  KEY `fk_reserva_bilheteiro` (`id_bilheteiro`),
  CONSTRAINT `fk_reserva_assento` FOREIGN KEY (`id_assento`) REFERENCES `assento` (`id_assento`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_bilheteiro` FOREIGN KEY (`id_bilheteiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_viagem` FOREIGN KEY (`id_viagem`) REFERENCES `viagem` (`id_viagem`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rota`;
CREATE TABLE `rota` (
  `id_rota` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origem` varchar(80) NOT NULL,
  `destino` varchar(80) NOT NULL,
  `distancia_km` decimal(8,2) DEFAULT NULL,
  `duracao_estimada` time DEFAULT NULL,
  `id_transportadora` int(10) unsigned NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_rota`),
  KEY `idx_rota_origem_destino` (`origem`,`destino`),
  KEY `fk_rota_transportadora` (`id_transportadora`),
  CONSTRAINT `fk_rota_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transportadora`;
CREATE TABLE `transportadora` (
  `id_transportadora` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `nuit` varchar(20) NOT NULL,
  `contacto` varchar(30) NOT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_transportadora`),
  UNIQUE KEY `uq_transportadora_nuit` (`nuit`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('1','Nagi Investimentos','400000001','+258 84 000 0001','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('2','CityLink','400000002','+258 84 000 0002','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('3','Lalgy','400000003','+258 84 000 0003','Maputo','1');

DROP TABLE IF EXISTS `utilizador`;
CREATE TABLE `utilizador` (
  `id_utilizador` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `agencia` varchar(100) DEFAULT NULL,
  `perfil` enum('passageiro','bilheteiro','cobrador','gestor','administrador') NOT NULL DEFAULT 'passageiro',
  `id_transportadora` int(10) unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tentativas_falhadas` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utilizador`),
  UNIQUE KEY `uq_utilizador_email` (`email`),
  KEY `idx_utilizador_perfil` (`perfil`),
  KEY `fk_utilizador_transportadora` (`id_transportadora`),
  CONSTRAINT `fk_utilizador_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('1','Ioramo Satar','ioramo.satar@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,NULL,'passageiro',NULL,'1','2026-10-05 11:57:08','0',NULL,NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('2','Manuel de Jesus Zoca','manuel.zoca@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,'Maputo – Terminal Junta','bilheteiro','1','1','2026-10-05 11:57:08','0',NULL,NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('3','Mikael Chongo','mikael.chongo@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,'Maputo – Terminal Junta','cobrador','3','1','2026-10-05 11:57:08','0',NULL,'2026-10-05 17:02:14');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('4','Peresterello Nhalicolo','p.nhalicolo@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,'Maputo – Terminal Junta','gestor','3','1','2026-10-05 11:57:08','0',NULL,'2026-10-05 17:01:10');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('5','Nelson Nhaven','nelson.nhaven@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,NULL,'administrador',NULL,'1','2026-10-05 11:57:08','0',NULL,'2026-10-05 17:09:42');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`) VALUES ('6','Jose Junior','jose.junior@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,'Maputo – Terminal Junta','bilheteiro','2','1','2026-10-05 11:57:08','0',NULL,'2026-10-05 16:59:49');

DROP TABLE IF EXISTS `viagem`;
CREATE TABLE `viagem` (
  `id_viagem` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `hora_partida` time NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estado` enum('agendada','em_curso','concluida','cancelada') NOT NULL DEFAULT 'agendada',
  `id_rota` int(10) unsigned NOT NULL,
  `id_autocarro` int(10) unsigned NOT NULL,
  `id_cobrador` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_viagem`),
  KEY `idx_viagem_data` (`data`,`hora_partida`),
  KEY `fk_viagem_rota` (`id_rota`),
  KEY `fk_viagem_autocarro` (`id_autocarro`),
  KEY `fk_viagem_cobrador` (`id_cobrador`),
  CONSTRAINT `fk_viagem_autocarro` FOREIGN KEY (`id_autocarro`) REFERENCES `autocarro` (`id_autocarro`) ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_cobrador` FOREIGN KEY (`id_cobrador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_rota` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS=1;
