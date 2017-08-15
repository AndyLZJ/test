/*
Navicat MySQL Data Transfer

Source Server         : APP_120.77.57.26
Source Server Version : 50548
Source Host           : 120.77.57.26:3306
Source Database       : think_yanshi1

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-17 16:44:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_audit_condition
-- ----------------------------
DROP TABLE IF EXISTS `think_audit_condition`;
CREATE TABLE `think_audit_condition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '限制的条件名称',
  `conditiona` double unsigned NOT NULL COMMENT '条件值1',
  `conditionb` double unsigned NOT NULL COMMENT '条件值2',
  `type` tinyint(3) unsigned DEFAULT NULL COMMENT '1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='审核条件表';

-- ----------------------------
-- Records of think_audit_condition
-- ----------------------------
INSERT INTO `think_audit_condition` VALUES ('1', '项目时长（天）', '20', '30', '1');
INSERT INTO `think_audit_condition` VALUES ('2', '项目预算（元）', '5000', '9000', '1');
INSERT INTO `think_audit_condition` VALUES ('3', '指定人员（人数）', '5', '8', '1');
INSERT INTO `think_audit_condition` VALUES ('4', '授课时长（分钟）', '50', '55', '2');
INSERT INTO `think_audit_condition` VALUES ('5', '新建课程学分（分）', '20', '25', '2');
INSERT INTO `think_audit_condition` VALUES ('6', '持续时长（天）', '0', '0', '6');
INSERT INTO `think_audit_condition` VALUES ('7', '学分（分）', '10', '20', '6');
INSERT INTO `think_audit_condition` VALUES ('8', '考试时长（分钟）', '50', '55', '7');
INSERT INTO `think_audit_condition` VALUES ('9', '学分（分）', '0', '0', '7');
INSERT INTO `think_audit_condition` VALUES ('10', '指定人员（人数）', '0', '0', '7');
INSERT INTO `think_audit_condition` VALUES ('11', '积分（分）', '20', '40', '8');
