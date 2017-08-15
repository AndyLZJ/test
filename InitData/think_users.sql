/*
Navicat MySQL Data Transfer

Source Server         : Test_192.168.1.201
Source Server Version : 50548
Source Host           : 192.168.1.201:3306
Source Database       : think

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-17 15:04:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_users
-- ----------------------------
DROP TABLE IF EXISTS `think_users`;
CREATE TABLE `think_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；mb_password加密',
  `avatar` varchar(255) NOT NULL DEFAULT '/Upload/avatar/20170303/58b8e79006e75.jpg' COMMENT '用户头像，相对于upload/avatar目录',
  `email` varchar(100) DEFAULT '' COMMENT '登录邮箱',
  `email_code` varchar(60) NOT NULL COMMENT '激活码',
  `phone` bigint(11) unsigned DEFAULT NULL COMMENT '手机号',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户状态 0：拒绝； 1：审核通过 ；2：待审核 ; 3 逻辑删除',
  `register_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '注册时间',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `job_number` varchar(18) DEFAULT '' COMMENT '工号',
  `province` varchar(30) NOT NULL DEFAULT '' COMMENT '所在省份',
  `city` varchar(30) NOT NULL DEFAULT '' COMMENT '所在城市',
  `personalized_signature` varchar(50) NOT NULL COMMENT '个性签名 ',
  `qrcode` varchar(50) NOT NULL DEFAULT '' COMMENT '扫码登录code',
  `token` varchar(50) NOT NULL COMMENT '用户标识token',
  `token_expires` int(10) NOT NULL COMMENT 'token有效时间',
  `is_login` tinyint(1) NOT NULL COMMENT '0未登录  1登录',
  `audit_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `orderno` varchar(20) DEFAULT NULL COMMENT '工单号',
  `objection` varchar(1000) DEFAULT NULL COMMENT '拒绝理由',
  `sequence` varchar(255) DEFAULT NULL COMMENT '序列',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:无 1:男 2:女',
  `age` tinyint(4) NOT NULL DEFAULT '0' COMMENT '年龄',
  `group_time` date DEFAULT NULL COMMENT '入集团时间',
  `center_time` date DEFAULT NULL COMMENT '入中心时间',
  `area` varchar(255) DEFAULT NULL COMMENT '区域',
  `room` varchar(255) DEFAULT NULL COMMENT '科室',
  `rank` varchar(255) DEFAULT NULL COMMENT '职级',
  `education` tinyint(4) DEFAULT NULL COMMENT '学历: 1:博士研究生 2:硕士研究生 3:本科 4:专科 5:专科以下',
  `mobilephone` bigint(20) unsigned DEFAULT NULL COMMENT '手机号码,非登录账号',
  `tel` varchar(255) DEFAULT NULL COMMENT '办公电话',
  `ip_phone` varchar(255) DEFAULT NULL COMMENT 'IP电话',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=764 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of think_users
-- ----------------------------
INSERT INTO `think_users` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '/Upload/avatar/20170714596877da9b2c3.png', 'awangbc@ac.cntaiping.com', '', '13333333333', '1', '0000-00-00 00:00:00', '58.63.0.56', '2017-05-12 10:01:57', '88888', '', '', 'dffgghwesdfgttttt', 'SaUHlJQ4sJkBMwhMeq7aoEUZBggqkjiV1491970049', '378d6f1190b3b8eb931e97da1aea50d8', '1500435865', '1', '0', null, null, '', '1993-04-12', '1', '24', '2017-05-23', '0000-00-00', '深圳/南山', '', '', '1', '13760475946', '0775-5555555', 'dd-844421111');
