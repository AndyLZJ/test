/*
Navicat MySQL Data Transfer

Source Server         : Test_192.168.1.201
Source Server Version : 50548
Source Host           : 192.168.1.201:3306
Source Database       : think

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-17 15:03:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_admin_message_type
-- ----------------------------
DROP TABLE IF EXISTS `think_admin_message_type`;
CREATE TABLE `think_admin_message_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_name` varchar(100) DEFAULT NULL COMMENT '消息类型名称',
  `cat_detail` varchar(100) DEFAULT NULL COMMENT '消息类型描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='消息类型表';

-- ----------------------------
-- Records of think_admin_message_type
-- ----------------------------
INSERT INTO `think_admin_message_type` VALUES ('1', '课程制作', '你有课程制作的任务，任务信息如下');
INSERT INTO `think_admin_message_type` VALUES ('2', '试卷制作', '你有试卷制作的任务，任务信息如下');
INSERT INTO `think_admin_message_type` VALUES ('3', '问卷制作', '你有问卷制作的任务，任务信息如下');
INSERT INTO `think_admin_message_type` VALUES ('4', '授课任务', '你有面授课程即将开始开讲，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('5', '成绩发布', '你有考试成绩即将发布，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('6', '调研结果', '你有调研结果待查看，点击前往');
INSERT INTO `think_admin_message_type` VALUES ('7', '审批任务', '你有新的审批任务，点击前往');
INSERT INTO `think_admin_message_type` VALUES ('8', '统计调研', '你有统计调研的任务，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('9', '签到提醒', '你有签到提醒的任务，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('10', '课程学习', '你有待学习课程即将开始，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('11', '参加考试', '你有待参加考试即将开始，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('12', '参与调研', '你有待参与调研即将开始，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('13', '计划总结', '你有已完成项目待写总结，信息如下');
INSERT INTO `think_admin_message_type` VALUES ('14', '互动消息', '你的互动有新的评论/赞，请查看');
INSERT INTO `think_admin_message_type` VALUES ('15', '问答消息', '你的问答有新消息，请查看：');
INSERT INTO `think_admin_message_type` VALUES ('16', '业务部落', '你的业务部落有新消息，请查看：');
