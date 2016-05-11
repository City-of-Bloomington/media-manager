-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: media
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.12.04.1-log

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
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'ITS'),(2,'Animal Shelter'),(3,'HAND'),(4,'P&R'),(5,'Economic Development'),(6,'CFRD'),(7,'Planning'),(8,'City Council'),(9,'Utilities'),(10,'Public Works'),(11,'Office of the Mayor');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `derivatives`
--

DROP TABLE IF EXISTS `derivatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `derivatives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `aspectRatio_width` int(10) unsigned DEFAULT NULL,
  `aspectRatio_height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `derivatives`
--

LOCK TABLES `derivatives` WRITE;
/*!40000 ALTER TABLE `derivatives` DISABLE KEYS */;
INSERT INTO `derivatives` VALUES (6,'Cover',1600,1600,400),(7,'Page Header',152,152,152),(8,'Page Header@2',304,304,304),(9,'Content Image',510,NULL,NULL),(10,'Map Thumbnail',80,NULL,NULL),(11,'Marketing Triptych',297,297,168);
/*!40000 ALTER TABLE `derivatives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `internalFilename` varchar(50) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mime_type` varchar(128) DEFAULT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` text,
  `md5` varchar(32) NOT NULL,
  `uploaded` datetime NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`),
  CONSTRAINT `media_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=833 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (784,'55f2e321dc944','photoheadertemplate.png','image/png','image','about-alpha-banner',NULL,'87360777ef8db8409738a57464ee6a95','2015-09-11 10:20:17',4,1,NULL,NULL),(786,'55f2e66592c11','alphaheaderphoto.png','image/png','image',NULL,NULL,'8a0532f34d2b3ef968bd0f8a82f6687b','2015-09-11 10:34:13',4,1,NULL,NULL),(787,'55f2ecb684bcf','itsbanner2.png','image/png','image','ITS Banner image',NULL,'8b50763b40a672ba7069ccdb0ae9bbc4','2015-09-11 11:01:10',4,1,NULL,NULL),(788,'55f2ed2772aff','itsheaderphoto3.png','image/png','image','ITS Header Image',NULL,'46180b0becb71746493d22df94a4af18','2015-09-11 11:03:03',4,1,NULL,NULL),(789,'55f2f16445de9','bduacbanner.png','image/png','image','BDUAC Banner Image',NULL,'e2430f8191e7aea575baf46980eaca56','2015-09-11 11:21:08',4,1,NULL,NULL),(790,'55f2f16456c6e','bduacheaderphoto.png','image/png','image','BDUAC Header Photo',NULL,'45ce53f0303531fc9ae17cbc5fe6fb23','2015-09-11 11:21:08',4,1,NULL,NULL),(791,'55f2f3fd4910c','telecombanner.png','image/png','image','Telecom Banner Image',NULL,'0b8ea3d63f4e14f071d761a2d4709199','2015-09-11 11:32:13',4,1,NULL,NULL),(792,'55f2f3fd5a8cd','telecomheaderphoto.png','image/png','image','Telecom Header Image',NULL,'3c28dda4c5f0d5d244c64a18e6c00917','2015-09-11 11:32:13',4,1,NULL,NULL),(793,'5613dbeecc437','commoncouncilchambersbanner.png','image/png','image','Council Chambers Banner Image',NULL,'4d858a7dd19d93ece1890e07fd0e3b08','2015-10-06 10:34:22',4,1,NULL,NULL),(794,'56154d57344b4','JohnHamiltonheaderphoto.png','image/png','image','John Hamilton Page Header Photo',NULL,'cb123bdc0ca49a9ee298d7f3f8025395','2015-10-07 12:50:31',4,11,NULL,NULL),(795,'56168f601285a','CDMapImage.png','image/png','image','A Map of City Council Districts','District X, a district that is greater than the sum of its parts.','fe1e6c82f6445cf9a4c519d85f1f2904','2015-10-08 11:44:32',4,8,NULL,NULL),(796,'5616b8b1dfcf6','CD1MapImage.png','image/png','image','City Council District I',NULL,'4592272611c62e2236d5f1b26e7bd776','2015-10-08 14:40:49',4,8,NULL,NULL),(797,'5616b8b1eeb4b','CD2MapImage.png','image/png','image','City Council District II',NULL,'7751444b4f6ae8c64b61e8ecb79d0c3a','2015-10-08 14:40:49',4,8,NULL,NULL),(798,'5616b8b202a59','CD3MapImage.png','image/png','image','Map of City Council District III','City Council District III','33fa8ad32c7fbe0ab4164d3e335c552b','2015-10-08 14:40:50',4,8,NULL,NULL),(799,'5616b8b21463e','CD4MapImage.png','image/png','image','City Council District IV',NULL,'866d9f03a6d8e46fe5ed36c0345cf5b6','2015-10-08 14:40:50',4,8,NULL,NULL),(800,'5616b8b21c6d4','CD5MapImage.png','image/png','image','City Council District V',NULL,'aa117c12a43ec043177c137b28e08eb2','2015-10-08 14:40:50',4,8,NULL,NULL),(801,'5616b8b2248a6','CD6MapImage.png','image/png','image','Map of City Council District VI','City Council District VI','22d0f1d6382852e95bfff742abc0fc27','2015-10-08 14:40:50',4,8,NULL,NULL),(802,'5616c0cc3f092','spechler.png','image/png','image','Marty Spechler',NULL,'3c1c9a9d100d0dda07f67160f2c5edb2','2015-10-08 15:15:24',4,8,NULL,NULL),(803,'5616c6812d616','granger.png','image/png','image','Dorothy Granger',NULL,'e8bcc4e5997ed928fb30fda907c90b83','2015-10-08 15:39:45',4,8,NULL,NULL),(804,'5616c681402ac','mayer.png','image/png','image','Timothy Mayer',NULL,'4ecb52a217b82b67c66f3f3c6815f31c','2015-10-08 15:39:45',4,8,NULL,NULL),(805,'5616c6814833b','moore.png','image/png','image','Regina Moore',NULL,'ec962d7090d8cb9a6698677a3fc76441','2015-10-08 15:39:45',4,1,NULL,NULL),(806,'5616c68150fff','neher.png','image/png','image','Darryl Neher',NULL,'bb0ede6b034f31632deb0a2fcbcc0361','2015-10-08 15:39:45',4,8,NULL,NULL),(807,'5616c68158b5e','rollo.png','image/png','image','Dave Rollo',NULL,'f62106afff97241c754be9300891907e','2015-10-08 15:39:45',4,8,NULL,NULL),(808,'5616c6816346a','ruff.png','image/png','image','Andy Ruff',NULL,'9f24a4026e7a0f13cb1782cdd3c6ba15','2015-10-08 15:39:45',4,8,NULL,NULL),(809,'5616c6816b876','sandberg.png','image/png','image','Susan Sandberg',NULL,'662785f0af71aa0d9fa710f6c7d99774','2015-10-08 15:39:45',4,8,NULL,NULL),(810,'5616c68176863','sturbaum.png','image/png','image','Chris Sturbaum',NULL,'c10819142048283dff4e436b36d90397','2015-10-08 15:39:45',4,8,NULL,NULL),(811,'5616c7157c5c2','volan.png','image/png','image','Steve Volan',NULL,'6bdb863b50b5ac5f297d01a5feed995d','2015-10-08 15:42:13',4,8,NULL,NULL),(812,'5633b15b1f3c1','newsarchivebanner.jpg','image/jpeg','image','News Archive Banner',NULL,'0171cc8e47c8e24ee7d4c180f64240dd','2015-10-30 14:05:15',4,1,NULL,NULL),(813,'5633b15b7e31c','newsarchiveheaderphoto.png','image/png','image','News Archive Header Photo',NULL,'0f85e51b517a2d381e2e016e832551ff','2015-10-30 14:05:15',4,1,NULL,NULL),(814,'5633b15b85ff9','newsroombanner.jpg','image/jpeg','image','Newsroom Banner',NULL,'33149ad1e4b3376312c48f11c48b1a5c','2015-10-30 14:05:15',4,1,NULL,NULL),(815,'5633b15b94e18','newsroomheader.png','image/png','image','Newsroom Header Photo',NULL,'24c534e325910f3005a5c92e85b92a83','2015-10-30 14:05:15',4,1,NULL,NULL),(822,'5654a9a24d814','CBVN_logo_website_304x304.jpg','image/jpeg','image','CBVN logo w-website','CBVN color logo with website','1c8a747f50d5d71589266401ca76c83f','2015-11-24 13:17:06',6,6,NULL,NULL),(823,'5654ad51a9f1d','CBVN_Logo_blue_orange.png','image/png','image','CBVN logo_blue_orange','CBVN Logo blue and orange','4fad84c95ed87d13f5856f5960810159','2015-11-24 13:32:49',6,6,NULL,NULL),(824,'56704792e801b','bacbanner.jpg','image/jpeg','image','BAC Banner',NULL,'22f62d3efd98dbe946ebc0e4b1ac090f','2015-12-15 12:02:10',4,6,NULL,NULL),(825,'567047932e4ad','bacheaderphoto.png','image/png','image','BAC Header Photo',NULL,'40a26152fc8fba8e0e77dbb35802320a','2015-12-15 12:02:11',4,6,NULL,NULL),(826,'56708489861ae','calendarbanner.jpg','image/jpeg','image','Calendar Banner',NULL,'1b71dd63ddb9ac3a2efae0340a77eff6','2015-12-15 16:22:17',4,1,NULL,NULL),(827,'56708489a0954','calendarheaderphoto.png','image/png','image','Calendar Header Image',NULL,'8f3ec516ad4ecb43e1ba158db48a620c','2015-12-15 16:22:17',4,1,NULL,NULL),(829,'567c5e566234e','CCA_wheelchair_image.png','image/png','image','CCA Page header',NULL,'6713b0068cedfe57028cbb34ec00ba50','2015-12-24 16:06:30',7,6,NULL,NULL),(830,'571a8b3fb9523','weightrack2.jpg','image/jpeg','image','Dumbells',NULL,'8fd0a9a96d8409e73c5cea425ef3178c','2016-04-22 16:36:15',4,4,NULL,NULL),(831,'571a975cb3768','DSC_0736.jpg','image/jpeg','image','Canoeing',NULL,'e52a51b4148a3a172e373006f0d19d51','2016-04-22 17:27:56',4,4,NULL,NULL),(832,'571aa2677bcd4','202020_7.jpg','image/jpeg','image','Group Exercise',NULL,'eaa074cfca015b644d48a3476aaaf773','2016-04-22 18:15:03',4,4,NULL,NULL);
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_tags`
--

DROP TABLE IF EXISTS `media_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_tags` (
  `media_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  KEY `media_id` (`media_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `media_tags_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  CONSTRAINT `media_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_tags`
--

LOCK TABLES `media_tags` WRITE;
/*!40000 ALTER TABLE `media_tags` DISABLE KEYS */;
INSERT INTO `media_tags` VALUES (784,126),(784,127),(784,128),(787,126),(787,129),(787,130),(795,131),(795,132),(803,131),(804,131),(805,133),(806,131),(807,131),(809,131),(810,131),(811,131),(812,126),(812,134),(813,135),(813,134),(814,126),(814,134),(815,135),(815,134),(824,126),(826,126),(827,136),(829,137),(830,138),(831,139),(832,138);
/*!40000 ALTER TABLE `media_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(40) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `authenticationMethod` varchar(40) DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL,
  `department_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `people_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` VALUES (1,'Cliff','Ingham','inghamn@bloomington.in.gov','inghamn',NULL,'Employee','Administrator',1),(3,'Emily','Brown','browne@bloomington.in.gov',NULL,NULL,NULL,NULL,1),(4,'Dan','Hiester','hiesterd@bloomington.in.gov','hiesterd',NULL,'Employee','Administrator',6),(5,'Charles','Brandt','brandtc@bloomington.in.gov','brandtc',NULL,'Employee','Administrator',1),(6,'Lucy','Schaich','schaichl@bloomington.in.gov','schaichl',NULL,'Employee','Administrator',6),(7,'Stefanie','Green','greens@bloomington.in.gov','greens',NULL,'Employee','Administrator',6);
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (137,'accessibility'),(126,'banner'),(128,'blur'),(129,'circuits'),(131,'city council'),(133,'clerk'),(127,'code'),(138,'fitness'),(136,'header image'),(135,'header photo'),(132,'maps'),(134,'news'),(139,'outdoors'),(130,'technology');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-10  9:42:10
