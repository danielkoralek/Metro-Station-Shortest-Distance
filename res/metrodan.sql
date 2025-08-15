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

CREATE TABLE `station_connection` (
  `base_station` varchar(50) NOT NULL ,
  `conn_station` varchar(50) NOT NULL ,
  `vertex_cost` int(11) NOT NULL COMMENT 'Vertex Cost in Time or Distance',
  PRIMARY KEY pk_station_connection ( base_station, conn_station )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
