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

LOCK TABLES `alert_deploy` WRITE;
/*!40000 ALTER TABLE `alert_deploy` DISABLE KEYS */;
INSERT INTO `alert_deploy` VALUES (1,'测试','hour/9,10,11,12,13,14,15,16,17,18,19,20','xuhao05,lingjing,zhangqingqing,zhangchunyu,chenfubin','xuhao05,lingjing',''),(2,'NOC重试','hour/9,10,11,12,13','xuhao05,lingjing,zhangqingqing,zhangchunyu','xuhao05',''),(3,'Iplat命令下发','','xuhao05,lingjing,zhangqingqing,zhangchunyu,chenfubin','xuhao05,lingjing',''),(4,'test','','xuhao05','','http://www.baidu.com/url.php');
/*!40000 ALTER TABLE `alert_deploy` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `alert_receiver` WRITE;
/*!40000 ALTER TABLE `alert_receiver` DISABLE KEYS */;
INSERT INTO `alert_receiver` VALUES (2,1,'xuhao05','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(3,2,'xuhao05','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(4,2,'lingjing','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(5,2,'zhangqingqing','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(6,2,'zhangchunyu','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(7,2,'chenfubin','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(8,3,'lingjing','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(9,3,'zhangqingqing','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail'),(10,3,'xuhao05','hour/9,10,11,12,13,14,15,16,17,18,19,20','mail');
/*!40000 ALTER TABLE `alert_receiver` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `chart_config` WRITE;
/*!40000 ALTER TABLE `chart_config` DISABLE KEYS */;
INSERT INTO `chart_config` VALUES (1,'测试2','select * from TABLE querygroup by ip,port',1,'$connections',1,1,'MC连接数2','demo图例',1,60,'连接数(个)',1,0,1,'http://rms.baidu.com','{\"test\":\"test\"}',1,0,'','',0,''),(2,'RMS队列堆积','select count(*) as num,\'Iplat\' as object_name from TABLE where object_name =\'Iplat\' and process_status in(0,1) UNION ALL select count(*) as num,\'IplatException\' as object_name from TABLE where object_name=\'Iplat\' and process_status=1 and property=\'exception\' UNION ALL  select count(*) as num,\'Noah\' as object_name from TABLE where object_name =\'Noah\' and process_status=1 UNION ALL select count(*) as num,\'Unified\' as object_name from TABLE where object_name =\'Unified\' and process_status=1 UNION ALL select count(*) as num,\'Eventcenter\' as object_name from TABLE where object_name =\'eventcenter\' and process_status=1 querygroup by object_name',5,'$num',1,1,'RMS队列堆积','',1,60,'堆积(个)',1,0,0,'','',0,0,'','',0,''),(3,'test','test',0,'$connection',0,1,'URL测试','',1,2,'连接数',1,5,1,'http://localhost/Althea/chart.php','[]',3,0,'test','test',60,'$create_time');
/*!40000 ALTER TABLE `chart_config` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `database_config` WRITE;
/*!40000 ALTER TABLE `database_config` DISABLE KEYS */;
INSERT INTO `database_config` VALUES (1,'mysql','general_monitor','ai-atm-ur-statistic01.ai01.baidu.com',3308,'rms','a1b2c3d4','通用监控'),(2,'mysql','monitor','ai-atm-ur-statistic01.ai01.baidu.com',3308,'rms','a1b2c3d4','监控'),(3,'mysql','sys_rms','10.50.15.154',6107,'sys_rms_nr','zT0pP_XHuJCx','rms线上库'),(4,'mysql','test','127.0.0.1',3306,'root','','test');
/*!40000 ALTER TABLE `database_config` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `log_config` WRITE;
/*!40000 ALTER TABLE `log_config` DISABLE KEYS */;
INSERT INTO `log_config` VALUES (1,'mc','mc_status',1,'add_time',60,0),(2,'api','api_status',1,'ctime',60,0),(3,'queue','queue',1,'ctime',60,1),(4,'api_monitor','rms_status_[dateFormatDay()]',2,'stime',60,0),(5,'RMS队列','data_queue',3,'create_time',60,1),(6,'IplatException','iplat_exception',3,'modify_time',60,1),(7,'job_detail','job_detail',3,'create_time',3000,1);
/*!40000 ALTER TABLE `log_config` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `monitor_condition` WRITE;
/*!40000 ALTER TABLE `monitor_condition` DISABLE KEYS */;
INSERT INTO `monitor_condition` VALUES (3,3,'and','>','','',0),(4,4,'and','==','$http_code','200',0),(5,4,'and','==','prev(http_code)','200',1),(6,4,'and','==','prev(http_code,2)','200',2),(53,9,'','==','$user','1',0),(55,1,'','==','$title','200',0);
/*!40000 ALTER TABLE `monitor_condition` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `monitor_rule` WRITE;
/*!40000 ALTER TABLE `monitor_rule` DISABLE KEYS */;
INSERT INTO `monitor_rule` VALUES (1,1,'mc连接数tt','select * from TABLE QUERYGROUP BY ip,port',1,0,0,'共有[count()]台MC连接数过高 [$ip]:[$port]','<tr><td>IP</td><td>端口</td><td>连接数</td><td>上一分钟连接数</td><td>时间</td></tr>','<tr><td>[join(ip,{ip_id,id},$ip_id,ip)]</td><td>[$port]</td><td>[$connections]</td><td>[prev(connections)]</td><td>[$add_time]</tr>',4,0,0,90,'',0,'','[]',1,1,'test2','ctime2',16),(2,2,'接口监控','',0,3,3,'接口报警','','<tr><td>{$interface}</td><td>{$http_code}</td><td>{$message}</td></tr>',0,0,0,60,'',0,'','',0,0,'','',0),(3,3,'queue_monitor','',1,0,0,'共有[count()]个队列堆积过高','<tr><td>queue</td><td>stack</td><td>状态</td><td>时间</td></tr>','<tr><td>[$queue_name]</td><td>[$size]</td><td>[$status]</td><td>[$ctime]</tr>',1,0,0,60,'',0,'','',0,0,'','',0),(4,4,'接口监控','select * from TABLE where result=0 querygroup by api_id',1,0,0,'共有[count()]个接口异常','<tr><td>接口ID</td><td>IP</td><td>端口</td><td>执行时间</td><td>http_code</td><td>上一周期</td><td>上二周期</td><td>时间</td></tr>','<tr><td>[join(api_id,{config_common_api,id},$api_id,api_name)]<td>[$api_isp_ip]</td><td>[$port]</td><td>[$total_time]</td><td>[$http_code]</td><td>[prev(http_code)]</td><td>[prev(http_code,2)]</td><td>[$stime]</td></tr>',2,0,0,60,'0 or 1 or 2',0,'','{\"test\":\"123\",\"abc\":\"test\"}',3,0,'','',0),(5,6,'IplatException重试监控','select TABLE.id as id,job_detail_id,table_name,case_id,step,list_id,relation_id,modify_time from TABLE join noc_case_list on noc_case_list.id=TABLE.case_id where noc_case_list.status in(0,1,3) and send_over=0 and TABLE.status=2 and TIMESTAMPDIFF(SECOND,modify_time,now()) > 7200 and case_id>10000',1,0,0,'共有[count()]个Iplat命令重试未返回','<tr><td>IplatException id</td><td>jobdetail id</td><td>caseID</td><td>单号</td><td>当前步骤</td><td>资源表</td><td>资源ID</td><td>发送命令时间</td></tr>','<tr><td>[$id]</td><td>[$job_detail_id]</td><td>[$case_id]</td><td>[$list_id]</td><td>[$step]</td><td>[$table_name]</td><td>[$relation_id]</td><td>[$modify_time]</td></tr>',2,0,0,3600,'1',0,'','[]',1,0,'','',0),(6,7,'Job detail异常','select TABLE.id as id,TABLE.atom_operation as atom_operation,uuid,list_id,object_id,TABLE.create_time as create_time from TABLE join noah_list on noah_list.job_id=TABLE.job_id where atom_operation in (\'PASSWD_INIT\',\'DNS_OP\',\'SEND_INSTALL_PARAMS\') and status=\'completed\' and dev_table = \'data_queue\'',1,0,0,'共有[count()]个原子命令异常未发送下一步操作','<tr><td>jobdetail id</td><td>原子命令</td><td>uuid</td><td>单号</td><td>服务器ID</td><td>时间</td></tr>','<tr><td>[$id]</td><td>[$atom_operation]</td><td>[$uuid]</td><td>[$list_id]</td><td>[$object_id]</td><td>[$create_time]</td></tr>',3,0,0,3600,'1',0,'','[]',1,0,'','',0),(7,6,'Noc异常','select TABLE.case_id,TABLE.relation_id,TABLE.info,TABLE.create_time,TABLE.list_id,TABLE.status,TABLE.rms_queue_id,TABLE.step from TABLE left join noc_case_list `case` on `case`.mask = TABLE.mask left join unified_detail ud on ud.list_id = TABLE.list_id  where ud.server_status = 1 and ud.server_id = TABLE.relation_id and `case`.status in (0,1,3) and TABLE.status in (0,2,3) and TABLE.send_over=0',1,0,0,'共有[count()]个机器已经交付，但还在NOC中','<tr><td>case_id</td><td>服务器ID</td><td>SN</td><td>单号</td><td>队列 ID</td><td>步骤</td><td>时间</td></tr>','<tr><td>[$case_id]</td><td>[$relation_id]</td><td>[$info]</td><td>[$list_id]</td><td>[$rms_queue_id]</td><td>[$create_time]</td></tr>',2,0,0,3600,'1',0,'','[]',1,0,'','',0),(8,6,'Noc异常','select TABLE.id,TABLE.info sn,TABLE.step,TABLE.list_id,u.list_type,TABLE.create_time from TABLE join unified_list u on u.id=TABLE.list_id where status =0 and case_id =0 and mask not in(\"CASE_MASK_GOTO_NEXT\",\"CASE_MASK_NOAH_OVER\") and TABLE.send_over = 0',1,0,0,'共有[count()]个机器','<tr><td>IplatExceptionId</td><td>SN</td><td>步骤</td><td>单号 ID</td><td>类型</td><td>时间</td></tr>','<tr><td>[$id]</td><td>[$sn]</td><td>[$step]</td><td>[$list_id]</td><td>[$list_type]</td><td>[$create_time]</td></tr>',1,0,0,3600,'1',0,'','[]',1,0,'','',0),(9,0,'测试用','select * from test where status=1',0,0,0,'测试','用户  创建时间 \n','[$user]  [$create_time]',1,0,1,60,'',1,'http://localhost/Althea/index-test.php','[]',4,0,'','',0);
/*!40000 ALTER TABLE `monitor_rule` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-19 10:54:16
