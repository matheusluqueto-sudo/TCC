-- Banco de dados do TCC - EPI Control
-- MySQL / MariaDB
-- Compatível com XAMPP

CREATE DATABASE IF NOT EXISTS `tcc`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `tcc`;

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `if_solicitacao`;
DROP TABLE IF EXISTS `tb_saida`;
DROP TABLE IF EXISTS `tb_entrada`;
DROP TABLE IF EXISTS `tb_solicitacoes`;
DROP TABLE IF EXISTS `tb_epis`;
DROP TABLE IF EXISTS `tb_funcionarios`;

SET FOREIGN_KEY_CHECKS = 1;

-- Funcionários / usuários do sistema
CREATE TABLE `tb_funcionarios` (
  `id_funcionario` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(100) NOT NULL,
  `cpf` VARCHAR(20) DEFAULT NULL,
  `cargo` VARCHAR(100) DEFAULT NULL,
  `setor` VARCHAR(100) DEFAULT NULL,
  `foto_url` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `nivel_acesso` VARCHAR(30) NOT NULL DEFAULT 'Funcionario',
  PRIMARY KEY (`id_funcionario`),
  UNIQUE KEY `uk_funcionarios_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- EPIs cadastrados
CREATE TABLE `tb_epis` (
  `id_epi` INT NOT NULL AUTO_INCREMENT,
  `nome_epi` VARCHAR(100) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `quantidade_estoque` INT NOT NULL DEFAULT 0,
  `estoque_minimo` INT NOT NULL DEFAULT 0,
  `validade` INT DEFAULT NULL,
  `codigo` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id_epi`),
  UNIQUE KEY `uk_epis_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Entradas de estoque
CREATE TABLE `tb_entrada` (
  `id_entrada` INT NOT NULL AUTO_INCREMENT,
  `id_epi` INT NOT NULL,
  `quantidade` INT NOT NULL,
  `data` DATE DEFAULT NULL,
  `motivo` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_entrada`),
  KEY `idx_entrada_epi` (`id_epi`),
  CONSTRAINT `tb_entrada_ibfk_1`
    FOREIGN KEY (`id_epi`) REFERENCES `tb_epis` (`id_epi`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Saídas / entregas de EPI
CREATE TABLE `tb_saida` (
  `id_saida` INT NOT NULL AUTO_INCREMENT,
  `id_funcionario` INT NOT NULL,
  `id_epi` INT NOT NULL,
  `solicitacao` TINYINT(1) DEFAULT NULL,
  `quantidade` INT NOT NULL,
  `data` DATE DEFAULT NULL,
  `motivo` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_saida`),
  KEY `idx_saida_funcionario` (`id_funcionario`),
  KEY `idx_saida_epi` (`id_epi`),
  CONSTRAINT `tb_saida_ibfk_1`
    FOREIGN KEY (`id_funcionario`) REFERENCES `tb_funcionarios` (`id_funcionario`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `tb_saida_ibfk_2`
    FOREIGN KEY (`id_epi`) REFERENCES `tb_epis` (`id_epi`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Solicitações de EPI
CREATE TABLE `tb_solicitacoes` (
  `id_solicitacao` INT NOT NULL AUTO_INCREMENT,
  `id_funcionario` INT NOT NULL,
  `id_epi` INT NOT NULL,
  `justificativa` TEXT DEFAULT NULL,
  `status_solicitacao` VARCHAR(30) NOT NULL DEFAULT 'Pendente',
  `data_solicitacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_solicitacao`),
  KEY `idx_solicitacao_funcionario` (`id_funcionario`),
  KEY `idx_solicitacao_epi` (`id_epi`),
  CONSTRAINT `tb_solicitacoes_ibfk_1`
    FOREIGN KEY (`id_funcionario`) REFERENCES `tb_funcionarios` (`id_funcionario`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `tb_solicitacoes_ibfk_2`
    FOREIGN KEY (`id_epi`) REFERENCES `tb_epis` (`id_epi`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela auxiliar para solicitação
CREATE TABLE `if_solicitacao` (
  `id_solicitacao` INT NOT NULL,
  PRIMARY KEY (`id_solicitacao`),
  CONSTRAINT `if_solicitacao_ibfk_1`
    FOREIGN KEY (`id_solicitacao`) REFERENCES `tb_solicitacoes` (`id_solicitacao`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuário inicial para teste do login
INSERT INTO `tb_funcionarios`
(
  `nome_completo`,
  `cpf`,
  `cargo`,
  `setor`,
  `email`,
  `senha`,
  `nivel_acesso`
)
VALUES
(
  'Administrador',
  '00000000000',
  'Administrador',
  'TI',
  'admin@epicontrol.com',
  '123456',
  'Administrador'
);