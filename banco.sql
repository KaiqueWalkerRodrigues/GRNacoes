-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 192.168.0.60    Database: grnacoes
-- ------------------------------------------------------
-- Server version	8.2.0

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
-- Table structure for table `cargos`
--

DROP TABLE IF EXISTS `cargos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargos` (
  `id_cargo` int NOT NULL AUTO_INCREMENT,
  `cargo` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
INSERT INTO `cargos` VALUES (1,'Auxiliar de Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(3,'Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(4,'Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(6,'Auxiliar de Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(7,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00','2024-04-18 09:59:40'),(8,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','2024-04-18 12:45:33',NULL),(9,'Vendedor Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(10,'Vendedor Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(11,'Vendedor Senior ','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(12,'Auxiliar de Serviços Gerais Junior','2024-04-18 11:34:30','2024-04-18 11:43:16',NULL),(13,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:24:05','2024-04-18 12:24:05',NULL),(14,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:31:32','2024-04-18 12:31:32','2024-04-18 12:32:12'),(15,'Vendedor Senior','2024-04-18 12:34:01','2024-04-18 12:34:01','2024-04-18 12:43:15'),(16,'Auxiliar de Serviços Gerais Senior','2024-04-18 12:43:50','2024-04-18 12:44:33',NULL),(17,'teste','2024-04-18 12:55:34','2024-04-18 12:55:34','2024-04-18 12:57:50'),(18,'Recursos Humanos','2024-04-19 11:53:46','2024-04-19 11:54:40',NULL),(19,'Assistente De Compra Sênior','2024-04-23 11:28:22','2024-04-23 11:28:22',NULL);
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chats` (
  `id_chat` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_chat`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats`
--

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
INSERT INTO `chats` VALUES (1,'0000-00-00 00:00:00','0000-00-00 00:00:00',NULL);
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chats_usuarios`
--

DROP TABLE IF EXISTS `chats_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chats_usuarios` (
  `id_chat_usuario` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_chat` int NOT NULL,
  PRIMARY KEY (`id_chat_usuario`),
  KEY `fk_chats_usuarios_idx` (`id_chat`),
  KEY `fk_chats_usuarios_usuarios_idx` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats_usuarios`
--

LOCK TABLES `chats_usuarios` WRITE;
/*!40000 ALTER TABLE `chats_usuarios` DISABLE KEYS */;
INSERT INTO `chats_usuarios` VALUES (1,1,1),(2,2,1);
/*!40000 ALTER TABLE `chats_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_categorias`
--

DROP TABLE IF EXISTS `compras_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_categorias` (
  `id_compra_categoria` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_categorias`
--

LOCK TABLES `compras_categorias` WRITE;
/*!40000 ALTER TABLE `compras_categorias` DISABLE KEYS */;
INSERT INTO `compras_categorias` VALUES (1,'Lente Intraocular','2024-04-23 11:42:12','2024-04-23 11:42:12',NULL),(2,'Lente De Contato','2024-04-25 11:40:09','2024-04-25 11:40:09',NULL),(3,'Produtos De Limpeza','2024-04-25 11:47:29','2024-04-25 11:47:29',NULL),(4,'Medicamentos / Material Hospitalar','2024-04-25 11:56:54','2024-05-09 11:51:38',NULL),(5,'Armações','2024-04-25 12:16:07','2024-04-25 12:23:22',NULL),(6,'ArmaÇÕes','2024-04-25 12:17:18','2024-04-25 12:17:18','2024-04-25 12:17:23'),(7,'Estojo De Oculos','2024-04-25 14:11:47','2024-04-25 14:11:47',NULL),(8,'Manutenção Predial','2024-04-25 14:13:08','2024-05-09 12:19:00',NULL),(9,'Materiais De Escritorio','2024-04-30 14:09:21','2024-05-02 12:27:42',NULL),(10,'Manutenção De Equipamentos','2024-05-03 10:17:57','2024-05-03 10:17:57',NULL);
/*!40000 ALTER TABLE `compras_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_fornecedores`
--

DROP TABLE IF EXISTS `compras_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_fornecedores` (
  `id_compra_fornecedor` int NOT NULL AUTO_INCREMENT,
  `id_categoria` int NOT NULL,
  `fornecedor` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_fornecedor`),
  KEY `fk_compras_fornecedores_compras_categorias_idx` (`id_categoria`),
  CONSTRAINT `fk_compras_fornecedores_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `compras_categorias` (`id_compra_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_fornecedores`
--

LOCK TABLES `compras_fornecedores` WRITE;
/*!40000 ALTER TABLE `compras_fornecedores` DISABLE KEYS */;
INSERT INTO `compras_fornecedores` VALUES (1,1,'Carl Zeiss Do Brasil Ltda','2024-04-23 11:43:59','2024-04-23 11:43:59',NULL),(2,2,'Coopervision','2024-04-25 11:41:25','2024-04-25 11:41:25',NULL),(3,1,'Adapt Produtos Oft Ltda','2024-04-25 11:43:44','2024-04-25 11:43:44',NULL),(4,3,'Sales Distribuidora Ltda','2024-04-25 11:47:58','2024-04-25 11:47:58',NULL),(5,2,'Johnson & Johnson Br Ind Com. Prod. Saude Ltda','2024-04-25 11:49:09','2024-04-25 11:49:09',NULL),(6,2,'Bl Industria Ltda','2024-04-25 11:50:08','2024-04-25 11:50:08',NULL),(7,2,'Solotica Distr. De Prod. Opticos Eireli','2024-04-25 11:51:25','2024-04-25 11:51:25',NULL),(8,1,'Ophthalmos Ltda','2024-04-25 11:55:34','2024-04-25 11:55:34',NULL),(9,4,'Alcon','2024-04-25 11:57:15','2024-04-25 11:57:15','2024-04-25 12:00:48'),(10,4,'Alcon','2024-04-25 11:58:25','2024-04-25 11:58:25','2024-04-25 12:00:51'),(11,4,'Alcon','2024-04-25 11:59:58','2024-04-25 12:01:31',NULL),(12,4,'Adapt Hospitalra Dist Medicamentos Ltda','2024-04-25 12:15:13','2024-04-25 12:15:13',NULL),(13,1,'Beta Surgical Materiais Medicos Ltda','2024-04-25 12:15:47','2024-04-25 12:15:47',NULL),(14,5,'Luxottica Brasil Prod Oticos Esp Ltda','2024-04-25 12:16:52','2024-04-25 12:16:52','2024-04-25 12:17:06'),(15,5,'Luxottica Brasil Prod Oticos Esp Ltda','2024-04-25 12:17:45','2024-04-25 12:17:45',NULL),(16,7,'J L Injecao De Plasticos Ltda','2024-04-25 14:11:58','2024-04-25 14:11:58',NULL),(17,8,'3 De Maio','2024-04-25 14:13:34','2024-04-25 14:13:34',NULL),(18,5,'Kenerson Ind E Com De Prod Opticos Ltda','2024-04-25 14:13:59','2024-04-25 14:13:59',NULL),(19,3,'Raami Comercio De Produtos De Limpeza Ltda','2024-04-29 12:19:25','2024-04-29 12:19:25',NULL),(20,1,'Jjsv Produtos óticos Ltda','2024-04-29 16:53:17','2024-04-29 16:53:17',NULL),(22,9,'Wg Negocios Corporativos','2024-04-30 14:10:12','2024-04-30 14:10:12',NULL),(23,10,'Wg Negocios Corporativos','2024-05-03 10:18:17','2024-05-03 10:18:17',NULL),(24,2,'Alcon','2024-05-09 11:35:47','2024-05-09 11:35:47',NULL),(25,2,'Coopervision  Do Brasil Ltda','2024-05-09 11:38:59','2024-05-09 11:38:59',NULL),(26,2,'Optolentes Lentes De Contato Ltda','2024-05-09 11:43:49','2024-05-09 11:45:04',NULL),(27,4,'Ophthalmos Ltda','2024-05-09 11:47:09','2024-05-09 11:47:09',NULL),(28,4,'Agf Express Materiais Medicos Ltda','2024-05-09 11:51:16','2024-05-09 11:51:16',NULL),(29,4,'Maycare Distribuidora Importadora','2024-05-09 11:52:12','2024-05-09 11:52:12',NULL),(30,4,'Thiago Rodrigues Cardoso 07541451665','2024-05-09 11:52:42','2024-05-09 11:52:42',NULL),(31,8,'Zerbini Do Brasil Ltda','2024-05-09 11:55:36','2024-05-09 11:55:36',NULL),(32,10,'Marcos Padovan Informatica Me','2024-05-09 11:56:04','2024-05-09 11:56:04',NULL),(33,9,'Luciano Cavalcante De Souza','2024-05-09 11:57:48','2024-05-09 11:57:48',NULL),(34,9,'V. Paula Souza Marcondes Comercio','2024-05-09 11:58:56','2024-05-09 11:58:56',NULL),(35,8,'Br Led Atacadista Ltda','2024-05-09 12:00:30','2024-05-09 12:00:30',NULL),(36,3,'Ravanna Modas Confeccao Ltda','2024-05-09 12:00:53','2024-05-09 12:00:53',NULL),(37,3,'Ravana Modas Confeccao Ltda','2024-05-09 12:04:29','2024-05-09 12:04:29',NULL),(38,8,'Mitev  Casa Das Tintas  Ltda Me','2024-05-09 12:08:13','2024-05-09 12:08:13',NULL),(39,4,'C M Hospitalar S.a (rpo)','2024-05-09 12:09:27','2024-05-09 12:09:27',NULL),(40,4,'Eye Pharma Ltda','2024-05-09 12:11:38','2024-05-09 12:11:38',NULL),(41,4,'Vision Line Imp E Com De Mar Equip Med Ltda','2024-05-09 12:12:56','2024-05-09 12:12:56',NULL),(42,4,'Intertech Ind Prod Med Hosp Ltda Epp','2024-05-09 12:14:20','2024-05-09 12:14:20',NULL),(43,4,'Apta Hospitalar Dist Mat Medicos Ltda','2024-05-09 12:15:48','2024-05-09 12:15:48',NULL),(44,9,'Bia Editora Comercio Ltda','2024-05-09 16:40:53','2024-05-09 16:40:53',NULL);
/*!40000 ALTER TABLE `compras_fornecedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_notas`
--

DROP TABLE IF EXISTS `compras_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_notas` (
  `id_compra_nota` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `n_nota` char(20) NOT NULL,
  `valor` double(10,2) NOT NULL,
  `quantidade` double(10,2) DEFAULT NULL,
  `data` date NOT NULL,
  `id_empresa` int NOT NULL,
  `descricao` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_nota`),
  KEY `fk_compras_notas_compras_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_compras_notas_compras_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `compras_fornecedores` (`id_compra_fornecedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_notas`
--

LOCK TABLES `compras_notas` WRITE;
/*!40000 ALTER TABLE `compras_notas` DISABLE KEYS */;
INSERT INTO `compras_notas` VALUES (1,1,'94965',1273.00,NULL,'2024-04-04',1,'','2024-04-23 11:45:21','2024-04-23 11:45:21',NULL),(2,2,'2340759',312.12,NULL,'2024-04-18',1,'','2024-04-25 11:42:24','2024-04-25 11:42:24',NULL),(3,3,'417348',520.00,NULL,'2024-04-10',1,'','2024-04-25 11:44:33','2024-04-25 11:44:33',NULL),(4,4,'601165',472.55,NULL,'2024-04-23',1,'','2024-04-25 11:48:27','2024-04-25 11:48:27',NULL),(5,5,'623548',280.77,NULL,'2024-04-17',1,'','2024-04-25 11:49:39','2024-04-25 11:49:39',NULL),(6,6,'520059',183.66,NULL,'2024-04-15',1,'','2024-04-25 11:50:48','2024-04-25 11:50:48',NULL),(7,7,'1570833',1300.85,NULL,'2024-04-17',1,'','2024-04-25 11:51:52','2024-04-25 11:51:52',NULL),(8,7,'1570877',440.00,NULL,'2024-04-17',1,'','2024-04-25 11:52:20','2024-04-25 11:52:20',NULL),(9,7,'1570876',150.50,NULL,'2024-04-17',1,'','2024-04-25 11:52:46','2024-04-25 11:52:46',NULL),(10,5,'621532',552.72,NULL,'2024-04-15',1,'','2024-04-25 11:53:55','2024-04-25 11:53:55',NULL),(11,5,'621533',532.40,NULL,'2024-04-15',1,'','2024-04-25 11:54:15','2024-04-25 11:54:15',NULL),(12,5,'621531',225.26,NULL,'2024-04-15',1,'','2024-04-25 11:54:34','2024-04-25 11:54:34',NULL),(13,5,'609758',258.52,NULL,'2024-04-05',5,'','2024-04-25 11:55:08','2024-04-25 11:55:08',NULL),(14,8,'142180',3150.00,NULL,'2024-04-18',1,'','2024-04-25 11:55:56','2024-04-25 11:55:56',NULL),(15,11,'537382',1099.20,NULL,'2024-04-22',1,'','2024-04-25 12:14:43','2024-04-25 12:14:43',NULL),(16,15,'8628386',1512.69,NULL,'2024-04-20',2,'','2024-04-25 12:30:33','2024-04-25 12:30:33',NULL),(17,13,'6359',1344.00,NULL,'2024-04-18',1,'','2024-04-25 12:31:15','2024-04-25 12:31:15',NULL),(18,16,'3803',2289.20,NULL,'2024-04-16',2,'','2024-04-25 14:12:28','2024-04-25 14:12:28',NULL),(19,17,'237583',2258.77,NULL,'2024-04-24',3,'','2024-04-26 12:29:58','2024-04-26 12:29:58',NULL),(20,15,'8636240',2277.72,NULL,'2024-04-24',2,'','2024-04-27 09:10:20','2024-04-27 09:10:20',NULL),(21,15,'837176',877.43,NULL,'2024-04-24',4,'','2024-04-29 11:27:35','2024-04-29 11:27:35',NULL),(22,19,'13257',299.70,NULL,'2024-04-26',3,'','2024-04-29 12:19:52','2024-04-29 12:19:52',NULL),(23,8,'142775',1050.00,NULL,'2024-04-26',1,'','2024-04-29 16:49:39','2024-04-29 16:49:39',NULL),(24,1,'96537',1273.00,NULL,'2024-04-26',1,'','2024-04-29 16:52:04','2024-04-29 16:52:04',NULL),(25,20,'438681',752.79,NULL,'2024-04-24',1,'','2024-04-29 16:53:45','2024-04-29 16:53:45',NULL),(26,7,'1572029',302.14,NULL,'2024-04-19',3,'','2024-04-30 14:02:02','2024-04-30 14:02:02',NULL),(27,7,'1569409',140.00,NULL,'2024-04-15',3,'','2024-04-30 14:02:32','2024-04-30 14:02:32',NULL),(28,5,'629613',185.91,NULL,'2024-04-22',3,'','2024-04-30 14:04:36','2024-04-30 14:04:36',NULL),(29,5,'617936',588.70,NULL,'2024-04-12',3,'','2024-04-30 14:05:07','2024-04-30 14:05:07',NULL),(30,22,'1625',100.00,NULL,'2024-04-29',1,'','2024-04-30 14:10:40','2024-04-30 14:10:40',NULL),(31,23,'54230',498.00,0.00,'2024-04-29',1,'','2024-04-30 14:21:28','2024-05-03 10:18:42',NULL),(33,5,'51515',500.00,3.00,'2024-05-03',2,'','2024-05-03 07:52:25','2024-05-03 07:53:56','2024-05-03 08:42:30'),(34,7,'1576706',272.07,1.00,'2024-04-30',3,'','2024-05-09 11:32:00','2024-05-09 11:32:00',NULL),(35,7,'1576204',107.07,1.00,'2024-04-30',3,'','2024-05-09 11:32:39','2024-05-09 11:32:39',NULL),(36,7,'1576713',391.60,1.00,'2024-04-30',3,'','2024-05-09 11:33:06','2024-05-09 11:33:06',NULL),(37,24,'539408',289.80,1.00,'2024-04-25',3,'','2024-05-09 11:38:03','2024-05-09 11:38:03',NULL),(38,2,'2349248',317.98,1.00,'2024-04-24',3,'','2024-05-09 11:39:37','2024-05-09 11:39:37',NULL),(39,7,'1575417',150.50,1.00,'2024-04-29',3,'','2024-05-09 11:40:07','2024-05-09 11:40:07',NULL),(40,5,'639028',276.36,1.00,'2024-04-30',5,'','2024-05-09 11:40:42','2024-05-09 11:40:42',NULL),(41,2,'2340243',251.56,1.00,'2024-04-17',5,'','2024-05-09 11:41:51','2024-05-09 11:41:51',NULL),(42,5,'636286',276.36,1.00,'2024-04-29',1,'','2024-05-09 11:42:18','2024-05-09 11:42:18',NULL),(43,24,'540126',767.38,1.00,'2024-04-26',1,'','2024-05-09 11:43:04','2024-05-09 11:43:04',NULL),(44,26,'187534',120.00,1.00,'2024-04-26',1,'','2024-05-09 11:44:28','2024-05-09 11:44:28',NULL),(45,7,'1577003',891.38,1.00,'2024-05-02',1,'','2024-05-09 11:46:11','2024-05-09 11:46:11',NULL),(46,27,'143227',573.75,1.00,'2024-05-03',1,'','2024-05-09 11:47:42','2024-05-09 11:47:42',NULL),(47,3,'417348',520.00,1.00,'2024-04-10',3,'troca','2024-05-09 11:48:41','2024-05-09 11:48:41',NULL),(48,3,'420865',1820.00,1.00,'2024-05-03',1,'','2024-05-09 11:50:38','2024-05-09 11:50:38',NULL),(49,30,'1597',180.00,1.00,'2024-05-06',1,'','2024-05-09 11:53:06','2024-05-09 11:53:06',NULL),(50,28,'258933',52.63,1.00,'2024-04-10',1,'','2024-05-09 11:53:30','2024-05-09 11:53:30',NULL),(51,29,'19855',37.95,1.00,'2024-04-10',1,'','2024-05-09 11:54:02','2024-05-09 11:54:02',NULL),(52,31,'1922858',187.87,1.00,'2024-04-02',1,'','2024-05-09 12:01:30','2024-05-09 12:01:30',NULL),(53,31,'1932697',231.96,1.00,'2024-04-05',1,'','2024-05-09 12:01:58','2024-05-09 12:01:58',NULL),(54,32,'87469',135.79,1.00,'2024-04-10',1,'','2024-05-09 12:02:26','2024-05-09 12:02:26',NULL),(55,33,'4831',68.30,1.00,'2024-04-09',1,'','2024-05-09 12:02:50','2024-05-09 12:02:50',NULL),(56,34,'13487',115.70,1.00,'2024-04-29',1,'','2024-05-09 12:03:18','2024-05-09 12:03:18',NULL),(57,35,'142974',219.99,1.00,'2024-04-21',1,'','2024-05-09 12:03:42','2024-05-09 12:03:42',NULL),(58,37,'7330',139.25,1.00,'2024-04-27',1,'','2024-05-09 12:04:56','2024-05-09 12:04:56',NULL),(59,15,'8654231',571.39,3.00,'2024-04-30',4,'','2024-05-09 12:07:29','2024-05-09 12:32:40',NULL),(60,38,'5732',275.92,1.00,'2024-05-09',3,'','2024-05-09 12:08:44','2024-05-09 12:08:44',NULL),(61,39,'1453063',347.44,1.00,'2024-05-03',1,'','2024-05-09 12:10:02','2024-05-09 12:10:02',NULL),(62,40,'1127195',423.00,1.00,'2024-05-06',1,'','2024-05-09 12:12:06','2024-05-09 12:12:06',NULL),(63,41,'138103',643.00,1.00,'2024-05-02',1,'','2024-05-09 12:13:28','2024-05-09 12:13:28',NULL),(64,42,'38663',425.00,1.00,'2024-05-03',1,'','2024-05-09 12:14:48','2024-05-09 12:14:48',NULL),(65,43,'9346',507.32,1.00,'2024-05-02',1,'','2024-05-09 12:16:10','2024-05-09 12:16:10',NULL),(66,4,'607331',346.37,1.00,'2024-04-26',1,'','2024-05-09 12:19:27','2024-05-09 12:19:27',NULL),(67,17,'238305',2170.48,1.00,'2024-05-07',1,'','2024-05-09 12:19:51','2024-05-09 12:19:51',NULL),(68,44,'2130',30.00,1.00,'2024-05-09',1,'','2024-05-09 16:41:19','2024-05-09 16:41:19',NULL),(69,15,'8654231',571.39,1.00,'2024-04-30',4,'','2024-05-09 16:44:10','2024-05-09 16:44:10',NULL);
/*!40000 ALTER TABLE `compras_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `acao` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id_log`),
  KEY `fk_logs_idx` (`id_usuario`),
  CONSTRAINT `fk_logs` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,1,'Editar','Editou a nota de compra: 0555 (1)','2024-04-20 10:55:31'),(2,1,'Desativar','Desativou a nota de compra (1)','2024-04-20 10:57:49'),(3,1,'Editar','Editou a nota de compra: 0555 (1)','2024-04-20 11:03:48'),(4,1,'Cadastrar','Cadastrou a categoria de compra: Lente Intraocular (2)','2024-04-20 11:21:48'),(5,1,'Cadastrar','Cadastrou o fornecedor de compra: Carl Zeiss Do Brasil Ltda (6)','2024-04-20 11:22:19'),(6,1,'Cadastrar','Cadastrou a nota de compra: 94965 (3)','2024-04-20 11:23:28'),(7,1,'Cadastrar','Cadastrou a nota de compra: 600 (4)','2024-04-22 10:44:07'),(8,1,'Cadastrar','Cadastrou a nota de compra: 5161 (5)','2024-04-22 11:07:39'),(9,1,'Cadastrar','Cadastrou a nota de compra: 6166 (6)','2024-04-22 12:41:12'),(10,1,'Cadastrar','Cadastrou a nota de compra: 5151 (7)','2024-04-22 13:01:13'),(11,1,'Cadastrar','Cadastrou a nota de compra: 05500010 (8)','2024-04-23 11:14:37'),(12,1,'Cadastrar','Cadastrou o setor: Compras (3)','2024-04-23 11:28:02'),(13,1,'Cadastrar','Cadastrou o cargo: Assistente De Compra Sênior (19)','2024-04-23 11:28:22'),(14,1,'Cadastrar','Cadastrou o usuário: Alessandra Vieira Nunes (6)','2024-04-23 11:36:22'),(15,6,'Editar','Editou o usuário: Alessandra Vieira Nunes (6)','2024-04-23 11:40:24'),(16,6,'Cadastrar','Cadastrou a categoria de compra: Lente Intraocular (1)','2024-04-23 11:42:12'),(17,6,'Cadastrar','Cadastrou o fornecedor de compra: Carl Zeiss Do Brasil Ltda (1)','2024-04-23 11:43:59'),(18,6,'Cadastrar','Cadastrou a nota de compra: 94965 (1)','2024-04-23 11:45:21'),(19,6,'Cadastrar','Cadastrou a categoria de compra: Lente De Contato (2)','2024-04-25 11:40:09'),(20,6,'Cadastrar','Cadastrou o fornecedor de compra: Coopervision (2)','2024-04-25 11:41:25'),(21,6,'Cadastrar','Cadastrou a nota de compra: 2340759 (2)','2024-04-25 11:42:24'),(22,6,'Cadastrar','Cadastrou o fornecedor de compra: Adapt Produtos Oft Ltda (3)','2024-04-25 11:43:44'),(23,6,'Cadastrar','Cadastrou a nota de compra: 417348 (3)','2024-04-25 11:44:33'),(24,6,'Cadastrar','Cadastrou a categoria de compra: Produtos De Limpeza (3)','2024-04-25 11:47:29'),(25,6,'Cadastrar','Cadastrou o fornecedor de compra: Sales Distribuidora Ltda (4)','2024-04-25 11:47:58'),(26,6,'Cadastrar','Cadastrou a nota de compra: 601165 (4)','2024-04-25 11:48:27'),(27,6,'Cadastrar','Cadastrou o fornecedor de compra: Johnson & Johnson Br Ind Com. Prod. Saude Ltda (5)','2024-04-25 11:49:09'),(28,6,'Cadastrar','Cadastrou a nota de compra: 623548 (5)','2024-04-25 11:49:39'),(29,6,'Cadastrar','Cadastrou o fornecedor de compra: Bl Industria Ltda (6)','2024-04-25 11:50:08'),(30,6,'Cadastrar','Cadastrou a nota de compra: 520059 (6)','2024-04-25 11:50:48'),(31,6,'Cadastrar','Cadastrou o fornecedor de compra: Solotica Distr. De Prod. Opticos Eireli (7)','2024-04-25 11:51:25'),(32,6,'Cadastrar','Cadastrou a nota de compra: 1570833 (7)','2024-04-25 11:51:52'),(33,6,'Cadastrar','Cadastrou a nota de compra: 1570877 (8)','2024-04-25 11:52:20'),(34,6,'Cadastrar','Cadastrou a nota de compra: 1570876 (9)','2024-04-25 11:52:46'),(35,6,'Cadastrar','Cadastrou a nota de compra: 621532 (10)','2024-04-25 11:53:55'),(36,6,'Cadastrar','Cadastrou a nota de compra: 621533 (11)','2024-04-25 11:54:15'),(37,6,'Cadastrar','Cadastrou a nota de compra: 621531 (12)','2024-04-25 11:54:34'),(38,6,'Cadastrar','Cadastrou a nota de compra: 609758 (13)','2024-04-25 11:55:08'),(39,6,'Cadastrar','Cadastrou o fornecedor de compra: Ophthalmos Ltda (8)','2024-04-25 11:55:34'),(40,6,'Cadastrar','Cadastrou a nota de compra: 142180 (14)','2024-04-25 11:55:56'),(41,6,'Cadastrar','Cadastrou a categoria de compra: Medicamentos (4)','2024-04-25 11:56:54'),(42,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (9)','2024-04-25 11:57:15'),(43,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (10)','2024-04-25 11:58:25'),(44,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (11)','2024-04-25 11:59:58'),(45,6,'Desativar','Desativou o fornecedor de compra Alcon (9)','2024-04-25 12:00:48'),(46,6,'Desativar','Desativou o fornecedor de compra Alcon (10)','2024-04-25 12:00:51'),(47,6,'Editar','Editou o fornecedor de compra: Alcon (11)','2024-04-25 12:01:25'),(48,6,'Editar','Editou o fornecedor de compra: Alcon (11)','2024-04-25 12:01:31'),(49,6,'Cadastrar','Cadastrou a nota de compra: 537382 (15)','2024-04-25 12:14:43'),(50,6,'Cadastrar','Cadastrou o fornecedor de compra: Adapt Hospitalra Dist Medicamentos Ltda (12)','2024-04-25 12:15:13'),(51,6,'Cadastrar','Cadastrou o fornecedor de compra: Beta Surgical Materiais Medicos Ltda (13)','2024-04-25 12:15:47'),(52,6,'Cadastrar','Cadastrou a categoria de compra: ArmaÇÕes (5)','2024-04-25 12:16:07'),(53,6,'Cadastrar','Cadastrou o fornecedor de compra: Luxottica Brasil Prod Oticos Esp Ltda (14)','2024-04-25 12:16:52'),(54,6,'Desativar','Desativou o fornecedor de compra Luxottica Brasil Prod Oticos Esp Ltda (14)','2024-04-25 12:17:06'),(55,6,'Cadastrar','Cadastrou a categoria de compra: ArmaÇÕes (6)','2024-04-25 12:17:18'),(56,6,'Desativar','Desativou a categoria de compra ArmaÇÕes (6)','2024-04-25 12:17:23'),(57,6,'Cadastrar','Cadastrou o fornecedor de compra: Luxottica Brasil Prod Oticos Esp Ltda (15)','2024-04-25 12:17:45'),(58,6,'Editar','Editou a categoria de compra: Armações (5)','2024-04-25 12:23:22'),(59,6,'Cadastrar','Cadastrou a nota de compra: 8628386 (16)','2024-04-25 12:30:33'),(60,6,'Cadastrar','Cadastrou a nota de compra: 6359 (17)','2024-04-25 12:31:15'),(61,6,'Cadastrar','Cadastrou a categoria de compra: Estojo De Oculos (7)','2024-04-25 14:11:47'),(62,6,'Cadastrar','Cadastrou o fornecedor de compra: J L Injecao De Plasticos Ltda (16)','2024-04-25 14:11:58'),(63,6,'Cadastrar','Cadastrou a nota de compra: 3803 (18)','2024-04-25 14:12:28'),(64,6,'Cadastrar','Cadastrou a categoria de compra: Manutencao Predial (8)','2024-04-25 14:13:08'),(65,6,'Cadastrar','Cadastrou o fornecedor de compra: 3 De Maio (17)','2024-04-25 14:13:34'),(66,6,'Cadastrar','Cadastrou o fornecedor de compra: Kenerson Ind E Com De Prod Opticos Ltda (18)','2024-04-25 14:13:59'),(67,6,'Cadastrar','Cadastrou a nota de compra: 237583 (19)','2024-04-26 12:29:58'),(68,6,'Cadastrar','Cadastrou a nota de compra: 8636240 (20)','2024-04-27 09:10:20'),(69,6,'Cadastrar','Cadastrou a nota de compra: 837176 (21)','2024-04-29 11:27:35'),(70,6,'Cadastrar','Cadastrou o fornecedor de compra: Raami Comercio De Produtos De Limpeza Ltda (19)','2024-04-29 12:19:25'),(71,6,'Cadastrar','Cadastrou a nota de compra: 13257 (22)','2024-04-29 12:19:52'),(72,6,'Cadastrar','Cadastrou a nota de compra: 142775 (23)','2024-04-29 16:49:39'),(73,6,'Cadastrar','Cadastrou a nota de compra: 96537 (24)','2024-04-29 16:52:04'),(74,6,'Cadastrar','Cadastrou o fornecedor de compra: Jjsv Produtos óticos Ltda (20)','2024-04-29 16:53:17'),(75,6,'Cadastrar','Cadastrou a nota de compra: 438681 (25)','2024-04-29 16:53:45'),(76,6,'Cadastrar','Cadastrou a nota de compra: 1572029 (26)','2024-04-30 14:02:02'),(77,6,'Cadastrar','Cadastrou a nota de compra: 1569409 (27)','2024-04-30 14:02:32'),(78,6,'Cadastrar','Cadastrou a nota de compra: 629613 (28)','2024-04-30 14:04:36'),(79,6,'Cadastrar','Cadastrou a nota de compra: 617936 (29)','2024-04-30 14:05:07'),(80,6,'Cadastrar','Cadastrou a categoria de compra: Materiais De Escritorio (9)','2024-04-30 14:09:21'),(81,6,'Editar','Editou a categoria de compra: Materiais De Escritorio / (9)','2024-04-30 14:09:55'),(82,6,'Cadastrar','Cadastrou o fornecedor de compra: Wg Negocios Corporativos (22)','2024-04-30 14:10:12'),(83,6,'Cadastrar','Cadastrou a nota de compra: 1625 (30)','2024-04-30 14:10:40'),(84,6,'Cadastrar','Cadastrou a nota de compra: 54230 (31)','2024-04-30 14:21:28'),(85,1,'Editar','Editou a categoria de compra: Materiais De Escritorio (9)','2024-05-02 12:27:42'),(86,1,'Cadastrar','Cadastrou a nota de compra: 51515 (32)','2024-05-03 07:46:12'),(87,1,'Cadastrar','Cadastrou a nota de compra: 51515 (33)','2024-05-03 07:52:25'),(88,1,'Editar','Editou a nota de compra: 51515 (33)','2024-05-03 07:53:56'),(89,1,'Desativar','Desativou a nota de compra (33)','2024-05-03 08:42:30'),(90,6,'Cadastrar','Cadastrou a categoria de compra: Manutenção De Equipamentos (10)','2024-05-03 10:17:57'),(91,6,'Cadastrar','Cadastrou o fornecedor de compra: Wg Negocios Corporativos (23)','2024-05-03 10:18:17'),(92,6,'Editar','Editou a nota de compra: 54230 (31)','2024-05-03 10:18:42'),(93,6,'Cadastrar','Cadastrou a nota de compra: 1576706 (34)','2024-05-09 11:32:00'),(94,6,'Cadastrar','Cadastrou a nota de compra: 1576204 (35)','2024-05-09 11:32:39'),(95,6,'Cadastrar','Cadastrou a nota de compra: 1576713 (36)','2024-05-09 11:33:06'),(96,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (24)','2024-05-09 11:35:47'),(97,6,'Cadastrar','Cadastrou a nota de compra: 539408 (37)','2024-05-09 11:38:03'),(98,6,'Cadastrar','Cadastrou o fornecedor de compra: Coopervision  Do Brasil Ltda (25)','2024-05-09 11:38:59'),(99,6,'Cadastrar','Cadastrou a nota de compra: 2349248 (38)','2024-05-09 11:39:37'),(100,6,'Cadastrar','Cadastrou a nota de compra: 1575417 (39)','2024-05-09 11:40:07'),(101,6,'Cadastrar','Cadastrou a nota de compra: 639028 (40)','2024-05-09 11:40:42'),(102,6,'Cadastrar','Cadastrou a nota de compra: 2340243 (41)','2024-05-09 11:41:51'),(103,6,'Cadastrar','Cadastrou a nota de compra: 636286 (42)','2024-05-09 11:42:18'),(104,6,'Cadastrar','Cadastrou a nota de compra: 540126 (43)','2024-05-09 11:43:04'),(105,6,'Cadastrar','Cadastrou o fornecedor de compra: Optlentes Lentes De Contato Ltda (26)','2024-05-09 11:43:49'),(106,6,'Cadastrar','Cadastrou a nota de compra: 187534 (44)','2024-05-09 11:44:28'),(107,6,'Editar','Editou o fornecedor de compra: Optolentes Lentes De Contato Ltda (26)','2024-05-09 11:45:04'),(108,6,'Cadastrar','Cadastrou a nota de compra: 1577003 (45)','2024-05-09 11:46:11'),(109,6,'Cadastrar','Cadastrou o fornecedor de compra: Ophthalmos Ltda (27)','2024-05-09 11:47:09'),(110,6,'Cadastrar','Cadastrou a nota de compra: 143227 (46)','2024-05-09 11:47:42'),(111,6,'Cadastrar','Cadastrou a nota de compra: 417348 (47)','2024-05-09 11:48:41'),(112,6,'Cadastrar','Cadastrou a nota de compra: 420865 (48)','2024-05-09 11:50:38'),(113,6,'Cadastrar','Cadastrou o fornecedor de compra: Agf Express Materiais Medicos Ltda (28)','2024-05-09 11:51:16'),(114,6,'Editar','Editou a categoria de compra: Medicamentos / Material Hospitalar (4)','2024-05-09 11:51:38'),(115,6,'Cadastrar','Cadastrou o fornecedor de compra: Maycare Distribuidora Importadora (29)','2024-05-09 11:52:12'),(116,6,'Cadastrar','Cadastrou o fornecedor de compra: Thiago Rodrigues Cardoso 07541451665 (30)','2024-05-09 11:52:42'),(117,6,'Cadastrar','Cadastrou a nota de compra: 1597 (49)','2024-05-09 11:53:06'),(118,6,'Cadastrar','Cadastrou a nota de compra: 258933 (50)','2024-05-09 11:53:30'),(119,6,'Cadastrar','Cadastrou a nota de compra: 19855 (51)','2024-05-09 11:54:02'),(120,6,'Cadastrar','Cadastrou o fornecedor de compra: Zerbini Do Brasil Ltda (31)','2024-05-09 11:55:36'),(121,6,'Cadastrar','Cadastrou o fornecedor de compra: Marcos Padovan Informatica Me (32)','2024-05-09 11:56:04'),(122,6,'Cadastrar','Cadastrou o fornecedor de compra: Luciano Cavalcante De Souza (33)','2024-05-09 11:57:48'),(123,6,'Cadastrar','Cadastrou o fornecedor de compra: V. Paula Souza Marcondes Comercio (34)','2024-05-09 11:58:56'),(124,6,'Cadastrar','Cadastrou o fornecedor de compra: Br Led Atacadista Ltda (35)','2024-05-09 12:00:30'),(125,6,'Cadastrar','Cadastrou o fornecedor de compra: Ravanna Modas Confeccao Ltda (36)','2024-05-09 12:00:53'),(126,6,'Cadastrar','Cadastrou a nota de compra: 1922858 (52)','2024-05-09 12:01:30'),(127,6,'Cadastrar','Cadastrou a nota de compra: 1932697 (53)','2024-05-09 12:01:58'),(128,6,'Cadastrar','Cadastrou a nota de compra: 87469 (54)','2024-05-09 12:02:26'),(129,6,'Cadastrar','Cadastrou a nota de compra: 4831 (55)','2024-05-09 12:02:51'),(130,6,'Cadastrar','Cadastrou a nota de compra: 13487 (56)','2024-05-09 12:03:18'),(131,6,'Cadastrar','Cadastrou a nota de compra: 142974 (57)','2024-05-09 12:03:42'),(132,6,'Cadastrar','Cadastrou o fornecedor de compra: Ravana Modas Confeccao Ltda (37)','2024-05-09 12:04:29'),(133,6,'Cadastrar','Cadastrou a nota de compra: 7330 (58)','2024-05-09 12:04:56'),(134,6,'Cadastrar','Cadastrou a nota de compra: 8654231 (59)','2024-05-09 12:07:29'),(135,6,'Cadastrar','Cadastrou o fornecedor de compra: Mitev  Casa Das Tintas  Ltda Me (38)','2024-05-09 12:08:13'),(136,6,'Cadastrar','Cadastrou a nota de compra: 5732 (60)','2024-05-09 12:08:44'),(137,6,'Cadastrar','Cadastrou o fornecedor de compra: C M Hospitalar S.a (rpo) (39)','2024-05-09 12:09:27'),(138,6,'Cadastrar','Cadastrou a nota de compra: 1453063 (61)','2024-05-09 12:10:02'),(139,6,'Cadastrar','Cadastrou o fornecedor de compra: Eye Pharma Ltda (40)','2024-05-09 12:11:38'),(140,6,'Cadastrar','Cadastrou a nota de compra: 1127195 (62)','2024-05-09 12:12:06'),(141,6,'Cadastrar','Cadastrou o fornecedor de compra: Vision Line Imp E Com De Mar Equip Med Ltda (41)','2024-05-09 12:12:56'),(142,6,'Cadastrar','Cadastrou a nota de compra: 138103 (63)','2024-05-09 12:13:28'),(143,6,'Cadastrar','Cadastrou o fornecedor de compra: Intertech Ind Prod Med Hosp Ltda Epp (42)','2024-05-09 12:14:20'),(144,6,'Cadastrar','Cadastrou a nota de compra: 38663 (64)','2024-05-09 12:14:48'),(145,6,'Cadastrar','Cadastrou o fornecedor de compra: Apta Hospitalar Dist Mat Medicos Ltda (43)','2024-05-09 12:15:48'),(146,6,'Cadastrar','Cadastrou a nota de compra: 9346 (65)','2024-05-09 12:16:11'),(147,6,'Editar','Editou a categoria de compra: Manutenção Predial (8)','2024-05-09 12:19:00'),(148,6,'Cadastrar','Cadastrou a nota de compra: 607331 (66)','2024-05-09 12:19:27'),(149,6,'Cadastrar','Cadastrou a nota de compra: 238305 (67)','2024-05-09 12:19:51'),(150,6,'Editar','Editou a nota de compra: 8654231 (59)','2024-05-09 12:32:40'),(151,6,'Cadastrar','Cadastrou o fornecedor de compra: Bia Editora Comercio Ltda (44)','2024-05-09 16:40:53'),(152,6,'Cadastrar','Cadastrou a nota de compra: 2130 (68)','2024-05-09 16:41:19'),(153,6,'Cadastrar','Cadastrou a nota de compra: 8654231 (69)','2024-05-09 16:44:10'),(155,1,'Editar','Editou o projeto de ID: 1 e Descrição: abc','2024-05-10 09:03:13'),(156,1,'Editar','Editou o projeto de ID: 1 e Descrição: a','2024-05-10 09:03:16'),(157,1,'Editar','Editou o projeto de ID: 1 e Descrição: a','2024-05-10 09:03:38'),(158,1,'Editar','Editou o projeto de ID: 1 e Descrição: a','2024-05-10 09:03:40'),(159,1,'Desativar','Desativou o projeto de ID: 1','2024-05-10 09:05:41'),(160,1,'Desativar','Desativou o projeto de ID: 1','2024-05-10 09:10:14');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens` (
  `id_mensagem` int NOT NULL AUTO_INCREMENT,
  `id_chat` int NOT NULL,
  `id_usuario` int NOT NULL,
  `mensagem` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_mensagem`),
  KEY `fk_mensagens_chat_idx` (`id_chat`),
  KEY `fk_mensgens_usuarios_idx` (`id_usuario`),
  CONSTRAINT `fk_mensagens_chat` FOREIGN KEY (`id_chat`) REFERENCES `chats` (`id_chat`),
  CONSTRAINT `fk_mensgens_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens`
--

LOCK TABLES `mensagens` WRITE;
/*!40000 ALTER TABLE `mensagens` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projetos`
--

DROP TABLE IF EXISTS `projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projetos` (
  `id_projeto` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `id_usuario` int NOT NULL,
  `status` tinyint NOT NULL,
  `descricao` text,
  `data_conclusao` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_projeto`),
  KEY `fk_projeto_usuario_idx` (`id_usuario`),
  CONSTRAINT `fk_projeto_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projetos`
--

LOCK TABLES `projetos` WRITE;
/*!40000 ALTER TABLE `projetos` DISABLE KEYS */;
INSERT INTO `projetos` VALUES (1,'Teste',1,1,'a','2024-05-20','2024-05-10 08:15:30','2024-05-10 09:03:40',NULL);
/*!40000 ALTER TABLE `projetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setores`
--

DROP TABLE IF EXISTS `setores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setores` (
  `id_setor` int NOT NULL AUTO_INCREMENT,
  `setor` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_setor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setores`
--

LOCK TABLES `setores` WRITE;
/*!40000 ALTER TABLE `setores` DISABLE KEYS */;
INSERT INTO `setores` VALUES (1,'Tecnologia da Informação','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Recursos Humanos','2024-04-19 08:24:29','2024-04-19 08:49:16',NULL),(3,'Compras','2024-04-23 11:28:02','2024-04-23 11:28:02',NULL);
/*!40000 ALTER TABLE `setores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `usuario` varchar(60) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `contrato` int NOT NULL,
  `celular` char(11) NOT NULL,
  `cpf` char(11) NOT NULL,
  `data_nascimento` date NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `empresa` tinyint NOT NULL,
  `id_setor` int NOT NULL,
  `id_cargo` int NOT NULL,
  `n_folha` varchar(25) DEFAULT NULL,
  `data_admissao` date NOT NULL,
  `rg` varchar(100) DEFAULT NULL,
  `foto3x4` varchar(100) DEFAULT NULL,
  `residencia` varchar(100) DEFAULT NULL,
  `contrato_nube` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuarios_cargos_idx` (`id_cargo`),
  KEY `fk_usuarios_setores_idx` (`id_setor`),
  CONSTRAINT `fk_usuarios_cargos` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`),
  CONSTRAINT `fk_usuarios_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Kaique Rodrigues de Souza','Kaique.Souza','GR2Os.QfJQoPc',1,'11983469094','37498968878','2004-07-10','ykaiqz17@gmail.com',2,1,1,'','2023-11-01','rg.jpg',NULL,NULL,NULL,'2024-04-17 08:11:30','2024-04-17 08:11:30',NULL),(3,'André dos Santos','André.Santos','GRuxKUiOXQSio',0,'11965022187','23863640896','2002-06-24','',2,1,3,'','2022-07-01',NULL,NULL,NULL,NULL,'2024-04-17 08:54:48','2024-04-19 11:34:00',NULL),(4,'Diego Diniz Flieber Ferreira','Diego.Diniz','GRLPylnit1wvA',1,'11952450209','50873738870','2005-04-16','dinizdiego1320@gmail.com',1,1,1,'','2024-05-04',NULL,NULL,NULL,NULL,'2024-04-19 09:00:30','2024-04-19 09:00:30',NULL),(5,'Elaine Regina Da Silva','Elaine.Silva','GRLPylnit1wvA',0,'11998519254','28265002874','1978-08-03','',2,2,18,'','2024-02-14',NULL,NULL,NULL,NULL,'2024-04-19 11:53:04','2024-04-19 12:02:00',NULL),(6,'Alessandra Vieira Nunes','Alessandra.Nunes','GRovqgYqlmmpk',0,'11958082806','16149457800','1974-09-10','',1,3,19,'','2017-06-06',NULL,NULL,NULL,NULL,'2024-04-23 11:36:22','2024-04-23 11:40:00',NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-10 10:30:56
