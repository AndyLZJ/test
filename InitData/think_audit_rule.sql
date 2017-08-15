/*
Navicat MySQL Data Transfer

Source Server         : APP_120.77.57.26
Source Server Version : 50548
Source Host           : 120.77.57.26:3306
Source Database       : think_yanshi2

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-19 09:33:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_audit_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_audit_rule`;
CREATE TABLE `think_audit_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册 10.小组审核',
  `oneaudit_role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一审角色id,关联角色表id',
  `twoaudit_role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '二审角色id',
  `threeaudit_role` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '三审角色id',
  `is_condition` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：为1启用条件，为0禁用',
  `condition_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联审核条件表id',
  `num` tinyint(3) DEFAULT '1' COMMENT '审核级数',
  `one_level_type` tinyint(3) DEFAULT '0' COMMENT '一审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)',
  `two_level_type` tinyint(3) DEFAULT '0' COMMENT '二审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)',
  `three_level_type` tinyint(3) DEFAULT '0' COMMENT '三审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)',
  `oneaudit_user_id` int(11) DEFAULT '0' COMMENT '一审指定用户id,关联用户表id',
  `twoaudit_user_id` int(11) DEFAULT '0' COMMENT '二审指定用户id,关联用户表id',
  `threeaudit_user_id` int(11) DEFAULT '0' COMMENT '三审指定用户id,关联用户表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='审核规则表';

-- ----------------------------
-- Records of think_audit_rule
-- ---------------------------- xx 1 0 0 0

INSERT INTO `think_audit_rule` VALUES ('1', '1', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('2', '2', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('3', '3', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('4', '4', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('5', '5', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('6', '6', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('7', '7', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('8', '8', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('9', '9', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `think_audit_rule` VALUES ('10', '10', '1', '0', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0');
