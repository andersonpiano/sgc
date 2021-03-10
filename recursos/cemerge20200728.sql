-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 28-Jul-2020 às 22:03
-- Versão do servidor: 10.1.38-MariaDB
-- versão do PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cemerge`
--
CREATE DATABASE IF NOT EXISTS `cemerge` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cemerge`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin_preferences`
--

DROP TABLE IF EXISTS `admin_preferences`;
CREATE TABLE IF NOT EXISTS `admin_preferences` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `user_panel` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar_form` tinyint(1) NOT NULL DEFAULT '0',
  `messages_menu` tinyint(1) NOT NULL DEFAULT '0',
  `notifications_menu` tinyint(1) NOT NULL DEFAULT '0',
  `tasks_menu` tinyint(1) NOT NULL DEFAULT '0',
  `user_menu` tinyint(1) NOT NULL DEFAULT '1',
  `ctrl_sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `transition_page` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `admin_preferences`
--

INSERT INTO `admin_preferences` (`id`, `user_panel`, `sidebar_form`, `messages_menu`, `notifications_menu`, `tasks_menu`, `user_menu`, `ctrl_sidebar`, `transition_page`) VALUES
(1, 0, 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20200720113403', '2020-07-20 13:40:49', 1896);

-- --------------------------------------------------------

--
-- Estrutura da tabela `escalas`
--

DROP TABLE IF EXISTS `escalas`;
CREATE TABLE IF NOT EXISTS `escalas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataplantao` date NOT NULL,
  `datafinalplantao` date NOT NULL,
  `horainicialplantao` time NOT NULL,
  `horafinalplantao` time NOT NULL,
  `duracao` int(11) NOT NULL,
  `profissional_id` int(11) DEFAULT '0',
  `setor_id` int(11) NOT NULL,
  `tipopassagem` int(11) DEFAULT NULL,
  `datahorapassagem` datetime DEFAULT NULL,
  `statuspassagem` tinyint(4) DEFAULT NULL,
  `datahoraconfirmacao` datetime DEFAULT NULL,
  `profissionalsubstituto_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `escalas`
--

INSERT INTO `escalas` (`id`, `dataplantao`, `datafinalplantao`, `horainicialplantao`, `horafinalplantao`, `duracao`, `profissional_id`, `setor_id`, `tipopassagem`, `datahorapassagem`, `statuspassagem`, `datahoraconfirmacao`, `profissionalsubstituto_id`) VALUES
(1, '2020-08-01', '2020-08-01', '07:00:00', '13:00:00', 6, 2, 5, 0, '2020-07-28 09:00:14', 0, NULL, 3),
(2, '2020-08-01', '2020-08-01', '13:00:00', '19:00:00', 6, 3, 5, 0, '0000-00-00 00:00:00', 1, '2020-07-28 10:17:17', 2),
(3, '2020-08-01', '2020-08-02', '19:00:00', '07:00:00', 12, 0, 5, 0, '0000-00-00 00:00:00', NULL, NULL, 0),
(4, '2020-08-02', '2020-08-03', '19:00:00', '07:00:00', 12, 2, 5, NULL, NULL, NULL, NULL, 0),
(5, '2020-08-02', '2020-08-02', '07:00:00', '13:00:00', 6, 0, 5, NULL, NULL, NULL, NULL, 0),
(6, '2020-08-02', '2020-08-02', '13:00:00', '19:00:00', 6, 0, 5, NULL, NULL, NULL, NULL, 0),
(7, '2020-08-03', '0000-00-00', '07:00:00', '13:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(8, '2020-08-03', '0000-00-00', '13:00:00', '19:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(9, '2020-08-03', '0000-00-00', '19:00:00', '07:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(10, '2020-08-04', '0000-00-00', '07:00:00', '13:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(11, '2020-08-04', '0000-00-00', '13:00:00', '19:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(12, '2020-08-04', '0000-00-00', '19:00:00', '07:00:00', 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(13, '2020-08-05', '2020-08-05', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(14, '2020-08-06', '2020-08-06', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(15, '2020-08-07', '2020-08-07', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(16, '2020-08-08', '2020-08-08', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(17, '2020-08-09', '2020-08-09', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(18, '2020-08-10', '2020-08-10', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(19, '2020-08-11', '2020-08-11', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(20, '2020-08-12', '2020-08-12', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(21, '2020-08-13', '2020-08-13', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(22, '2020-08-14', '2020-08-14', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(23, '2020-08-15', '2020-08-15', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(24, '2020-08-16', '2020-08-16', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(25, '2020-08-17', '2020-08-17', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(26, '2020-08-18', '2020-08-18', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(27, '2020-08-19', '2020-08-19', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(28, '2020-08-20', '2020-08-20', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(29, '2020-08-21', '2020-08-21', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(30, '2020-08-22', '2020-08-22', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(31, '2020-08-23', '2020-08-23', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(32, '2020-08-24', '2020-08-24', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(33, '2020-08-25', '2020-08-25', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(34, '2020-08-26', '2020-08-26', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(35, '2020-08-27', '2020-08-27', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(36, '2020-08-28', '2020-08-28', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(37, '2020-08-29', '2020-08-29', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(38, '2020-08-30', '2020-08-30', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(39, '2020-08-31', '2020-08-31', '07:00:00', '13:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(40, '2020-08-05', '2020-08-05', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(41, '2020-08-06', '2020-08-06', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(42, '2020-08-07', '2020-08-07', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(43, '2020-08-08', '2020-08-08', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(44, '2020-08-09', '2020-08-09', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(45, '2020-08-10', '2020-08-10', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(46, '2020-08-11', '2020-08-11', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(47, '2020-08-12', '2020-08-12', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(48, '2020-08-13', '2020-08-13', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(49, '2020-08-14', '2020-08-14', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(50, '2020-08-15', '2020-08-15', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(51, '2020-08-16', '2020-08-16', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(52, '2020-08-17', '2020-08-17', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(53, '2020-08-18', '2020-08-18', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(54, '2020-08-19', '2020-08-19', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(55, '2020-08-20', '2020-08-20', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(56, '2020-08-21', '2020-08-21', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(57, '2020-08-22', '2020-08-22', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(58, '2020-08-23', '2020-08-23', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(59, '2020-08-24', '2020-08-24', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(60, '2020-08-25', '2020-08-25', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(61, '2020-08-26', '2020-08-26', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(62, '2020-08-27', '2020-08-27', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(63, '2020-08-28', '2020-08-28', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(64, '2020-08-29', '2020-08-29', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(65, '2020-08-30', '2020-08-30', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(66, '2020-08-31', '2020-08-31', '13:00:00', '19:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(67, '2020-08-05', '2020-08-06', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(68, '2020-08-07', '2020-08-08', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(69, '2020-08-09', '2020-08-10', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(70, '2020-08-11', '2020-08-12', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(71, '2020-08-13', '2020-08-14', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(72, '2020-08-15', '2020-08-16', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(73, '2020-08-17', '2020-08-18', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(74, '2020-08-19', '2020-08-20', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(75, '2020-08-21', '2020-08-22', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(76, '2020-08-23', '2020-08-24', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(77, '2020-08-25', '2020-08-26', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(78, '2020-08-27', '2020-08-28', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(79, '2020-08-29', '2020-08-30', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0),
(80, '2020-08-31', '2020-09-01', '19:00:00', '07:00:00', 0, 0, 5, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `bgcolor` char(7) NOT NULL DEFAULT '#607D8B',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `bgcolor`) VALUES
(1, 'admin', 'Administrator', '#009688'),
(2, 'members', 'General User', '#2196F3'),
(3, 'profissionais', 'Profissionais Médicos', '#607D8B');

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissionais`
--

DROP TABLE IF EXISTS `profissionais`;
CREATE TABLE IF NOT EXISTS `profissionais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registro` varchar(10) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `profissionais`
--

INSERT INTO `profissionais` (`id`, `registro`, `nome`, `email`, `active`) VALUES
(1, '0001', 'Dieison Rabêlo', 'dieisonroberto@gmail.com', 1),
(2, '15461', 'Breno Douglas Dantas Oliveira', 'brenodouglas@hotmail.com', 1),
(3, '5409', 'Frederico Carlos de Sousa Arnaud', 'frederico.arnaud@hotmail.com', 1),
(4, '13290', 'Natalia Suelen Braga da Silva', 'nataliasuellen@gmail.com', 1),
(5, '17236', 'Francisco Maximiano Nunes Moura', 'maximiano.moura@gmail.com', 1),
(6, '16095', 'Cicero Thiago Lopes de Sousa', 'tiago_lop@hotmail.com', 1),
(7, '16660', 'Diego Antunes Silveira', 'diego-asilveira@hotmail.com', 1),
(8, '16950', 'Larissa Clara Lopes de Souza', 'larissaclarals@hotmail.com', 1),
(9, '16967', 'Rubens Lopes Sabino', 'rubensabino87@gmail.com', 1),
(10, '11173', 'Francisco Daniel Cavalcante Vidal', 'fcodanielcavalcante@gmail.com', 1),
(11, '10278', 'Rohden Leite Varela Filho', 'rohdenvarela@yahoo.com.br', 1),
(12, '8089', 'Antonio Murilo de Souza Almeida', 'murillus@yahoo.com', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissionalsetor`
--

DROP TABLE IF EXISTS `profissionalsetor`;
CREATE TABLE IF NOT EXISTS `profissionalsetor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profissional_id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `profissionalsetor`
--

INSERT INTO `profissionalsetor` (`id`, `profissional_id`, `setor_id`) VALUES
(1, 2, 5),
(2, 3, 5),
(3, 4, 5),
(4, 5, 5),
(5, 6, 5),
(6, 7, 5),
(7, 8, 5),
(8, 9, 5),
(9, 10, 5),
(10, 11, 5),
(11, 12, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `public_preferences`
--

DROP TABLE IF EXISTS `public_preferences`;
CREATE TABLE IF NOT EXISTS `public_preferences` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `transition_page` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `public_preferences`
--

INSERT INTO `public_preferences` (`id`, `transition_page`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `setores`
--

DROP TABLE IF EXISTS `setores`;
CREATE TABLE IF NOT EXISTS `setores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `unidadehospitalar_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `setores`
--

INSERT INTO `setores` (`id`, `nome`, `unidadehospitalar_id`, `active`) VALUES
(1, 'Setor de Hemodinamica', 1, 0),
(2, 'Posto 1', 1, 0),
(3, 'Posto 2', 1, 0),
(4, 'Posto 3', 1, 0),
(5, 'Chefia de Equipe Emergência', 1, 0),
(6, 'Consultório 1 PA', 3, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidadeshospitalares`
--

DROP TABLE IF EXISTS `unidadeshospitalares`;
CREATE TABLE IF NOT EXISTS `unidadeshospitalares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(14) NOT NULL,
  `razaosocial` varchar(256) NOT NULL,
  `nomefantasia` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `unidadeshospitalares`
--

INSERT INTO `unidadeshospitalares` (`id`, `cnpj`, `razaosocial`, `nomefantasia`, `active`) VALUES
(1, '07954571002239', 'Hospital Dr Carlos Alberto Studart Gomes', 'Hospital de Messejana', 1),
(2, '07954571003553', 'Hospital Sao Jose de Doencas Infecciosas de Fortaleza', 'Hospital Sao Jose', 0),
(3, '07954571001429', 'Hospital Geral de Fortaleza', 'HGF', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, '8OqtoW5GKnMG7HypIlbiSe', 1268889823, 1595953654, 1, 'Admin', 'istrator', 'ADMIN', '0'),
(2, '192.168.0.118', 'dieison rabelo', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', NULL, 'dieisonroberto@gmail.com', NULL, NULL, NULL, NULL, 1594901668, 1595617567, 1, 'dieison', 'rabelo', 'Cemerge', '85996629121'),
(3, '192.168.0.118', 'breno dantas', '$2y$08$mEDXLtp76J/OI7egeNweZ.QuNyBUtDJS/vzKmj.n.1iCsArzKW2QS', NULL, 'brenodouglas@hotmail.com', NULL, NULL, NULL, NULL, 1595616292, 1595962867, 1, 'Breno', 'Dantas', 'Cemerge', '85996629121'),
(4, '::1', 'natalia silva', '$2y$08$WNSNHTFKjSrPepsMOoPFVOVjDACMFUnkBkWqxWJs72r6cbB3YMDfK', NULL, 'nataliasuellen@gmail.com', NULL, NULL, NULL, NULL, 1595965323, NULL, 1, 'Natalia', 'Silva', 'Cemerge', '8599999999');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(4, 2, 2),
(5, 2, 3),
(8, 3, 3),
(11, 4, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuariosprofissionais`
--

DROP TABLE IF EXISTS `usuariosprofissionais`;
CREATE TABLE IF NOT EXISTS `usuariosprofissionais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profissional_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuariosprofissionais`
--

INSERT INTO `usuariosprofissionais` (`id`, `profissional_id`, `user_id`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_escalas`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_escalas`;
CREATE TABLE IF NOT EXISTS `vw_escalas` (
`id` int(11)
,`dataplantao` date
,`horainicialplantao` time
,`horafinalplantao` time
,`duracao` int(11)
,`profissional_id` int(11)
,`setor_id` int(11)
,`tipopassagem` int(11)
,`datahorapassagem` datetime
,`statuspassagem` tinyint(4)
,`profissionalsubstituto_id` int(11)
,`registroprofissional` varchar(10)
,`nomeprofissional` varchar(256)
,`registroprofissionalsubstituto` varchar(10)
,`nomeprofissionalsubstituto` varchar(256)
,`nomesetor` varchar(255)
,`unidadehospitalar_id` int(11)
,`cnpj` varchar(14)
,`razaosocial` varchar(256)
,`nomefantasia` varchar(256)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_profissionais_setor`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_profissionais_setor`;
CREATE TABLE IF NOT EXISTS `vw_profissionais_setor` (
`id` int(11)
,`registro` varchar(10)
,`nome` varchar(256)
,`setor_id` int(11)
,`nomesetor` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_escalas`
--
DROP TABLE IF EXISTS `vw_escalas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_escalas`  AS  (select `e`.`id` AS `id`,`e`.`dataplantao` AS `dataplantao`,`e`.`horainicialplantao` AS `horainicialplantao`,`e`.`horafinalplantao` AS `horafinalplantao`,`e`.`duracao` AS `duracao`,`e`.`profissional_id` AS `profissional_id`,`e`.`setor_id` AS `setor_id`,`e`.`tipopassagem` AS `tipopassagem`,`e`.`datahorapassagem` AS `datahorapassagem`,`e`.`statuspassagem` AS `statuspassagem`,`e`.`profissionalsubstituto_id` AS `profissionalsubstituto_id`,`p`.`registro` AS `registroprofissional`,`p`.`nome` AS `nomeprofissional`,`ps`.`registro` AS `registroprofissionalsubstituto`,`ps`.`nome` AS `nomeprofissionalsubstituto`,`s`.`nome` AS `nomesetor`,`s`.`unidadehospitalar_id` AS `unidadehospitalar_id`,`u`.`cnpj` AS `cnpj`,`u`.`razaosocial` AS `razaosocial`,`u`.`nomefantasia` AS `nomefantasia` from ((((`escalas` `e` left join `profissionais` `p` on((`e`.`profissional_id` = `p`.`id`))) left join `profissionais` `ps` on((`e`.`profissionalsubstituto_id` = `ps`.`id`))) join `setores` `s` on((`e`.`setor_id` = `s`.`id`))) join `unidadeshospitalares` `u` on((`s`.`unidadehospitalar_id` = `u`.`id`)))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_profissionais_setor`
--
DROP TABLE IF EXISTS `vw_profissionais_setor`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_profissionais_setor`  AS  (select `p`.`id` AS `id`,`p`.`registro` AS `registro`,`p`.`nome` AS `nome`,`s`.`id` AS `setor_id`,`s`.`nome` AS `nomesetor` from ((`profissionais` `p` join `profissionalsetor` `ps` on((`p`.`id` = `ps`.`profissional_id`))) join `setores` `s` on((`s`.`id` = `ps`.`setor_id`)))) ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
