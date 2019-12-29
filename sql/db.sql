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
(1,'Mleko','Polnomastno mleko z najvec okusa.', 2.00, true),
(2,'Jajca','Jajca baterijske reje (niso videla svetlobe).', 5.99, true),
(3,'Kruh','Hribovc, domaci.', 2.99, true);
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
(1,'Simen', 'Ravnik', 'simen@ep.si', '$2y$10$9dsxWqgbfIHnfZRAZ0urBOha/julx.gYNCxvlTFKSwPSvlRA/zy4.', 0, '', '', '', true),
(2, 'Jure', 'Srovin', 'jure@ep.si', '$2y$10$4pfixLIqiS4Re6uP1KaZUOcQdZ452DVfSElOjdfYaqihJbQBUaFDq', 1, '', '', '', true),
(3, 'Jure', 'Vito', 'vito@ep.si', '$2y$10$.6ZJL/PP3GyIYPDMQdPT9O4DRYCYq.4n0Gfck1QCxw2gKZ/qwaiu6', 2, 'Večna pot 113', 5, '040123456', true);
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
