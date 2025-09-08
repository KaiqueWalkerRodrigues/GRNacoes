-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: grnacoes
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `arquivo_morto_caixas`
--

DROP TABLE IF EXISTS `arquivo_morto_caixas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arquivo_morto_caixas` (
  `id_caixa` int NOT NULL AUTO_INCREMENT,
  `numero_caixa` varchar(100) NOT NULL,
  `id_local` int NOT NULL,
  `observacoes` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_caixa`),
  KEY `fk_arquivo_morto_caixas_locais_idx` (`id_local`),
  CONSTRAINT `fk_arquivo_morto_caixas_locais` FOREIGN KEY (`id_local`) REFERENCES `arquivo_morto_locais` (`id_local`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivo_morto_caixas`
--

LOCK TABLES `arquivo_morto_caixas` WRITE;
/*!40000 ALTER TABLE `arquivo_morto_caixas` DISABLE KEYS */;
/*!40000 ALTER TABLE `arquivo_morto_caixas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arquivo_morto_itens`
--

DROP TABLE IF EXISTS `arquivo_morto_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arquivo_morto_itens` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_caixa` int NOT NULL,
  `tipo_documento` varchar(255) DEFAULT NULL,
  `nome_documento` varchar(255) DEFAULT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `data_documento` date DEFAULT NULL,
  `data_arquivamento` date DEFAULT NULL,
  `observacoes_item` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `fk_arquivo_morto_itens_caixas_idx` (`id_caixa`),
  CONSTRAINT `fk_arquivo_morto_itens_caixas` FOREIGN KEY (`id_caixa`) REFERENCES `arquivo_morto_caixas` (`id_caixa`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivo_morto_itens`
--

LOCK TABLES `arquivo_morto_itens` WRITE;
/*!40000 ALTER TABLE `arquivo_morto_itens` DISABLE KEYS */;
/*!40000 ALTER TABLE `arquivo_morto_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arquivo_morto_locais`
--

DROP TABLE IF EXISTS `arquivo_morto_locais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arquivo_morto_locais` (
  `id_local` int NOT NULL AUTO_INCREMENT,
  `nome_local` varchar(255) NOT NULL,
  `descricao` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_local`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivo_morto_locais`
--

LOCK TABLES `arquivo_morto_locais` WRITE;
/*!40000 ALTER TABLE `arquivo_morto_locais` DISABLE KEYS */;
/*!40000 ALTER TABLE `arquivo_morto_locais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `captados`
--

DROP TABLE IF EXISTS `captados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `captados` (
  `id_captado` int NOT NULL AUTO_INCREMENT,
  `id_captador` int NOT NULL,
  `id_medico` int NOT NULL,
  `id_motivo` int DEFAULT NULL,
  `id_empresa` int NOT NULL,
  `nome_paciente` varchar(100) NOT NULL,
  `captado` tinyint NOT NULL,
  `observacao` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_captado`),
  KEY `fk_captados_medicos_idx` (`id_medico`),
  KEY `fk_captados_usuarios_idx` (`id_captador`),
  CONSTRAINT `fk_captados_medicos` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`),
  CONSTRAINT `fk_captados_usuarios` FOREIGN KEY (`id_captador`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=12468 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `captados`
--

LOCK TABLES `captados` WRITE;
/*!40000 ALTER TABLE `captados` DISABLE KEYS */;
/*!40000 ALTER TABLE `captados` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
INSERT INTO `cargos` VALUES (1,'Auxiliar de Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Analista de Suporte Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(3,'Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(4,'Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(6,'Auxiliar de Analista de Suporte Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(7,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','0000-00-00 00:00:00','2024-04-18 09:59:40'),(8,'Auxiliar de Analista de Suporte Senior','0000-00-00 00:00:00','2024-04-18 12:45:33',NULL),(9,'Vendedor Junior','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(10,'Vendedor Pleno','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(11,'Vendedor Senior ','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(12,'Auxiliar de Serviços Gerais Junior','2024-04-18 11:34:30','2024-04-18 11:43:16',NULL),(13,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:24:05','2024-04-18 12:24:05',NULL),(14,'Auxiliar de Serviços Gerais Pleno','2024-04-18 12:31:32','2024-04-18 12:31:32','2024-04-18 12:32:12'),(15,'Vendedor Senior','2024-04-18 12:34:01','2024-04-18 12:34:01','2024-04-18 12:43:15'),(16,'Auxiliar de Serviços Gerais Senior','2024-04-18 12:43:50','2024-04-18 12:44:33',NULL),(17,'teste','2024-04-18 12:55:34','2024-04-18 12:55:34','2024-04-18 12:57:50'),(18,'Assistente Recursos Humano','2024-04-19 11:53:46','2024-08-21 12:21:08',NULL),(19,'Assistente De Compra Sênior','2024-04-23 11:28:22','2024-04-23 11:28:22',NULL),(20,'Coordenador(a) Financeiro','2024-08-20 12:03:38','2024-08-20 12:03:38',NULL),(21,'Assistente Financeiro Junior','2024-08-21 12:19:45','2024-08-21 12:19:45',NULL),(22,'Assistente Financeiro Pleno','2024-08-21 12:19:52','2024-08-21 12:19:52',NULL),(23,'Assistente Financeiro Sênior','2024-08-21 12:19:54','2024-08-21 12:19:54',NULL),(24,'Médico','2024-10-01 10:36:48','2024-10-01 10:36:48',NULL),(25,'Recepcionista Junior','2024-10-03 09:59:52','2024-10-03 09:59:52',NULL),(26,'Supervisor(a)','2024-10-04 09:06:30','2024-10-04 09:06:30',NULL),(27,'Coordenador(a) Clínica','2024-10-05 09:03:18','2024-11-27 08:56:39',NULL),(28,'Orientador(a) Cirurgica','2024-11-27 08:54:48','2024-11-27 08:54:48',NULL),(29,'Coordenador(a) Orientação Cirurgica','2024-11-27 08:55:11','2024-11-27 08:55:11',NULL),(30,'Coordenadora Callcenter','2025-08-20 15:09:59','2025-08-20 15:09:59',NULL),(31,'Atendente Callcenter','2025-08-20 15:35:40','2025-08-20 15:35:40',NULL);
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catarata_agendamentos`
--

DROP TABLE IF EXISTS `catarata_agendamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catarata_agendamentos` (
  `id_catarata_agendamento` int NOT NULL AUTO_INCREMENT,
  `id_solicitante` int DEFAULT NULL,
  `id_convenio` int DEFAULT NULL,
  `id_lente` int NOT NULL,
  `id_agenda` int NOT NULL,
  `id_turma` int NOT NULL,
  `id_orientador` int NOT NULL,
  `externo` tinyint NOT NULL DEFAULT '0',
  `nome` varchar(80) NOT NULL,
  `cpf` char(11) DEFAULT NULL,
  `contato` char(11) DEFAULT NULL,
  `olhos` tinyint NOT NULL,
  `dioptria_esquerda` double(10,2) DEFAULT NULL,
  `dioptria_direita` double(10,2) DEFAULT NULL,
  `valor` double(10,2) NOT NULL,
  `forma_pgto1` tinyint DEFAULT NULL,
  `forma_pgto2` tinyint DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_catarata_agendamento`),
  KEY `fk_catarata_pacientes_catarata_medicos_idx` (`id_solicitante`),
  KEY `fk_catarata_pacientes_catarata_convenios_idx` (`id_convenio`),
  KEY `fk_catarata_pacientes_catarata_lentes_idx` (`id_lente`),
  KEY `fk_catarata_pacientes_catarata_agenda_idx` (`id_agenda`),
  KEY `fk_catarata_pacientes_catarata_turmas_idx` (`id_turma`),
  KEY `fk_catarata_pacientes_catarata_usuarios_idx` (`id_orientador`),
  CONSTRAINT `fk_catarata_pacientes_catarata_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `catarata_agendas` (`id_catarata_agenda`),
  CONSTRAINT `fk_catarata_pacientes_catarata_convenios` FOREIGN KEY (`id_convenio`) REFERENCES `convenios` (`id_convenio`),
  CONSTRAINT `fk_catarata_pacientes_catarata_lentes` FOREIGN KEY (`id_lente`) REFERENCES `catarata_lentes` (`id_catarata_lente`),
  CONSTRAINT `fk_catarata_pacientes_catarata_medicos` FOREIGN KEY (`id_solicitante`) REFERENCES `medicos` (`id_medico`),
  CONSTRAINT `fk_catarata_pacientes_catarata_turmas` FOREIGN KEY (`id_turma`) REFERENCES `catarata_turmas` (`id_catarata_turma`),
  CONSTRAINT `fk_catarata_pacientes_catarata_usuarios` FOREIGN KEY (`id_orientador`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catarata_agendamentos`
--

LOCK TABLES `catarata_agendamentos` WRITE;
/*!40000 ALTER TABLE `catarata_agendamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `catarata_agendamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catarata_agendas`
--

DROP TABLE IF EXISTS `catarata_agendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catarata_agendas` (
  `id_catarata_agenda` int NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_catarata_agenda`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catarata_agendas`
--

LOCK TABLES `catarata_agendas` WRITE;
/*!40000 ALTER TABLE `catarata_agendas` DISABLE KEYS */;
/*!40000 ALTER TABLE `catarata_agendas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catarata_lentes`
--

DROP TABLE IF EXISTS `catarata_lentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catarata_lentes` (
  `id_catarata_lente` int NOT NULL AUTO_INCREMENT,
  `lente` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_catarata_lente`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catarata_lentes`
--

LOCK TABLES `catarata_lentes` WRITE;
/*!40000 ALTER TABLE `catarata_lentes` DISABLE KEYS */;
/*!40000 ALTER TABLE `catarata_lentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catarata_turmas`
--

DROP TABLE IF EXISTS `catarata_turmas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catarata_turmas` (
  `id_catarata_turma` int NOT NULL AUTO_INCREMENT,
  `id_agenda` int NOT NULL,
  `horario` time NOT NULL,
  `qntd` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_catarata_turma`),
  KEY `fk_catarata_turmas_catarata_agendas_idx` (`id_agenda`),
  CONSTRAINT `fk_catarata_turmas_catarata_agenas` FOREIGN KEY (`id_agenda`) REFERENCES `catarata_agendas` (`id_catarata_agenda`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catarata_turmas`
--

LOCK TABLES `catarata_turmas` WRITE;
/*!40000 ALTER TABLE `catarata_turmas` DISABLE KEYS */;
/*!40000 ALTER TABLE `catarata_turmas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chamados`
--

DROP TABLE IF EXISTS `chamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chamados` (
  `id_chamado` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `status` tinyint NOT NULL,
  `id_usuario` int NOT NULL,
  `id_setor` int NOT NULL,
  `urgencia` tinyint NOT NULL,
  `descricao` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_chamado`),
  KEY `fk_chamados_usuarios_idx` (`id_usuario`),
  KEY `fk_chamados_setores_idx` (`id_setor`),
  CONSTRAINT `fk_chamados_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`),
  CONSTRAINT `fk_chamados_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamados`
--

LOCK TABLES `chamados` WRITE;
/*!40000 ALTER TABLE `chamados` DISABLE KEYS */;
/*!40000 ALTER TABLE `chamados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chamados_mensagens`
--

DROP TABLE IF EXISTS `chamados_mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chamados_mensagens` (
  `id_chamado_mensagem` int NOT NULL AUTO_INCREMENT,
  `id_chamado` int NOT NULL,
  `id_mensagem` int NOT NULL,
  PRIMARY KEY (`id_chamado_mensagem`),
  KEY `fk_chamados_mensagens_chamados_idx` (`id_chamado`),
  KEY `fk_chamados_mensagens_mensagens_idx` (`id_mensagem`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamados_mensagens`
--

LOCK TABLES `chamados_mensagens` WRITE;
/*!40000 ALTER TABLE `chamados_mensagens` DISABLE KEYS */;
/*!40000 ALTER TABLE `chamados_mensagens` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_categorias`
--

LOCK TABLES `compras_categorias` WRITE;
/*!40000 ALTER TABLE `compras_categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_estoque_venda`
--

DROP TABLE IF EXISTS `compras_estoque_venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_estoque_venda` (
  `id_compra_estoque_venda` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `venda` int DEFAULT NULL,
  `estoque` int DEFAULT NULL,
  `ano` int NOT NULL,
  `mes` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_estoque_venda`),
  KEY `fk_compras_estoque_venda_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_compras_estoque_venda_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `compras_fornecedores` (`id_compra_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_estoque_venda`
--

LOCK TABLES `compras_estoque_venda` WRITE;
/*!40000 ALTER TABLE `compras_estoque_venda` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_estoque_venda` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_fornecedores`
--

LOCK TABLES `compras_fornecedores` WRITE;
/*!40000 ALTER TABLE `compras_fornecedores` DISABLE KEYS */;
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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compra_nota`),
  UNIQUE KEY `n_nota_UNIQUE` (`n_nota`),
  KEY `fk_compras_notas_compras_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_compras_notas_compras_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `compras_fornecedores` (`id_compra_fornecedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1315 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_notas`
--

LOCK TABLES `compras_notas` WRITE;
/*!40000 ALTER TABLE `compras_notas` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_notas_itens`
--

DROP TABLE IF EXISTS `compras_notas_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_notas_itens` (
  `id_compras_notas_itens` int NOT NULL AUTO_INCREMENT,
  `id_nota` int NOT NULL,
  `item` varchar(120) NOT NULL,
  `valor_uni` double(10,2) NOT NULL,
  `quantidade` int NOT NULL,
  `descricao` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_compras_notas_itens`),
  KEY `fk_compras_notas_itens_compras_notas_idx` (`id_nota`),
  CONSTRAINT `fk_compras_notas_itens_compras_notas` FOREIGN KEY (`id_nota`) REFERENCES `compras_notas` (`id_compra_nota`)
) ENGINE=InnoDB AUTO_INCREMENT=963 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_notas_itens`
--

LOCK TABLES `compras_notas_itens` WRITE;
/*!40000 ALTER TABLE `compras_notas_itens` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_notas_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_pedidos`
--

DROP TABLE IF EXISTS `compras_pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras_pedidos` (
  `id_compra_pedido` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `empresa` int NOT NULL,
  `link` text,
  `id_setor` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `descricao` text,
  `urgencia` int NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_compra_pedido`),
  KEY `fk_compras_pedidos_usuarios_idx` (`id_usuario`),
  KEY `compras_pedidos_setores_idx` (`id_setor`),
  CONSTRAINT `fk_compras_pedidos_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`),
  CONSTRAINT `fk_compras_pedidos_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_pedidos`
--

LOCK TABLES `compras_pedidos` WRITE;
/*!40000 ALTER TABLE `compras_pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenios`
--

DROP TABLE IF EXISTS `convenios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `convenios` (
  `id_convenio` int NOT NULL AUTO_INCREMENT,
  `convenio` varchar(80) NOT NULL,
  `razao_social` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_convenio`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenios`
--

LOCK TABLES `convenios` WRITE;
/*!40000 ALTER TABLE `convenios` DISABLE KEYS */;
/*!40000 ALTER TABLE `convenios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversas`
--

DROP TABLE IF EXISTS `conversas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversas` (
  `id_conversa` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `tipo` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_conversa`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversas`
--

LOCK TABLES `conversas` WRITE;
/*!40000 ALTER TABLE `conversas` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversas_mensagens`
--

DROP TABLE IF EXISTS `conversas_mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversas_mensagens` (
  `id_conversa_mensagen` int NOT NULL AUTO_INCREMENT,
  `id_mensagem` int NOT NULL,
  `id_conversa` int NOT NULL,
  PRIMARY KEY (`id_conversa_mensagen`),
  KEY `fk_conversas_mensagens_mensagens_idx` (`id_mensagem`),
  KEY `fk_conversas_mensagens_conversas_idx` (`id_conversa`),
  CONSTRAINT `fk_conversas_mensagens_conversas` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  CONSTRAINT `fk_conversas_mensagens_mensagens` FOREIGN KEY (`id_mensagem`) REFERENCES `mensagens` (`id_mensagem`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversas_mensagens`
--

LOCK TABLES `conversas_mensagens` WRITE;
/*!40000 ALTER TABLE `conversas_mensagens` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversas_mensagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financeiro_boletos`
--

DROP TABLE IF EXISTS `financeiro_boletos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financeiro_boletos` (
  `id_financeiro_boleto` int NOT NULL AUTO_INCREMENT,
  `n_boleto` char(10) NOT NULL,
  `id_campanha` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_empresa` int NOT NULL,
  `cliente` varchar(150) NOT NULL,
  `data_venda` date NOT NULL,
  `valor` double(10,2) NOT NULL,
  `valor_pago` double(10,2) DEFAULT '0.00',
  `data_pago` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_financeiro_boleto`),
  KEY `fk_financeiro_boletos_usuarios_idx` (`id_usuario`),
  KEY `fk_financeiro_boletos_campanhas_idx` (`id_campanha`),
  CONSTRAINT `fk_financeiro_boletos_financeiro_campanhas` FOREIGN KEY (`id_campanha`) REFERENCES `financeiro_campanhas` (`id_financeiro_campanha`),
  CONSTRAINT `fk_financeiro_boletos_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=498 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financeiro_boletos`
--

LOCK TABLES `financeiro_boletos` WRITE;
/*!40000 ALTER TABLE `financeiro_boletos` DISABLE KEYS */;
/*!40000 ALTER TABLE `financeiro_boletos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financeiro_campanhas`
--

DROP TABLE IF EXISTS `financeiro_campanhas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financeiro_campanhas` (
  `id_financeiro_campanha` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fim` date NOT NULL,
  `data_pagamento` date NOT NULL,
  `data_pagamento_pos` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_financeiro_campanha`),
  UNIQUE KEY `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financeiro_campanhas`
--

LOCK TABLES `financeiro_campanhas` WRITE;
/*!40000 ALTER TABLE `financeiro_campanhas` DISABLE KEYS */;
/*!40000 ALTER TABLE `financeiro_campanhas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financeiro_contratos`
--

DROP TABLE IF EXISTS `financeiro_contratos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financeiro_contratos` (
  `id_financeiro_contrato` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `n_contrato` int NOT NULL,
  `data` date NOT NULL,
  `id_testemunha1` int NOT NULL,
  `id_testemunha2` int DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` char(11) NOT NULL,
  `cep` char(8) NOT NULL,
  `numero` varchar(7) NOT NULL,
  `endereco` varchar(150) NOT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `bairro` varchar(120) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` char(2) NOT NULL,
  `telefone_residencial` char(10) DEFAULT NULL,
  `telefone_comercial` char(11) DEFAULT NULL,
  `celular1` char(11) DEFAULT NULL,
  `celular2` char(11) DEFAULT NULL,
  `sinal_entrada` double(20,2) DEFAULT NULL,
  `valor` double(20,2) NOT NULL,
  `parcelas` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_financeiro_contrato`),
  UNIQUE KEY `n_contrato_UNIQUE` (`n_contrato`),
  KEY `fk_financeiro_contratos_usuarios_idx1` (`id_testemunha1`,`id_testemunha2`),
  CONSTRAINT `fk_financeiro_contratos_usuarios` FOREIGN KEY (`id_testemunha1`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financeiro_contratos`
--

LOCK TABLES `financeiro_contratos` WRITE;
/*!40000 ALTER TABLE `financeiro_contratos` DISABLE KEYS */;
/*!40000 ALTER TABLE `financeiro_contratos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financeiro_contratos_parcelas`
--

DROP TABLE IF EXISTS `financeiro_contratos_parcelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financeiro_contratos_parcelas` (
  `id_financeiro_contrato_parcela` int NOT NULL AUTO_INCREMENT,
  `id_contrato` int NOT NULL,
  `pago_em` date DEFAULT NULL,
  `valor_pago` double(10,2) DEFAULT '0.00',
  `parcela` tinyint NOT NULL,
  `data` date NOT NULL,
  `valor` double(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_financeiro_contrato_parcela`),
  KEY `fk_financeiro_contratos_parcelas_contratos_idx` (`id_contrato`),
  CONSTRAINT `fk_financeiro_contratos_parcelas_contratos` FOREIGN KEY (`id_contrato`) REFERENCES `financeiro_contratos` (`id_financeiro_contrato`)
) ENGINE=InnoDB AUTO_INCREMENT=981 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financeiro_contratos_parcelas`
--

LOCK TABLES `financeiro_contratos_parcelas` WRITE;
/*!40000 ALTER TABLE `financeiro_contratos_parcelas` DISABLE KEYS */;
/*!40000 ALTER TABLE `financeiro_contratos_parcelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lente_contato_fornecedores`
--

DROP TABLE IF EXISTS `lente_contato_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lente_contato_fornecedores` (
  `id_lente_contato_fornecedor` int NOT NULL AUTO_INCREMENT,
  `fornecedor` varchar(180) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_lente_contato_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lente_contato_fornecedores`
--

LOCK TABLES `lente_contato_fornecedores` WRITE;
/*!40000 ALTER TABLE `lente_contato_fornecedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `lente_contato_fornecedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lente_contato_modelos`
--

DROP TABLE IF EXISTS `lente_contato_modelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lente_contato_modelos` (
  `id_lente_contato_modelo` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `modelo` varchar(80) NOT NULL,
  `codigo_simah` int DEFAULT NULL,
  `unidade` tinyint NOT NULL,
  `valor_custo` double(10,2) NOT NULL,
  `valor_venda` double(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_lente_contato_modelo`),
  KEY `fk_lente_contato_modelos_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_lente_contato_modelos_lente_contato_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `lente_contato_fornecedores` (`id_lente_contato_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lente_contato_modelos`
--

LOCK TABLES `lente_contato_modelos` WRITE;
/*!40000 ALTER TABLE `lente_contato_modelos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lente_contato_modelos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lente_contato_orcamentos`
--

DROP TABLE IF EXISTS `lente_contato_orcamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lente_contato_orcamentos` (
  `id_lente_contato_orcamento` int NOT NULL AUTO_INCREMENT,
  `id_medico` int DEFAULT NULL,
  `id_modelo_esquerdo` int DEFAULT NULL,
  `id_modelo_direito` int DEFAULT NULL,
  `id_contatologa` int NOT NULL,
  `qnt_direita` int DEFAULT NULL,
  `qnt_esquerda` int DEFAULT NULL,
  `id_empresa` int NOT NULL,
  `nome` varchar(120) NOT NULL,
  `cpf` char(11) NOT NULL,
  `contato` char(11) NOT NULL,
  `olhos` tinyint NOT NULL,
  `valor` double(10,2) DEFAULT NULL,
  `id_forma_pagamento1` tinyint DEFAULT NULL,
  `id_forma_pagamento2` tinyint DEFAULT NULL,
  `cv_pgto1` varchar(50) DEFAULT NULL,
  `cv_pgto2` varchar(50) DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `olho_esquerdo` tinytext,
  `olho_direito` tinytext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `observacao` text,
  PRIMARY KEY (`id_lente_contato_orcamento`),
  KEY `fk_lentes_contato_orcamentos_medicos_idx` (`id_medico`),
  KEY `fk_lentes_contato_orcamentos_lente_contato_modelos_idx` (`id_modelo_esquerdo`),
  KEY `fk_lentes_contato_usuarios_idx` (`id_contatologa`),
  KEY `fk_lentes_contato_orcamentos_modelos_idx` (`id_modelo_direito`),
  CONSTRAINT `fk_lente_contato_orcamentos_modelos_direito` FOREIGN KEY (`id_modelo_direito`) REFERENCES `lente_contato_modelos` (`id_lente_contato_modelo`),
  CONSTRAINT `fk_lente_contato_orcamentos_modelos_esquerdo` FOREIGN KEY (`id_modelo_esquerdo`) REFERENCES `lente_contato_modelos` (`id_lente_contato_modelo`),
  CONSTRAINT `fk_lente_contato_orcamentos_usuarios` FOREIGN KEY (`id_contatologa`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_lentes_contato_orcamentos_medicos` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lente_contato_orcamentos`
--

LOCK TABLES `lente_contato_orcamentos` WRITE;
/*!40000 ALTER TABLE `lente_contato_orcamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lente_contato_orcamentos` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5406 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicos`
--

DROP TABLE IF EXISTS `medicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicos` (
  `id_medico` int NOT NULL AUTO_INCREMENT,
  `ativo` tinyint NOT NULL DEFAULT '1',
  `nome` varchar(150) NOT NULL,
  `crm` char(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_medico`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicos`
--

LOCK TABLES `medicos` WRITE;
/*!40000 ALTER TABLE `medicos` DISABLE KEYS */;
/*!40000 ALTER TABLE `medicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens` (
  `id_mensagem` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `mensagem` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_mensagem`),
  KEY `fk_mensangens_chats_usuarios_idx` (`id_usuario`),
  CONSTRAINT `fk_mensangens_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens`
--

LOCK TABLES `mensagens` WRITE;
/*!40000 ALTER TABLE `mensagens` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens_anexos`
--

DROP TABLE IF EXISTS `mensagens_anexos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens_anexos` (
  `id_anexo` int NOT NULL AUTO_INCREMENT,
  `id_mensagem` int NOT NULL,
  `nome_original` varchar(45) NOT NULL,
  `arquivo_sistema` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_anexo`),
  KEY `fk_mensagens_anexos_mensagens_idx` (`id_mensagem`),
  CONSTRAINT `fk_mensagens_anexos_mensagens` FOREIGN KEY (`id_mensagem`) REFERENCES `mensagens` (`id_mensagem`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens_anexos`
--

LOCK TABLES `mensagens_anexos` WRITE;
/*!40000 ALTER TABLE `mensagens_anexos` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensagens_anexos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens_lidas`
--

DROP TABLE IF EXISTS `mensagens_lidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens_lidas` (
  `id_mensagem_lida` int NOT NULL AUTO_INCREMENT,
  `id_mensagem` int NOT NULL,
  `id_usuario` int NOT NULL,
  `lida_em` timestamp NOT NULL,
  PRIMARY KEY (`id_mensagem_lida`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens_lidas`
--

LOCK TABLES `mensagens_lidas` WRITE;
/*!40000 ALTER TABLE `mensagens_lidas` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensagens_lidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacoes_chamados`
--

DROP TABLE IF EXISTS `notificacoes_chamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacoes_chamados` (
  `id_notificacao_chamado` int NOT NULL AUTO_INCREMENT,
  `id_chamado` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_setor` int DEFAULT NULL,
  `texto` text,
  `tipo` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_notificacao_chamado`),
  KEY `fk_notificacoes_chamados_chamados_idx` (`id_chamado`),
  KEY `fk_notificacoes_chamados_usuarios_idx` (`id_usuario`),
  KEY `fk_notificacoes_chamados_setores_idx` (`id_setor`),
  CONSTRAINT `fk_notificacoes_chamados_chamados` FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`),
  CONSTRAINT `fk_notificacoes_chamados_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`),
  CONSTRAINT `fk_notificacoes_chamados_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacoes_chamados`
--

LOCK TABLES `notificacoes_chamados` WRITE;
/*!40000 ALTER TABLE `notificacoes_chamados` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacoes_chamados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacoes_chamados_usuarios`
--

DROP TABLE IF EXISTS `notificacoes_chamados_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacoes_chamados_usuarios` (
  `id_notificacao_chamado_usuario` int NOT NULL AUTO_INCREMENT,
  `id_notificacao` int NOT NULL,
  `id_usuario` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_notificacao_chamado_usuario`),
  KEY `fk_notificacoes_chamados_usuarios_usuarios_idx` (`id_usuario`),
  KEY `fk_notificacoes_chamados_usuarios_notificacoes_idx` (`id_notificacao`),
  CONSTRAINT `fk_notificacoes_chamados_usuarios_notificacoes` FOREIGN KEY (`id_notificacao`) REFERENCES `notificacoes_chamados` (`id_notificacao_chamado`),
  CONSTRAINT `fk_notificacoes_chamados_usuarios_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacoes_chamados_usuarios`
--

LOCK TABLES `notificacoes_chamados_usuarios` WRITE;
/*!40000 ALTER TABLE `notificacoes_chamados_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacoes_chamados_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otica_estoque`
--

DROP TABLE IF EXISTS `otica_estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otica_estoque` (
  `id_otica_estoque` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `mes_ano` varchar(7) NOT NULL,
  `quantidade` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_otica_estoque`),
  KEY `fk_otica_estoque_fornecedores_idx` (`id_fornecedor`),
  CONSTRAINT `fk_otica_estoque_fornecedores` FOREIGN KEY (`id_fornecedor`) REFERENCES `compras_fornecedores` (`id_compra_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otica_estoque`
--

LOCK TABLES `otica_estoque` WRITE;
/*!40000 ALTER TABLE `otica_estoque` DISABLE KEYS */;
/*!40000 ALTER TABLE `otica_estoque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes`
--

DROP TABLE IF EXISTS `participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes` (
  `id_participante` int NOT NULL AUTO_INCREMENT,
  `id_conversa` int NOT NULL,
  `id_usuario` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_participante`),
  KEY `fk_participantes_usuarios_idx` (`id_usuario`),
  KEY `fk_participantes_conversas_idx` (`id_conversa`),
  CONSTRAINT `fk_participantes_conversas` FOREIGN KEY (`id_conversa`) REFERENCES `conversas` (`id_conversa`),
  CONSTRAINT `fk_participantes_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes`
--

LOCK TABLES `participantes` WRITE;
/*!40000 ALTER TABLE `participantes` DISABLE KEYS */;
/*!40000 ALTER TABLE `participantes` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setores`
--

LOCK TABLES `setores` WRITE;
/*!40000 ALTER TABLE `setores` DISABLE KEYS */;
INSERT INTO `setores` VALUES (1,'Tecnologia da Informação','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL),(2,'Recursos Humanos','2024-04-19 08:24:29','2024-04-19 08:49:16',NULL),(3,'Compras','2024-04-23 11:28:02','2024-04-23 11:28:02',NULL),(4,'Ótica','2024-08-15 09:18:17','2024-08-15 09:18:17',NULL),(5,'Financeiro','2024-08-20 12:03:19','2024-08-20 12:03:19',NULL),(6,'Exames','2024-08-23 08:51:30','2024-08-23 08:51:30',NULL),(7,'Recepção','2024-08-23 08:51:34','2024-08-23 08:51:34',NULL),(8,'Captação','2024-08-23 08:51:41','2024-08-23 08:51:41',NULL),(9,'Médicos','2024-08-23 08:51:53','2024-08-23 08:51:53',NULL),(10,'Call Center','2024-08-23 08:52:07','2024-08-23 08:52:07',NULL),(11,'Médicos','2024-10-01 10:36:16','2024-10-01 10:36:16','2024-10-01 10:36:19'),(12,'Supervisão','2024-10-04 09:06:12','2024-10-04 09:06:12',NULL),(13,'Coordenação Clínica','2024-10-05 09:03:09','2024-10-05 09:03:09',NULL),(14,'Coordenação Financeiro','2024-10-18 11:58:55','2024-10-18 11:58:55',NULL),(15,'Orientação Cirurgica','2024-11-27 08:52:33','2024-11-27 08:52:33',NULL),(17,'Lente De Contato','2025-01-24 16:09:25','2025-01-24 16:09:25',NULL);
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
  `ativo` tinyint NOT NULL DEFAULT '1',
  `nome` varchar(150) NOT NULL,
  `usuario` varchar(60) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `contrato` int NOT NULL,
  `celular` char(11) DEFAULT NULL,
  `cpf` char(11) NOT NULL,
  `data_nascimento` date NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `empresa` tinyint NOT NULL,
  `id_cargo` int NOT NULL,
  `n_folha` varchar(25) DEFAULT NULL,
  `data_admissao` date NOT NULL,
  `id_avatar` tinyint NOT NULL DEFAULT '0',
  `rg` varchar(100) DEFAULT NULL,
  `foto3x4` varchar(100) DEFAULT NULL,
  `residencia` varchar(100) DEFAULT NULL,
  `contrato_nube` varchar(100) DEFAULT NULL,
  `online_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`),
  KEY `fk_usuarios_cargos_idx` (`id_cargo`),
  CONSTRAINT `fk_usuarios_cargos` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,1,'Teste','teste','$2y$10$7geeiFRTBCPa5YVx.WE7K./Sw3g0cgGTg4EubaKknYLebuOsQ/In2',0,'11988888888','48770104085','2000-01-01','test@test.com',1,4,'','2024-01-01',0,NULL,NULL,NULL,NULL,'2025-09-08 13:53:24','2025-09-08 12:55:51','2025-09-08 12:55:51',NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_setores`
--

DROP TABLE IF EXISTS `usuarios_setores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_setores` (
  `id_usuario_setor` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_setor` int NOT NULL,
  `surpervisor` tinyint NOT NULL DEFAULT '0',
  `principal` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario_setor`),
  KEY `fk_usuarios_setores_setores_idx` (`id_setor`),
  KEY `fk_usuarios_setores_usuarios_idx` (`id_usuario`),
  CONSTRAINT `fk_usuarios_setores_setores` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`),
  CONSTRAINT `fk_usuarios_setores_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_setores`
--

LOCK TABLES `usuarios_setores` WRITE;
/*!40000 ALTER TABLE `usuarios_setores` DISABLE KEYS */;
INSERT INTO `usuarios_setores` VALUES (158,54,1,0,1,'2025-09-08 12:49:36','2025-09-08 12:49:36',NULL),(159,1,1,0,1,'2025-09-08 12:55:51','2025-09-08 12:55:51',NULL);
/*!40000 ALTER TABLE `usuarios_setores` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-08 13:55:34
