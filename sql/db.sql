drop database if exists productstore;
create database if not exists productstore default character set utf8mb4 collate utf8mb4_unicode_ci;
use productstore;
-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: productstore
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `description` text COLLATE utf8_slovenian_ci NOT NULL,
  `price` float NOT NULL,
  `activated` boolean NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES
(1,'Gamepad Logitech F510','F510 igralni plošček podpira vse vrste iger. Priložena Logitech programska oprema omogoča uporabo ploščka tudi pri igrah, ki tega ne podpirajo. Igranje bo postalo še večji užitek zaradi dveh vgrajenih vibracijskih motorjev, domače postavitve gumbov in edinstvene oblike D-ploščka.', 40.00, true),
(2,'Pametni telefon Huawei P20 Lite moder','Telefon boste sedaj lahko odklepali s svojim obrazom, vgrajeni 4GB delovnega spomina pa omogočajo hitro uporabo in nemoteno preklapljanje med aplikacijami. Na zadnji strani je Huawei v svoji “Lite” različici P serije prvič ponudil dve kameri – 16MP in 2MP. Da bo telefon z lahkoto sledil vašemu živahnemu življenskemu stilu, bo 3000 mAh baterija s funkcijo Quick Charge napolnjena hitro in varno.', 299.99, true),
(3,'Tablični računalnik SAMSUNG GALAXY TAB S2','Uživajte še več fleksibilnosti z Galaxy Tab S2 kot kdajkoli prej. Zaradi njegovih izjemno tankih in ultra-lahkih značilnosti lahko napravo uporabljate za branje e-knjig, pregledovanje fotografij, video posnetkov in datotek, povezanih z vašim delom, kjerkoli že ste.', 479.99, true);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `lastName` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `type` smallint NOT NULL default 2,
  `address` varchar(255) COLLATE utf8_slovenian_ci,
  `zipcode_id` int(11),
  `phone` varchar(100) COLLATE utf8_slovenian_ci,
  `activated` boolean NOT NULL default 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_zipcode_id` FOREIGN KEY (`zipcode_id`) REFERENCES `post_office`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'Matic', 'Zahradnik', 'matic@ep.si', '$2y$10$rxQX9QnpsV4PVnx7EMwJGuE5beH/52i7lQBDfljDLm6zy6d.hCPSi', 0, '', '', '', true),
(2, 'Vanesa', 'Godec', 'vanesa@ep.si', '$2y$10$HEV/K2FkedHZc9N.ayjkUOmzbyuLnO1yj/Q6iFddqo2IVc.4IUmOC', 1, '', '', '', true),
(3, 'Maja', 'Lobnik', 'maja@lobnik.si', '$2y$10$LOja/YT0WBqIYYJYtgyLxeBBOl8ceZLHip/ss3clw3C/YnL/u6p.q', 2, 'Večna pot 113', 5, '040123456', true);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;


DROP TABLE IF EXISTS `bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_seller` int(11) NOT NULL,
  `status` smallint NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_order_user` FOREIGN KEY(`id_user`) REFERENCES `user`(`id`),
  CONSTRAINT `FK_order_seller` FOREIGN KEY(`id_seller`) REFERENCES `user`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill`
--

LOCK TABLES `bill` WRITE;
/*!40000 ALTER TABLE `bill` DISABLE KEYS */;
INSERT INTO `bill` VALUES
(1, 3, 2, 0),
(2, 3, 2, 1),
(3, 3, 2, 2);
/*!40000 ALTER TABLE `bill` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;


DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product` (
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id_product`, `id_order`),
  CONSTRAINT `FK_oa_order` FOREIGN KEY(`id_order`) REFERENCES `bill`(`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
INSERT INTO `order_product` VALUES
(1, 1, 10),
(1, 2, 5),
(1, 3, 3),
(2, 1, 4),
(2, 2, 2),
(3, 1, 1),
(3, 3, 1);
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;



DROP TABLE IF EXISTS `post_office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zipcode` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_office`
--

LOCK TABLES `post_office` WRITE;
/*!40000 ALTER TABLE `post_office` DISABLE KEYS */;
INSERT INTO `post_office` VALUES
(1, '1000 Ljubljana'),
(2, '2000 Maribor'),
(3, '3000 Celje'),
(4, '4000 Kranj'),
(5, '5000 Nova Gorica');
/*!40000 ALTER TABLE `post_office` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
-- Dump completed on 2014-12-12 16:45:04
