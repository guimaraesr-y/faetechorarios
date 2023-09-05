-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.31 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- --------------------------------------------------------


-- Copiando estrutura do banco de dados para faetechorarios
CREATE DATABASE IF NOT EXISTS `faetechorarios` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `faetechorarios`;

-- Copiando estrutura para tabela faetechorarios.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.disciplinas
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `tempos` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.horarios
CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `turma_id` int DEFAULT NULL,
  `tempo_semana_id` int DEFAULT NULL,
  `sala_id` int DEFAULT NULL,
  `periodo_letivo_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `turma_id` (`turma_id`),
  KEY `tempo_semana_id` (`tempo_semana_id`),
  KEY `sala_id` (`sala_id`),
  KEY `periodo_letivo_id` (`periodo_letivo_id`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`),
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`tempo_semana_id`) REFERENCES `tempo_semana` (`id`),
  CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`),
  CONSTRAINT `horarios_ibfk_4` FOREIGN KEY (`periodo_letivo_id`) REFERENCES `periodo_letivo` (`id`)
);

-- Copiando estrutura para tabela faetechorarios.periodo_letivo
CREATE TABLE IF NOT EXISTS `periodo_letivo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `especificacao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.professores
CREATE TABLE IF NOT EXISTS `professores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.professor_matriculas
CREATE TABLE IF NOT EXISTS `professor_matriculas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professor_id` int DEFAULT NULL,
  `matricula` varchar(50) DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professor_id` (`professor_id`),
  CONSTRAINT `professor_matriculas_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`)
);

-- Copiando estrutura para tabela faetechorarios.professor_turma
CREATE TABLE IF NOT EXISTS `professor_turma` (
  `id` int NOT NULL AUTO_INCREMENT,
  `professor_id` int DEFAULT NULL,
  `turma_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `professor_id` (`professor_id`),
  KEY `turma_id` (`turma_id`),
  CONSTRAINT `professor_turma_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professores` (`id`),
  CONSTRAINT `professor_turma_ibfk_2` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando estrutura para tabela faetechorarios.salas
CREATE TABLE IF NOT EXISTS `salas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.tempo_semana
CREATE TABLE IF NOT EXISTS `tempo_semana` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia` varchar(255) DEFAULT NULL,
  `tempo` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Copiando estrutura para tabela faetechorarios.turmas
CREATE TABLE IF NOT EXISTS `turmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int DEFAULT NULL,
  `professor_matricula_id` int DEFAULT NULL,
  `disciplina_id` int DEFAULT NULL,
  `etapa` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curso_id` (`curso_id`),
  KEY `professor_matricula_id` (`professor_matricula_id`),
  KEY `disciplina_id` (`disciplina_id`),
  CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `turmas_ibfk_2` FOREIGN KEY (`professor_matricula_id`) REFERENCES `professor_matriculas` (`id`),
  CONSTRAINT `turmas_ibfk_3` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`)
);

