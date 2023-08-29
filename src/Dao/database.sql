-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.31 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `faetechorarios`
USE `faetechorarios`;

-- Copiando estrutura para tabela faetechorarios.horarios
CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sala_id` int DEFAULT NULL,
  `professor_turma_id` int DEFAULT NULL,
  `horario` int DEFAULT NULL,
  `dia` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `horarios_ibfk_1` (`sala_id`),
  KEY `horarios_ibfk_2` (`professor_turma_id`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`professor_turma_id`) REFERENCES `professor_turma` (`id`) ON DELETE CASCADE
);

-- Copiando estrutura para tabela faetechorarios.professores
CREATE TABLE IF NOT EXISTS `professores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tempos_semanais` int NOT NULL DEFAULT '20',
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.professor_turma
CREATE TABLE IF NOT EXISTS `professor_turma` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professor_id` int DEFAULT NULL,
  `turma_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professor_turma_ibfk_1` (`professor_id`),
  KEY `professor_turma_ibfk_2` (`turma_id`),
  CONSTRAINT `professor_turma_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `professor_turma_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE
);

-- Copiando estrutura para tabela faetechorarios.salas
CREATE TABLE IF NOT EXISTS `salas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.turmas
CREATE TABLE IF NOT EXISTS `turmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `turno` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);
