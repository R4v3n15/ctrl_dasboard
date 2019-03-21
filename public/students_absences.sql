-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2019 a las 06:24:44
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
-- Estructura de tabla para la tabla `students_absences`
--

CREATE TABLE `students_absences` (
  `absence_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL,
  `course` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horary` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_id` int(10) NOT NULL,
  `absence_note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_note` text COLLATE utf8mb4_unicode_ci,
  `absence_date` date DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `students_absences`
--
ALTER TABLE `students_absences`
  ADD PRIMARY KEY (`absence_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `students_absences`
--
ALTER TABLE `students_absences`
  MODIFY `absence_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
