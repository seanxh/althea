-- MySQL dump 10.13  Distrib 5.6.20, for osx10.8 (x86_64)
--
-- Host: localhost    Database: monitor
-- ------------------------------------------------------
-- Server version	5.6.20

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
-- Table structure for table `alert_deploy`
--

DROP TABLE IF EXISTS `alert_deploy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alert_deploy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert_name` varchar(50) NOT NULL,
  `rule` varchar(500) NOT NULL DEFAULT '',
  `mail_receiver` varchar(1000) NOT NULL DEFAULT '',
  `message_receiver` varchar(1000) NOT NULL DEFAULT '',
  `url_receiver` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alert_deploy`
--


--
-- Table structure for table `alert_receiver`
--

DROP TABLE IF EXISTS `alert_receiver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alert_receiver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert_deploy_id` int(11) NOT NULL,
  `receiver` varchar(30) NOT NULL,
  `rule` varchar(50) NOT NULL,
  `type` char(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alert_receiver`
--


--
-- Table structure for table `chart_config`
--

DROP TABLE IF EXISTS `chart_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `select_sql` varchar(1000) DEFAULT NULL,
  `log_id` int(11) NOT NULL DEFAULT '0',
  `y_expression` varchar(500) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL,
  `realtime` tinyint(4) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(200) NOT NULL DEFAULT '',
  `theme` int(11) NOT NULL,
  `cycle` int(11) NOT NULL DEFAULT '0',
  `y_title` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `max_points` int(11) NOT NULL DEFAULT '0',
  `data_type` tinyint(4) NOT NULL DEFAULT '0',
  `data_url` varchar(500) NOT NULL DEFAULT '',
  `data_url_parameters` varchar(2000) NOT NULL DEFAULT '',
  `database_id` int(11) NOT NULL DEFAULT '0',
  `log_type` tinyint(4) NOT NULL DEFAULT '0',
  `log_table_name` varchar(200) NOT NULL DEFAULT '',
  `log_time_column` varchar(100) NOT NULL DEFAULT '',
  `log_cycle` int(11) NOT NULL DEFAULT '0',
  `x_expression` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_config`
--


--
-- Table structure for table `database_config`
--

DROP TABLE IF EXISTS `database_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `database_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(15) NOT NULL DEFAULT 'mysql',
  `dbname` varchar(30) NOT NULL,
  `host` varchar(150) NOT NULL,
  `port` int(11) NOT NULL,
  `user` varchar(30) NOT NULL,
  `passwd` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `database_config`
--

-- Table structure for table `log_config`
--

DROP TABLE IF EXISTS `log_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_name` varchar(200) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `database_id` int(11) NOT NULL,
  `time_column` varchar(50) NOT NULL,
  `log_cycle` int(11) NOT NULL,
  `log_type` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_config`
--

-- Table structure for table `monitor_condition`
--

DROP TABLE IF EXISTS `monitor_condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `logci_operator` char(10) NOT NULL DEFAULT '',
  `comparison_operator` char(30) NOT NULL DEFAULT '',
  `left_expression` varchar(500) NOT NULL,
  `right_expression` varchar(500) NOT NULL,
  `serial_num` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rule_id` (`rule_id`,`serial_num`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitor_condition`
--


--
-- Table structure for table `monitor_rule`
--

DROP TABLE IF EXISTS `monitor_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL DEFAULT '0',
  `monitor_name` varchar(100) NOT NULL,
  `select_sql` varchar(2000) NOT NULL,
  `is_alert_everytime` tinyint(4) NOT NULL DEFAULT '0',
  `alert_in_cycles` int(11) NOT NULL DEFAULT '0',
  `alert_when_gt_times` int(11) NOT NULL DEFAULT '0',
  `alert_title` varchar(2000) NOT NULL,
  `alert_head` varchar(2000) NOT NULL,
  `alert_content` varchar(2000) NOT NULL,
  `alert_deploy_id` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `cycle` int(11) NOT NULL,
  `condition_logic_operator` varchar(1000) NOT NULL DEFAULT '',
  `data_type` tinyint(4) NOT NULL DEFAULT '0',
  `data_url` varchar(500) NOT NULL DEFAULT '',
  `data_url_parameters` varchar(2000) NOT NULL DEFAULT '',
  `database_id` int(11) NOT NULL DEFAULT '0',
  `log_type` tinyint(4) NOT NULL DEFAULT '0',
  `log_table_name` varchar(200) NOT NULL DEFAULT '',
  `log_time_column` varchar(100) NOT NULL DEFAULT '',
  `log_cycle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitor_rule`
--
-- Dump completed on 2014-10-19 10:54:16
