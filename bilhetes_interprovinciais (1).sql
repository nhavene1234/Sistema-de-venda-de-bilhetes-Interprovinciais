-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Nov-2026 às 15:51
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bilhetes_interprovinciais`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `assento`
--

CREATE TABLE `assento` (
  `id_assento` int(10) UNSIGNED NOT NULL,
  `numero` varchar(5) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'normal',
  `id_autocarro` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `assento`
--

INSERT INTO `assento` (`id_assento`, `numero`, `tipo`, `id_autocarro`) VALUES
(1, '1A', 'normal', 1),
(2, '1B', 'normal', 1),
(3, '1C', 'normal', 1),
(4, '1D', 'normal', 1),
(5, '2A', 'normal', 1),
(6, '2B', 'normal', 1),
(7, '2C', 'normal', 1),
(8, '2D', 'normal', 1),
(9, '3A', 'normal', 1),
(10, '3B', 'normal', 1),
(11, '3C', 'normal', 1),
(12, '3D', 'normal', 1),
(13, '4A', 'normal', 1),
(14, '4B', 'normal', 1),
(15, '4C', 'normal', 1),
(16, '4D', 'normal', 1),
(17, '5A', 'normal', 1),
(18, '5B', 'normal', 1),
(19, '5C', 'normal', 1),
(20, '5D', 'normal', 1),
(21, '6A', 'normal', 1),
(22, '6B', 'normal', 1),
(23, '6C', 'normal', 1),
(24, '6D', 'normal', 1),
(25, '7A', 'normal', 1),
(26, '7B', 'normal', 1),
(27, '7C', 'normal', 1),
(28, '7D', 'normal', 1),
(29, '8A', 'normal', 1),
(30, '8B', 'normal', 1),
(31, '8C', 'normal', 1),
(32, '8D', 'normal', 1),
(33, '9A', 'normal', 1),
(34, '9B', 'normal', 1),
(35, '9C', 'normal', 1),
(36, '9D', 'normal', 1),
(37, '10A', 'normal', 1),
(38, '10B', 'normal', 1),
(39, '10C', 'normal', 1),
(40, '10D', 'normal', 1),
(41, '11A', 'normal', 1),
(42, '11B', 'normal', 1),
(43, '11C', 'normal', 1),
(44, '11D', 'normal', 1),
(45, '12A', 'normal', 1),
(46, '12B', 'normal', 1),
(47, '12C', 'normal', 1),
(48, '12D', 'normal', 1),
(49, '13A', 'normal', 1),
(50, '13B', 'normal', 1),
(51, '13C', 'normal', 1),
(52, '13D', 'normal', 1),
(53, '14A', 'normal', 1),
(54, '14B', 'normal', 1),
(55, '14C', 'normal', 1),
(56, '14D', 'normal', 1),
(57, '15A', 'normal', 1),
(58, '15B', 'normal', 1),
(59, '15C', 'normal', 1),
(60, '15D', 'normal', 1),
(61, '1A', 'normal', 2),
(62, '1B', 'normal', 2),
(63, '1C', 'normal', 2),
(64, '1D', 'normal', 2),
(65, '2A', 'normal', 2),
(66, '2B', 'normal', 2),
(67, '2C', 'normal', 2),
(68, '2D', 'normal', 2),
(69, '3A', 'normal', 2),
(70, '3B', 'normal', 2),
(71, '3C', 'normal', 2),
(72, '3D', 'normal', 2),
(73, '4A', 'normal', 2),
(74, '4B', 'normal', 2),
(75, '4C', 'normal', 2),
(76, '4D', 'normal', 2),
(77, '5A', 'normal', 2),
(78, '5B', 'normal', 2),
(79, '5C', 'normal', 2),
(80, '5D', 'normal', 2),
(81, '6A', 'normal', 2),
(82, '6B', 'normal', 2),
(83, '6C', 'normal', 2),
(84, '6D', 'normal', 2),
(85, '7A', 'normal', 2),
(86, '7B', 'normal', 2),
(87, '7C', 'normal', 2),
(88, '7D', 'normal', 2),
(89, '8A', 'normal', 2),
(90, '8B', 'normal', 2),
(91, '8C', 'normal', 2),
(92, '8D', 'normal', 2),
(93, '9A', 'normal', 2),
(94, '9B', 'normal', 2),
(95, '9C', 'normal', 2),
(96, '9D', 'normal', 2),
(97, '10A', 'normal', 2),
(98, '10B', 'normal', 2),
(99, '10C', 'normal', 2),
(100, '10D', 'normal', 2),
(101, '11A', 'normal', 2),
(102, '11B', 'normal', 2),
(103, '11C', 'normal', 2),
(104, '11D', 'normal', 2),
(105, '12A', 'normal', 2),
(106, '12B', 'normal', 2),
(107, '12C', 'normal', 2),
(108, '12D', 'normal', 2),
(109, '13A', 'normal', 2),
(110, '13B', 'normal', 2),
(111, '13C', 'normal', 2),
(112, '13D', 'normal', 2),
(113, '14A', 'normal', 2),
(114, '14B', 'normal', 2),
(115, '14C', 'normal', 2),
(116, '14D', 'normal', 2),
(117, '15A', 'normal', 2),
(118, '15B', 'normal', 2),
(119, '15C', 'normal', 2),
(120, '15D', 'normal', 2),
(121, '1A', 'normal', 3),
(122, '1B', 'normal', 3),
(123, '1C', 'normal', 3),
(124, '1D', 'normal', 3),
(125, '2A', 'normal', 3),
(126, '2B', 'normal', 3),
(127, '2C', 'normal', 3),
(128, '2D', 'normal', 3),
(129, '3A', 'normal', 3),
(130, '3B', 'normal', 3),
(131, '3C', 'normal', 3),
(132, '3D', 'normal', 3),
(133, '4A', 'normal', 3),
(134, '4B', 'normal', 3),
(135, '4C', 'normal', 3),
(136, '4D', 'normal', 3),
(137, '5A', 'normal', 3),
(138, '5B', 'normal', 3),
(139, '5C', 'normal', 3),
(140, '5D', 'normal', 3),
(141, '6A', 'normal', 3),
(142, '6B', 'normal', 3),
(143, '6C', 'normal', 3),
(144, '6D', 'normal', 3),
(145, '7A', 'normal', 3),
(146, '7B', 'normal', 3),
(147, '7C', 'normal', 3),
(148, '7D', 'normal', 3),
(149, '8A', 'normal', 3),
(150, '8B', 'normal', 3),
(151, '8C', 'normal', 3),
(152, '8D', 'normal', 3),
(153, '9A', 'normal', 3),
(154, '9B', 'normal', 3),
(155, '9C', 'normal', 3),
(156, '9D', 'normal', 3),
(157, '10A', 'normal', 3),
(158, '10B', 'normal', 3),
(159, '10C', 'normal', 3),
(160, '10D', 'normal', 3),
(161, '11A', 'normal', 3),
(162, '11B', 'normal', 3),
(163, '11C', 'normal', 3),
(164, '11D', 'normal', 3),
(165, '12A', 'normal', 3),
(166, '12B', 'normal', 3),
(167, '12C', 'normal', 3),
(168, '12D', 'normal', 3),
(169, '13A', 'normal', 3),
(170, '13B', 'normal', 3),
(171, '13C', 'normal', 3),
(172, '13D', 'normal', 3),
(173, '14A', 'normal', 3),
(174, '14B', 'normal', 3),
(175, '14C', 'normal', 3),
(176, '14D', 'normal', 3),
(177, '15A', 'normal', 3),
(178, '15B', 'normal', 3),
(179, '15C', 'normal', 3),
(180, '15D', 'normal', 3),
(181, '1A', 'normal', 4),
(182, '1B', 'normal', 4),
(183, '1C', 'normal', 4),
(184, '1D', 'normal', 4),
(185, '2A', 'normal', 4),
(186, '2B', 'normal', 4),
(187, '2C', 'normal', 4),
(188, '2D', 'normal', 4),
(189, '3A', 'normal', 4),
(190, '3B', 'normal', 4),
(191, '3C', 'normal', 4),
(192, '3D', 'normal', 4),
(193, '4A', 'normal', 4),
(194, '4B', 'normal', 4),
(195, '4C', 'normal', 4),
(196, '4D', 'normal', 4),
(197, '5A', 'normal', 4),
(198, '5B', 'normal', 4),
(199, '5C', 'normal', 4),
(200, '5D', 'normal', 4),
(201, '6A', 'normal', 4),
(202, '6B', 'normal', 4),
(203, '6C', 'normal', 4),
(204, '6D', 'normal', 4),
(205, '7A', 'normal', 4),
(206, '7B', 'normal', 4),
(207, '7C', 'normal', 4),
(208, '7D', 'normal', 4),
(209, '8A', 'normal', 4),
(210, '8B', 'normal', 4),
(211, '8C', 'normal', 4),
(212, '8D', 'normal', 4),
(213, '9A', 'normal', 4),
(214, '9B', 'normal', 4),
(215, '9C', 'normal', 4),
(216, '9D', 'normal', 4),
(217, '10A', 'normal', 4),
(218, '10B', 'normal', 4),
(219, '10C', 'normal', 4),
(220, '10D', 'normal', 4),
(221, '11A', 'normal', 4),
(222, '11B', 'normal', 4),
(223, '11C', 'normal', 4),
(224, '11D', 'normal', 4),
(225, '12A', 'normal', 4),
(226, '12B', 'normal', 4),
(227, '12C', 'normal', 4),
(228, '12D', 'normal', 4),
(229, '13A', 'normal', 4),
(230, '13B', 'normal', 4),
(231, '13C', 'normal', 4),
(232, '13D', 'normal', 4),
(233, '14A', 'normal', 4),
(234, '14B', 'normal', 4),
(235, '14C', 'normal', 4),
(236, '14D', 'normal', 4),
(237, '15A', 'normal', 4),
(238, '15B', 'normal', 4),
(239, '15C', 'normal', 4),
(240, '15D', 'normal', 4),
(241, '1A', 'normal', 5),
(242, '1B', 'normal', 5),
(243, '1C', 'normal', 5),
(244, '1D', 'normal', 5),
(245, '2A', 'normal', 5),
(246, '2B', 'normal', 5),
(247, '2C', 'normal', 5),
(248, '2D', 'normal', 5),
(249, '3A', 'normal', 5),
(250, '3B', 'normal', 5),
(251, '3C', 'normal', 5),
(252, '3D', 'normal', 5),
(253, '4A', 'normal', 5),
(254, '4B', 'normal', 5),
(255, '4C', 'normal', 5),
(256, '4D', 'normal', 5),
(257, '5A', 'normal', 5),
(258, '5B', 'normal', 5),
(259, '5C', 'normal', 5),
(260, '5D', 'normal', 5),
(261, '6A', 'normal', 5),
(262, '6B', 'normal', 5),
(263, '6C', 'normal', 5),
(264, '6D', 'normal', 5),
(265, '7A', 'normal', 5),
(266, '7B', 'normal', 5),
(267, '7C', 'normal', 5),
(268, '7D', 'normal', 5),
(269, '8A', 'normal', 5),
(270, '8B', 'normal', 5),
(271, '8C', 'normal', 5),
(272, '8D', 'normal', 5),
(273, '9A', 'normal', 5),
(274, '9B', 'normal', 5),
(275, '9C', 'normal', 5),
(276, '9D', 'normal', 5),
(277, '10A', 'normal', 5),
(278, '10B', 'normal', 5),
(279, '10C', 'normal', 5),
(280, '10D', 'normal', 5),
(281, '11A', 'normal', 5),
(282, '11B', 'normal', 5),
(283, '11C', 'normal', 5),
(284, '11D', 'normal', 5),
(285, '12A', 'normal', 5),
(286, '12B', 'normal', 5),
(287, '12C', 'normal', 5),
(288, '12D', 'normal', 5),
(289, '1A', 'normal', 6),
(290, '1B', 'normal', 6),
(291, '1C', 'normal', 6),
(292, '1D', 'normal', 6),
(293, '2A', 'normal', 6),
(294, '2B', 'normal', 6),
(295, '2C', 'normal', 6),
(296, '2D', 'normal', 6),
(297, '3A', 'normal', 6),
(298, '3B', 'normal', 6),
(299, '3C', 'normal', 6),
(300, '3D', 'normal', 6),
(301, '4A', 'normal', 6),
(302, '4B', 'normal', 6),
(303, '4C', 'normal', 6),
(304, '4D', 'normal', 6),
(305, '5A', 'normal', 6),
(306, '5B', 'normal', 6),
(307, '5C', 'normal', 6),
(308, '5D', 'normal', 6),
(309, '6A', 'normal', 6),
(310, '6B', 'normal', 6),
(311, '6C', 'normal', 6),
(312, '6D', 'normal', 6),
(313, '7A', 'normal', 6),
(314, '7B', 'normal', 6),
(315, '7C', 'normal', 6),
(316, '7D', 'normal', 6),
(317, '8A', 'normal', 6),
(318, '8B', 'normal', 6),
(319, '8C', 'normal', 6),
(320, '8D', 'normal', 6),
(321, '9A', 'normal', 6),
(322, '9B', 'normal', 6),
(323, '9C', 'normal', 6),
(324, '9D', 'normal', 6),
(325, '10A', 'normal', 6),
(326, '10B', 'normal', 6),
(327, '10C', 'normal', 6),
(328, '10D', 'normal', 6),
(329, '11A', 'normal', 6),
(330, '11B', 'normal', 6),
(331, '11C', 'normal', 6),
(332, '11D', 'normal', 6),
(333, '12A', 'normal', 6),
(334, '12B', 'normal', 6),
(335, '12C', 'normal', 6),
(336, '12D', 'normal', 6),
(337, '1A', 'normal', 7),
(338, '1B', 'normal', 7),
(339, '1C', 'normal', 7),
(340, '1D', 'normal', 7),
(341, '2A', 'normal', 7),
(342, '2B', 'normal', 7),
(343, '2C', 'normal', 7),
(344, '2D', 'normal', 7),
(345, '3A', 'normal', 7),
(346, '3B', 'normal', 7),
(347, '3C', 'normal', 7),
(348, '3D', 'normal', 7),
(349, '4A', 'normal', 7),
(350, '4B', 'normal', 7),
(351, '4C', 'normal', 7),
(352, '4D', 'normal', 7),
(353, '5A', 'normal', 7),
(354, '5B', 'normal', 7),
(355, '5C', 'normal', 7),
(356, '5D', 'normal', 7),
(357, '6A', 'normal', 7),
(358, '6B', 'normal', 7),
(359, '6C', 'normal', 7),
(360, '6D', 'normal', 7),
(361, '7A', 'normal', 7),
(362, '7B', 'normal', 7),
(363, '7C', 'normal', 7),
(364, '7D', 'normal', 7),
(365, '8A', 'normal', 7),
(366, '8B', 'normal', 7),
(367, '8C', 'normal', 7),
(368, '8D', 'normal', 7),
(369, '9A', 'normal', 7),
(370, '9B', 'normal', 7),
(371, '9C', 'normal', 7),
(372, '9D', 'normal', 7),
(373, '10A', 'normal', 7),
(374, '10B', 'normal', 7),
(375, '10C', 'normal', 7),
(376, '10D', 'normal', 7),
(377, '11A', 'normal', 7),
(378, '11B', 'normal', 7),
(379, '11C', 'normal', 7),
(380, '11D', 'normal', 7),
(381, '12A', 'normal', 7),
(382, '12B', 'normal', 7),
(383, '12C', 'normal', 7),
(384, '12D', 'normal', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `autocarro`
--

CREATE TABLE `autocarro` (
  `id_autocarro` int(10) UNSIGNED NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `modelo` varchar(60) DEFAULT NULL,
  `capacidade_total` smallint(5) UNSIGNED NOT NULL,
  `id_transportadora` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `codigo_frota` varchar(20) DEFAULT NULL,
  `marca` varchar(40) DEFAULT NULL,
  `ano_fabrico` smallint(5) UNSIGNED DEFAULT NULL,
  `tipo_servico` varchar(30) DEFAULT NULL,
  `configuracao_assentos` varchar(5) NOT NULL DEFAULT '2+2',
  `ar_condicionado` tinyint(1) NOT NULL DEFAULT 0,
  `wifi` tinyint(1) NOT NULL DEFAULT 0,
  `usb` tinyint(1) NOT NULL DEFAULT 0,
  `validade_inspecao` date DEFAULT NULL,
  `livrete` varchar(255) DEFAULT NULL,
  `estado` enum('disponivel','manutencao','inactivo') NOT NULL DEFAULT 'disponivel',
  `portas_usb` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `autocarro`
--

INSERT INTO `autocarro` (`id_autocarro`, `matricula`, `modelo`, `capacidade_total`, `id_transportadora`, `activo`, `codigo_frota`, `marca`, `ano_fabrico`, `tipo_servico`, `configuracao_assentos`, `ar_condicionado`, `wifi`, `usb`, `validade_inspecao`, `livrete`, `estado`, `portas_usb`) VALUES
(1, 'AFA 345 MP', 'KG3442HG', 60, 3, 1, 'LA-001', 'Yutong', 2010, 'Executivo · AC', '2+2', 1, 1, 1, '2027-10-11', 'livretes/611edde25aa9abb1b4b17cac.jpg', 'disponivel', 0),
(2, 'AFA 346 MP', 'KG3442ZZG', 60, 1, 1, 'NA-001', 'Outra', 2020, 'Executivo · AC', '2+2', 1, 1, 1, '2027-10-11', 'livretes/ffb981d55d1f9776c75039b8.png', 'disponivel', 0),
(3, 'AAF 444 MP', 'MCxhcgc32', 60, 3, 1, 'LA-002', 'Marcopolo', 2009, 'Executivo · AC', '2+2', 1, 1, 1, '2027-10-11', 'livretes/4afbdab54e431a66e2500a13.png', 'disponivel', 0),
(4, 'MVP 657 IB', 'SDF5AAGHT', 60, 3, 1, 'LA-003', 'Scania', 2018, 'Executivo · AC', '2+2', 1, 1, 1, '2027-10-11', 'livretes/8ead0e5313d8183bbc9df244.png', 'disponivel', 0),
(5, 'AIW-784', 'KG3442HY', 48, 3, 1, 'LA-11', 'Volvo', 2026, 'Convencional', '2+2', 1, 1, 1, '2027-10-16', NULL, 'disponivel', 0),
(6, 'AIW-784-MP', 'KG3442ZZT', 48, 2, 1, 'CI-001', 'Volvo', 2024, 'Convencional', '2+2', 1, 1, 1, '2027-10-16', 'livretes/7a55335493334bd72cb2cff9.jpg', 'disponivel', 0),
(7, 'AIW-784-MC', 'VW', 48, 4, 1, 'CA-001', 'Yutong', 2026, 'Executivo · AC', '2+2', 1, 1, 1, '2027-10-26', NULL, 'disponivel', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bilhete`
--

CREATE TABLE `bilhete` (
  `id_bilhete` int(10) UNSIGNED NOT NULL,
  `codigo_qr` varchar(64) NOT NULL,
  `data_emissao` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('valido','usado','cancelado') NOT NULL DEFAULT 'valido',
  `id_reserva` int(10) UNSIGNED NOT NULL,
  `data_validacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bilhete`
--

INSERT INTO `bilhete` (`id_bilhete`, `codigo_qr`, `data_emissao`, `estado`, `id_reserva`, `data_validacao`) VALUES
(1, 'MT-2026-000003-7028B3DFCBB06C2E', '2026-10-10 20:28:19', 'valido', 3, NULL),
(2, 'MT-2026-000001-6F99EB34A3B0DB3B', '2026-10-10 20:28:19', 'valido', 1, NULL),
(3, 'MT-2026-000002-73E9A123C8CBD812', '2026-10-10 20:28:19', 'valido', 2, NULL),
(4, 'MT-2026-000004-161C113C819D71BD', '2026-10-10 20:28:19', 'valido', 4, NULL),
(5, 'MT-2026-000008-DBA06E19F1B3F4D2', '2026-10-10 20:31:36', 'valido', 8, NULL),
(6, 'MT-2026-000005-BB102CEEE6E6D086', '2026-10-10 20:31:36', 'valido', 5, NULL),
(7, 'MT-2026-000006-3F67D60DA99B71D7', '2026-10-10 20:31:36', 'valido', 6, NULL),
(8, 'MT-2026-000007-DD684CE493E0B847', '2026-10-10 20:31:36', 'valido', 7, NULL),
(9, 'MT-2026-000011-B0F04819702EDA49', '2026-10-10 20:40:23', 'valido', 11, NULL),
(10, 'MT-2026-000012-30A2840BAAA992FB', '2026-10-10 20:40:23', 'valido', 12, NULL),
(11, 'MT-2026-000013-14FCC5DAE9F2DA04', '2026-10-10 20:42:31', 'cancelado', 13, NULL),
(12, 'MT-2026-000014-09A66C5F1E9272C8', '2026-10-10 20:44:50', 'valido', 14, NULL),
(13, 'MT-2026-000015-607D55E1FA72DBA0', '2026-10-11 12:27:42', 'valido', 15, NULL),
(14, 'MT-2026-000017-197E6FE921B04A4A', '2026-10-11 14:08:23', 'valido', 17, NULL),
(15, 'MT-2026-000018-56FA93E9C3F77AAB', '2026-10-11 15:06:53', 'valido', 18, NULL),
(16, 'MT-2026-000023-F3F3B0F60FFFDFF8', '2026-10-11 15:21:31', 'valido', 23, NULL),
(17, 'MT-2026-000024-F6F6F3945612D723', '2026-10-11 15:21:31', 'valido', 24, NULL),
(18, 'MT-2026-000025-AC835944085F8A5F', '2026-10-11 15:21:31', 'valido', 25, NULL),
(19, 'MT-2026-000026-49644AA403F1F579', '2026-10-11 15:21:31', 'valido', 26, NULL),
(20, 'MT-2026-000027-D6F178CD0D0AE298', '2026-10-11 15:21:31', 'valido', 27, NULL),
(21, 'MT-2026-000028-C59660DCF8C4F05C', '2026-10-11 15:21:31', 'valido', 28, NULL),
(22, 'MT-2026-000029-11A976D4381AC689', '2026-10-11 15:41:13', 'valido', 29, NULL),
(23, 'MT-2026-000030-534FC0F548E44846', '2026-10-11 15:41:13', 'valido', 30, NULL),
(24, 'MT-2026-000031-3A1686C2AF01438D', '2026-10-11 15:41:13', 'valido', 31, NULL),
(25, 'MT-2026-000032-A2CABD996C0CC93C', '2026-10-11 15:42:31', 'valido', 32, NULL),
(26, 'MT-2026-000033-A8874A71680CB794', '2026-10-11 16:08:05', 'valido', 33, NULL),
(27, 'MT-2026-000034-27E0A510A319FAEF', '2026-10-12 19:43:51', 'valido', 34, NULL),
(28, 'MT-2026-000035-9683FE5CCF51B760', '2026-10-12 19:43:51', 'valido', 35, NULL),
(29, 'MT-2026-000038-3CC4F287D7B0ABF5', '2026-10-12 19:54:35', 'valido', 38, NULL),
(30, 'MT-2026-000037-D5106323CDA4E506', '2026-10-12 19:54:35', 'valido', 37, NULL),
(31, 'MT-2026-000036-591FD9399DEE7C08', '2026-10-12 19:54:35', 'valido', 36, NULL),
(32, 'MT-2026-000039-1043A067E50E76B9', '2026-10-12 19:56:16', 'valido', 39, NULL),
(33, 'MT-2026-000040-18F5E67F285C6C61', '2026-10-14 16:49:15', 'valido', 40, NULL),
(34, 'MT-2026-000042-05430AF240DFA785', '2026-10-16 08:48:26', 'valido', 42, NULL),
(35, 'MT-2026-000041-E26A895197173ED0', '2026-10-16 08:48:26', 'valido', 41, NULL),
(36, 'MT-2026-000043-1E77545ADE70A32A', '2026-10-16 08:53:16', 'valido', 43, NULL),
(37, 'MT-2026-000044-C969E0C51F295F01', '2026-10-16 08:55:21', 'valido', 44, NULL),
(38, 'MT-2026-000045-F5371214550F6CF8', '2026-10-16 09:12:16', 'valido', 45, NULL),
(39, 'MT-2026-000046-DDA1EDDCEFC31C2A', '2026-10-16 09:12:16', 'valido', 46, NULL),
(40, 'MT-2026-000047-F97A872BDB33D718', '2026-10-16 09:40:08', 'valido', 47, NULL),
(41, 'MT-2026-000048-749C3D5A9C08FDF6', '2026-10-16 09:40:08', 'valido', 48, NULL),
(42, 'MT-2026-000049-831010F750377515', '2026-10-16 09:57:14', 'valido', 49, NULL),
(43, 'MT-2026-000050-59C45EEEEE9AEE92', '2026-10-16 10:05:16', 'valido', 50, NULL),
(44, 'MT-2026-000051-69D32A0E5338DB22', '2026-10-16 10:05:16', 'valido', 51, NULL),
(45, 'MT-2026-000052-371434C086E53E78', '2026-10-16 10:05:16', 'valido', 52, NULL),
(46, 'MT-2026-000053-63F42E69E2674D07', '2026-10-16 10:05:16', 'valido', 53, NULL),
(47, 'MT-2026-000054-A5D9166676C6F489', '2026-10-16 10:05:16', 'valido', 54, NULL),
(48, 'MT-2026-000055-3524B40E54FA4773', '2026-10-16 10:05:16', 'valido', 55, NULL),
(49, 'MT-2026-000056-CA1D67704E006BE5', '2026-10-16 10:05:16', 'valido', 56, NULL),
(50, 'MT-2026-000057-0D4EF1F504B95F6E', '2026-10-16 10:05:16', 'valido', 57, NULL),
(51, 'MT-2026-000058-5D6C5DD39B7A6D4D', '2026-10-26 09:32:33', 'valido', 58, NULL),
(52, 'MT-2026-000062-91AD2AABA5F31B9A', '2026-11-04 07:40:18', 'valido', 62, NULL),
(53, 'MT-2026-000063-4053CB2AC4220158', '2026-11-04 07:40:18', 'valido', 63, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao`
--

CREATE TABLE `configuracao` (
  `chave` varchar(50) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `descricao` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `configuracao`
--

INSERT INTO `configuracao` (`chave`, `valor`, `descricao`) VALUES
('bloqueio_assento_minutos', '10', 'Minutos que o assento fica bloqueado até ao pagamento'),
('bloqueio_minutos', '60', 'Minutos de bloqueio após exceder as tentativas'),
('cancelamento_horas', '24', 'Horas antes da partida até às quais se pode cancelar ou remarcar'),
('recuperacao_validade_minutos', '10', 'Validade do código de recuperação de palavra-passe'),
('tentativas_login', '10', 'Tentativas de login falhadas antes do bloqueio'),
('ultimo_backup', '2026-10-11 15:34:20', 'Data do último backup');

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_transaccao`
--

CREATE TABLE `log_transaccao` (
  `id_log` int(10) UNSIGNED NOT NULL,
  `id_utilizador` int(10) UNSIGNED DEFAULT NULL,
  `accao` varchar(60) NOT NULL,
  `entidade` varchar(40) DEFAULT NULL,
  `id_entidade` int(10) UNSIGNED DEFAULT NULL,
  `detalhes` varchar(255) DEFAULT NULL,
  `data_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `log_transaccao`
--

INSERT INTO `log_transaccao` (`id_log`, `id_utilizador`, `accao`, `entidade`, `id_entidade`, `detalhes`, `data_hora`, `ip`) VALUES
(1, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-05 11:58:24', '::1'),
(2, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-05 11:59:39', '::1'),
(3, NULL, 'Login realizado', 'utilizador', 6, NULL, '2026-10-05 11:59:49', '::1'),
(4, NULL, 'Logout realizado', 'utilizador', 6, NULL, '2026-10-05 12:00:45', '::1'),
(5, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-05 12:01:10', '::1'),
(6, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-05 12:01:35', '::1'),
(7, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-05 12:02:14', '::1'),
(8, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-05 12:09:12', '::1'),
(9, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-05 12:09:42', '::1'),
(10, NULL, 'Permissão alterada', 'permissao', 6, 'Bilheteiro: Ver dados de todas as transportadoras = sim', '2026-10-05 12:12:25', '::1'),
(11, NULL, 'Permissão alterada', 'permissao', 6, 'Bilheteiro: Ver dados de todas as transportadoras = não', '2026-10-05 12:12:26', '::1'),
(12, NULL, 'Permissão alterada', 'permissao', 1, 'Bilheteiro: Ver dashboard = não', '2026-10-05 12:12:39', '::1'),
(13, NULL, 'Permissão alterada', 'permissao', 1, 'Bilheteiro: Ver dashboard = sim', '2026-10-05 12:12:40', '::1'),
(14, NULL, 'Permissão alterada', 'permissao', 2, 'Bilheteiro: Vender bilhetes = não', '2026-10-05 12:12:41', '::1'),
(15, NULL, 'Permissão alterada', 'permissao', 2, 'Bilheteiro: Vender bilhetes = sim', '2026-10-05 12:12:41', '::1'),
(16, NULL, 'Permissão alterada', 'permissao', 10, 'Gestor: Gerir segurança = sim', '2026-10-05 12:12:56', '::1'),
(17, NULL, 'Permissão alterada', 'permissao', 10, 'Gestor: Gerir segurança = não', '2026-10-05 12:12:57', '::1'),
(18, NULL, 'Backup executado', 'sistema', NULL, 'backup_20261005_171415.sql', '2026-10-05 12:14:15', '::1'),
(19, NULL, 'Configuração de bloqueio alterada', 'sistema', NULL, '3 tentativas, 60 min', '2026-10-05 12:15:06', '::1'),
(20, NULL, 'Transportadora adicionada', 'transportadora', 4, 'Capital Way', '2026-10-05 12:16:21', '::1'),
(21, NULL, 'Transportadora adicionada', 'transportadora', 5, 'Kurula', '2026-10-05 12:17:23', '::1'),
(22, NULL, 'Dados pessoais actualizados', 'utilizador', 5, NULL, '2026-10-05 12:20:36', '::1'),
(23, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-05 12:25:11', '::1'),
(24, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-05 12:25:14', '::1'),
(25, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-05 12:25:34', '::1'),
(26, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-05 12:25:42', '::1'),
(27, NULL, 'Dados pessoais actualizados', 'utilizador', 2, NULL, '2026-10-05 12:26:18', '::1'),
(28, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-05 12:26:28', '::1'),
(29, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-05 12:26:30', '::1'),
(30, 4, 'Dados pessoais actualizados', 'utilizador', 4, NULL, '2026-10-05 12:26:46', '::1'),
(31, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-05 12:26:56', '::1'),
(32, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-07 08:01:07', '::1'),
(33, NULL, 'Utilizador bloqueado', 'utilizador', 4, 'Peresterello Nhalicolo', '2026-10-07 08:02:11', '::1'),
(34, NULL, 'Utilizador desbloqueado', 'utilizador', 4, 'Peresterello Nhalicolo', '2026-10-07 08:02:19', '::1'),
(35, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-08 09:06:05', '::1'),
(36, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-08 09:07:38', '::1'),
(37, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-08 09:16:15', '::1'),
(38, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-08 09:16:22', '::1'),
(39, NULL, 'Permissão alterada', 'permissao', 3, 'Gestor: Gerir rotas = não', '2026-10-08 09:19:55', '::1'),
(40, NULL, 'Permissão alterada', 'permissao', 3, 'Gestor: Gerir rotas = sim', '2026-10-08 09:19:55', '::1'),
(41, NULL, 'Transportadora adicionada', 'transportadora', 6, 'Khurula Investiment', '2026-10-08 09:49:45', '::1'),
(42, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-09 08:58:47', '::1'),
(43, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-09 09:04:29', '::1'),
(44, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-09 09:04:37', '::1'),
(45, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-09 09:16:25', '::1'),
(46, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-09 09:17:05', '::1'),
(47, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-09 09:17:34', '::1'),
(48, NULL, 'Login realizado', 'utilizador', 6, NULL, '2026-10-09 09:17:56', '::1'),
(49, NULL, 'Logout realizado', 'utilizador', 6, NULL, '2026-10-09 09:18:16', '::1'),
(50, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-09 09:18:19', '::1'),
(51, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-09 09:18:47', '::1'),
(52, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-09 09:18:55', '::1'),
(53, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-09 09:23:55', '::1'),
(54, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-09 09:24:42', '::1'),
(55, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-09 09:37:12', '::1'),
(56, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-09 09:37:31', '::1'),
(57, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-09 09:37:55', '::1'),
(58, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-09 09:38:05', '::1'),
(59, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-09 09:46:52', '::1'),
(60, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-09 10:26:37', '::1'),
(61, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-09 10:27:25', '::1'),
(62, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 12:45:06', '::1'),
(63, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 12:47:49', '::1'),
(64, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 12:47:52', '::1'),
(65, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 12:47:58', '::1'),
(66, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-10 12:48:00', '::1'),
(67, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-10 12:48:04', '::1'),
(68, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 12:48:07', '::1'),
(69, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 13:16:26', '::1'),
(70, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 13:16:33', '::1'),
(71, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 13:17:37', '::1'),
(72, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 13:17:40', '::1'),
(73, 4, 'Rota criada', 'rota', 1, 'Maputo → Inhambane', '2026-10-10 13:19:17', '::1'),
(74, 4, 'Rota criada', 'rota', 2, 'Maputo → Beirra', '2026-10-10 13:19:46', '::1'),
(75, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 13:19:53', '::1'),
(76, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-10 13:20:14', '::1'),
(77, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-10 13:20:51', '::1'),
(78, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 13:20:54', '::1'),
(79, 4, 'Motorista adicionado', 'motorista', 1, 'MT-001 · Nelson Francisco Nhavene', '2026-10-10 19:30:38', '::1'),
(80, 4, 'Autocarro adicionado', 'autocarro', 1, 'LA-001 · AFA 345 MP', '2026-10-10 19:32:38', '::1'),
(81, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 19:36:08', '::1'),
(82, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-10 19:36:32', '::1'),
(83, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-10 19:42:32', '::1'),
(84, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 19:50:28', '::1'),
(85, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 19:51:05', '::1'),
(86, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 19:58:59', '::1'),
(87, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:03:24', '::1'),
(88, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:05:59', '::1'),
(89, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:07:02', '::1'),
(90, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:07:23', '::1'),
(91, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:19:15', '::1'),
(92, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:19:19', '::1'),
(93, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:19:40', '::1'),
(94, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:19:43', '::1'),
(95, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:19:48', '::1'),
(96, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:19:50', '::1'),
(97, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:20:38', '::1'),
(98, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:20:40', '::1'),
(99, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:21:24', '::1'),
(100, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-10 20:21:27', '::1'),
(101, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-10 20:21:59', '::1'),
(102, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:22:02', '::1'),
(103, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:22:08', '::1'),
(104, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:22:10', '::1'),
(105, NULL, 'Utilizador criado', 'utilizador', 7, 'Francisco Nhavene (bilheteiro)', '2026-10-10 20:22:42', '::1'),
(106, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:22:56', '::1'),
(107, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-10 20:23:10', '::1'),
(108, NULL, 'Palavra-passe alterada', 'utilizador', 7, NULL, '2026-10-10 20:23:40', '::1'),
(109, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-10 20:24:24', '::1'),
(110, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:24:27', '::1'),
(111, 4, 'Rota criada', 'rota', 3, 'Nampula → Maputo', '2026-10-10 20:25:55', '::1'),
(112, 4, 'Viagens programadas', 'rota', 1, '2 viagens às 02:00', '2026-10-10 20:26:28', '::1'),
(113, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:26:33', '::1'),
(114, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-10 20:26:47', '::1'),
(115, NULL, 'Venda presencial registada', 'reserva', 3, 'MT-2026-000003, MT-2026-000001, MT-2026-000002, MT-2026-000004', '2026-10-10 20:28:19', '::1'),
(116, NULL, 'Bilhete enviado por SMS (simulado)', 'reserva', 3, '+258 87 350 5758', '2026-10-10 20:28:51', '::1'),
(117, NULL, 'Venda presencial registada', 'reserva', 8, 'MT-2026-000008, MT-2026-000005, MT-2026-000006, MT-2026-000007', '2026-10-10 20:31:36', '::1'),
(118, NULL, 'Bilhete enviado por SMS (simulado)', 'reserva', 8, '+258 85 060 4771', '2026-10-10 20:31:43', '::1'),
(119, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-10 20:32:56', '::1'),
(120, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:32:59', '::1'),
(121, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:33:32', '::1'),
(122, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-10 20:33:37', '::1'),
(123, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-10 20:33:54', '::1'),
(124, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-10 20:34:07', '::1'),
(125, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-10 20:34:45', '::1'),
(126, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:34:46', '::1'),
(127, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:35:51', '::1'),
(128, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-10 20:35:56', '::1'),
(129, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-10 20:36:00', '::1'),
(130, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-10 20:36:13', '::1'),
(131, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:38:35', '::1'),
(132, NULL, 'Venda presencial registada', 'reserva', 11, 'MT-2026-000011, MT-2026-000012', '2026-10-10 20:40:23', '::1'),
(133, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-10 20:41:06', '::1'),
(134, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-10 20:41:29', '::1'),
(135, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 13, NULL, '2026-10-10 20:41:56', '::1'),
(136, 1, 'Pagamento confirmado', 'reserva', 13, 'MT-2026-000013', '2026-10-10 20:42:31', '::1'),
(137, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 14, NULL, '2026-10-10 20:44:05', '::1'),
(138, 1, 'Pagamento confirmado', 'reserva', 14, 'MT-2026-000014', '2026-10-10 20:44:50', '::1'),
(139, 1, 'Reserva remarcada', 'reserva', 13, 'MT-2026-000013 → 2026-10-13', '2026-10-10 20:45:50', '::1'),
(140, 1, 'Reserva cancelada', 'reserva', 13, 'MT-2026-000013', '2026-10-10 20:46:09', '::1'),
(141, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:47:05', '::1'),
(142, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:47:07', '::1'),
(143, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:47:30', '::1'),
(144, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 20:47:35', '::1'),
(145, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 20:48:28', '::1'),
(146, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:48:37', '::1'),
(147, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-10 20:49:21', '::1'),
(148, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 20:49:23', '::1'),
(149, NULL, 'Utilizador criado', 'utilizador', 8, 'Jose Mate (gestor)', '2026-10-10 20:50:07', '::1'),
(150, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 20:50:23', '::1'),
(151, NULL, 'Login realizado', 'utilizador', 8, NULL, '2026-10-10 20:50:37', '::1'),
(152, NULL, 'Palavra-passe alterada', 'utilizador', 8, NULL, '2026-10-10 20:51:35', '::1'),
(153, NULL, 'Rota criada', 'rota', 4, 'Maputo → Inhambane', '2026-10-10 20:52:48', '::1'),
(154, NULL, 'Rota actualizada', 'rota', 4, 'Maputo → Inhambane', '2026-10-10 20:52:57', '::1'),
(155, NULL, 'Rota criada', 'rota', 5, 'Maputo → Gaza', '2026-10-10 20:53:24', '::1'),
(156, NULL, 'Rota criada', 'rota', 6, 'Maputo → Beira', '2026-10-10 20:53:59', '::1'),
(157, NULL, 'Autocarro adicionado', 'autocarro', 2, 'NA-001 · AFA 346 MP', '2026-10-10 21:01:50', '::1'),
(158, NULL, 'Logout realizado', 'utilizador', 8, NULL, '2026-10-10 21:01:56', '::1'),
(159, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 21:02:00', '::1'),
(160, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 21:02:22', '::1'),
(161, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 21:02:33', '::1'),
(162, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 21:07:34', '::1'),
(163, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 21:08:03', '::1'),
(164, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 21:08:07', '::1'),
(165, NULL, 'Login realizado', 'utilizador', 8, NULL, '2026-10-10 21:08:33', '::1'),
(166, NULL, 'Motorista adicionado', 'motorista', 2, 'MT-001 · Djeld Nhacengo', '2026-10-10 21:11:44', '::1'),
(167, NULL, 'Motorista adicionado', 'motorista', 3, 'MT-002 · Nilton Simbine', '2026-10-10 21:15:01', '::1'),
(168, NULL, 'Estado do motorista alterado', 'motorista', 3, 'MT-002 → Inactivo', '2026-10-10 21:15:12', '::1'),
(169, NULL, 'Estado do motorista alterado', 'motorista', 3, 'MT-002 → De folga', '2026-10-10 21:15:15', '::1'),
(170, NULL, 'Estado do motorista alterado', 'motorista', 3, 'MT-002 → Disponível', '2026-10-10 21:15:16', '::1'),
(171, NULL, 'Logout realizado', 'utilizador', 8, NULL, '2026-10-10 21:17:28', '::1'),
(172, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 21:18:04', '::1'),
(173, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 21:19:35', '::1'),
(174, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 21:19:38', '::1'),
(175, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 21:21:15', '::1'),
(176, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 21:22:14', '::1'),
(177, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 21:23:42', '::1'),
(178, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-10 21:24:47', '::1'),
(179, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 21:42:59', '::1'),
(180, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 21:43:12', '::1'),
(181, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 21:43:28', '::1'),
(182, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 21:43:40', '::1'),
(183, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 21:44:28', '::1'),
(184, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 21:44:31', '::1'),
(185, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 21:45:04', '::1'),
(186, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 21:45:18', '::1'),
(187, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 21:45:49', '::1'),
(188, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 21:45:51', '::1'),
(189, 4, 'Rota criada', 'rota', 7, 'Maputo → Cabo Delgado', '2026-10-10 21:47:37', '::1'),
(190, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 21:47:45', '::1'),
(191, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 21:47:56', '::1'),
(192, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 22:11:08', '::1'),
(193, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 22:11:10', '::1'),
(194, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 22:12:12', '::1'),
(195, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 22:12:16', '::1'),
(196, 4, 'Cobrador atribuído a viagens', 'utilizador', 3, 'Mikael Chongo · 1 viagem(ns)', '2026-10-10 22:12:49', '::1'),
(197, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 22:13:34', '::1'),
(198, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 22:13:37', '::1'),
(199, NULL, 'Utilizador criado', 'utilizador', 9, 'Antonio Machava (cobrador)', '2026-10-10 22:14:30', '::1'),
(200, NULL, 'Palavra-passe reposta', 'utilizador', 9, 'Antonio Machava', '2026-10-10 22:16:33', '::1'),
(201, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 22:16:44', '::1'),
(202, NULL, 'Login realizado', 'utilizador', 9, NULL, '2026-10-10 22:16:57', '::1'),
(203, NULL, 'Logout realizado', 'utilizador', 9, NULL, '2026-10-10 22:17:45', '::1'),
(204, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-10 22:18:14', '::1'),
(205, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-10 22:18:29', '::1'),
(206, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 22:18:40', '::1'),
(207, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 22:19:07', '::1'),
(208, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-10 22:19:13', '::1'),
(209, 4, 'Cobrador atribuído a viagens', 'utilizador', 3, 'Mikael Chongo · 1 viagem(ns)', '2026-10-10 22:20:30', '::1'),
(210, 4, 'Cobrador atribuído a viagens', 'utilizador', 3, 'Mikael Chongo · 1 viagem(ns) · motorista Nelson Francisco Nhavene', '2026-10-10 22:20:41', '::1'),
(211, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-10 22:20:51', '::1'),
(212, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-10 22:21:02', '::1'),
(213, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-10 22:29:12', '::1'),
(214, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 10:39:36', '::1'),
(215, 4, 'Rota criada', 'rota', 8, 'Maputo → Manica', '2026-10-11 10:41:16', '::1'),
(216, 4, 'Viagens programadas', 'rota', 8, '60 viagens às 15:00', '2026-10-11 10:41:52', '::1'),
(217, 4, 'Motorista adicionado', 'motorista', 4, 'MT-002 · Alberto Cumbane', '2026-10-11 10:44:23', '::1'),
(218, 4, 'Autocarro adicionado', 'autocarro', 3, 'LA-002 · AAF 444 MP', '2026-10-11 10:46:17', '::1'),
(219, 4, 'Viagens programadas', 'rota', 2, '3 viagens às 20:00', '2026-10-11 10:47:03', '::1'),
(220, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 10:48:19', '::1'),
(221, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 10:48:22', '::1'),
(222, NULL, 'Utilizador criado', 'utilizador', 10, 'Nelson Web (cobrador)', '2026-10-11 10:49:04', '::1'),
(223, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 10:49:17', '::1'),
(224, NULL, 'Login realizado', 'utilizador', 10, NULL, '2026-10-11 10:49:26', '::1'),
(225, NULL, 'Logout realizado', 'utilizador', 10, NULL, '2026-10-11 10:49:44', '::1'),
(226, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 10:49:48', '::1'),
(227, 4, 'Cobrador atribuído a viagens', 'utilizador', 10, 'Nelson Web · 1 viagem(ns) · motorista Alberto Cumbane', '2026-10-11 10:50:52', '::1'),
(228, 4, 'Viagens programadas', 'rota', 3, '1 viagens às 06:45', '2026-10-11 10:52:07', '::1'),
(229, 4, 'Cobrador atribuído a viagens', 'utilizador', 10, 'Nelson Web · 1 viagem(ns) · motorista Alberto Cumbane', '2026-10-11 10:52:40', '::1'),
(230, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 10:53:18', '::1'),
(231, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 10:53:29', '::1'),
(232, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 10:53:43', '::1'),
(233, NULL, 'Login realizado', 'utilizador', 3, NULL, '2026-10-11 10:53:53', '::1'),
(234, NULL, 'Logout realizado', 'utilizador', 3, NULL, '2026-10-11 10:54:32', '::1'),
(235, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 11:01:27', '::1'),
(236, 4, 'Cobrador criado', 'utilizador', 11, 'Bradly Alexandre → Lalgy', '2026-10-11 11:02:30', '::1'),
(237, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 11:04:07', '::1'),
(238, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 11:04:36', '::1'),
(239, 4, 'Palavra-passe de cobrador reposta', 'utilizador', 11, 'Bradly Alexandre', '2026-10-11 11:04:49', '::1'),
(240, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 11:05:02', '::1'),
(241, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-11 11:05:24', '::1'),
(242, NULL, 'Dados pessoais actualizados', 'utilizador', 11, NULL, '2026-10-11 11:05:39', '::1'),
(243, NULL, 'Palavra-passe alterada', 'utilizador', 11, NULL, '2026-10-11 11:06:00', '::1'),
(244, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-11 11:07:31', '::1'),
(245, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 11:07:35', '::1'),
(246, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 11:08:16', '::1'),
(247, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 11:10:48', '::1'),
(248, 4, 'Autocarro adicionado', 'autocarro', 4, 'LA-003 · MVP 657 IB', '2026-10-11 11:24:32', '::1'),
(249, 4, 'Rota criada', 'rota', 9, 'Maputo → Zambézia', '2026-10-11 11:31:43', '::1'),
(250, 4, 'Motorista adicionado', 'motorista', 5, 'MT-003 · Pedro Tembe', '2026-10-11 11:33:38', '::1'),
(251, 4, 'Viagens programadas', 'rota', 9, '0 viagens às 18:00', '2026-10-11 11:34:50', '::1'),
(252, 4, 'Viagem cancelada', 'viagem', 129, '12/10 18:00 · Maputo → Zambézia', '2026-10-11 11:35:18', '::1'),
(253, 4, 'Viagens criadas', 'rota', 9, '1 viagem(ns) Maputo → Zambézia às 18:00 a partir de 2026-10-11', '2026-10-11 11:35:49', '::1'),
(254, 4, 'Cobrador criado', 'utilizador', 12, 'jose Augusto → Lalgy', '2026-10-11 11:50:47', '::1'),
(255, 4, 'Cobrador atribuído a viagens', 'utilizador', 12, 'jose Augusto · 1 viagem(ns) · motorista Pedro Tembe', '2026-10-11 11:51:42', '::1'),
(256, 4, 'Palavra-passe de cobrador reposta', 'utilizador', 12, 'jose Augusto', '2026-10-11 11:52:03', '::1'),
(257, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 11:52:09', '::1'),
(258, NULL, 'Login realizado', 'utilizador', 12, NULL, '2026-10-11 11:52:20', '::1'),
(259, NULL, 'Logout realizado', 'utilizador', 12, NULL, '2026-10-11 11:52:39', '::1'),
(260, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 11:52:56', '::1'),
(261, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 11:54:47', '::1'),
(262, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-11 11:54:50', '::1'),
(263, NULL, 'Dados pessoais actualizados', 'utilizador', 2, NULL, '2026-10-11 11:55:23', '::1'),
(264, NULL, 'Dados pessoais actualizados', 'utilizador', 2, NULL, '2026-10-11 11:55:23', '::1'),
(265, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-11 11:56:06', '::1'),
(266, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 11:56:08', '::1'),
(267, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 11:56:53', '::1'),
(268, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-11 11:57:04', '::1'),
(269, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-11 12:05:23', '::1'),
(270, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 12:05:25', '::1'),
(271, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 12:09:26', '::1'),
(272, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 12:09:33', '::1'),
(273, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 12:11:57', '::1'),
(274, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 12:12:11', '::1'),
(275, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 15, NULL, '2026-10-11 12:27:22', '::1'),
(276, 1, 'Pagamento confirmado', 'reserva', 15, 'MT-2026-000015', '2026-10-11 12:27:42', '::1'),
(277, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 12:33:24', '::1'),
(278, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 12:33:30', '::1'),
(279, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 12:33:55', '::1'),
(280, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-11 12:34:10', '::1'),
(281, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-11 12:35:21', '::1'),
(282, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 12:35:24', '::1'),
(283, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 12:35:43', '::1'),
(284, NULL, 'Recuperação de palavra-passe solicitada', 'utilizador', 12, NULL, '2026-10-11 12:36:21', '::1'),
(285, NULL, 'Palavra-passe redefinida', 'utilizador', 12, NULL, '2026-10-11 12:37:01', '::1'),
(286, NULL, 'Login realizado', 'utilizador', 12, NULL, '2026-10-11 12:37:17', '::1'),
(287, NULL, 'Embarque recusado', 'reserva', 0, '2026-000015 · Bilhete não encontrado', '2026-10-11 12:37:51', '::1'),
(288, NULL, 'Dados pessoais actualizados', 'utilizador', 12, NULL, '2026-10-11 12:40:37', '::1'),
(289, NULL, 'Logout realizado', 'utilizador', 12, NULL, '2026-10-11 12:40:52', '::1'),
(290, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 12:40:55', '::1'),
(291, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 12:41:50', '::1'),
(292, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 12:41:52', '::1'),
(293, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 12:44:01', '::1'),
(294, NULL, 'Login realizado', 'utilizador', 12, NULL, '2026-10-11 12:44:18', '::1'),
(295, NULL, 'Embarque confirmado', 'reserva', 15, 'MT-2026-000015', '2026-10-11 12:48:09', '::1'),
(296, NULL, 'Logout realizado', 'utilizador', 12, NULL, '2026-10-11 12:54:10', '::1'),
(297, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 12:54:12', '::1'),
(298, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 13:14:37', '::1'),
(299, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 13:14:39', '::1'),
(300, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 13:14:49', '::1'),
(301, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-11 13:14:51', '::1'),
(302, NULL, 'Dados pessoais actualizados', 'utilizador', 2, NULL, '2026-10-11 13:15:28', '::1'),
(303, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-11 13:15:34', '::1'),
(304, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 13:15:37', '::1'),
(305, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 13:25:57', '::1'),
(306, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 13:26:05', '::1'),
(307, 1, 'Pedido de ajuda criado', 'suporte_pedido', 1, 'Outro assunto', '2026-10-11 13:29:08', '::1'),
(308, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 13:29:27', '::1'),
(309, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 13:29:29', '::1'),
(310, NULL, 'Pedido de ajuda: estado alterado', 'suporte_pedido', 1, 'Em curso', '2026-10-11 13:46:56', '::1'),
(311, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 13:47:06', '::1'),
(312, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 13:47:18', '::1'),
(313, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 13:48:45', '::1'),
(314, NULL, 'Conta de passageiro criada', 'utilizador', 13, NULL, '2026-10-11 14:05:35', '::1'),
(315, NULL, 'Login realizado', 'utilizador', 13, NULL, '2026-10-11 14:06:17', '::1'),
(316, NULL, 'Lugar reservado (a aguardar pagamento)', 'reserva', 16, NULL, '2026-10-11 14:07:36', '::1'),
(317, NULL, 'Lugar reservado (a aguardar pagamento)', 'reserva', 17, NULL, '2026-10-11 14:07:57', '::1'),
(318, NULL, 'Pagamento confirmado', 'reserva', 17, 'MT-2026-000017', '2026-10-11 14:08:23', '::1'),
(319, NULL, 'Logout realizado', 'utilizador', 13, NULL, '2026-10-11 14:09:04', '::1'),
(320, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 14:09:13', '::1'),
(321, NULL, 'Pedido de ajuda: estado alterado', 'suporte_pedido', 1, 'Resolvido', '2026-10-11 14:09:35', '::1'),
(322, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 14:15:30', '::1'),
(323, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 14:15:47', '::1'),
(324, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 14:16:03', '::1'),
(325, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 14:16:15', '::1'),
(326, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 14:16:28', '::1'),
(327, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-11 14:16:39', '::1'),
(328, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-11 14:17:52', '::1'),
(329, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 14:17:54', '::1'),
(330, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 14:38:54', '::1'),
(331, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 14:38:57', '::1'),
(332, NULL, 'Backup executado', 'sistema', NULL, 'backup_20261011_193921.sql', '2026-10-11 14:39:21', '::1'),
(333, NULL, 'Backup executado', 'sistema', NULL, 'backup_20261011_193926.sql', '2026-10-11 14:39:26', '::1'),
(334, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 14:40:06', '::1'),
(335, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 14:40:23', '::1'),
(336, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 18, NULL, '2026-10-11 15:05:58', '::1'),
(337, 1, 'Pagamento confirmado', 'reserva', 18, 'MT-2026-000018', '2026-10-11 15:06:53', '::1'),
(338, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 19, NULL, '2026-10-11 15:21:02', '::1'),
(339, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 20, NULL, '2026-10-11 15:21:02', '::1'),
(340, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 21, NULL, '2026-10-11 15:21:02', '::1'),
(341, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 22, NULL, '2026-10-11 15:21:02', '::1'),
(342, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 23, NULL, '2026-10-11 15:21:13', '::1'),
(343, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 24, NULL, '2026-10-11 15:21:13', '::1'),
(344, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 25, NULL, '2026-10-11 15:21:13', '::1'),
(345, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 26, NULL, '2026-10-11 15:21:13', '::1'),
(346, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 27, NULL, '2026-10-11 15:21:13', '::1'),
(347, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 28, NULL, '2026-10-11 15:21:13', '::1'),
(348, 1, 'Pagamento confirmado', 'reserva', 23, 'MT-2026-000023', '2026-10-11 15:21:31', '::1'),
(349, 1, 'Pagamento confirmado', 'reserva', 24, 'MT-2026-000024', '2026-10-11 15:21:31', '::1'),
(350, 1, 'Pagamento confirmado', 'reserva', 25, 'MT-2026-000025', '2026-10-11 15:21:31', '::1'),
(351, 1, 'Pagamento confirmado', 'reserva', 26, 'MT-2026-000026', '2026-10-11 15:21:31', '::1'),
(352, 1, 'Pagamento confirmado', 'reserva', 27, 'MT-2026-000027', '2026-10-11 15:21:31', '::1'),
(353, 1, 'Pagamento confirmado', 'reserva', 28, 'MT-2026-000028', '2026-10-11 15:21:31', '::1'),
(354, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 15:23:21', '::1'),
(355, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 15:23:25', '::1'),
(356, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 15:33:20', '::1'),
(357, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 15:33:23', '::1'),
(358, NULL, 'Backup executado', 'sistema', NULL, 'backup_20261011_153419.sql', '2026-10-11 15:34:20', '::1'),
(359, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 15:37:10', '::1'),
(360, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-11 15:37:23', '::1'),
(361, NULL, 'Dados pessoais actualizados', 'utilizador', 2, NULL, '2026-10-11 15:37:34', '::1'),
(362, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-11 15:37:51', '::1'),
(363, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 15:37:53', '::1'),
(364, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 15:38:18', '::1'),
(365, NULL, 'Login realizado', 'utilizador', 8, NULL, '2026-10-11 15:38:28', '::1'),
(366, NULL, 'Viagens criadas', 'rota', 6, '1 viagem(ns) Maputo → Beira às 12:00 a partir de 2026-10-12', '2026-10-11 15:39:50', '::1'),
(367, NULL, 'Logout realizado', 'utilizador', 8, NULL, '2026-10-11 15:40:11', '::1'),
(368, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-11 15:40:14', '::1'),
(369, NULL, 'Venda presencial registada', 'reserva', 29, 'MT-2026-000029, MT-2026-000030, MT-2026-000031', '2026-10-11 15:41:13', '::1'),
(370, NULL, 'Bilhete enviado por SMS (simulado)', 'reserva', 29, '+258 87 450 6259', '2026-10-11 15:41:19', '::1'),
(371, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-11 15:42:02', '::1'),
(372, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 15:42:13', '::1'),
(373, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 32, NULL, '2026-10-11 15:42:29', '::1'),
(374, 1, 'Pagamento confirmado', 'reserva', 32, 'MT-2026-000032', '2026-10-11 15:42:31', '::1'),
(375, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 15:42:52', '::1'),
(376, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-11 15:43:20', '::1'),
(377, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-11 15:43:24', '::1'),
(378, NULL, 'Login realizado', 'utilizador', 8, NULL, '2026-10-11 15:46:55', '::1'),
(379, NULL, 'Logout realizado', 'utilizador', 8, NULL, '2026-10-11 15:48:12', '::1'),
(380, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 15:48:14', '::1'),
(381, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 16:07:43', '::1'),
(382, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-11 16:07:54', '::1'),
(383, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 33, NULL, '2026-10-11 16:08:01', '::1'),
(384, 1, 'Pagamento confirmado', 'reserva', 33, 'MT-2026-000033', '2026-10-11 16:08:05', '::1'),
(385, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-11 16:08:47', '::1'),
(386, NULL, 'Login realizado', 'utilizador', 8, NULL, '2026-10-11 16:09:08', '::1'),
(387, NULL, 'Logout realizado', 'utilizador', 8, NULL, '2026-10-11 16:09:12', '::1'),
(388, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 16:09:16', '::1'),
(389, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 16:09:28', '::1'),
(390, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 16:09:55', '::1'),
(391, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 16:10:11', '::1'),
(392, NULL, 'Recuperação de palavra-passe solicitada', 'utilizador', 9, NULL, '2026-10-11 16:10:30', '::1'),
(393, NULL, 'Palavra-passe redefinida', 'utilizador', 9, NULL, '2026-10-11 16:10:49', '::1'),
(394, NULL, 'Login realizado', 'utilizador', 9, NULL, '2026-10-11 16:11:02', '::1'),
(395, NULL, 'Logout realizado', 'utilizador', 9, NULL, '2026-10-11 16:14:10', '::1'),
(396, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-11 20:02:40', '::1'),
(397, NULL, 'Permissão alterada', 'permissao', 6, 'Bilheteiro: Ver dados de todas as transportadoras = sim', '2026-10-11 20:03:20', '::1'),
(398, NULL, 'Permissão alterada', 'permissao', 6, 'Bilheteiro: Ver dados de todas as transportadoras = não', '2026-10-11 20:03:21', '::1'),
(399, NULL, 'Permissão alterada', 'permissao', 6, 'Cobrador: Ver dados de todas as transportadoras = sim', '2026-10-11 20:03:22', '::1'),
(400, NULL, 'Permissão alterada', 'permissao', 6, 'Cobrador: Ver dados de todas as transportadoras = não', '2026-10-11 20:03:22', '::1'),
(401, NULL, 'Permissão alterada', 'permissao', 6, 'Gestor: Ver dados de todas as transportadoras = sim', '2026-10-11 20:03:24', '::1'),
(402, NULL, 'Permissão alterada', 'permissao', 6, 'Gestor: Ver dados de todas as transportadoras = não', '2026-10-11 20:03:24', '::1'),
(403, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-11 20:03:32', '::1'),
(404, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 16:12:46', '::1'),
(405, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 16:12:52', '::1'),
(406, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-12 16:12:56', '::1'),
(407, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-12 16:16:14', '::1'),
(408, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-12 16:16:29', '::1'),
(409, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-12 16:17:46', '::1'),
(410, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-12 16:17:50', '::1'),
(411, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-12 16:18:00', '::1'),
(412, NULL, 'Login realizado', 'utilizador', 9, NULL, '2026-10-12 16:18:14', '::1'),
(413, NULL, 'Logout realizado', 'utilizador', 9, NULL, '2026-10-12 16:19:43', '::1'),
(414, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-12 16:19:45', '::1'),
(415, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-12 16:20:57', '::1'),
(416, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 16:20:59', '::1'),
(417, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 19:09:23', '::1'),
(418, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-12 19:09:25', '::1'),
(419, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-12 19:09:31', '::1'),
(420, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-12 19:15:59', '::1'),
(421, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-12 19:32:16', '::1'),
(422, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 19:32:21', '::1'),
(423, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 19:35:07', '::1'),
(424, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-12 19:35:36', '::1'),
(425, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-12 19:37:07', '::1'),
(426, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-12 19:37:10', '::1'),
(427, 4, 'Rota criada', 'rota', 10, 'Cabo Delgado → Maputo', '2026-10-12 19:37:49', '::1'),
(428, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-12 19:38:30', '::1'),
(429, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-12 19:38:46', '::1'),
(430, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 34, NULL, '2026-10-12 19:42:15', '::1'),
(431, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 35, NULL, '2026-10-12 19:42:15', '::1'),
(432, 1, 'Pagamento confirmado', 'reserva', 34, 'MT-2026-000034', '2026-10-12 19:43:51', '::1'),
(433, 1, 'Pagamento confirmado', 'reserva', 35, 'MT-2026-000035', '2026-10-12 19:43:51', '::1'),
(434, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-12 19:46:26', '::1'),
(435, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-12 19:46:37', '::1'),
(436, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-12 19:46:42', '::1'),
(437, NULL, 'Conta de passageiro criada', 'utilizador', 14, NULL, '2026-10-12 19:48:03', '::1'),
(438, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 19:48:19', '::1'),
(439, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 19:48:56', '::1'),
(440, NULL, 'Login realizado', 'utilizador', 12, NULL, '2026-10-12 19:49:17', '::1'),
(441, NULL, 'Logout realizado', 'utilizador', 12, NULL, '2026-10-12 19:49:38', '::1'),
(442, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 19:49:41', '::1'),
(443, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 19:50:19', '::1'),
(444, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-12 19:50:28', '::1'),
(445, NULL, 'Venda presencial registada', 'reserva', 38, 'MT-2026-000038, MT-2026-000037, MT-2026-000036', '2026-10-12 19:54:35', '::1'),
(446, NULL, 'Venda presencial registada', 'reserva', 39, 'MT-2026-000039', '2026-10-12 19:56:16', '::1'),
(447, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-12 19:57:55', '::1'),
(448, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-12 19:58:00', '::1'),
(449, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-12 19:58:25', '::1'),
(450, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-12 19:58:32', '::1'),
(451, 4, 'Cobrador atribuído a viagens', 'utilizador', 11, 'Bradly Alexandre · 1 viagem(ns) · motorista Pedro Tembe', '2026-10-12 19:59:19', '::1'),
(452, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-12 19:59:23', '::1'),
(453, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-12 19:59:44', '::1'),
(454, NULL, 'Embarque confirmado', 'reserva', 39, 'MT-2026-000039', '2026-10-12 20:01:23', '::1'),
(455, NULL, 'Embarque confirmado', 'reserva', 35, 'MT-2026-000035-9683FE5CCF51B760', '2026-10-12 20:02:58', '::1'),
(456, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-12 20:03:50', '::1'),
(457, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-12 20:06:17', '::1'),
(458, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-13 09:41:27', '::1'),
(459, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-14 08:45:30', '::1'),
(460, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-14 08:46:42', '::1'),
(461, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-14 08:47:06', '::1'),
(462, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-14 08:50:09', '::1'),
(463, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-14 08:50:12', '::1'),
(464, NULL, 'Login realizado', 'utilizador', 2, NULL, '2026-10-14 08:50:16', '::1'),
(465, NULL, 'Logout realizado', 'utilizador', 2, NULL, '2026-10-14 08:50:19', '::1'),
(466, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-14 08:51:32', '::1'),
(467, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-14 08:52:22', '::1'),
(468, NULL, 'Conta de passageiro criada', 'utilizador', 15, NULL, '2026-10-14 09:23:50', '::1'),
(469, NULL, 'Login realizado', 'utilizador', 15, NULL, '2026-10-14 09:24:03', '::1'),
(470, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-14 16:26:01', '::1'),
(471, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-14 16:26:05', '::1'),
(472, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-14 16:26:07', '::1'),
(473, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-14 16:27:44', '::1'),
(474, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-14 16:28:00', '::1'),
(475, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-14 16:34:26', '::1'),
(476, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-14 16:34:29', '::1'),
(477, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-14 16:39:58', '::1'),
(478, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-14 16:40:01', '::1'),
(479, NULL, 'Utilizador criado', 'utilizador', 16, 'Nelson Nhavene (administrador)', '2026-10-14 16:40:48', '::1'),
(480, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-14 16:41:10', '::1'),
(481, NULL, 'Login realizado', 'utilizador', 16, NULL, '2026-10-14 16:41:31', '::1'),
(482, NULL, 'Logout realizado', 'utilizador', 16, NULL, '2026-10-14 16:44:06', '::1'),
(483, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-14 16:44:08', '::1'),
(484, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-14 16:48:21', '::1'),
(485, NULL, 'Login realizado', 'utilizador', 13, NULL, '2026-10-14 16:48:45', '::1'),
(486, NULL, 'Lugar reservado (a aguardar pagamento)', 'reserva', 40, NULL, '2026-10-14 16:48:56', '::1'),
(487, NULL, 'Pagamento confirmado', 'reserva', 40, 'MT-2026-000040', '2026-10-14 16:49:15', '::1'),
(488, NULL, 'Logout realizado', 'utilizador', 13, NULL, '2026-10-14 16:50:37', '::1'),
(489, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-14 16:50:40', '::1'),
(490, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-16 08:10:43', '::1'),
(491, 4, 'Login realizado', 'utilizador', 4, NULL, '2026-10-16 08:10:47', '::1'),
(492, 4, 'Logout realizado', 'utilizador', 4, NULL, '2026-10-16 08:10:52', '::1'),
(493, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-16 08:11:21', '::1'),
(494, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-16 08:11:23', '::1'),
(495, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-16 08:11:27', '::1'),
(496, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-16 08:11:57', '::1'),
(497, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-16 08:12:14', '::1'),
(498, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-16 08:13:56', '::1'),
(499, NULL, 'Conta bloqueada por tentativas falhadas', 'utilizador', 16, NULL, '2026-10-16 08:19:26', '::1'),
(500, NULL, 'Login realizado', 'utilizador', 5, NULL, '2026-10-16 08:19:30', '::1'),
(501, NULL, 'Utilizador desbloqueado', 'utilizador', 16, 'Nelson Nhavene', '2026-10-16 08:19:51', '::1'),
(502, NULL, 'Logout realizado', 'utilizador', 5, NULL, '2026-10-16 08:19:54', '::1'),
(503, NULL, 'Recuperação de palavra-passe solicitada', 'utilizador', 16, NULL, '2026-10-16 08:20:30', '::1'),
(504, NULL, 'Palavra-passe redefinida', 'utilizador', 16, NULL, '2026-10-16 08:20:49', '::1'),
(505, NULL, 'Login realizado', 'utilizador', 16, NULL, '2026-10-16 08:21:01', '::1'),
(506, NULL, 'Utilizador criado', 'utilizador', 17, 'Peresterello Nhalicolo (gestor)', '2026-10-16 08:23:43', '::1'),
(507, NULL, 'Logout realizado', 'utilizador', 16, NULL, '2026-10-16 08:23:51', '::1'),
(508, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 08:24:14', '::1'),
(509, 17, 'Palavra-passe alterada', 'utilizador', 17, NULL, '2026-10-16 08:24:33', '::1'),
(510, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 08:24:44', '::1'),
(511, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 08:25:12', '::1'),
(512, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 08:28:47', '::1'),
(513, NULL, 'Login realizado', 'utilizador', 16, NULL, '2026-10-16 08:29:41', '::1'),
(514, NULL, 'Logout realizado', 'utilizador', 16, NULL, '2026-10-16 08:30:28', '::1'),
(515, NULL, 'Login realizado', 'utilizador', 16, NULL, '2026-10-16 08:31:53', '::1'),
(516, NULL, 'Utilizador criado', 'utilizador', 18, 'Mikael Chongo (administrador)', '2026-10-16 08:32:37', '::1'),
(517, NULL, 'Logout realizado', 'utilizador', 16, NULL, '2026-10-16 08:32:55', '::1'),
(518, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 08:33:14', '::1'),
(519, NULL, 'Palavra-passe alterada', 'utilizador', 18, NULL, '2026-10-16 08:34:02', '::1'),
(520, NULL, 'Logout realizado', 'utilizador', 18, NULL, '2026-10-16 08:34:15', '::1'),
(521, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 08:35:04', '::1'),
(522, NULL, 'Logout realizado', 'utilizador', 18, NULL, '2026-10-16 08:35:49', '::1'),
(523, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 08:36:05', '::1'),
(524, 17, 'Motorista adicionado', 'motorista', 6, 'MT-004 · Leva Gente', '2026-10-16 08:38:50', '::1'),
(525, 17, 'Rota criada', 'rota', 11, 'Maputo → Sofala', '2026-10-16 08:41:44', '::1'),
(526, 17, 'Autocarro adicionado', 'autocarro', 5, 'LA-11 · AIW-784', '2026-10-16 08:43:49', '::1'),
(527, 17, 'Viagens criadas', 'rota', 11, '1 viagem(ns) Maputo → Sofala às 10:00 a partir de 2026-10-20', '2026-10-16 08:44:28', '::1'),
(528, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 08:45:17', '::1'),
(529, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-16 08:46:16', '::1'),
(530, NULL, 'Venda presencial registada', 'reserva', 42, 'MT-2026-000042, MT-2026-000041', '2026-10-16 08:48:26', '::1'),
(531, NULL, 'Venda presencial registada', 'reserva', 43, 'MT-2026-000043', '2026-10-16 08:53:16', '::1'),
(532, NULL, 'Venda presencial registada', 'reserva', 44, 'MT-2026-000044', '2026-10-16 08:55:21', '::1'),
(533, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-16 09:03:01', '::1'),
(534, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 09:04:40', '::1'),
(535, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 09:04:55', '::1'),
(536, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-16 09:05:21', '::1'),
(537, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-16 09:08:49', '::1'),
(538, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-16 09:09:23', '::1'),
(539, NULL, 'Venda presencial registada', 'reserva', 45, 'MT-2026-000045, MT-2026-000046', '2026-10-16 09:12:16', '::1'),
(540, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-16 09:13:53', '::1'),
(541, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-16 09:14:04', '::1'),
(542, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-16 09:14:31', '::1'),
(543, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 09:14:45', '::1'),
(544, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 09:16:37', '::1'),
(545, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-16 09:16:56', '::1'),
(546, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-16 09:17:53', '::1'),
(547, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 09:18:04', '::1'),
(548, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 09:18:34', '::1'),
(549, NULL, 'Login realizado', 'utilizador', 11, NULL, '2026-10-16 09:18:42', '::1'),
(550, NULL, 'Logout realizado', 'utilizador', 11, NULL, '2026-10-16 09:19:05', '::1'),
(551, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 09:19:16', '::1'),
(552, 17, 'Viagem cancelada', 'viagem', 69, '16/10 15:00 · Maputo → Manica', '2026-10-16 09:19:37', '::1'),
(553, 17, 'Rota criada', 'rota', 12, 'Maputo → Gaza', '2026-10-16 09:20:47', '::1'),
(554, 17, 'Viagem cancelada', 'viagem', 12, '19/10 17:00 · Maputo → Manica', '2026-10-16 09:22:41', '::1'),
(555, 17, 'Viagem cancelada', 'viagem', 132, '17/10 10:00 · Maputo → Sofala', '2026-10-16 09:22:45', '::1'),
(556, 17, 'Viagem cancelada', 'viagem', 70, '17/10 15:00 · Maputo → Manica', '2026-10-16 09:22:51', '::1');
INSERT INTO `log_transaccao` (`id_log`, `id_utilizador`, `accao`, `entidade`, `id_entidade`, `detalhes`, `data_hora`, `ip`) VALUES
(557, 17, 'Viagem cancelada', 'viagem', 10, '17/10 17:00 · Maputo → Manica', '2026-10-16 09:22:54', '::1'),
(558, 17, 'Viagem cancelada', 'viagem', 72, '19/10 15:00 · Maputo → Manica', '2026-10-16 09:23:06', '::1'),
(559, 17, 'Viagem cancelada', 'viagem', 11, '18/10 17:00 · Maputo → Manica', '2026-10-16 09:23:13', '::1'),
(560, 17, 'Viagem cancelada', 'viagem', 71, '18/10 15:00 · Maputo → Manica', '2026-10-16 09:23:18', '::1'),
(561, 17, 'Viagem cancelada', 'viagem', 133, '18/10 10:00 · Maputo → Sofala', '2026-10-16 09:23:28', '::1'),
(562, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 09:23:59', '::1'),
(563, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 09:24:08', '::1'),
(564, NULL, 'Utilizador criado', 'utilizador', 19, 'Nelson Francisco (gestor)', '2026-10-16 09:25:12', '::1'),
(565, NULL, 'Logout realizado', 'utilizador', 18, NULL, '2026-10-16 09:25:21', '::1'),
(566, NULL, 'Login realizado', 'utilizador', 19, NULL, '2026-10-16 09:25:41', '::1'),
(567, NULL, 'Palavra-passe alterada', 'utilizador', 19, NULL, '2026-10-16 09:26:06', '::1'),
(568, NULL, 'Motorista adicionado', 'motorista', 7, 'MT-001 · Djeld', '2026-10-16 09:28:05', '::1'),
(569, NULL, 'Cobrador criado', 'utilizador', 20, 'Galawas → CityLink', '2026-10-16 09:28:42', '::1'),
(570, NULL, 'Autocarro adicionado', 'autocarro', 6, 'CI-001 · AIW-784-MP', '2026-10-16 09:30:29', '::1'),
(571, NULL, 'Rota criada', 'rota', 13, 'Maputo → Gaza', '2026-10-16 09:31:05', '::1'),
(572, NULL, 'Logout realizado', 'utilizador', 19, NULL, '2026-10-16 09:33:57', '::1'),
(573, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 09:34:08', '::1'),
(574, NULL, 'Utilizador criado', 'utilizador', 21, 'Mohamad Abdula (bilheteiro)', '2026-10-16 09:34:56', '::1'),
(575, NULL, 'Logout realizado', 'utilizador', 18, NULL, '2026-10-16 09:37:27', '::1'),
(576, NULL, 'Login realizado', 'utilizador', 21, NULL, '2026-10-16 09:37:57', '::1'),
(577, NULL, 'Logout realizado', 'utilizador', 21, NULL, '2026-10-16 09:38:53', '::1'),
(578, NULL, 'Login realizado', 'utilizador', 21, NULL, '2026-10-16 09:39:08', '::1'),
(579, NULL, 'Venda presencial registada', 'reserva', 47, 'MT-2026-000047, MT-2026-000048', '2026-10-16 09:40:08', '::1'),
(580, NULL, 'Logout realizado', 'utilizador', 21, NULL, '2026-10-16 09:44:35', '::1'),
(581, NULL, 'Login realizado', 'utilizador', 19, NULL, '2026-10-16 09:52:16', '::1'),
(582, NULL, 'Viagens criadas', 'rota', 13, '1 viagem(ns) Maputo → Gaza às 11:00 a partir de 2026-10-16', '2026-10-16 09:53:45', '::1'),
(583, NULL, 'Logout realizado', 'utilizador', 19, NULL, '2026-10-16 09:53:52', '::1'),
(584, NULL, 'Recuperação de palavra-passe solicitada', 'utilizador', 21, NULL, '2026-10-16 09:55:16', '::1'),
(585, NULL, 'Palavra-passe redefinida', 'utilizador', 21, NULL, '2026-10-16 09:55:41', '::1'),
(586, NULL, 'Login realizado', 'utilizador', 19, NULL, '2026-10-16 09:55:50', '::1'),
(587, NULL, 'Logout realizado', 'utilizador', 19, NULL, '2026-10-16 09:56:02', '::1'),
(588, NULL, 'Login realizado', 'utilizador', 21, NULL, '2026-10-16 09:56:18', '::1'),
(589, NULL, 'Venda presencial registada', 'reserva', 49, 'MT-2026-000049', '2026-10-16 09:57:14', '::1'),
(590, NULL, 'Logout realizado', 'utilizador', 21, NULL, '2026-10-16 09:58:03', '::1'),
(591, NULL, 'Recuperação de palavra-passe solicitada', 'utilizador', 20, NULL, '2026-10-16 09:58:56', '::1'),
(592, NULL, 'Palavra-passe redefinida', 'utilizador', 20, NULL, '2026-10-16 09:59:46', '::1'),
(593, NULL, 'Login realizado', 'utilizador', 20, NULL, '2026-10-16 10:00:12', '::1'),
(594, NULL, 'Embarque confirmado', 'reserva', 49, 'MT-2026-000049-831010F750377515', '2026-10-16 10:01:37', '::1'),
(595, NULL, 'Logout realizado', 'utilizador', 20, NULL, '2026-10-16 10:03:22', '::1'),
(596, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-16 10:03:46', '::1'),
(597, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 50, NULL, '2026-10-16 10:04:33', '::1'),
(598, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 51, NULL, '2026-10-16 10:04:33', '::1'),
(599, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 52, NULL, '2026-10-16 10:04:33', '::1'),
(600, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 53, NULL, '2026-10-16 10:04:33', '::1'),
(601, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 54, NULL, '2026-10-16 10:04:33', '::1'),
(602, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 55, NULL, '2026-10-16 10:04:33', '::1'),
(603, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 56, NULL, '2026-10-16 10:04:33', '::1'),
(604, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 57, NULL, '2026-10-16 10:04:33', '::1'),
(605, 1, 'Pagamento confirmado', 'reserva', 50, 'MT-2026-000050', '2026-10-16 10:05:16', '::1'),
(606, 1, 'Pagamento confirmado', 'reserva', 51, 'MT-2026-000051', '2026-10-16 10:05:16', '::1'),
(607, 1, 'Pagamento confirmado', 'reserva', 52, 'MT-2026-000052', '2026-10-16 10:05:16', '::1'),
(608, 1, 'Pagamento confirmado', 'reserva', 53, 'MT-2026-000053', '2026-10-16 10:05:16', '::1'),
(609, 1, 'Pagamento confirmado', 'reserva', 54, 'MT-2026-000054', '2026-10-16 10:05:16', '::1'),
(610, 1, 'Pagamento confirmado', 'reserva', 55, 'MT-2026-000055', '2026-10-16 10:05:16', '::1'),
(611, 1, 'Pagamento confirmado', 'reserva', 56, 'MT-2026-000056', '2026-10-16 10:05:16', '::1'),
(612, 1, 'Pagamento confirmado', 'reserva', 57, 'MT-2026-000057', '2026-10-16 10:05:16', '::1'),
(613, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-16 10:06:24', '::1'),
(614, NULL, 'Login realizado', 'utilizador', 20, NULL, '2026-10-16 10:06:42', '::1'),
(615, NULL, 'Logout realizado', 'utilizador', 20, NULL, '2026-10-16 10:32:22', '::1'),
(616, NULL, 'Login realizado', 'utilizador', 20, NULL, '2026-10-16 10:32:30', '::1'),
(617, NULL, 'Logout realizado', 'utilizador', 20, NULL, '2026-10-16 10:32:34', '::1'),
(618, NULL, 'Login realizado', 'utilizador', 16, NULL, '2026-10-16 10:32:48', '::1'),
(619, NULL, 'Permissão alterada', 'permissao', 2, 'Gestor: Vender bilhetes = sim', '2026-10-16 10:36:51', '::1'),
(620, NULL, 'Logout realizado', 'utilizador', 16, NULL, '2026-10-16 10:37:09', '::1'),
(621, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-16 10:37:27', '::1'),
(622, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-16 10:38:37', '::1'),
(623, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 10:39:29', '::1'),
(624, NULL, 'Permissão alterada', 'permissao', 2, 'Gestor: Vender bilhetes = não', '2026-10-16 10:39:35', '::1'),
(625, NULL, 'Logout realizado', 'utilizador', 18, NULL, '2026-10-16 11:53:22', '::1'),
(626, NULL, 'Login realizado', 'utilizador', 18, NULL, '2026-10-16 19:40:38', '::1'),
(627, NULL, 'Configuração de bloqueio alterada', 'sistema', NULL, '3 tentativas, 20 min', '2026-10-16 19:42:13', '::1'),
(628, NULL, 'Configuração de bloqueio alterada', 'sistema', NULL, '3 tentativas, 60 min', '2026-10-16 19:42:20', '::1'),
(629, NULL, 'Login realizado', 'utilizador', 20, NULL, '2026-10-26 08:16:13', '::1'),
(630, NULL, 'Logout realizado', 'utilizador', 20, NULL, '2026-10-26 08:16:16', '::1'),
(631, NULL, 'Conta bloqueada por tentativas falhadas', 'utilizador', 16, NULL, '2026-10-26 08:16:29', '::1'),
(632, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-26 08:26:42', '::1'),
(633, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-26 08:26:47', '::1'),
(634, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-26 08:27:04', '::1'),
(635, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-26 08:27:08', '::1'),
(636, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-10-26 08:41:03', '::1'),
(637, 23, 'Configuração de bloqueio alterada', 'sistema', NULL, '10 tentativas, 60 min', '2026-10-26 08:41:44', '::1'),
(638, 23, 'Utilizador desbloqueado', 'utilizador', 16, 'Nelson Nhavene', '2026-10-26 08:42:09', '::1'),
(639, 23, 'Palavra-passe reposta', 'utilizador', 16, 'Nelson Nhavene', '2026-10-26 08:42:18', '::1'),
(640, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-10-26 08:42:38', '::1'),
(641, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-10-26 08:43:29', '::1'),
(642, 23, 'Palavra-passe reposta', 'utilizador', 18, 'Mikael Chongo', '2026-10-26 08:44:03', '::1'),
(643, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-10-26 08:44:16', '::1'),
(644, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-10-26 08:44:48', '::1'),
(645, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-10-26 08:45:10', '::1'),
(646, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-26 08:45:22', '::1'),
(647, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-26 08:46:55', '::1'),
(648, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-10-26 08:47:03', '::1'),
(649, 23, 'Utilizador criado', 'utilizador', 24, 'Mikael jose (gestor)', '2026-10-26 08:49:21', '::1'),
(650, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-10-26 08:49:33', '::1'),
(651, NULL, 'Login realizado', 'utilizador', 24, NULL, '2026-10-26 08:49:53', '::1'),
(652, NULL, 'Logout realizado', 'utilizador', 24, NULL, '2026-10-26 09:21:48', '::1'),
(653, NULL, 'Login realizado', 'utilizador', 24, NULL, '2026-10-26 09:22:05', '::1'),
(654, NULL, 'Motorista adicionado', 'motorista', 8, 'MT-001 · Galawas', '2026-10-26 09:24:41', '::1'),
(655, NULL, 'Cobrador criado', 'utilizador', 25, 'Rafique → Capital Way', '2026-10-26 09:25:23', '::1'),
(656, NULL, 'Autocarro adicionado', 'autocarro', 7, 'CA-001 · AIW-784-MC', '2026-10-26 09:27:21', '::1'),
(657, NULL, 'Rota criada', 'rota', 14, 'Maputo → Gaza', '2026-10-26 09:28:01', '::1'),
(658, NULL, 'Rota criada', 'rota', 15, 'Maputo → NIASSA', '2026-10-26 09:30:10', '::1'),
(659, NULL, 'Viagens criadas', 'rota', 15, '1 viagem(ns) Maputo → NIASSA às 10:00 a partir de 2026-10-26', '2026-10-26 09:30:39', '::1'),
(660, NULL, 'Logout realizado', 'utilizador', 24, NULL, '2026-10-26 09:30:53', '::1'),
(661, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-26 09:31:22', '::1'),
(662, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 58, NULL, '2026-10-26 09:32:21', '::1'),
(663, 1, 'Pagamento confirmado', 'reserva', 58, 'MT-2026-000058', '2026-10-26 09:32:33', '::1'),
(664, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-10-26 09:33:22', '::1'),
(665, NULL, 'Login realizado', 'utilizador', 25, NULL, '2026-10-26 09:33:43', '::1'),
(666, NULL, 'Embarque confirmado', 'reserva', 58, 'MT-2026-000058-5D6C5DD39B7A6D4D', '2026-10-26 09:34:28', '::1'),
(667, NULL, 'Logout realizado', 'utilizador', 25, NULL, '2026-10-26 09:34:53', '::1'),
(668, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-10-26 09:35:35', '::1'),
(669, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-10-26 09:35:48', '::1'),
(670, NULL, 'Login realizado', 'utilizador', 7, NULL, '2026-10-26 09:35:59', '::1'),
(671, NULL, 'Logout realizado', 'utilizador', 7, NULL, '2026-10-26 09:39:00', '::1'),
(672, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-10-27 09:27:47', '::1'),
(673, NULL, 'Conta de passageiro criada', 'utilizador', 26, NULL, '2026-10-27 09:30:07', '::1'),
(674, NULL, 'Login realizado', 'utilizador', 26, NULL, '2026-10-27 09:30:34', '::1'),
(675, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 59, NULL, '2026-10-27 09:32:15', '::1'),
(676, NULL, 'Lugar reservado (a aguardar pagamento)', 'reserva', 60, NULL, '2026-10-27 09:32:38', '::1'),
(677, NULL, 'Lugar reservado (a aguardar pagamento)', 'reserva', 61, NULL, '2026-10-27 09:32:38', '::1'),
(678, NULL, 'Login realizado', 'utilizador', 25, NULL, '2026-11-03 16:00:32', '::1'),
(679, NULL, 'Logout realizado', 'utilizador', 25, NULL, '2026-11-03 16:00:39', '::1'),
(680, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-11-03 16:06:30', '::1'),
(681, 23, 'Utilizador criado', 'utilizador', 27, 'Nelson Nhavene (administrador)', '2026-11-03 16:10:59', '::1'),
(682, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-11-03 16:11:16', '::1'),
(683, 27, 'Login realizado', 'utilizador', 27, NULL, '2026-11-03 16:11:52', '::1'),
(684, 27, 'Logout realizado', 'utilizador', 27, NULL, '2026-11-03 16:14:39', '::1'),
(685, 23, 'Login realizado', 'utilizador', 23, NULL, '2026-11-03 16:15:02', '::1'),
(686, 23, 'Palavra-passe reposta', 'utilizador', 27, 'Nelson Nhavene', '2026-11-03 16:27:33', '::1'),
(687, 23, 'Logout realizado', 'utilizador', 23, NULL, '2026-11-03 16:27:42', '::1'),
(688, 27, 'Login realizado', 'utilizador', 27, NULL, '2026-11-03 16:27:50', '::1'),
(689, 27, 'Palavra-passe alterada', 'utilizador', 27, NULL, '2026-11-03 16:28:13', '::1'),
(690, 27, 'Logout realizado', 'utilizador', 27, NULL, '2026-11-03 16:29:06', '::1'),
(691, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-11-03 16:29:12', '::1'),
(692, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-11-03 16:29:30', '::1'),
(693, 27, 'Login realizado', 'utilizador', 27, NULL, '2026-11-04 07:03:51', '::1'),
(694, 27, 'Logout realizado', 'utilizador', 27, NULL, '2026-11-04 07:04:09', '::1'),
(695, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-11-04 07:04:16', '::1'),
(696, 17, 'Viagens criadas', 'rota', 1, '1 viagem(ns) Maputo → Inhambane às 09:00 a partir de 2026-11-05', '2026-11-04 07:06:11', '::1'),
(697, 17, 'Cobrador criado', 'utilizador', 28, 'Bradly Alexandre → Lalgy', '2026-11-04 07:06:50', '::1'),
(698, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-11-04 07:07:03', '::1'),
(699, 28, 'Login realizado', 'utilizador', 28, NULL, '2026-11-04 07:07:20', '::1'),
(700, 28, 'Palavra-passe alterada', 'utilizador', 28, NULL, '2026-11-04 07:07:48', '::1'),
(701, 28, 'Logout realizado', 'utilizador', 28, NULL, '2026-11-04 07:08:30', '::1'),
(702, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-11-04 07:08:41', '::1'),
(703, 17, 'Viagem cancelada', 'viagem', 200, '05/11 09:00 · Maputo → Inhambane', '2026-11-04 07:09:11', '::1'),
(704, 17, 'Viagens criadas', 'rota', 1, '1 viagem(ns) Maputo → Inhambane às 10:00 a partir de 2026-11-05', '2026-11-04 07:10:17', '::1'),
(705, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-11-04 07:10:27', '::1'),
(706, 28, 'Login realizado', 'utilizador', 28, NULL, '2026-11-04 07:10:37', '::1'),
(707, 28, 'Logout realizado', 'utilizador', 28, NULL, '2026-11-04 07:11:03', '::1'),
(708, 27, 'Login realizado', 'utilizador', 27, NULL, '2026-11-04 07:11:12', '::1'),
(709, 27, 'Logout realizado', 'utilizador', 27, NULL, '2026-11-04 07:11:17', '::1'),
(710, 17, 'Login realizado', 'utilizador', 17, NULL, '2026-11-04 07:11:24', '::1'),
(711, 17, 'Viagem cancelada', 'viagem', 201, '05/11 10:00 · Maputo → Inhambane', '2026-11-04 07:11:40', '::1'),
(712, 17, 'Viagens criadas', 'rota', 1, '1 viagem(ns) Maputo → Inhambane às 10:00 a partir de 2026-11-04', '2026-11-04 07:12:12', '::1'),
(713, 17, 'Logout realizado', 'utilizador', 17, NULL, '2026-11-04 07:12:15', '::1'),
(714, 28, 'Login realizado', 'utilizador', 28, NULL, '2026-11-04 07:12:45', '::1'),
(715, 28, 'Logout realizado', 'utilizador', 28, NULL, '2026-11-04 07:13:16', '::1'),
(716, 1, 'Login realizado', 'utilizador', 1, NULL, '2026-11-04 07:39:28', '::1'),
(717, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 62, NULL, '2026-11-04 07:40:14', '::1'),
(718, 1, 'Lugar reservado (a aguardar pagamento)', 'reserva', 63, NULL, '2026-11-04 07:40:14', '::1'),
(719, 1, 'Pagamento confirmado', 'reserva', 62, 'MT-2026-000062', '2026-11-04 07:40:18', '::1'),
(720, 1, 'Pagamento confirmado', 'reserva', 63, 'MT-2026-000063', '2026-11-04 07:40:18', '::1'),
(721, 1, 'Logout realizado', 'utilizador', 1, NULL, '2026-11-04 07:54:00', '::1'),
(722, 27, 'Login realizado', 'utilizador', 27, NULL, '2026-11-04 07:54:15', '::1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `motorista`
--

CREATE TABLE `motorista` (
  `id_motorista` int(10) UNSIGNED NOT NULL,
  `id_transportadora` int(10) UNSIGNED NOT NULL,
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
  `numero_carta` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `motorista`
--

INSERT INTO `motorista` (`id_motorista`, `id_transportadora`, `codigo`, `nome`, `documento`, `data_nascimento`, `telefone`, `email`, `morada`, `carta_numero`, `carta_categoria`, `carta_emissao`, `carta_validade`, `carta_ficheiro`, `estado`, `data_criacao`, `id_utilizador`, `numero_carta`) VALUES
(1, 3, 'MT-001', 'Nelson Francisco Nhavene', '634636436463P', '2004-03-01', '+258 87 350 5758', 'nelson@gmail.com', 'Alto mae, Maputo', '21321241E24512S', 'D · Transporte de passageiros', '2024-11-11', '2029-11-11', 'cartas/2762574ab51497980d7fd481.png', 'disponivel', '2026-10-10 19:30:38', NULL, NULL),
(2, 1, 'MT-001', 'Djeld Nhacengo', '54455254725385N', '1995-07-26', '+258 87 323 2657', 'djeld@gmail.com', 'Zimpeto, Maputo', '535625FEW3', 'D · Transporte de passageiros', '2025-10-11', '2031-10-11', 'cartas/5e4cfafbb8abd1095d28cce2.png', 'disponivel', '2026-10-10 21:11:44', NULL, NULL),
(3, 1, 'MT-002', 'Nilton Simbine', '544552543385S', '2002-09-07', '+258 86 553 3733', 'nilton@gmail.com', 'Xai-xai', 'F5R56R356R2G', 'D · Transporte de passageiros', '2010-10-11', '2026-10-12', NULL, 'disponivel', '2026-10-10 21:15:01', NULL, NULL),
(4, 3, 'MT-002', 'Alberto Cumbane', '463643654463A', '2000-08-21', '+258 87 450 6255', 'alberto@gmail.com', 'Inhambane', '21321241WEW', 'D · Transporte de passageiros', '2023-02-20', '2028-02-20', NULL, 'disponivel', '2026-10-11 10:44:23', NULL, NULL),
(5, 3, 'MT-003', 'Pedro Tembe', '6346364363P', '1998-11-21', '+258 87 450 6253', 'pedro@gmail.com', 'Manica', '21321241WEWZ', 'D · Transporte de passageiros', '2026-03-03', '2031-03-03', NULL, 'disponivel', '2026-10-11 11:33:38', NULL, NULL),
(6, 3, 'MT-004', 'Leva Gente', '63624642636D', '1975-12-06', '+258 87 450 9875', 'leva@gmail.com', 'Maputo', '2376237637D', 'D · Transporte de passageiros', '2025-10-06', '2028-10-06', 'cartas/a0785eb1acf8929d26b36a9a.jpg', 'disponivel', '2026-10-16 08:38:50', NULL, NULL),
(7, 2, 'MT-001', 'Djeld', '2366326D', '2002-05-05', '+258 87 450 9854', 'djeld@gmail.com', 'Maputo', '235652365U', 'D · Transporte de passageiros', '2022-10-08', '2027-10-08', 'cartas/aad192667ad1facfc7a6f3d6.jpg', 'disponivel', '2026-10-16 09:28:05', NULL, NULL),
(8, 4, 'MT-001', 'Galawas', '73257572573D', '2004-05-05', '+258 87 450 6259', 'galawa@gmail.com', 'Maputo', '6357257357D', 'D · Transporte de passageiros', '2023-05-02', '2028-06-20', 'cartas/ee043ce56a09b5fdba9e3698.png', 'disponivel', '2026-10-26 09:24:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `id_pagamento` int(10) UNSIGNED NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `metodo` enum('mpesa','emola','cartao','numerario') NOT NULL,
  `estado` enum('pendente','confirmado','falhado','reembolsado') NOT NULL DEFAULT 'pendente',
  `data_pagamento` datetime DEFAULT NULL,
  `referencia_transacao` varchar(60) DEFAULT NULL,
  `id_reserva` int(10) UNSIGNED NOT NULL,
  `telefone_pagamento` varchar(30) DEFAULT NULL,
  `motivo_falha` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pagamento`
--

INSERT INTO `pagamento` (`id_pagamento`, `valor`, `metodo`, `estado`, `data_pagamento`, `referencia_transacao`, `id_reserva`, `telefone_pagamento`, `motivo_falha`) VALUES
(1, 4000.00, 'numerario', 'confirmado', '2026-10-10 20:28:19', 'MT-2026-000003', 3, NULL, NULL),
(2, 4000.00, 'numerario', 'confirmado', '2026-10-10 20:28:19', 'MT-2026-000001', 1, NULL, NULL),
(3, 4000.00, 'numerario', 'confirmado', '2026-10-10 20:28:19', 'MT-2026-000002', 2, NULL, NULL),
(4, 4000.00, 'numerario', 'confirmado', '2026-10-10 20:28:19', 'MT-2026-000004', 4, NULL, NULL),
(5, 1500.00, 'mpesa', 'confirmado', '2026-10-10 20:31:36', 'MT-2026-000008', 8, '850604771', NULL),
(6, 1500.00, 'mpesa', 'confirmado', '2026-10-10 20:31:36', 'MT-2026-000005', 5, '850604771', NULL),
(7, 1500.00, 'mpesa', 'confirmado', '2026-10-10 20:31:36', 'MT-2026-000006', 6, '850604771', NULL),
(8, 1500.00, 'mpesa', 'confirmado', '2026-10-10 20:31:36', 'MT-2026-000007', 7, '850604771', NULL),
(9, 1500.00, 'cartao', 'confirmado', '2026-10-10 20:40:23', 'MT-2026-000011', 11, NULL, NULL),
(10, 1500.00, 'cartao', 'confirmado', '2026-10-10 20:40:23', 'MT-2026-000012', 12, NULL, NULL),
(11, 1500.00, 'emola', 'reembolsado', '2026-10-10 20:42:31', 'MT-2026-000013', 13, '874506259', NULL),
(12, 4000.00, 'cartao', 'confirmado', '2026-10-10 20:44:50', 'MT-2026-000014', 14, NULL, NULL),
(13, 5000.00, 'emola', 'confirmado', '2026-10-11 17:27:42', 'MT-2026-000015', 15, '873505758', NULL),
(14, 4000.00, 'cartao', 'confirmado', '2026-10-11 19:08:23', 'MT-2026-000017', 17, NULL, NULL),
(15, 5000.00, 'emola', 'confirmado', '2026-10-11 15:06:53', 'MT-2026-000018', 18, '874509870', NULL),
(16, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000023', 23, '874506253', NULL),
(17, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000024', 24, '874506253', NULL),
(18, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000025', 25, '874506253', NULL),
(19, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000026', 26, '874506253', NULL),
(20, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000027', 27, '874506253', NULL),
(21, 5000.00, 'emola', 'confirmado', '2026-10-11 15:21:31', 'MT-2026-000028', 28, '874506253', NULL),
(22, 1800.00, 'numerario', 'confirmado', '2026-10-11 15:41:13', 'MT-2026-000029', 29, NULL, NULL),
(23, 1800.00, 'numerario', 'confirmado', '2026-10-11 15:41:13', 'MT-2026-000030', 30, NULL, NULL),
(24, 1800.00, 'numerario', 'confirmado', '2026-10-11 15:41:13', 'MT-2026-000031', 31, NULL, NULL),
(25, 1800.00, 'cartao', 'confirmado', '2026-10-11 15:42:31', 'MT-2026-000032', 32, NULL, NULL),
(26, 1800.00, 'cartao', 'confirmado', '2026-10-11 16:08:05', 'MT-2026-000033', 33, NULL, NULL),
(27, 8000.00, 'mpesa', 'confirmado', '2026-10-12 19:43:51', 'MT-2026-000034', 34, '841234567', NULL),
(28, 8000.00, 'mpesa', 'confirmado', '2026-10-12 19:43:51', 'MT-2026-000035', 35, '841234567', NULL),
(29, 8000.00, 'mpesa', 'confirmado', '2026-10-12 19:54:35', 'MT-2026-000038', 38, '844506253', NULL),
(30, 8000.00, 'mpesa', 'confirmado', '2026-10-12 19:54:35', 'MT-2026-000037', 37, '844506253', NULL),
(31, 8000.00, 'mpesa', 'confirmado', '2026-10-12 19:54:35', 'MT-2026-000036', 36, '844506253', NULL),
(32, 8000.00, 'emola', 'confirmado', '2026-10-12 19:56:16', 'MT-2026-000039', 39, '873505758', NULL),
(33, 3800.00, 'mpesa', 'confirmado', '2026-10-14 16:49:15', 'MT-2026-000040', 40, '852980738', NULL),
(34, 2600.00, 'emola', 'confirmado', '2026-10-16 08:48:26', 'MT-2026-000042', 42, '874509874', NULL),
(35, 2600.00, 'emola', 'confirmado', '2026-10-16 08:48:26', 'MT-2026-000041', 41, '874509874', NULL),
(36, 3800.00, 'numerario', 'confirmado', '2026-10-16 08:53:16', 'MT-2026-000043', 43, NULL, NULL),
(37, 3800.00, 'numerario', 'confirmado', '2026-10-16 08:55:21', 'MT-2026-000044', 44, NULL, NULL),
(38, 3800.00, 'emola', 'confirmado', '2026-10-16 09:12:16', 'MT-2026-000045', 45, '874506255', NULL),
(39, 3800.00, 'emola', 'confirmado', '2026-10-16 09:12:16', 'MT-2026-000046', 46, '874506255', NULL),
(40, 1200.00, 'emola', 'confirmado', '2026-10-16 09:40:08', 'MT-2026-000047', 47, '871056908', NULL),
(41, 1200.00, 'emola', 'confirmado', '2026-10-16 09:40:08', 'MT-2026-000048', 48, '871056908', NULL),
(42, 1200.00, 'emola', 'confirmado', '2026-10-16 09:57:14', 'MT-2026-000049', 49, '874506252', NULL),
(43, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000050', 50, '848787878', NULL),
(44, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000051', 51, '848787878', NULL),
(45, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000052', 52, '848787878', NULL),
(46, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000053', 53, '848787878', NULL),
(47, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000054', 54, '848787878', NULL),
(48, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000055', 55, '848787878', NULL),
(49, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000056', 56, '848787878', NULL),
(50, 1200.00, 'mpesa', 'confirmado', '2026-10-16 10:05:16', 'MT-2026-000057', 57, '848787878', NULL),
(51, 1500.00, 'mpesa', 'confirmado', '2026-10-26 09:32:33', 'MT-2026-000058', 58, '849290009', NULL),
(52, 1500.00, 'cartao', 'confirmado', '2026-11-04 07:40:18', 'MT-2026-000062', 62, NULL, NULL),
(53, 1500.00, 'cartao', 'confirmado', '2026-11-04 07:40:18', 'MT-2026-000063', 63, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_permissao`
--

CREATE TABLE `perfil_permissao` (
  `perfil` enum('bilheteiro','cobrador','gestor','administrador') NOT NULL,
  `id_permissao` int(10) UNSIGNED NOT NULL,
  `concedida` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `perfil_permissao`
--

INSERT INTO `perfil_permissao` (`perfil`, `id_permissao`, `concedida`) VALUES
('bilheteiro', 1, 1),
('bilheteiro', 2, 1),
('bilheteiro', 3, 0),
('bilheteiro', 4, 0),
('bilheteiro', 5, 0),
('bilheteiro', 6, 0),
('bilheteiro', 7, 0),
('bilheteiro', 8, 0),
('bilheteiro', 9, 0),
('bilheteiro', 10, 0),
('bilheteiro', 11, 0),
('cobrador', 1, 1),
('cobrador', 2, 0),
('cobrador', 3, 0),
('cobrador', 4, 0),
('cobrador', 5, 0),
('cobrador', 6, 0),
('cobrador', 7, 0),
('cobrador', 8, 0),
('cobrador', 9, 0),
('cobrador', 10, 0),
('cobrador', 11, 0),
('gestor', 1, 1),
('gestor', 2, 0),
('gestor', 3, 1),
('gestor', 4, 1),
('gestor', 5, 0),
('gestor', 6, 0),
('gestor', 7, 0),
('gestor', 8, 0),
('gestor', 9, 0),
('gestor', 10, 0),
('gestor', 11, 1),
('administrador', 1, 1),
('administrador', 2, 1),
('administrador', 3, 1),
('administrador', 4, 1),
('administrador', 5, 1),
('administrador', 6, 1),
('administrador', 7, 1),
('administrador', 8, 1),
('administrador', 9, 1),
('administrador', 10, 1),
('administrador', 11, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

CREATE TABLE `permissao` (
  `id_permissao` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(40) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `alerta_seguranca` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `permissao`
--

INSERT INTO `permissao` (`id_permissao`, `codigo`, `descricao`, `alerta_seguranca`) VALUES
(1, 'ver_dashboard', 'Ver dashboard', 0),
(2, 'vender_bilhetes', 'Vender bilhetes', 0),
(3, 'gerir_rotas', 'Gerir rotas', 0),
(4, 'ver_relatorios', 'Ver relatórios', 0),
(5, 'gerir_utilizadores', 'Gerir utilizadores', 0),
(6, 'ver_dados_todas', 'Ver dados de todas as transportadoras', 1),
(7, 'gerir_permissoes', 'Gerir permissões', 0),
(8, 'gerir_transportadoras', 'Gerir transportadoras', 0),
(9, 'ver_auditoria', 'Ver auditoria', 0),
(10, 'gerir_seguranca', 'Gerir segurança', 0),
(11, 'gerir_frota', 'Gerir autocarros e motoristas', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `recuperacao_senha`
--

CREATE TABLE `recuperacao_senha` (
  `id_recuperacao` int(10) UNSIGNED NOT NULL,
  `id_utilizador` int(10) UNSIGNED NOT NULL,
  `codigo_hash` varchar(255) NOT NULL,
  `data_expiracao` datetime NOT NULL,
  `tentativas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(10) UNSIGNED NOT NULL,
  `data_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `data_expiracao` datetime DEFAULT NULL,
  `estado` enum('pendente','confirmada','cancelada','expirada') NOT NULL DEFAULT 'pendente',
  `id_passageiro` int(10) UNSIGNED DEFAULT NULL,
  `nome_passageiro` varchar(100) DEFAULT NULL,
  `telefone_passageiro` varchar(30) DEFAULT NULL,
  `id_viagem` int(10) UNSIGNED NOT NULL,
  `id_assento` int(10) UNSIGNED NOT NULL,
  `id_bilheteiro` int(10) UNSIGNED DEFAULT NULL,
  `assento_ocupado` tinyint(4) GENERATED ALWAYS AS (if(`estado` in ('pendente','confirmada'),1,NULL)) STORED,
  `referencia` varchar(20) DEFAULT NULL,
  `data_cancelamento` datetime DEFAULT NULL,
  `tipo_documento` varchar(30) DEFAULT NULL,
  `documento_passageiro` varchar(30) DEFAULT NULL,
  `grupo_venda` varchar(24) DEFAULT NULL,
  `embarcado_em` datetime DEFAULT NULL,
  `embarcado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `data_reserva`, `data_expiracao`, `estado`, `id_passageiro`, `nome_passageiro`, `telefone_passageiro`, `id_viagem`, `id_assento`, `id_bilheteiro`, `referencia`, `data_cancelamento`, `tipo_documento`, `documento_passageiro`, `grupo_venda`, `embarcado_em`, `embarcado_por`) VALUES
(1, '2026-10-10 15:27:23', NULL, 'confirmada', NULL, 'Nelson Nhavene', '+258 87 350 5758', 1, 8, NULL, 'MT-2026-000001', NULL, 'BI', '634636436463P', 'V261011012723E06D', NULL, NULL),
(2, '2026-10-10 15:27:23', NULL, 'confirmada', NULL, 'Nelson Nhavene', '+258 87 350 5758', 1, 11, NULL, 'MT-2026-000002', NULL, 'BI', '634636436463P', 'V261011012723E06D', NULL, NULL),
(3, '2026-10-10 15:27:23', NULL, 'confirmada', NULL, 'Nelson Nhavene', '+258 87 350 5758', 1, 7, NULL, 'MT-2026-000003', NULL, 'BI', '634636436463P', 'V261011012723E06D', NULL, NULL),
(4, '2026-10-10 15:27:23', NULL, 'confirmada', NULL, 'Nelson Nhavene', '+258 87 350 5758', 1, 12, NULL, 'MT-2026-000004', NULL, 'BI', '634636436463P', 'V261011012723E06D', NULL, NULL),
(5, '2026-10-10 15:31:20', NULL, 'confirmada', NULL, 'Nelson F Nhavene', '+258 85 060 4771', 2, 9, NULL, 'MT-2026-000005', NULL, 'BI', '634636436463P', 'V2610110131209103', NULL, NULL),
(6, '2026-10-10 15:31:20', NULL, 'confirmada', NULL, 'Nelson F Nhavene', '+258 85 060 4771', 2, 14, NULL, 'MT-2026-000006', NULL, 'BI', '634636436463P', 'V2610110131209103', NULL, NULL),
(7, '2026-10-10 15:31:20', NULL, 'confirmada', NULL, 'Nelson F Nhavene', '+258 85 060 4771', 2, 20, NULL, 'MT-2026-000007', NULL, 'BI', '634636436463P', 'V2610110131209103', NULL, NULL),
(8, '2026-10-10 15:31:20', NULL, 'confirmada', NULL, 'Nelson F Nhavene', '+258 85 060 4771', 2, 7, NULL, 'MT-2026-000008', NULL, 'BI', '634636436463P', 'V2610110131209103', NULL, NULL),
(9, '2026-10-10 15:34:23', '2026-10-11 01:44:23', 'cancelada', NULL, NULL, NULL, 3, 4, NULL, 'MT-2026-000009', '2026-10-11 01:36:35', NULL, NULL, 'V26101101342324DF', NULL, NULL),
(10, '2026-10-10 15:36:35', '2026-10-11 01:46:35', 'cancelada', NULL, NULL, NULL, 2, 15, NULL, 'MT-2026-000010', '2026-10-11 01:38:08', NULL, NULL, 'V2610110136353DD7', NULL, NULL),
(11, '2026-10-10 15:38:08', NULL, 'confirmada', NULL, 'zoca', '+258 87 450 6253', 2, 15, NULL, 'MT-2026-000011', NULL, 'BI', '634636436463P', 'V261011013808B7BB', NULL, NULL),
(12, '2026-10-10 15:38:08', NULL, 'confirmada', NULL, 'zoca', '+258 87 450 6253', 2, 16, NULL, 'MT-2026-000012', NULL, 'BI', '634636436463P', 'V261011013808B7BB', NULL, NULL),
(13, '2026-10-10 15:41:56', NULL, 'cancelada', 1, NULL, NULL, 3, 10, NULL, 'MT-2026-000013', '2026-10-11 01:46:09', NULL, NULL, NULL, NULL, NULL),
(14, '2026-10-10 15:44:05', NULL, 'confirmada', 1, NULL, NULL, 1, 1, NULL, 'MT-2026-000014', NULL, NULL, NULL, NULL, NULL, NULL),
(15, '2026-10-11 07:27:22', NULL, 'confirmada', 1, NULL, NULL, 130, 181, NULL, 'MT-2026-000015', NULL, NULL, NULL, NULL, '2026-10-11 12:48:09', 12),
(16, '2026-10-11 14:07:36', '2026-10-11 19:17:36', 'cancelada', NULL, NULL, NULL, 1, 5, NULL, 'MT-2026-000016', '2026-10-11 19:07:57', NULL, NULL, NULL, NULL, NULL),
(17, '2026-10-11 14:07:57', NULL, 'confirmada', NULL, NULL, NULL, 1, 14, NULL, 'MT-2026-000017', NULL, NULL, NULL, NULL, NULL, NULL),
(18, '2026-10-11 15:05:58', NULL, 'confirmada', 1, NULL, NULL, 130, 182, NULL, 'MT-2026-000018', NULL, NULL, NULL, NULL, NULL, NULL),
(19, '2026-10-11 15:21:02', '2026-10-11 15:31:02', 'cancelada', 1, NULL, NULL, 130, 202, NULL, 'MT-2026-000019', '2026-10-11 15:21:13', NULL, NULL, NULL, NULL, NULL),
(20, '2026-10-11 15:21:02', '2026-10-11 15:31:02', 'cancelada', 1, NULL, NULL, 130, 198, NULL, 'MT-2026-000020', '2026-10-11 15:21:13', NULL, NULL, NULL, NULL, NULL),
(21, '2026-10-11 15:21:02', '2026-10-11 15:31:02', 'cancelada', 1, NULL, NULL, 130, 197, NULL, 'MT-2026-000021', '2026-10-11 15:21:13', NULL, NULL, NULL, NULL, NULL),
(22, '2026-10-11 15:21:02', '2026-10-11 15:31:02', 'cancelada', 1, NULL, NULL, 130, 201, NULL, 'MT-2026-000022', '2026-10-11 15:21:13', NULL, NULL, NULL, NULL, NULL),
(23, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 194, NULL, 'MT-2026-000023', NULL, NULL, NULL, NULL, NULL, NULL),
(24, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 190, NULL, 'MT-2026-000024', NULL, NULL, NULL, NULL, NULL, NULL),
(25, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 189, NULL, 'MT-2026-000025', NULL, NULL, NULL, NULL, NULL, NULL),
(26, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 193, NULL, 'MT-2026-000026', NULL, NULL, NULL, NULL, NULL, NULL),
(27, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 196, NULL, 'MT-2026-000027', NULL, NULL, NULL, NULL, NULL, NULL),
(28, '2026-10-11 15:21:13', NULL, 'confirmada', 1, NULL, NULL, 130, 195, NULL, 'MT-2026-000028', NULL, NULL, NULL, NULL, NULL, NULL),
(29, '2026-10-11 15:40:34', NULL, 'confirmada', NULL, 'Genoveva', '+258 87 450 6259', 131, 65, NULL, 'MT-2026-000029', NULL, 'BI', '0346364363P', 'V261011154034E817', NULL, NULL),
(30, '2026-10-11 15:40:34', NULL, 'confirmada', NULL, 'Genoveva', '+258 87 450 6259', 131, 70, NULL, 'MT-2026-000030', NULL, 'BI', '0346364363P', 'V261011154034E817', NULL, NULL),
(31, '2026-10-11 15:40:34', NULL, 'confirmada', NULL, 'Genoveva', '+258 87 450 6259', 131, 74, NULL, 'MT-2026-000031', NULL, 'BI', '0346364363P', 'V261011154034E817', NULL, NULL),
(32, '2026-10-11 15:42:29', NULL, 'confirmada', 1, NULL, NULL, 131, 73, NULL, 'MT-2026-000032', NULL, NULL, NULL, NULL, NULL, NULL),
(33, '2026-10-11 16:08:01', NULL, 'confirmada', 1, NULL, NULL, 131, 78, NULL, 'MT-2026-000033', NULL, NULL, NULL, NULL, NULL, NULL),
(34, '2026-10-12 19:42:15', NULL, 'confirmada', 1, NULL, NULL, 4, 14, NULL, 'MT-2026-000034', NULL, NULL, NULL, NULL, NULL, NULL),
(35, '2026-10-12 19:42:15', NULL, 'confirmada', 1, NULL, NULL, 4, 13, NULL, 'MT-2026-000035', NULL, NULL, NULL, NULL, '2026-10-12 20:02:58', 11),
(36, '2026-10-12 19:53:37', NULL, 'confirmada', NULL, 'MK Chongo', '+258 84 450 6253', 4, 1, NULL, 'MT-2026-000036', NULL, 'BI', '634636463P', 'V2610121953375541', NULL, NULL),
(37, '2026-10-12 19:53:37', NULL, 'confirmada', NULL, 'MK Chongo', '+258 84 450 6253', 4, 60, NULL, 'MT-2026-000037', NULL, 'BI', '634636463P', 'V2610121953375541', NULL, NULL),
(38, '2026-10-12 19:53:37', NULL, 'confirmada', NULL, 'MK Chongo', '+258 84 450 6253', 4, 59, NULL, 'MT-2026-000038', NULL, 'BI', '634636463P', 'V2610121953375541', NULL, NULL),
(39, '2026-10-12 19:55:17', NULL, 'confirmada', NULL, 'Nelson Nhavene', '+258 87 350 5758', 4, 27, NULL, 'MT-2026-000039', NULL, 'BI', '6346364363P', 'V2610121955175CFA', '2026-10-12 20:01:23', 11),
(40, '2026-10-14 16:48:56', NULL, 'confirmada', NULL, NULL, NULL, 68, 2, NULL, 'MT-2026-000040', NULL, NULL, NULL, NULL, NULL, NULL),
(41, '2026-10-16 08:46:47', NULL, 'confirmada', NULL, 'Maurina', '+258 87 450 9874', 134, 241, NULL, 'MT-2026-000041', NULL, 'BI', '512451462461D', 'V26101608464748B2', NULL, NULL),
(42, '2026-10-16 08:46:47', NULL, 'confirmada', NULL, 'Maurina', '+258 87 450 9874', 134, 288, NULL, 'MT-2026-000042', NULL, 'BI', '512451462461D', 'V26101608464748B2', NULL, NULL),
(43, '2026-10-16 08:53:01', NULL, 'confirmada', NULL, 'nnnn', '+258 87 450 6253', 9, 3, NULL, 'MT-2026-000043', NULL, 'BI', '6346364363P', 'V261016085301712F', NULL, NULL),
(44, '2026-10-16 08:55:08', NULL, 'confirmada', NULL, 'nnnnn', '+258 87 450 6253', 9, 4, NULL, 'MT-2026-000044', NULL, 'BI', '634636463P', 'V261016085508B021', NULL, NULL),
(45, '2026-10-16 09:11:41', NULL, 'confirmada', NULL, 'xivambo', '+258 87 450 6255', 73, 1, NULL, 'MT-2026-000045', NULL, 'BI', '443677776G', 'V261016091141784E', NULL, NULL),
(46, '2026-10-16 09:11:41', NULL, 'confirmada', NULL, 'xivambo', '+258 87 450 6255', 73, 2, NULL, 'MT-2026-000046', NULL, 'BI', '443677776G', 'V261016091141784E', NULL, NULL),
(47, '2026-10-16 09:39:30', NULL, 'confirmada', NULL, 'Teresa', '+258 87 105 6908', 136, 289, NULL, 'MT-2026-000047', NULL, 'BI', '126461364D', 'V2610160939309D66', NULL, NULL),
(48, '2026-10-16 09:39:30', NULL, 'confirmada', NULL, 'Teresa', '+258 87 105 6908', 136, 290, NULL, 'MT-2026-000048', NULL, 'BI', '126461364D', 'V2610160939309D66', NULL, NULL),
(49, '2026-10-16 09:56:44', NULL, 'confirmada', NULL, 'Joel Nuvunga', '+258 87 450 6252', 138, 303, NULL, 'MT-2026-000049', NULL, 'BI', '1442556256D', 'V261016095644BE01', '2026-10-16 10:01:37', 20),
(50, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 307, NULL, 'MT-2026-000050', NULL, NULL, NULL, NULL, NULL, NULL),
(51, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 308, NULL, 'MT-2026-000051', NULL, NULL, NULL, NULL, NULL, NULL),
(52, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 311, NULL, 'MT-2026-000052', NULL, NULL, NULL, NULL, NULL, NULL),
(53, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 312, NULL, 'MT-2026-000053', NULL, NULL, NULL, NULL, NULL, NULL),
(54, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 316, NULL, 'MT-2026-000054', NULL, NULL, NULL, NULL, NULL, NULL),
(55, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 315, NULL, 'MT-2026-000055', NULL, NULL, NULL, NULL, NULL, NULL),
(56, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 319, NULL, 'MT-2026-000056', NULL, NULL, NULL, NULL, NULL, NULL),
(57, '2026-10-16 10:04:33', NULL, 'confirmada', 1, NULL, NULL, 138, 320, NULL, 'MT-2026-000057', NULL, NULL, NULL, NULL, NULL, NULL),
(58, '2026-10-26 09:32:21', NULL, 'confirmada', 1, NULL, NULL, 199, 352, NULL, 'MT-2026-000058', NULL, NULL, NULL, NULL, '2026-10-26 09:34:28', 25),
(59, '2026-10-27 09:32:15', '2026-10-27 09:42:15', 'expirada', 1, NULL, NULL, 140, 338, NULL, 'MT-2026-000059', NULL, NULL, NULL, NULL, NULL, NULL),
(60, '2026-10-27 09:32:38', '2026-10-27 09:42:38', 'expirada', NULL, NULL, NULL, 140, 337, NULL, 'MT-2026-000060', NULL, NULL, NULL, NULL, NULL, NULL),
(61, '2026-10-27 09:32:38', '2026-10-27 09:42:38', 'expirada', NULL, NULL, NULL, 140, 342, NULL, 'MT-2026-000061', NULL, NULL, NULL, NULL, NULL, NULL),
(62, '2026-11-04 07:40:14', NULL, 'confirmada', 1, NULL, NULL, 202, 126, NULL, 'MT-2026-000062', NULL, NULL, NULL, NULL, NULL, NULL),
(63, '2026-11-04 07:40:14', NULL, 'confirmada', 1, NULL, NULL, 202, 125, NULL, 'MT-2026-000063', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rota`
--

CREATE TABLE `rota` (
  `id_rota` int(10) UNSIGNED NOT NULL,
  `origem` varchar(80) NOT NULL,
  `destino` varchar(80) NOT NULL,
  `distancia_km` decimal(8,2) DEFAULT NULL,
  `duracao_estimada` time DEFAULT NULL,
  `id_transportadora` int(10) UNSIGNED NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `preco_base` decimal(10,2) DEFAULT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `rota`
--

INSERT INTO `rota` (`id_rota`, `origem`, `destino`, `distancia_km`, `duracao_estimada`, `id_transportadora`, `activa`, `preco_base`, `ativa`) VALUES
(1, 'Maputo', 'Inhambane', 1200.00, '50:00:00', 3, 1, 1500.00, 1),
(2, 'Maputo', 'Beirra', 2000.00, '70:00:00', 3, 1, 2500.00, 1),
(3, 'Nampula', 'Maputo', 3000.00, '28:00:00', 3, 1, 4000.00, 1),
(4, 'Maputo', 'Inhambane', 5000.00, '05:00:00', 1, 1, 1500.00, 1),
(5, 'Maputo', 'Gaza', 4000.00, '03:00:00', 1, 1, 1200.00, 1),
(6, 'Maputo', 'Beira', 6000.00, '10:00:00', 1, 1, 1800.00, 1),
(7, 'Maputo', 'Cabo Delgado', 3800.00, '24:00:00', 3, 1, 8000.00, 1),
(8, 'Maputo', 'Manica', 6800.00, '15:00:00', 3, 1, 3800.00, 1),
(9, 'Maputo', 'Zambézia', 7000.00, '18:00:00', 3, 1, 5000.00, 1),
(10, 'Cabo Delgado', 'Maputo', 7599.00, '26:00:00', 3, 1, 8000.00, 1),
(11, 'Maputo', 'Sofala', 900.00, '23:00:00', 3, 1, 2600.00, 1),
(12, 'Maputo', 'Gaza', 200.00, '06:00:00', 3, 1, 1200.00, 1),
(13, 'Maputo', 'Gaza', 100.00, '06:00:00', 2, 1, 1200.00, 1),
(14, 'Maputo', 'Gaza', 200.00, '06:00:00', 4, 1, 1500.00, 1),
(15, 'Maputo', 'NIASSA', 300.00, '08:00:00', 4, 1, 1500.00, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `suporte_mensagem`
--

CREATE TABLE `suporte_mensagem` (
  `id_mensagem` int(10) UNSIGNED NOT NULL,
  `id_pedido` int(10) UNSIGNED NOT NULL,
  `autor` enum('passageiro','administrador') NOT NULL,
  `id_utilizador` int(10) UNSIGNED DEFAULT NULL,
  `texto` text NOT NULL,
  `data_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `suporte_mensagem`
--

INSERT INTO `suporte_mensagem` (`id_mensagem`, `id_pedido`, `autor`, `id_utilizador`, `texto`, `data_hora`) VALUES
(1, 1, 'passageiro', 1, 'ola hjfs bsvdgsbg hhws hsh hsdhs hsd s', '2026-10-11 18:29:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `suporte_pedido`
--

CREATE TABLE `suporte_pedido` (
  `id_pedido` int(10) UNSIGNED NOT NULL,
  `id_passageiro` int(10) UNSIGNED DEFAULT NULL,
  `nome_contacto` varchar(100) NOT NULL,
  `contacto` varchar(120) NOT NULL,
  `categoria` enum('senha','pagamento','bilhete','reserva','transportadora','conta','outro') NOT NULL,
  `id_reserva` int(10) UNSIGNED DEFAULT NULL,
  `assunto` varchar(150) NOT NULL,
  `estado` enum('aberto','em_curso','resolvido') NOT NULL DEFAULT 'aberto',
  `id_admin` int(10) UNSIGNED DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data_criacao` datetime NOT NULL,
  `data_actualizacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `suporte_pedido`
--

INSERT INTO `suporte_pedido` (`id_pedido`, `id_passageiro`, `nome_contacto`, `contacto`, `categoria`, `id_reserva`, `assunto`, `estado`, `id_admin`, `ip`, `data_criacao`, `data_actualizacao`) VALUES
(1, 1, 'Ioramo Satar', 'ioramo.satar@moztransport.co.mz', 'outro', NULL, 'Outro assunto', 'resolvido', NULL, '::1', '2026-10-11 18:29:08', '2026-10-11 19:09:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transportadora`
--

CREATE TABLE `transportadora` (
  `id_transportadora` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `nuit` varchar(20) NOT NULL,
  `contacto` varchar(30) NOT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `transportadora`
--

INSERT INTO `transportadora` (`id_transportadora`, `nome`, `nuit`, `contacto`, `endereco`, `activa`) VALUES
(1, 'Nagi Investimentos', '400000001', '+258 84 000 0001', 'Maputo', 1),
(2, 'CityLink', '400000002', '+258 84 000 0002', 'Maputo', 1),
(3, 'Lalgy', '400000003', '+258 84 000 0003', 'Maputo', 1),
(4, 'Capital Way', '983132312', '850604771', 'Maputo', 1),
(6, 'Khurula Investiment', '645354346', '875433422', 'Maputo', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `id_utilizador` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `documento` varchar(30) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `agencia` varchar(100) DEFAULT NULL,
  `perfil` enum('passageiro','bilheteiro','cobrador','gestor','administrador') NOT NULL DEFAULT 'passageiro',
  `id_transportadora` int(10) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tentativas_falhadas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `bloqueado_ate` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `notificacoes` tinyint(1) NOT NULL DEFAULT 1,
  `notificacoes_vistas_em` datetime DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `numero_documento` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id_utilizador`, `nome`, `email`, `senha`, `telefone`, `documento`, `foto`, `agencia`, `perfil`, `id_transportadora`, `activo`, `data_criacao`, `tentativas_falhadas`, `bloqueado_ate`, `ultimo_acesso`, `notificacoes`, `notificacoes_vistas_em`, `tipo_documento`, `numero_documento`) VALUES
(1, 'Ioramo Satar', 'ioramo.satar@moztransport.co.mz', '$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm', NULL, NULL, NULL, NULL, 'passageiro', NULL, 1, '2026-10-05 11:57:08', 0, NULL, '2026-11-04 07:39:28', 1, '2026-10-11 18:26:08', NULL, NULL),
(4, 'Peresterello Nhalicolo', 'p.nhalicolo@moztransport.co.mz', '$2y$10$l8jQv/7W.U27wKSXdcr1M.NSocuVVxFh/m2oZ5PKFtkM.0kTcy0hm', '841920856', NULL, NULL, 'Maputo – Terminal Junta', 'gestor', 3, 1, '2026-10-05 11:57:08', 0, NULL, '2026-10-16 08:10:47', 1, '2026-10-11 19:20:02', NULL, NULL),
(17, 'Peresterello Nhalicolo', 'peresterello@gmail.com', '$2y$10$mSoij4NjBruAGqIEuqmwz.BgeGM9dOqiQVy4y/KiUvyHIJxKAM7C2', '+258 87 450 6250', NULL, NULL, NULL, 'gestor', 3, 1, '2026-10-16 08:23:43', 0, NULL, '2026-11-04 07:11:24', 1, NULL, NULL, NULL),
(23, 'Admin', 'n@gmail.com', '$2y$10$4cis3wraQuxOCRey5gPlE.zbdXHhtrwUDUrTjJBHbeXMkNpPpc3VS', NULL, NULL, NULL, NULL, 'administrador', NULL, 1, '2026-10-26 08:40:21', 0, NULL, '2026-11-03 16:15:02', 1, '2026-11-03 16:15:17', NULL, NULL),
(27, 'Nelson Nhavene', 'nelsonnhavene@gmail.com', '$2y$10$m/1xdlHAlqh1pWyIMM5Ya.8A9yYUsrrzgT3x7mrj5y.brNmo0BA.y', '+258 87 350 5758', NULL, NULL, NULL, 'administrador', NULL, 1, '2026-11-03 16:10:59', 0, NULL, '2026-11-04 07:54:15', 1, NULL, NULL, NULL),
(28, 'Bradly Alexandre', 'bradly@gmail.com', '$2y$10$K4fMJl3L14e.I9uR8G6amu6Ez5/RqI7SGlq371lUwYrP4Pqh/3t6W', '+258 84 411 1519', NULL, NULL, NULL, 'cobrador', 3, 1, '2026-11-04 07:06:50', 0, NULL, '2026-11-04 07:12:45', 1, '2026-11-04 07:13:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem`
--

CREATE TABLE `viagem` (
  `id_viagem` int(10) UNSIGNED NOT NULL,
  `data` date NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada` time DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `classe` varchar(30) NOT NULL DEFAULT 'Convencional',
  `estado` enum('agendada','em_curso','concluida','cancelada') NOT NULL DEFAULT 'agendada',
  `id_rota` int(10) UNSIGNED NOT NULL,
  `id_autocarro` int(10) UNSIGNED NOT NULL,
  `id_cobrador` int(10) UNSIGNED DEFAULT NULL,
  `id_motorista` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `viagem`
--

INSERT INTO `viagem` (`id_viagem`, `data`, `hora_partida`, `hora_chegada`, `preco`, `classe`, `estado`, `id_rota`, `id_autocarro`, `id_cobrador`, `id_motorista`) VALUES
(1, '2026-10-12', '10:00:00', '14:00:00', 4000.00, 'Executivo · AC', 'concluida', 3, 1, NULL, 1),
(2, '2026-10-12', '02:00:00', '04:00:00', 1500.00, 'Executivo · AC', 'concluida', 1, 1, NULL, 1),
(3, '2026-10-13', '02:00:00', '04:00:00', 1500.00, 'Executivo · AC', 'em_curso', 1, 1, NULL, 1),
(4, '2026-10-12', '23:00:00', '23:00:00', 8000.00, 'Executivo · AC', 'concluida', 7, 1, NULL, 5),
(5, '2026-10-12', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'concluida', 8, 1, NULL, 1),
(6, '2026-10-13', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'concluida', 8, 1, NULL, 1),
(7, '2026-10-14', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(8, '2026-10-15', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(9, '2026-10-16', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(10, '2026-10-17', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(11, '2026-10-18', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(12, '2026-10-19', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(13, '2026-10-20', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(14, '2026-10-21', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(15, '2026-10-22', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(16, '2026-10-23', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(17, '2026-10-24', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(18, '2026-10-25', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(19, '2026-10-26', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(20, '2026-10-27', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(21, '2026-10-28', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(22, '2026-10-29', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(23, '2026-10-30', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(24, '2026-10-31', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(25, '2026-11-01', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(26, '2026-11-02', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(27, '2026-11-03', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(28, '2026-11-04', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(29, '2026-11-05', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(30, '2026-11-06', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(31, '2026-11-07', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(32, '2026-11-08', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(33, '2026-11-09', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(34, '2026-11-10', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(35, '2026-11-11', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(36, '2026-11-12', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(37, '2026-11-13', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(38, '2026-11-14', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(39, '2026-11-15', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(40, '2026-11-16', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(41, '2026-11-17', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(42, '2026-11-18', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(43, '2026-11-19', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(44, '2026-11-20', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(45, '2026-11-21', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(46, '2026-11-22', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(47, '2026-11-23', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(48, '2026-11-24', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(49, '2026-11-25', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(50, '2026-11-26', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(51, '2026-11-27', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(52, '2026-11-28', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(53, '2026-11-29', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(54, '2026-11-30', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(55, '2026-12-01', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(56, '2026-12-02', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(57, '2026-12-03', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(58, '2026-12-04', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(59, '2026-12-05', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(60, '2026-12-06', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(61, '2026-12-07', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(62, '2026-12-08', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(63, '2026-12-09', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(64, '2026-12-10', '17:00:00', '08:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(65, '2026-10-12', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'concluida', 8, 1, NULL, 1),
(66, '2026-10-13', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'concluida', 8, 1, NULL, 1),
(67, '2026-10-14', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'em_curso', 8, 1, NULL, 1),
(68, '2026-10-15', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(69, '2026-10-16', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(70, '2026-10-17', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(71, '2026-10-18', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(72, '2026-10-19', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'cancelada', 8, 1, NULL, 1),
(73, '2026-10-20', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(74, '2026-10-21', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(75, '2026-10-22', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(76, '2026-10-23', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(77, '2026-10-24', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(78, '2026-10-25', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(79, '2026-10-26', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(80, '2026-10-27', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(81, '2026-10-28', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(82, '2026-10-29', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(83, '2026-10-30', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(84, '2026-10-31', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(85, '2026-11-01', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(86, '2026-11-02', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(87, '2026-11-03', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(88, '2026-11-04', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(89, '2026-11-05', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(90, '2026-11-06', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(91, '2026-11-07', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(92, '2026-11-08', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(93, '2026-11-09', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(94, '2026-11-10', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(95, '2026-11-11', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(96, '2026-11-12', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(97, '2026-11-13', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(98, '2026-11-14', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(99, '2026-11-15', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(100, '2026-11-16', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(101, '2026-11-17', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(102, '2026-11-18', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(103, '2026-11-19', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(104, '2026-11-20', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(105, '2026-11-21', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(106, '2026-11-22', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(107, '2026-11-23', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(108, '2026-11-24', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(109, '2026-11-25', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(110, '2026-11-26', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(111, '2026-11-27', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(112, '2026-11-28', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(113, '2026-11-29', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(114, '2026-11-30', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(115, '2026-12-01', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(116, '2026-12-02', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(117, '2026-12-03', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(118, '2026-12-04', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(119, '2026-12-05', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(120, '2026-12-06', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(121, '2026-12-07', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(122, '2026-12-08', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(123, '2026-12-09', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(124, '2026-12-10', '15:00:00', '06:00:00', 3800.00, 'Executivo · AC', 'agendada', 8, 1, NULL, 1),
(125, '2026-10-12', '20:00:00', '18:00:00', 2500.00, 'Executivo · AC', 'em_curso', 2, 3, NULL, 4),
(126, '2026-10-13', '20:00:00', '18:00:00', 2500.00, 'Executivo · AC', 'em_curso', 2, 3, NULL, 4),
(127, '2026-10-14', '20:00:00', '18:00:00', 2500.00, 'Executivo · AC', 'agendada', 2, 3, NULL, 4),
(128, '2026-10-12', '06:45:00', '10:45:00', 4000.00, 'Executivo · AC', 'concluida', 3, 3, NULL, 4),
(129, '2026-10-12', '18:00:00', '12:00:00', 5000.00, 'Executivo · AC', 'cancelada', 9, 4, NULL, NULL),
(130, '2026-10-11', '18:00:00', '12:00:00', 5000.00, 'Executivo · AC', 'concluida', 9, 4, NULL, 5),
(131, '2026-10-12', '12:00:00', '22:00:00', 1800.00, 'Executivo · AC', 'concluida', 6, 2, NULL, 2),
(132, '2026-10-17', '10:00:00', '09:00:00', 2600.00, 'Convencional', 'cancelada', 11, 1, NULL, 1),
(133, '2026-10-18', '10:00:00', '09:00:00', 2600.00, 'Convencional', 'cancelada', 11, 1, NULL, 1),
(134, '2026-10-20', '10:00:00', '09:00:00', 2600.00, 'Convencional', 'agendada', 11, 5, NULL, 5),
(135, '2026-10-17', '20:00:00', '02:00:00', 1200.00, 'Convencional', 'agendada', 12, 5, NULL, 6),
(136, '2026-10-17', '10:00:00', '16:00:00', 1200.00, 'Convencional', 'agendada', 13, 6, NULL, 7),
(137, '2026-10-18', '10:00:00', '16:00:00', 1200.00, 'Convencional', 'agendada', 13, 6, NULL, 7),
(138, '2026-10-16', '11:00:00', '17:00:00', 1200.00, 'Convencional', 'agendada', 13, 6, NULL, 7),
(139, '2026-10-27', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(140, '2026-10-28', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(141, '2026-10-29', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(142, '2026-10-30', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(143, '2026-10-31', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(144, '2026-11-01', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(145, '2026-11-02', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(146, '2026-11-03', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(147, '2026-11-04', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(148, '2026-11-05', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(149, '2026-11-06', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(150, '2026-11-07', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(151, '2026-11-08', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(152, '2026-11-09', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(153, '2026-11-10', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(154, '2026-11-11', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(155, '2026-11-12', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(156, '2026-11-13', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(157, '2026-11-14', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(158, '2026-11-15', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(159, '2026-11-16', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(160, '2026-11-17', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(161, '2026-11-18', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(162, '2026-11-19', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(163, '2026-11-20', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(164, '2026-11-21', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(165, '2026-11-22', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(166, '2026-11-23', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(167, '2026-11-24', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(168, '2026-11-25', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(169, '2026-11-26', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(170, '2026-11-27', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(171, '2026-11-28', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(172, '2026-11-29', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(173, '2026-11-30', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(174, '2026-12-01', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(175, '2026-12-02', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(176, '2026-12-03', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(177, '2026-12-04', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(178, '2026-12-05', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(179, '2026-12-06', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(180, '2026-12-07', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(181, '2026-12-08', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(182, '2026-12-09', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(183, '2026-12-10', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(184, '2026-12-11', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(185, '2026-12-12', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(186, '2026-12-13', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(187, '2026-12-14', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(188, '2026-12-15', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(189, '2026-12-16', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(190, '2026-12-17', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(191, '2026-12-18', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(192, '2026-12-19', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(193, '2026-12-20', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(194, '2026-12-21', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(195, '2026-12-22', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(196, '2026-12-23', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(197, '2026-12-24', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(198, '2026-12-25', '10:00:00', '16:00:00', 1500.00, 'Convencional', 'agendada', 14, 7, NULL, 8),
(199, '2026-10-26', '10:00:00', '18:00:00', 1500.00, 'Convencional', 'agendada', 15, 7, NULL, 8),
(200, '2026-11-05', '09:00:00', '11:00:00', 1500.00, 'Convencional', 'cancelada', 1, 3, NULL, 5),
(201, '2026-11-05', '10:00:00', '12:00:00', 1500.00, 'Convencional', 'cancelada', 1, 3, 28, 5),
(202, '2026-11-04', '10:00:00', '12:00:00', 1500.00, 'Convencional', 'agendada', 1, 3, 28, 5);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `assento`
--
ALTER TABLE `assento`
  ADD PRIMARY KEY (`id_assento`),
  ADD UNIQUE KEY `uq_assento_autocarro_numero` (`id_autocarro`,`numero`);

--
-- Índices para tabela `autocarro`
--
ALTER TABLE `autocarro`
  ADD PRIMARY KEY (`id_autocarro`),
  ADD UNIQUE KEY `uq_autocarro_matricula` (`matricula`),
  ADD UNIQUE KEY `uq_autocarro_codigo` (`id_transportadora`,`codigo_frota`);

--
-- Índices para tabela `bilhete`
--
ALTER TABLE `bilhete`
  ADD PRIMARY KEY (`id_bilhete`),
  ADD UNIQUE KEY `uq_bilhete_codigo_qr` (`codigo_qr`),
  ADD UNIQUE KEY `uq_bilhete_reserva` (`id_reserva`);

--
-- Índices para tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD PRIMARY KEY (`chave`);

--
-- Índices para tabela `log_transaccao`
--
ALTER TABLE `log_transaccao`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_log_data` (`data_hora`),
  ADD KEY `fk_log_utilizador` (`id_utilizador`);

--
-- Índices para tabela `motorista`
--
ALTER TABLE `motorista`
  ADD PRIMARY KEY (`id_motorista`),
  ADD UNIQUE KEY `uq_motorista_codigo` (`id_transportadora`,`codigo`),
  ADD UNIQUE KEY `uq_motorista_documento` (`documento`),
  ADD UNIQUE KEY `uq_motorista_carta` (`carta_numero`);

--
-- Índices para tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD UNIQUE KEY `uq_pagamento_reserva` (`id_reserva`),
  ADD UNIQUE KEY `uq_pagamento_referencia` (`referencia_transacao`);

--
-- Índices para tabela `perfil_permissao`
--
ALTER TABLE `perfil_permissao`
  ADD PRIMARY KEY (`perfil`,`id_permissao`),
  ADD KEY `fk_pp_permissao` (`id_permissao`);

--
-- Índices para tabela `permissao`
--
ALTER TABLE `permissao`
  ADD PRIMARY KEY (`id_permissao`),
  ADD UNIQUE KEY `uq_permissao_codigo` (`codigo`);

--
-- Índices para tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  ADD PRIMARY KEY (`id_recuperacao`),
  ADD KEY `idx_recuperacao_utilizador` (`id_utilizador`,`usado`);

--
-- Índices para tabela `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `uq_reserva_assento_viagem` (`id_viagem`,`id_assento`,`assento_ocupado`),
  ADD UNIQUE KEY `uq_reserva_referencia` (`referencia`),
  ADD KEY `idx_reserva_passageiro` (`id_passageiro`),
  ADD KEY `idx_reserva_estado` (`estado`,`data_expiracao`),
  ADD KEY `fk_reserva_assento` (`id_assento`),
  ADD KEY `fk_reserva_bilheteiro` (`id_bilheteiro`),
  ADD KEY `idx_reserva_grupo` (`grupo_venda`),
  ADD KEY `idx_reserva_embarque` (`id_viagem`,`embarcado_em`);

--
-- Índices para tabela `rota`
--
ALTER TABLE `rota`
  ADD PRIMARY KEY (`id_rota`),
  ADD KEY `idx_rota_origem_destino` (`origem`,`destino`),
  ADD KEY `fk_rota_transportadora` (`id_transportadora`);

--
-- Índices para tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `idx_spm_pedido` (`id_pedido`,`id_mensagem`),
  ADD KEY `idx_spm_data` (`autor`,`data_hora`);

--
-- Índices para tabela `suporte_pedido`
--
ALTER TABLE `suporte_pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `idx_sp_estado` (`estado`,`data_actualizacao`),
  ADD KEY `idx_sp_passageiro` (`id_passageiro`),
  ADD KEY `idx_sp_ip` (`ip`,`data_criacao`),
  ADD KEY `fk_sp_admin` (`id_admin`),
  ADD KEY `fk_sp_reserva` (`id_reserva`);

--
-- Índices para tabela `transportadora`
--
ALTER TABLE `transportadora`
  ADD PRIMARY KEY (`id_transportadora`),
  ADD UNIQUE KEY `uq_transportadora_nuit` (`nuit`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `uq_utilizador_email` (`email`),
  ADD UNIQUE KEY `uq_utilizador_documento` (`documento`),
  ADD KEY `idx_utilizador_perfil` (`perfil`),
  ADD KEY `fk_utilizador_transportadora` (`id_transportadora`);

--
-- Índices para tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`id_viagem`),
  ADD KEY `idx_viagem_data` (`data`,`hora_partida`),
  ADD KEY `fk_viagem_rota` (`id_rota`),
  ADD KEY `fk_viagem_autocarro` (`id_autocarro`),
  ADD KEY `fk_viagem_cobrador` (`id_cobrador`),
  ADD KEY `fk_viagem_motorista` (`id_motorista`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `assento`
--
ALTER TABLE `assento`
  MODIFY `id_assento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT de tabela `autocarro`
--
ALTER TABLE `autocarro`
  MODIFY `id_autocarro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `bilhete`
--
ALTER TABLE `bilhete`
  MODIFY `id_bilhete` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `log_transaccao`
--
ALTER TABLE `log_transaccao`
  MODIFY `id_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=723;

--
-- AUTO_INCREMENT de tabela `motorista`
--
ALTER TABLE `motorista`
  MODIFY `id_motorista` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `id_pagamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `permissao`
--
ALTER TABLE `permissao`
  MODIFY `id_permissao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  MODIFY `id_recuperacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `rota`
--
ALTER TABLE `rota`
  MODIFY `id_rota` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  MODIFY `id_mensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `suporte_pedido`
--
ALTER TABLE `suporte_pedido`
  MODIFY `id_pedido` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `transportadora`
--
ALTER TABLE `transportadora`
  MODIFY `id_transportadora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id_utilizador` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `id_viagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `assento`
--
ALTER TABLE `assento`
  ADD CONSTRAINT `fk_assento_autocarro` FOREIGN KEY (`id_autocarro`) REFERENCES `autocarro` (`id_autocarro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `autocarro`
--
ALTER TABLE `autocarro`
  ADD CONSTRAINT `fk_autocarro_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `bilhete`
--
ALTER TABLE `bilhete`
  ADD CONSTRAINT `fk_bilhete_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `log_transaccao`
--
ALTER TABLE `log_transaccao`
  ADD CONSTRAINT `fk_log_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `motorista`
--
ALTER TABLE `motorista`
  ADD CONSTRAINT `fk_motorista_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `fk_pagamento_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `perfil_permissao`
--
ALTER TABLE `perfil_permissao`
  ADD CONSTRAINT `fk_pp_permissao` FOREIGN KEY (`id_permissao`) REFERENCES `permissao` (`id_permissao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  ADD CONSTRAINT `fk_recuperacao_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_assento` FOREIGN KEY (`id_assento`) REFERENCES `assento` (`id_assento`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_bilheteiro` FOREIGN KEY (`id_bilheteiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_viagem` FOREIGN KEY (`id_viagem`) REFERENCES `viagem` (`id_viagem`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `rota`
--
ALTER TABLE `rota`
  ADD CONSTRAINT `fk_rota_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  ADD CONSTRAINT `fk_spm_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `suporte_pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `suporte_pedido`
--
ALTER TABLE `suporte_pedido`
  ADD CONSTRAINT `fk_sp_admin` FOREIGN KEY (`id_admin`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sp_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sp_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD CONSTRAINT `fk_utilizador_transportadora` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `fk_viagem_autocarro` FOREIGN KEY (`id_autocarro`) REFERENCES `autocarro` (`id_autocarro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_cobrador` FOREIGN KEY (`id_cobrador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_motorista` FOREIGN KEY (`id_motorista`) REFERENCES `motorista` (`id_motorista`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_rota` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
