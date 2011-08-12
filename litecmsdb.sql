-- MySQL dump 10.13  Distrib 5.1.57, for apple-darwin10.7.4 (i386)
--
-- Host: localhost    Database: litecmsdb
-- ------------------------------------------------------
-- Server version	5.1.57

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
-- Table structure for table `contentmenu`
--

DROP TABLE IF EXISTS `contentmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `contentobject_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `path_string` longtext NOT NULL,
  `lang` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentmenu`
--

LOCK TABLES `contentmenu` WRITE;
/*!40000 ALTER TABLE `contentmenu` DISABLE KEYS */;
INSERT INTO `contentmenu` VALUES (1,1,1,'accueil','/accueil','fre-FR'),(2,2,2,'enluminure-et-caligraphie','/accueil/enluminure-et-caligraphie','fre-FR'),(3,3,3,'contact','/contact','fre-FR'),(4,4,4,'test-image','/accueil/test-image','fre-FR'),(5,5,5,'creations-originales','/accueil/enluminure-et-caligraphie/creations-originales','fre-FR'),(6,6,6,'article-de-test','/accueil/test-image/article-de-test','fre-FR'),(7,7,7,'article-root','/accueil/enluminure-et-caligraphie/article-root','fre-FR'),(8,8,8,'test','/accueil/enluminure-et-caligraphie/test','fre-FR'),(9,9,9,'test','/accueil/test-image/article-de-test/test','fre-FR'),(10,10,10,'ange-orant','/accueil/enluminure-et-caligraphie/creations-originales/ange-orant','fre-FR'),(11,11,11,'annonciation','/accueil/enluminure-et-caligraphie/creations-originales/annonciation','fre-FR'),(16,16,16,'dernieres-infos','/accueil/dernieres-infos','fre-FR'),(17,17,17,'prochain-stage','/accueil/dernieres-infos/prochain-stage','fre-FR');
/*!40000 ALTER TABLE `contentmenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contentobject_attributes`
--

DROP TABLE IF EXISTS `contentobject_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentobject_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentobject_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `int_value` int(11) NOT NULL,
  `txt_value` varchar(255) NOT NULL,
  `ltxt_value` text NOT NULL,
  `datatype` varchar(32) NOT NULL,
  `identifier` varchar(32) NOT NULL,
  `lang` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contentobject_id` (`contentobject_id`,`datatype`,`identifier`,`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentobject_attributes`
--

LOCK TABLES `contentobject_attributes` WRITE;
/*!40000 ALTER TABLE `contentobject_attributes` DISABLE KEYS */;
INSERT INTO `contentobject_attributes` VALUES (1,1,'Name',0,'Accueil','','string','name','fre-FR'),(2,1,'Description',0,'','<p>Bienvenu &agrave; l\'atelier de Sil&ouml;e, artiste enlumineuse, peintre h&eacute;raldiste et cr&eacute;atrice de la collection de bijoux harmonisants \"Pierres vives\".<br /><br />Mettre en lumi&egrave;re l\'harmonie du monde, &eacute;veiller vore cr&eacute;ativit&eacute; et votre sens de la beaut&eacute;, r&eacute;v&eacute;ler vos ressources int&eacute;rieures, voil&agrave; en quelques mots ce &agrave; quoi sa d&eacute;couverte vous incite.<br /><br />Alors, bonne et douce visite !</p>\r\n<p style=\"text-align: right;\">\"L\'obscurit&eacute; du monde n\'est qu\'une ombre.(...)<br />La vie est tellement emplie de sens et de propos, tellement pleine de beaut&eacute;(...)<br />, que vous vous apercevrez que la terre ne fait que recouvrir votre ciel.\"<br />Fra Angelico</p>','xmlbloc','description','fre-FR'),(3,2,'Name',0,'Enluminure et caligraphie','','string','name','fre-FR'),(4,2,'Description',0,'','<p>L\'art de l\'enluminure est n&eacute; avec les premiers &eacute;crits et s\'est &eacute;panoui dans les livres manuscrits du moyen age.</p>\r\n<p>Enluminer, c\'est mettre en lumi&egrave;re un texte, spirituel ou profane, par son illustration et l\'utilisation de la feuille d\'or. Une enluminure se contemple et se m&eacute;dite tout &agrave; la fois, dans un dialogue permanente entre le texte et l\'image. Offrir une enluminure, c\'est confier un message tr&egrave;s pr&eacute;cieux et personnel &agrave; une personne qui nous est ch&egrave;re.</p>\r\n<p>Silo&euml; pratique cet art depuis de nombreuses ann&eacute;es et met son savoir-faire au service de votre d&eacute;sir de beaut&eacute; et de cr&eacute;ativit&eacute; en animant des cours et des stages d\'enluminure et de calligraphie occidentale. Dessin, composition respectant la r&egrave;gle du nombre d\'or, harmonie des couleurs, fantaisie des bordures, peinture de miniatures, arabesques des lettres calligraphi&eacute;es, cette activit&eacute; fera reliure votre personnalit&eacute; artistique sous toutes ses facettes</p>','xmlbloc','description','fre-FR'),(5,3,'Name',0,'Contact','','string','name','fre-FR'),(6,3,'Description',0,'','<p>Pour me contacter, vous pouvez bla <a href=\"mailto:contact@atelier-siloe.fr\">bla bla bla avec modi</a>f</p>','xmlbloc','description','fre-FR'),(7,4,'Name',0,'test image','','string','name','fre-FR'),(8,4,'Description',0,'','<p>test je modifie le texte</p>','xmlbloc','description','fre-FR'),(9,4,'Image',0,'','<file>\r\n<name>moustique.gif</name>\r\n<type>image/gif</type>\r\n<filename>moustique.gif</filename>\r\n<basename>moustique</basename>\r\n<extension>gif</extension>\r\n<size>12842</size>\r\n<created>14-06-2011</created>\r\n</file>\r\n','image','image','fre-FR'),(10,5,'Name',0,'Créations originales','','string','name','fre-FR'),(11,5,'Description',0,'','<p>Enlumineuse du XXI&egrave;me sci&egrave;cle, Silo&euml;; travaille aussi bien avec des techniques et des mat&eacute;riaux anciens (pigments li&eacute;s selon une recette familiale inspir&eacute;e de Van Eyck, assiette &agrave; dorer m&eacute;di&eacute;valle, glaire d\'oeufs pour poser l\'or &agrave; la feuille, parchemin) qu\'avec des produits plus r&eacute;cents comme la gouache technique ou le papier \"peau d\'&eacute;l&eacute;phant\".</p>\r\n<p>Issue d\'une famille d\'artistes, elle perfectionne sa technique dans l\'atelier de Staits Faraklas, enlumieur et iconographe, apr&egrave;s avoir obtenu un master d\'Histoire de l\'art &agrave; la Sorbonne (sp&eacute;cialit&eacute; : les repr&eacute;sentations ang&eacute;liques &agrave; la renaissance).</p>','xmlbloc','description','fre-FR'),(12,5,'Image',0,'','','image','image','fre-FR'),(13,0,'Image',0,'','','image','image','fre-FR'),(14,2,'Image',0,'','<?xml version=\'1.0\'?><file>\n<name>nerval.jpg</name>\n<type>image/jpeg</type>\n<filename>nerval.jpg</filename>\n<basename>nerval</basename>\n<extension>jpg</extension>\n<size>168830</size>\n<created>20-06-2011</created>\n</file>\n','image','image','fre-FR'),(15,6,'Name',0,'article de test','','string','name','fre-FR'),(16,6,'Description',0,'','<p>test</p>','xmlbloc','description','fre-FR'),(17,6,'Image',0,'','','image','image','fre-FR'),(18,3,'Image',0,'','','image','image','fre-FR'),(19,7,'Name',0,'article root','','string','name','fre-FR'),(20,7,'Description',0,'','<p>test</p>','xmlbloc','description','fre-FR'),(21,7,'Image',0,'','','image','image','fre-FR'),(22,8,'Name',0,'test','','string','name','fre-FR'),(23,8,'Description',0,'','<p>test</p>','xmlbloc','description','fre-FR'),(24,8,'Image',0,'','','image','image','fre-FR'),(25,9,'Name',0,'test ','','string','name','fre-FR'),(26,9,'Description',0,'','<p>test</p>','xmlbloc','description','fre-FR'),(27,9,'Image',0,'','','image','image','fre-FR'),(28,1,'Image',0,'','<?xml version=\'1.0\'?><file>\n<name>ange gardien marie sans cadre.jpg</name>\n<type>image/jpeg</type>\n<filename>ange-gardien-marie-sans-cadre.jpg</filename>\n<basename>ange-gardien-marie-sans-cadre</basename>\n<extension>jpg</extension>\n<size>3357382</size>\n<created>06-07-2011</created>\n</file>\n','image','image','fre-FR'),(29,10,'Name',0,'ange orant','','string','name','fre-FR'),(30,10,'Description',0,'','<p>Ceci est l\'ange orant</p>','xmlbloc','description','fre-FR'),(31,10,'Image',0,'','<?xml version=\'1.0\'?><file>\n<name>ange orant.jpg</name>\n<type>image/jpeg</type>\n<filename>ange-orant.jpg</filename>\n<basename>ange-orant</basename>\n<extension>jpg</extension>\n<size>5240275</size>\n<created>07-07-2011</created>\n</file>\n','image','image','fre-FR'),(32,11,'Name',0,'annonciation','','string','name','fre-FR'),(33,11,'Description',0,'','<p>Annonciation... carte r&eacute;alis&eacute;e pour</p>\r\n<p>et qui bla bla</p>','xmlbloc','description','fre-FR'),(34,11,'Image',0,'','<?xml version=\'1.0\'?><file>\n<name>annonciation-nany-CARTE.jpg</name>\n<type>image/jpeg</type>\n<filename>annonciation-nany-carte.jpg</filename>\n<basename>annonciation-nany-carte</basename>\n<extension>jpg</extension>\n<size>514033</size>\n<created>08-07-2011</created>\n</file>\n','image','image','fre-FR'),(47,16,'Name',0,'Dernières infos','','string','name','fre-FR'),(48,16,'Description',0,'','<p>Retrouvez ici l\'actualit&eacute; de Silo&euml; et les diff&eacute;rentes activit&eacute;s de l\'atelier.</p>','xmlbloc','description','fre-FR'),(49,16,'Image',0,'','','image','image','fre-FR'),(50,17,'Name',0,'Prochain stage','','string','name','fre-FR'),(51,17,'Description',0,'','<p>Le prochain stage.</p>','xmlbloc','description','fre-FR'),(52,17,'Image',0,'','','image','image','fre-FR');
/*!40000 ALTER TABLE `contentobject_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contentobjects`
--

DROP TABLE IF EXISTS `contentobjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentobjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_name` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `class_identifier` varchar(32) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_identifier` (`class_identifier`,`node_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentobjects`
--

LOCK TABLES `contentobjects` WRITE;
/*!40000 ALTER TABLE `contentobjects` DISABLE KEYS */;
INSERT INTO `contentobjects` VALUES (1,'accueil',1307482362,0,'article',0),(2,'enluminure-et-caligraphie',1307482397,0,'article',0),(3,'contact',1307483419,0,'article',0),(4,'test-image',1308051670,0,'article',0),(5,'creations-originales',1308464993,0,'article',0),(6,'article-de-test',1308612214,0,'article',0),(7,'article-root',1309464951,0,'article',0),(8,'test',1309759292,0,'article',0),(9,'test',1309846786,0,'article',0),(10,'ange-orant',1310018890,0,'image',0),(11,'annonciation',1310106974,0,'image',0),(16,'dernieres-infos',1310192153,0,'article',0),(17,'prochain-stage',1310192173,0,'breve',0);
/*!40000 ALTER TABLE `contentobjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_node_id` int(11) NOT NULL,
  `sort_val` varchar(255) NOT NULL,
  `path_ids` varchar(255) NOT NULL,
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`node_id`),
  KEY `parent_node_id` (`parent_node_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,0,'00','/1/',1),(2,1,'00/10','/1/2/',1),(3,0,'10','/3/',1),(4,1,'00/05','/1/4/',1),(5,2,'00/10/10','/1/2/5/',1),(6,4,'00/05/05','/1/4/6/',1),(7,2,'00/10/20','/1/2/7/',1),(8,2,'00/10/05','/1/2/8/',1),(9,6,'00/05/05/05','/1/4/6/9/',1),(10,5,'00/10/10/05','/1/2/5/10/',1),(11,5,'00/10/10/10','/1/2/5/11/',1),(16,1,'00/15','/1/16/',1),(17,16,'00/15/05','/1/16/17/',1);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin'),(2,'anonymous'),(3,'test');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;
INSERT INTO `rules` VALUES (1,1,'content','read',''),(2,1,'content','edit',''),(3,2,'content','read','');
/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'standard');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (1,'test'),(3,'test2'),(4,'test3'),(5,'test3_4'),(6,'test3'),(7,'test3_6');
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `login` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','bab77ccf06f0b1f982e11c60f344c3c2',3,'admin');
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

-- Dump completed on 2011-08-12 17:41:55
