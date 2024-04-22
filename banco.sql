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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
INSERT INTO `cargos` VALUES (1,'Auxiliar de Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(3,'Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(4,'Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(6,'Auxiliar de Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(7,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00','2024-04-18 09:59:40'),(8,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','2024-04-18 12:45:33',NULL),(9,'Vendedor Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(10,'Vendedor Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(11,'Vendedor Senior ','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(12,'Auxiliar de Serviços Gerais Junior','2024-04-18 11:34:30','2024-04-18 11:43:16',NULL),(13,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:24:05','2024-04-18 12:24:05',NULL),(14,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:31:32','2024-04-18 12:31:32','2024-04-18 12:32:12'),(15,'Vendedor Senior','2024-04-18 12:34:01','2024-04-18 12:34:01','2024-04-18 12:43:15'),(16,'Auxiliar de Serviços Gerais Senior','2024-04-18 12:43:50','2024-04-18 12:44:33',NULL),(17,'teste','2024-04-18 12:55:34','2024-04-18 12:55:34','2024-04-18 12:57:50'),(18,'Recursos Humanos','2024-04-19 11:53:46','2024-04-19 11:54:40',NULL);
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,1,'Cadastrar','Cadastrou o cargo: Vendedor Senior (15)','2024-04-18 12:34:01'),(2,1,'Cadastrar','Cadastrou o cargo: Auxiliar de Serviços Gerais Senor (16)','2024-04-18 12:43:50'),(3,1,'Editar','Editou o cargo: Auxiliar de Analista de Suporte Senior (8)','2024-04-18 12:45:33'),(4,1,'Cadastrar','Cadastrou o cargo: teste (17)','2024-04-18 12:55:34'),(5,1,'Desativar','Desativou o cargo: teste(17)','2024-04-18 12:57:50'),(16,1,'Editar','Editou o setor: Recursos Humanos (2)','2024-04-19 08:49:16'),(17,1,'Cadastrar','Cadastrou o usuário: Diego Diniz Flieber Ferreira (3)','2024-04-19 09:05:10'),(18,3,'Editar','Editou o usuário: André dos Santo (3)','2024-04-19 10:40:22'),(19,3,'Editar','Editou o usuário: André dos Santos (3)','2024-04-19 10:40:25'),(20,1,'Desativar','Desativou o usuário André dos Santos (3)','2024-04-19 11:31:14'),(21,3,'Editar','Editou o usuário: André dos Santo (3)','2024-04-19 11:31:58'),(22,1,'Editar','Editou o usuário: André dos Santos (3)','2024-04-19 11:34:34'),(23,1,'Cadastrar','Cadastrou o usuário: Elaine Regina da Silva (5)','2024-04-19 11:53:04'),(24,1,'Cadastrar','Cadastrou o cargo: RECURSOS HUMANOS (18)','2024-04-19 11:53:46'),(25,1,'Editar','Editou o usuário: Elaine Regina da Silva (5)','2024-04-19 11:54:01'),(26,1,'Editar','Editou o cargo: Recursos Humanos (18)','2024-04-19 11:54:40'),(27,1,'Editar','Editou o usuário: Elaine Regina da Silva (5)','2024-04-19 11:58:04'),(28,1,'Editar','Editou o usuário: Elaine Regina Da Silva (5)','2024-04-19 12:01:24'),(29,1,'Editar','Editou o usuário: Elaine Regina Da Silva (5)','2024-04-19 12:01:44'),(30,1,'Editar','Editou o usuário: Elaine Regina Da Silva (5)','2024-04-19 12:02:06');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setores`
--

LOCK TABLES `setores` WRITE;
/*!40000 ALTER TABLE `setores` DISABLE KEYS */;
INSERT INTO `setores` VALUES (1,'Tecnologia da Informação','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Recursos Humanos','2024-04-19 08:24:29','2024-04-19 08:49:16',NULL);
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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuarios_cargos_idx` (`id_cargo`),
  KEY `fk_usuarios_setores_idx` (`id_setor`),
  CONSTRAINT `fk_usuarios_cargos` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`),
  CONSTRAINT `fk_usuarios_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Kaique Rodrigues de Souza','Kaique.Souza','GR2Os.QfJQoPc',1,'11983469094','37498968878','2004-07-10','ykaiqz17@gmail.com',2,1,1,'','2023-11-01','2024-04-17 08:11:30','2024-04-17 08:11:30',NULL),(3,'André dos Santos','André.Santos','GRuxKUiOXQSio',0,'11965022187','23863640896','2002-06-24','',2,1,3,'','2022-07-01','2024-04-17 08:54:48','2024-04-19 11:34:00',NULL),(4,'Diego Diniz Flieber Ferreira','Diego.Diniz','GRLPylnit1wvA',1,'11952450209','50873738870','2005-04-16','dinizdiego1320@gmail.com',1,1,1,'','2024-05-04','2024-04-19 09:00:30','2024-04-19 09:00:30',NULL),(5,'Elaine Regina Da Silva','Elaine.Silva','GRLPylnit1wvA',0,'11998519254','28265002874','1978-08-03','',2,2,18,'','2024-02-14','2024-04-19 11:53:04','2024-04-19 12:02:00',NULL);
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

-- Dump completed on 2024-04-19 13:09:26
