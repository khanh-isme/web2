CREATE DATABASE  IF NOT EXISTS `shoe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `shoe`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: shoe
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_permissions` (
  `admin_id` int NOT NULL,
  `perm_id` int NOT NULL,
  PRIMARY KEY (`admin_id`,`perm_id`),
  KEY `perm_id` (`perm_id`),
  CONSTRAINT `admin_permissions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admin_permissions_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,1),(5,1),(1,2),(5,2),(1,3),(5,3),(1,4),(5,4),(1,5),(5,5),(1,6),(5,6),(1,7),(5,7),(1,8),(5,8),(1,9),(5,9),(1,10),(5,10),(1,11),(5,11),(1,12),(5,12),(1,13),(5,13),(1,14),(5,14),(1,15),(5,15),(1,16),(5,16),(1,17),(5,17),(1,18),(5,18),(1,19),(5,19),(1,20),(5,20),(1,21),(5,21),(1,22),(5,22),(1,23),(5,23),(1,24),(5,24),(1,25),(5,25),(1,26),(5,26),(1,27),(5,27),(1,28),(5,28),(1,29),(5,29),(1,30),(5,30),(1,31),(5,31);
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_vietnamese_ci DEFAULT 'active',
  `role` varchar(255) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'0209','$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy','Cao Cát Lượng','active','Người thừa'),(2,'0033','$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy','Từ Huy Bình','inactive','Ăn bám'),(3,'0307','$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy','Đinh Văn Thanh Sơn','active','Cục dàng'),(4,'0161','$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy','Dương Văn Khánh','active','Cứng đầu'),(5,'0149','$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy','Dương Nguyễn Minh Khang','active','Nole');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_size_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `product_size_id` (`product_size_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,1,1,1,2,'2025-03-29 01:28:56'),(2,2,1,2,1,'2025-03-29 01:28:56'),(3,3,2,3,3,'2025-03-29 01:28:56'),(4,4,2,4,1,'2025-03-29 01:28:56'),(5,5,3,5,2,'2025-03-29 01:28:56'),(6,6,3,6,4,'2025-03-29 01:28:56'),(7,7,4,7,2,'2025-03-29 01:28:56'),(8,8,4,8,1,'2025-03-29 01:28:56'),(9,9,5,9,3,'2025-03-29 01:28:56'),(10,10,5,10,1,'2025-03-29 01:28:56'),(11,11,6,11,2,'2025-03-29 01:28:56'),(12,12,6,12,5,'2025-03-29 01:28:56'),(13,13,6,13,1,'2025-03-29 01:28:56'),(14,14,6,14,3,'2025-03-29 01:28:56'),(15,15,6,15,2,'2025-03-29 01:28:56'),(16,16,7,16,4,'2025-03-29 01:28:56'),(17,17,7,17,1,'2025-03-29 01:28:56'),(18,18,7,18,2,'2025-03-29 01:28:56'),(19,19,7,19,3,'2025-03-29 01:28:56'),(20,20,7,20,1,'2025-03-29 01:28:56');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Sneakers','Giày thể thao'),(2,'Boots','Giày boot thời trang'),(3,'Sandals','Dép và sandals'),(4,'Loafers','Giày lười'),(5,'Athletic','Giày thể thao chuyên dụng');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collection`
--

DROP TABLE IF EXISTS `collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `collection` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection`
--

LOCK TABLES `collection` WRITE;
/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
INSERT INTO `collection` VALUES (1,'Summer Collection','Bộ sưu tập mùa hè 2025'),(2,'Winter Collection','Bộ sưu tập mùa đông'),(3,'Limited Edition','Phiên bản giới hạn'),(4,'Running Shoes','Bộ sưu tập giày chạy bộ'),(5,'Casual Style','Phong cách thường ngày');
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `order_detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_size_id` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_id` (`order_id`),
  KEY `fk_product_size_id` (`product_size_id`),
  CONSTRAINT `fk_product_size_id` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,5,2,500000.00),(2,2,10,1,1800000.00),(3,3,15,3,250000.00),(4,4,20,5,300000.00),(5,5,25,2,890000.00),(6,6,30,4,750000.00),(7,7,35,1,590000.00),(8,8,40,3,890000.00),(9,9,45,2,630000.00),(10,10,50,4,1050000.00);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `shipping_name` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3,'2025-04-01 14:23:00',500000.00,'delivered','Nguyễn Văn A','0987654321','15 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội'),(2,5,'2025-04-12 10:15:00',1800000.00,'delivered','Trần Thị B','0912345678','89 Trần Quang Diệu, Quận 3, TP.HCM'),(3,2,'2025-04-05 18:30:00',250000.00,'delivered','Lê Văn C','0909988777','25 Nguyễn Đình Chiểu, Quận 1, TP.HCM'),(4,7,'2025-04-07 09:00:00',300000.00,'delivered','Phạm Thị D','0988776655','102 Trường Chinh, Thanh Xuân, Hà Nội'),(5,9,'2025-04-12 11:45:00',890000.00,'delivered','Vũ Minh E','0945566777','78 Phan Đình Phùng, Ba Đình, Hà Nội'),(6,4,'2025-04-09 15:20:00',750000.00,'delivered','Nguyễn Thị F','0977333222','12 Hoàng Văn Thụ, Phú Nhuận, TP.HCM'),(7,6,'2025-04-10 13:05:00',590000.00,'delivered','Đặng Văn G','0933222111','30 Nguyễn Văn Cừ, Long Biên, Hà Nội'),(8,1,'2025-04-12 16:40:00',890000.00,'delivered','Lương Thị H','0966778899','45 Lê Văn Sỹ, Phú Nhuận, TP.HCM'),(9,8,'2025-04-13 08:55:00',630000.00,'delivered','Phan Văn I','0905112233','23 Tô Hiến Thành, Quận 10, TP.HCM'),(10,10,'2025-04-14 17:30:00',1050000.00,'delivered','Đỗ Thị K','0911223344','68 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'STATS'),(2,'PRODUCTS'),(3,'ORDERS'),(4,'CUSTOMERS'),(5,'EMPLOYEES'),(6,'SUPPLIERS'),(7,'VIEW_STATS'),(8,'EDIT_STATS'),(9,'EXPORT_STATS'),(10,'VIEW_PRODUCTS'),(11,'ADD_PRODUCT'),(12,'EDIT_PRODUCT'),(13,'DELETE_PRODUCT'),(14,'VIEW_ORDERS'),(15,'EDIT_ORDER'),(16,'DELETE_ORDER'),(17,'MANAGE_ORDER_STATUS'),(18,'VIEW_CUSTOMERS'),(19,'ADD_CUSTOMER'),(20,'EDIT_CUSTOMER'),(21,'DELETE_CUSTOMER'),(22,'VIEW_EMPLOYEES'),(23,'ADD_EMPLOYEE'),(24,'EDIT_EMPLOYEE'),(25,'DELETE_EMPLOYEE'),(26,'MANAGE_EMPLOYEE_ROLES'),(27,'VIEW_SUPPLIERS'),(28,'ADD_SUPPLIER'),(29,'EDIT_SUPPLIER'),(30,'DELETE_SUPPLIER'),(31,'MANAGE_SUPPLIER_CONTRACTS');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_size`
--

DROP TABLE IF EXISTS `product_size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_size` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `size` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `stock` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_size_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_size`
--

LOCK TABLES `product_size` WRITE;
/*!40000 ALTER TABLE `product_size` DISABLE KEYS */;
INSERT INTO `product_size` VALUES (1,1,'40',10),(2,1,'41',15),(3,2,'42',20),(4,2,'43',18),(5,3,'40',12),(6,3,'41',14),(7,4,'39',10),(8,4,'40',20),(9,5,'41',12),(10,5,'42',16),(11,6,'38',10),(12,6,'39',15),(13,6,'40',20),(14,6,'41',25),(15,6,'42',30),(16,7,'36',12),(17,7,'37',18),(18,7,'38',22),(19,7,'39',28),(20,7,'40',32),(21,8,'39',14),(22,8,'40',20),(23,8,'41',24),(24,8,'42',26),(25,8,'43',30),(26,9,'38',10),(27,9,'39',15),(28,9,'40',20),(29,9,'41',25),(30,9,'42',30),(31,10,'40',18),(32,10,'41',22),(33,10,'42',28),(34,10,'43',32),(35,10,'44',35),(36,11,'38',12),(37,11,'39',18),(38,11,'40',22),(39,11,'41',28),(40,11,'42',32),(41,12,'36',10),(42,12,'37',15),(43,12,'38',20),(44,12,'39',25),(45,12,'40',30),(46,13,'39',14),(47,13,'40',20),(48,13,'41',24),(49,13,'42',26),(50,13,'43',30),(51,14,'38',12),(52,14,'39',18),(53,14,'40',22),(54,14,'41',28),(55,14,'42',32),(56,15,'40',16),(57,15,'41',22),(58,15,'42',28),(59,15,'43',32),(60,15,'44',35),(61,16,'38',10),(62,16,'39',15),(63,16,'40',20),(64,16,'41',25),(65,16,'42',30),(66,17,'36',12),(67,17,'37',18),(68,17,'38',22),(69,17,'39',28),(70,17,'40',32),(71,18,'39',14),(72,18,'40',20),(73,18,'41',24),(74,18,'42',26),(75,18,'43',30),(76,19,'38',10),(77,19,'39',15),(78,19,'40',20),(79,19,'41',25),(80,19,'42',30),(81,20,'40',18),(82,20,'41',22),(83,20,'42',28),(84,20,'43',32),(85,20,'44',35),(86,21,'38',12),(87,21,'39',18),(88,21,'40',22),(89,21,'41',28),(90,21,'42',32),(91,22,'36',10),(92,22,'37',15),(93,22,'38',20),(94,22,'39',25),(95,22,'40',30),(96,23,'39',14),(97,23,'40',20),(98,23,'41',24),(99,23,'42',26),(100,23,'43',30),(101,24,'38',12),(102,24,'39',18),(103,24,'40',22),(104,24,'41',28),(105,24,'42',32),(106,25,'40',16),(107,25,'41',22),(108,25,'42',28),(109,25,'43',32),(110,25,'44',35),(111,26,'38',10),(112,26,'39',15),(113,26,'40',20),(114,26,'41',25),(115,26,'42',30),(116,27,'36',12),(117,27,'37',18),(118,27,'38',22),(119,27,'39',28),(120,27,'40',32),(121,28,'39',14),(122,28,'40',20),(123,28,'41',24),(124,28,'42',26),(125,28,'43',30),(126,29,'38',10),(127,29,'39',15),(128,29,'40',20),(129,29,'41',25),(130,29,'42',30),(131,30,'40',18),(132,30,'41',22),(133,30,'42',28),(134,30,'43',32),(135,30,'44',35),(136,31,'38',10),(137,31,'39',15),(138,31,'40',20),(139,31,'41',25),(140,31,'42',30),(141,32,'36',12),(142,32,'37',18),(143,32,'38',22),(144,32,'39',28),(145,32,'40',32),(146,33,'39',14),(147,33,'40',20),(148,33,'41',24),(149,33,'42',26),(150,33,'43',30),(151,34,'38',10),(152,34,'39',15),(153,34,'40',20),(154,34,'41',25),(155,34,'42',30),(156,35,'40',18),(157,35,'41',22),(158,35,'42',28),(159,35,'43',32),(160,35,'44',35),(161,36,'38',12),(162,36,'39',18),(163,36,'40',22),(164,36,'41',28),(165,36,'42',32),(166,37,'36',10),(167,37,'37',15),(168,37,'38',20),(169,37,'39',25),(170,37,'40',30),(171,38,'39',14),(172,38,'40',20),(173,38,'41',24),(174,38,'42',26),(175,38,'43',30),(176,39,'38',12),(177,39,'39',18),(178,39,'40',22),(179,39,'41',28),(180,39,'42',32),(181,40,'40',16),(182,40,'41',22),(183,40,'42',28),(184,40,'43',32),(185,40,'44',35),(186,41,'38',10),(187,41,'39',15),(188,41,'40',20),(189,41,'41',25),(190,41,'42',30),(191,42,'36',12),(192,42,'37',18),(193,42,'38',22),(194,42,'39',28),(195,42,'40',32),(196,43,'39',14),(197,43,'40',20),(198,43,'41',24),(199,43,'42',26),(200,43,'43',30),(201,44,'38',10),(202,44,'39',15),(203,44,'40',20),(204,44,'41',25),(205,44,'42',30),(206,45,'40',18),(207,45,'41',22),(208,45,'42',28),(209,45,'43',32),(210,45,'44',35),(211,46,'38',12),(212,46,'39',18),(213,46,'40',22),(214,46,'41',28),(215,46,'42',32),(216,47,'36',10),(217,47,'37',15),(218,47,'38',20),(219,47,'39',25),(220,47,'40',30),(221,48,'39',14),(222,48,'40',20),(223,48,'41',24),(224,48,'42',26),(225,48,'43',30),(226,49,'38',12),(227,49,'39',18),(228,49,'40',22),(229,49,'41',28),(230,49,'42',32),(231,50,'40',16),(232,50,'41',22),(233,50,'42',28),(234,50,'43',32),(235,50,'44',35),(236,51,'38',10),(237,51,'39',15),(238,51,'40',20),(239,51,'41',25),(240,51,'42',30),(241,52,'36',12),(242,52,'37',18),(243,52,'38',22),(244,52,'39',28),(245,52,'40',32),(246,53,'39',14),(247,53,'40',20),(248,53,'41',24),(249,53,'42',26),(250,53,'43',30),(251,54,'38',10),(252,54,'39',15),(253,54,'40',20),(254,54,'41',25),(255,54,'42',30),(256,55,'40',18),(257,55,'41',22),(258,55,'42',28),(259,55,'43',32),(260,55,'44',35),(261,56,'38',12),(262,56,'39',18),(263,56,'40',22),(264,56,'41',28),(265,56,'42',32),(266,57,'36',10),(267,57,'37',15),(268,57,'38',20),(269,57,'39',25),(270,57,'40',30),(271,58,'39',14),(272,58,'40',20),(273,58,'41',24),(274,58,'42',26),(275,58,'43',30),(276,59,'38',10),(277,59,'39',15),(278,59,'40',20),(279,59,'41',25),(280,59,'42',30),(281,60,'40',18),(282,60,'41',22),(283,60,'42',28),(284,60,'43',32),(285,60,'44',35),(286,61,'38',12),(287,61,'39',18),(288,61,'40',22),(289,61,'41',28),(290,61,'42',32),(291,62,'36',10),(292,62,'37',15),(293,62,'38',20),(294,62,'39',25),(295,62,'40',30),(296,63,'39',14),(297,63,'40',20),(298,63,'41',24),(299,63,'42',26),(300,63,'43',30),(301,64,'38',12),(302,64,'39',18),(303,64,'40',22),(304,64,'41',28),(305,64,'42',32),(306,65,'40',16),(307,65,'41',22),(308,65,'42',28),(309,65,'43',32),(310,65,'44',35),(311,66,'38',10),(312,66,'39',15),(313,66,'40',20),(314,66,'41',25),(315,66,'42',30),(316,67,'36',12),(317,67,'37',18),(318,67,'38',22),(319,67,'39',28),(320,67,'40',32),(321,68,'39',14),(322,68,'40',20),(323,68,'41',24),(324,68,'42',26),(325,68,'43',30),(326,69,'38',10),(327,69,'39',15),(328,69,'40',20),(329,69,'41',25),(330,69,'42',30),(331,70,'40',18),(332,70,'41',22),(333,70,'42',28),(334,70,'43',32),(335,70,'44',35);
/*!40000 ALTER TABLE `product_size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `collection_id` int DEFAULT NULL,
  `gender` enum('nam','nu','unisex') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unisex',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `collection_id` (`collection_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Nike Air Force 1',1,1200000.00,'Giày thể thao cổ điển','/web2/assets/images/11.png','2025-03-21 08:01:45',1,'unisex'),(2,'Adidas Ultraboost',5,2800000.00,'Giày chạy bộ cao cấp','/web2/assets/images/11.png','2025-03-21 08:01:45',4,'unisex'),(3,'Timberland Classic',2,3500000.00,'Giày boot chống nước','/web2/assets/images/11.png','2025-03-21 08:01:45',2,'nam'),(4,'Vans Old Skool',1,1500000.00,'Giày trượt ván cổ điển','/web2/assets/images/11.png','2025-03-21 08:01:45',5,'unisex'),(5,'Puma RS-X',1,2200000.00,'Phong cách retro','/web2/assets/images/11.png','2025-03-21 08:01:45',3,'unisex'),(6,'Gucci Loafer',4,5500000.00,'Giày lười thời trang cao cấp','/web2/assets/images/11.png','2025-03-21 08:01:45',3,'nu'),(7,'Converse Chuck Taylor',1,1300000.00,'Mẫu giày cao cổ huyền thoại','/web2/assets/images/11.png','2025-03-21 08:01:45',5,'unisex'),(8,'Adidas Yeezy Boost',5,5000000.00,'Giày sneakers phiên bản giới hạn','/web2/assets/images/11.png','2025-03-21 08:01:45',3,'unisex'),(9,'Nike ZoomX',5,3200000.00,'Giày chạy bộ siêu nhẹ','/web2/assets/images/11.png','2025-03-21 08:01:45',4,'nam'),(10,'Crocs Classic',3,900000.00,'Dép thoải mái và nhẹ nhàng','/web2/assets/images/11.png','2025-03-21 08:01:45',1,'unisex'),(11,'Air Max 2025',1,120000.00,'Giày thể thao cao cấp','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(12,'Winter Boot Pro',2,150000.00,'Giày boot ấm áp mùa đông','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nu'),(13,'Casual Sandal',3,500000.00,'Dép sandals thoáng mát','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(14,'Leather Loafers',4,100000.00,'Giày lười da sang trọng','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(15,'Running Pro 3000',5,130000.00,'Giày chạy bộ chuyên nghiệp','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(16,'Sporty Runner',1,110000.00,'Giày chạy bộ nhẹ','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(17,'Winter Guard Boot',2,160000.00,'Boot chống nước mùa đông','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nu'),(18,'Summer Sandal',3,450000.00,'Dép mùa hè phong cách','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(19,'Elegant Loafers',4,105000.00,'Giày lười thanh lịch','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(20,'Speed Track',5,140000.00,'Giày thể thao tốc độ','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(21,'Flex Sneakers',1,115000.00,'Giày thể thao linh hoạt','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(22,'Mountain Boot',2,170000.00,'Giày boot leo núi','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nam'),(23,'Beach Sandal',3,55000.00,'Dép đi biển thời trang','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(24,'Classic Loafers',4,95000.00,'Giày lười cổ điển','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(25,'Endurance Runner',5,135000.00,'Giày chạy bộ bền bỉ','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(26,'Elite Sneakers',1,125000.00,'Giày thể thao đẳng cấp','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(27,'Arctic Boot',2,180000.00,'Giày boot chống lạnh','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nu'),(28,'Urban Sandal',3,60000.00,'Dép thành phố hiện đại','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(29,'Formal Loafers',4,110000.00,'Giày lười công sở','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(30,'Marathon Pro',5,145000.00,'Giày chạy marathon','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(31,'Hyper Sneakers',1,130000.00,'Giày sneaker phong cách','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(32,'Explorer Boot',2,175000.00,'Boot khám phá địa hình','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nam'),(33,'Comfy Sandal',3,65000.00,'Dép thoải mái mọi lúc','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(34,'Casual Loafers',4,115000.00,'Giày lười hàng ngày','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(35,'Speed Runner X',5,150000.00,'Giày chạy bộ nhanh','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(36,'Active Sneakers',1,135000.00,'Giày sneaker thể thao','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(37,'Trek Boot',2,185000.00,'Boot trekking bền bỉ','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nam'),(38,'Summer Breeze Sandal',3,70000.00,'Dép mùa hè thoáng mát','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(39,'Stylish Loafers',4,120000.00,'Giày lười thời trang','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(40,'Pro Runner Elite',5,155000.00,'Giày chạy bộ chuyên nghiệp','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(41,'Dynamic Sneakers',1,140000.00,'Giày thể thao năng động','/web2/assets/images/14.png','2025-03-22 01:09:11',1,'unisex'),(42,'Alpine Boot',2,190000.00,'Boot leo núi chuyên dụng','/web2/assets/images/14.png','2025-03-22 01:09:11',2,'nam'),(43,'Outdoor Sandal',3,75000.00,'Dép ngoài trời bền bỉ','/web2/assets/images/14.png','2025-03-22 01:09:11',3,'unisex'),(44,'Luxury Loafers',4,125000.00,'Giày lười cao cấp','/web2/assets/images/14.png','2025-03-22 01:09:11',5,'nam'),(45,'Ultrafast Runner',5,160000.00,'Giày chạy bộ tốc độ cao','/web2/assets/images/14.png','2025-03-22 01:09:11',4,'unisex'),(46,'Giày Sneakers X1',1,59000.99,'Giày sneakers phong cách trẻ trung.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'unisex'),(47,'Giày Sneakers X2',1,65000.50,'Thiết kế thể thao, năng động.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'unisex'),(48,'Giày Sneakers X3',1,70002.00,'Màu sắc cá tính, phù hợp giới trẻ.','/web2/assets/images/14.png','2025-03-22 01:32:05',3,'unisex'),(49,'Giày Boots B1',2,85000.99,'Giày boot thời trang, phù hợp mùa đông.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'nam'),(50,'Giày Boots B2',2,90000.75,'Chất liệu da cao cấp, bền bỉ.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'nu'),(51,'Giày Sandals S1',3,35000.99,'Dép sandals nhẹ, thoáng khí.','/web2/assets/images/14.png','2025-03-22 01:32:05',4,'unisex'),(52,'Giày Sandals S2',3,40000.50,'Dép đế cao, phong cách hiện đại.','/web2/assets/images/14.png','2025-03-22 01:32:05',5,'nu'),(53,'Giày Loafers L1',4,55000.99,'Giày lười tiện lợi, phù hợp công sở.','/web2/assets/images/14.png','2025-03-22 01:32:05',3,'nam'),(54,'Giày Loafers L2',4,60000.00,'Chất liệu da sang trọng, dễ kết hợp trang phục.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'nu'),(55,'Giày Thể Thao A1',5,78000.99,'Giày thể thao chuyên dụng.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'unisex'),(56,'Giày Sneakers X4',1,62000.99,'Thiết kế mới, đế cao su bền.','/web2/assets/images/14.png','2025-03-22 01:32:05',5,'unisex'),(57,'Giày Sneakers X5',1,60008.50,'Phong cách streetwear.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'unisex'),(58,'Giày Boots B3',2,95000.99,'Boots da thật, chống nước.','/web2/assets/images/14.png','2025-03-22 01:32:05',4,'nam'),(59,'Giày Boots B4',2,99000.50,'Boots cổ cao thời trang.','/web2/assets/images/14.png','2025-03-22 01:32:05',3,'nu'),(60,'Giày Sandals S3',3,38000.50,'Sandal dây mềm mại.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'nu'),(61,'Giày Sandals S4',3,42000.99,'Dép quai ngang, thoải mái.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'unisex'),(62,'Giày Loafers L3',4,58000.75,'Loafers phong cách Hàn Quốc.','/web2/assets/images/14.png','2025-03-22 01:32:05',5,'nu'),(63,'Giày Loafers L4',4,63000.50,'Giày lười nam lịch lãm.','/web2/assets/images/14.png','2025-03-22 01:32:05',3,'nam'),(64,'Giày Thể Thao A2',5,79000.99,'Đế cao su chống trơn trượt.','/web2/assets/images/14.png','2025-03-22 01:32:05',4,'unisex'),(65,'Giày Thể Thao A3',5,82000.50,'Giày nhẹ, bám sân tốt.','/web2/assets/images/14.png','2025-03-22 01:32:05',5,'unisex'),(66,'Giày Sneakers X6',1,64000.99,'Sneakers đơn giản nhưng tinh tế.','/web2/assets/images/14.png','2025-03-22 01:32:05',2,'unisex'),(67,'Giày Boots B5',2,92000.00,'Boots chống nước, chống trơn.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'nam'),(68,'Giày Sandals S5',3,37000.50,'Sandal nữ đi biển.','/web2/assets/images/14.png','2025-03-22 01:32:05',3,'nu'),(69,'Giày Loafers L5',4,61000.99,'Giày lười da bò thật.','/web2/assets/images/14.png','2025-03-22 01:32:05',4,'nam'),(70,'Giày Thể Thao A4',5,85000.00,'Giày chạy bộ chuyên dụng.','/web2/assets/images/14.png','2025-03-22 01:32:05',1,'unisex');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles_permissions`
--

DROP TABLE IF EXISTS `roles_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles_permissions` (
  `role_id` int NOT NULL,
  `perm_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`perm_id`),
  KEY `perm_id` (`perm_id`),
  CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_permissions_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles_permissions`
--

LOCK TABLES `roles_permissions` WRITE;
/*!40000 ALTER TABLE `roles_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `address` text COLLATE utf8mb4_vietnamese_ci,
  `phone` varchar(20) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Công Ty TNHH Thương Mại Hoàng Long','Số 12, Nguyễn Trãi, Thanh Xuân, Hà Nội','0968123456','hoanglong.supplier@gmail.com','2025-04-15 09:39:26'),(2,'Công Ty TNHH An Phát','234 Điện Biên Phủ, Bình Thạnh, TP.HCM','0934556677','anphat.supplier@gmail.com','2025-04-15 09:39:26'),(3,'Công Ty CP May Việt Tiến','7 Lê Thánh Tôn, Quận 1, TP.HCM','0901234567','viettien.contact@gmail.com','2025-04-15 09:39:26'),(4,'Công Ty TNHH Minh Nhật','56 Trần Phú, Hải Châu, Đà Nẵng','0977888999','minhnhat.supplier@gmail.com','2025-04-15 09:39:26'),(5,'Công Ty TNHH Toàn Cầu','123 Nguyễn Văn Linh, Hải Châu, Đà Nẵng','0944556677','toancau.office@gmail.com','2025-04-15 09:39:26'),(6,'Công Ty TNHH Đầu Tư Nam Sơn','88 Lê Duẩn, Quận Thanh Khê, Đà Nẵng','0988111222','namson.contact@gmail.com','2025-04-15 09:39:26'),(7,'Công Ty TNHH Xuất Nhập Khẩu Đông Phương','20 Cách Mạng Tháng 8, Quận 3, TP.HCM','0909988776','dongphuong.export@gmail.com','2025-04-15 09:39:26'),(8,'Công Ty TNHH TM-DV Kim Ngân','45 Hoàng Hoa Thám, Ba Đình, Hà Nội','0911223344','kimngan.contact@gmail.com','2025-04-15 09:39:26'),(9,'Công Ty CP Việt Nhật','11 Trần Đại Nghĩa, Hai Bà Trưng, Hà Nội','0934556677','vietnhat.equipment@gmail.com','2025-04-15 09:39:26'),(10,'Công Ty TNHH Thương Mại Hà Thành','59 Nguyễn Khuyến, Đống Đa, Hà Nội','0968998877','hathanh.contact@gmail.com','2025-04-15 09:39:26');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Nguyễn Văn A','nguyenvana@example.com','123456','0987654321','Hà Nội'),(2,'Trần Thị B','tranthib@example.com','abcdef','0977123456','TP HCM'),(3,'Lê Văn C','levanc@example.com','pass123','0912345678','Đà Nẵng'),(4,'Hoàng Thị D','hoangthid@example.com','securepass','0933221144','Cần Thơ'),(5,'Phạm Văn E','phamvane@example.com','e123456','0966554433','Hải Phòng'),(6,'Vũ Thị F','vuthif@example.com','fpassword','0922113344','Huế'),(7,'Bùi Văn G','buivang@example.com','gpass','0944556677','Nha Trang'),(8,'Đặng Thị H','dangthih@example.com','hpassword','0988223344','Vũng Tàu'),(9,'Ngô Văn I','ngovani@example.com','ipass123','0911998877','Quảng Ninh'),(10,'Dương Thị J','duongthij@example.com','jpassword','0955332244','Bắc Ninh'),(11,'Trịnh Văn K','trinhvank@example.com','ksecure','0999223344','Thanh Hóa'),(12,'Đoàn Thị L','doanthil@example.com','lpass','0944559922','Nam Định'),(13,'Mai Văn M','maivanm@example.com','mpassword','0900557788','Hòa Bình'),(14,'Châu Thị N','chauthin@example.com','npass','0933225566','Bình Dương'),(15,'Lương Văn O','luongvano@example.com','opassword','0977448822','Lạng Sơn'),(16,'Phan Thị P','phanthip@example.com','ppass123','0966991155','Quảng Bình'),(17,'Cao Văn Q','caovanq@example.com','qpassword','0922334455','Cà Mau'),(18,'Tạ Thị R','tatheir@example.com','rpass','0999887766','Kon Tum'),(19,'Trương Văn S','truongvans@example.com','spassword','0900223344','Bắc Giang'),(20,'Lý Thị T','lythit@example.com','tpass123','0944778899','Đắk Lắk');
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

-- Dump completed on 2025-04-16 18:40:58
