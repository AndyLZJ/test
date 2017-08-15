/*
Navicat MySQL Data Transfer

Source Server         : APP_120.77.57.26
Source Server Version : 50548
Source Host           : 120.77.57.26:3306
Source Database       : think_yanshi1

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-18 08:38:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_tissue_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_tissue_rule`;
CREATE TABLE `think_tissue_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单表',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '所属菜单',
  `name` varchar(15) DEFAULT '' COMMENT '菜单名称',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  `rules` text COMMENT '规则ID(当前组织下无限级关联的组织id)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_tissue_rule
-- ----------------------------
INSERT INTO `think_tissue_rule` VALUES ('1', '0', '深圳典阅模拟银行', null, '0');

