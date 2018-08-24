-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-08-2018 a las 05:28:39
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_history`
--

CREATE TABLE `student_history` (
  `history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL COMMENT 'Id del alumno',
  `student_group` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Grupo al que pertenecia',
  `student_init_date` date NOT NULL COMMENT 'fecha en que inicio el curso',
  `student_end_date` date NOT NULL COMMENT 'fecha en que termino el curso',
  `teacher_group` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Maestro del curso',
  `student_school` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Escuela al que pertenecia',
  `student_becado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'si estaba becado',
  `student_sponsor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'numbre del padrino',
  `student_age` tinyint(4) UNSIGNED NOT NULL COMMENT 'edad durante el curso',
  `ciclo` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ciclo escolar',
  `student_grade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Grado de estudios',
  `student_sep` tinyint(1) NOT NULL COMMENT 'si estaba registrado en la sep',
  `created_at` datetime NOT NULL COMMENT 'fecha de creacion del registro',
  PRIMARY KEY (`id_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
