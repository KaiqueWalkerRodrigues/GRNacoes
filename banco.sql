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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_categorias`
--

LOCK TABLES `compras_categorias` WRITE;
/*!40000 ALTER TABLE `compras_categorias` DISABLE KEYS */;
INSERT INTO `compras_categorias` VALUES (1,'Lente Intraocular','2024-04-23 11:42:12','2024-04-23 11:42:12',NULL),(2,'Lente De Contato','2024-04-25 11:40:09','2024-04-25 11:40:09',NULL),(3,'Produtos De Limpeza','2024-04-25 11:47:29','2024-04-25 11:47:29',NULL),(4,'Medicamentos','2024-04-25 11:56:54','2024-04-25 11:56:54',NULL),(5,'Armações','2024-04-25 12:16:07','2024-04-25 12:23:22',NULL),(6,'ArmaÇÕes','2024-04-25 12:17:18','2024-04-25 12:17:18','2024-04-25 12:17:23'),(7,'Estojo De Oculos','2024-04-25 14:11:47','2024-04-25 14:11:47',NULL),(8,'Manutencao Predial','2024-04-25 14:13:08','2024-04-25 14:13:08',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_fornecedores`
--

LOCK TABLES `compras_fornecedores` WRITE;
/*!40000 ALTER TABLE `compras_fornecedores` DISABLE KEYS */;
INSERT INTO `compras_fornecedores` VALUES (1,1,'Carl Zeiss Do Brasil Ltda','2024-04-23 11:43:59','2024-04-23 11:43:59',NULL),(2,2,'Coopervision','2024-04-25 11:41:25','2024-04-25 11:41:25',NULL),(3,1,'Adapt Produtos Oft Ltda','2024-04-25 11:43:44','2024-04-25 11:43:44',NULL),(4,3,'Sales Distribuidora Ltda','2024-04-25 11:47:58','2024-04-25 11:47:58',NULL),(5,2,'Johnson & Johnson Br Ind Com. Prod. Saude Ltda','2024-04-25 11:49:09','2024-04-25 11:49:09',NULL),(6,2,'Bl Industria Ltda','2024-04-25 11:50:08','2024-04-25 11:50:08',NULL),(7,2,'Solotica Distr. De Prod. Opticos Eireli','2024-04-25 11:51:25','2024-04-25 11:51:25',NULL),(8,1,'Ophthalmos Ltda','2024-04-25 11:55:34','2024-04-25 11:55:34',NULL),(9,4,'Alcon','2024-04-25 11:57:15','2024-04-25 11:57:15','2024-04-25 12:00:48'),(10,4,'Alcon','2024-04-25 11:58:25','2024-04-25 11:58:25','2024-04-25 12:00:51'),(11,4,'Alcon','2024-04-25 11:59:58','2024-04-25 12:01:31',NULL),(12,4,'Adapt Hospitalra Dist Medicamentos Ltda','2024-04-25 12:15:13','2024-04-25 12:15:13',NULL),(13,1,'Beta Surgical Materiais Medicos Ltda','2024-04-25 12:15:47','2024-04-25 12:15:47',NULL),(14,5,'Luxottica Brasil Prod Oticos Esp Ltda','2024-04-25 12:16:52','2024-04-25 12:16:52','2024-04-25 12:17:06'),(15,5,'Luxottica Brasil Prod Oticos Esp Ltda','2024-04-25 12:17:45','2024-04-25 12:17:45',NULL),(16,7,'J L Injecao De Plasticos Ltda','2024-04-25 14:11:58','2024-04-25 14:11:58',NULL),(17,8,'3 De Maio','2024-04-25 14:13:34','2024-04-25 14:13:34',NULL),(18,5,'Kenerson Ind E Com De Prod Opticos Ltda','2024-04-25 14:13:59','2024-04-25 14:13:59',NULL);
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
  `data` date NOT NULL,
  `id_empresa` int NOT NULL,
  `descricao` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_nota`),
  KEY `fk_compras_notas_compras_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_compras_notas_compras_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `compras_fornecedores` (`id_compra_fornecedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_notas`
--

LOCK TABLES `compras_notas` WRITE;
/*!40000 ALTER TABLE `compras_notas` DISABLE KEYS */;
INSERT INTO `compras_notas` VALUES (1,1,'94965',1273.00,'2024-04-04',1,'','2024-04-23 11:45:21','2024-04-23 11:45:21',NULL),(2,2,'2340759',312.12,'2024-04-18',1,'','2024-04-25 11:42:24','2024-04-25 11:42:24',NULL),(3,3,'417348',520.00,'2024-04-10',1,'','2024-04-25 11:44:33','2024-04-25 11:44:33',NULL),(4,4,'601165',472.55,'2024-04-23',1,'','2024-04-25 11:48:27','2024-04-25 11:48:27',NULL),(5,5,'623548',280.77,'2024-04-17',1,'','2024-04-25 11:49:39','2024-04-25 11:49:39',NULL),(6,6,'520059',183.66,'2024-04-15',1,'','2024-04-25 11:50:48','2024-04-25 11:50:48',NULL),(7,7,'1570833',1300.85,'2024-04-17',1,'','2024-04-25 11:51:52','2024-04-25 11:51:52',NULL),(8,7,'1570877',440.00,'2024-04-17',1,'','2024-04-25 11:52:20','2024-04-25 11:52:20',NULL),(9,7,'1570876',150.50,'2024-04-17',1,'','2024-04-25 11:52:46','2024-04-25 11:52:46',NULL),(10,5,'621532',552.72,'2024-04-15',1,'','2024-04-25 11:53:55','2024-04-25 11:53:55',NULL),(11,5,'621533',532.40,'2024-04-15',1,'','2024-04-25 11:54:15','2024-04-25 11:54:15',NULL),(12,5,'621531',225.26,'2024-04-15',1,'','2024-04-25 11:54:34','2024-04-25 11:54:34',NULL),(13,5,'609758',258.52,'2024-04-05',5,'','2024-04-25 11:55:08','2024-04-25 11:55:08',NULL),(14,8,'142180',3150.00,'2024-04-18',1,'','2024-04-25 11:55:56','2024-04-25 11:55:56',NULL),(15,11,'537382',1099.20,'2024-04-22',1,'','2024-04-25 12:14:43','2024-04-25 12:14:43',NULL),(16,15,'8628386',1512.69,'2024-04-20',2,'','2024-04-25 12:30:33','2024-04-25 12:30:33',NULL),(17,13,'6359',1344.00,'2024-04-18',1,'','2024-04-25 12:31:15','2024-04-25 12:31:15',NULL),(18,16,'3803',2289.20,'2024-04-16',2,'','2024-04-25 14:12:28','2024-04-25 14:12:28',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,1,'Editar','Editou a nota de compra: 0555 (1)','2024-04-20 10:55:31'),(2,1,'Desativar','Desativou a nota de compra (1)','2024-04-20 10:57:49'),(3,1,'Editar','Editou a nota de compra: 0555 (1)','2024-04-20 11:03:48'),(4,1,'Cadastrar','Cadastrou a categoria de compra: Lente Intraocular (2)','2024-04-20 11:21:48'),(5,1,'Cadastrar','Cadastrou o fornecedor de compra: Carl Zeiss Do Brasil Ltda (6)','2024-04-20 11:22:19'),(6,1,'Cadastrar','Cadastrou a nota de compra: 94965 (3)','2024-04-20 11:23:28'),(7,1,'Cadastrar','Cadastrou a nota de compra: 600 (4)','2024-04-22 10:44:07'),(8,1,'Cadastrar','Cadastrou a nota de compra: 5161 (5)','2024-04-22 11:07:39'),(9,1,'Cadastrar','Cadastrou a nota de compra: 6166 (6)','2024-04-22 12:41:12'),(10,1,'Cadastrar','Cadastrou a nota de compra: 5151 (7)','2024-04-22 13:01:13'),(11,1,'Cadastrar','Cadastrou a nota de compra: 05500010 (8)','2024-04-23 11:14:37'),(12,1,'Cadastrar','Cadastrou o setor: Compras (3)','2024-04-23 11:28:02'),(13,1,'Cadastrar','Cadastrou o cargo: Assistente De Compra Sênior (19)','2024-04-23 11:28:22'),(14,1,'Cadastrar','Cadastrou o usuário: Alessandra Vieira Nunes (6)','2024-04-23 11:36:22'),(15,6,'Editar','Editou o usuário: Alessandra Vieira Nunes (6)','2024-04-23 11:40:24'),(16,6,'Cadastrar','Cadastrou a categoria de compra: Lente Intraocular (1)','2024-04-23 11:42:12'),(17,6,'Cadastrar','Cadastrou o fornecedor de compra: Carl Zeiss Do Brasil Ltda (1)','2024-04-23 11:43:59'),(18,6,'Cadastrar','Cadastrou a nota de compra: 94965 (1)','2024-04-23 11:45:21'),(19,6,'Cadastrar','Cadastrou a categoria de compra: Lente De Contato (2)','2024-04-25 11:40:09'),(20,6,'Cadastrar','Cadastrou o fornecedor de compra: Coopervision (2)','2024-04-25 11:41:25'),(21,6,'Cadastrar','Cadastrou a nota de compra: 2340759 (2)','2024-04-25 11:42:24'),(22,6,'Cadastrar','Cadastrou o fornecedor de compra: Adapt Produtos Oft Ltda (3)','2024-04-25 11:43:44'),(23,6,'Cadastrar','Cadastrou a nota de compra: 417348 (3)','2024-04-25 11:44:33'),(24,6,'Cadastrar','Cadastrou a categoria de compra: Produtos De Limpeza (3)','2024-04-25 11:47:29'),(25,6,'Cadastrar','Cadastrou o fornecedor de compra: Sales Distribuidora Ltda (4)','2024-04-25 11:47:58'),(26,6,'Cadastrar','Cadastrou a nota de compra: 601165 (4)','2024-04-25 11:48:27'),(27,6,'Cadastrar','Cadastrou o fornecedor de compra: Johnson & Johnson Br Ind Com. Prod. Saude Ltda (5)','2024-04-25 11:49:09'),(28,6,'Cadastrar','Cadastrou a nota de compra: 623548 (5)','2024-04-25 11:49:39'),(29,6,'Cadastrar','Cadastrou o fornecedor de compra: Bl Industria Ltda (6)','2024-04-25 11:50:08'),(30,6,'Cadastrar','Cadastrou a nota de compra: 520059 (6)','2024-04-25 11:50:48'),(31,6,'Cadastrar','Cadastrou o fornecedor de compra: Solotica Distr. De Prod. Opticos Eireli (7)','2024-04-25 11:51:25'),(32,6,'Cadastrar','Cadastrou a nota de compra: 1570833 (7)','2024-04-25 11:51:52'),(33,6,'Cadastrar','Cadastrou a nota de compra: 1570877 (8)','2024-04-25 11:52:20'),(34,6,'Cadastrar','Cadastrou a nota de compra: 1570876 (9)','2024-04-25 11:52:46'),(35,6,'Cadastrar','Cadastrou a nota de compra: 621532 (10)','2024-04-25 11:53:55'),(36,6,'Cadastrar','Cadastrou a nota de compra: 621533 (11)','2024-04-25 11:54:15'),(37,6,'Cadastrar','Cadastrou a nota de compra: 621531 (12)','2024-04-25 11:54:34'),(38,6,'Cadastrar','Cadastrou a nota de compra: 609758 (13)','2024-04-25 11:55:08'),(39,6,'Cadastrar','Cadastrou o fornecedor de compra: Ophthalmos Ltda (8)','2024-04-25 11:55:34'),(40,6,'Cadastrar','Cadastrou a nota de compra: 142180 (14)','2024-04-25 11:55:56'),(41,6,'Cadastrar','Cadastrou a categoria de compra: Medicamentos (4)','2024-04-25 11:56:54'),(42,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (9)','2024-04-25 11:57:15'),(43,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (10)','2024-04-25 11:58:25'),(44,6,'Cadastrar','Cadastrou o fornecedor de compra: Alcon (11)','2024-04-25 11:59:58'),(45,6,'Desativar','Desativou o fornecedor de compra Alcon (9)','2024-04-25 12:00:48'),(46,6,'Desativar','Desativou o fornecedor de compra Alcon (10)','2024-04-25 12:00:51'),(47,6,'Editar','Editou o fornecedor de compra: Alcon (11)','2024-04-25 12:01:25'),(48,6,'Editar','Editou o fornecedor de compra: Alcon (11)','2024-04-25 12:01:31'),(49,6,'Cadastrar','Cadastrou a nota de compra: 537382 (15)','2024-04-25 12:14:43'),(50,6,'Cadastrar','Cadastrou o fornecedor de compra: Adapt Hospitalra Dist Medicamentos Ltda (12)','2024-04-25 12:15:13'),(51,6,'Cadastrar','Cadastrou o fornecedor de compra: Beta Surgical Materiais Medicos Ltda (13)','2024-04-25 12:15:47'),(52,6,'Cadastrar','Cadastrou a categoria de compra: ArmaÇÕes (5)','2024-04-25 12:16:07'),(53,6,'Cadastrar','Cadastrou o fornecedor de compra: Luxottica Brasil Prod Oticos Esp Ltda (14)','2024-04-25 12:16:52'),(54,6,'Desativar','Desativou o fornecedor de compra Luxottica Brasil Prod Oticos Esp Ltda (14)','2024-04-25 12:17:06'),(55,6,'Cadastrar','Cadastrou a categoria de compra: ArmaÇÕes (6)','2024-04-25 12:17:18'),(56,6,'Desativar','Desativou a categoria de compra ArmaÇÕes (6)','2024-04-25 12:17:23'),(57,6,'Cadastrar','Cadastrou o fornecedor de compra: Luxottica Brasil Prod Oticos Esp Ltda (15)','2024-04-25 12:17:45'),(58,6,'Editar','Editou a categoria de compra: Armações (5)','2024-04-25 12:23:22'),(59,6,'Cadastrar','Cadastrou a nota de compra: 8628386 (16)','2024-04-25 12:30:33'),(60,6,'Cadastrar','Cadastrou a nota de compra: 6359 (17)','2024-04-25 12:31:15'),(61,6,'Cadastrar','Cadastrou a categoria de compra: Estojo De Oculos (7)','2024-04-25 14:11:47'),(62,6,'Cadastrar','Cadastrou o fornecedor de compra: J L Injecao De Plasticos Ltda (16)','2024-04-25 14:11:58'),(63,6,'Cadastrar','Cadastrou a nota de compra: 3803 (18)','2024-04-25 14:12:28'),(64,6,'Cadastrar','Cadastrou a categoria de compra: Manutencao Predial (8)','2024-04-25 14:13:08'),(65,6,'Cadastrar','Cadastrou o fornecedor de compra: 3 De Maio (17)','2024-04-25 14:13:34'),(66,6,'Cadastrar','Cadastrou o fornecedor de compra: Kenerson Ind E Com De Prod Opticos Ltda (18)','2024-04-25 14:13:59');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
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

-- Dump completed on 2024-04-26 10:52:45
