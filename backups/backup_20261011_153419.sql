-- Backup MozTransport - 2026-10-11 15:34:19
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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('1','1A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('2','1B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('3','1C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('4','1D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('5','2A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('6','2B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('7','2C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('8','2D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('9','3A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('10','3B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('11','3C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('12','3D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('13','4A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('14','4B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('15','4C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('16','4D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('17','5A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('18','5B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('19','5C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('20','5D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('21','6A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('22','6B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('23','6C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('24','6D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('25','7A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('26','7B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('27','7C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('28','7D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('29','8A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('30','8B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('31','8C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('32','8D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('33','9A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('34','9B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('35','9C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('36','9D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('37','10A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('38','10B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('39','10C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('40','10D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('41','11A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('42','11B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('43','11C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('44','11D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('45','12A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('46','12B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('47','12C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('48','12D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('49','13A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('50','13B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('51','13C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('52','13D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('53','14A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('54','14B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('55','14C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('56','14D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('57','15A','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('58','15B','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('59','15C','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('60','15D','normal','1');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('61','1A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('62','1B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('63','1C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('64','1D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('65','2A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('66','2B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('67','2C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('68','2D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('69','3A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('70','3B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('71','3C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('72','3D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('73','4A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('74','4B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('75','4C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('76','4D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('77','5A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('78','5B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('79','5C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('80','5D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('81','6A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('82','6B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('83','6C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('84','6D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('85','7A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('86','7B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('87','7C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('88','7D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('89','8A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('90','8B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('91','8C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('92','8D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('93','9A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('94','9B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('95','9C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('96','9D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('97','10A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('98','10B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('99','10C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('100','10D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('101','11A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('102','11B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('103','11C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('104','11D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('105','12A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('106','12B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('107','12C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('108','12D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('109','13A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('110','13B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('111','13C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('112','13D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('113','14A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('114','14B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('115','14C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('116','14D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('117','15A','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('118','15B','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('119','15C','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('120','15D','normal','2');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('121','1A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('122','1B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('123','1C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('124','1D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('125','2A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('126','2B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('127','2C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('128','2D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('129','3A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('130','3B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('131','3C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('132','3D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('133','4A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('134','4B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('135','4C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('136','4D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('137','5A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('138','5B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('139','5C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('140','5D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('141','6A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('142','6B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('143','6C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('144','6D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('145','7A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('146','7B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('147','7C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('148','7D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('149','8A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('150','8B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('151','8C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('152','8D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('153','9A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('154','9B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('155','9C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('156','9D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('157','10A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('158','10B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('159','10C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('160','10D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('161','11A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('162','11B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('163','11C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('164','11D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('165','12A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('166','12B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('167','12C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('168','12D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('169','13A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('170','13B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('171','13C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('172','13D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('173','14A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('174','14B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('175','14C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('176','14D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('177','15A','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('178','15B','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('179','15C','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('180','15D','normal','3');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('181','1A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('182','1B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('183','1C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('184','1D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('185','2A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('186','2B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('187','2C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('188','2D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('189','3A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('190','3B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('191','3C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('192','3D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('193','4A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('194','4B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('195','4C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('196','4D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('197','5A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('198','5B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('199','5C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('200','5D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('201','6A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('202','6B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('203','6C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('204','6D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('205','7A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('206','7B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('207','7C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('208','7D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('209','8A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('210','8B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('211','8C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('212','8D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('213','9A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('214','9B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('215','9C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('216','9D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('217','10A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('218','10B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('219','10C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('220','10D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('221','11A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('222','11B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('223','11C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('224','11D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('225','12A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('226','12B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('227','12C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('228','12D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('229','13A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('230','13B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('231','13C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('232','13D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('233','14A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('234','14B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('235','14C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('236','14D','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('237','15A','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('238','15B','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('239','15C','normal','4');
INSERT INTO `assento` (`id_assento`,`numero`,`tipo`,`id_autocarro`) VALUES ('240','15D','normal','4');

DROP TABLE IF EXISTS `autocarro`;
CREATE TABLE `autocarro` (
  `id_autocarro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `matricula` varchar(20) NOT NULL,
  `modelo` varchar(60) DEFAULT NULL,
  `capacidade_total` smallint(5) unsigned NOT NULL,
  `id_transportadora` int(10) unsigned NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `codigo_frota` varchar(20) DEFAULT NULL,
  `marca` varchar(40) DEFAULT NULL,
  `ano_fabrico` smallint(5) unsigned DEFAULT NULL,
  `tipo_servico` varchar(30) DEFAULT NULL,
  `configuracao_assentos` varchar(5) NOT NULL DEFAULT '2+2',
  `ar_condicionado` tinyint(1) NOT NULL DEFAULT 0,
  `wifi` tinyint(1) NOT NULL DEFAULT 0,
  `usb` tinyint(1) NOT NULL DEFAULT 0,
  `validade_inspecao` date DEFAULT NULL,
  `livrete` varchar(255) DEFAULT NULL,
  `estado` enum('disponivel','manutencao','inactivo') NOT NULL DEFAULT 'disponivel',
  PRIMARY KEY (`id_autocarro`),
  UNIQUE KEY `uq_autocarro_matricula` (`matricula`),
  UNIQUE KEY `uq_autocarro_codigo` (`id_transportadora`,`codigo_frota`),
  CONSTRAINT `fk_autocarro_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `autocarro` (`id_autocarro`,`matricula`,`modelo`,`capacidade_total`,`id_transportadora`,`activo`,`codigo_frota`,`marca`,`ano_fabrico`,`tipo_servico`,`configuracao_assentos`,`ar_condicionado`,`wifi`,`usb`,`validade_inspecao`,`livrete`,`estado`) VALUES ('1','AFA 345 MP','KG3442HG','60','3','1','LA-001','Yutong','2010','Executivo · AC','2+2','1','1','1','2027-10-11','livretes/611edde25aa9abb1b4b17cac.jpg','disponivel');
INSERT INTO `autocarro` (`id_autocarro`,`matricula`,`modelo`,`capacidade_total`,`id_transportadora`,`activo`,`codigo_frota`,`marca`,`ano_fabrico`,`tipo_servico`,`configuracao_assentos`,`ar_condicionado`,`wifi`,`usb`,`validade_inspecao`,`livrete`,`estado`) VALUES ('2','AFA 346 MP','KG3442ZZG','60','1','1','NA-001','Outra','2020','Executivo · AC','2+2','1','1','1','2027-10-11','livretes/ffb981d55d1f9776c75039b8.png','disponivel');
INSERT INTO `autocarro` (`id_autocarro`,`matricula`,`modelo`,`capacidade_total`,`id_transportadora`,`activo`,`codigo_frota`,`marca`,`ano_fabrico`,`tipo_servico`,`configuracao_assentos`,`ar_condicionado`,`wifi`,`usb`,`validade_inspecao`,`livrete`,`estado`) VALUES ('3','AAF 444 MP','MCxhcgc32','60','3','1','LA-002','Marcopolo','2009','Executivo · AC','2+2','1','1','1','2027-10-11','livretes/4afbdab54e431a66e2500a13.png','disponivel');
INSERT INTO `autocarro` (`id_autocarro`,`matricula`,`modelo`,`capacidade_total`,`id_transportadora`,`activo`,`codigo_frota`,`marca`,`ano_fabrico`,`tipo_servico`,`configuracao_assentos`,`ar_condicionado`,`wifi`,`usb`,`validade_inspecao`,`livrete`,`estado`) VALUES ('4','MVP 657 IB','SDF5AAGHT','60','3','1','LA-003','Scania','2018','Executivo · AC','2+2','1','1','1','2027-10-11','livretes/8ead0e5313d8183bbc9df244.png','disponivel');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('1','MT-2026-000003-7028B3DFCBB06C2E','2026-10-10 20:28:19','valido','3');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('2','MT-2026-000001-6F99EB34A3B0DB3B','2026-10-10 20:28:19','valido','1');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('3','MT-2026-000002-73E9A123C8CBD812','2026-10-10 20:28:19','valido','2');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('4','MT-2026-000004-161C113C819D71BD','2026-10-10 20:28:19','valido','4');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('5','MT-2026-000008-DBA06E19F1B3F4D2','2026-10-10 20:31:36','valido','8');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('6','MT-2026-000005-BB102CEEE6E6D086','2026-10-10 20:31:36','valido','5');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('7','MT-2026-000006-3F67D60DA99B71D7','2026-10-10 20:31:36','valido','6');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('8','MT-2026-000007-DD684CE493E0B847','2026-10-10 20:31:36','valido','7');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('9','MT-2026-000011-B0F04819702EDA49','2026-10-10 20:40:23','valido','11');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('10','MT-2026-000012-30A2840BAAA992FB','2026-10-10 20:40:23','valido','12');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('11','MT-2026-000013-14FCC5DAE9F2DA04','2026-10-10 20:42:31','cancelado','13');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('12','MT-2026-000014-09A66C5F1E9272C8','2026-10-10 20:44:50','valido','14');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('13','MT-2026-000015-607D55E1FA72DBA0','2026-10-11 12:27:42','valido','15');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('14','MT-2026-000017-197E6FE921B04A4A','2026-10-11 14:08:23','valido','17');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('15','MT-2026-000018-56FA93E9C3F77AAB','2026-10-11 15:06:53','valido','18');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('16','MT-2026-000023-F3F3B0F60FFFDFF8','2026-10-11 15:21:31','valido','23');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('17','MT-2026-000024-F6F6F3945612D723','2026-10-11 15:21:31','valido','24');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('18','MT-2026-000025-AC835944085F8A5F','2026-10-11 15:21:31','valido','25');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('19','MT-2026-000026-49644AA403F1F579','2026-10-11 15:21:31','valido','26');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('20','MT-2026-000027-D6F178CD0D0AE298','2026-10-11 15:21:31','valido','27');
INSERT INTO `bilhete` (`id_bilhete`,`codigo_qr`,`data_emissao`,`estado`,`id_reserva`) VALUES ('21','MT-2026-000028-C59660DCF8C4F05C','2026-10-11 15:21:31','valido','28');

DROP TABLE IF EXISTS `configuracao`;
CREATE TABLE `configuracao` (
  `chave` varchar(50) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`chave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('bloqueio_assento_minutos','10','Minutos que o assento fica bloqueado até ao pagamento');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('bloqueio_minutos','60','Minutos de bloqueio após exceder as tentativas');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('cancelamento_horas','24','Horas antes da partida até às quais se pode cancelar ou remarcar');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('recuperacao_validade_minutos','10','Validade do código de recuperação de palavra-passe');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('tentativas_login','3','Tentativas de login falhadas antes do bloqueio');
INSERT INTO `configuracao` (`chave`,`valor`,`descricao`) VALUES ('ultimo_backup','2026-10-11 19:39:26','Data do último backup');

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
) ENGINE=InnoDB AUTO_INCREMENT=358 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('18','5','Backup executado','sistema',NULL,'backup_20261005_171415.sql','2026-10-05 12:14:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('19','5','Configuração de bloqueio alterada','sistema',NULL,'3 tentativas, 60 min','2026-10-05 12:15:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('20','5','Transportadora adicionada','transportadora','4','Capital Way','2026-10-05 12:16:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('21','5','Transportadora adicionada','transportadora','5','Kurula','2026-10-05 12:17:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('22','5','Dados pessoais actualizados','utilizador','5',NULL,'2026-10-05 12:20:36','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('23','5','Logout realizado','utilizador','5',NULL,'2026-10-05 12:25:11','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('24','4','Login realizado','utilizador','4',NULL,'2026-10-05 12:25:14','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('25','4','Logout realizado','utilizador','4',NULL,'2026-10-05 12:25:34','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('26','2','Login realizado','utilizador','2',NULL,'2026-10-05 12:25:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('27','2','Dados pessoais actualizados','utilizador','2',NULL,'2026-10-05 12:26:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('28','2','Logout realizado','utilizador','2',NULL,'2026-10-05 12:26:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('29','4','Login realizado','utilizador','4',NULL,'2026-10-05 12:26:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('30','4','Dados pessoais actualizados','utilizador','4',NULL,'2026-10-05 12:26:46','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('31','4','Logout realizado','utilizador','4',NULL,'2026-10-05 12:26:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('32','5','Login realizado','utilizador','5',NULL,'2026-10-07 08:01:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('33','5','Utilizador bloqueado','utilizador','4','Peresterello Nhalicolo','2026-10-07 08:02:11','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('34','5','Utilizador desbloqueado','utilizador','4','Peresterello Nhalicolo','2026-10-07 08:02:19','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('35','5','Logout realizado','utilizador','5',NULL,'2026-10-08 09:06:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('36','1','Login realizado','utilizador','1',NULL,'2026-10-08 09:07:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('37','1','Logout realizado','utilizador','1',NULL,'2026-10-08 09:16:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('38','5','Login realizado','utilizador','5',NULL,'2026-10-08 09:16:22','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('39','5','Permissão alterada','permissao','3','Gestor: Gerir rotas = não','2026-10-08 09:19:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('40','5','Permissão alterada','permissao','3','Gestor: Gerir rotas = sim','2026-10-08 09:19:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('41','5','Transportadora adicionada','transportadora','6','Khurula Investiment','2026-10-08 09:49:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('42','2','Login realizado','utilizador','2',NULL,'2026-10-09 08:58:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('43','2','Logout realizado','utilizador','2',NULL,'2026-10-09 09:04:29','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('44','2','Login realizado','utilizador','2',NULL,'2026-10-09 09:04:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('45','2','Logout realizado','utilizador','2',NULL,'2026-10-09 09:16:25','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('46','5','Login realizado','utilizador','5',NULL,'2026-10-09 09:17:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('47','5','Logout realizado','utilizador','5',NULL,'2026-10-09 09:17:34','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('48','6','Login realizado','utilizador','6',NULL,'2026-10-09 09:17:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('49','6','Logout realizado','utilizador','6',NULL,'2026-10-09 09:18:16','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('50','5','Login realizado','utilizador','5',NULL,'2026-10-09 09:18:19','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('51','5','Logout realizado','utilizador','5',NULL,'2026-10-09 09:18:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('52','2','Login realizado','utilizador','2',NULL,'2026-10-09 09:18:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('53','2','Logout realizado','utilizador','2',NULL,'2026-10-09 09:23:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('54','4','Login realizado','utilizador','4',NULL,'2026-10-09 09:24:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('55','4','Logout realizado','utilizador','4',NULL,'2026-10-09 09:37:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('56','5','Login realizado','utilizador','5',NULL,'2026-10-09 09:37:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('57','5','Logout realizado','utilizador','5',NULL,'2026-10-09 09:37:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('58','1','Login realizado','utilizador','1',NULL,'2026-10-09 09:38:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('59','1','Logout realizado','utilizador','1',NULL,'2026-10-09 09:46:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('60','1','Login realizado','utilizador','1',NULL,'2026-10-09 10:26:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('61','1','Logout realizado','utilizador','1',NULL,'2026-10-09 10:27:25','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('62','5','Login realizado','utilizador','5',NULL,'2026-10-10 12:45:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('63','5','Logout realizado','utilizador','5',NULL,'2026-10-10 12:47:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('64','4','Login realizado','utilizador','4',NULL,'2026-10-10 12:47:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('65','4','Logout realizado','utilizador','4',NULL,'2026-10-10 12:47:58','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('66','2','Login realizado','utilizador','2',NULL,'2026-10-10 12:48:00','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('67','2','Logout realizado','utilizador','2',NULL,'2026-10-10 12:48:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('68','5','Login realizado','utilizador','5',NULL,'2026-10-10 12:48:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('69','5','Logout realizado','utilizador','5',NULL,'2026-10-10 13:16:26','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('70','3','Login realizado','utilizador','3',NULL,'2026-10-10 13:16:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('71','3','Logout realizado','utilizador','3',NULL,'2026-10-10 13:17:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('72','4','Login realizado','utilizador','4',NULL,'2026-10-10 13:17:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('73','4','Rota criada','rota','1','Maputo → Inhambane','2026-10-10 13:19:17','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('74','4','Rota criada','rota','2','Maputo → Beirra','2026-10-10 13:19:46','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('75','4','Logout realizado','utilizador','4',NULL,'2026-10-10 13:19:53','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('76','1','Login realizado','utilizador','1',NULL,'2026-10-10 13:20:14','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('77','1','Logout realizado','utilizador','1',NULL,'2026-10-10 13:20:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('78','4','Login realizado','utilizador','4',NULL,'2026-10-10 13:20:54','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('79','4','Motorista adicionado','motorista','1','MT-001 · Nelson Francisco Nhavene','2026-10-10 19:30:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('80','4','Autocarro adicionado','autocarro','1','LA-001 · AFA 345 MP','2026-10-10 19:32:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('81','4','Logout realizado','utilizador','4',NULL,'2026-10-10 19:36:08','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('82','1','Login realizado','utilizador','1',NULL,'2026-10-10 19:36:32','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('83','1','Logout realizado','utilizador','1',NULL,'2026-10-10 19:42:32','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('84','5','Login realizado','utilizador','5',NULL,'2026-10-10 19:50:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('85','5','Logout realizado','utilizador','5',NULL,'2026-10-10 19:51:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('86','5','Login realizado','utilizador','5',NULL,'2026-10-10 19:58:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('87','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:03:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('88','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:05:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('89','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:07:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('90','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:07:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('91','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:19:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('92','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:19:19','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('93','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:19:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('94','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:19:43','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('95','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:19:48','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('96','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:19:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('97','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:20:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('98','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:20:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('99','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:21:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('100','2','Login realizado','utilizador','2',NULL,'2026-10-10 20:21:27','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('101','2','Logout realizado','utilizador','2',NULL,'2026-10-10 20:21:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('102','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:22:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('103','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:22:08','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('104','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:22:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('105','5','Utilizador criado','utilizador','7','Francisco Nhavene (bilheteiro)','2026-10-10 20:22:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('106','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:22:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('107','7','Login realizado','utilizador','7',NULL,'2026-10-10 20:23:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('108','7','Palavra-passe alterada','utilizador','7',NULL,'2026-10-10 20:23:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('109','7','Logout realizado','utilizador','7',NULL,'2026-10-10 20:24:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('110','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:24:27','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('111','4','Rota criada','rota','3','Nampula → Maputo','2026-10-10 20:25:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('112','4','Viagens programadas','rota','1','2 viagens às 02:00','2026-10-10 20:26:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('113','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:26:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('114','7','Login realizado','utilizador','7',NULL,'2026-10-10 20:26:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('115','7','Venda presencial registada','reserva','3','MT-2026-000003, MT-2026-000001, MT-2026-000002, MT-2026-000004','2026-10-10 20:28:19','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('116','7','Bilhete enviado por SMS (simulado)','reserva','3','+258 87 350 5758','2026-10-10 20:28:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('117','7','Venda presencial registada','reserva','8','MT-2026-000008, MT-2026-000005, MT-2026-000006, MT-2026-000007','2026-10-10 20:31:36','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('118','7','Bilhete enviado por SMS (simulado)','reserva','8','+258 85 060 4771','2026-10-10 20:31:43','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('119','7','Logout realizado','utilizador','7',NULL,'2026-10-10 20:32:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('120','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:32:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('121','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:33:32','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('122','2','Login realizado','utilizador','2',NULL,'2026-10-10 20:33:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('123','2','Logout realizado','utilizador','2',NULL,'2026-10-10 20:33:54','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('124','7','Login realizado','utilizador','7',NULL,'2026-10-10 20:34:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('125','7','Logout realizado','utilizador','7',NULL,'2026-10-10 20:34:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('126','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:34:46','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('127','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:35:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('128','2','Login realizado','utilizador','2',NULL,'2026-10-10 20:35:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('129','2','Logout realizado','utilizador','2',NULL,'2026-10-10 20:36:00','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('130','7','Login realizado','utilizador','7',NULL,'2026-10-10 20:36:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('131','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:38:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('132','7','Venda presencial registada','reserva','11','MT-2026-000011, MT-2026-000012','2026-10-10 20:40:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('133','7','Logout realizado','utilizador','7',NULL,'2026-10-10 20:41:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('134','1','Login realizado','utilizador','1',NULL,'2026-10-10 20:41:29','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('135','1','Lugar reservado (a aguardar pagamento)','reserva','13',NULL,'2026-10-10 20:41:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('136','1','Pagamento confirmado','reserva','13','MT-2026-000013','2026-10-10 20:42:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('137','1','Lugar reservado (a aguardar pagamento)','reserva','14',NULL,'2026-10-10 20:44:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('138','1','Pagamento confirmado','reserva','14','MT-2026-000014','2026-10-10 20:44:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('139','1','Reserva remarcada','reserva','13','MT-2026-000013 → 2026-10-13','2026-10-10 20:45:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('140','1','Reserva cancelada','reserva','13','MT-2026-000013','2026-10-10 20:46:09','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('141','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:47:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('142','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:47:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('143','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:47:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('144','4','Login realizado','utilizador','4',NULL,'2026-10-10 20:47:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('145','4','Logout realizado','utilizador','4',NULL,'2026-10-10 20:48:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('146','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:48:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('147','1','Logout realizado','utilizador','1',NULL,'2026-10-10 20:49:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('148','5','Login realizado','utilizador','5',NULL,'2026-10-10 20:49:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('149','5','Utilizador criado','utilizador','8','Jose Mate (gestor)','2026-10-10 20:50:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('150','5','Logout realizado','utilizador','5',NULL,'2026-10-10 20:50:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('151','8','Login realizado','utilizador','8',NULL,'2026-10-10 20:50:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('152','8','Palavra-passe alterada','utilizador','8',NULL,'2026-10-10 20:51:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('153','8','Rota criada','rota','4','Maputo → Inhambane','2026-10-10 20:52:48','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('154','8','Rota actualizada','rota','4','Maputo → Inhambane','2026-10-10 20:52:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('155','8','Rota criada','rota','5','Maputo → Gaza','2026-10-10 20:53:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('156','8','Rota criada','rota','6','Maputo → Beira','2026-10-10 20:53:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('157','8','Autocarro adicionado','autocarro','2','NA-001 · AFA 346 MP','2026-10-10 21:01:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('158','8','Logout realizado','utilizador','8',NULL,'2026-10-10 21:01:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('159','5','Login realizado','utilizador','5',NULL,'2026-10-10 21:02:00','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('160','5','Logout realizado','utilizador','5',NULL,'2026-10-10 21:02:22','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('161','3','Login realizado','utilizador','3',NULL,'2026-10-10 21:02:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('162','5','Logout realizado','utilizador','5',NULL,'2026-10-10 21:07:34','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('163','3','Logout realizado','utilizador','3',NULL,'2026-10-10 21:08:03','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('164','5','Login realizado','utilizador','5',NULL,'2026-10-10 21:08:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('165','8','Login realizado','utilizador','8',NULL,'2026-10-10 21:08:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('166','8','Motorista adicionado','motorista','2','MT-001 · Djeld Nhacengo','2026-10-10 21:11:44','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('167','8','Motorista adicionado','motorista','3','MT-002 · Nilton Simbine','2026-10-10 21:15:01','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('168','8','Estado do motorista alterado','motorista','3','MT-002 → Inactivo','2026-10-10 21:15:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('169','8','Estado do motorista alterado','motorista','3','MT-002 → De folga','2026-10-10 21:15:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('170','8','Estado do motorista alterado','motorista','3','MT-002 → Disponível','2026-10-10 21:15:16','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('171','8','Logout realizado','utilizador','8',NULL,'2026-10-10 21:17:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('172','3','Login realizado','utilizador','3',NULL,'2026-10-10 21:18:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('173','3','Logout realizado','utilizador','3',NULL,'2026-10-10 21:19:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('174','4','Login realizado','utilizador','4',NULL,'2026-10-10 21:19:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('175','4','Logout realizado','utilizador','4',NULL,'2026-10-10 21:21:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('176','5','Logout realizado','utilizador','5',NULL,'2026-10-10 21:22:14','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('177','4','Login realizado','utilizador','4',NULL,'2026-10-10 21:23:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('178','2','Login realizado','utilizador','2',NULL,'2026-10-10 21:24:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('179','4','Logout realizado','utilizador','4',NULL,'2026-10-10 21:42:59','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('180','5','Login realizado','utilizador','5',NULL,'2026-10-10 21:43:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('181','5','Logout realizado','utilizador','5',NULL,'2026-10-10 21:43:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('182','3','Login realizado','utilizador','3',NULL,'2026-10-10 21:43:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('183','3','Logout realizado','utilizador','3',NULL,'2026-10-10 21:44:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('184','4','Login realizado','utilizador','4',NULL,'2026-10-10 21:44:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('185','4','Logout realizado','utilizador','4',NULL,'2026-10-10 21:45:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('186','3','Login realizado','utilizador','3',NULL,'2026-10-10 21:45:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('187','3','Logout realizado','utilizador','3',NULL,'2026-10-10 21:45:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('188','4','Login realizado','utilizador','4',NULL,'2026-10-10 21:45:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('189','4','Rota criada','rota','7','Maputo → Cabo Delgado','2026-10-10 21:47:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('190','4','Logout realizado','utilizador','4',NULL,'2026-10-10 21:47:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('191','3','Login realizado','utilizador','3',NULL,'2026-10-10 21:47:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('192','3','Logout realizado','utilizador','3',NULL,'2026-10-10 22:11:08','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('193','5','Login realizado','utilizador','5',NULL,'2026-10-10 22:11:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('194','5','Logout realizado','utilizador','5',NULL,'2026-10-10 22:12:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('195','4','Login realizado','utilizador','4',NULL,'2026-10-10 22:12:16','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('196','4','Cobrador atribuído a viagens','utilizador','3','Mikael Chongo · 1 viagem(ns)','2026-10-10 22:12:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('197','4','Logout realizado','utilizador','4',NULL,'2026-10-10 22:13:34','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('198','5','Login realizado','utilizador','5',NULL,'2026-10-10 22:13:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('199','5','Utilizador criado','utilizador','9','Antonio Machava (cobrador)','2026-10-10 22:14:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('200','5','Palavra-passe reposta','utilizador','9','Antonio Machava','2026-10-10 22:16:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('201','5','Logout realizado','utilizador','5',NULL,'2026-10-10 22:16:44','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('202','9','Login realizado','utilizador','9',NULL,'2026-10-10 22:16:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('203','9','Logout realizado','utilizador','9',NULL,'2026-10-10 22:17:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('204','5','Login realizado','utilizador','5',NULL,'2026-10-10 22:18:14','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('205','5','Logout realizado','utilizador','5',NULL,'2026-10-10 22:18:29','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('206','3','Login realizado','utilizador','3',NULL,'2026-10-10 22:18:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('207','3','Logout realizado','utilizador','3',NULL,'2026-10-10 22:19:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('208','4','Login realizado','utilizador','4',NULL,'2026-10-10 22:19:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('209','4','Cobrador atribuído a viagens','utilizador','3','Mikael Chongo · 1 viagem(ns)','2026-10-10 22:20:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('210','4','Cobrador atribuído a viagens','utilizador','3','Mikael Chongo · 1 viagem(ns) · motorista Nelson Francisco Nhavene','2026-10-10 22:20:41','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('211','4','Logout realizado','utilizador','4',NULL,'2026-10-10 22:20:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('212','3','Login realizado','utilizador','3',NULL,'2026-10-10 22:21:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('213','3','Logout realizado','utilizador','3',NULL,'2026-10-10 22:29:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('214','4','Login realizado','utilizador','4',NULL,'2026-10-11 10:39:36','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('215','4','Rota criada','rota','8','Maputo → Manica','2026-10-11 10:41:16','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('216','4','Viagens programadas','rota','8','60 viagens às 15:00','2026-10-11 10:41:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('217','4','Motorista adicionado','motorista','4','MT-002 · Alberto Cumbane','2026-10-11 10:44:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('218','4','Autocarro adicionado','autocarro','3','LA-002 · AAF 444 MP','2026-10-11 10:46:17','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('219','4','Viagens programadas','rota','2','3 viagens às 20:00','2026-10-11 10:47:03','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('220','4','Logout realizado','utilizador','4',NULL,'2026-10-11 10:48:19','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('221','5','Login realizado','utilizador','5',NULL,'2026-10-11 10:48:22','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('222','5','Utilizador criado','utilizador','10','Nelson Web (cobrador)','2026-10-11 10:49:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('223','5','Logout realizado','utilizador','5',NULL,'2026-10-11 10:49:17','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('224','10','Login realizado','utilizador','10',NULL,'2026-10-11 10:49:26','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('225','10','Logout realizado','utilizador','10',NULL,'2026-10-11 10:49:44','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('226','4','Login realizado','utilizador','4',NULL,'2026-10-11 10:49:48','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('227','4','Cobrador atribuído a viagens','utilizador','10','Nelson Web · 1 viagem(ns) · motorista Alberto Cumbane','2026-10-11 10:50:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('228','4','Viagens programadas','rota','3','1 viagens às 06:45','2026-10-11 10:52:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('229','4','Cobrador atribuído a viagens','utilizador','10','Nelson Web · 1 viagem(ns) · motorista Alberto Cumbane','2026-10-11 10:52:40','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('230','4','Logout realizado','utilizador','4',NULL,'2026-10-11 10:53:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('231','5','Login realizado','utilizador','5',NULL,'2026-10-11 10:53:29','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('232','5','Logout realizado','utilizador','5',NULL,'2026-10-11 10:53:43','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('233','3','Login realizado','utilizador','3',NULL,'2026-10-11 10:53:53','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('234','3','Logout realizado','utilizador','3',NULL,'2026-10-11 10:54:32','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('235','4','Login realizado','utilizador','4',NULL,'2026-10-11 11:01:27','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('236','4','Cobrador criado','utilizador','11','Bradly Alexandre → Lalgy','2026-10-11 11:02:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('237','4','Logout realizado','utilizador','4',NULL,'2026-10-11 11:04:07','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('238','4','Login realizado','utilizador','4',NULL,'2026-10-11 11:04:36','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('239','4','Palavra-passe de cobrador reposta','utilizador','11','Bradly Alexandre','2026-10-11 11:04:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('240','4','Logout realizado','utilizador','4',NULL,'2026-10-11 11:05:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('241','11','Login realizado','utilizador','11',NULL,'2026-10-11 11:05:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('242','11','Dados pessoais actualizados','utilizador','11',NULL,'2026-10-11 11:05:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('243','11','Palavra-passe alterada','utilizador','11',NULL,'2026-10-11 11:06:00','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('244','11','Logout realizado','utilizador','11',NULL,'2026-10-11 11:07:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('245','5','Login realizado','utilizador','5',NULL,'2026-10-11 11:07:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('246','5','Logout realizado','utilizador','5',NULL,'2026-10-11 11:08:16','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('247','4','Login realizado','utilizador','4',NULL,'2026-10-11 11:10:48','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('248','4','Autocarro adicionado','autocarro','4','LA-003 · MVP 657 IB','2026-10-11 11:24:32','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('249','4','Rota criada','rota','9','Maputo → Zambézia','2026-10-11 11:31:43','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('250','4','Motorista adicionado','motorista','5','MT-003 · Pedro Tembe','2026-10-11 11:33:38','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('251','4','Viagens programadas','rota','9','0 viagens às 18:00','2026-10-11 11:34:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('252','4','Viagem cancelada','viagem','129','12/10 18:00 · Maputo → Zambézia','2026-10-11 11:35:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('253','4','Viagens criadas','rota','9','1 viagem(ns) Maputo → Zambézia às 18:00 a partir de 2026-10-11','2026-10-11 11:35:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('254','4','Cobrador criado','utilizador','12','jose Augusto → Lalgy','2026-10-11 11:50:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('255','4','Cobrador atribuído a viagens','utilizador','12','jose Augusto · 1 viagem(ns) · motorista Pedro Tembe','2026-10-11 11:51:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('256','4','Palavra-passe de cobrador reposta','utilizador','12','jose Augusto','2026-10-11 11:52:03','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('257','4','Logout realizado','utilizador','4',NULL,'2026-10-11 11:52:09','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('258','12','Login realizado','utilizador','12',NULL,'2026-10-11 11:52:20','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('259','12','Logout realizado','utilizador','12',NULL,'2026-10-11 11:52:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('260','1','Login realizado','utilizador','1',NULL,'2026-10-11 11:52:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('261','1','Logout realizado','utilizador','1',NULL,'2026-10-11 11:54:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('262','2','Login realizado','utilizador','2',NULL,'2026-10-11 11:54:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('263','2','Dados pessoais actualizados','utilizador','2',NULL,'2026-10-11 11:55:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('264','2','Dados pessoais actualizados','utilizador','2',NULL,'2026-10-11 11:55:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('265','2','Logout realizado','utilizador','2',NULL,'2026-10-11 11:56:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('266','5','Login realizado','utilizador','5',NULL,'2026-10-11 11:56:08','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('267','5','Logout realizado','utilizador','5',NULL,'2026-10-11 11:56:53','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('268','7','Login realizado','utilizador','7',NULL,'2026-10-11 11:57:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('269','7','Logout realizado','utilizador','7',NULL,'2026-10-11 12:05:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('270','4','Login realizado','utilizador','4',NULL,'2026-10-11 12:05:25','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('271','4','Logout realizado','utilizador','4',NULL,'2026-10-11 12:09:26','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('272','4','Login realizado','utilizador','4',NULL,'2026-10-11 12:09:33','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('273','4','Logout realizado','utilizador','4',NULL,'2026-10-11 12:11:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('274','1','Login realizado','utilizador','1',NULL,'2026-10-11 12:12:11','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('275','1','Lugar reservado (a aguardar pagamento)','reserva','15',NULL,'2026-10-11 12:27:22','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('276','1','Pagamento confirmado','reserva','15','MT-2026-000015','2026-10-11 12:27:42','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('277','1','Logout realizado','utilizador','1',NULL,'2026-10-11 12:33:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('278','5','Login realizado','utilizador','5',NULL,'2026-10-11 12:33:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('279','5','Logout realizado','utilizador','5',NULL,'2026-10-11 12:33:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('280','11','Login realizado','utilizador','11',NULL,'2026-10-11 12:34:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('281','11','Logout realizado','utilizador','11',NULL,'2026-10-11 12:35:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('282','4','Login realizado','utilizador','4',NULL,'2026-10-11 12:35:24','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('283','4','Logout realizado','utilizador','4',NULL,'2026-10-11 12:35:43','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('284','12','Recuperação de palavra-passe solicitada','utilizador','12',NULL,'2026-10-11 12:36:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('285','12','Palavra-passe redefinida','utilizador','12',NULL,'2026-10-11 12:37:01','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('286','12','Login realizado','utilizador','12',NULL,'2026-10-11 12:37:17','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('287','12','Embarque recusado','reserva','0','2026-000015 · Bilhete não encontrado','2026-10-11 12:37:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('288','12','Dados pessoais actualizados','utilizador','12',NULL,'2026-10-11 12:40:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('289','12','Logout realizado','utilizador','12',NULL,'2026-10-11 12:40:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('290','4','Login realizado','utilizador','4',NULL,'2026-10-11 12:40:55','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('291','4','Logout realizado','utilizador','4',NULL,'2026-10-11 12:41:50','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('292','5','Login realizado','utilizador','5',NULL,'2026-10-11 12:41:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('293','5','Logout realizado','utilizador','5',NULL,'2026-10-11 12:44:01','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('294','12','Login realizado','utilizador','12',NULL,'2026-10-11 12:44:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('295','12','Embarque confirmado','reserva','15','MT-2026-000015','2026-10-11 12:48:09','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('296','12','Logout realizado','utilizador','12',NULL,'2026-10-11 12:54:10','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('297','4','Login realizado','utilizador','4',NULL,'2026-10-11 12:54:12','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('298','4','Logout realizado','utilizador','4',NULL,'2026-10-11 13:14:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('299','5','Login realizado','utilizador','5',NULL,'2026-10-11 13:14:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('300','5','Logout realizado','utilizador','5',NULL,'2026-10-11 13:14:49','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('301','2','Login realizado','utilizador','2',NULL,'2026-10-11 13:14:51','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('302','2','Dados pessoais actualizados','utilizador','2',NULL,'2026-10-11 13:15:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('303','2','Logout realizado','utilizador','2',NULL,'2026-10-11 13:15:34','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('304','5','Login realizado','utilizador','5',NULL,'2026-10-11 13:15:37','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('305','5','Logout realizado','utilizador','5',NULL,'2026-10-11 13:25:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('306','1','Login realizado','utilizador','1',NULL,'2026-10-11 13:26:05','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('307','1','Pedido de ajuda criado','suporte_pedido','1','Outro assunto','2026-10-11 13:29:08','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('308','1','Logout realizado','utilizador','1',NULL,'2026-10-11 13:29:27','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('309','5','Login realizado','utilizador','5',NULL,'2026-10-11 13:29:29','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('310','5','Pedido de ajuda: estado alterado','suporte_pedido','1','Em curso','2026-10-11 13:46:56','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('311','5','Logout realizado','utilizador','5',NULL,'2026-10-11 13:47:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('312','1','Login realizado','utilizador','1',NULL,'2026-10-11 13:47:18','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('313','1','Logout realizado','utilizador','1',NULL,'2026-10-11 13:48:45','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('314','13','Conta de passageiro criada','utilizador','13',NULL,'2026-10-11 14:05:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('315','13','Login realizado','utilizador','13',NULL,'2026-10-11 14:06:17','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('316','13','Lugar reservado (a aguardar pagamento)','reserva','16',NULL,'2026-10-11 14:07:36','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('317','13','Lugar reservado (a aguardar pagamento)','reserva','17',NULL,'2026-10-11 14:07:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('318','13','Pagamento confirmado','reserva','17','MT-2026-000017','2026-10-11 14:08:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('319','13','Logout realizado','utilizador','13',NULL,'2026-10-11 14:09:04','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('320','5','Login realizado','utilizador','5',NULL,'2026-10-11 14:09:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('321','5','Pedido de ajuda: estado alterado','suporte_pedido','1','Resolvido','2026-10-11 14:09:35','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('322','5','Logout realizado','utilizador','5',NULL,'2026-10-11 14:15:30','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('323','1','Login realizado','utilizador','1',NULL,'2026-10-11 14:15:47','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('324','1','Logout realizado','utilizador','1',NULL,'2026-10-11 14:16:03','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('325','5','Login realizado','utilizador','5',NULL,'2026-10-11 14:16:15','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('326','5','Logout realizado','utilizador','5',NULL,'2026-10-11 14:16:28','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('327','7','Login realizado','utilizador','7',NULL,'2026-10-11 14:16:39','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('328','7','Logout realizado','utilizador','7',NULL,'2026-10-11 14:17:52','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('329','4','Login realizado','utilizador','4',NULL,'2026-10-11 14:17:54','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('330','4','Logout realizado','utilizador','4',NULL,'2026-10-11 14:38:54','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('331','5','Login realizado','utilizador','5',NULL,'2026-10-11 14:38:57','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('332','5','Backup executado','sistema',NULL,'backup_20261011_193921.sql','2026-10-11 14:39:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('333','5','Backup executado','sistema',NULL,'backup_20261011_193926.sql','2026-10-11 14:39:26','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('334','5','Logout realizado','utilizador','5',NULL,'2026-10-11 14:40:06','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('335','1','Login realizado','utilizador','1',NULL,'2026-10-11 14:40:23','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('336','1','Lugar reservado (a aguardar pagamento)','reserva','18',NULL,'2026-10-11 15:05:58','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('337','1','Pagamento confirmado','reserva','18','MT-2026-000018','2026-10-11 15:06:53','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('338','1','Lugar reservado (a aguardar pagamento)','reserva','19',NULL,'2026-10-11 15:21:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('339','1','Lugar reservado (a aguardar pagamento)','reserva','20',NULL,'2026-10-11 15:21:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('340','1','Lugar reservado (a aguardar pagamento)','reserva','21',NULL,'2026-10-11 15:21:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('341','1','Lugar reservado (a aguardar pagamento)','reserva','22',NULL,'2026-10-11 15:21:02','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('342','1','Lugar reservado (a aguardar pagamento)','reserva','23',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('343','1','Lugar reservado (a aguardar pagamento)','reserva','24',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('344','1','Lugar reservado (a aguardar pagamento)','reserva','25',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('345','1','Lugar reservado (a aguardar pagamento)','reserva','26',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('346','1','Lugar reservado (a aguardar pagamento)','reserva','27',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('347','1','Lugar reservado (a aguardar pagamento)','reserva','28',NULL,'2026-10-11 15:21:13','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('348','1','Pagamento confirmado','reserva','23','MT-2026-000023','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('349','1','Pagamento confirmado','reserva','24','MT-2026-000024','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('350','1','Pagamento confirmado','reserva','25','MT-2026-000025','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('351','1','Pagamento confirmado','reserva','26','MT-2026-000026','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('352','1','Pagamento confirmado','reserva','27','MT-2026-000027','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('353','1','Pagamento confirmado','reserva','28','MT-2026-000028','2026-10-11 15:21:31','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('354','1','Logout realizado','utilizador','1',NULL,'2026-10-11 15:23:21','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('355','4','Login realizado','utilizador','4',NULL,'2026-10-11 15:23:25','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('356','4','Logout realizado','utilizador','4',NULL,'2026-10-11 15:33:20','::1');
INSERT INTO `log_transaccao` (`id_log`,`id_utilizador`,`accao`,`entidade`,`id_entidade`,`detalhes`,`data_hora`,`ip`) VALUES ('357','5','Login realizado','utilizador','5',NULL,'2026-10-11 15:33:23','::1');

DROP TABLE IF EXISTS `motorista`;
CREATE TABLE `motorista` (
  `id_motorista` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_transportadora` int(10) unsigned NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(30) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `morada` varchar(200) DEFAULT NULL,
  `carta_numero` varchar(30) NOT NULL,
  `carta_categoria` varchar(40) NOT NULL,
  `carta_emissao` date NOT NULL,
  `carta_validade` date NOT NULL,
  `carta_ficheiro` varchar(255) DEFAULT NULL,
  `estado` enum('disponivel','folga','inactivo') NOT NULL DEFAULT 'disponivel',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `id_utilizador` int(11) DEFAULT NULL,
  `numero_carta` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_motorista`),
  UNIQUE KEY `uq_motorista_codigo` (`id_transportadora`,`codigo`),
  UNIQUE KEY `uq_motorista_documento` (`documento`),
  UNIQUE KEY `uq_motorista_carta` (`carta_numero`),
  CONSTRAINT `fk_motorista_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `motorista` (`id_motorista`,`id_transportadora`,`codigo`,`nome`,`documento`,`data_nascimento`,`telefone`,`email`,`morada`,`carta_numero`,`carta_categoria`,`carta_emissao`,`carta_validade`,`carta_ficheiro`,`estado`,`data_criacao`,`id_utilizador`,`numero_carta`) VALUES ('1','3','MT-001','Nelson Francisco Nhavene','634636436463P','2004-03-01','+258 87 350 5758','nelson@gmail.com','Alto mae, Maputo','21321241E24512S','D · Transporte de passageiros','2024-11-11','2029-11-11','cartas/2762574ab51497980d7fd481.png','disponivel','2026-10-10 19:30:38',NULL,NULL);
INSERT INTO `motorista` (`id_motorista`,`id_transportadora`,`codigo`,`nome`,`documento`,`data_nascimento`,`telefone`,`email`,`morada`,`carta_numero`,`carta_categoria`,`carta_emissao`,`carta_validade`,`carta_ficheiro`,`estado`,`data_criacao`,`id_utilizador`,`numero_carta`) VALUES ('2','1','MT-001','Djeld Nhacengo','54455254725385N','1995-07-26','+258 87 323 2657','djeld@gmail.com','Zimpeto, Maputo','535625FEW3','D · Transporte de passageiros','2025-10-11','2031-10-11','cartas/5e4cfafbb8abd1095d28cce2.png','disponivel','2026-10-10 21:11:44',NULL,NULL);
INSERT INTO `motorista` (`id_motorista`,`id_transportadora`,`codigo`,`nome`,`documento`,`data_nascimento`,`telefone`,`email`,`morada`,`carta_numero`,`carta_categoria`,`carta_emissao`,`carta_validade`,`carta_ficheiro`,`estado`,`data_criacao`,`id_utilizador`,`numero_carta`) VALUES ('3','1','MT-002','Nilton Simbine','544552543385S','2002-09-07','+258 86 553 3733','nilton@gmail.com','Xai-xai','F5R56R356R2G','D · Transporte de passageiros','2010-10-11','2026-10-12',NULL,'disponivel','2026-10-10 21:15:01',NULL,NULL);
INSERT INTO `motorista` (`id_motorista`,`id_transportadora`,`codigo`,`nome`,`documento`,`data_nascimento`,`telefone`,`email`,`morada`,`carta_numero`,`carta_categoria`,`carta_emissao`,`carta_validade`,`carta_ficheiro`,`estado`,`data_criacao`,`id_utilizador`,`numero_carta`) VALUES ('4','3','MT-002','Alberto Cumbane','463643654463A','2000-08-21','+258 87 450 6255','alberto@gmail.com','Inhambane','21321241WEW','D · Transporte de passageiros','2023-02-20','2028-02-20',NULL,'disponivel','2026-10-11 10:44:23',NULL,NULL);
INSERT INTO `motorista` (`id_motorista`,`id_transportadora`,`codigo`,`nome`,`documento`,`data_nascimento`,`telefone`,`email`,`morada`,`carta_numero`,`carta_categoria`,`carta_emissao`,`carta_validade`,`carta_ficheiro`,`estado`,`data_criacao`,`id_utilizador`,`numero_carta`) VALUES ('5','3','MT-003','Pedro Tembe','6346364363P','1998-11-21','+258 87 450 6253','pedro@gmail.com','Manica','21321241WEWZ','D · Transporte de passageiros','2026-03-03','2031-03-03',NULL,'disponivel','2026-10-11 11:33:38',NULL,NULL);

DROP TABLE IF EXISTS `pagamento`;
CREATE TABLE `pagamento` (
  `id_pagamento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,2) NOT NULL,
  `metodo` enum('mpesa','emola','cartao','numerario') NOT NULL,
  `estado` enum('pendente','confirmado','falhado','reembolsado') NOT NULL DEFAULT 'pendente',
  `data_pagamento` datetime DEFAULT NULL,
  `referencia_transacao` varchar(60) DEFAULT NULL,
  `id_reserva` int(10) unsigned NOT NULL,
  `telefone_pagamento` varchar(30) DEFAULT NULL,
  `motivo_falha` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_pagamento`),
  UNIQUE KEY `uq_pagamento_reserva` (`id_reserva`),
  UNIQUE KEY `uq_pagamento_referencia` (`referencia_transacao`),
  CONSTRAINT `fk_pagamento_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('1','4000.00','numerario','confirmado','2026-10-10 20:28:19','MT-2026-000003','3',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('2','4000.00','numerario','confirmado','2026-10-10 20:28:19','MT-2026-000001','1',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('3','4000.00','numerario','confirmado','2026-10-10 20:28:19','MT-2026-000002','2',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('4','4000.00','numerario','confirmado','2026-10-10 20:28:19','MT-2026-000004','4',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('5','1500.00','mpesa','confirmado','2026-10-10 20:31:36','MT-2026-000008','8','850604771',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('6','1500.00','mpesa','confirmado','2026-10-10 20:31:36','MT-2026-000005','5','850604771',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('7','1500.00','mpesa','confirmado','2026-10-10 20:31:36','MT-2026-000006','6','850604771',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('8','1500.00','mpesa','confirmado','2026-10-10 20:31:36','MT-2026-000007','7','850604771',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('9','1500.00','cartao','confirmado','2026-10-10 20:40:23','MT-2026-000011','11',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('10','1500.00','cartao','confirmado','2026-10-10 20:40:23','MT-2026-000012','12',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('11','1500.00','emola','reembolsado','2026-10-10 20:42:31','MT-2026-000013','13','874506259',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('12','4000.00','cartao','confirmado','2026-10-10 20:44:50','MT-2026-000014','14',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('13','5000.00','emola','confirmado','2026-10-11 17:27:42','MT-2026-000015','15','873505758',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('14','4000.00','cartao','confirmado','2026-10-11 19:08:23','MT-2026-000017','17',NULL,NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('15','5000.00','emola','confirmado','2026-10-11 15:06:53','MT-2026-000018','18','874509870',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('16','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000023','23','874506253',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('17','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000024','24','874506253',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('18','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000025','25','874506253',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('19','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000026','26','874506253',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('20','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000027','27','874506253',NULL);
INSERT INTO `pagamento` (`id_pagamento`,`valor`,`metodo`,`estado`,`data_pagamento`,`referencia_transacao`,`id_reserva`,`telefone_pagamento`,`motivo_falha`) VALUES ('21','5000.00','emola','confirmado','2026-10-11 15:21:31','MT-2026-000028','28','874506253',NULL);

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
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('bilheteiro','11','0');
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
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('cobrador','11','0');
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
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('gestor','11','1');
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
INSERT INTO `perfil_permissao` (`perfil`,`id_permissao`,`concedida`) VALUES ('administrador','11','1');

DROP TABLE IF EXISTS `permissao`;
CREATE TABLE `permissao` (
  `id_permissao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(40) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `alerta_seguranca` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_permissao`),
  UNIQUE KEY `uq_permissao_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
INSERT INTO `permissao` (`id_permissao`,`codigo`,`descricao`,`alerta_seguranca`) VALUES ('11','gerir_frota','Gerir autocarros e motoristas','0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `recuperacao_senha` (`id_recuperacao`,`id_utilizador`,`codigo_hash`,`data_expiracao`,`tentativas`,`usado`,`data_criacao`) VALUES ('1','12','$2y$10$d1nJC8likeVuyFF3YL6PreoOW7NQPcKntY6xYP3DPdVGIcJMsatVm','2026-10-11 17:46:19','0','1','2026-10-11 12:36:19');

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
  `referencia` varchar(20) DEFAULT NULL,
  `data_cancelamento` datetime DEFAULT NULL,
  `tipo_documento` varchar(30) DEFAULT NULL,
  `documento_passageiro` varchar(30) DEFAULT NULL,
  `grupo_venda` varchar(24) DEFAULT NULL,
  `embarcado_em` datetime DEFAULT NULL,
  `embarcado_por` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  UNIQUE KEY `uq_reserva_assento_viagem` (`id_viagem`,`id_assento`,`assento_ocupado`),
  UNIQUE KEY `uq_reserva_referencia` (`referencia`),
  KEY `idx_reserva_passageiro` (`id_passageiro`),
  KEY `idx_reserva_estado` (`estado`,`data_expiracao`),
  KEY `fk_reserva_assento` (`id_assento`),
  KEY `fk_reserva_bilheteiro` (`id_bilheteiro`),
  KEY `idx_reserva_grupo` (`grupo_venda`),
  KEY `idx_reserva_embarque` (`id_viagem`,`embarcado_em`),
  CONSTRAINT `fk_reserva_assento` FOREIGN KEY (`id_assento`) REFERENCES `assento` (`id_assento`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_bilheteiro` FOREIGN KEY (`id_bilheteiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_viagem` FOREIGN KEY (`id_viagem`) REFERENCES `viagem` (`id_viagem`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('1','2026-10-10 15:27:23',NULL,'confirmada',NULL,'Nelson Nhavene','+258 87 350 5758','1','8','7','MT-2026-000001',NULL,'BI','634636436463P','V261011012723E06D',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('2','2026-10-10 15:27:23',NULL,'confirmada',NULL,'Nelson Nhavene','+258 87 350 5758','1','11','7','MT-2026-000002',NULL,'BI','634636436463P','V261011012723E06D',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('3','2026-10-10 15:27:23',NULL,'confirmada',NULL,'Nelson Nhavene','+258 87 350 5758','1','7','7','MT-2026-000003',NULL,'BI','634636436463P','V261011012723E06D',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('4','2026-10-10 15:27:23',NULL,'confirmada',NULL,'Nelson Nhavene','+258 87 350 5758','1','12','7','MT-2026-000004',NULL,'BI','634636436463P','V261011012723E06D',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('5','2026-10-10 15:31:20',NULL,'confirmada',NULL,'Nelson F Nhavene','+258 85 060 4771','2','9','7','MT-2026-000005',NULL,'BI','634636436463P','V2610110131209103',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('6','2026-10-10 15:31:20',NULL,'confirmada',NULL,'Nelson F Nhavene','+258 85 060 4771','2','14','7','MT-2026-000006',NULL,'BI','634636436463P','V2610110131209103',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('7','2026-10-10 15:31:20',NULL,'confirmada',NULL,'Nelson F Nhavene','+258 85 060 4771','2','20','7','MT-2026-000007',NULL,'BI','634636436463P','V2610110131209103',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('8','2026-10-10 15:31:20',NULL,'confirmada',NULL,'Nelson F Nhavene','+258 85 060 4771','2','7','7','MT-2026-000008',NULL,'BI','634636436463P','V2610110131209103',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('9','2026-10-10 15:34:23','2026-10-11 01:44:23','cancelada',NULL,NULL,NULL,'3','4','7','MT-2026-000009','2026-10-11 01:36:35',NULL,NULL,'V26101101342324DF',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('10','2026-10-10 15:36:35','2026-10-11 01:46:35','cancelada',NULL,NULL,NULL,'2','15','7','MT-2026-000010','2026-10-11 01:38:08',NULL,NULL,'V2610110136353DD7',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('11','2026-10-10 15:38:08',NULL,'confirmada',NULL,'zoca','+258 87 450 6253','2','15','7','MT-2026-000011',NULL,'BI','634636436463P','V261011013808B7BB',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('12','2026-10-10 15:38:08',NULL,'confirmada',NULL,'zoca','+258 87 450 6253','2','16','7','MT-2026-000012',NULL,'BI','634636436463P','V261011013808B7BB',NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('13','2026-10-10 15:41:56',NULL,'cancelada','1',NULL,NULL,'3','10',NULL,'MT-2026-000013','2026-10-11 01:46:09',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('14','2026-10-10 15:44:05',NULL,'confirmada','1',NULL,NULL,'1','1',NULL,'MT-2026-000014',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('15','2026-10-11 07:27:22',NULL,'confirmada','1',NULL,NULL,'130','181',NULL,'MT-2026-000015',NULL,NULL,NULL,NULL,'2026-10-11 12:48:09','12');
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('16','2026-10-11 14:07:36','2026-10-11 19:17:36','cancelada','13',NULL,NULL,'1','5',NULL,'MT-2026-000016','2026-10-11 19:07:57',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('17','2026-10-11 14:07:57',NULL,'confirmada','13',NULL,NULL,'1','14',NULL,'MT-2026-000017',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('18','2026-10-11 15:05:58',NULL,'confirmada','1',NULL,NULL,'130','182',NULL,'MT-2026-000018',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('19','2026-10-11 15:21:02','2026-10-11 15:31:02','cancelada','1',NULL,NULL,'130','202',NULL,'MT-2026-000019','2026-10-11 15:21:13',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('20','2026-10-11 15:21:02','2026-10-11 15:31:02','cancelada','1',NULL,NULL,'130','198',NULL,'MT-2026-000020','2026-10-11 15:21:13',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('21','2026-10-11 15:21:02','2026-10-11 15:31:02','cancelada','1',NULL,NULL,'130','197',NULL,'MT-2026-000021','2026-10-11 15:21:13',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('22','2026-10-11 15:21:02','2026-10-11 15:31:02','cancelada','1',NULL,NULL,'130','201',NULL,'MT-2026-000022','2026-10-11 15:21:13',NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('23','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','194',NULL,'MT-2026-000023',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('24','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','190',NULL,'MT-2026-000024',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('25','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','189',NULL,'MT-2026-000025',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('26','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','193',NULL,'MT-2026-000026',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('27','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','196',NULL,'MT-2026-000027',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `reserva` (`id_reserva`,`data_reserva`,`data_expiracao`,`estado`,`id_passageiro`,`nome_passageiro`,`telefone_passageiro`,`id_viagem`,`id_assento`,`id_bilheteiro`,`referencia`,`data_cancelamento`,`tipo_documento`,`documento_passageiro`,`grupo_venda`,`embarcado_em`,`embarcado_por`) VALUES ('28','2026-10-11 15:21:13',NULL,'confirmada','1',NULL,NULL,'130','195',NULL,'MT-2026-000028',NULL,NULL,NULL,NULL,NULL,NULL);

DROP TABLE IF EXISTS `rota`;
CREATE TABLE `rota` (
  `id_rota` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origem` varchar(80) NOT NULL,
  `destino` varchar(80) NOT NULL,
  `distancia_km` decimal(8,2) DEFAULT NULL,
  `duracao_estimada` time DEFAULT NULL,
  `id_transportadora` int(10) unsigned NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `preco_base` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_rota`),
  KEY `idx_rota_origem_destino` (`origem`,`destino`),
  KEY `fk_rota_transportadora` (`id_transportadora`),
  CONSTRAINT `fk_rota_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('1','Maputo','Inhambane','1200.00','50:00:00','3','1','1500.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('2','Maputo','Beirra','2000.00','70:00:00','3','1','2500.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('3','Nampula','Maputo','3000.00','28:00:00','3','1','4000.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('4','Maputo','Inhambane','5000.00','05:00:00','1','1','1500.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('5','Maputo','Gaza','4000.00','03:00:00','1','1','1200.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('6','Maputo','Beira','6000.00','10:00:00','1','1','1800.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('7','Maputo','Cabo Delgado','3800.00','24:00:00','3','1','8000.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('8','Maputo','Manica','6800.00','15:00:00','3','1','3800.00');
INSERT INTO `rota` (`id_rota`,`origem`,`destino`,`distancia_km`,`duracao_estimada`,`id_transportadora`,`activa`,`preco_base`) VALUES ('9','Maputo','Zambézia','7000.00','18:00:00','3','1','5000.00');

DROP TABLE IF EXISTS `suporte_mensagem`;
CREATE TABLE `suporte_mensagem` (
  `id_mensagem` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` int(10) unsigned NOT NULL,
  `autor` enum('passageiro','administrador') NOT NULL,
  `id_utilizador` int(10) unsigned DEFAULT NULL,
  `texto` text NOT NULL,
  `data_hora` datetime NOT NULL,
  PRIMARY KEY (`id_mensagem`),
  KEY `idx_spm_pedido` (`id_pedido`,`id_mensagem`),
  KEY `idx_spm_data` (`autor`,`data_hora`),
  CONSTRAINT `fk_spm_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `suporte_pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `suporte_mensagem` (`id_mensagem`,`id_pedido`,`autor`,`id_utilizador`,`texto`,`data_hora`) VALUES ('1','1','passageiro','1','ola hjfs bsvdgsbg hhws hsh hsdhs hsd s','2026-10-11 18:29:08');

DROP TABLE IF EXISTS `suporte_pedido`;
CREATE TABLE `suporte_pedido` (
  `id_pedido` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_passageiro` int(10) unsigned DEFAULT NULL,
  `nome_contacto` varchar(100) NOT NULL,
  `contacto` varchar(120) NOT NULL,
  `categoria` enum('senha','pagamento','bilhete','reserva','transportadora','conta','outro') NOT NULL,
  `id_reserva` int(10) unsigned DEFAULT NULL,
  `assunto` varchar(150) NOT NULL,
  `estado` enum('aberto','em_curso','resolvido') NOT NULL DEFAULT 'aberto',
  `id_admin` int(10) unsigned DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data_criacao` datetime NOT NULL,
  `data_actualizacao` datetime NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `idx_sp_estado` (`estado`,`data_actualizacao`),
  KEY `idx_sp_passageiro` (`id_passageiro`),
  KEY `idx_sp_ip` (`ip`,`data_criacao`),
  KEY `fk_sp_admin` (`id_admin`),
  KEY `fk_sp_reserva` (`id_reserva`),
  CONSTRAINT `fk_sp_admin` FOREIGN KEY (`id_admin`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sp_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sp_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `suporte_pedido` (`id_pedido`,`id_passageiro`,`nome_contacto`,`contacto`,`categoria`,`id_reserva`,`assunto`,`estado`,`id_admin`,`ip`,`data_criacao`,`data_actualizacao`) VALUES ('1','1','Ioramo Satar','ioramo.satar@moztransport.co.mz','outro',NULL,'Outro assunto','resolvido','5','::1','2026-10-11 18:29:08','2026-10-11 19:09:35');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('1','Nagi Investimentos','400000001','+258 84 000 0001','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('2','CityLink','400000002','+258 84 000 0002','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('3','Lalgy','400000003','+258 84 000 0003','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('4','Capital Way','983132312','850604771','Maputo','1');
INSERT INTO `transportadora` (`id_transportadora`,`nome`,`nuit`,`contacto`,`endereco`,`activa`) VALUES ('6','Khurula Investiment','645354346','875433422','Maputo','1');

DROP TABLE IF EXISTS `utilizador`;
CREATE TABLE `utilizador` (
  `id_utilizador` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `documento` varchar(30) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `agencia` varchar(100) DEFAULT NULL,
  `perfil` enum('passageiro','bilheteiro','cobrador','gestor','administrador') NOT NULL DEFAULT 'passageiro',
  `id_transportadora` int(10) unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tentativas_falhadas` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `notificacoes` tinyint(1) NOT NULL DEFAULT 1,
  `notificacoes_vistas_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utilizador`),
  UNIQUE KEY `uq_utilizador_email` (`email`),
  UNIQUE KEY `uq_utilizador_documento` (`documento`),
  KEY `idx_utilizador_perfil` (`perfil`),
  KEY `fk_utilizador_transportadora` (`id_transportadora`),
  CONSTRAINT `fk_utilizador_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('1','Ioramo Satar','ioramo.satar@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,NULL,NULL,'passageiro',NULL,'1','2026-10-05 11:57:08','0',NULL,'2026-10-11 19:40:23','1','2026-10-11 18:26:08');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('2','Manuel  Zoca','manuel.zoca@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm','+258 85 853 4256',NULL,NULL,'Nagi Investiment','bilheteiro','1','1','2026-10-05 11:57:08','0',NULL,'2026-10-11 18:14:51','1','2026-10-11 18:14:55');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('3','Mikael Chongo','mikael.chongo@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,NULL,'Maputo – Terminal Junta','cobrador','3','1','2026-10-05 11:57:08','0',NULL,'2026-10-11 15:53:53','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('4','Peresterello Nhalicolo','p.nhalicolo@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm','841920856',NULL,NULL,'Maputo – Terminal Junta','gestor','3','1','2026-10-05 11:57:08','0',NULL,'2026-10-11 15:23:25','1','2026-10-11 19:20:02');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('5','Nelson Nhaven','nelson.nhaven@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,'assets/img/perfis/u5_1791213636.jpg',NULL,'administrador',NULL,'1','2026-10-05 11:57:08','0',NULL,'2026-10-11 15:33:23','1','2026-10-11 18:14:42');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('6','Jose Junior','jose.junior@moztransport.co.mz','$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm',NULL,NULL,NULL,'Maputo – Terminal Junta','bilheteiro','2','1','2026-10-05 11:57:08','0',NULL,'2026-10-09 14:17:56','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('7','Francisco Nhavene','francisco@gmail.com','$2y$10$sTF30VsMtTihjUJx4EQWvu9ec8ZGWRTx5hWPtpmtiVJu3dJ6Sn3vi',NULL,NULL,NULL,NULL,'bilheteiro','3','1','2026-10-10 20:22:42','0',NULL,'2026-10-11 19:16:39','1','2026-10-11 19:17:26');
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('8','Jose Mate','jose@gmail.com','$2y$10$RFFtAA2Sl2NZ1a9mmyX7aOqHF/NNkHG80uN79iLGFB6ATHJy6n2J6',NULL,NULL,NULL,NULL,'gestor','1','1','2026-10-10 20:50:07','0',NULL,'2026-10-11 02:08:33','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('9','Antonio Machava','antonio@gmail.com','$2y$10$gO5yytoY1RBSn1ZMBUa6Fu9CDmqNqWRrPSp9ndWgKpr1ARkd34lrm',NULL,NULL,NULL,NULL,'cobrador','1','1','2026-10-10 22:14:30','0',NULL,'2026-10-11 03:16:57','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('10','Nelson Web','web@gmail.com','$2y$10$1AOhJWqmzLnE151OpClISuIMUObZ5MT2Fn5fLdhMRvCEewO.o9aJu',NULL,NULL,NULL,NULL,'cobrador','3','1','2026-10-11 10:49:04','0',NULL,'2026-10-11 15:49:26','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('11','Bradly Alexandre','bradly@gmail.com','$2y$10$I5eAcWUvpURFlZ.QYDF9W.7wNZ7vikG9isdVppJriQPnTLD8zA/iW','873287574',NULL,NULL,NULL,'cobrador','3','1','2026-10-11 11:02:30','0',NULL,'2026-10-11 17:34:10','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('12','jose Augusto','josea@gmail.com','$2y$10$I/VaU8QelioiqsI4s5i2i.3hadS2Axj9PH0SIG2bX.jI.ERfAFN82','874509870909',NULL,NULL,NULL,'cobrador','3','1','2026-10-11 11:50:47','0',NULL,'2026-10-11 17:44:18','1',NULL);
INSERT INTO `utilizador` (`id_utilizador`,`nome`,`email`,`senha`,`telefone`,`documento`,`foto`,`agencia`,`perfil`,`id_transportadora`,`activo`,`data_criacao`,`tentativas_falhadas`,`bloqueado_ate`,`ultimo_acesso`,`notificacoes`,`notificacoes_vistas_em`) VALUES ('13','Nelson Francisco','nelsonf@gmail.com','$2y$10$53LluWAr64kWHrxhtziQXehj72hcuf2akwCzkV0i91yX.zOwZxG6e','+258 85 298 0738','42323242429P',NULL,NULL,'passageiro',NULL,'1','2026-10-11 14:05:35','0',NULL,'2026-10-11 19:06:17','1','2026-10-11 19:08:30');

DROP TABLE IF EXISTS `viagem`;
CREATE TABLE `viagem` (
  `id_viagem` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada` time DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `classe` varchar(30) NOT NULL DEFAULT 'Convencional',
  `estado` enum('agendada','em_curso','concluida','cancelada') NOT NULL DEFAULT 'agendada',
  `id_rota` int(10) unsigned NOT NULL,
  `id_autocarro` int(10) unsigned NOT NULL,
  `id_cobrador` int(10) unsigned DEFAULT NULL,
  `id_motorista` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_viagem`),
  KEY `idx_viagem_data` (`data`,`hora_partida`),
  KEY `fk_viagem_rota` (`id_rota`),
  KEY `fk_viagem_autocarro` (`id_autocarro`),
  KEY `fk_viagem_cobrador` (`id_cobrador`),
  KEY `fk_viagem_motorista` (`id_motorista`),
  CONSTRAINT `fk_viagem_autocarro` FOREIGN KEY (`id_autocarro`) REFERENCES `autocarro` (`id_autocarro`) ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_cobrador` FOREIGN KEY (`id_cobrador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_motorista` FOREIGN KEY (`id_motorista`) REFERENCES `motorista` (`id_motorista`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_viagem_rota` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('1','2026-10-12','10:00:00','14:00:00','4000.00','Executivo · AC','agendada','3','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('2','2026-10-12','02:00:00','04:00:00','1500.00','Executivo · AC','agendada','1','1','3','1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('3','2026-10-13','02:00:00','04:00:00','1500.00','Executivo · AC','agendada','1','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('4','2026-10-12','23:00:00','23:00:00','8000.00','Executivo · AC','agendada','7','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('5','2026-10-12','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('6','2026-10-13','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('7','2026-10-14','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('8','2026-10-15','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('9','2026-10-16','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('10','2026-10-17','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('11','2026-10-18','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('12','2026-10-19','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('13','2026-10-20','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('14','2026-10-21','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('15','2026-10-22','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('16','2026-10-23','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('17','2026-10-24','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('18','2026-10-25','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('19','2026-10-26','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('20','2026-10-27','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('21','2026-10-28','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('22','2026-10-29','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('23','2026-10-30','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('24','2026-10-31','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('25','2026-11-01','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('26','2026-11-02','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('27','2026-11-03','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('28','2026-11-04','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('29','2026-11-05','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('30','2026-11-06','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('31','2026-11-07','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('32','2026-11-08','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('33','2026-11-09','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('34','2026-11-10','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('35','2026-11-11','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('36','2026-11-12','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('37','2026-11-13','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('38','2026-11-14','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('39','2026-11-15','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('40','2026-11-16','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('41','2026-11-17','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('42','2026-11-18','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('43','2026-11-19','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('44','2026-11-20','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('45','2026-11-21','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('46','2026-11-22','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('47','2026-11-23','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('48','2026-11-24','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('49','2026-11-25','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('50','2026-11-26','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('51','2026-11-27','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('52','2026-11-28','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('53','2026-11-29','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('54','2026-11-30','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('55','2026-12-01','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('56','2026-12-02','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('57','2026-12-03','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('58','2026-12-04','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('59','2026-12-05','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('60','2026-12-06','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('61','2026-12-07','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('62','2026-12-08','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('63','2026-12-09','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('64','2026-12-10','17:00:00','08:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('65','2026-10-12','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('66','2026-10-13','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('67','2026-10-14','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('68','2026-10-15','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('69','2026-10-16','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('70','2026-10-17','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('71','2026-10-18','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('72','2026-10-19','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('73','2026-10-20','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('74','2026-10-21','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('75','2026-10-22','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('76','2026-10-23','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('77','2026-10-24','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('78','2026-10-25','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('79','2026-10-26','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('80','2026-10-27','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('81','2026-10-28','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('82','2026-10-29','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('83','2026-10-30','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('84','2026-10-31','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('85','2026-11-01','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('86','2026-11-02','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('87','2026-11-03','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('88','2026-11-04','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('89','2026-11-05','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('90','2026-11-06','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('91','2026-11-07','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('92','2026-11-08','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('93','2026-11-09','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('94','2026-11-10','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('95','2026-11-11','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('96','2026-11-12','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('97','2026-11-13','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('98','2026-11-14','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('99','2026-11-15','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('100','2026-11-16','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('101','2026-11-17','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('102','2026-11-18','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('103','2026-11-19','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('104','2026-11-20','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('105','2026-11-21','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('106','2026-11-22','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('107','2026-11-23','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('108','2026-11-24','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('109','2026-11-25','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('110','2026-11-26','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('111','2026-11-27','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('112','2026-11-28','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('113','2026-11-29','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('114','2026-11-30','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('115','2026-12-01','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('116','2026-12-02','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('117','2026-12-03','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('118','2026-12-04','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('119','2026-12-05','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('120','2026-12-06','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('121','2026-12-07','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('122','2026-12-08','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('123','2026-12-09','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('124','2026-12-10','15:00:00','06:00:00','3800.00','Executivo · AC','agendada','8','1',NULL,'1');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('125','2026-10-12','20:00:00','18:00:00','2500.00','Executivo · AC','agendada','2','3',NULL,'4');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('126','2026-10-13','20:00:00','18:00:00','2500.00','Executivo · AC','agendada','2','3',NULL,'4');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('127','2026-10-14','20:00:00','18:00:00','2500.00','Executivo · AC','agendada','2','3','10','4');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('128','2026-10-12','06:45:00','10:45:00','4000.00','Executivo · AC','agendada','3','3','10','4');
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('129','2026-10-12','18:00:00','12:00:00','5000.00','Executivo · AC','cancelada','9','4',NULL,NULL);
INSERT INTO `viagem` (`id_viagem`,`data`,`hora_partida`,`hora_chegada`,`preco`,`classe`,`estado`,`id_rota`,`id_autocarro`,`id_cobrador`,`id_motorista`) VALUES ('130','2026-10-11','18:00:00','12:00:00','5000.00','Executivo · AC','agendada','9','4','12','5');

SET FOREIGN_KEY_CHECKS=1;
