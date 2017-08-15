/*
Navicat MySQL Data Transfer

Source Server         : Test_192.168.1.201
Source Server Version : 50548
Source Host           : 192.168.1.201:3306
Source Database       : think

Target Server Type    : MYSQL
Target Server Version : 50548
File Encoding         : 65001

Date: 2017-07-17 15:04:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_integration_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_integration_rule`;
CREATE TABLE `think_integration_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '积分规则名称',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '分值',
  `oneday_score` varchar(25) DEFAULT NULL COMMENT '每天封顶积分(30天/月)',
  `type` varchar(25) DEFAULT NULL COMMENT '积分类型',
  `sort` tinyint(3) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='积分规则表';

-- ----------------------------
-- Records of think_integration_rule
-- ----------------------------
INSERT INTO `think_integration_rule` VALUES ('1', '登陆系统', '3', '20', '系统达人', null);
INSERT INTO `think_integration_rule` VALUES ('2', '查看系统消息', '5', '13', '系统达人', null);
INSERT INTO `think_integration_rule` VALUES ('3', '头像/邮箱/个人简介设置成功', '2', '20/30', '系统达人', null);
INSERT INTO `think_integration_rule` VALUES ('4', '查看积分规则', '10', '30/30', '系统达人', null);
INSERT INTO `think_integration_rule` VALUES ('5', '查看学习目标', '6', '50', '系统达人', null);
INSERT INTO `think_integration_rule` VALUES ('6', '阅读学习资料', '10', '', '爱学习', null);
INSERT INTO `think_integration_rule` VALUES ('7', '关注课程', '2', '0', '爱学习', null);
INSERT INTO `think_integration_rule` VALUES ('8', '对课程/讲师评价', '5', '20', '爱学习', null);
INSERT INTO `think_integration_rule` VALUES ('9', '完成调研', '10', '', '爱学习', null);
INSERT INTO `think_integration_rule` VALUES ('10', '观看课程中做笔记', '2', '1', '爱学习', null);
INSERT INTO `think_integration_rule` VALUES ('11', '发布／上传学习资料', '2', '20', '乐分享', null);
INSERT INTO `think_integration_rule` VALUES ('12', '发布的学习资料被关注', '2', '', '乐分享', null);
INSERT INTO `think_integration_rule` VALUES ('13', '对他人学课的问题回复／评论', '2', '20', '乐分享', null);
INSERT INTO `think_integration_rule` VALUES ('14', '发布工作圈状态', '2', '20', '乐分享', null);
INSERT INTO `think_integration_rule` VALUES ('15', '回复/点赞工作圈', '2', '20', '乐分享', null);
INSERT INTO `think_integration_rule` VALUES ('16', '正常考勤', '2', '', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('17', '迟到/早退', '-10', '', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('18', '旷课', '-50', '', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('19', '选修一门课程(完成学习)', '5', '', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('20', '学习任务到期未完成', '-30', '', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('21', '按期完成学习任务', '20', '40', '任务范儿', null);
INSERT INTO `think_integration_rule` VALUES ('22', '考试成绩第一名', '30', '', '我是学霸', null);
INSERT INTO `think_integration_rule` VALUES ('23', '部门学分排名第一', '20', '', '我是学霸', null);
INSERT INTO `think_integration_rule` VALUES ('24', '公司学分排名第一', '30', '', '我是学霸', null);
INSERT INTO `think_integration_rule` VALUES ('25', '发布课程', '10', '', '好为人师', null);
INSERT INTO `think_integration_rule` VALUES ('26', '完成授课', '20', '', '好为人师', null);
