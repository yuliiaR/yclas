-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: reoc
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.10.1

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
-- Table structure for table `oc_access`
--

DROP TABLE IF EXISTS `oc_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_access`
--

LOCK TABLES `oc_access` WRITE;
/*!40000 ALTER TABLE `oc_access` DISABLE KEYS */;
INSERT INTO `oc_access` (`id_access`, `id_role`, `access`) VALUES (1,10,'*.*'),(2,1,'profile.*');
/*!40000 ALTER TABLE `oc_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_ads`
--

DROP TABLE IF EXISTS `oc_ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_ads` (
  `id_ad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `adress` varchar(145) DEFAULT '0',
  `price` decimal(14,3) NOT NULL DEFAULT '0.000',
  `phone` varchar(30) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `ip_address` float DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ad`) USING BTREE,
  UNIQUE KEY `ads_UK_seotitle` (`seotitle`),
  KEY `ads_IK_id_user` (`id_user`),
  KEY `ads_IK_id_category` (`id_category`),
  KEY `ads_IK_title` (`title`),
  KEY `ads_IK_status` (`status`),
  CONSTRAINT `ads_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `oc_categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ads_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `oc_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_ads`
--

LOCK TABLES `oc_ads` WRITE;
/*!40000 ALTER TABLE `oc_ads` DISABLE KEYS */;
INSERT INTO `oc_ads` (`id_ad`, `id_user`, `id_category`, `id_location`, `type`, `title`, `seotitle`, `description`, `adress`, `price`, `phone`, `website`, `ip_address`, `created`, `published`, `status`, `has_images`) VALUES (193,2,1,1,0,'1','1','asd','',0.000,'',NULL,NULL,'2013-01-25 09:47:15',NULL,1,1),(194,2,1,2,0,'1','1-1','asd','',0.000,'',NULL,NULL,'2013-01-25 09:47:34',NULL,1,1),(195,2,1,1,0,'2','2','ads','',0.000,'',NULL,NULL,'2013-01-25 09:49:53',NULL,1,1),(196,2,4,2,0,'iphone 4s CHEEP!!!','iphone-4s-cheep','I have it for a about 1 year. I kept it in good condition.\nBut i want new one. And im selling this one cheep. \nGo go, contact me.','deu i mata',120.000,'123123',NULL,NULL,'2013-01-29 08:58:09',NULL,1,1),(197,2,2,3,0,'asd','asd','asd','',0.000,'',NULL,NULL,'2013-01-29 09:44:22',NULL,1,0),(198,2,1,3,0,'asd','asd-1','asd','',0.000,'',NULL,NULL,'2013-01-29 09:46:08',NULL,1,0),(199,2,2,3,0,'asdfgsadwqe11232','asdfgsadwqe11232','asd','',0.000,'',NULL,NULL,'2013-01-29 09:46:36',NULL,1,0),(200,2,1,2,0,'image deletew','image-deletew','asd','',0.000,'',NULL,NULL,'2013-01-29 09:47:18',NULL,1,0),(201,2,1,1,0,'123','123','asd','',0.000,'',NULL,NULL,'2013-01-29 09:51:57',NULL,1,0),(202,2,1,1,0,'published','published','asd','',0.000,'',NULL,NULL,'2013-01-29 09:53:30','2013-01-29 10:53:30',50,0),(203,2,1,1,0,'published','published-1','asd\n[u]asdasd[/u][ol][li][u]asd[/u][/li][li][u]asd[/u][/li][li][u]asd[/u][/li][li][u][b]asd[/b][/u][/li][/ol]','',0.000,'123',NULL,NULL,'2013-01-29 09:54:44','2013-01-29 10:54:44',1,1),(204,2,2,2,0,'notpublished','notpublished','asd','',0.000,'',NULL,NULL,'2013-01-29 10:03:01',NULL,0,0),(205,2,2,2,0,'lalalaa','lalalaa','asd','',0.000,'123',NULL,NULL,'2013-01-29 10:04:16',NULL,0,0),(208,2,4,2,0,'asd','asd-2','[ul][li][b]asdasdasd[/b][/li][/ul]\n[ul][li][b]asdasd[/b][/li][/ul]','adsa',123.000,'123123',NULL,NULL,'2013-01-29 11:18:17',NULL,0,1),(209,2,3,3,0,'new123','new123','asd','',0.000,'',NULL,NULL,'2013-01-29 11:29:30','2013-01-29 12:29:30',50,1),(212,2,3,2,0,'asdyusad','asdyusad','asd[b]asd[/b]','',0.000,'',NULL,NULL,'2013-01-30 11:06:15','2013-01-30 12:06:15',30,0),(213,2,1,5,0,'belgrade','belgrade','asdasd','',0.000,'',NULL,NULL,'2013-02-04 09:48:52','2013-02-04 10:48:52',1,0),(214,2,1,1,0,'send mail','send-mail','asd','',0.000,'',NULL,NULL,'2013-02-04 10:29:17','2013-02-04 11:29:17',1,0);
/*!40000 ALTER TABLE `oc_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_categories`
--

DROP TABLE IF EXISTS `oc_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_categories` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_category_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE KEY `categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_categories`
--

LOCK TABLES `oc_categories` WRITE;
/*!40000 ALTER TABLE `oc_categories` DISABLE KEYS */;
INSERT INTO `oc_categories` (`id_category`, `name`, `order`, `created`, `id_category_parent`, `parent_deep`, `seoname`, `description`, `price`) VALUES (1,'all',0,'2013-01-29 08:36:18',0,0,'all','all',0),(2,'cat_car',1,'2012-12-14 10:53:20',1,1,'catcar',NULL,0),(3,'cat_house',2,'2012-12-14 10:53:44',2,2,'cathouse',NULL,0),(4,'mobile',3,'2013-01-29 08:37:07',2,0,'mobile','mobile',0);
/*!40000 ALTER TABLE `oc_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_config`
--

DROP TABLE IF EXISTS `oc_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_config` (
  `group_name` varchar(128) NOT NULL,
  `config_key` varchar(128) NOT NULL,
  `config_value` text,
  PRIMARY KEY (`group_name`,`config_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_config`
--

LOCK TABLES `oc_config` WRITE;
/*!40000 ALTER TABLE `oc_config` DISABLE KEYS */;
INSERT INTO `oc_config` (`group_name`, `config_key`, `config_value`) VALUES ('appearance','theme','default'),('i18n','charset','utf-8'),('init','base_url','/'),('i18n','timezone','Europe/Madrid'),('i18n','locale','en_US'),('cookie','salt','13413mdksdf-948jd'),('general','moderation','0'),('asd','','asd');
/*!40000 ALTER TABLE `oc_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_content`
--

DROP TABLE IF EXISTS `oc_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text,
  `from_email` varchar(145) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  UNIQUE KEY `as_content_UK_id_language_AND_seotitle` (`seotitle`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_content`
--

LOCK TABLES `oc_content` WRITE;
/*!40000 ALTER TABLE `oc_content` DISABLE KEYS */;
INSERT INTO `oc_content` (`id_content`, `order`, `title`, `seotitle`, `description`, `from_email`, `created`, `type`, `status`) VALUES (2,1,'New advertisement','newadvertisement','This is reserved for sending mails to users that have created new advert.\n\nNOTE: Repalce this field with appropriate format.\nAnd in field \"Form Email\" add your own email.\n\nWARNING: If u change title or seotitle this mail wont work , so be sure to check \"how to.txt\" -- email configuration','root@slobodantumanitas-System','2013-02-04 11:11:15','email',1),(3,1,'New User','newuser','This is reserved for sending mails to new users.\n\nNOTE: Repalce this field with appropriate format.\nIn field \"Form Email\" add your own email.\n\nWARNING: If you change title or seotitle this mail wont work , so be sure to check \"how to.txt\" -- email configuration','root@slobodantumanitas-System','2013-02-04 12:01:55','email',1);
/*!40000 ALTER TABLE `oc_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_locations`
--

DROP TABLE IF EXISTS `oc_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_locations`
--

LOCK TABLES `oc_locations` WRITE;
/*!40000 ALTER TABLE `oc_locations` DISABLE KEYS */;
INSERT INTO `oc_locations` (`id_location`, `name`, `id_location_parent`, `parent_deep`, `seoname`, `description`, `lat`, `lng`) VALUES (1,'all',0,0,'all','root location',NULL,NULL),(2,'spain',1,0,'spain',NULL,NULL,NULL),(3,'barcelona',1,1,'barcelona',NULL,NULL,NULL),(4,'madrid',1,1,'madrid',NULL,NULL,NULL),(5,'Belgrade',2,0,'belgrade',NULL,NULL,NULL);
/*!40000 ALTER TABLE `oc_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_roles`
--

DROP TABLE IF EXISTS `oc_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `oc_roles_UK_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_roles`
--

LOCK TABLES `oc_roles` WRITE;
/*!40000 ALTER TABLE `oc_roles` DISABLE KEYS */;
INSERT INTO `oc_roles` (`id_role`, `name`, `description`, `date_created`) VALUES (1,'user','Normal user','2012-12-14 10:40:02'),(10,'Administrator','Access to everything','2012-12-14 10:40:02');
/*!40000 ALTER TABLE `oc_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_users`
--

DROP TABLE IF EXISTS `oc_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `seoname` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `id_role` int(10) unsigned DEFAULT '1',
  `id_location` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime DEFAULT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip` float DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `users_UK_email` (`email`),
  UNIQUE KEY `users_UK_token` (`token`),
  UNIQUE KEY `users_UK_seoname` (`seoname`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_users`
--

LOCK TABLES `oc_users` WRITE;
/*!40000 ALTER TABLE `oc_users` DISABLE KEYS */;
INSERT INTO `oc_users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`, `id_location`, `created`, `last_modified`, `logins`, `last_login`, `last_ip`, `user_agent`, `token`, `token_created`, `token_expires`) VALUES (1,'chema',NULL,'neo22s@gmail.com','15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd',1,10,NULL,'2012-12-14 10:40:02',NULL,1,'2012-12-14 11:49:59',2130710000,'0e63dfc2619929acb7808fd16798d388ff4f50a7','b7c6dd003b4243a9888a81d4fc42d4e0faa271a9','2012-12-14 11:50:37','2013-03-14 11:50:37'),(2,'slobodan',NULL,'slobodan.josifovic@gmail.com','15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd',1,10,NULL,'2012-12-14 10:48:26',NULL,66,'2013-02-04 13:11:33',2130710000,'be639db1da16426edd9ce5890429a9a1022ec044','a50ff5eb979d0d66ebc9a7cc2550d53bf953fb6c','2013-02-04 13:11:33','2013-05-05 14:11:33'),(26,'andrey',NULL,'andrey@gmail.com','15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd',1,1,NULL,'2013-01-17 11:57:57',NULL,7,'2013-02-01 10:11:53',2130710000,'64bf8ec195ca70e7458e2ea8aa7f6330be09ae1f','31ea07c9136a79d691ed0aa2165e0c172bb54ffd','2013-02-01 10:11:58','2013-05-02 11:11:58');
/*!40000 ALTER TABLE `oc_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oc_visits`
--

DROP TABLE IF EXISTS `oc_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oc_visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `visits_IK_id_user` (`id_user`),
  KEY `visits_IK_id_ad` (`id_ad`)
) ENGINE=MyISAM AUTO_INCREMENT=241 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oc_visits`
--

LOCK TABLES `oc_visits` WRITE;
/*!40000 ALTER TABLE `oc_visits` DISABLE KEYS */;
INSERT INTO `oc_visits` (`id_visit`, `id_ad`, `id_user`, `created`, `ip_address`) VALUES (102,32,2,'2013-01-07 11:28:15',NULL),(101,33,2,'2013-01-07 11:28:15',NULL),(100,34,2,'2013-01-07 11:28:15',NULL),(99,34,2,'2013-01-07 11:24:58',NULL),(98,34,2,'2013-01-07 11:24:56',NULL),(97,34,2,'2013-01-07 11:24:55',NULL),(96,34,2,'2013-01-07 11:24:54',NULL),(95,34,2,'2013-01-07 11:24:52',NULL),(94,34,2,'2013-01-07 11:24:13',NULL),(93,34,2,'2013-01-07 11:24:13',NULL),(92,34,2,'2013-01-07 11:24:10',NULL),(91,34,2,'2013-01-07 11:24:10',NULL),(90,34,2,'2013-01-07 11:22:31',NULL),(89,34,2,'2013-01-07 11:22:31',NULL),(88,34,2,'2013-01-07 11:22:27',NULL),(87,34,2,'2013-01-07 11:22:27',NULL),(86,34,2,'2013-01-07 11:22:06',NULL),(85,34,2,'2013-01-07 11:22:06',NULL),(84,34,2,'2013-01-07 11:20:22',NULL),(83,30,2,'2013-01-07 11:19:38',NULL),(82,31,2,'2013-01-07 11:19:38',NULL),(81,32,2,'2013-01-07 11:19:38',NULL),(80,33,2,'2013-01-07 11:19:38',NULL),(79,34,2,'2013-01-07 11:19:38',NULL),(78,30,2,'2013-01-07 11:01:14',NULL),(77,31,2,'2013-01-07 11:01:14',NULL),(76,32,2,'2013-01-07 11:01:14',NULL),(75,33,2,'2013-01-07 11:01:14',NULL),(74,34,2,'2013-01-07 11:01:14',NULL),(73,30,2,'2013-01-07 10:59:49',NULL),(72,31,2,'2013-01-07 10:59:49',NULL),(71,32,2,'2013-01-07 10:59:49',NULL),(70,33,2,'2013-01-07 10:59:49',NULL),(69,34,2,'2013-01-07 10:59:49',NULL),(68,30,2,'2013-01-07 10:54:54',NULL),(67,31,2,'2013-01-07 10:54:54',NULL),(66,32,2,'2013-01-07 10:54:54',NULL),(65,33,2,'2013-01-07 10:54:54',NULL),(64,34,2,'2013-01-07 10:54:54',NULL),(63,31,2,'2013-01-07 10:53:06',NULL),(62,31,2,'2013-01-07 10:49:29',NULL),(61,31,2,'2013-01-07 10:49:06',NULL),(60,31,2,'2013-01-07 10:48:41',NULL),(59,31,2,'2013-01-07 10:48:33',NULL),(58,31,2,'2013-01-07 10:45:12',NULL),(57,34,2,'2013-01-07 10:45:09',NULL),(56,31,2,'2013-01-07 10:45:07',NULL),(55,34,2,'2013-01-07 10:45:04',NULL),(54,34,2,'2013-01-07 10:45:02',NULL),(53,32,2,'2013-01-07 10:44:53',NULL),(104,30,2,'2013-01-07 11:28:15',NULL),(103,31,2,'2013-01-07 11:28:15',NULL),(105,34,2,'2013-01-07 11:29:01',NULL),(106,33,2,'2013-01-07 11:29:01',NULL),(107,32,2,'2013-01-07 11:29:01',NULL),(108,31,2,'2013-01-07 11:29:01',NULL),(109,30,2,'2013-01-07 11:29:01',NULL),(110,34,2,'2013-01-07 11:29:24',NULL),(111,33,2,'2013-01-07 11:29:24',NULL),(112,32,2,'2013-01-07 11:29:24',NULL),(113,31,2,'2013-01-07 11:29:24',NULL),(114,30,2,'2013-01-07 11:29:24',NULL),(115,34,2,'2013-01-07 11:29:26',NULL),(116,33,2,'2013-01-07 11:29:32',NULL),(117,33,2,'2013-01-07 11:29:39',NULL),(118,32,2,'2013-01-07 11:29:42',NULL),(119,32,2,'2013-01-07 11:29:46',NULL),(120,34,2,'2013-01-07 11:34:53',NULL),(121,33,2,'2013-01-07 11:34:53',NULL),(122,32,2,'2013-01-07 11:34:53',NULL),(123,31,2,'2013-01-07 11:34:53',NULL),(124,30,2,'2013-01-07 11:34:53',NULL),(125,34,2,'2013-01-07 11:34:55',NULL),(126,33,2,'2013-01-07 11:34:55',NULL),(127,32,2,'2013-01-07 11:34:55',NULL),(128,31,2,'2013-01-07 11:34:55',NULL),(129,30,2,'2013-01-07 11:34:55',NULL),(130,34,2,'2013-01-07 11:35:06',NULL),(131,33,2,'2013-01-07 11:35:06',NULL),(132,32,2,'2013-01-07 11:35:06',NULL),(133,31,2,'2013-01-07 11:35:06',NULL),(134,30,2,'2013-01-07 11:35:06',NULL),(135,34,2,'2013-01-07 11:42:21',NULL),(136,34,2,'2013-01-07 11:43:49',NULL),(137,34,2,'2013-01-07 11:56:42',NULL),(138,34,2,'2013-01-07 11:57:02',NULL),(139,34,2,'2013-01-07 11:57:42',NULL),(140,34,2,'2013-01-07 11:57:44',NULL),(141,34,2,'2013-01-07 11:58:02',NULL),(142,34,2,'2013-01-08 08:34:05',NULL),(143,33,2,'2013-01-08 08:34:05',NULL),(144,32,2,'2013-01-08 08:34:05',NULL),(145,31,2,'2013-01-08 08:34:05',NULL),(146,30,2,'2013-01-08 08:34:05',NULL),(147,34,2,'2013-01-08 08:35:35',NULL),(148,33,2,'2013-01-08 08:35:35',NULL),(149,32,2,'2013-01-08 08:35:35',NULL),(150,31,2,'2013-01-08 08:35:35',NULL),(151,30,2,'2013-01-08 08:35:35',NULL),(152,34,2,'2013-01-08 08:36:42',NULL),(153,33,2,'2013-01-08 08:36:42',NULL),(154,32,2,'2013-01-08 08:36:42',NULL),(155,31,2,'2013-01-08 08:36:42',NULL),(156,30,2,'2013-01-08 08:36:42',NULL),(157,34,2,'2013-01-08 08:45:10',NULL),(158,33,2,'2013-01-08 08:45:10',NULL),(159,32,2,'2013-01-08 08:45:10',NULL),(160,31,2,'2013-01-08 08:45:10',NULL),(161,30,2,'2013-01-08 08:45:10',NULL),(162,34,2,'2013-01-08 08:45:57',NULL),(163,33,2,'2013-01-08 08:45:57',NULL),(164,32,2,'2013-01-08 08:45:57',NULL),(165,31,2,'2013-01-08 08:45:57',NULL),(166,30,2,'2013-01-08 08:45:57',NULL),(167,34,2,'2013-01-08 08:46:06',NULL),(168,33,2,'2013-01-08 08:46:06',NULL),(169,32,2,'2013-01-08 08:46:06',NULL),(170,31,2,'2013-01-08 08:46:06',NULL),(171,30,2,'2013-01-08 08:46:06',NULL),(172,34,2,'2013-01-08 08:46:34',NULL),(173,33,2,'2013-01-08 08:46:34',NULL),(174,32,2,'2013-01-08 08:46:34',NULL),(175,31,2,'2013-01-08 08:46:34',NULL),(176,30,2,'2013-01-08 08:46:34',NULL),(177,34,2,'2013-01-08 08:47:00',NULL),(178,33,2,'2013-01-08 08:47:00',NULL),(179,32,2,'2013-01-08 08:47:00',NULL),(180,31,2,'2013-01-08 08:47:00',NULL),(181,30,2,'2013-01-08 08:47:00',NULL),(182,34,2,'2013-01-08 08:47:10',NULL),(183,33,2,'2013-01-08 08:47:10',NULL),(184,32,2,'2013-01-08 08:47:10',NULL),(185,31,2,'2013-01-08 08:47:10',NULL),(186,30,2,'2013-01-08 08:47:10',NULL),(187,34,2,'2013-01-08 09:54:01',NULL),(188,33,2,'2013-01-08 09:54:08',NULL),(189,33,2,'2013-01-08 09:54:12',NULL),(190,33,2,'2013-01-08 09:54:15',NULL),(191,4,1,'2013-01-08 10:03:26',NULL),(192,38,2,'2013-01-08 10:29:33',NULL),(193,46,2,'2013-01-08 11:13:55',NULL),(194,43,2,'2013-01-09 10:23:58',NULL),(195,3,2,'2013-01-09 10:25:58',NULL),(196,43,2,'2013-01-15 08:25:50',NULL),(197,46,2,'2013-01-15 09:15:01',NULL),(198,46,2,'2013-01-15 09:19:45',NULL),(199,46,2,'2013-01-15 10:02:18',NULL),(200,46,2,'2013-01-15 10:09:29',NULL),(201,72,2,'2013-01-17 10:52:40',NULL),(202,74,25,'2013-01-17 10:54:24',NULL),(203,75,2,'2013-01-17 11:23:25',NULL),(204,75,2,'2013-01-17 11:53:00',NULL),(205,74,25,'2013-01-18 10:44:04',NULL),(206,74,25,'2013-01-18 10:52:59',NULL),(207,84,2,'2013-01-21 08:18:24',NULL),(208,98,2,'2013-01-21 09:23:43',NULL),(209,120,2,'2013-01-22 08:06:05',NULL),(210,129,2,'2013-01-23 08:13:54',NULL),(211,130,2,'2013-01-23 08:17:09',NULL),(212,155,2,'2013-01-23 09:13:09',NULL),(213,157,27,'2013-01-23 10:10:34',NULL),(214,157,27,'2013-01-23 10:10:55',NULL),(215,157,27,'2013-01-23 10:16:59',NULL),(216,157,27,'2013-01-23 10:17:47',NULL),(217,157,27,'2013-01-23 10:20:28',NULL),(218,157,27,'2013-01-23 10:31:37',NULL),(219,157,27,'2013-01-23 12:01:02',NULL),(220,157,27,'2013-01-23 12:01:25',NULL),(221,157,27,'2013-01-23 12:02:29',NULL),(222,195,2,'2013-01-29 08:09:31',NULL),(223,195,2,'2013-01-29 08:11:07',NULL),(224,208,2,'2013-01-29 11:20:28',NULL),(225,210,2,'2013-01-30 08:59:01',NULL),(226,210,2,'2013-01-30 09:02:28',NULL),(227,210,2,'2013-01-30 09:05:26',NULL),(228,210,2,'2013-01-30 09:05:39',NULL),(229,210,2,'2013-01-30 09:06:00',NULL),(230,209,2,'2013-01-30 09:07:08',NULL),(231,209,2,'2013-01-30 09:07:14',NULL),(232,209,2,'2013-01-30 09:07:41',NULL),(233,209,2,'2013-01-30 09:08:11',NULL),(234,209,2,'2013-01-30 09:08:28',NULL),(235,209,2,'2013-01-30 09:08:31',NULL),(236,209,2,'2013-01-30 09:08:35',NULL),(237,209,2,'2013-01-30 09:08:45',NULL),(238,209,2,'2013-01-30 09:12:42',NULL),(239,203,2,'2013-01-30 09:17:03',NULL),(240,209,2,'2013-01-30 09:17:28',NULL);
/*!40000 ALTER TABLE `oc_visits` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-02-04 13:14:44
