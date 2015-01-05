/*
Navicat MySQL Data Transfer

Source Server         : 192.168.137.2(localhost)
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : pronoz

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-01-05 14:34:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pn_metas
-- ----------------------------
DROP TABLE IF EXISTS `pn_metas`;
CREATE TABLE `pn_metas` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '项目主键',
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `slug` varchar(200) DEFAULT NULL COMMENT '项目缩略名',
  `type` varchar(32) NOT NULL COMMENT '项目类型',
  `description` varchar(200) DEFAULT NULL COMMENT '选项描述',
  `count` int(10) unsigned DEFAULT '0' COMMENT '项目所属内容个数',
  `order` int(10) unsigned DEFAULT '0' COMMENT '项目排序',
  PRIMARY KEY (`mid`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pn_posts
-- ----------------------------
DROP TABLE IF EXISTS `pn_posts`;
CREATE TABLE `pn_posts` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `slug` varchar(200) DEFAULT NULL COMMENT '缩略名',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章创建时间',
  `modified` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `status` varchar(16) NOT NULL DEFAULT 'public',
  PRIMARY KEY (`pid`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pn_relationships
-- ----------------------------
DROP TABLE IF EXISTS `pn_relationships`;
CREATE TABLE `pn_relationships` (
  `pid` int(10) unsigned NOT NULL COMMENT '内容主键',
  `mid` int(10) unsigned NOT NULL COMMENT '项目主键'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pn_sessions
-- ----------------------------
DROP TABLE IF EXISTS `pn_sessions`;
CREATE TABLE `pn_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pn_users
-- ----------------------------
DROP TABLE IF EXISTS `pn_users`;
CREATE TABLE `pn_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户PK',
  `username` varchar(32) NOT NULL COMMENT '用户名称',
  `password` varchar(49) DEFAULT NULL COMMENT '用户密码',
  `mail` varchar(200) NOT NULL COMMENT '用户邮箱',
  `url` varchar(200) DEFAULT NULL COMMENT '用户主页',
  `screenName` varchar(32) DEFAULT NULL COMMENT '用户的显示名称',
  `created` int(10) unsigned NOT NULL COMMENT '用户的注册时间',
  `activated` int(10) unsigned NOT NULL COMMENT '最后活跃时间',
  `logged` int(10) unsigned NOT NULL COMMENT '上次登陆最后活跃时间',
  `group` varchar(16) NOT NULL COMMENT '用户所在组',
  `token` varchar(40) DEFAULT NULL COMMENT '令牌',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `name` (`username`,`mail`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

