-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: control
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id_address` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type` int(5) NOT NULL COMMENT '1=tutor, 2=alumno',
  `street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `st_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `st_between` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colony` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitud` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_address`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `becas`
--

DROP TABLE IF EXISTS `becas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `becas` (
  `beca_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) DEFAULT NULL,
  `sponsor_id` int(10) DEFAULT NULL,
  `applicant` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Solicitante?',
  `granted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Becado?',
  `year` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Año',
  `ciclo` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'En que ciclo fue becado',
  `percentage` int(10) DEFAULT NULL,
  `applicant_at` date DEFAULT NULL COMMENT 'Fecha en que solicito beca',
  `granted_at` date DEFAULT NULL,
  `emailed` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Ya envio email al sponsor?',
  PRIMARY KEY (`beca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `becas`
--

LOCK TABLES `becas` WRITE;
/*!40000 ALTER TABLE `becas` DISABLE KEYS */;
/*!40000 ALTER TABLE `becas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `schedul_id` int(11) unsigned NOT NULL,
  `teacher_id` int(11) unsigned DEFAULT NULL,
  `costo_normal` decimal(10,2) unsigned DEFAULT '0.00',
  `costo_promocional` decimal(10,2) unsigned DEFAULT '0.00',
  `costo_inscripcion` decimal(10,2) unsigned DEFAULT '0.00',
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,1,1,1,NULL,400.00,360.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(2,1,2,2,NULL,400.00,360.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(3,1,1,3,2,400.00,360.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(4,3,4,4,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(5,3,8,5,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(6,3,12,6,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(7,3,22,7,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(8,4,4,8,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(9,4,6,9,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:17','2018-05-08 04:26:17'),(10,4,12,10,NULL,450.00,40.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(11,4,10,11,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(12,4,16,12,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(13,4,2,13,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(14,4,28,14,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(15,4,5,15,NULL,425.00,385.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(16,4,7,16,NULL,425.00,385.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(17,4,11,17,NULL,425.00,385.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(18,4,15,18,NULL,425.00,385.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(19,4,24,19,NULL,425.00,385.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(20,1,10,20,3,400.00,360.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(21,3,6,21,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(22,3,2,22,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(23,3,4,23,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(24,3,2,24,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18'),(25,4,3,25,NULL,450.00,400.00,200.00,1,'2018-05-08 04:26:18','2018-05-08 04:26:18');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `course_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=Inactivo, 1=Activo',
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'ENGLISH CLUB','',1),(2,'BIG TOTS','description',1),(3,'PRIMARY','',1),(4,'ADOLESCENTES','',1),(5,'ADULTOS','',1),(6,'AVANZADO','',1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `days`
--

DROP TABLE IF EXISTS `days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `days` (
  `day_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`day_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `days`
--

LOCK TABLES `days` WRITE;
/*!40000 ALTER TABLE `days` DISABLE KEYS */;
INSERT INTO `days` VALUES (1,'LUNES'),(2,'MARTES'),(3,'MIERCOLES'),(4,'JUEVES'),(5,'VIERNES'),(6,'SABADO');
/*!40000 ALTER TABLE `days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'BIG TOTS',NULL),(2,'INICIAL',NULL),(3,'INICIAL SABADO',NULL),(4,'1A',NULL),(5,'1A SABADO',NULL),(6,'1B',NULL),(7,'1B SABADO',NULL),(8,'1C',NULL),(9,'1C SABADO',NULL),(10,'2A',NULL),(11,'2A SABADO',NULL),(12,'2B',NULL),(13,'2B SABADO',NULL),(14,'2C',NULL),(15,'2C SABADO',NULL),(16,'3A',NULL),(17,'3A SABADO',NULL),(18,'3B',NULL),(19,'3B SABADO',NULL),(20,'3C',NULL),(21,'3C SABADO',NULL),(22,'4A',NULL),(23,'4A SABADO',NULL),(24,'4B',NULL),(25,'4B SABADO',NULL),(26,'4C',NULL),(27,'4C SABADO',NULL),(28,'AVANZADO',NULL);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedul_days`
--

DROP TABLE IF EXISTS `schedul_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedul_days` (
  `schedul_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedul_days`
--

LOCK TABLES `schedul_days` WRITE;
/*!40000 ALTER TABLE `schedul_days` DISABLE KEYS */;
INSERT INTO `schedul_days` VALUES (1,2),(1,3),(1,4),(2,2),(2,3),(2,4),(3,2),(3,3),(3,4),(4,2),(4,4),(5,2),(5,4),(6,2),(6,4),(7,2),(7,4),(8,1),(8,3),(9,1),(9,3),(10,1),(10,3),(11,1),(11,3),(12,1),(12,3),(13,1),(13,3),(14,1),(14,3),(15,6),(16,6),(17,6),(18,6),(19,6),(20,2),(20,3),(20,4),(21,2),(21,4),(22,2),(22,4),(23,2),(23,4),(24,2),(24,4),(25,6),(27,1),(27,2),(27,3);
/*!40000 ALTER TABLE `schedul_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedules` (
  `schedul_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_init` date NOT NULL,
  `date_end` date NOT NULL,
  `hour_init` time NOT NULL,
  `hour_end` time NOT NULL,
  PRIMARY KEY (`schedul_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (1,'2017 A','2016-08-23','2016-12-06','15:00:00','16:00:00'),(2,'2017 A','2017-07-29','2018-07-16','17:30:00','18:30:00'),(3,'2017 A','2017-07-29','2018-07-16','15:00:00','16:00:00'),(4,'2017 A','2017-08-29','2018-07-16','18:30:00','20:00:00'),(5,'2017 A','2017-08-29','2018-07-16','16:30:00','18:00:00'),(6,'2017 A','2017-08-29','2018-07-16','18:30:00','20:00:00'),(7,'2017 A','2017-08-29','2018-07-16','16:30:00','18:00:00'),(8,'2018 A','2017-08-29','2018-02-16','16:30:00','18:00:00'),(9,'2017 A','2017-08-29','2018-07-16','15:00:00','16:30:00'),(10,'2017 A','2017-08-29','2018-07-16','19:30:00','20:30:00'),(11,'2017 A','2017-08-29','2018-07-16','15:00:00','16:30:00'),(12,'2017 A','2017-08-29','2018-07-16','19:30:00','20:30:00'),(13,'2017 A','2017-09-04','2018-07-16','16:30:00','18:00:00'),(14,'2017 A','2017-08-29','2018-07-16','19:30:00','20:30:00'),(15,'2017 A','2017-09-02','2018-07-14','12:30:00','15:00:00'),(16,'2017 A','2017-09-02','2018-07-14','12:30:00','15:00:00'),(17,'2017 A','2017-09-02','2018-07-14','12:30:00','15:00:00'),(18,'2017 A','2018-09-02','2018-07-14','09:30:00','12:00:00'),(19,'2016 B','2017-09-02','2018-07-14','09:30:00','12:00:00'),(20,'2017 A','2017-08-29','2018-07-16','16:00:00','17:00:00'),(21,'2017 A','2017-08-29','2018-07-16','15:00:00','16:30:00'),(22,'2017 A','2017-09-05','2018-06-21','16:30:00','18:00:00'),(23,'2017 A','2017-08-29','2018-08-21','15:00:00','16:30:00'),(24,'2017 A','2017-10-10','2018-06-21','18:30:00','20:00:00'),(25,'2017 A','2017-09-09','2018-06-23','09:30:00','12:00:00'),(27,'2018 B','2018-05-08','2018-05-30','16:30:00','17:30:00');
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sponsors` (
  `sponsor_id` int(11) NOT NULL AUTO_INCREMENT,
  `sp_name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_surname` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_type` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_description` varchar(280) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_status` tinyint(2) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de creacion',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'eliminado?',
  `deleted_at` datetime DEFAULT NULL COMMENT 'fecha de eliminacion',
  PRIMARY KEY (`sponsor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sponsors`
--

LOCK TABLES `sponsors` WRITE;
/*!40000 ALTER TABLE `sponsors` DISABLE KEYS */;
/*!40000 ALTER TABLE `sponsors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `student_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `age` tinyint(3) unsigned DEFAULT NULL,
  `genre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `edo_civil` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cellphone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sickness` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `medication` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_s` varchar(450) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1: Activo, 0:Baja, 2:en espera, 3:egresado',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: no eliminado, 1:eliminado',
  `deleted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha en el que fue eliminado',
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_details`
--

DROP TABLE IF EXISTS `students_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_details` (
  `detail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `convenio` tinyint(1) DEFAULT '0',
  `facturacion` tinyint(1) DEFAULT '0',
  `homestay` tinyint(1) DEFAULT '0',
  `acta_nacimiento` tinyint(1) DEFAULT '0',
  `ocupation` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workplace` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studies` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastgrade` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prior_course` tinyint(4) DEFAULT NULL,
  `prior_comments` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_details`
--

LOCK TABLES `students_details` WRITE;
/*!40000 ALTER TABLE `students_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_evaluations`
--

DROP TABLE IF EXISTS `students_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_evaluations` (
  `evaluation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de registro',
  `student_id` int(11) NOT NULL,
  `grade` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `period` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `bimestry` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `subjects` varchar(350) COLLATE utf8_spanish_ci NOT NULL,
  `date_evaluation` date NOT NULL,
  `read_achiev` smallint(6) NOT NULL,
  `write_achiev` smallint(6) NOT NULL,
  `speak_achiev` smallint(2) NOT NULL,
  `listen_achiev` smallint(2) NOT NULL,
  `read_effort` smallint(2) NOT NULL,
  `writ_effort` smallint(2) NOT NULL,
  `speak_effort` smallint(2) NOT NULL,
  `listen_effort` smallint(3) NOT NULL,
  `participation_effort` smallint(2) NOT NULL,
  `teamwork_effort` smallint(2) NOT NULL,
  `timing_effort` smallint(2) NOT NULL,
  `annotations` varchar(350) COLLATE utf8_spanish_ci NOT NULL,
  `teacher` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Nombre del Maestro',
  `tutor` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Nombre del tutor',
  PRIMARY KEY (`evaluation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Almacena las calificaciones de los alumnos por periodos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_evaluations`
--

LOCK TABLES `students_evaluations` WRITE;
/*!40000 ALTER TABLE `students_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_groups`
--

DROP TABLE IF EXISTS `students_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(11) unsigned DEFAULT NULL,
  `student_id` int(11) unsigned DEFAULT NULL,
  `date_begin` date DEFAULT NULL,
  `convenio` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:pendiente, 1:firmado',
  `status` tinyint(11) unsigned NOT NULL DEFAULT '1' COMMENT '0:inactivo, 1: activo',
  `year` smallint(5) unsigned DEFAULT NULL,
  `ciclo` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prior_course` char(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_groups`
--

LOCK TABLES `students_groups` WRITE;
/*!40000 ALTER TABLE `students_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_pays`
--

DROP TABLE IF EXISTS `students_pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_pays` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `ene` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No pagado, 1=pagado, 2=Becado, 3=No Aplica',
  `feb` tinyint(1) NOT NULL DEFAULT '0',
  `mar` tinyint(1) NOT NULL DEFAULT '0',
  `abr` tinyint(1) NOT NULL DEFAULT '0',
  `may` tinyint(1) NOT NULL DEFAULT '0',
  `jun` tinyint(1) NOT NULL DEFAULT '0',
  `jul` tinyint(1) NOT NULL DEFAULT '0',
  `becado_b` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Becado en el ciclo B?',
  `ago` tinyint(1) NOT NULL DEFAULT '0',
  `sep` tinyint(1) NOT NULL DEFAULT '0',
  `oct` tinyint(1) NOT NULL DEFAULT '0',
  `nov` tinyint(1) NOT NULL DEFAULT '0',
  `dic` tinyint(1) NOT NULL DEFAULT '0',
  `becado_a` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Becado en el ciclo A?',
  `year` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Anio',
  `ciclo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A o B',
  `comment` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_pays`
--

LOCK TABLES `students_pays` WRITE;
/*!40000 ALTER TABLE `students_pays` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_pays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_sep`
--

DROP TABLE IF EXISTS `students_sep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_sep` (
  `sep_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `sep_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_register` date DEFAULT NULL,
  `beca` int(11) NOT NULL DEFAULT '0',
  `period` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`sep_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_sep`
--

LOCK TABLES `students_sep` WRITE;
/*!40000 ALTER TABLE `students_sep` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_sep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students_sep_evaluations`
--

DROP TABLE IF EXISTS `students_sep_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students_sep_evaluations` (
  `id_evaluation` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `record` decimal(10,2) NOT NULL,
  `period` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_evaluation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students_sep_evaluations`
--

LOCK TABLES `students_sep_evaluations` WRITE;
/*!40000 ALTER TABLE `students_sep_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `students_sep_evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(5) unsigned NOT NULL,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `task` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` date NOT NULL,
  `date_todo` date NOT NULL,
  `priority` int(2) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutors`
--

DROP TABLE IF EXISTS `tutors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutors` (
  `id_tutor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `namet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surnamet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastnamet` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cellphone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relationship` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relationship_alt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tutor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutors`
--

LOCK TABLES `tutors` WRITE;
/*!40000 ALTER TABLE `tutors` DISABLE KEYS */;
/*!40000 ALTER TABLE `tutors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `session_id` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'stores session cookie id to prevent session concurrency',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` int(11) NOT NULL,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(254) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email, unique',
  `user_access_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Codigo de acceso',
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
  `user_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s deletion status',
  `user_account_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'user''s account type (basic, premium, etc)',
  `user_has_avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if user has a local avatar, 0 if not',
  `user_avatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Foto del usuario',
  `user_remember_me_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `user_creation_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the creation of user''s account',
  `user_suspension_timestamp` bigint(20) DEFAULT NULL COMMENT 'Timestamp till the end of a user suspension',
  `user_last_login_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of user''s last login',
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attempts',
  `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `user_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
  `user_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
  `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `user_provider_type` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'','Luis','Yama',777,'LuisYama','$2y$10$wZubnnm4w4k5hrStMKx5Z.FXzZJy3Ogkzxf5q4.Ze0LaHATF1jyZ6','jluis.yama@gmail.com','th3r4v3n',1,0,1,1,NULL,NULL,1497318197,NULL,1527904902,0,NULL,'c0e06a17c47e6149c0b1e16eed9b1d240f10ee5c',NULL,NULL,'DEFAULT'),(2,NULL,'CHRIS','',3,'chris','$2y$10$d5FuyMq7pm1grAcwtyBcpuY8XY.8Z1YWjzEjTmvjzI.yXbFcbqUnK','chris@test.com','OSORIO',1,0,1,0,'teacher_2',NULL,1525747036,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(3,NULL,'WENDY ','OSORIO ARANA',3,'wendy','$2y$10$drdKAph93YyM9ae2ji4z4OVKwmzpQiJ261Pp8mJgFq1UTPaktaKWq','wendy@test.com','CHRIS',1,0,1,0,'teacher_3',NULL,1525747036,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(4,NULL,'CESAR GERMAN','MANRIQUE MANZANERO',3,'cesar','$2y$10$5EVuT.B0zKoHjJnMjfnFKu2PjqYdbYccxxPanp3JjP6WmQyaGefiG','cesar@test.com','MANRIQUE',1,0,1,0,NULL,NULL,1525747036,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(5,NULL,'PAUL','ALLEN',3,'Paul','$2y$10$R8mK9Cy0P50/nrSslTPAkuiV33gZdB.rriqdCc5I89QNb6I6LatWy','paul@test.com','Allen',1,0,1,0,NULL,NULL,1525747036,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(6,'nt4tvpp11nc9rqvtnkjn0kd83b','Luis','admin',777,'admin','$2y$10$Ma2cqYIgJfVa1QI3NXGqIemK2C0jAAERMPpP8AZVkgr0qCM9xgwAq','admin@admin.com','admin',1,0,1,0,NULL,NULL,1526254465,NULL,1531237877,0,NULL,NULL,NULL,NULL,NULL),(7,'','Silvia','Diaz Rubio',1,'silvia','$2y$10$urOtS1Y083EFKSXia2.Ch.K5uJpMAIG.oSIpBJLIB4BaSEYJnwG5i','silvia@correo.com','12345',1,0,1,0,NULL,NULL,1527917586,NULL,1527918962,0,NULL,NULL,NULL,NULL,NULL),(8,NULL,'Sugeidy','Santiago',1,'suge','$2y$10$kZ6Jss/3EtwZhBCIoe7tyuvARI86fYLwDSEAwlMEKmMyjAlg./6Le','suge@correo.com','12345',1,0,1,0,NULL,NULL,1527917657,NULL,1527917786,0,NULL,NULL,NULL,NULL,NULL),(9,NULL,'Claudia','Pacheco',1,'ClauPachecoB','$2y$10$xcOKjIloqfLRB8vQ7u7iJOdDsfbHRWqrjGCZlymVRU.TJ5wHYUv3q','claudia@correo.com','Pacheco021',1,0,1,0,NULL,NULL,1527917764,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-10 17:45:45
