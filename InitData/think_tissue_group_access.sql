/*
Navicat MySQL Data Transfer

Source Server         : Test_192.168.1.201
Source Server Version : 50548
Source Host           : 192.168.1.201:3306
Source Database       : think

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-17 15:04:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_tissue_group_access
-- ----------------------------
DROP TABLE IF EXISTS `think_tissue_group_access`;
CREATE TABLE `think_tissue_group_access` (
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `tissue_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构关联字段',
  `job_id` int(11) NOT NULL DEFAULT '0' COMMENT '岗位ID',
  `manage_id` int(11) NOT NULL DEFAULT '0' COMMENT '组织架构权限,0-普通用户,1-管理员,2-负责人',
  `branch_id` tinyint(6) NOT NULL COMMENT '相同分公司标注',
  `serial_number` varchar(200) NOT NULL COMMENT '序列',
  KEY `tissue_id` (`tissue_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组织用户关联表';

-- ----------------------------
-- Records of think_tissue_group_access
-- ----------------------------

INSERT INTO `think_tissue_group_access` VALUES ('1', '1', '1', '2', '0', '');