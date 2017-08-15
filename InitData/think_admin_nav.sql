-- ----------------------------
-- Table structure for think_admin_nav
-- ----------------------------
DROP TABLE IF EXISTS `think_admin_nav`;
CREATE TABLE `think_admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单表',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '所属菜单',
  `name` varchar(15) DEFAULT '' COMMENT '菜单名称',
  `mca` varchar(255) DEFAULT '' COMMENT '模块、控制器、方法',
  `ico` varchar(20) DEFAULT '' COMMENT 'font-awesome图标',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_admin_nav
-- ----------------------------
INSERT INTO `think_admin_nav` VALUES ('1', '0', '培训班管理', 'admin/train/index', 'cog', '7');
INSERT INTO `think_admin_nav` VALUES ('39', '0', '学习任务', 'admin/task/index', 'mortar-board', '1');
INSERT INTO `think_admin_nav` VALUES ('40', '0', '个人中心', 'admin/center/index', 'send', '4');
INSERT INTO `think_admin_nav` VALUES ('41', '0', '组织架构', 'admin/tissue/index', 'cubes', '5');
INSERT INTO `think_admin_nav` VALUES ('42', '0', '审核中心', 'admin/audit/projectauditlist', 'cubes', '10');
INSERT INTO `think_admin_nav` VALUES ('43', '0', '资源管理', 'admin/resources/index', 'calendar-check-o', '6');
INSERT INTO `think_admin_nav` VALUES ('45', '39', '公开课程', 'admin/my_course/publiccourse', 'circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('46', '39', '我的课程', 'admin/my_course/index/type/1', 'circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('47', '41', '用户管理', 'admin/tissue/index', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('49', '41', '权限管理', 'admin/jurisdiction/index', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('51', '43', '供应商管理', 'admin/supplier/suppliermanage', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('52', '43', '讲师管理', 'admin/lecturer/pagelist', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('53', '43', '课程管理', 'admin/rs_course/index', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('55', '1', '培训班管理', 'admin/manage/index', 'circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('57', '1', '考勤管理', 'admin/attendance/attendanceManage', 'circle-o', '3');
INSERT INTO `think_admin_nav` VALUES ('64', '41', '岗位管理', 'admin/audit_users/jobsmanage', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('65', '0', '积分中心', 'admin/integration/index', 'fa fa-mortar-board', '3');
INSERT INTO `think_admin_nav` VALUES ('67', '39', '我的考试', 'admin/my_exam/allExam', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('75', '65', '积分管理', 'admin/Integration/integrationlist', 'circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('78', '1', '主持考试', 'admin/host/index', 'circle-o', '4');
INSERT INTO `think_admin_nav` VALUES ('81', '0', '首页', 'admin/Index/home', 'home', '1');
INSERT INTO `think_admin_nav` VALUES ('88', '39', '我的问卷', 'admin/my_survey/waitsurvey', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('89', '40', '学习档案', 'admin/center/study', 'fa fa-circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('90', '40', '内训师档案', 'admin/myGoal/goalPage', 'fa fa-circle-o', '3');
INSERT INTO `think_admin_nav` VALUES ('91', '40', '我的笔记', 'admin/myNote/notePage', 'fa fa-circle-o', '6');
INSERT INTO `think_admin_nav` VALUES ('92', '40', '工作圈', 'admin/FriendsCircle/friendsCircleList', 'fa fa-circle-o', '7');
INSERT INTO `think_admin_nav` VALUES ('93', '40', '找人PK', 'admin/center/pk', 'fa fa-circle-o', '10');
INSERT INTO `think_admin_nav` VALUES ('94', '40', '通讯录', 'admin/contacts/index', 'fa fa-circle-o', '11');
INSERT INTO `think_admin_nav` VALUES ('95', '40', '帮助中心', 'admin/help/index', 'fa fa-circle-o', '12');
INSERT INTO `think_admin_nav` VALUES ('96', '43', '试卷管理', 'admin/ResourcesManage/passexam', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('97', '43', '问卷管理', 'Admin/rs_survey/listPage', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('100', '1', '主持调研/评估', 'admin/survey/index', 'fa fa-circle-o', '5');
INSERT INTO `think_admin_nav` VALUES ('101', '1', '我的授课', 'admin/teach/index', 'fa fa-circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('102', '0', '工具管理', 'admin/tool/index', 'fa fa-circle-o', '9');
INSERT INTO `think_admin_nav` VALUES ('103', '102', '调研管理', 'admin/research/index', 'fa fa-circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('147', '40', '业务部落', 'admin/topic_group/index', 'fa fa-circle-o', '8');
INSERT INTO `think_admin_nav` VALUES ('105', '102', '考试管理', 'admin/TestManage/index', 'fa fa-circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('106', '0', '资讯管理', 'admin/news/page', 'fa fa-circle-o', '11');
INSERT INTO `think_admin_nav` VALUES ('107', '0', '内容管理', 'admin/content/index', 'fa fa-circle-o', '12');
INSERT INTO `think_admin_nav` VALUES ('108', '107', '工作圈管理', 'admin/FriendsCircle/manageList', 'fa fa-circle-o', '1');
INSERT INTO `think_admin_nav` VALUES ('109', '107', 'Banner管理', 'admin/banner/index', 'fa fa-circle-o', '3');
INSERT INTO `think_admin_nav` VALUES ('110', '107', '评论管理', 'admin/comment_manage/index/', 'fa fa-circle-o', '4');
INSERT INTO `think_admin_nav` VALUES ('111', '0', '数据管理', 'admin/data/index', 'fa fa-circle-o', '13');
INSERT INTO `think_admin_nav` VALUES ('113', '111', '员工学习统计', 'Admin/LearningStat/index', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('114', '111', '部门学时统计', 'Admin/ExamReport/hoursStat', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('115', '111', '费用统计分析', 'admin/data/cost', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('116', '111', '课程库报表', 'Admin/ExamReport/course', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('117', '111', '讲师库报表', 'Admin/ExamReport/lecturer', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('118', '111', '考试报表', 'admin/ExamReport/index', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('119', '111', '调研报表', 'Admin/ExamReport/survey', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('121', '65', '福利管理', 'admin/Integration/welfarelist', 'circle-o', '3');
INSERT INTO `think_admin_nav` VALUES ('145', '40', '申请加分', 'admin/CreditsApply/apply', 'fa fa-circle-o', '5');
INSERT INTO `think_admin_nav` VALUES ('123', '40', '学习目标', 'admin/center/goal', 'fa fa-circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('124', '42', '审核流程设置', 'admin/audit/setlist', 'circle-o', '12');
INSERT INTO `think_admin_nav` VALUES ('125', '42', '培训项目审核', 'admin/audit/projectauditlist', 'fa fa-circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('126', '42', '新建课程审核', 'admin/audit/courseauditlist', 'fa fa-circle-o', '3');
INSERT INTO `think_admin_nav` VALUES ('127', '42', '新建试卷审核', 'admin/audit/examinationauditlist', 'fa fa-circle-o', '4');
INSERT INTO `think_admin_nav` VALUES ('128', '42', '新建问卷审核', 'admin/audit/questionauditlist', 'fa fa-circle-o', '5');
INSERT INTO `think_admin_nav` VALUES ('129', '42', '新建互动审核', 'admin/audit/topicauditlist', 'fa fa-circle-o', '6');
INSERT INTO `think_admin_nav` VALUES ('130', '42', '发起调研审核', 'admin/audit/researchauditlist', 'fa fa-circle-o', '7');
INSERT INTO `think_admin_nav` VALUES ('131', '42', '发起考试审核', 'admin/audit/testauditlist', 'fa fa-circle-o', '8');
INSERT INTO `think_admin_nav` VALUES ('132', '42', '加分申请审核', 'admin/audit/creditsapplyauditlist', 'fa fa-circle-o', '9');
INSERT INTO `think_admin_nav` VALUES ('133', '42', '用户注册审核', 'admin/audit/usersauditlist', 'fa fa-circle-o', '10');
INSERT INTO `think_admin_nav` VALUES ('134', '40', '项目互动', 'admin/ItemInteraction/index', 'fa fa-circle-o', '9');
INSERT INTO `think_admin_nav` VALUES ('135', '107', '项目互动管理', 'admin/ItemInteraction/manageindex', 'fa fa-circle-o', '2');
INSERT INTO `think_admin_nav` VALUES ('146', '111', '目标管理', 'admin/tool/target', 'fa fa-circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('136', '42', '创建部落审核', 'Admin/audit/topicgrouplist', 'fa fa-circle-o', '11');
INSERT INTO `think_admin_nav` VALUES ('139', '0', '技能竞赛', 'admin/competition/index', 'fa fa-circle-o', '8');
INSERT INTO `think_admin_nav` VALUES ('140', '139', '竞赛入口', 'admin/competition/member', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('141', '139', '竞赛管理', 'admin/competition/admin', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('142', '139', '成绩查看', 'admin/competition/scorecheck', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('143', '139', '成绩管理', 'admin/competition/scoreadmin', 'circle-o', null);
INSERT INTO `think_admin_nav` VALUES ('144', '40', '我的积分', 'admin/Integration/myintegration', 'fa fa-circle-o', '4');
