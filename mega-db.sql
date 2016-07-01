-- MySQL dump 10.13  Distrib 5.6.28, for Linux (i686)
--
-- Host: localhost    Database: mega
-- ------------------------------------------------------
-- Server version	5.6.28

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
-- Table structure for table `mega_task`
--

DROP TABLE IF EXISTS `mega_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mega_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmpl_key` char(32) NOT NULL COMMENT '模板key',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '进程id',
  `task_name` varchar(100) NOT NULL COMMENT '任务名称',
  `send_to_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发送目标类型（0：全部人，1：根据用户ID）',
  `file` varchar(255) NOT NULL COMMENT '文件路径',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '总数',
  `success` int(11) NOT NULL DEFAULT '0' COMMENT '成功数',
  `fail` int(11) NOT NULL DEFAULT '0' COMMENT '失败数',
  `file_index` int(11) NOT NULL DEFAULT '0' COMMENT '文件索引',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态（0：待处理，1：处理中，2：任务完成，3：任务失败，4：中断，5：暂停，6：终止）',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `information` varchar(255) NOT NULL DEFAULT '' COMMENT '信息',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=779 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mega_user`
--

DROP TABLE IF EXISTS `mega_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mega_user` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(16) DEFAULT NULL,
  `password` char(32) NOT NULL DEFAULT '',
  `nickname` char(16) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `openid` char(40) NOT NULL DEFAULT '',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态（-1-删除，0-禁止登陆，1-正常）',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=688 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mega_wechat_template`
--

DROP TABLE IF EXISTS `mega_wechat_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mega_wechat_template` (
  `tmpl_key` char(32) NOT NULL COMMENT '模板key',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '模板标题',
  `template` text NOT NULL COMMENT '模板内容',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tmpl_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-01 15:05:02
