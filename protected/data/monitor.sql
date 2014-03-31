/*
Navicat MySQL Data Transfer

Source Server         : localhost1212
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : monitor

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2014-03-31 00:38:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `alert_deploy`
-- ----------------------------
DROP TABLE IF EXISTS `alert_deploy`;
CREATE TABLE `alert_deploy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of alert_deploy
-- ----------------------------
INSERT INTO `alert_deploy` VALUES ('1', '测试');

-- ----------------------------
-- Table structure for `alert_receiver`
-- ----------------------------
DROP TABLE IF EXISTS `alert_receiver`;
CREATE TABLE `alert_receiver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert_deploy_id` int(11) NOT NULL,
  `receiver` varchar(30) NOT NULL,
  `rule` varchar(50) NOT NULL,
  `type` char(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of alert_receiver
-- ----------------------------
INSERT INTO `alert_receiver` VALUES ('1', '1', 'xuhao05', '', 'msg');
INSERT INTO `alert_receiver` VALUES ('2', '1', 'xuhao05', '', 'mail');

-- ----------------------------
-- Table structure for `chart_config`
-- ----------------------------
DROP TABLE IF EXISTS `chart_config`;
CREATE TABLE `chart_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `select_sql` varchar(500) NOT NULL,
  `log_id` int(11) NOT NULL,
  `expression` varchar(500) NOT NULL,
  `type` int(11) NOT NULL,
  `realtime` tinyint(4) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(200) NOT NULL,
  `theme` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `y_title` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chart_config
-- ----------------------------
INSERT INTO `chart_config` VALUES ('1', '测试', 'select * from TABLE querygroup by ip,port', '1', '$connections', '1', '0', 'MC连接数', 'demo图例', '1', '60', '连接数(个)', '1');

-- ----------------------------
-- Table structure for `database_config`
-- ----------------------------
DROP TABLE IF EXISTS `database_config`;
CREATE TABLE `database_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(15) NOT NULL,
  `dbname` varchar(30) NOT NULL,
  `host` varchar(150) NOT NULL,
  `port` int(11) NOT NULL,
  `user` varchar(30) NOT NULL,
  `passwd` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of database_config
-- ----------------------------
INSERT INTO `database_config` VALUES ('1', 'mysql', 'monitor', '127.0.0.1', '3306', 'root', 'root');

-- ----------------------------
-- Table structure for `log_config`
-- ----------------------------
DROP TABLE IF EXISTS `log_config`;
CREATE TABLE `log_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_name` varchar(200) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `database_id` int(11) NOT NULL,
  `time_column` varchar(50) NOT NULL,
  `log_cycle` int(11) NOT NULL,
  `log_type` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log_config
-- ----------------------------
INSERT INTO `log_config` VALUES ('1', 'mc', 'mc_status', '1', 'add_time', '60', '0');
INSERT INTO `log_config` VALUES ('2', 'api', 'api_status', '1', 'ctime', '60', '0');
INSERT INTO `log_config` VALUES ('3', 'queue', 'queue', '1', 'ctime', '0', '1');

-- ----------------------------
-- Table structure for `mc_status`
-- ----------------------------
DROP TABLE IF EXISTS `mc_status`;
CREATE TABLE `mc_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(70) NOT NULL,
  `port` int(11) NOT NULL,
  `connections` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_status
-- ----------------------------
INSERT INTO `mc_status` VALUES ('1', '127.0.0.1', '8008', '10000', '2014-03-30 11:55:32');
INSERT INTO `mc_status` VALUES ('2', '127.0.0.1', '8008', '90000', '2014-03-30 11:56:10');
INSERT INTO `mc_status` VALUES ('3', '127.0.0.1', '8008', '1000', '2014-03-30 11:57:10');
INSERT INTO `mc_status` VALUES ('4', '127.0.0.1', '8008', '700', '2014-03-30 11:58:10');
INSERT INTO `mc_status` VALUES ('5', '127.0.0.1', '9000', '10000', '2014-03-30 11:55:32');
INSERT INTO `mc_status` VALUES ('6', '127.0.0.1', '9000', '1000', '2014-03-30 11:56:10');
INSERT INTO `mc_status` VALUES ('7', '127.0.0.1', '9000', '8000', '2014-03-30 11:57:10');
INSERT INTO `mc_status` VALUES ('8', '127.0.0.1', '9000', '9000', '2014-03-30 11:58:10');

-- ----------------------------
-- Table structure for `monitor_condition`
-- ----------------------------
DROP TABLE IF EXISTS `monitor_condition`;
CREATE TABLE `monitor_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `logic_operator` char(10) NOT NULL,
  `comparison_operator` char(10) NOT NULL,
  `left_expressoin` varchar(500) NOT NULL,
  `right_expression` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of monitor_condition
-- ----------------------------
INSERT INTO `monitor_condition` VALUES ('1', '1', 'and', '>=', '$connections-prevHour(connections,1)', '50');
INSERT INTO `monitor_condition` VALUES ('2', '1', 'and', 'in', '$ip', '{127.0.0.1,192.168.0.1}');
INSERT INTO `monitor_condition` VALUES ('3', '3', 'and', '>', '$size', '10');
INSERT INTO `monitor_condition` VALUES ('4', '4', 'and', '==', '$http_code', '200');

-- ----------------------------
-- Table structure for `monitor_rule`
-- ----------------------------
DROP TABLE IF EXISTS `monitor_rule`;
CREATE TABLE `monitor_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `monitor_name` varchar(100) NOT NULL,
  `select_sql` varchar(2000) NOT NULL,
  `filter_fields` varchar(500) NOT NULL,
  `filter_conditions` varchar(500) NOT NULL,
  `is_alert_everytime` tinyint(4) NOT NULL,
  `alert_in_cycles` int(11) NOT NULL,
  `alert_when_gt_times` int(11) NOT NULL,
  `alert_title` varchar(2000) NOT NULL,
  `alert_head` varchar(2000) NOT NULL,
  `alert_content` varchar(2000) NOT NULL,
  `alert_receiver` varchar(500) NOT NULL,
  `alert_deploy_id` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cycle` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of monitor_rule
-- ----------------------------
INSERT INTO `monitor_rule` VALUES ('1', '1', 'mc连接数', 'select * from TABLE QUERYGROUP BY ip,port', '', '', '1', '0', '0', '共有[count()]台MC连接数过高 [$ip]:[$port]', '<tr><td>IP</td><td>端口</td><td>连接数</td><td>上一分钟连接数</td><td>时间</td></tr>', '<tr><td>[join(ip,{ip_id,id},$ip_id,ip)]</td><td>[$port]</td><td>[$connections]</td><td>[prev(connections)]</td><td>[$add_time]</tr>', 'xuhao05', '1', '0', '1', '0');
INSERT INTO `monitor_rule` VALUES ('2', '2', '接口监控', '', '', '', '0', '3', '3', '接口报警', '', '<tr><td>{$interface}</td><td>{$http_code}</td><td>{$message}</td></tr>', 'xuhao05', '0', '0', '1', '0');
INSERT INTO `monitor_rule` VALUES ('3', '3', 'queue_monitor', '', '', 'ctime>=\'2014-03-09 23:00:00\' and ctime<=\'2014-03-09 23:30:00\' and status=0', '0', '0', '0', '共有[count()]个队列堆积过高', '<tr><td>queue</td><td>stack</td><td>状态</td><td>时间</td></tr>', '<tr><td>[$queue_name]</td><td>[$size]</td><td>[$status]</td><td>[$ctime]</tr>', '', '1', '0', '1', '0');
INSERT INTO `monitor_rule` VALUES ('4', '4', '接口监控', 'select * from TABLE where result=0 querygroup by api_id', '', '', '1', '0', '0', '共有[count()]个接口异常', '<tr><td>接口ID</td><td>IP</td><td>端口</td><td>执行时间</td><td>时间</td></tr>', '<tr><td>{$api_id}<td>{$api_isp_ip}</td><td>{$port}</td><td>{$total_time}</td><td>{$stime}</td></tr>', '', '1', '0', '0', '60');
