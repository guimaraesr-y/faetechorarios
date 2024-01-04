-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura do banco de dados para faetechorarios
CREATE DATABASE IF NOT EXISTS `faetechorarios` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `faetechorarios`;

-- Copiando estrutura para tabela faetechorarios.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.disciplinas
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `tempos` int DEFAULT NULL,
  `curso_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curso_id` (`curso_id`),
  CONSTRAINT `FK_disciplinas_cursos` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.horarios
CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tempo_semana_id` int DEFAULT NULL,
  `sala_id` int DEFAULT NULL,
  `periodo_letivo_id` int DEFAULT NULL,
  `turma_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tempo_semana_id` (`tempo_semana_id`),
  KEY `sala_id` (`sala_id`),
  KEY `periodo_letivo_id` (`periodo_letivo_id`),
  KEY `FK_horarios_turmas` (`turma_id`),
  CONSTRAINT `FK_horarios_turmas` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`),
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`tempo_semana_id`) REFERENCES `tempo_semana` (`id`),
  CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`),
  CONSTRAINT `horarios_ibfk_4` FOREIGN KEY (`periodo_letivo_id`) REFERENCES `periodo_letivo` (`id`)
) ENGINE=InnoDB26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.periodo_letivo
CREATE TABLE IF NOT EXISTS `periodo_letivo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `especificacao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.professores
CREATE TABLE IF NOT EXISTS `professores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.professor_matriculas
CREATE TABLE IF NOT EXISTS `professor_matriculas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professor_id` int DEFAULT NULL,
  `matricula` varchar(50) DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professor_id` (`professor_id`),
  CONSTRAINT `professor_matriculas_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.salas
CREATE TABLE IF NOT EXISTS `salas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.tempo_semana
CREATE TABLE IF NOT EXISTS `tempo_semana` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia` varchar(255) DEFAULT NULL,
  `tempo` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.turmas
CREATE TABLE IF NOT EXISTS `turmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int DEFAULT NULL,
  `professor_matricula_id` int DEFAULT NULL,
  `disciplina_id` int DEFAULT NULL,
  `periodo_letivo_id` int DEFAULT NULL,
  `etapa` int DEFAULT NULL,
  `turno` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curso_id` (`curso_id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `FK_turmas_professor_matriculas` (`professor_matricula_id`),
  KEY `FK_turmas_periodo_letivo` (`periodo_letivo_id`),
  CONSTRAINT `FK_turmas_periodo_letivo` FOREIGN KEY (`periodo_letivo_id`) REFERENCES `periodo_letivo` (`id`),
  CONSTRAINT `FK_turmas_professor_matriculas` FOREIGN KEY (`professor_matricula_id`) REFERENCES `professor_matriculas` (`id`),
  CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `turmas_ibfk_3` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Inserindo dados
INSERT INTO `tempo_semana` (`id`, `dia`, `tempo`) VALUES
  (1, 'Segunda', 1),
  (2, 'Segunda', 2),
  (3, 'Segunda', 3),
  (4, 'Segunda', 4),
  (5, 'Segunda', 5),
  (6, 'Segunda', 6),

  (7, 'Terça', 1),
  (8, 'Terça', 2),
  (9, 'Terça', 3),
  (10, 'Terça', 4),
  (11, 'Terça', 5),
  (12, 'Terça', 6),

  (13, 'Quarta', 1),
  (14, 'Quarta', 2),
  (15, 'Quarta', 3),
  (16, 'Quarta', 4),
  (17, 'Quarta', 5),
  (18, 'Quarta', 6),

  (19, 'Quinta', 1),
  (20, 'Quinta', 2),
  (21, 'Quinta', 3),
  (22, 'Quinta', 4),
  (23, 'Quinta', 5),
  (24, 'Quinta', 6),

  (25, 'Sexta', 1),
  (26, 'Sexta', 2),
  (27, 'Sexta', 3),
  (28, 'Sexta', 4),
  (29, 'Sexta', 5),
  (30, 'Sexta', 6);

INSERT INTO `salas` (`id`, `nome`) VALUES
  (1, 'LAB1'),
  (2, 'LAB2'),
  (3, 'LAB3'),
  (4, 'SALA1'),
  (5, 'SALA2'),
  (6, 'SALA3'),
  (7, 'SALA4'),
  (8, 'BARBEIRO'),
  (9, 'CABELEIREIRO');

INSERT INTO `periodo_letivo` (`id`, `especificacao`) VALUES
  (1, '2024.1');