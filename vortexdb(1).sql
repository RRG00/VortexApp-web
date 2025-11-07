-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 06, 2025 at 08:14 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vortexdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1762458903),
('organizer', '4', 1762458903);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Administrador - gere users', NULL, NULL, 1762458903, 1762458903),
('organizer', 1, 'Organizador - gere users', NULL, NULL, 1762458903, 1762458903);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipa`
--

DROP TABLE IF EXISTS `equipa`;
CREATE TABLE IF NOT EXISTS `equipa` (
  `id_equipa` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `id_capitao` int DEFAULT NULL,
  `data_criacao` date NOT NULL,
  PRIMARY KEY (`id_equipa`),
  KEY `id_capitao` (`id_capitao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estatisticas`
--

DROP TABLE IF EXISTS `estatisticas`;
CREATE TABLE IF NOT EXISTS `estatisticas` (
  `id_estatistica` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `id_jogo` int NOT NULL,
  `vitorias` int NOT NULL DEFAULT '0',
  `derrotas` int NOT NULL DEFAULT '0',
  `pontuacao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kd` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_estatistica`),
  KEY `id_utilizador` (`id_utilizador`),
  KEY `id_jogo` (`id_jogo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscricao`
--

DROP TABLE IF EXISTS `inscricao`;
CREATE TABLE IF NOT EXISTS `inscricao` (
  `id_inscricao` int NOT NULL AUTO_INCREMENT,
  `id_torneio` int NOT NULL,
  `id_equipa` int NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id_inscricao`),
  KEY `id_torneio` (`id_torneio`),
  KEY `id_equipa` (`id_equipa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jogo`
--

DROP TABLE IF EXISTS `jogo`;
CREATE TABLE IF NOT EXISTS `jogo` (
  `id_jogo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `genero` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_jogo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jogo`
--

INSERT INTO `jogo` (`id_jogo`, `nome`, `genero`, `imagem`) VALUES
(1, 'Jogo Teste', 'FPS', 'JT');

-- --------------------------------------------------------

--
-- Table structure for table `membros_equipa`
--

DROP TABLE IF EXISTS `membros_equipa`;
CREATE TABLE IF NOT EXISTS `membros_equipa` (
  `id_utilizador` int NOT NULL,
  `id_equipa` int NOT NULL,
  `funcao` varchar(100) NOT NULL,
  PRIMARY KEY (`id_utilizador`,`id_equipa`),
  KEY `id_equipa` (`id_equipa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1760643438),
('m130524_201442_init', 1760643440),
('m140506_102106_rbac_init', 1761942572),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1761942572),
('m180523_151638_rbac_updates_indexes_without_prefix', 1761942572),
('m190124_110200_add_verification_token_column_to_user_table', 1760643440),
('m200409_110543_rbac_update_mssql_trigger', 1761942572);

-- --------------------------------------------------------

--
-- Table structure for table `noticia`
--

DROP TABLE IF EXISTS `noticia`;
CREATE TABLE IF NOT EXISTS `noticia` (
  `id_noticia` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `data_publicacao` date NOT NULL,
  `autor_id` int NOT NULL,
  `id_jogo` int NOT NULL,
  PRIMARY KEY (`id_noticia`),
  KEY `autor_id` (`autor_id`),
  KEY `id_jogo` (`id_jogo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notificacao`
--

DROP TABLE IF EXISTS `notificacao`;
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id_notificacao` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL,
  PRIMARY KEY (`id_notificacao`),
  KEY `id_utilizador` (`id_utilizador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partida`
--

DROP TABLE IF EXISTS `partida`;
CREATE TABLE IF NOT EXISTS `partida` (
  `id_partida` int NOT NULL AUTO_INCREMENT,
  `id_torneio` int NOT NULL,
  `equipa_a` int NOT NULL,
  `equipa_b` int NOT NULL,
  `vitorias_a` int NOT NULL DEFAULT '0',
  `vitorias_b` int NOT NULL DEFAULT '0',
  `estado` enum('pendente','em_andamento','concluida') NOT NULL,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id_partida`),
  KEY `id_torneio` (`id_torneio`),
  KEY `equipa_a` (`equipa_a`),
  KEY `equipa_b` (`equipa_b`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patrocinador`
--

DROP TABLE IF EXISTS `patrocinador`;
CREATE TABLE IF NOT EXISTS `patrocinador` (
  `id_patrocinador` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `logotipo` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `id_torneio` int NOT NULL,
  PRIMARY KEY (`id_patrocinador`),
  KEY `id_torneio` (`id_torneio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publicidade`
--

DROP TABLE IF EXISTS `publicidade`;
CREATE TABLE IF NOT EXISTS `publicidade` (
  `id_anuncio` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  PRIMARY KEY (`id_anuncio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reclamacao`
--

DROP TABLE IF EXISTS `reclamacao`;
CREATE TABLE IF NOT EXISTS `reclamacao` (
  `id_reclamacao` int NOT NULL AUTO_INCREMENT,
  `id_partida` int NOT NULL,
  `id_moderador` int DEFAULT NULL,
  `descricao` text NOT NULL,
  `estado` varchar(50) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id_reclamacao`),
  KEY `id_partida` (`id_partida`),
  KEY `id_moderador` (`id_moderador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `torneio`
--

DROP TABLE IF EXISTS `torneio`;
CREATE TABLE IF NOT EXISTS `torneio` (
  `id_torneio` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `best_of` int NOT NULL,
  `regras` text,
  `limite_inscricoes` int NOT NULL,
  `premios` varchar(255) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `estado` varchar(50) NOT NULL,
  `organizador_id` int NOT NULL,
  `aprovado_por` int DEFAULT NULL,
  `id_jogo` int NOT NULL,
  `descricao` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_torneio`),
  KEY `organizador_id` (`organizador_id`),
  KEY `aprovado_por` (`aprovado_por`),
  KEY `id_jogo` (`id_jogo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `torneio`
--

INSERT INTO `torneio` (`id_torneio`, `nome`, `best_of`, `regras`, `limite_inscricoes`, `premios`, `data_inicio`, `data_fim`, `estado`, `organizador_id`, `aprovado_por`, `id_jogo`, `descricao`) VALUES
(1, 'Torneio teste', 1, 'jogar\r\nteste\r\nteste\r\nteste\r\nteste\r\nteste\r\nteste\r\nteste\r\nteste\r\n', 1, '1', '2025-10-30', '2025-10-31', 'em breve', 1, 1, 1, 'este é um torneio de teste onde estamos a testar'),
(2, 'teste 2', 1, 'teste', 2, '1', '2025-10-31', '2025-10-31', 'Ativo', 1, 1, 1, 'este é um torneio de teste onde estamos a testar'),
(3, 'teste 3', 3, 'teste\r\nteste\r\nteste', 5, '5', '2025-10-31', '2025-10-31', 'cancelado', 1, 1, 1, 'este é um torneio de teste onde estamos a testar'),
(4, 'teste 4', 3, 'teste\r\nteste\r\nteste\r\nteste', 6, '43', '2025-10-31', '2025-10-31', 'concluido', 1, 1, 1, 'este é um torneio de teste onde estamos a testar');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `papel` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado_conta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`),
  KEY `idx_user_email` (`email`),
  KEY `idx_user_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`, `data_nascimento`, `papel`, `estado_conta`) VALUES
(1, 'admin', 'ss1TcbZkdHYQ15rlEgVFPyvK6DFi351j', '$2y$13$jTB8dDw8dbY90pyw6ZCT.ux6qYWnI6MEMiXDrj7s/8zYeSGAkqtGO', NULL, 'admin@admin.pt', 10, 1760646068, 1760646068, 'xGywy8ayknt_qn37Q1U6d9MNzDdzmEVE_1760646068', NULL, 'admin', NULL),
(4, 'org', 'FtxBCahabjMmrHRBkLJR3Wrn86wAd1eZ', '$2y$13$xnv8EBwbkbhg6qbESHzNn.hIHNSVsfInE7JwgFHRsWqyHVvdoZ2ze', NULL, 'org@org.com', 10, 1762457126, 1762457126, '6waZUMW2sJiwYB83WOTFVMf8MUaa8BV4_1762457126', NULL, 'organizer', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `equipa`
--
ALTER TABLE `equipa`
  ADD CONSTRAINT `equipa_ibfk_1` FOREIGN KEY (`id_capitao`) REFERENCES `user` (`id`);

--
-- Constraints for table `estatisticas`
--
ALTER TABLE `estatisticas`
  ADD CONSTRAINT `estatisticas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `estatisticas_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id_jogo`);

--
-- Constraints for table `inscricao`
--
ALTER TABLE `inscricao`
  ADD CONSTRAINT `inscricao_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `torneio` (`id_torneio`),
  ADD CONSTRAINT `inscricao_ibfk_2` FOREIGN KEY (`id_equipa`) REFERENCES `equipa` (`id_equipa`);

--
-- Constraints for table `membros_equipa`
--
ALTER TABLE `membros_equipa`
  ADD CONSTRAINT `membros_equipa_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `membros_equipa_ibfk_2` FOREIGN KEY (`id_equipa`) REFERENCES `equipa` (`id_equipa`);

--
-- Constraints for table `noticia`
--
ALTER TABLE `noticia`
  ADD CONSTRAINT `noticia_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `noticia_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id_jogo`);

--
-- Constraints for table `notificacao`
--
ALTER TABLE `notificacao`
  ADD CONSTRAINT `notificacao_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`);

--
-- Constraints for table `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `torneio` (`id_torneio`),
  ADD CONSTRAINT `partida_ibfk_2` FOREIGN KEY (`equipa_a`) REFERENCES `equipa` (`id_equipa`),
  ADD CONSTRAINT `partida_ibfk_3` FOREIGN KEY (`equipa_b`) REFERENCES `equipa` (`id_equipa`);

--
-- Constraints for table `patrocinador`
--
ALTER TABLE `patrocinador`
  ADD CONSTRAINT `patrocinador_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `torneio` (`id_torneio`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reclamacao`
--
ALTER TABLE `reclamacao`
  ADD CONSTRAINT `reclamacao_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`),
  ADD CONSTRAINT `reclamacao_ibfk_2` FOREIGN KEY (`id_moderador`) REFERENCES `user` (`id`);

--
-- Constraints for table `torneio`
--
ALTER TABLE `torneio`
  ADD CONSTRAINT `torneio_ibfk_1` FOREIGN KEY (`organizador_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `torneio_ibfk_2` FOREIGN KEY (`aprovado_por`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `torneio_ibfk_3` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id_jogo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
