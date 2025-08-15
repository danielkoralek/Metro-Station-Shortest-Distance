-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 03-Dez-2016 às 13:41
-- Versão do servidor: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metrodan`
--

-- --------------------------------------------------------

DROP DATABASE IF EXISTS `metrodan`;

CREATE DATABASE IF NOT EXISTS `metrodan` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `metrodan`;

--
-- Estrutura da tabela `station_connection`
--

CREATE TABLE `station_index` (
  `idstation` int(11) NOT NULL,
  `nmstation` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `station_connection` (
  `idbasestation` int(11) NOT NULL COMMENT 'Id of the base Station',
  `idconnstation` int(11) NOT NULL COMMENT 'Id of the Connected Station',
  `vertex_cost` int(11) NOT NULL COMMENT 'Vertex Cost in Time or Distance'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE `station_connection`
  ADD PRIMARY KEY (`idbasestation`,`idconnstation`),
  ADD KEY `fk_connection_conn_station` (`idconnstation`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
