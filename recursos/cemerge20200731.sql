-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31/07/2020 às 22:01
-- Versão do servidor: 10.1.38-MariaDB
-- Versão do PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cemerge`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_preferences`
--

CREATE TABLE `admin_preferences` (
  `id` tinyint(1) NOT NULL,
  `user_panel` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar_form` tinyint(1) NOT NULL DEFAULT '0',
  `messages_menu` tinyint(1) NOT NULL DEFAULT '0',
  `notifications_menu` tinyint(1) NOT NULL DEFAULT '0',
  `tasks_menu` tinyint(1) NOT NULL DEFAULT '0',
  `user_menu` tinyint(1) NOT NULL DEFAULT '1',
  `ctrl_sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `transition_page` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `admin_preferences`
--

INSERT INTO `admin_preferences` (`id`, `user_panel`, `sidebar_form`, `messages_menu`, `notifications_menu`, `tasks_menu`, `user_menu`, `ctrl_sidebar`, `transition_page`) VALUES
(1, 0, 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20200720113403', '2020-07-20 13:40:49', 1896);

-- --------------------------------------------------------

--
-- Estrutura para tabela `escalas`
--

CREATE TABLE `escalas` (
  `id` int(11) NOT NULL,
  `dataplantao` date NOT NULL,
  `datafinalplantao` date NOT NULL,
  `horainicialplantao` time NOT NULL,
  `horafinalplantao` time NOT NULL,
  `duracao` int(11) NOT NULL,
  `profissional_id` int(11) DEFAULT '0',
  `setor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `escalas`
--

INSERT INTO `escalas` (`id`, `dataplantao`, `datafinalplantao`, `horainicialplantao`, `horafinalplantao`, `duracao`, `profissional_id`, `setor_id`) VALUES
(1, '2020-08-01', '2020-08-01', '07:00:00', '13:00:00', 6, 4, 5),
(2, '2020-08-01', '2020-08-01', '13:00:00', '19:00:00', 6, 4, 5),
(3, '2020-08-01', '2020-08-02', '19:00:00', '07:00:00', 12, 5, 5),
(4, '2020-08-02', '2020-08-03', '19:00:00', '07:00:00', 12, 6, 5),
(5, '2020-08-02', '2020-08-02', '07:00:00', '13:00:00', 6, 6, 5),
(6, '2020-08-02', '2020-08-02', '13:00:00', '19:00:00', 6, 6, 5),
(7, '2020-08-03', '2020-08-03', '07:00:00', '13:00:00', 0, 7, 5),
(8, '2020-08-03', '2020-08-03', '13:00:00', '19:00:00', 0, 11, 5),
(9, '2020-08-03', '2020-08-04', '19:00:00', '07:00:00', 0, 12, 5),
(10, '2020-08-04', '2020-08-04', '07:00:00', '13:00:00', 0, 8, 5),
(11, '2020-08-04', '2020-08-04', '13:00:00', '19:00:00', 0, 11, 5),
(12, '2020-08-04', '2020-08-05', '19:00:00', '07:00:00', 0, 2, 5),
(13, '2020-08-05', '2020-08-05', '07:00:00', '13:00:00', 0, 0, 5),
(14, '2020-08-06', '2020-08-06', '07:00:00', '13:00:00', 0, 0, 5),
(15, '2020-08-07', '2020-08-07', '07:00:00', '13:00:00', 0, 0, 5),
(16, '2020-08-08', '2020-08-08', '07:00:00', '13:00:00', 0, 0, 5),
(17, '2020-08-09', '2020-08-09', '07:00:00', '13:00:00', 0, 0, 5),
(18, '2020-08-10', '2020-08-10', '07:00:00', '13:00:00', 0, 0, 5),
(19, '2020-08-11', '2020-08-11', '07:00:00', '13:00:00', 0, 0, 5),
(20, '2020-08-12', '2020-08-12', '07:00:00', '13:00:00', 0, 0, 5),
(21, '2020-08-13', '2020-08-13', '07:00:00', '13:00:00', 0, 0, 5),
(22, '2020-08-14', '2020-08-14', '07:00:00', '13:00:00', 0, 0, 5),
(23, '2020-08-15', '2020-08-15', '07:00:00', '13:00:00', 0, 0, 5),
(24, '2020-08-16', '2020-08-16', '07:00:00', '13:00:00', 0, 0, 5),
(25, '2020-08-17', '2020-08-17', '07:00:00', '13:00:00', 0, 0, 5),
(26, '2020-08-18', '2020-08-18', '07:00:00', '13:00:00', 0, 0, 5),
(27, '2020-08-19', '2020-08-19', '07:00:00', '13:00:00', 0, 0, 5),
(28, '2020-08-20', '2020-08-20', '07:00:00', '13:00:00', 0, 0, 5),
(29, '2020-08-21', '2020-08-21', '07:00:00', '13:00:00', 0, 0, 5),
(30, '2020-08-22', '2020-08-22', '07:00:00', '13:00:00', 0, 0, 5),
(31, '2020-08-23', '2020-08-23', '07:00:00', '13:00:00', 0, 0, 5),
(32, '2020-08-24', '2020-08-24', '07:00:00', '13:00:00', 0, 0, 5),
(33, '2020-08-25', '2020-08-25', '07:00:00', '13:00:00', 0, 0, 5),
(34, '2020-08-26', '2020-08-26', '07:00:00', '13:00:00', 0, 0, 5),
(35, '2020-08-27', '2020-08-27', '07:00:00', '13:00:00', 0, 0, 5),
(36, '2020-08-28', '2020-08-28', '07:00:00', '13:00:00', 0, 0, 5),
(37, '2020-08-29', '2020-08-29', '07:00:00', '13:00:00', 0, 0, 5),
(38, '2020-08-30', '2020-08-30', '07:00:00', '13:00:00', 0, 0, 5),
(39, '2020-08-31', '2020-08-31', '07:00:00', '13:00:00', 0, 0, 5),
(40, '2020-08-05', '2020-08-05', '13:00:00', '19:00:00', 0, 0, 5),
(41, '2020-08-06', '2020-08-06', '13:00:00', '19:00:00', 0, 0, 5),
(42, '2020-08-07', '2020-08-07', '13:00:00', '19:00:00', 0, 0, 5),
(43, '2020-08-08', '2020-08-08', '13:00:00', '19:00:00', 0, 0, 5),
(44, '2020-08-09', '2020-08-09', '13:00:00', '19:00:00', 0, 0, 5),
(45, '2020-08-10', '2020-08-10', '13:00:00', '19:00:00', 0, 0, 5),
(46, '2020-08-11', '2020-08-11', '13:00:00', '19:00:00', 0, 0, 5),
(47, '2020-08-12', '2020-08-12', '13:00:00', '19:00:00', 0, 0, 5),
(48, '2020-08-13', '2020-08-13', '13:00:00', '19:00:00', 0, 0, 5),
(49, '2020-08-14', '2020-08-14', '13:00:00', '19:00:00', 0, 0, 5),
(50, '2020-08-15', '2020-08-15', '13:00:00', '19:00:00', 0, 0, 5),
(51, '2020-08-16', '2020-08-16', '13:00:00', '19:00:00', 0, 0, 5),
(52, '2020-08-17', '2020-08-17', '13:00:00', '19:00:00', 0, 0, 5),
(53, '2020-08-18', '2020-08-18', '13:00:00', '19:00:00', 0, 0, 5),
(54, '2020-08-19', '2020-08-19', '13:00:00', '19:00:00', 0, 0, 5),
(55, '2020-08-20', '2020-08-20', '13:00:00', '19:00:00', 0, 0, 5),
(56, '2020-08-21', '2020-08-21', '13:00:00', '19:00:00', 0, 0, 5),
(57, '2020-08-22', '2020-08-22', '13:00:00', '19:00:00', 0, 0, 5),
(58, '2020-08-23', '2020-08-23', '13:00:00', '19:00:00', 0, 0, 5),
(59, '2020-08-24', '2020-08-24', '13:00:00', '19:00:00', 0, 0, 5),
(60, '2020-08-25', '2020-08-25', '13:00:00', '19:00:00', 0, 0, 5),
(61, '2020-08-26', '2020-08-26', '13:00:00', '19:00:00', 0, 0, 5),
(62, '2020-08-27', '2020-08-27', '13:00:00', '19:00:00', 0, 0, 5),
(63, '2020-08-28', '2020-08-28', '13:00:00', '19:00:00', 0, 0, 5),
(64, '2020-08-29', '2020-08-29', '13:00:00', '19:00:00', 0, 0, 5),
(65, '2020-08-30', '2020-08-30', '13:00:00', '19:00:00', 0, 0, 5),
(66, '2020-08-31', '2020-08-31', '13:00:00', '19:00:00', 0, 0, 5),
(67, '2020-08-05', '2020-08-06', '19:00:00', '07:00:00', 0, 0, 5),
(68, '2020-08-07', '2020-08-08', '19:00:00', '07:00:00', 0, 0, 5),
(69, '2020-08-09', '2020-08-10', '19:00:00', '07:00:00', 0, 0, 5),
(70, '2020-08-11', '2020-08-12', '19:00:00', '07:00:00', 0, 0, 5),
(71, '2020-08-13', '2020-08-14', '19:00:00', '07:00:00', 0, 0, 5),
(72, '2020-08-15', '2020-08-16', '19:00:00', '07:00:00', 0, 0, 5),
(73, '2020-08-17', '2020-08-18', '19:00:00', '07:00:00', 0, 0, 5),
(74, '2020-08-19', '2020-08-20', '19:00:00', '07:00:00', 0, 0, 5),
(75, '2020-08-21', '2020-08-22', '19:00:00', '07:00:00', 0, 0, 5),
(76, '2020-08-23', '2020-08-24', '19:00:00', '07:00:00', 0, 0, 5),
(77, '2020-08-25', '2020-08-26', '19:00:00', '07:00:00', 0, 0, 5),
(78, '2020-08-27', '2020-08-28', '19:00:00', '07:00:00', 0, 0, 5),
(79, '2020-08-29', '2020-08-30', '19:00:00', '07:00:00', 0, 0, 5),
(80, '2020-08-31', '2020-09-01', '19:00:00', '07:00:00', 0, 0, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `bgcolor` char(7) NOT NULL DEFAULT '#607D8B'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `bgcolor`) VALUES
(1, 'admin', 'Administrator', '#009688'),
(2, 'members', 'Usuário comum', '#2196f3'),
(3, 'profissionais', 'Profissionais Médicos', '#607D8B');

-- --------------------------------------------------------

--
-- Estrutura para tabela `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(3, '192.168.45.57', 'dieisonroberto@gmail.comm', 1596133398);

-- --------------------------------------------------------

--
-- Estrutura para tabela `passagenstrocas`
--

CREATE TABLE `passagenstrocas` (
  `id` int(11) NOT NULL,
  `escala_id` int(11) NOT NULL,
  `profissional_id` int(11) NOT NULL DEFAULT '0',
  `profissionalsubstituto_id` int(11) NOT NULL DEFAULT '0',
  `tipopassagem` tinyint(4) NOT NULL,
  `datahorapassagem` datetime NOT NULL,
  `datahoraconfirmacao` datetime DEFAULT NULL,
  `statuspassagem` tinyint(4) DEFAULT NULL,
  `escalatroca_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `passagenstrocas`
--

INSERT INTO `passagenstrocas` (`id`, `escala_id`, `profissional_id`, `profissionalsubstituto_id`, `tipopassagem`, `datahorapassagem`, `datahoraconfirmacao`, `statuspassagem`, `escalatroca_id`) VALUES
(1, 12, 2, 12, 0, '2020-07-31 16:41:28', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissionais`
--

CREATE TABLE `profissionais` (
  `id` int(11) NOT NULL,
  `registro` varchar(10) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `profissionais`
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
(12, '8089', 'Antonio Murilo de Souza Almeida', 'murillus@yahoo.com', 1),
(13, '17168', 'Alisson David Ribeiro', 'alissondribeiro@gmail.com', 1),
(14, '18278', 'Jayranne Mara Santana dos Santos', 'jayrannemara@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissionalsetor`
--

CREATE TABLE `profissionalsetor` (
  `id` int(11) NOT NULL,
  `profissional_id` int(11) NOT NULL,
  `setor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `profissionalsetor`
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
-- Estrutura para tabela `public_preferences`
--

CREATE TABLE `public_preferences` (
  `id` int(1) NOT NULL,
  `transition_page` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `public_preferences`
--

INSERT INTO `public_preferences` (`id`, `transition_page`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `unidadehospitalar_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `setores`
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
-- Estrutura para tabela `unidadeshospitalares`
--

CREATE TABLE `unidadeshospitalares` (
  `id` int(11) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `razaosocial` varchar(256) NOT NULL,
  `nomefantasia` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `unidadeshospitalares`
--

INSERT INTO `unidadeshospitalares` (`id`, `cnpj`, `razaosocial`, `nomefantasia`, `active`) VALUES
(1, '07954571002239', 'Hospital Dr Carlos Alberto Studart Gomes', 'Hospital de Messejana', 1),
(2, '07954571003553', 'Hospital Sao Jose de Doencas Infecciosas de Fortaleza', 'Hospital Sao Jose', 0),
(3, '07954571001429', 'Hospital Geral de Fortaleza', 'HGF', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
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
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$08$JGhESAD5mFbc5bJ2r8tDXuk1J80zA8S6G.5HcFTM1HvwD3C8g12FW', '', 'admin@cemerge.com.br', '', NULL, NULL, NULL, 1268889823, 1596217791, 1, 'Admin', 'istrator', 'Cemerge', '0'),
(2, '192.168.0.118', 'dieison rabelo', '$2y$08$pO88rd0dZ0hRtLk3T7fU3ulLzhHbyKOi6cj8A16SlQX/JyLSaVM7i', NULL, 'dieisonroberto@gmail.com', NULL, NULL, NULL, NULL, 1594901668, 1596201506, 1, 'Dieison', 'Rabelo', 'Cemerge', '85996629121'),
(3, '192.168.0.118', 'breno dantas', '$2y$08$mEDXLtp76J/OI7egeNweZ.QuNyBUtDJS/vzKmj.n.1iCsArzKW2QS', NULL, 'brenodouglas@hotmail.com', NULL, NULL, NULL, NULL, 1595616292, 1596201350, 1, 'Breno', 'Dantas', 'Cemerge', '85996629121'),
(4, '::1', 'natalia silva', '$2y$08$wOFl75yMfkwz8sjcB37TqePaswhqv5pu2EOzY/Foxhx.anPQdlwda', NULL, 'nataliasuellen@gmail.com', NULL, NULL, NULL, NULL, 1595965323, 1596046111, 1, 'Natalia', 'Silva', 'Cemerge', '8599999999'),
(5, '::1', 'fred arnaud', '$2y$08$B/hjxH8Z4FhmBP1Y0hIo..UZNrh/lqI4IcQ68bEVb06teq9dnyVeG', NULL, 'frederico.arnaud@hotmail.com', NULL, NULL, NULL, NULL, 1596023643, 1596023704, 1, 'Fred', 'Arnaud', 'Cemerge', '85999999999'),
(6, '192.168.45.57', 'maximiano moura', '$2y$08$yNx8mslnfzDDXtafcYeBVeGz9L8OUE3ZZfmSK6XIbXBZTfdZCaPeK', NULL, 'maximiano.moura@gmail.com', NULL, NULL, NULL, NULL, 1596217028, NULL, 1, 'Maximiano', 'Moura', 'Cemerge', '85987979195'),
(7, '192.168.45.57', 'cicero lopes', '$2y$08$.DMY1FyHD2nZPvl1RjXmxunmhVF2wqmM50BlGMRyihzjOPP6nyxEe', NULL, 'tiago_lop@hotmail.com', NULL, NULL, NULL, NULL, 1596217377, NULL, 1, 'Cicero', 'Lopes', 'Cemerge', '85988768180'),
(8, '192.168.45.57', 'diego silveira', '$2y$08$leJzc4Umo11PZ8lOiCrzo.OnpmUh5tURB5UfOt/4RyNN3E1tkBb9m', NULL, 'diego-asilveira@hotmail.com', NULL, NULL, NULL, NULL, 1596217438, NULL, 1, 'Diego', 'Silveira', 'Cemerge', '85999692222'),
(9, '192.168.45.57', 'larissa souza', '$2y$08$PLmdEFLsaOp6lfdCbQQSIufMiERNXsKofSdZvvmuhQESBreZ8qJq.', NULL, 'larissaclarals@hotmail.com', NULL, NULL, NULL, NULL, 1596217601, NULL, 1, 'Larissa', 'Souza', 'Cemerge', '85986044931'),
(10, '192.168.45.57', 'rubens sabino', '$2y$08$XVGajFqUEStQx/xXBkuBc.5qO.MAjTh9Sn3W16hHGvjqTcJCwe3em', NULL, 'rubensabino87@gmail.com', NULL, NULL, NULL, NULL, 1596217769, NULL, 1, 'Rubens', 'Sabino', 'Cemerge', '85988169427'),
(11, '192.168.45.57', 'francisco daniel', '$2y$08$2tqm051jNv88STmktGj1Zeh.WypojrCmIQwGHG1vTH9D.g.LgvwKy', NULL, 'fcodanielcavalcante@gmail.com', NULL, NULL, NULL, NULL, 1596217965, NULL, 1, 'Francisco', 'Daniel', 'Cemerge', '85991425138'),
(12, '192.168.45.57', 'rohden varela', '$2y$08$/cez1K8BBNRU39000I8m/unhET7oBKwpDlcZKMV9k3wGKajjE13HO', NULL, 'rohdenvarela@yahoo.com.br', NULL, NULL, NULL, NULL, 1596218042, NULL, 1, 'Rohden', 'Varela', 'Cemerge', '85986504426'),
(13, '192.168.45.57', 'murilo almeida', '$2y$08$qoiw2IcPTZquK0qB17KbPuU.iq2mcB0p4Kha26uh5IbmQf8TdcGOO', NULL, 'murillus@yahoo.com', NULL, NULL, NULL, NULL, 1596218228, NULL, 1, 'Murilo', 'Almeida', 'Cemerge', '85988088089'),
(14, '192.168.45.57', 'alisson ribeiro', '$2y$08$cMOtf90sFEBOKl4Z5LDJn.kzT5RCNRga8AfF0xCNOWsySmTx71S4e', NULL, 'alissondribeiro@gmail.com', NULL, NULL, NULL, NULL, 1596218335, NULL, 1, 'Alisson', 'Ribeiro', 'Cemerge', '85981137002'),
(15, '192.168.45.57', 'jayranne mara', '$2y$08$uPsGlLDK/Rylbq1llmBhfOqaug5OFQwr/mBtspmxGiV.KQxGEXYh6', NULL, 'jayrannemara@gmail.com', NULL, NULL, NULL, NULL, 1596218452, NULL, 1, 'Jayranne', 'Mara', 'Cemerge', '86999296044');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(18, 2, 1),
(19, 2, 2),
(20, 2, 3),
(8, 3, 3),
(41, 4, 3),
(42, 5, 3),
(43, 6, 3),
(23, 7, 3),
(25, 8, 3),
(28, 9, 3),
(30, 10, 3),
(32, 11, 3),
(34, 12, 3),
(36, 13, 3),
(38, 14, 3),
(40, 15, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuariosprofissionais`
--

CREATE TABLE `usuariosprofissionais` (
  `id` int(11) NOT NULL,
  `profissional_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `usuariosprofissionais`
--

INSERT INTO `usuariosprofissionais` (`id`, `profissional_id`, `user_id`) VALUES
(1, 2, 3),
(2, 4, 4),
(3, 3, 5),
(4, 1, 2),
(5, 5, 6),
(6, 6, 7),
(7, 7, 8),
(8, 8, 9),
(9, 9, 10),
(10, 10, 11),
(11, 11, 12),
(12, 12, 13),
(13, 13, 14),
(14, 14, 15);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `admin_preferences`
--
ALTER TABLE `admin_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Índices de tabela `escalas`
--
ALTER TABLE `escalas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `passagenstrocas`
--
ALTER TABLE `passagenstrocas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `profissionais`
--
ALTER TABLE `profissionais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `profissionalsetor`
--
ALTER TABLE `profissionalsetor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `public_preferences`
--
ALTER TABLE `public_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `unidadeshospitalares`
--
ALTER TABLE `unidadeshospitalares`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Índices de tabela `usuariosprofissionais`
--
ALTER TABLE `usuariosprofissionais`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `admin_preferences`
--
ALTER TABLE `admin_preferences`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `escalas`
--
ALTER TABLE `escalas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `passagenstrocas`
--
ALTER TABLE `passagenstrocas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `profissionais`
--
ALTER TABLE `profissionais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `profissionalsetor`
--
ALTER TABLE `profissionalsetor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `public_preferences`
--
ALTER TABLE `public_preferences`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `unidadeshospitalares`
--
ALTER TABLE `unidadeshospitalares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `usuariosprofissionais`
--
ALTER TABLE `usuariosprofissionais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
