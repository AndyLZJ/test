-- ----------------------------
-- Table structure for think_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of think_auth_rule
-- ----------------------------
INSERT INTO `think_auth_rule` VALUES ('1', '20', 'admin/my_course/publiccourse', '公开课程', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('6', '0', 'admin/index/index', '后台首页', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('7', '21', 'admin/rule/index', '权限管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('8', '7', 'admin/rule/add', '添加权限', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('9', '7', 'admin/rule/edit', '修改权限', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('10', '7', 'admin/rule/delete', '删除权限', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('11', '21', 'admin/rule/group', '用户组管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('12', '11', 'admin/rule/add_group', '添加用户组', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('13', '11', 'admin/rule/edit_group', '修改用户组', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('14', '11', 'admin/rule/delete_group', '删除用户组', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('15', '11', 'admin/rule/rule_group', '分配权限', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('16', '11', 'admin/rule/check_user', '添加成员', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('19', '21', 'admin/rule/admin_user_list', '管理员列表', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('20', '0', 'admin/task/index', '学习任务', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('21', '0', 'admin/shownav/rule', '权限控制', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('96', '6', 'admin/index/home', '首页', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('104', '0', 'admin/integration/index', '积分中心', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('236', '127', 'admin/Integration/myintegration', '我的积分', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('123', '11', 'admin/rule/add_user_to_group', '设置为管理员', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('124', '11', 'admin/rule/add_admin', '添加管理员', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('125', '11', 'admin/rule/edit_admin', '修改管理员', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('127', '0', 'admin/center/index', '个人中心', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('128', '0', 'admin/tissue/home', '组织架构', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('129', '0', 'admin/resources/index', '资源管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('131', '0', 'admin/train/index', '培训管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('133', '127', 'admin/center/study', '学习档案', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('137', '129', 'admin/supplier/suppliermanage', '供应商管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('138', '129', 'admin/lecturer/pagelist', '讲师管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('139', '129', 'admin/rs_course/index', '课程管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('239', '127', 'admin/topic_group/index', '业务部落', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('164', '6', 'admin/index_admin/index', '培训主管', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('165', '6', 'admin/index/indexlecturer', '讲师面板', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('166', '6', 'admin/index/indexstudent', '学员面板', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('169', '127', 'admin/myGoal/goalPage', '授课目标', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('170', '127', 'admin/myNote/notePage', '我的笔记', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('171', '127', 'admin/FriendsCircle/friendsCircleList', '工作圈', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('172', '131', 'admin/manage/index', '培训项目管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('173', '131', 'admin/teach/index', '我的授课', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('174', '131', 'admin/attendance/attendanceManage', '考勤管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('175', '131', 'admin/host/index', '主持考试', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('176', '20', 'admin/my_course/index/type/1', '我的课程', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('177', '0', 'admin/tool/index', '工具管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('178', '177', 'admin/research/index', '调研管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('179', '177', 'admin/TestManage/index', '考试管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('238', '203', 'admin/tool/target', '目标管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('181', '0', 'admin/audit/auditlist', '审核中心', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('186', '20', 'admin/my_exam/allExam', '我的考试', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('187', '20', 'admin/my_survey/waitsurvey', '我的问卷', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('188', '104', 'admin/Integration/integrationlist', '积分管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('189', '127', 'admin/center/pk', '找人PK', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('190', '127', 'admin/contacts/index', '通讯录', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('191', '127', 'admin/help/index', '帮助中心', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('192', '128', 'admin/tissue/index', '用户管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('193', '128', 'admin/jurisdiction/index', '权限管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('194', '128', 'admin/audit_users/jobsmanage', '岗位管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('195', '129', 'admin/ResourcesManage/passexam', '试卷管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('196', '129', 'admin/rs_survey/listPage', '问卷管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('197', '131', 'admin/survey/index', '主持调研/评估', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('198', '0', 'admin/news/page', '资讯管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('199', '0', 'admin/content/index', '内容管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('200', '199', 'admin/FriendsCircle/manageList', '工作圈管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('201', '199', 'admin/Banner/index', 'Banner管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('202', '199', 'admin/comment_manage/index/', '评论管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('203', '0', 'admin/data/index', '数据管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('204', '203', 'admin/data/study', '学习档案', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('205', '203', 'Admin/LearningStat/index', '员工学习统计', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('206', '203', 'Admin/ExamReport/hoursStat', '部门学时统计', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('207', '203', 'admin/data/cost', '费用统计分析', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('208', '203', 'Admin/ExamReport/course', '课程库报表', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('209', '203', 'Admin/ExamReport/lecturer', '讲师库报表', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('210', '203', 'admin/ExamReport/index', '考试报表', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('212', '0', 'admin/test/index', '测试导航', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('213', '104', 'admin/Integration/welfarelist', '福利管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('214', '203', 'Admin/ExamReport/survey', '调研报表', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('215', '127', 'admin/center/goal', '学习目标', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('216', '181', 'admin/audit/setlist', '审核流程设置', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('218', '181', 'admin/audit/projectauditlist', '培训项目审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('219', '181', 'admin/audit/courseauditlist', '新建课程审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('220', '181', 'admin/audit/examinationauditlist', '新建试卷审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('221', '181', 'admin/audit/questionauditlist', '新建问卷审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('222', '181', 'admin/audit/topicauditlist', '新建互动审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('223', '181', 'admin/audit/researchauditlist', '发起调研审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('224', '181', 'admin/audit/testauditlist', '发起考试审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('225', '181', 'admin/audit/creditsapplyauditlist', '加分申请审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('226', '181', 'admin/audit/usersauditlist', '用户注册审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('227', '127', 'admin/ItemInteraction/index', '项目互动', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('228', '199', 'admin/ItemInteraction/manageindex', '项目互动管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('229', '181', 'Admin/audit/topicgrouplist', '创建部落审核', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('231', '0', 'admin/competition/index', '技能竞赛', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('232', '231', 'admin/competition/member', '竞赛入口', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('233', '231', 'admin/competition/admin', '竞赛管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('234', '231', 'admin/competition/scorecheck', '成绩查看', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('235', '231', 'admin/competition/scoreadmin', '成绩管理', '1', '1', '');
INSERT INTO `think_auth_rule` VALUES ('237', '127', 'admin/CreditsApply/apply', '申请加分', '1', '1', '');
