-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 29, 2025 at 08:21 PM
-- Server version: 8.0.40
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
-- Table structure for table `EQUIPA`
--

CREATE TABLE `EQUIPA` (
  `id_equipa` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `id_capitao` int DEFAULT NULL,
  `data_criacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ESTATISTICAS`
--

CREATE TABLE `ESTATISTICAS` (
  `id_estatistica` int NOT NULL,
  `id_utilizador` int NOT NULL,
  `id_jogo` int NOT NULL,
  `vitorias` int NOT NULL DEFAULT '0',
  `derrotas` int NOT NULL DEFAULT '0',
  `pontuacao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kd` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `INSCRICAO`
--

CREATE TABLE `INSCRICAO` (
  `id_inscricao` int NOT NULL,
  `id_torneio` int NOT NULL,
  `id_equipa` int NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `JOGOS`
--

CREATE TABLE `JOGOS` (
  `id_jogo` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `genero` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `MEMBROS_EQUIPA`
--

CREATE TABLE `MEMBROS_EQUIPA` (
  `id_utilizador` int NOT NULL,
  `id_equipa` int NOT NULL,
  `funcao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1760643438),
('m130524_201442_init', 1760643440),
('m190124_110200_add_verification_token_column_to_user_table', 1760643440);

-- --------------------------------------------------------

--
-- Table structure for table `NOTICIA`
--

CREATE TABLE `NOTICIA` (
  `id_noticia` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `data_publicacao` date NOT NULL,
  `autor_id` int NOT NULL,
  `id_jogo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICACAO`
--

CREATE TABLE `NOTIFICACAO` (
  `id_notificacao` int NOT NULL,
  `id_utilizador` int NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PARTIDA`
--

CREATE TABLE `PARTIDA` (
  `id_partida` int NOT NULL,
  `id_torneio` int NOT NULL,
  `equipa_a` int NOT NULL,
  `equipa_b` int NOT NULL,
  `vitorias_a` int NOT NULL DEFAULT '0',
  `vitorias_b` int NOT NULL DEFAULT '0',
  `estado` enum('pendente','em_andamento','concluida') NOT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PATROCINADOR`
--

CREATE TABLE `PATROCINADOR` (
  `id_patrocinador` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `logotipo` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `id_torneio` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PUBLICIDADE`
--

CREATE TABLE `PUBLICIDADE` (
  `id_anuncio` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `RECLAMACAO`
--

CREATE TABLE `RECLAMACAO` (
  `id_reclamacao` int NOT NULL,
  `id_partida` int NOT NULL,
  `id_moderador` int DEFAULT NULL,
  `descricao` text NOT NULL,
  `estado` varchar(50) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TORNEIO`
--

CREATE TABLE `TORNEIO` (
  `id_torneio` int NOT NULL,
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
  `id_jogo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `papel` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado_conta` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`, `data_nascimento`, `papel`, `estado_conta`) VALUES
(1, 'admin', 'ss1TcbZkdHYQ15rlEgVFPyvK6DFi351j', '$2y$13$jTB8dDw8dbY90pyw6ZCT.ux6qYWnI6MEMiXDrj7s/8zYeSGAkqtGO', NULL, 'admin@admin.pt', 10, 1760646068, 1760646068, 'xGywy8ayknt_qn37Q1U6d9MNzDdzmEVE_1760646068', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `EQUIPA`
--
ALTER TABLE `EQUIPA`
  ADD PRIMARY KEY (`id_equipa`),
  ADD KEY `id_capitao` (`id_capitao`);

--
-- Indexes for table `ESTATISTICAS`
--
ALTER TABLE `ESTATISTICAS`
  ADD PRIMARY KEY (`id_estatistica`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_jogo` (`id_jogo`);

--
-- Indexes for table `INSCRICAO`
--
ALTER TABLE `INSCRICAO`
  ADD PRIMARY KEY (`id_inscricao`),
  ADD KEY `id_torneio` (`id_torneio`),
  ADD KEY `id_equipa` (`id_equipa`);

--
-- Indexes for table `JOGOS`
--
ALTER TABLE `JOGOS`
  ADD PRIMARY KEY (`id_jogo`);

--
-- Indexes for table `MEMBROS_EQUIPA`
--
ALTER TABLE `MEMBROS_EQUIPA`
  ADD PRIMARY KEY (`id_utilizador`,`id_equipa`),
  ADD KEY `id_equipa` (`id_equipa`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `NOTICIA`
--
ALTER TABLE `NOTICIA`
  ADD PRIMARY KEY (`id_noticia`),
  ADD KEY `autor_id` (`autor_id`),
  ADD KEY `id_jogo` (`id_jogo`);

--
-- Indexes for table `NOTIFICACAO`
--
ALTER TABLE `NOTIFICACAO`
  ADD PRIMARY KEY (`id_notificacao`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Indexes for table `PARTIDA`
--
ALTER TABLE `PARTIDA`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_torneio` (`id_torneio`),
  ADD KEY `equipa_a` (`equipa_a`),
  ADD KEY `equipa_b` (`equipa_b`);

--
-- Indexes for table `PATROCINADOR`
--
ALTER TABLE `PATROCINADOR`
  ADD PRIMARY KEY (`id_patrocinador`),
  ADD KEY `id_torneio` (`id_torneio`);

--
-- Indexes for table `PUBLICIDADE`
--
ALTER TABLE `PUBLICIDADE`
  ADD PRIMARY KEY (`id_anuncio`);

--
-- Indexes for table `RECLAMACAO`
--
ALTER TABLE `RECLAMACAO`
  ADD PRIMARY KEY (`id_reclamacao`),
  ADD KEY `id_partida` (`id_partida`),
  ADD KEY `id_moderador` (`id_moderador`);

--
-- Indexes for table `TORNEIO`
--
ALTER TABLE `TORNEIO`
  ADD PRIMARY KEY (`id_torneio`),
  ADD KEY `organizador_id` (`organizador_id`),
  ADD KEY `aprovado_por` (`aprovado_por`),
  ADD KEY `id_jogo` (`id_jogo`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `EQUIPA`
--
ALTER TABLE `EQUIPA`
  MODIFY `id_equipa` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ESTATISTICAS`
--
ALTER TABLE `ESTATISTICAS`
  MODIFY `id_estatistica` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `INSCRICAO`
--
ALTER TABLE `INSCRICAO`
  MODIFY `id_inscricao` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `JOGOS`
--
ALTER TABLE `JOGOS`
  MODIFY `id_jogo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `NOTICIA`
--
ALTER TABLE `NOTICIA`
  MODIFY `id_noticia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `NOTIFICACAO`
--
ALTER TABLE `NOTIFICACAO`
  MODIFY `id_notificacao` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PARTIDA`
--
ALTER TABLE `PARTIDA`
  MODIFY `id_partida` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PATROCINADOR`
--
ALTER TABLE `PATROCINADOR`
  MODIFY `id_patrocinador` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PUBLICIDADE`
--
ALTER TABLE `PUBLICIDADE`
  MODIFY `id_anuncio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `RECLAMACAO`
--
ALTER TABLE `RECLAMACAO`
  MODIFY `id_reclamacao` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `TORNEIO`
--
ALTER TABLE `TORNEIO`
  MODIFY `id_torneio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `EQUIPA`
--
ALTER TABLE `EQUIPA`
  ADD CONSTRAINT `equipa_ibfk_1` FOREIGN KEY (`id_capitao`) REFERENCES `user` (`id`);

--
-- Constraints for table `ESTATISTICAS`
--
ALTER TABLE `ESTATISTICAS`
  ADD CONSTRAINT `estatisticas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `estatisticas_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `JOGOS` (`id_jogo`);

--
-- Constraints for table `INSCRICAO`
--
ALTER TABLE `INSCRICAO`
  ADD CONSTRAINT `inscricao_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `TORNEIO` (`id_torneio`),
  ADD CONSTRAINT `inscricao_ibfk_2` FOREIGN KEY (`id_equipa`) REFERENCES `EQUIPA` (`id_equipa`);

--
-- Constraints for table `MEMBROS_EQUIPA`
--
ALTER TABLE `MEMBROS_EQUIPA`
  ADD CONSTRAINT `membros_equipa_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `membros_equipa_ibfk_2` FOREIGN KEY (`id_equipa`) REFERENCES `EQUIPA` (`id_equipa`);

--
-- Constraints for table `NOTICIA`
--
ALTER TABLE `NOTICIA`
  ADD CONSTRAINT `noticia_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `noticia_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `JOGOS` (`id_jogo`);

--
-- Constraints for table `NOTIFICACAO`
--
ALTER TABLE `NOTIFICACAO`
  ADD CONSTRAINT `notificacao_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `user` (`id`);

--
-- Constraints for table `PARTIDA`
--
ALTER TABLE `PARTIDA`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `TORNEIO` (`id_torneio`),
  ADD CONSTRAINT `partida_ibfk_2` FOREIGN KEY (`equipa_a`) REFERENCES `EQUIPA` (`id_equipa`),
  ADD CONSTRAINT `partida_ibfk_3` FOREIGN KEY (`equipa_b`) REFERENCES `EQUIPA` (`id_equipa`);

--
-- Constraints for table `PATROCINADOR`
--
ALTER TABLE `PATROCINADOR`
  ADD CONSTRAINT `patrocinador_ibfk_1` FOREIGN KEY (`id_torneio`) REFERENCES `TORNEIO` (`id_torneio`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `RECLAMACAO`
--
ALTER TABLE `RECLAMACAO`
  ADD CONSTRAINT `reclamacao_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `PARTIDA` (`id_partida`),
  ADD CONSTRAINT `reclamacao_ibfk_2` FOREIGN KEY (`id_moderador`) REFERENCES `user` (`id`);

--
-- Constraints for table `TORNEIO`
--
ALTER TABLE `TORNEIO`
  ADD CONSTRAINT `torneio_ibfk_1` FOREIGN KEY (`organizador_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `torneio_ibfk_2` FOREIGN KEY (`aprovado_por`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `torneio_ibfk_3` FOREIGN KEY (`id_jogo`) REFERENCES `JOGOS` (`id_jogo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
