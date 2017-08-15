/*
Navicat Oracle Data Transfer
Oracle Client Version : 11.2.0.3.0

Source Server         : localhost_oracle_scott
Source Server Version : 110200
Source Host           : localhost:1521
Source Schema         : SCOTT

Target Server Type    : ORACLE
Target Server Version : 110200
File Encoding         : 65001

Date: 2017-06-23 11:26:37
*/


-- ----------------------------
-- Table structure for BONUS
-- ----------------------------
DROP TABLE "SCOTT"."BONUS";
CREATE TABLE "SCOTT"."BONUS" (
"ENAME" VARCHAR2(10 BYTE) NULL ,
"JOB" VARCHAR2(9 BYTE) NULL ,
"SAL" NUMBER NULL ,
"COMM" NUMBER NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of BONUS
-- ----------------------------

-- ----------------------------
-- Table structure for DEPT
-- ----------------------------
DROP TABLE "SCOTT"."DEPT";
CREATE TABLE "SCOTT"."DEPT" (
"DEPTNO" NUMBER(2) NOT NULL ,
"DNAME" VARCHAR2(14 BYTE) NULL ,
"LOC" VARCHAR2(13 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of DEPT
-- ----------------------------
INSERT INTO "SCOTT"."DEPT" VALUES ('10', 'ACCOUNTING', 'NEW YORK');
INSERT INTO "SCOTT"."DEPT" VALUES ('20', 'RESEARCH', 'DALLAS');
INSERT INTO "SCOTT"."DEPT" VALUES ('30', 'SALES', 'CHICAGO');
INSERT INTO "SCOTT"."DEPT" VALUES ('40', 'OPERATIONS', 'BOSTON');

-- ----------------------------
-- Table structure for EMP
-- ----------------------------
DROP TABLE "SCOTT"."EMP";
CREATE TABLE "SCOTT"."EMP" (
"EMPNO" NUMBER(4) NOT NULL ,
"ENAME" VARCHAR2(10 BYTE) NULL ,
"JOB" VARCHAR2(9 BYTE) NULL ,
"MGR" NUMBER(4) NULL ,
"HIREDATE" DATE NULL ,
"SAL" NUMBER(7,2) NULL ,
"COMM" NUMBER(7,2) NULL ,
"DEPTNO" NUMBER(2) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of EMP
-- ----------------------------
INSERT INTO "SCOTT"."EMP" VALUES ('7369', 'SMITH', 'CLERK', '7902', TO_DATE('1980-12-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '800', null, '20');
INSERT INTO "SCOTT"."EMP" VALUES ('7499', 'ALLEN', 'SALESMAN', '7698', TO_DATE('1981-02-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1600', '300', '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7521', 'WARD', 'SALESMAN', '7698', TO_DATE('1981-02-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1250', '500', '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7566', 'JONES', 'MANAGER', '7839', TO_DATE('1981-04-02 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '2975', null, '20');
INSERT INTO "SCOTT"."EMP" VALUES ('7654', 'MARTIN', 'SALESMAN', '7698', TO_DATE('1981-09-28 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1250', '1400', '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7698', 'BLAKE', 'MANAGER', '7839', TO_DATE('1981-05-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '2850', null, '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7782', 'CLARK', 'MANAGER', '7839', TO_DATE('1981-06-09 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '2450', null, '10');
INSERT INTO "SCOTT"."EMP" VALUES ('7788', '"SCOTT"', 'ANALYST', '7566', TO_DATE('1987-04-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '3000', null, '20');
INSERT INTO "SCOTT"."EMP" VALUES ('7839', 'KING', 'PRESIDENT', null, TO_DATE('1981-11-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '5000', null, '10');
INSERT INTO "SCOTT"."EMP" VALUES ('7844', 'TURNER', 'SALESMAN', '7698', TO_DATE('1981-09-08 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1500', '0', '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7876', 'ADAMS', 'CLERK', '7788', TO_DATE('1987-05-23 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1100', null, '20');
INSERT INTO "SCOTT"."EMP" VALUES ('7900', 'JAMES', 'CLERK', '7698', TO_DATE('1981-12-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '950', null, '30');
INSERT INTO "SCOTT"."EMP" VALUES ('7902', 'FORD', 'ANALYST', '7566', TO_DATE('1981-12-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '3000', null, '20');
INSERT INTO "SCOTT"."EMP" VALUES ('7934', 'MILLER', 'CLERK', '7782', TO_DATE('1982-01-23 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1300', null, '10');

-- ----------------------------
-- Table structure for SALGRADE
-- ----------------------------
DROP TABLE "SCOTT"."SALGRADE";
CREATE TABLE "SCOTT"."SALGRADE" (
"GRADE" NUMBER NULL ,
"LOSAL" NUMBER NULL ,
"HISAL" NUMBER NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of SALGRADE
-- ----------------------------
INSERT INTO "SCOTT"."SALGRADE" VALUES ('1', '700', '1200');
INSERT INTO "SCOTT"."SALGRADE" VALUES ('2', '1201', '1400');
INSERT INTO "SCOTT"."SALGRADE" VALUES ('3', '1401', '2000');
INSERT INTO "SCOTT"."SALGRADE" VALUES ('4', '2001', '3000');
INSERT INTO "SCOTT"."SALGRADE" VALUES ('5', '3001', '9999');

-- ----------------------------
-- Table structure for THINK_ADMIN_COMPANY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ADMIN_COMPANY";
CREATE TABLE "SCOTT"."THINK_ADMIN_COMPANY" (
"ID" NUMBER(10) NOT NULL ,
"COMPANY_NAME" VARCHAR2(255 BYTE) NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_COMPANY"."COMPANY_NAME" IS '公司名称';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_COMPANY"."STATUS" IS '0表示禁用，1表示启用';

-- ----------------------------
-- Records of THINK_ADMIN_COMPANY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_ADMIN_MESSAGE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ADMIN_MESSAGE";
CREATE TABLE "SCOTT"."THINK_ADMIN_MESSAGE" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NULL ,
"TITLE" VARCHAR2(255 BYTE) NULL ,
"CONTENTS_TIME" DATE NULL ,
"TYPE_ID" NUMBER(10) DEFAULT 0  NOT NULL ,
"NEWSTIME" DATE NOT NULL ,
"FROM_ID" NUMBER(10) NOT NULL ,
"STATUS" VARCHAR2(255 BYTE) NOT NULL ,
"IS_DELETE" VARCHAR2(255 BYTE) DEFAULT 0  NOT NULL ,
"URL" VARCHAR2(255 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_ADMIN_MESSAGE" IS '消息表';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."USER_ID" IS '收信人';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."TITLE" IS '消息标题';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."CONTENTS_TIME" IS '消息内容时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."TYPE_ID" IS '消息类型';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."NEWSTIME" IS '发布时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."FROM_ID" IS '消息来源UID';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."STATUS" IS '消息读取状态0表示未读，1表示已读';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."IS_DELETE" IS '0表示未删除，1表示已删除';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE"."URL" IS '任务跳转的url';

-- ----------------------------
-- Records of THINK_ADMIN_MESSAGE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_ADMIN_MESSAGE_TYPE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ADMIN_MESSAGE_TYPE";
CREATE TABLE "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" (
"ID" NUMBER NOT NULL ,
"TYPE_NAME" VARCHAR2(100 BYTE) NULL ,
"CAT_DETAIL" VARCHAR2(100 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" IS '消息类型表';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE_TYPE"."TYPE_NAME" IS '消息类型名称';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_MESSAGE_TYPE"."CAT_DETAIL" IS '消息类型描述';

-- ----------------------------
-- Records of THINK_ADMIN_MESSAGE_TYPE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('1', '课程制作', '你有课程制作的任务，任务信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('2', '试卷制作', '你有试卷制作的任务，任务信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('3', '问卷制作', '你有问卷制作的任务，任务信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('4', '授课任务', '你有面授课程即将开始开讲，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('5', '成绩发布', '你有考试成绩即将发布，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('6', '调研结果', '你有调研结果待查看，点击前往');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('7', '审批任务', '你有新的审批任务，点击前往');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('8', '统计调研', '你有统计调研的任务，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('9', '签到提醒', '你有签到提醒的任务，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('10', '课程学习', '你有待学习课程即将开始，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('11', '参加考试', '你有待参加考试即将开始，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('12', '参与调研', '你有待参与调研即将开始，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('13', '计划总结', '你有已完成项目待写总结，信息如下');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('14', '互动消息', '你的互动有新的评论/赞，请查看');
INSERT INTO "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" VALUES ('15', '问答消息', '你的问答有新消息，请查看：');

-- ----------------------------
-- Table structure for THINK_ADMIN_NAV
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ADMIN_NAV";
CREATE TABLE "SCOTT"."THINK_ADMIN_NAV" (
"ID" NUMBER NOT NULL ,
"PID" NUMBER DEFAULT 0  NULL ,
"NAME" VARCHAR2(255 BYTE) NULL ,
"MCA" VARCHAR2(255 BYTE) NULL ,
"ICO" VARCHAR2(20 BYTE) NULL ,
"ORDER_NUMBER" NUMBER NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_ADMIN_NAV" IS '菜单表';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_NAV"."PID" IS '所属菜单';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_NAV"."NAME" IS '菜单名称';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_NAV"."MCA" IS '模块、控制器、方法';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_NAV"."ICO" IS 'font-awesome图标';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_NAV"."ORDER_NUMBER" IS '排序';

-- ----------------------------
-- Records of THINK_ADMIN_NAV
-- ----------------------------
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('40', '0', '个人中心', 'admin/center/index', 'send', '4');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('41', '0', '组织架构', 'admin/tissue/index', 'cubes', '5');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('42', '0', '审核中心', 'admin/audit/projectauditlist', 'cubes', '9');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('43', '0', '资源管理', 'admin/resources/index', 'calendar-check-o', '6');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('45', '39', '公开课程', 'admin/my_course/publiccourse', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('46', '39', '我的课程', 'admin/my_course/index/type/1', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('47', '41', '用户管理', 'admin/tissue/index', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('49', '41', '权限管理', 'admin/jurisdiction/index', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('51', '43', '供应商管理', 'admin/supplier/suppliermanage', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('52', '43', '讲师管理', 'admin/lecturer/pagelist', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('53', '43', '课程管理', 'admin/rs_course/index', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('54', '43', '试题库管理', 'admin/ResourcesManage/examination', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('55', '1', '培训项目管理', 'admin/manage/index', 'circle-o', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('57', '1', '考勤管理', 'admin/attendance/attendanceManage', 'circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('1', '0', '培训管理', 'admin/train/index', 'cog', '7');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('39', '0', '学习任务', 'admin/task/index', 'mortar-board', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('64', '41', '岗位管理', 'admin/audit_users/jobsmanage', 'circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('65', '0', '积分中心', 'admin/integration/index', 'fa fa-mortar-board', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('67', '39', '我的考试', 'admin/my_exam/waitexam', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('75', '65', '积分管理', 'admin/Integration/integrationlist', 'circle-o', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('78', '1', '主持考试', 'admin/host/index', 'circle-o', '4');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('81', '0', '首页', 'admin/Index/home', 'home', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('88', '39', '我的调研/评估', 'admin/my_survey/waitsurvey', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('89', '40', '学习档案', 'admin/center/study', 'fa fa-circle-o', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('90', '40', '授课目标', 'admin/myGoal/goalPage', 'fa fa-circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('91', '40', '我的笔记', 'admin/myNote/notePage', 'fa fa-circle-o', '4');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('92', '40', '我的互动', 'admin/FriendsCircle/friendsCircleList', 'fa fa-circle-o', '5');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('93', '40', '找人PK', 'admin/center/pk', 'fa fa-circle-o', '7');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('94', '40', '通讯录', 'admin/contacts/index', 'fa fa-circle-o', '8');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('95', '40', '帮助中心', 'admin/help/index', 'fa fa-circle-o', '9');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('96', '43', '试卷管理', 'admin/ResourcesManage/passexam', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('97', '43', '问卷管理', 'Admin/ResourcesManage/passquestionnaire', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('100', '1', '主持调研/评估', 'admin/survey/index', 'fa fa-circle-o', '5');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('101', '1', '我的授课', 'admin/teach/index', 'fa fa-circle-o', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('102', '0', '工具管理', 'admin/tool/index', 'fa fa-circle-o', '8');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('103', '102', '调研管理', 'admin/research/index', 'fa fa-circle-o', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('104', '102', '目标管理', 'admin/tool/target', 'fa fa-circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('105', '102', '考试管理', 'admin/TestManage/index', 'fa fa-circle-o', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('106', '0', '资讯管理', 'admin/news/page', 'fa fa-circle-o', '10');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('107', '0', '内容管理', 'admin/content/index', 'fa fa-circle-o', '11');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('108', '107', '我的互动管理', 'admin/FriendsCircle/manageList', 'fa fa-circle-o', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('109', '107', 'Banner管理', 'admin/banner/detail', 'fa fa-circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('110', '107', '评论管理', 'admin/comment_manage/index/', 'fa fa-circle-o', '4');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('111', '0', '数据管理', 'admin/data/index', 'fa fa-circle-o', '12');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('113', '111', '员工学习统计', 'Admin/LearningStat/index', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('114', '111', '部门学时统计', 'Admin/ExamReport/hoursStat', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('115', '111', '费用统计分析', 'admin/data/cost', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('116', '111', '课程库报表', 'Admin/ExamReport/course', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('117', '111', '讲师库报表', 'Admin/ExamReport/lecturer', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('118', '111', '考试报表', 'admin/ExamReport/index', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('119', '111', '调研报表', 'Admin/ExamReport/survey', 'fa fa-circle-o', null);
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('121', '65', '福利管理', 'admin/Integration/welfarelist', 'circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('122', '65', '我的积分', 'admin/Integration/myintegration', 'circle-o', '1');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('123', '40', '学习目标', 'admin/center/goal', 'fa fa-circle-o', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('124', '42', '审核流程设置', 'admin/audit/setlist', 'circle-o', '11');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('125', '42', '培训项目审核', 'admin/audit/projectauditlist', 'fa fa-circle-o', '2');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('126', '42', '新建课程审核', 'admin/audit/courseauditlist', 'fa fa-circle-o', '3');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('127', '42', '新建试卷审核', 'admin/audit/examinationauditlist', 'fa fa-circle-o', '4');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('128', '42', '新建问卷审核', 'admin/audit/questionauditlist', 'fa fa-circle-o', '5');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('129', '42', '新建互动审核', 'admin/audit/topicauditlist', 'fa fa-circle-o', '6');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('130', '42', '发起调研审核', 'admin/audit/researchauditlist', 'fa fa-circle-o', '7');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('131', '42', '发起考试审核', 'admin/audit/testauditlist', 'fa fa-circle-o', '8');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('132', '42', '加分申请审核', 'admin/audit/applyauditlist', 'fa fa-circle-o', '9');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('133', '42', '用户注册审核', 'admin/audit/usersauditlist', 'fa fa-circle-o', '10');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('134', '40', '项目互动', 'admin/ItemInteraction/index', 'fa fa-circle-o', '6');
INSERT INTO "SCOTT"."THINK_ADMIN_NAV" VALUES ('135', '107', '项目互动管理', 'admin/ItemInteraction/manageindex', 'fa fa-circle-o', '2');

-- ----------------------------
-- Table structure for THINK_ADMIN_PROJECT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ADMIN_PROJECT";
CREATE TABLE "SCOTT"."THINK_ADMIN_PROJECT" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NULL ,
"PROJECT_NAME" VARCHAR2(60 BYTE) NULL ,
"CLASS_NAME" VARCHAR2(60 BYTE) NULL ,
"PROJECT_DESCRIPTION" VARCHAR2(4000 BYTE) NULL ,
"PROJECT_COVERS" VARCHAR2(60 BYTE) NULL ,
"PROJECT_BUDGET" NUMBER(20,2) NULL ,
"IS_PUBLIC" NUMBER(1) DEFAULT 0  NOT NULL ,
"POPULATION" NUMBER(1) DEFAULT 0  NULL ,
"ADD_TIME" DATE NULL ,
"START_TIME" DATE NOT NULL ,
"END_TIME" DATE NOT NULL ,
"TYPE" NUMBER(1) DEFAULT 2  NULL ,
"AUDIT_TIME" DATE NULL ,
"TISSUE_ID" VARCHAR2(4000 BYTE) NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(4000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_ADMIN_PROJECT" IS '项目表';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."USER_ID" IS '项目发起人uid';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."PROJECT_NAME" IS '名称';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."CLASS_NAME" IS '班级';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."PROJECT_DESCRIPTION" IS '描述';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."PROJECT_COVERS" IS '项目封面';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."PROJECT_BUDGET" IS '项目预算';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."IS_PUBLIC" IS '0表示不公开，1表示公开';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."POPULATION" IS '指定人数';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."ADD_TIME" IS '项目新增时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."TYPE" IS '0表示进行中，1表示草稿，2表示待审核，3表示拒绝，4已完成';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."TISSUE_ID" IS '指定部门';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_ADMIN_PROJECT"."OBJECTION" IS '拒绝理由';

-- ----------------------------
-- Records of THINK_ADMIN_PROJECT
-- ----------------------------
INSERT INTO "SCOTT"."THINK_ADMIN_PROJECT" VALUES ('44', '1', 'ssss', null, 'sss', null, '130', '0', null, TO_DATE('2017-06-01 17:13:55', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-01 16:54:35', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-23 16:54:37', 'YYYY-MM-DD HH24:MI:SS'), '0', null, null, '0184356979', null);

-- ----------------------------
-- Table structure for THINK_ATTENDANCE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ATTENDANCE";
CREATE TABLE "SCOTT"."THINK_ATTENDANCE" (
"ID" NUMBER(10) NOT NULL ,
"PID" NUMBER(10) DEFAULT 0  NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"COURSE_ID" NUMBER(10) NOT NULL ,
"STATUS" NUMBER(1) NULL ,
"MOBILE_SCANNING" NUMBER(1) DEFAULT 0  NOT NULL ,
"STATE" NUMBER(1) DEFAULT 0  NOT NULL ,
"TYPE" NUMBER(1) NOT NULL ,
"ATTENDANCE_TIME" DATE NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_ATTENDANCE" IS '课程考勤表';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."PID" IS '关联项目表id';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."STATUS" IS '考勤状态0表示缺勤，1表示按时,2表示迟到,3表示未考勤';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."MOBILE_SCANNING" IS '手机是否扫描过0表示未扫描，1表示已经扫描';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."STATE" IS '点击状态  0未点击（未开始）  1点击（结束）';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."TYPE" IS '类型 1pc端   2移动端';
COMMENT ON COLUMN "SCOTT"."THINK_ATTENDANCE"."ATTENDANCE_TIME" IS '考勤时间';

-- ----------------------------
-- Records of THINK_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_AUDIT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUDIT";
CREATE TABLE "SCOTT"."THINK_AUDIT" (
"ID" NUMBER(11) NOT NULL ,
"TYPE" NUMBER(3) DEFAULT 1  NULL ,
"CORRELATE_ID" NUMBER(10) DEFAULT 0  NULL ,
"LEVALONE_MAN" NUMBER(10) DEFAULT 0  NULL ,
"LEVALTWO_MAN" NUMBER(10) DEFAULT 0  NULL ,
"LEVALTHREE_MAN" NUMBER(10) DEFAULT 0  NULL ,
"AUDIT_STATUS" NUMBER(3) DEFAULT 0  NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) DEFAULT ''  NULL ,
"ONE_LEVEL_TYPE" NUMBER(3) DEFAULT 0  NULL ,
"TWO_LEVEL_TYPE" NUMBER(3) DEFAULT 0  NULL ,
"THREE_LEVEL_TYPE" NUMBER(3) DEFAULT 0  NULL ,
"ONEAUDIT_USER_ID" NUMBER(11) DEFAULT 0  NULL ,
"TWOAUDIT_USER_ID" NUMBER(11) DEFAULT 0  NULL ,
"THREEAUDIT_USER_ID" NUMBER(11) DEFAULT 0  NULL ,
"ONE_LEADER_TISSUEID" NUMBER(11) DEFAULT 0  NULL ,
"TWO_LEADER_TISSUEID" NUMBER(11) DEFAULT 0  NULL ,
"THREE_LEADER_TISSUEID" NUMBER(11) DEFAULT 0  NULL ,
"ONEAUDIT_ROLE" NUMBER(3) DEFAULT 0  NULL ,
"TWOAUDIT_ROLE" NUMBER(3) DEFAULT 0  NULL ,
"THREEAUDIT_ROLE" NUMBER(3) DEFAULT 0  NULL ,
"IS_CONDITION" NUMBER(3) DEFAULT 0  NULL ,
"CONDITION_ID" NUMBER(10) DEFAULT 0  NULL ,
"CONDITIONA" NUMBER(10) DEFAULT 0  NULL ,
"CONDITIONB" NUMBER(10) DEFAULT 0  NULL ,
"NUM" NUMBER(3) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."TYPE" IS '1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."CORRELATE_ID" IS '关联相应表自增ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."LEVALONE_MAN" IS '一级审核人，关联USER表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."LEVALTWO_MAN" IS '二级审核人';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."LEVALTHREE_MAN" IS '三级审核人';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."AUDIT_STATUS" IS '状态：0:待审核 1:一审通过 2:二审通过 3:三审通过 4:一审拒绝 5:二审拒绝 6:三审拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."ONE_LEVEL_TYPE" IS '一审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."TWO_LEVEL_TYPE" IS '二审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."THREE_LEVEL_TYPE" IS '三审人员范围的类型选择 (0:不选择,1: 选择人员,2:选择角色,3:负责人审核)';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."ONEAUDIT_USER_ID" IS '一审指定用户ID,关联用户表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."TWOAUDIT_USER_ID" IS '二审指定用户ID,关联用户表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."THREEAUDIT_USER_ID" IS '三审指定用户ID,关联用户表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."ONE_LEADER_TISSUEID" IS '一审负责人所在的组织ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."TWO_LEADER_TISSUEID" IS '二审负责人所在的组织ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."THREE_LEADER_TISSUEID" IS '三审负责人所在的组织ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."ONEAUDIT_ROLE" IS '一审角色ID,关联角色表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."TWOAUDIT_ROLE" IS '二审角色ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."THREEAUDIT_ROLE" IS '三审角色ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."IS_CONDITION" IS '状态：为1启用条件，为0禁用';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."CONDITION_ID" IS '关联审核条件表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."CONDITIONA" IS '条件值1';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."CONDITIONB" IS '条件值2';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT"."NUM" IS '审核轮数';

-- ----------------------------
-- Records of THINK_AUDIT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_AUDIT_CONDITION
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUDIT_CONDITION";
CREATE TABLE "SCOTT"."THINK_AUDIT_CONDITION" (
"ID" NUMBER(10) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"CONDITIONA" NUMBER(10,2) NOT NULL ,
"CONDITIONB" NUMBER(10,2) NOT NULL ,
"TYPE" NUMBER(1) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_AUDIT_CONDITION" IS '审核条件表';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_CONDITION"."NAME" IS '限制的条件名称';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_CONDITION"."CONDITIONA" IS '条件值1';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_CONDITION"."CONDITIONB" IS '条件值2';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_CONDITION"."TYPE" IS '1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册';

-- ----------------------------
-- Records of THINK_AUDIT_CONDITION
-- ----------------------------
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('1', '项目时长（天）', '5', '2', '1');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('2', '项目预算（元）', '5000', '0', '1');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('3', '指定人员（人数）', '5', '8', '1');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('4', '授课时长（分钟）', '50', '55', '2');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('5', '新建课程学分（分）', '20', '25', '2');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('6', '持续时长（天）', '0', '0', '6');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('7', '学分（分）', '10', '20', '6');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('8', '考试时长（分钟）', '50', '55', '7');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('9', '学分（分）', '20', '40', '7');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('10', '指定人员（人数）', '0', '0', '7');
INSERT INTO "SCOTT"."THINK_AUDIT_CONDITION" VALUES ('11', '积分（分）', '20', '40', '8');

-- ----------------------------
-- Table structure for THINK_AUDIT_RULE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUDIT_RULE";
CREATE TABLE "SCOTT"."THINK_AUDIT_RULE" (
"ID" NUMBER(10) NOT NULL ,
"TYPE" NUMBER(1) DEFAULT 1  NOT NULL ,
"ONEAUDIT_ROLE" NUMBER(10) DEFAULT 0  NOT NULL ,
"TWOAUDIT_ROLE" NUMBER(10) DEFAULT 0  NOT NULL ,
"THREEAUDIT_ROLE" NUMBER(10) DEFAULT 0  NOT NULL ,
"IS_CONDITION" NUMBER(1) DEFAULT 0  NOT NULL ,
"CONDITION_ID" NUMBER(10) DEFAULT 0  NOT NULL ,
"NUM" NUMBER(10) DEFAULT 1  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_AUDIT_RULE" IS '审核规则表';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."TYPE" IS '1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."ONEAUDIT_ROLE" IS '一审角色ID,关联角色表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."TWOAUDIT_ROLE" IS '二审角色ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."THREEAUDIT_ROLE" IS '三审角色ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."IS_CONDITION" IS '状态：为1启用条件，为0禁用';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."CONDITION_ID" IS '关联审核条件表ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUDIT_RULE"."NUM" IS '审核级数';

-- ----------------------------
-- Records of THINK_AUDIT_RULE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('1', '1', '2', '1', '1', '1', '3', '3');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('2', '2', '1', '1', '1', '0', '0', '3');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('3', '3', '2', '1', '0', '0', '0', '2');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('4', '4', '1', '2', '0', '0', '0', '2');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('5', '5', '1', '0', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('6', '6', '2', '1', '1', '1', '7', '3');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('7', '7', '1', '2', '1', '1', '9', '3');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('9', '9', '1', '0', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_AUDIT_RULE" VALUES ('8', '8', '2', '1', '1', '1', '11', '3');

-- ----------------------------
-- Table structure for THINK_AUTH_GROUP
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUTH_GROUP";
CREATE TABLE "SCOTT"."THINK_AUTH_GROUP" (
"ID" NUMBER(10) NOT NULL ,
"TITLE" VARCHAR2(100 BYTE) NULL ,
"STATUS" NUMBER(1) DEFAULT 1  NOT NULL ,
"RULES" VARCHAR2(4000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_AUTH_GROUP" IS '用户组表';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_GROUP"."ID" IS '标题';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_GROUP"."STATUS" IS '状态';

-- ----------------------------
-- Records of THINK_AUTH_GROUP
-- ----------------------------
INSERT INTO "SCOTT"."THINK_AUTH_GROUP" VALUES ('1', '管理员', '1', '96,164,165,166,20,1,176,186,187,21,7,8,9,10,11,12,13,14,15,16,123,124,125,19,104,105,188,213,127,133,169,170,171,189,190,191,215,227,192,193,194,129,137,138,139,140,195,196,131,172,173,174,175,197,177,178,179,180,181,216,218,219,220,221,222,223,224,225,226,198,199,200,201,202,203,204,205,206,207,208,209,210,214,212');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP" VALUES ('2', '讲师', '1', '96,165,20,1,176,186,187,104,105,127,133,169,170,171,189,190,191,215,129,139,140,195,196,131,172,173,174,175,197,177,178,179,180,181,216,218,219,220,221,222,223,224,225,226,203,204,205,206,207,208,209,210,214');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP" VALUES ('3', '学员', '1', '96,166,20,1,176,186,187,104,105,127,133,169,170,171,189,190,191,215');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP" VALUES ('46', '部门/区域兼职培训管理员', '1', '6,96,164,166,20,1,176,186,187,104,105,127,133,169,170,171,189,190,191,215,192,129,137,138,139,140,195,196,131,172,173,174,175,197,177,178,179,180,181,216,218,219,220,221,222,223,224,225,226,203,204,205,206,207,208,209,210,214');

-- ----------------------------
-- Table structure for THINK_AUTH_GROUP_ACCESS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUTH_GROUP_ACCESS";
CREATE TABLE "SCOTT"."THINK_AUTH_GROUP_ACCESS" (
"USER_ID" NUMBER(10) NOT NULL ,
"GROUP_ID" NUMBER(10) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_GROUP_ACCESS"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_GROUP_ACCESS"."GROUP_ID" IS '用户组ID';

-- ----------------------------
-- Records of THINK_AUTH_GROUP_ACCESS
-- ----------------------------
INSERT INTO "SCOTT"."THINK_AUTH_GROUP_ACCESS" VALUES ('55', '3');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP_ACCESS" VALUES ('1', '1');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP_ACCESS" VALUES ('53', '3');
INSERT INTO "SCOTT"."THINK_AUTH_GROUP_ACCESS" VALUES ('54', '3');

-- ----------------------------
-- Table structure for THINK_AUTH_RULE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_AUTH_RULE";
CREATE TABLE "SCOTT"."THINK_AUTH_RULE" (
"ID" NUMBER(10) NOT NULL ,
"PID" NUMBER(10) DEFAULT 0  NULL ,
"NAME" VARCHAR2(80 BYTE) NOT NULL ,
"TITLE" VARCHAR2(20 BYTE) NOT NULL ,
"STATUS" NUMBER(10) DEFAULT 1  NOT NULL ,
"TYPE" NUMBER(10) DEFAULT 1  NOT NULL ,
"CONDITION" VARCHAR2(100 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_AUTH_RULE" IS '规则表';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_RULE"."PID" IS '父级ID';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_RULE"."NAME" IS '规则唯一标识';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_RULE"."TITLE" IS '规则中文名称';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_RULE"."STATUS" IS '状态：为1正常，为0禁用';
COMMENT ON COLUMN "SCOTT"."THINK_AUTH_RULE"."CONDITION" IS '规则表达式，为空表示存在就验证，不为空表示按照条件验证';

-- ----------------------------
-- Records of THINK_AUTH_RULE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('1', '20', 'admin/my_course/publiccourse', '公开课程', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('6', '0', 'admin/index/index', '后台首页', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('7', '21', 'admin/rule/index', '权限管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('8', '7', 'admin/rule/add', '添加权限', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('9', '7', 'admin/rule/edit', '修改权限', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('10', '7', 'admin/rule/delete', '删除权限', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('11', '21', 'admin/rule/group', '用户组管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('12', '11', 'admin/rule/add_group', '添加用户组', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('13', '11', 'admin/rule/edit_group', '修改用户组', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('14', '11', 'admin/rule/delete_group', '删除用户组', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('15', '11', 'admin/rule/rule_group', '分配权限', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('16', '11', 'admin/rule/check_user', '添加成员', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('19', '21', 'admin/rule/admin_user_list', '管理员列表', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('20', '0', 'admin/task/index', '学习任务', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('21', '0', 'admin/shownav/rule', '权限控制', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('96', '6', 'admin/index/home', '首页', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('104', '0', 'admin/integration/index', '积分中心', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('105', '104', 'admin/Integration/myintegration', '我的积分', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('123', '11', 'admin/rule/add_user_to_group', '设置为管理员', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('124', '11', 'admin/rule/add_admin', '添加管理员', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('125', '11', 'admin/rule/edit_admin', '修改管理员', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('127', '0', 'admin/center/index', '个人中心', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('128', '0', 'admin/tissue/home', '组织架构', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('129', '0', 'admin/resources/index', '资源管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('131', '0', 'admin/train/index', '培训管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('133', '127', 'admin/center/study', '学习档案', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('137', '129', 'admin/supplier/suppliermanage', '供应商管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('138', '129', 'admin/lecturer/pagelist', '讲师管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('139', '129', 'admin/rs_course/index', '课程管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('140', '129', 'admin/ResourcesManage/examination', '试题库管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('164', '6', 'admin/index_admin/index', '培训主管', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('165', '6', 'admin/index/indexlecturer', '讲师面板', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('166', '6', 'admin/index/indexstudent', '学员面板', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('169', '127', 'admin/myGoal/goalPage', '授课目标', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('170', '127', 'admin/myNote/notePage', '我的笔记', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('171', '127', 'admin/FriendsCircle/friendsCircleList', '我的互动', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('172', '131', 'admin/manage/index', '培训项目管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('173', '131', 'admin/teach/index', '我的授课', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('174', '131', 'admin/attendance/attendanceManage', '考勤管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('175', '131', 'admin/host/index', '主持考试', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('176', '20', 'admin/my_course/index/type/1', '我的课程', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('177', '0', 'admin/tool/index', '工具管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('178', '177', 'admin/research/index', '调研管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('179', '177', 'admin/TestManage/index', '考试管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('180', '177', 'admin/tool/target', '目标管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('181', '0', 'admin/audit/auditlist', '审核中心', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('186', '20', 'admin/my_exam/waitexam', '我的考试', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('187', '20', 'admin/my_survey/waitsurvey', '我的调研/评估', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('188', '104', 'admin/Integration/integrationlist', '积分管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('189', '127', 'admin/center/pk', '找人PK', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('190', '127', 'admin/contacts/index', '通讯录', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('191', '127', 'admin/help/index', '帮助中心', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('192', '128', 'admin/tissue/index', '用户管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('193', '128', 'admin/jurisdiction/index', '权限管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('194', '128', 'admin/audit_users/jobsmanage', '岗位管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('195', '129', 'admin/ResourcesManage/passexam', '试卷管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('196', '129', 'admin/ResourcesManage/passquestionnaire', '问卷管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('197', '131', 'admin/survey/index', '主持调研/评估', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('198', '0', 'admin/news/page', '资讯管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('199', '0', 'admin/content/index', '内容管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('200', '199', 'admin/FriendsCircle/manageList', '我的互动管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('201', '199', 'admin/Banner/detail', 'Banner管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('202', '199', 'admin/comment_manage/index/', '评论管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('203', '0', 'admin/data/index', '数据管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('204', '203', 'admin/data/study', '学习档案', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('205', '203', 'Admin/LearningStat/index', '员工学习统计', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('206', '203', 'Admin/ExamReport/hoursStat', '部门学时统计', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('207', '203', 'admin/data/cost', '费用统计分析', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('208', '203', 'Admin/ExamReport/course', '课程库报表', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('209', '203', 'Admin/ExamReport/lecturer', '讲师库报表', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('210', '203', 'admin/ExamReport/index', '考试报表', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('212', '0', 'admin/test/index', '测试导航', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('213', '104', 'admin/Integration/welfarelist', '福利管理', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('214', '203', 'Admin/ExamReport/survey', '调研报表', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('215', '127', 'admin/center/goal', '学习目标', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('216', '181', 'admin/audit/setlist', '审核流程设置', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('218', '181', 'admin/audit/projectauditlist', '培训项目审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('219', '181', 'admin/audit/courseauditlist', '新建课程审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('220', '181', 'admin/audit/examinationauditlist', '新建试卷审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('221', '181', 'admin/audit/questionauditlist', '新建问卷审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('222', '181', 'admin/audit/topicauditlist', '新建互动审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('223', '181', 'admin/audit/researchauditlist', '发起调研审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('224', '181', 'admin/audit/testauditlist', '发起考试审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('225', '181', 'admin/audit/applyauditlist', '加分申请审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('226', '181', 'admin/audit/usersauditlist', '用户注册审核', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('227', '127', 'admin/ItemInteraction/index', '项目互动', '1', '1', ' ');
INSERT INTO "SCOTT"."THINK_AUTH_RULE" VALUES ('228', '199', 'admin/ItemInteraction/manageindex', '项目互动管理', '1', '1', ' ');

-- ----------------------------
-- Table structure for THINK_CENTER_STUDY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_CENTER_STUDY";
CREATE TABLE "SCOTT"."THINK_CENTER_STUDY" (
"ID" NUMBER(10) NOT NULL ,
"CREATE_TIME" DATE NULL ,
"TYPEID" NUMBER(1) NULL ,
"CREDIT" NUMBER(10) NULL ,
"SOURCE_ID" VARCHAR2(255 BYTE) NULL ,
"PROJECT_ID" NUMBER(10) NULL ,
"USER_ID" NUMBER(10) NULL ,
"HOURS" NUMBER(10) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_CENTER_STUDY" IS '个人中心 - 学习档案';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."TYPEID" IS '学分类型(0-我的考试,1-项目调研,2-我的课程,3-其它调研,4必修课程，5选修课程)';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."CREDIT" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."SOURCE_ID" IS '试卷/课程/调研ID';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."PROJECT_ID" IS '关联项目';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_CENTER_STUDY"."HOURS" IS '学时(分钟)';

-- ----------------------------
-- Records of THINK_CENTER_STUDY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_CHAIQIQI
-- ----------------------------
DROP TABLE "SCOTT"."THINK_CHAIQIQI";
CREATE TABLE "SCOTT"."THINK_CHAIQIQI" (
"ID" NUMBER NOT NULL ,
"USERNAME" VARCHAR2(255 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of THINK_CHAIQIQI
-- ----------------------------
INSERT INTO "SCOTT"."THINK_CHAIQIQI" VALUES ('1', 'name1');
INSERT INTO "SCOTT"."THINK_CHAIQIQI" VALUES ('2', 'name2');
INSERT INTO "SCOTT"."THINK_CHAIQIQI" VALUES ('3', 'name3');
INSERT INTO "SCOTT"."THINK_CHAIQIQI" VALUES ('5', 'name5');
INSERT INTO "SCOTT"."THINK_CHAIQIQI" VALUES ('6', 'chaiqiqi');

-- ----------------------------
-- Table structure for THINK_COLLIGATE_COMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COLLIGATE_COMMENT";
CREATE TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" (
"ID" NUMBER(10) NOT NULL ,
"LECTURER_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"LECTURER_EVALUATION" NUMBER(1) DEFAULT 5  NOT NULL ,
"COURSE_ID" NUMBER(10) NOT NULL ,
"COURSE_EVALUATION" NUMBER(1) DEFAULT 5  NOT NULL ,
"COMMENT_CONTENT" VARCHAR2(4000 BYTE) NOT NULL ,
"COMMENT_TIME" DATE NOT NULL ,
"PID" NUMBER(10) DEFAULT 0  NOT NULL ,
"STATE" NUMBER(1) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" IS '课程讲师评价表';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."LECTURER_ID" IS '讲师ID';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."USER_ID" IS '发布评论者ID';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."LECTURER_EVALUATION" IS '讲师评价 0没星 1一星  2两星  3三星  4四星 5五星(废弃)';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."COURSE_ID" IS '发布评论所关联的课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."COURSE_EVALUATION" IS '课程评价 0没星 1一星  2两星  3三星  4四星 5五星(废弃)';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."COMMENT_CONTENT" IS '评论内容';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."COMMENT_TIME" IS '评论时间';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."PID" IS '上级评论ID ,没有评论则PID为0';
COMMENT ON COLUMN "SCOTT"."THINK_COLLIGATE_COMMENT"."STATE" IS '状态 0未读 1已读';

-- ----------------------------
-- Records of THINK_COLLIGATE_COMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COMPANY_BANNER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COMPANY_BANNER";
CREATE TABLE "SCOTT"."THINK_COMPANY_BANNER" (
"ID" NUMBER(10) NOT NULL ,
"COMPANY_NAME" VARCHAR2(50 BYTE) NULL ,
"BANNER_IMG" VARCHAR2(100 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COMPANY_BANNER" IS '公司BANNER图片表';
COMMENT ON COLUMN "SCOTT"."THINK_COMPANY_BANNER"."ID" IS '公司ID';
COMMENT ON COLUMN "SCOTT"."THINK_COMPANY_BANNER"."COMPANY_NAME" IS '公司名称';
COMMENT ON COLUMN "SCOTT"."THINK_COMPANY_BANNER"."BANNER_IMG" IS 'BANNER图链接';

-- ----------------------------
-- Records of THINK_COMPANY_BANNER
-- ----------------------------
INSERT INTO "SCOTT"."THINK_COMPANY_BANNER" VALUES ('2', '深圳典阅', '/Upload/20170601/592f851ba4fbc.jpg');

-- ----------------------------
-- Table structure for THINK_COURSE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE";
CREATE TABLE "SCOTT"."THINK_COURSE" (
"ID" NUMBER(10) NOT NULL ,
"COURSE_NAME" VARCHAR2(50 BYTE) NOT NULL ,
"COURSE_CODE" VARCHAR2(20 BYTE) DEFAULT ''  NULL ,
"COURSE_TIME" NUMBER(10) NOT NULL ,
"COURSE_CAT_ID" NUMBER(10) NULL ,
"LECTURER" NUMBER(10) DEFAULT 0  NULL ,
"COURSE_WAY" NUMBER(1) DEFAULT 0  NULL ,
"MEDIA_SRC" VARCHAR2(255 BYTE) NULL ,
"MAKER" VARCHAR2(50 BYTE) NOT NULL ,
"CHAPTER" VARCHAR2(3000 BYTE) DEFAULT ''  NULL ,
"COURSE_COVER" VARCHAR2(255 BYTE) NOT NULL ,
"CREDIT" NUMBER(2) NOT NULL ,
"AUDITING" NUMBER(1) DEFAULT 0  NOT NULL ,
"CREATE_TIME" DATE NOT NULL ,
"UPDATE_TIME" DATE NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"IS_PUBLIC" NUMBER(1) DEFAULT 0  NULL ,
"CLICK_COUNT" NUMBER(10) DEFAULT 0  NOT NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"AUDIT_TIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"LOCATION" VARCHAR2(30 BYTE) NULL ,
"RESTRICTIONS" VARCHAR2(30 BYTE) DEFAULT 0  NULL ,
"LECTURER_NAME" VARCHAR2(80 BYTE) NULL ,
"USER_ID" NUMBER(10) NULL ,
"IS_TRIGGER" NUMBER(10) DEFAULT 0  NULL ,
"SCORE" NUMBER(2,1) DEFAULT 0.0  NOT NULL ,
"ARRANGEMENT_ID" NUMBER(10) NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL ,
"COURSE_DESCRIPTION" VARCHAR2(4000 BYTE) NULL ,
"TAG_ID" VARCHAR2(1000 BYTE) NULL ,
"JOBS_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE" IS '课程表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."ID" IS '主键自增';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."COURSE_NAME" IS '课程名';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."COURSE_CODE" IS '课程编码';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."COURSE_CAT_ID" IS '分类';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."LECTURER" IS '讲师ＩＤ';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."COURSE_WAY" IS '授课方式:0在线，1面授';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."MEDIA_SRC" IS '媒体地址';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."MAKER" IS '制作人';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."CHAPTER" IS '章节';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."COURSE_COVER" IS '课程封面';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."CREDIT" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."AUDITING" IS '是否启用:0表示禁用,1表示启用';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."UPDATE_TIME" IS '更新时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."STATUS" IS '0表示待审核，1表示已通过，2表示已拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."IS_PUBLIC" IS '0表示不公开，1表示公开';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."CLICK_COUNT" IS '点击次数';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."START_TIME" IS '面授课程开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."END_TIME" IS '面授结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."LOCATION" IS '面授地点';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."RESTRICTIONS" IS '限制人数';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."LECTURER_NAME" IS '讲师名称';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."USER_ID" IS '上传用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."IS_TRIGGER" IS '筛选条件 (0-禁用,1-开启)';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."SCORE" IS '课程评分';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."ARRANGEMENT_ID" IS '所属层次(1-基层,2-中间层,3-核心层,4-专业层)';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."TAG_ID" IS '所属标签';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE"."JOBS_ID" IS '岗位ID';

-- ----------------------------
-- Records of THINK_COURSE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_COURSE" VALUES ('9', '课程1', null, '120', '48', '0', '0', null, '制作人1', null, '/Public/Dist/img/onlinecourse.png', '12', '1', TO_DATE('2017-05-31 14:45:07', 'YYYY-MM-DD HH24:MI:SS'), null, '1', '0', '0', null, null, '0', null, '0', '讲师1', '1', '0', '0', '1', '0231078763', null, null, null, '5');

-- ----------------------------
-- Table structure for THINK_COURSE_ANSWER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_ANSWER";
CREATE TABLE "SCOTT"."THINK_COURSE_ANSWER" (
"ID" NUMBER(10) NOT NULL ,
"QUES_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"CONTENT" VARCHAR2(600 BYTE) NOT NULL ,
"ADD_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_ANSWER" IS '课程提问答案表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ANSWER"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ANSWER"."QUES_ID" IS '问题ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ANSWER"."USER_ID" IS '会员ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ANSWER"."CONTENT" IS '问题内容';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ANSWER"."ADD_TIME" IS '数据添加时间';

-- ----------------------------
-- Records of THINK_COURSE_ANSWER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_ARTICLE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_ARTICLE";
CREATE TABLE "SCOTT"."THINK_COURSE_ARTICLE" (
"ID" NUMBER(10) NOT NULL ,
"COURSE_ID" NUMBER(10) NULL ,
"SORT" NUMBER(3) NULL ,
"TITLE" VARCHAR2(30 BYTE) NULL ,
"CONTENTS" BLOB NULL ,
"ADDRESS" VARCHAR2(255 BYTE) NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NULL ,
"ICO" VARCHAR2(20 BYTE) NOT NULL ,
"SAVE_NAME" VARCHAR2(60 BYTE) NULL ,
"TRUE_NAME" VARCHAR2(60 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_ARTICLE" IS '课程附件表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."SORT" IS '排序';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."TITLE" IS '课程标题';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."CONTENTS" IS '课程内容';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."ADDRESS" IS '课程地址';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."TYPE" IS '0表示PDF,1表示WPS,2表示PPT，3表示EXCLE, 4表示视频';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."ICO" IS '图标';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."SAVE_NAME" IS '保存文件名';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ARTICLE"."TRUE_NAME" IS '文件原名';

-- ----------------------------
-- Records of THINK_COURSE_ARTICLE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_ATTENDANCE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_ATTENDANCE";
CREATE TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" (
"COURSE_ID" NUMBER(10) NOT NULL ,
"PROJECT_ID" NUMBER(10) NOT NULL ,
"STATUS" NUMBER(1) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" IS '课程考勤表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ATTENDANCE"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ATTENDANCE"."PROJECT_ID" IS '所关联的项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ATTENDANCE"."STATUS" IS '状态 0-未签到 1-已签到 3-已删除';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_ATTENDANCE"."USER_ID" IS '用户ID';

-- ----------------------------
-- Records of THINK_COURSE_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_CARE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_CARE";
CREATE TABLE "SCOTT"."THINK_COURSE_CARE" (
"ID" NUMBER(10) NOT NULL ,
"COURSE_ID" NUMBER(10) NOT NULL ,
"CARE_STATUS" NUMBER(1) DEFAULT 2  NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"IS_PUBLIC" NUMBER(1) DEFAULT 1  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_CARE" IS '课程关注表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CARE"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CARE"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CARE"."CARE_STATUS" IS '关注状态 2取消关注 1关注 默认2';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CARE"."USER_ID" IS '会员ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CARE"."IS_PUBLIC" IS '2不公开 1公开  默认1';

-- ----------------------------
-- Records of THINK_COURSE_CARE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_CATEGORY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_CATEGORY";
CREATE TABLE "SCOTT"."THINK_COURSE_CATEGORY" (
"ID" NUMBER(10) NOT NULL ,
"PID" NUMBER(10) DEFAULT 0  NULL ,
"SORT" NUMBER(10) DEFAULT 0  NULL ,
"CAT_NAME" VARCHAR2(50 BYTE) NULL ,
"IMG" VARCHAR2(255 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_CATEGORY" IS '课程分类';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CATEGORY"."PID" IS '父类ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CATEGORY"."SORT" IS '排序';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CATEGORY"."CAT_NAME" IS '分类名';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CATEGORY"."IMG" IS '类别图片';

-- ----------------------------
-- Records of THINK_COURSE_CATEGORY
-- ----------------------------
INSERT INTO "SCOTT"."THINK_COURSE_CATEGORY" VALUES ('53', '0', '0', '保险培训类', '/Upload/coursecategory/20170316/category4.png');
INSERT INTO "SCOTT"."THINK_COURSE_CATEGORY" VALUES ('48', '0', '0', '太平培训', '/Upload/coursecategory/20170316/category4.png');
INSERT INTO "SCOTT"."THINK_COURSE_CATEGORY" VALUES ('5', '0', '0', '分类11', null);
INSERT INTO "SCOTT"."THINK_COURSE_CATEGORY" VALUES ('6', '5', '0', '分类21', null);

-- ----------------------------
-- Table structure for THINK_COURSE_CHAPTER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_CHAPTER";
CREATE TABLE "SCOTT"."THINK_COURSE_CHAPTER" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NOT NULL ,
"COURSE_ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"PATH" VARCHAR2(255 BYTE) NOT NULL ,
"TYPE" NUMBER(1) NOT NULL ,
"STATUS" NUMBER(1) NOT NULL ,
"TIMELEN" NUMBER(11) DEFAULT 0  NOT NULL ,
"TIME_PERCENT" NUMBER(11,2) NULL ,
"CREATE_TIME" DATE NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_CHAPTER" IS '课程附件章节表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."NAME" IS '附件名称';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."PATH" IS '附件地址';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."TYPE" IS '附件类型 1视频VIDEO 2音频AUDIO 3文档DOC';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."STATUS" IS '学习状态 1未学习 2学习中 3已学完，学习中为视频音频专用';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."TIMELEN" IS '学习时长（单位：秒），为视频音频专用';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."TIME_PERCENT" IS '视频音频播放进度百分比，示例：85.60';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_CHAPTER"."CREATE_TIME" IS '附件创建时间';

-- ----------------------------
-- Records of THINK_COURSE_CHAPTER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_COMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_COMMENT";
CREATE TABLE "SCOTT"."THINK_COURSE_COMMENT" (
"ID" NUMBER NULL ,
"AUTHOR_ID" NUMBER NULL ,
"PID" NUMBER NULL ,
"COMMENT_CONTENT" VARCHAR2(100 BYTE) NULL ,
"COMMENT_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of THINK_COURSE_COMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_DETAIL
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_DETAIL";
CREATE TABLE "SCOTT"."THINK_COURSE_DETAIL" (
"ID" NUMBER(10) NOT NULL ,
"COURSE_INTRO" VARCHAR2(4000 BYTE) NULL ,
"COURSE_AIM" VARCHAR2(4000 BYTE) NULL ,
"COURSE_SUMMARY" VARCHAR2(4000 BYTE) NULL ,
"COURSE_OUTLINE" VARCHAR2(4000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_DETAIL" IS '课程详情表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL"."ID" IS '课程ＩＤ';

-- ----------------------------
-- Records of THINK_COURSE_DETAIL
-- ----------------------------
INSERT INTO "SCOTT"."THINK_COURSE_DETAIL" VALUES ('9', '少时诵诗书', '少时诵诗书', null, '少时诵诗书');

-- ----------------------------
-- Table structure for THINK_COURSE_DETAIL_BAK
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_DETAIL_BAK";
CREATE TABLE "SCOTT"."THINK_COURSE_DETAIL_BAK" (
"ID" NUMBER(10) NOT NULL ,
"COURSE_INTRO" BLOB NULL ,
"COURSE_AIM" BLOB NULL ,
"COURSE_SUMMARY" BLOB NULL ,
"COURSE_OUTLINE" BLOB NULL ,
"COURSE_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_DETAIL_BAK" IS '课程详情表--历史记录';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."ID" IS '课程ＩＤ';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."COURSE_INTRO" IS '课程介绍';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."COURSE_AIM" IS '课程目标';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."COURSE_SUMMARY" IS '课程概要';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."COURSE_OUTLINE" IS '课程大纲';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_DETAIL_BAK"."COURSE_ID" IS '来源课程ID';

-- ----------------------------
-- Records of THINK_COURSE_DETAIL_BAK
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_NOTE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_NOTE";
CREATE TABLE "SCOTT"."THINK_COURSE_NOTE" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NULL ,
"PROJECT_ID" NUMBER(11) NULL ,
"COURSE_ID" NUMBER(10) NULL ,
"NOTE_CONTENT" VARCHAR2(100 BYTE) NULL ,
"TIME" DATE NULL ,
"IS_PUBLIC" NUMBER(1) DEFAULT 1  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."USER_ID" IS '学员ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."NOTE_CONTENT" IS '笔记内容';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."TIME" IS '新建笔记时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_NOTE"."IS_PUBLIC" IS '1公开 2不公开';

-- ----------------------------
-- Records of THINK_COURSE_NOTE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_PRAISE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_PRAISE";
CREATE TABLE "SCOTT"."THINK_COURSE_PRAISE" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"PRAISE" NUMBER(11) NOT NULL ,
"PRAISE_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_PRAISE" IS '课程点赞表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_PRAISE"."ID" IS '关联综合评价表主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_PRAISE"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_PRAISE"."PRAISE" IS '点赞状态 1点赞  0取消点赞';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_PRAISE"."PRAISE_TIME" IS '点赞时间';

-- ----------------------------
-- Records of THINK_COURSE_PRAISE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_QUES
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_QUES";
CREATE TABLE "SCOTT"."THINK_COURSE_QUES" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"COURSE_ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NOT NULL ,
"CHAPTER" NUMBER(11) NOT NULL ,
"TITLE" VARCHAR2(400 BYTE) NOT NULL ,
"CONTENT" VARCHAR2(600 BYTE) NOT NULL ,
"ADD_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_QUES" IS '课程提问问题表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."USER_ID" IS '会员ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."CHAPTER" IS '章节序号';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."TITLE" IS '标题';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."CONTENT" IS '问题内容';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_QUES"."ADD_TIME" IS '数据添加时间';

-- ----------------------------
-- Records of THINK_COURSE_QUES
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_RECORD
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_RECORD";
CREATE TABLE "SCOTT"."THINK_COURSE_RECORD" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"COURSE_ID" NUMBER(10) NOT NULL ,
"RECENTLY_LOOKUP_TIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"START_TIME" DATE NOT NULL ,
"END_TIME" DATE NOT NULL ,
"PROJECT_ID" NUMBER(10) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_RECORD" IS '课程记录表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."RECENTLY_LOOKUP_TIME" IS '最近预览时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."START_TIME" IS '开始学习时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."END_TIME" IS '结束学习时间';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_RECORD"."PROJECT_ID" IS '项目ID';

-- ----------------------------
-- Records of THINK_COURSE_RECORD
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_REPLYCOMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_REPLYCOMMENT";
CREATE TABLE "SCOTT"."THINK_COURSE_REPLYCOMMENT" (
"ID" NUMBER NULL ,
"AUTHOR_ID" NUMBER NULL ,
"REPLY_COMMENT_CONTENT" VARCHAR2(255 BYTE) NULL ,
"REPLY_COMMENT_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of THINK_COURSE_REPLYCOMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_COURSE_SCORE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_COURSE_SCORE";
CREATE TABLE "SCOTT"."THINK_COURSE_SCORE" (
"ID" NUMBER(10) NOT NULL ,
"LECTURER_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"COURSE_ID" NUMBER(10) NOT NULL ,
"LECTURER_SCORE" NUMBER(1) DEFAULT 5  NOT NULL ,
"COURSE_SCORE" NUMBER(1) DEFAULT 5  NOT NULL ,
"SCORE_TIME" DATE NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_COURSE_SCORE" IS '课程讲师评分表';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."LECTURER_ID" IS '讲师ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."USER_ID" IS '发布评论者ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."COURSE_ID" IS '发布评论所关联的课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."LECTURER_SCORE" IS '讲师评价 0没星 1一星  2两星  3三星  4四星 5五星';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."COURSE_SCORE" IS '课程评价 0没星 1一星  2两星  3三星  4四星 5五星';
COMMENT ON COLUMN "SCOTT"."THINK_COURSE_SCORE"."SCORE_TIME" IS '评分时间';

-- ----------------------------
-- Records of THINK_COURSE_SCORE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_CREDITS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_CREDITS";
CREATE TABLE "SCOTT"."THINK_CREDITS" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NULL ,
"TISSUE_ID" NUMBER(10) NULL ,
"SCORE" NUMBER(10) DEFAULT 0  NULL ,
"PROJECT_ID" NUMBER(10) NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NULL ,
"SOURCE" VARCHAR2(100 BYTE) NOT NULL ,
"COURSE_ID" NUMBER(10) DEFAULT 0  NULL ,
"TEST_ID" NUMBER(10) DEFAULT 0  NULL ,
"SURVEY_ID" NUMBER(10) DEFAULT 0  NULL ,
"ADD_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_CREDITS" IS '学分统计表';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."TISSUE_ID" IS '组织ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."SCORE" IS '学分值';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."TYPE" IS '学分类型：1表示参加课程，2表示参加考试，3表示参加调研';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."SOURCE" IS '来源：课程、考试、调研名称';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."TEST_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."SURVEY_ID" IS '问卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_CREDITS"."ADD_TIME" IS '增加时间';

-- ----------------------------
-- Records of THINK_CREDITS
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_DESIGNATED_PERSONNEL
-- ----------------------------
DROP TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL";
CREATE TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL" (
"ID" NUMBER(10) NOT NULL ,
"PROJECT_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL" IS '项目指定人员关联表';
COMMENT ON COLUMN "SCOTT"."THINK_DESIGNATED_PERSONNEL"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_DESIGNATED_PERSONNEL"."USER_ID" IS '指定人员ID';

-- ----------------------------
-- Records of THINK_DESIGNATED_PERSONNEL
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_EXAM_ANSWER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAM_ANSWER";
CREATE TABLE "SCOTT"."THINK_EXAM_ANSWER" (
"ID" NUMBER(10) NOT NULL ,
"EXAM_ID" NUMBER(10) NOT NULL ,
"PROJECT_ID" NUMBER(10) NOT NULL ,
"U_EXAM_ID" NUMBER(10) NOT NULL ,
"EXAM_ANSWER" VARCHAR2(600 BYTE) NOT NULL ,
"CLASSIFICATION" VARCHAR2(30 BYTE) NOT NULL ,
"QUESTION_NUMBER" NUMBER(10) NOT NULL ,
"ISEXAM" NUMBER(10) DEFAULT 0  NULL ,
"CORRECT_ANSWER" VARCHAR2(255 BYTE) NULL ,
"DATA_TIEM" DATE NULL ,
"TEST_ID" NUMBER(10) NOT NULL ,
"WDSCORE" NUMBER(10) NULL ,
"CHECKED" NUMBER(1) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAM_ANSWER" IS '考试答案记录表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."EXAM_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."PROJECT_ID" IS '关联项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."U_EXAM_ID" IS '关联考试人ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."EXAM_ANSWER" IS '考试答案';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."CLASSIFICATION" IS '题型（1单选题 2多选题 3判断题 4简答题）';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."QUESTION_NUMBER" IS '题目序号';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."ISEXAM" IS '答题状态 (0-错误,1-正确)';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."CORRECT_ANSWER" IS '正确答案';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."DATA_TIEM" IS '参加考试时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."TEST_ID" IS '考试ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."WDSCORE" IS '问答题得分';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_ANSWER"."CHECKED" IS '是否已经人工判分，0-否，1-是';

-- ----------------------------
-- Records of THINK_EXAM_ANSWER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_EXAM_SCORE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAM_SCORE";
CREATE TABLE "SCOTT"."THINK_EXAM_SCORE" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"EXAM_ID" NUMBER(10) NOT NULL ,
"TOTAL_SCORE" NUMBER(4) NOT NULL ,
"PROJECT_ID" NUMBER(10) NULL ,
"IS_PUBLISH" NUMBER(1) DEFAULT 0  NOT NULL ,
"TEST_ID" NUMBER(10) NULL ,
"USE_TIME" NUMBER(10) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAM_SCORE" IS '得分表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."USER_ID" IS '指定人员ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."EXAM_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."TOTAL_SCORE" IS '总得分';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."PROJECT_ID" IS '关联项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."IS_PUBLISH" IS '发布状态，0表示未发布，1表示已发布';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."TEST_ID" IS '考试ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAM_SCORE"."USE_TIME" IS '考试用时，单位：秒';

-- ----------------------------
-- Records of THINK_EXAM_SCORE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_EXAMINATION
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION";
CREATE TABLE "SCOTT"."THINK_EXAMINATION" (
"ID" NUMBER(10) NOT NULL ,
"TEST_NAME" VARCHAR2(100 BYTE) NOT NULL ,
"TEST_CAT_ID" NUMBER(10) NOT NULL ,
"TEST_SCORE" NUMBER(10,2) DEFAULT 0  NOT NULL ,
"TEST_HEIR" VARCHAR2(60 BYTE) NULL ,
"TEST_UPLOAD_TIME" DATE NOT NULL ,
"STATUS" NUMBER(1) NOT NULL ,
"IS_AVAILABLE" NUMBER(1) DEFAULT 0  NOT NULL ,
"PRINCIPAL" VARCHAR2(60 BYTE) NULL ,
"START_TIME" DATE NULL ,
"TEST_MODE" NUMBER(1) NULL ,
"END_TIME" DATE NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NOT NULL ,
"AUDIT_TIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL ,
"PASSING_SCORE" NUMBER(10,2) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION" IS '试卷表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_NAME" IS '试卷名称';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_CAT_ID" IS '试卷分类';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_SCORE" IS '试卷总分';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_HEIR" IS '上传人';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_UPLOAD_TIME" IS '上传时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."STATUS" IS '0表示待审核，1表示已通过，3表示已拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."IS_AVAILABLE" IS '是否启用，0表示禁用，1表示启用';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."PRINCIPAL" IS '负责人';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TEST_MODE" IS '考试方式0表示线下，1表示线上（废弃）';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."TYPE" IS '试卷类型：0-组卷 1-导入';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION"."PASSING_SCORE" IS '及格分数';

-- ----------------------------
-- Records of THINK_EXAMINATION
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_EXAMINATION_ATTENDANCE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE";
CREATE TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" (
"ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"TEST_ID" NUMBER(10) NULL ,
"DEPARTMENT_ID" NUMBER(10) NULL ,
"POSITION_ID" NUMBER(10) NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"MOBILE_SCANNING" NUMBER(1) DEFAULT 0  NOT NULL ,
"PROJECT_ID" NUMBER(10) NULL ,
"EXAMINATION_ID" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" IS '学员考试考勤表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."USER_ID" IS '考试人ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."TEST_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."DEPARTMENT_ID" IS '部门ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."POSITION_ID" IS '岗位ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."STATUS" IS '0未考,1已考,3删除';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."PROJECT_ID" IS '试卷关联的项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ATTENDANCE"."EXAMINATION_ID" IS '考试ID';

-- ----------------------------
-- Records of THINK_EXAMINATION_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_EXAMINATION_CATEGORY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY";
CREATE TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" (
"ID" NUMBER(10) NOT NULL ,
"PID" NUMBER(10) DEFAULT 0  NOT NULL ,
"SORT" NUMBER(10) DEFAULT 1  NOT NULL ,
"CAT_NAME" VARCHAR2(50 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" IS '试卷分类表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_CATEGORY"."PID" IS '父ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_CATEGORY"."SORT" IS '排序';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_CATEGORY"."CAT_NAME" IS '栏目名称';

-- ----------------------------
-- Records of THINK_EXAMINATION_CATEGORY
-- ----------------------------
INSERT INTO "SCOTT"."THINK_EXAMINATION_CATEGORY" VALUES ('1', '0', '1', '试卷分类1');
INSERT INTO "SCOTT"."THINK_EXAMINATION_CATEGORY" VALUES ('2', '0', '1', '试卷分类2');

-- ----------------------------
-- Table structure for THINK_EXAMINATION_COLLECT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION_COLLECT";
CREATE TABLE "SCOTT"."THINK_EXAMINATION_COLLECT" (
"ID" NUMBER(10) NOT NULL ,
"TEST_NAME" VARCHAR2(50 BYTE) NULL ,
"TEST_ID" NUMBER(10) NULL ,
"CREATE_USER" NUMBER(10) NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"PRINCIPAL" VARCHAR2(50 BYTE) NULL ,
"TEST_MAX_ID" NUMBER(10) NULL ,
"EXAMINATION_MAX_ID" NUMBER(10) NULL ,
"BELONG_PROJECT_ID" NUMBER(10) NULL ,
"BELONG_PROJECT_NAME" VARCHAR2(50 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION_COLLECT" IS '项目考试--考试管理汇总表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."TEST_NAME" IS '考试名称';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."TEST_ID" IS '考试ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."CREATE_USER" IS '创建者ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."TYPE" IS '考试方式,0-线上，1-线下';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."START_TIME" IS '考试开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."END_TIME" IS '考试结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."PRINCIPAL" IS '负责人';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."TEST_MAX_ID" IS '保存更新本表时的TEST表最大ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."EXAMINATION_MAX_ID" IS '保存更新本表时的EXAMINATION表最大ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."BELONG_PROJECT_ID" IS '所属课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_COLLECT"."BELONG_PROJECT_NAME" IS '所属课程名称';

-- ----------------------------
-- Records of THINK_EXAMINATION_COLLECT
-- ----------------------------
INSERT INTO "SCOTT"."THINK_EXAMINATION_COLLECT" VALUES ('1', '线下01', '44', null, null, TO_DATE('2017-06-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-02 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'admin', null, '44', '44', 'ssss');
INSERT INTO "SCOTT"."THINK_EXAMINATION_COLLECT" VALUES ('2', '试卷1101', '44', null, null, TO_DATE('2017-06-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'admin', null, '44', '44', 'ssss');

-- ----------------------------
-- Table structure for THINK_EXAMINATION_ITEM
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION_ITEM";
CREATE TABLE "SCOTT"."THINK_EXAMINATION_ITEM" (
"ID" NUMBER(10) NOT NULL ,
"TITLE" VARCHAR2(255 BYTE) NOT NULL ,
"OPTIONA" VARCHAR2(50 BYTE) NULL ,
"OPTIONB" VARCHAR2(50 BYTE) NULL ,
"OPTIONC" VARCHAR2(50 BYTE) NULL ,
"OPTIOND" VARCHAR2(50 BYTE) NULL ,
"OPTIONE" VARCHAR2(50 BYTE) NULL ,
"RIGHT_OPTION" VARCHAR2(512 BYTE) NOT NULL ,
"CLASSIFICATION" NUMBER(1) DEFAULT 0  NULL ,
"CTIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"CREATER_ID" NUMBER(10) NULL ,
"KEYWORDS" VARCHAR2(255 BYTE) NULL ,
"BELONGCOURSE" NUMBER(10) NOT NULL ,
"ANALYSIS" VARCHAR2(512 BYTE) NULL ,
"QUESTION_BANK_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION_ITEM" IS '题库表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."TITLE" IS '题目名称';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."OPTIONA" IS '选项A';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."OPTIONB" IS '选项B';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."OPTIONC" IS '选项C';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."OPTIOND" IS '选项D';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."OPTIONE" IS '选项E';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."RIGHT_OPTION" IS '正确答案';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."CLASSIFICATION" IS '试题分类 1表示单选择题 2表示多选 3判断题 4问答题';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."CTIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."CREATER_ID" IS '创建人ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."KEYWORDS" IS '问答题得分关键字,英文逗号分割';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."BELONGCOURSE" IS '所属课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."ANALYSIS" IS '试题解析';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM"."QUESTION_BANK_ID" IS '所属试题库ID';

-- ----------------------------
-- Records of THINK_EXAMINATION_ITEM
-- ----------------------------
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM" VALUES ('9', 'yyyyyyyyyy', '对', '错', null, null, null, 'B', '3', '1496214387', '1', null, '9', 'qqqqqqqqqqqqqq', null);
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM" VALUES ('6', '单选题1', 'danxuana', 'danxuanb', 'danxuanc', 'danxuand', null, 'A', '1', '1496214077', '1', null, '9', '单选题试题解析1', null);
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM" VALUES ('7', '多选题1', '多选a', '多选b', '多选c', '多选d', null, 'B,C,D', '2', '1496214148', '1', null, '9', '多选题试题解析1', null);
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM" VALUES ('8', 'ssss', 'sss', 'ddd', 'qqq', 'www', null, 'D', '1', '1496214366', '1', null, '9', 'asasaaaa', null);
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM" VALUES ('10', 'hhhhhhhhhhhhhhhhh', null, null, null, null, null, 'ssasasdadasdasdasdasdasdasdas', '4', '1496214416', '1', '关键字1,关键字2,关键字3', '9', '233333', null);

-- ----------------------------
-- Table structure for THINK_EXAMINATION_ITEM_REL
-- ----------------------------
DROP TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL";
CREATE TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL" (
"EXAMINATION_ID" NUMBER(10) NOT NULL ,
"EXAMINATION_ITEM_ID" NUMBER(10) NOT NULL ,
"SCORE" NUMBER(4,1) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL" IS '试卷试题关联表';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM_REL"."EXAMINATION_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM_REL"."EXAMINATION_ITEM_ID" IS '试题ID';
COMMENT ON COLUMN "SCOTT"."THINK_EXAMINATION_ITEM_REL"."SCORE" IS '试题分数';

-- ----------------------------
-- Records of THINK_EXAMINATION_ITEM_REL
-- ----------------------------
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM_REL" VALUES ('18', '6', '10');
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM_REL" VALUES ('18', '7', '10');
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM_REL" VALUES ('18', '9', '10');
INSERT INTO "SCOTT"."THINK_EXAMINATION_ITEM_REL" VALUES ('18', '10', '10');

-- ----------------------------
-- Table structure for THINK_FILE_DOWNLOAD
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FILE_DOWNLOAD";
CREATE TABLE "SCOTT"."THINK_FILE_DOWNLOAD" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NOT NULL ,
"COURSE_ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"PATH" VARCHAR2(255 BYTE) NOT NULL ,
"TYPE" NUMBER(1) NOT NULL ,
"TIMELEN" NUMBER(11) DEFAULT 0  NOT NULL ,
"TIME_PERCENT" NUMBER(10,2) NULL ,
"CREATE_TIME" DATE NOT NULL ,
"STYLE" VARCHAR2(30 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_FILE_DOWNLOAD" IS '记录文件下载';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."COURSE_ID" IS '课程ID';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."NAME" IS '附件名称';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."PATH" IS '附件地址';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."TYPE" IS '附件类型 1视频VIDEO 2音频AUDIO 3文档DOC';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."TIMELEN" IS '学习时长（单位：秒），为视频音频专用';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."TIME_PERCENT" IS '视频音频播放进度百分比，示例：85.60';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."CREATE_TIME" IS '附件创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_FILE_DOWNLOAD"."STYLE" IS '文件后缀名';

-- ----------------------------
-- Records of THINK_FILE_DOWNLOAD
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_FRIENDS_CIRCLE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FRIENDS_CIRCLE";
CREATE TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"CONTENT" VARCHAR2(4000 BYTE) NOT NULL ,
"IMAGES" VARCHAR2(4000 BYTE) NULL ,
"PUBLISH_TIME" DATE NOT NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"PID" NUMBER(11) DEFAULT 0  NOT NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"STATE" NUMBER(1) DEFAULT 0  NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NOT NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" IS '工作圈发布/评论/回复评论表';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."ID" IS '主键自增（内容）ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."USER_ID" IS '发布者/回复人ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."CONTENT" IS '发布内容';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."IMAGES" IS '图片，多个图片英文逗号隔开';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."PUBLISH_TIME" IS '发布时间';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."STATUS" IS '审核状态  0待审核 1审核通过 2审核拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."PID" IS '上级评论ID，若是一级评论则为0';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."STATE" IS '状态 0未读 1已读';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_CIRCLE"."TYPE" IS '来源类型，0表示:APP 1表示:PC';

-- ----------------------------
-- Records of THINK_FRIENDS_CIRCLE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_FRIENDS_CIRCLE" VALUES ('19', '1', '<p>asasasasa</p>', null, TO_DATE('2017-05-31 09:53:42', 'YYYY-MM-DD HH24:MI:SS'), '0', '0', '0', '0', '0556229311', null, '1');
INSERT INTO "SCOTT"."THINK_FRIENDS_CIRCLE" VALUES ('21', '1', '<p>asasasasa</p>', null, TO_DATE('2017-05-31 09:54:30', 'YYYY-MM-DD HH24:MI:SS'), '0', '0', '0', '0', '0556702029', null, '1');
INSERT INTO "SCOTT"."THINK_FRIENDS_CIRCLE" VALUES ('22', '1', '<p>asadas</p><p><span style=";font-family:宋体;font-size:14px">array(&#39;exp&#39;,&quot;to_date(&#39;&quot;.date(&#39;Y-m-d H:i:s&#39;).&quot;&#39;,&#39;yy-mm-dd hh24:mi:ss&#39;)&quot;);</span></p><p><span style=";font-family:宋体;font-size:14px">array(&#39;exp&#39;,&quot;to_date(&#39;&quot;.date(&#39;Y-m-d H:i:s&#39;).&quot;&#39;,&#39;yy-mm-dd hh24:mi:ss&#39;)&quot;);</span></p><p><br/></p>', null, TO_DATE('2017-06-01 11:06:57', 'YYYY-MM-DD HH24:MI:SS'), '0', '0', '0', '0', '0564175604', null, '1');
INSERT INTO "SCOTT"."THINK_FRIENDS_CIRCLE" VALUES ('23', '1', '<p>asadas</p><p><span style=";font-family:宋体;font-size:14px">array(&#39;exp&#39;,&quot;to_date(&#39;&quot;.date(&#39;Y-m-d H:i:s&#39;).&quot;&#39;,&#39;yy-mm-dd hh24:mi:ss&#39;)&quot;);</span></p><p><span style=";font-family:宋体;font-size:14px">array(&#39;exp&#39;,&quot;to_date(&#39;&quot;.date(&#39;Y-m-d H:i:s&#39;).&quot;&#39;,&#39;yy-mm-dd hh24:mi:ss&#39;)&quot;);</span></p><p><br/></p>', null, TO_DATE('2017-06-01 11:07:06', 'YYYY-MM-DD HH24:MI:SS'), '0', '0', '0', '0', '0564261815', null, '1');

-- ----------------------------
-- Table structure for THINK_FRIENDS_COMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FRIENDS_COMMENT";
CREATE TABLE "SCOTT"."THINK_FRIENDS_COMMENT" (
"ID" NUMBER(11) NOT NULL ,
"CONTENT_ID" NUMBER(11) NOT NULL ,
"COMMENT_CONTENT" BLOB NULL ,
"AUTHOR_ID" NUMBER(11) NOT NULL ,
"COMMENT_TIME" DATE NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_FRIENDS_COMMENT" IS '工作圈评论表(暂时没用上)';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_COMMENT"."ID" IS '评论ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_COMMENT"."CONTENT_ID" IS '关联内容表ID(发布内容ID)';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_COMMENT"."COMMENT_CONTENT" IS '评论内容';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_COMMENT"."AUTHOR_ID" IS '评论者ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_COMMENT"."COMMENT_TIME" IS '评论时间';

-- ----------------------------
-- Records of THINK_FRIENDS_COMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_FRIENDS_NEWS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FRIENDS_NEWS";
CREATE TABLE "SCOTT"."THINK_FRIENDS_NEWS" (
"ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"PARISE_TOTAL" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_FRIENDS_NEWS" IS '朋友圈动态点赞数量表';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_NEWS"."ID" IS '朋友圈内容ID(表暂时不用上)';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_NEWS"."PARISE_TOTAL" IS '点赞总数';

-- ----------------------------
-- Records of THINK_FRIENDS_NEWS
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_FRIENDS_PRAISE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FRIENDS_PRAISE";
CREATE TABLE "SCOTT"."THINK_FRIENDS_PRAISE" (
"ID" NUMBER(11) NOT NULL ,
"CID" NUMBER(11) DEFAULT 0  NOT NULL ,
"PRAISE" NUMBER(11) NOT NULL ,
"PRAISE_TIME" DATE NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_PRAISE"."CID" IS '关联发布评论表ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_PRAISE"."PRAISE" IS '点赞数';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_PRAISE"."PRAISE_TIME" IS '点赞时间';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_PRAISE"."USER_ID" IS '点赞人ID';

-- ----------------------------
-- Records of THINK_FRIENDS_PRAISE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_FRIENDS_REPLYCOMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT";
CREATE TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" (
"ID" NUMBER(11) NOT NULL ,
"AUTHOR_ID" NUMBER(11) NOT NULL ,
"REPLY_CONTENT" VARCHAR2(100 BYTE) NOT NULL ,
"REPLY_TIME" DATE NOT NULL ,
"PID" NUMBER NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" IS '工作圈回复评论表(暂时没用上)';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_REPLYCOMMENT"."ID" IS '自增ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_REPLYCOMMENT"."AUTHOR_ID" IS '回复者ID';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_REPLYCOMMENT"."REPLY_CONTENT" IS '回复内容';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_REPLYCOMMENT"."REPLY_TIME" IS '回复时间';
COMMENT ON COLUMN "SCOTT"."THINK_FRIENDS_REPLYCOMMENT"."PID" IS '回复评论ID';

-- ----------------------------
-- Records of THINK_FRIENDS_REPLYCOMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_INTEGRATION_APPLY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_INTEGRATION_APPLY";
CREATE TABLE "SCOTT"."THINK_INTEGRATION_APPLY" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"APPLY_DESCRIPTION" VARCHAR2(255 BYTE) NOT NULL ,
"ATTACHMENT" VARCHAR2(255 BYTE) NOT NULL ,
"ADD_SCORE" VARCHAR2(255 BYTE) NOT NULL ,
"ADD_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"APPLY_TITLE" VARCHAR2(30 BYTE) NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_INTEGRATION_APPLY" IS '积分申请加分表';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."APPLY_DESCRIPTION" IS '申请说明';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."ATTACHMENT" IS '申请附件';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."ADD_SCORE" IS '申请加分分值';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."ADD_TIME" IS '申请时间';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."STATUS" IS '0表示待审核，1表示已通过，2表示已拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."APPLY_TITLE" IS '申请标题';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_APPLY"."OBJECTION" IS '拒绝理由';

-- ----------------------------
-- Records of THINK_INTEGRATION_APPLY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_INTEGRATION_RECORD
-- ----------------------------
DROP TABLE "SCOTT"."THINK_INTEGRATION_RECORD";
CREATE TABLE "SCOTT"."THINK_INTEGRATION_RECORD" (
"ID" NUMBER(11) NOT NULL ,
"TIME" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"DEPARTMENT" VARCHAR2(255 BYTE) NULL ,
"SCORE" NUMBER(11) DEFAULT 0  NOT NULL ,
"TYPE" VARCHAR2(255 BYTE) NULL ,
"DESCRIBE" VARCHAR2(255 BYTE) NULL ,
"APPLY_ID" NUMBER(11) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_INTEGRATION_RECORD" IS '积分流水表';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."TIME" IS '记录时间';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."USER_ID" IS '用户表的UID';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."DEPARTMENT" IS '部门(没用上)';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."SCORE" IS '获取积分';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."TYPE" IS '积分类型(字符串)';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."DESCRIBE" IS '积分说明';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RECORD"."APPLY_ID" IS '关联申请加分表ID';

-- ----------------------------
-- Records of THINK_INTEGRATION_RECORD
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_INTEGRATION_RULE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_INTEGRATION_RULE";
CREATE TABLE "SCOTT"."THINK_INTEGRATION_RULE" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NULL ,
"SCORE" NUMBER(11) DEFAULT 0  NOT NULL ,
"ONEDAY_SCORE" VARCHAR2(25 BYTE) NULL ,
"TYPE" VARCHAR2(25 BYTE) NULL ,
"SORT" NUMBER(11) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_INTEGRATION_RULE" IS '积分规则表';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RULE"."NAME" IS '积分规则名称';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RULE"."SCORE" IS '分值';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RULE"."ONEDAY_SCORE" IS '每天封顶积分(30天/月)';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RULE"."TYPE" IS '积分类型';
COMMENT ON COLUMN "SCOTT"."THINK_INTEGRATION_RULE"."SORT" IS '排序';

-- ----------------------------
-- Records of THINK_INTEGRATION_RULE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('1', '登陆系统', '2', '20', '系统达人', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('2', '查看系统消息', '5', '3', '系统达人', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('3', '头像/邮箱/个人简介设置成功', '2', '20/30', '系统达人', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('4', '查看积分规则', '10', '30/30', '系统达人', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('5', '查看学习目标', '5', '50', '系统达人', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('6', '阅读学习资料', '0', null, '爱学习', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('7', '关注课程', '2', '0', '爱学习', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('8', '对课程/讲师评价', '5', '20', '爱学习', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('9', '完成调研', '10', null, '爱学习', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('10', '观看课程中做笔记', '2', '1', '爱学习', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('11', '发布／上传学习资料', '2', '20', '乐分享', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('12', '发布的学习资料被关注', '2', null, '乐分享', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('13', '对他人学课的问题回复／评论', '2', '20', '乐分享', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('14', '发布工作圈状态', '2', '20', '乐分享', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('15', '回复/点赞工作圈', '2', '20', '乐分享', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('16', '正常考勤', '2', null, '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('17', '迟到/早退', '-10', null, '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('18', '旷课', '-50', null, '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('19', '选修一门课程(完成学习)', '5', null, '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('20', '学习任务到期未完成', '-30', null, '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('21', '按期完成学习任务', '20', '40', '任务范儿', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('22', '考试成绩第一名', '30', null, '我是学霸', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('23', '部门学分排名第一', '20', null, '我是学霸', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('24', '公司学分排名第一', '30', null, '我是学霸', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('25', '发布课程', '10', null, '好为人师', null);
INSERT INTO "SCOTT"."THINK_INTEGRATION_RULE" VALUES ('26', '完成授课', '20', null, '好为人师', null);

-- ----------------------------
-- Table structure for THINK_ITEM_INTERACTION
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ITEM_INTERACTION";
CREATE TABLE "SCOTT"."THINK_ITEM_INTERACTION" (
"ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NULL ,
"USER_ID" NUMBER NULL ,
"CONTENT" VARCHAR2(4000 BYTE) NULL ,
"IMAGES" VARCHAR2(4000 BYTE) NULL ,
"PUBLISH_TIME" DATE NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NULL ,
"PID" NUMBER(11) NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_ITEM_INTERACTION"."STATUS" IS '审核状态  0待审核 1审核通过 2审核拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_ITEM_INTERACTION"."PID" IS '上级评论ID，若是一级评论则为0';
COMMENT ON COLUMN "SCOTT"."THINK_ITEM_INTERACTION"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_ITEM_INTERACTION"."ORDERNO" IS '工单号';

-- ----------------------------
-- Records of THINK_ITEM_INTERACTION
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_JOBS_MANAGE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_JOBS_MANAGE";
CREATE TABLE "SCOTT"."THINK_JOBS_MANAGE" (
"ID" NUMBER(10) NOT NULL ,
"NAME" VARCHAR2(120 BYTE) NULL ,
"TISSUE_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_JOBS_MANAGE" IS '岗位管理';
COMMENT ON COLUMN "SCOTT"."THINK_JOBS_MANAGE"."NAME" IS '岗位名称';
COMMENT ON COLUMN "SCOTT"."THINK_JOBS_MANAGE"."TISSUE_ID" IS '组织架构权限ID';

-- ----------------------------
-- Records of THINK_JOBS_MANAGE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_JOBS_MANAGE" VALUES ('4', '后勤', '1');
INSERT INTO "SCOTT"."THINK_JOBS_MANAGE" VALUES ('5', '研发', '1');

-- ----------------------------
-- Table structure for THINK_JOBS_MANAGE_COPY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_JOBS_MANAGE_COPY";
CREATE TABLE "SCOTT"."THINK_JOBS_MANAGE_COPY" (
"ID" NUMBER(10) NOT NULL ,
"NAME" VARCHAR2(120 BYTE) NULL ,
"TISSUE_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_JOBS_MANAGE_COPY" IS '岗位管理';
COMMENT ON COLUMN "SCOTT"."THINK_JOBS_MANAGE_COPY"."ID" IS '自增ID(岗位ID)';
COMMENT ON COLUMN "SCOTT"."THINK_JOBS_MANAGE_COPY"."NAME" IS '岗位名称';
COMMENT ON COLUMN "SCOTT"."THINK_JOBS_MANAGE_COPY"."TISSUE_ID" IS '组织架构权限ID';

-- ----------------------------
-- Records of THINK_JOBS_MANAGE_COPY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_LEARNING_OBJECTIVES
-- ----------------------------
DROP TABLE "SCOTT"."THINK_LEARNING_OBJECTIVES";
CREATE TABLE "SCOTT"."THINK_LEARNING_OBJECTIVES" (
"ID" NUMBER NULL ,
"TYPEID" NUMBER NULL ,
"TISSUE_ID" NUMBER NULL ,
"JOB_ID" NUMBER NULL ,
"YEAR" NUMBER NULL ,
"JANUARY" NUMBER NULL ,
"FEBRUARY" NUMBER NULL ,
"MARCH" NUMBER NULL ,
"APRIL" NUMBER NULL ,
"MAY" NUMBER NULL ,
"JUNE" NUMBER NULL ,
"JULY" NUMBER NULL ,
"AUGUST" NUMBER NULL ,
"SEPTEMBER" NUMBER NULL ,
"OCTOBER" NUMBER NULL ,
"NOVEMBER" NUMBER NULL ,
"DECEMBER" NUMBER NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of THINK_LEARNING_OBJECTIVES
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_LECTURER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_LECTURER";
CREATE TABLE "SCOTT"."THINK_LECTURER" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"RESOURCE" VARCHAR2(255 BYTE) NOT NULL ,
"AGE" NUMBER(11) NOT NULL ,
"TYPE" NUMBER(11) NOT NULL ,
"LEVEL" VARCHAR2(50 BYTE) NOT NULL ,
"YEAR" NUMBER(3,1) NOT NULL ,
"PRICE" NUMBER(10,2) NOT NULL ,
"NUM" NUMBER(11) NOT NULL ,
"TIMES" NUMBER(11) NOT NULL ,
"ADDRESS" VARCHAR2(255 BYTE) NOT NULL ,
"DESC" VARCHAR2(255 BYTE) NOT NULL ,
"CERTIFICATE" VARCHAR2(255 BYTE) NOT NULL ,
"SID" NUMBER(11) NOT NULL ,
"CREATE_TIME" NUMBER(11) NOT NULL ,
"UPDATE_TIME" NUMBER(11) NOT NULL ,
"IS_DELETE" NUMBER(1) DEFAULT 0  NOT NULL ,
"UID" NUMBER(10) NOT NULL ,
"SCORE" NUMBER(2,1) DEFAULT 0.0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_LECTURER" IS '讲师表';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."NAME" IS '讲师姓名';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."USER_ID" IS '关联用户表ID';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."RESOURCE" IS '供应商(没用上)';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."AGE" IS '年龄';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."TYPE" IS '0表示内部讲师 1表示外部讲师';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."LEVEL" IS '讲师级别';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."YEAR" IS '授课年限';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."PRICE" IS '课酬/天';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."NUM" IS '授课次数';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."ADDRESS" IS '居住地';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."DESC" IS '讲师介绍';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."CERTIFICATE" IS '证书';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."SID" IS '供应商SID';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."UPDATE_TIME" IS '更改时间';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."IS_DELETE" IS '0表示未删除 1表示已删除 （没用上）';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."UID" IS '讲师的添加者';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER"."SCORE" IS '讲师评分';

-- ----------------------------
-- Records of THINK_LECTURER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_LECTURER_COMMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_LECTURER_COMMENT";
CREATE TABLE "SCOTT"."THINK_LECTURER_COMMENT" (
"ID" NUMBER(10) NOT NULL ,
"LECTURER_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL ,
"LECTURER_EVALUATION" NUMBER(1) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_LECTURER_COMMENT" IS '讲师评论表';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER_COMMENT"."ID" IS '讲师评论表';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER_COMMENT"."LECTURER_ID" IS '讲师ID';
COMMENT ON COLUMN "SCOTT"."THINK_LECTURER_COMMENT"."USER_ID" IS '学员ID';

-- ----------------------------
-- Records of THINK_LECTURER_COMMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_NEWS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_NEWS";
CREATE TABLE "SCOTT"."THINK_NEWS" (
"ID" NUMBER(10) NOT NULL ,
"TITLE" VARCHAR2(100 BYTE) NOT NULL ,
"TYPE" NUMBER(1) NULL ,
"CONTENT" VARCHAR2(4000 BYTE) NOT NULL ,
"CREATE_TIME" DATE NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"IMG" VARCHAR2(100 BYTE) DEFAULT ''  NULL ,
"TEMPLATE" NUMBER(1) NULL ,
"TISSUE_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_NEWS" IS '资讯表';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."TITLE" IS '标题';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."TYPE" IS '资讯类型,1-要闻，2-培训，3-通知，4-活动';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."CONTENT" IS '内容';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."IMG" IS '资讯图片';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."TEMPLATE" IS '资讯所属模板 1:公司资讯  2:综合资讯';
COMMENT ON COLUMN "SCOTT"."THINK_NEWS"."TISSUE_ID" IS '公司资讯所在 组织架构关联字段';

-- ----------------------------
-- Records of THINK_NEWS
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_OAUTH_USER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_OAUTH_USER";
CREATE TABLE "SCOTT"."THINK_OAUTH_USER" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"TYPE" NUMBER(1) DEFAULT 1  NOT NULL ,
"NICKNAME" VARCHAR2(30 BYTE) NOT NULL ,
"HEAD_IMG" VARCHAR2(255 BYTE) NOT NULL ,
"OPENID" VARCHAR2(40 BYTE) NOT NULL ,
"ACCESS_TOKEN" VARCHAR2(255 BYTE) NOT NULL ,
"CREATE_TIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"LAST_LOGIN_TIME" NUMBER(10) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."USER_ID" IS '关联的本站用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."TYPE" IS '类型 1：融云   2：友盟';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."NICKNAME" IS '第三方昵称';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."HEAD_IMG" IS '头像';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."OPENID" IS '第三方用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."ACCESS_TOKEN" IS 'ACCESS_TOKEN TOKEN';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."CREATE_TIME" IS '绑定时间';
COMMENT ON COLUMN "SCOTT"."THINK_OAUTH_USER"."LAST_LOGIN_TIME" IS '最后登录时间';

-- ----------------------------
-- Records of THINK_OAUTH_USER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_ORDER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_ORDER";
CREATE TABLE "SCOTT"."THINK_ORDER" (
"ID" NUMBER(11) NOT NULL ,
"ORDER_SN" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_ORDER"."ID" IS '订单主键';
COMMENT ON COLUMN "SCOTT"."THINK_ORDER"."ORDER_SN" IS '订单号';

-- ----------------------------
-- Records of THINK_ORDER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_PROJECT_BUDGET
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROJECT_BUDGET";
CREATE TABLE "SCOTT"."THINK_PROJECT_BUDGET" (
"PROJECT_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"OPTION_NAME" VARCHAR2(255 BYTE) NOT NULL ,
"AMOUNT" NUMBER(11,2) NULL ,
"ACTUAL_AMOUNT" NUMBER(11,2) NULL ,
"ID" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_PROJECT_BUDGET" IS '项目-培训预算关联表';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_BUDGET"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_BUDGET"."OPTION_NAME" IS '预算选项名称';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_BUDGET"."AMOUNT" IS '金额';

-- ----------------------------
-- Records of THINK_PROJECT_BUDGET
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_PROJECT_COURSE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROJECT_COURSE";
CREATE TABLE "SCOTT"."THINK_PROJECT_COURSE" (
"ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NULL ,
"COURSE_ID" VARCHAR2(60 BYTE) NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"CREDIT" NUMBER(4) DEFAULT 0  NULL ,
"LECTURER_ID" NUMBER(11) NULL ,
"LOCATION" VARCHAR2(100 BYTE) NULL ,
"IS_ATTACHMENT" NUMBER(11) DEFAULT 0  NULL ,
"COURSE_NAMES" VARCHAR2(255 BYTE) NULL ,
"MANAGER_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_PROJECT_COURSE" IS '项目课程表';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."COURSE_ID" IS '课程ＩＤ';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."CREDIT" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."LECTURER_ID" IS '讲师ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."LOCATION" IS '授课地址';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."IS_ATTACHMENT" IS '考勤 (0-关闭,1-开启)';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."COURSE_NAMES" IS '课程名称';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_COURSE"."MANAGER_ID" IS '负责人ID';

-- ----------------------------
-- Records of THINK_PROJECT_COURSE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_PROJECT_EXAMINATION
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROJECT_EXAMINATION";
CREATE TABLE "SCOTT"."THINK_PROJECT_EXAMINATION" (
"PROJECT_ID" NUMBER(11) NOT NULL ,
"TEST_ID" VARCHAR2(60 BYTE) NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"CREDITS" NUMBER(11) NULL ,
"TEST_LENGTH" NUMBER(11) NULL ,
"MANAGER_ID" NUMBER(11) NULL ,
"TEST_NAMES" VARCHAR2(100 BYTE) NULL ,
"CACHEID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_PROJECT_EXAMINATION" IS '项目考试表';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."PROJECT_ID" IS '项目ID ';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."TEST_ID" IS '试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."CREDITS" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."TEST_LENGTH" IS '考试时长';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."MANAGER_ID" IS '指定负责人ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."TEST_NAMES" IS '考试名称（添加时有修改过则有数据，否则为空）';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_EXAMINATION"."CACHEID" IS '线下考试 - 标记ID';

-- ----------------------------
-- Records of THINK_PROJECT_EXAMINATION
-- ----------------------------
INSERT INTO "SCOTT"."THINK_PROJECT_EXAMINATION" VALUES ('44', '0', TO_DATE('2017-06-01 16:54:58', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-02 16:54:58', 'YYYY-MM-DD HH24:MI:SS'), '15', '1440', '1', '线下01', '1');
INSERT INTO "SCOTT"."THINK_PROJECT_EXAMINATION" VALUES ('44', '18', TO_DATE('2017-06-01 16:55:26', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2017-06-03 16:55:28', 'YYYY-MM-DD HH24:MI:SS'), '40', '2880', '1', '试卷1101', null);

-- ----------------------------
-- Table structure for THINK_PROJECT_SUMMARY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROJECT_SUMMARY";
CREATE TABLE "SCOTT"."THINK_PROJECT_SUMMARY" (
"ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NULL ,
"ACTUAL_EXPENSES" NUMBER(30) NOT NULL ,
"SUMMARY" BLOB NULL ,
"ENCLOSURE" VARCHAR2(255 BYTE) NULL ,
"TOTAL_EXPENSES" NUMBER(30) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SUMMARY"."ACTUAL_EXPENSES" IS '项目实际费用';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SUMMARY"."ENCLOSURE" IS '附件';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SUMMARY"."TOTAL_EXPENSES" IS '项目合计费用';

-- ----------------------------
-- Records of THINK_PROJECT_SUMMARY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_PROJECT_SURVEY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROJECT_SURVEY";
CREATE TABLE "SCOTT"."THINK_PROJECT_SURVEY" (
"PROJECT_ID" NUMBER(11) NOT NULL ,
"SURVEY_ID" NUMBER(11) NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"MANAGER_ID" NUMBER(11) NULL ,
"CREDIT" NUMBER(4) NULL ,
"SURVEY_NAMES" VARCHAR2(100 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_PROJECT_SURVEY" IS '项目调研关联表';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."SURVEY_ID" IS '调研ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."MANAGER_ID" IS '关联组织架构被设置的负责人ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."CREDIT" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_PROJECT_SURVEY"."SURVEY_NAMES" IS '调研名称（修改过则有数据，否则为空)';

-- ----------------------------
-- Records of THINK_PROJECT_SURVEY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_PROVINCE_CITY_AREA
-- ----------------------------
DROP TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA";
CREATE TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA" (
"ID" NUMBER(11) NOT NULL ,
"PID" NUMBER(11) DEFAULT 0  NULL ,
"NAME" VARCHAR2(25 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA" IS '省-城市-地区表';
COMMENT ON COLUMN "SCOTT"."THINK_PROVINCE_CITY_AREA"."PID" IS '父级ID';
COMMENT ON COLUMN "SCOTT"."THINK_PROVINCE_CITY_AREA"."NAME" IS '城市名';

-- ----------------------------
-- Records of THINK_PROVINCE_CITY_AREA
-- ----------------------------
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('940', '934', '吴中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('941', '934', '相城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('942', '934', '常熟市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('943', '934', '张家港市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('944', '934', '昆山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('945', '934', '吴江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('946', '934', '太仓市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('947', '884', '南通');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('948', '947', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('949', '947', '崇川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('950', '947', '港闸区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('951', '947', '海安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('952', '947', '如东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('953', '947', '启东市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('954', '947', '如皋市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('955', '947', '通州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('956', '947', '海门市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('957', '884', '连云港');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('958', '957', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('959', '957', '连云区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('960', '957', '云台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('961', '957', '新浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('962', '957', '海州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('963', '957', '赣榆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('964', '957', '东海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('965', '957', '灌云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('966', '957', '灌南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('967', '884', '淮安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('968', '967', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('969', '967', '清河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('970', '967', '楚州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('971', '967', '淮阴区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('972', '967', '清浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('973', '967', '涟水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('974', '967', '洪泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('975', '967', '盱眙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('976', '967', '金湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('977', '884', '盐城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('978', '977', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('979', '977', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('980', '977', '响水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('981', '977', '滨海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('982', '977', '阜宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('983', '977', '射阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('984', '977', '建湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('985', '977', '盐都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('986', '977', '东台市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('987', '977', '大丰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('988', '884', '扬州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('989', '988', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('990', '988', '广陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('991', '988', '邗江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('992', '988', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('993', '988', '宝应县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('994', '988', '仪征市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('995', '988', '高邮市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('996', '988', '江都市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('997', '884', '镇江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('998', '997', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('999', '997', '京口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1000', '997', '润州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1001', '997', '丹徒县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1002', '997', '丹阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1003', '997', '扬中市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1004', '997', '句容市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1005', '884', '泰州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1006', '1005', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1007', '1005', '海陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1008', '1005', '高港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1009', '1005', '兴化市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1010', '1005', '靖江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1011', '1005', '泰兴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1012', '1005', '姜堰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1013', '884', '宿迁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1014', '1013', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1015', '1013', '宿城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1016', '1013', '宿豫县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1017', '1013', '沭阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1018', '1013', '泗阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1019', '1013', '泗洪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1020', '0', '浙江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1021', '1020', '杭州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1022', '1021', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1023', '1021', '上城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1024', '1021', '下城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1025', '1021', '江干区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1026', '1021', '拱墅区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1027', '1021', '西湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1028', '1021', '滨江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1029', '1021', '桐庐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1030', '1021', '淳安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1031', '1021', '萧山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1032', '1021', '建德市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1033', '1021', '富阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1034', '1021', '余杭市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1035', '1021', '临安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1036', '1020', '宁波');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1037', '1036', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1038', '1036', '海曙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1039', '1036', '江东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1040', '1036', '江北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1041', '1036', '北仑区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1042', '1036', '镇海区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1043', '1036', '象山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1044', '1036', '宁海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1045', '1036', '鄞  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1046', '1036', '余姚市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1047', '1036', '慈溪市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1048', '1036', '奉化市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1049', '1020', '温州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1050', '1049', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1051', '1049', '鹿城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1052', '1049', '龙湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1053', '1049', '瓯海区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1054', '1049', '洞头县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1055', '1049', '永嘉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1056', '1049', '平阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1057', '1049', '苍南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1058', '1049', '文成县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1059', '1049', '泰顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1060', '1049', '瑞安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1061', '1049', '乐清市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1062', '1020', '嘉兴');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1063', '1062', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1064', '1062', '秀城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1065', '1062', '秀洲区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1066', '1062', '嘉善县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1067', '1062', '海盐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1068', '1062', '海宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1069', '1062', '平湖市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1070', '1062', '桐乡市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1071', '1020', '湖州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1072', '1071', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1073', '1071', '德清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1074', '1071', '长兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1075', '1071', '安吉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1076', '1020', '绍兴');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1077', '1076', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1078', '1076', '越城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1079', '1076', '绍兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1080', '1076', '新昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1081', '1076', '诸暨市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1082', '1076', '上虞市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1083', '1076', '嵊州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1084', '1020', '金华');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1085', '1084', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1086', '1084', '婺城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1087', '1084', '金东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1088', '1084', '武义县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1089', '1084', '浦江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1090', '1084', '磐安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1091', '1084', '兰溪市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1092', '1084', '义乌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1093', '1084', '东阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1094', '1084', '永康市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1095', '1020', '衢州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1096', '1095', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1097', '1095', '柯城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1098', '1095', '衢  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1099', '1095', '常山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1100', '1095', '开化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1101', '1095', '龙游县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1102', '1095', '江山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1103', '1020', '舟山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1104', '1103', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1105', '1103', '定海区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1106', '1103', '普陀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1107', '1103', '岱山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1108', '1103', '嵊泗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1109', '1020', '台州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1110', '1109', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1111', '1109', '椒江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1112', '1109', '黄岩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1113', '1109', '路桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1114', '1109', '玉环县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1115', '1109', '三门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1116', '1109', '天台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1117', '1109', '仙居县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1118', '1109', '温岭市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1119', '1109', '临海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1120', '1020', '丽水');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1121', '1120', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1122', '1120', '莲都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1123', '1120', '青田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1124', '1120', '缙云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1125', '1120', '遂昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1126', '1120', '松阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1127', '1120', '云和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1128', '1120', '庆元县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1129', '1120', '景宁畲族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1130', '1120', '龙泉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1131', '0', '安徽');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1132', '1131', '合肥');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1133', '1132', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1134', '1132', '东市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1135', '1132', '中市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1136', '1132', '西市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1137', '1132', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1138', '1132', '长丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1139', '1132', '肥东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1140', '1132', '肥西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1141', '1131', '芜湖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1142', '1141', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1143', '1141', '镜湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1144', '1141', '马塘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1145', '1141', '新芜区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1146', '1141', '鸠江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1147', '1141', '芜湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1148', '1141', '繁昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1149', '1141', '南陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1150', '1131', '蚌埠');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1151', '1150', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1152', '1150', '东市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1153', '1150', '中市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1154', '1150', '西市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1155', '1150', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1156', '1150', '怀远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1157', '1150', '五河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1158', '1150', '固镇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1159', '1131', '淮南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1160', '1159', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1161', '1159', '大通区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1162', '1159', '田家庵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1163', '1159', '谢家集区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1164', '1159', '八公山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1165', '1159', '潘集区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1166', '1159', '凤台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1167', '1131', '马鞍山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1168', '1167', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1169', '1167', '金家庄区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1170', '1167', '花山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1171', '1167', '雨山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1172', '1167', '向山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1173', '1167', '当涂县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1174', '1131', '淮北');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1175', '1174', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1176', '1174', '杜集区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1177', '1174', '相山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1178', '1174', '烈山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1179', '1174', '濉溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1180', '1131', '铜陵');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1181', '1180', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1182', '1180', '铜官山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1183', '1180', '狮子山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1184', '1180', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1185', '1180', '铜陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1186', '1131', '安庆');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1187', '1186', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1188', '1186', '迎江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1189', '1186', '大观区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1190', '1186', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1191', '1186', '怀宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1192', '1186', '枞阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1193', '1186', '潜山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1194', '1186', '太湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1195', '1186', '宿松县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1196', '1186', '望江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1197', '1186', '岳西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1198', '1186', '桐城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1199', '1131', '黄山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1200', '1199', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1201', '1199', '屯溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1202', '1199', '黄山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1203', '1199', '徽州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1204', '1199', '歙  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1205', '1199', '休宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1206', '1199', '黟  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1207', '1199', '祁门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1208', '1131', '滁州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1209', '1208', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1210', '1208', '琅琊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1211', '1208', '南谯区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1212', '1208', '来安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1213', '1208', '全椒县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1214', '1208', '定远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1215', '1208', '凤阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1216', '1208', '天长市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1217', '1208', '明光市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1218', '1131', '阜阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1219', '1218', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1220', '1218', '颍州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1221', '1218', '颍东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1222', '1218', '颍泉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1223', '1218', '临泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1224', '1218', '太和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1225', '1218', '阜南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1226', '1218', '颍上县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1227', '1218', '界首市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1228', '1131', '宿州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1229', '1228', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1230', '1228', '墉桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1231', '1228', '砀山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1232', '1228', '萧  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1233', '1228', '灵璧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1234', '1228', '泗  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1235', '1131', '巢湖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1236', '1235', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1237', '1235', '居巢区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1238', '1235', '庐江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1239', '1235', '无为县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1240', '1235', '含山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1241', '1235', '和  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1242', '1131', '六安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1243', '1242', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1244', '1242', '金安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1245', '1242', '裕安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1246', '1242', '寿  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1247', '1242', '霍邱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1248', '1242', '舒城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1249', '1242', '金寨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1250', '1242', '霍山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1251', '1131', '亳州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1252', '1251', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1253', '1251', '谯城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1254', '1251', '涡阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1255', '1251', '蒙城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1256', '1251', '利辛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1257', '1131', '池州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1258', '1257', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1259', '1257', '贵池区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1260', '1257', '东至县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1261', '1257', '石台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1262', '1257', '青阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1263', '1131', '宣城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1264', '1263', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1265', '1263', '宣州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1266', '1263', '郎溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1267', '1263', '广德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1268', '1263', '泾  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1269', '1263', '绩溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1270', '1263', '旌德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1271', '1263', '宁国市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1272', '0', '福建');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1273', '1272', '福州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1274', '1273', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1275', '1273', '鼓楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1276', '1273', '台江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1277', '1273', '仓山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1278', '1273', '马尾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1279', '1273', '晋安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1280', '1273', '闽侯县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1281', '1273', '连江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1282', '1273', '罗源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1283', '1273', '闽清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1284', '1273', '永泰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1285', '1273', '平潭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1286', '1273', '福清市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1287', '1273', '长乐市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1288', '1272', '厦门');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1289', '1288', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1290', '1288', '鼓浪屿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1291', '1288', '思明区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1292', '1288', '开元区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1293', '1288', '杏林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1294', '1288', '湖里区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1295', '1288', '集美区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1296', '1288', '同安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1297', '1272', '莆田');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1298', '1297', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1299', '1297', '城厢区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1300', '1297', '涵江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1301', '1297', '莆田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1302', '1297', '仙游县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1303', '1272', '三明');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1304', '1303', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1305', '1303', '梅列区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1306', '1303', '三元区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1307', '1303', '明溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1308', '1303', '清流县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1309', '1303', '宁化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1310', '1303', '大田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1311', '1303', '尤溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1312', '1303', '沙  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1313', '1303', '将乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1314', '1303', '泰宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1315', '1303', '建宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1316', '1303', '永安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1317', '1272', '泉州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1318', '1317', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1319', '1317', '鲤城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1320', '1317', '丰泽区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1321', '1317', '洛江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1322', '1317', '泉港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1323', '1317', '惠安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1324', '1317', '安溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1325', '1317', '永春县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1326', '1317', '德化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1327', '1317', '金门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1328', '1317', '石狮市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1329', '1317', '晋江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1330', '1317', '南安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1331', '1272', '漳州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1332', '1331', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1333', '1331', '芗城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1334', '1331', '龙文区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1335', '1331', '云霄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1336', '1331', '漳浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1337', '1331', '诏安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1338', '1331', '长泰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1339', '1331', '东山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1340', '1331', '南靖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1341', '1331', '平和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1342', '1331', '华安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1343', '1331', '龙海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1344', '1272', '南平');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1345', '1344', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1346', '1344', '延平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1347', '1344', '顺昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1348', '1344', '浦城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1349', '1344', '光泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1350', '1344', '松溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1351', '1344', '政和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1352', '1344', '邵武市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1353', '1344', '武夷山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1354', '1344', '建瓯市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1355', '1344', '建阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1356', '1272', '龙岩');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1357', '1356', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1358', '1356', '新罗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1359', '1356', '长汀县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1360', '1356', '永定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1361', '1356', '上杭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1362', '1356', '武平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1363', '1356', '连城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1364', '1356', '漳平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1365', '1272', '宁德');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1366', '1365', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1367', '1365', '蕉城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1368', '1365', '霞浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1369', '1365', '古田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1370', '1365', '屏南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1371', '1365', '寿宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1372', '1365', '周宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1373', '1365', '柘荣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1374', '1365', '福安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1375', '1365', '福鼎市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1376', '0', '江西');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1377', '1376', '南昌');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1378', '1377', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1379', '1377', '东湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1380', '1377', '西湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1381', '1377', '青云谱区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1382', '1377', '湾里区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1383', '1377', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1384', '1377', '南昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1385', '1377', '新建县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1386', '1377', '安义县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1387', '1377', '进贤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1388', '1376', '景德镇');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1389', '1388', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1390', '1388', '昌江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1391', '1388', '珠山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1392', '1388', '浮梁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1393', '1388', '乐平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1394', '1376', '萍乡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1395', '1394', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1396', '1394', '安源区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1397', '1394', '湘东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1398', '1394', '莲花县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1399', '1394', '上栗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1400', '1394', '芦溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1401', '1376', '九江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1402', '1401', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1403', '1401', '庐山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1404', '1401', '浔阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1405', '1401', '九江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1406', '1401', '武宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1407', '1401', '修水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1408', '1401', '永修县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1409', '1401', '德安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1410', '1401', '星子县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1411', '1401', '都昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1412', '1401', '湖口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1413', '1401', '彭泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1414', '1401', '瑞昌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1415', '1376', '新余');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1416', '1415', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1417', '1415', '渝水区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1418', '1415', '分宜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1419', '1376', '鹰潭');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1420', '1419', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1421', '1419', '月湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1422', '1419', '余江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1423', '1419', '贵溪市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1424', '1376', '赣州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1425', '1424', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1426', '1424', '章贡区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1427', '1424', '赣  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1428', '1424', '信丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1429', '1424', '大余县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1430', '1424', '上犹县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1431', '1424', '崇义县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1432', '1424', '安远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1433', '1424', '龙南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1434', '1424', '定南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1435', '1424', '全南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1436', '1424', '宁都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1437', '1424', '于都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1438', '1424', '兴国县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1439', '1424', '会昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1440', '1424', '寻乌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1441', '1424', '石城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1442', '1424', '瑞金市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1443', '1424', '南康市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1444', '1376', '吉安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1445', '1444', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1446', '1444', '吉州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1447', '1444', '青原区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1448', '1444', '吉安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1449', '1444', '吉水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1450', '1444', '峡江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1451', '1444', '新干县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1452', '1444', '永丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1453', '1444', '泰和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1454', '1444', '遂川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1455', '1444', '万安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1456', '1444', '安福县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1457', '1444', '永新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1458', '1444', '井冈山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1459', '1376', '宜春');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1460', '1459', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1461', '1459', '袁州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1462', '1459', '奉新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1463', '1459', '万载县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1464', '1459', '上高县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1465', '1459', '宜丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1466', '1459', '靖安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1467', '1459', '铜鼓县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1468', '1459', '丰城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1469', '1459', '樟树市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1470', '1459', '高安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1471', '1376', '抚州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1472', '1471', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1473', '1471', '临川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1474', '1471', '南城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1475', '1471', '黎川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1476', '1471', '南丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1477', '1471', '崇仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1478', '1471', '乐安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1479', '1471', '宜黄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1480', '1471', '金溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1481', '1471', '资溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1482', '1471', '东乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1483', '1471', '广昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1484', '1376', '上饶');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1485', '1484', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1486', '1484', '信州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1487', '1484', '上饶县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1488', '1484', '广丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1489', '1484', '玉山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1490', '1484', '铅山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1491', '1484', '横峰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1492', '1484', '弋阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1493', '1484', '余干县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1494', '1484', '波阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1495', '1484', '万年县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1496', '1484', '婺源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1497', '1484', '德兴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1498', '0', '山东');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1499', '1498', '济南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1500', '1499', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1501', '1499', '历下区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1502', '1499', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1503', '1499', '槐荫区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1504', '1499', '天桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1505', '1499', '历城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1506', '1499', '长清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1507', '1499', '平阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1508', '1499', '济阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1509', '1499', '商河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1510', '1499', '章丘市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1511', '1498', '青岛');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1512', '1511', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1513', '1511', '市南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1514', '1511', '市北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1515', '1511', '四方区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1516', '1511', '黄岛区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1517', '1511', '崂山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1518', '1511', '李沧区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1519', '1511', '城阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1520', '1511', '胶州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1521', '1511', '即墨市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1522', '1511', '平度市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1523', '1511', '胶南市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1524', '1511', '莱西市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1525', '1498', '淄博');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1526', '1525', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1527', '1525', '淄川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1528', '1525', '张店区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1529', '1525', '博山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1530', '1525', '临淄区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1531', '1525', '周村区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1532', '1525', '桓台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1533', '1525', '高青县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1534', '1525', '沂源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1535', '1498', '枣庄');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1536', '1535', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1537', '1535', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1538', '1535', '薛城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1539', '1535', '峄城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1540', '1535', '台儿庄区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1541', '1535', '山亭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1542', '1535', '滕州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1543', '1498', '东营');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1544', '1543', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1545', '1543', '东营区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1546', '1543', '河口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1547', '1543', '垦利县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1548', '1543', '利津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1549', '1543', '广饶县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1550', '1498', '烟台');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1551', '1550', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1552', '1550', '芝罘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1553', '1550', '福山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1554', '1550', '牟平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1555', '1550', '莱山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1556', '1550', '长岛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1557', '1550', '龙口市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1558', '1550', '莱阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1559', '1550', '莱州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1560', '1550', '蓬莱市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1561', '1550', '招远市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1562', '1550', '栖霞市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1563', '1550', '海阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1564', '1498', '潍坊');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1565', '1564', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1566', '1564', '潍城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1567', '1564', '寒亭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1568', '1564', '坊子区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1569', '1564', '奎文区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1570', '1564', '临朐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1571', '1564', '昌乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1572', '1564', '青州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1573', '1564', '诸城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1574', '1564', '寿光市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1575', '1564', '安丘市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1576', '1564', '高密市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1577', '1564', '昌邑市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1', '0', '北京');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2', '1', '北京市辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3', '2', '东城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('4', '2', '西城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('5', '2', '崇文区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('6', '2', '宣武区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('7', '2', '朝阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('8', '2', '丰台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('9', '2', '石景山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('10', '2', '海淀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('11', '2', '门头沟区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('12', '2', '房山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('13', '2', '通州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('14', '2', '顺义区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('15', '2', '昌平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('16', '1', '北京县辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('17', '16', '大兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('18', '16', '平谷县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('19', '16', '怀柔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('20', '16', '密云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('21', '16', '延庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('22', '0', '天津');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('23', '22', '天津市辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('24', '23', '和平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('25', '23', '河东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('26', '23', '河西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('27', '23', '南开区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('28', '23', '河北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('29', '23', '红桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('30', '23', '塘沽区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('31', '23', '汉沽区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('32', '23', '大港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('33', '23', '东丽区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('34', '23', '西青区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('35', '23', '津南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('36', '23', '北辰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('37', '23', '武清区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('38', '22', '天津县辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('39', '38', '宁河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('40', '38', '静海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('41', '38', '宝坻县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('42', '38', '蓟  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('43', '0', '河北');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('44', '43', '石家庄');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('45', '44', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('46', '44', '长安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('47', '44', '桥东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('48', '44', '桥西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('49', '44', '新华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('50', '44', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('51', '44', '井陉矿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('52', '44', '井陉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('53', '44', '正定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('54', '44', '栾城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('55', '44', '行唐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('56', '44', '灵寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('57', '44', '高邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('58', '44', '深泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('59', '44', '赞皇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('60', '44', '无极县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('61', '44', '平山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('62', '44', '元氏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('63', '44', '赵  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('64', '44', '辛集市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('65', '44', '藁城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('66', '44', '晋州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('67', '44', '新乐市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('68', '44', '鹿泉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('69', '43', '唐山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('70', '69', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('71', '69', '路南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('72', '69', '路北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('73', '69', '古冶区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('74', '69', '开平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('75', '69', '新  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('76', '69', '丰润县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('77', '69', '滦  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('78', '69', '滦南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('79', '69', '乐亭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('80', '69', '迁西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('81', '69', '玉田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('82', '69', '唐海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('83', '69', '遵化市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('84', '69', '丰南市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('85', '69', '迁安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('86', '43', '秦皇岛');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('87', '86', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('88', '86', '海港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('89', '86', '山海关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('90', '86', '北戴河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('91', '86', '青龙满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('92', '86', '昌黎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('93', '86', '抚宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('94', '86', '卢龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('95', '43', '邯郸');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('96', '95', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('97', '95', '邯山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('98', '95', '丛台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('99', '95', '复兴区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('100', '95', '峰峰矿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('101', '95', '邯郸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('102', '95', '临漳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('103', '95', '成安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('104', '95', '大名县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('105', '95', '涉  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('106', '95', '磁  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('107', '95', '肥乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('108', '95', '永年县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('109', '95', '邱  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('110', '95', '鸡泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('111', '95', '广平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('112', '95', '馆陶县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('113', '95', '魏  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('114', '95', '曲周县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('115', '95', '武安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('116', '43', '邢台');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('117', '116', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('118', '116', '桥东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('119', '116', '桥西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('120', '116', '邢台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('121', '116', '临城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('122', '116', '内丘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('123', '116', '柏乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('124', '116', '隆尧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('125', '116', '任  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('126', '116', '南和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('127', '116', '宁晋县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('128', '116', '巨鹿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('129', '116', '新河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('130', '116', '广宗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('131', '116', '平乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('132', '116', '威  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('133', '116', '清河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('134', '116', '临西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('135', '116', '南宫市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('136', '116', '沙河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('137', '43', '保定');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('138', '137', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('139', '137', '新市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('140', '137', '北市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('141', '137', '南市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('142', '137', '满城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('143', '137', '清苑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('144', '137', '涞水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('145', '137', '阜平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('146', '137', '徐水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('147', '137', '定兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('148', '137', '唐  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('149', '137', '高阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('150', '137', '容城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('151', '137', '涞源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('152', '137', '望都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('153', '137', '安新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('154', '137', '易  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('155', '137', '曲阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('156', '137', '蠡  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('157', '137', '顺平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('158', '137', '博野县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('159', '137', '雄  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('160', '137', '涿州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('161', '137', '定州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('162', '137', '安国市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('163', '137', '高碑店市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('164', '43', '张家口');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('165', '164', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('166', '164', '桥东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('167', '164', '桥西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('168', '164', '宣化区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('169', '164', '下花园区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('170', '164', '宣化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('171', '164', '张北县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('172', '164', '康保县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('173', '164', '沽源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('174', '164', '尚义县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('175', '164', '蔚  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('176', '164', '阳原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('177', '164', '怀安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('178', '164', '万全县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('179', '164', '怀来县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('180', '164', '涿鹿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('181', '164', '赤城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('182', '164', '崇礼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('183', '43', '承德');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('184', '183', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('185', '183', '双桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('186', '183', '双滦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('187', '183', '鹰手营子矿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('188', '183', '承德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('189', '183', '兴隆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('190', '183', '平泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('191', '183', '滦平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('192', '183', '隆化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('193', '183', '丰宁满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('194', '183', '宽城满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('196', '43', '沧州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('197', '196', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('198', '196', '新华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('199', '196', '运河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('200', '196', '沧  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('201', '196', '青  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('202', '196', '东光县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('203', '196', '海兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('204', '196', '盐山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('205', '196', '肃宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('206', '196', '南皮县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('207', '196', '吴桥县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('208', '196', '献  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('209', '196', '孟村回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('210', '196', '泊头市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('211', '196', '任丘市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('212', '196', '黄骅市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('213', '196', '河间市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('214', '43', '廊坊');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('215', '214', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('216', '214', '安次区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('217', '214', '廊坊市广阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('218', '214', '固安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('219', '214', '永清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('220', '214', '香河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('221', '214', '大城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('222', '214', '文安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('223', '214', '大厂回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('224', '214', '霸州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('225', '214', '三河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('226', '43', '衡水');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('227', '226', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('228', '226', '桃城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('229', '226', '枣强县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('230', '226', '武邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('231', '226', '武强县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('232', '226', '饶阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('233', '226', '安平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('234', '226', '故城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('235', '226', '景  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('236', '226', '阜城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('237', '226', '冀州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('238', '226', '深州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('239', '0', '山西');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('240', '239', '太原');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('241', '240', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('242', '240', '小店区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('243', '240', '迎泽区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('244', '240', '杏花岭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('245', '240', '尖草坪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('246', '240', '万柏林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('247', '240', '晋源区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('248', '240', '清徐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('249', '240', '阳曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('250', '240', '娄烦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('251', '240', '古交市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('252', '239', '大同');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('253', '252', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('254', '252', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('255', '252', '矿  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('256', '252', '南郊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('257', '252', '新荣区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('258', '252', '阳高县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('259', '252', '天镇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('260', '252', '广灵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('261', '252', '灵丘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('262', '252', '浑源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('263', '252', '左云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('264', '252', '大同县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('265', '239', '阳泉');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('266', '265', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('267', '265', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('268', '265', '矿  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('269', '265', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('270', '265', '平定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('271', '265', '盂  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('272', '239', '长治');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('273', '272', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('274', '272', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('275', '272', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('276', '272', '长治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('277', '272', '襄垣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('278', '272', '屯留县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('279', '272', '平顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('280', '272', '黎城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('281', '272', '壶关县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('282', '272', '长子县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('283', '272', '武乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('284', '272', '沁  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('285', '272', '沁源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('286', '272', '潞城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('287', '239', '晋城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('288', '287', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('289', '287', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('290', '287', '沁水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('291', '287', '阳城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('292', '287', '陵川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('293', '287', '泽州县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('294', '287', '高平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('295', '239', '朔州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('296', '295', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('297', '295', '朔城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('298', '295', '平鲁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('299', '295', '山阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('300', '295', '应  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('301', '295', '右玉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('302', '295', '怀仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('303', '239', '晋中');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('304', '303', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('305', '303', '榆次区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('306', '303', '榆社县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('307', '303', '左权县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('308', '303', '和顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('309', '303', '昔阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('310', '303', '寿阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('311', '303', '太谷县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('312', '303', '祁  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('313', '303', '平遥县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('314', '303', '灵石县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('315', '303', '介休市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('316', '239', '运城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('317', '316', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('318', '316', '盐湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('319', '316', '临猗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('320', '316', '万荣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('321', '316', '闻喜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('322', '316', '稷山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('323', '316', '新绛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('324', '316', '绛  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('325', '316', '垣曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('326', '316', '夏  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('327', '316', '平陆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('328', '316', '芮城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('329', '316', '永济市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('330', '316', '河津市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('331', '239', '忻州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('332', '331', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('333', '331', '忻府区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('334', '331', '定襄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('335', '331', '五台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('336', '331', '代  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('337', '331', '繁峙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('338', '331', '宁武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('339', '331', '静乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('340', '331', '神池县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('341', '331', '五寨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('342', '331', '岢岚县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('343', '331', '河曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('344', '331', '保德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('345', '331', '偏关县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('346', '331', '原平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('347', '239', '临汾');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('348', '347', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('349', '347', '尧都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('350', '347', '曲沃县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('351', '347', '翼城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('352', '347', '襄汾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('353', '347', '洪洞县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('354', '347', '古  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('355', '347', '安泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('356', '347', '浮山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('357', '347', '吉  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('358', '347', '乡宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('359', '347', '大宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('360', '347', '隰  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('361', '347', '永和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('362', '347', '蒲  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('363', '347', '汾西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('364', '347', '侯马市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('365', '347', '霍州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('366', '239', '吕梁地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('367', '366', '孝义市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('368', '366', '离石市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('369', '366', '汾阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('370', '366', '文水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('371', '366', '交城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('372', '366', '兴  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('373', '366', '临  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('374', '366', '柳林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('375', '366', '石楼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('376', '366', '岚  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('377', '366', '方山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('378', '366', '中阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('379', '366', '交口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('380', '0', '内蒙古');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('381', '380', '呼和浩特');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('382', '381', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('383', '381', '新城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('384', '381', '回民区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('385', '381', '玉泉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('386', '381', '赛罕区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('387', '381', '土默特左旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('388', '381', '托克托县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('389', '381', '和林格尔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('390', '381', '清水河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('391', '381', '武川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('392', '380', '包头');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('393', '392', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('394', '392', '东河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('395', '392', '昆都伦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('396', '392', '青山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('397', '392', '石拐区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('398', '392', '白云矿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('399', '392', '九原区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('400', '392', '土默特右旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('401', '392', '固阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('403', '380', '乌海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('404', '403', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('405', '403', '海勃湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('406', '403', '海南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('407', '403', '乌达区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('408', '380', '赤峰');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('409', '408', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('410', '408', '红山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('411', '408', '元宝山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('412', '408', '松山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('413', '408', '阿鲁科尔沁旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('414', '408', '巴林左旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('415', '408', '巴林右旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('416', '408', '林西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('417', '408', '克什克腾旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('418', '408', '翁牛特旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('419', '408', '喀喇沁旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('420', '408', '宁城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('421', '408', '敖汉旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('422', '380', '通辽');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('423', '422', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('424', '422', '科尔沁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('425', '422', '科尔沁左翼中旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('426', '422', '科尔沁左翼后旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('427', '422', '开鲁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('428', '422', '库伦旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('429', '422', '奈曼旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('430', '422', '扎鲁特旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('431', '422', '霍林郭勒市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('432', '380', '呼伦贝尔盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('433', '432', '海拉尔市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('434', '432', '满洲里市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('435', '432', '扎兰屯市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('436', '432', '牙克石市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('437', '432', '根河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('438', '432', '额尔古纳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('439', '432', '阿荣旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('441', '432', '鄂伦春自治旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('442', '432', '鄂温克族自治旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('443', '432', '新巴尔虎右旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('444', '432', '新巴尔虎左旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('445', '432', '陈巴尔虎旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('446', '380', '兴安盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('447', '446', '乌兰浩特市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('448', '446', '阿尔山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('449', '446', '科尔沁右翼前旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('450', '446', '科尔沁右翼中旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('451', '446', '扎赉特旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('452', '446', '突泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('453', '380', '锡林郭勒盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('454', '453', '二连浩特市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('455', '453', '锡林浩特市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('456', '453', '阿巴嘎旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('457', '453', '苏尼特左旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('458', '453', '苏尼特右旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('459', '453', '东乌珠穆沁旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('460', '453', '西乌珠穆沁旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('461', '453', '太仆寺旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('462', '453', '镶黄旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('463', '453', '正镶白旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('464', '453', '正蓝旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('465', '453', '多伦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('466', '380', '乌兰察布盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('467', '466', '集宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('468', '466', '丰镇市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('469', '466', '卓资县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('470', '466', '化德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('471', '466', '商都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('472', '466', '兴和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('473', '466', '凉城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('474', '466', '察哈尔右翼前旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('475', '466', '察哈尔右翼中旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('476', '466', '察哈尔右翼后旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('477', '466', '四子王旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('478', '380', '伊克昭盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('479', '478', '东胜市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('480', '478', '达拉特旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('481', '478', '准格尔旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('482', '478', '鄂托克前旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('483', '478', '鄂托克旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('484', '478', '杭锦旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('485', '478', '乌审旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('486', '478', '伊金霍洛旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('487', '380', '巴彦淖尔盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('488', '487', '临河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('489', '487', '五原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('490', '487', '磴口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('491', '487', '乌拉特前旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('492', '487', '乌拉特中旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('493', '487', '乌拉特后旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('494', '487', '杭锦后旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('495', '380', '阿拉善盟');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('496', '495', '阿拉善左旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('497', '495', '阿拉善右旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('498', '495', '额济纳旗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('499', '0', '辽宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('500', '499', '沈阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('501', '500', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('502', '500', '和平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('503', '500', '沈河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('504', '500', '大东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('505', '500', '皇姑区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('506', '500', '铁西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('507', '500', '苏家屯区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('508', '500', '东陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('509', '500', '新城子区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('510', '500', '于洪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('511', '500', '辽中县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('512', '500', '康平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('513', '500', '法库县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('514', '500', '新民市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('515', '499', '大连');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('516', '515', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('517', '515', '中山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('518', '515', '西岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('519', '515', '沙河口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('520', '515', '甘井子区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('521', '515', '旅顺口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('522', '515', '金州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('523', '515', '长海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('524', '515', '瓦房店市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('525', '515', '普兰店市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('526', '515', '庄河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('527', '499', '鞍山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('528', '527', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('529', '527', '铁东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('530', '527', '铁西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('531', '527', '立山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('532', '527', '千山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('533', '527', '台安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('534', '527', '岫岩满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('535', '527', '海城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('536', '499', '抚顺');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('537', '536', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('538', '536', '新抚区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('539', '536', '东洲区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('540', '536', '望花区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('541', '536', '顺城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('542', '536', '抚顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('543', '536', '新宾满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('544', '536', '清原满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('545', '499', '本溪');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('546', '545', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('547', '545', '平山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('548', '545', '溪湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('549', '545', '明山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('550', '545', '南芬区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('551', '545', '本溪满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('552', '545', '桓仁满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('553', '499', '丹东');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('554', '553', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('555', '553', '元宝区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('556', '553', '振兴区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('557', '553', '振安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('558', '553', '宽甸满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('559', '553', '东港市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('560', '553', '凤城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('561', '499', '锦州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('562', '561', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('563', '561', '古塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('564', '561', '凌河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('565', '561', '太和区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('566', '561', '黑山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('567', '561', '义  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('568', '561', '凌海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('569', '561', '北宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('570', '499', '营口');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('571', '570', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('572', '570', '站前区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('573', '570', '西市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('574', '570', '鲅鱼圈区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('575', '570', '老边区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('576', '570', '盖州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('577', '570', '大石桥市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('578', '499', '阜新');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('579', '578', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('580', '578', '海州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('581', '578', '新邱区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('582', '578', '太平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('583', '578', '清河门区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('584', '578', '细河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('585', '578', '阜新蒙古族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('586', '578', '彰武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('587', '499', '辽阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('588', '587', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('589', '587', '白塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('590', '587', '文圣区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('591', '587', '宏伟区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('592', '587', '弓长岭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('593', '587', '太子河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('594', '587', '辽阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('595', '587', '灯塔市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('596', '499', '盘锦');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('597', '596', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('598', '596', '双台子区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('599', '596', '兴隆台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('600', '596', '大洼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('601', '596', '盘山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('602', '499', '铁岭');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('603', '602', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('604', '602', '银州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('605', '602', '清河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('606', '602', '铁岭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('607', '602', '西丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('608', '602', '昌图县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('609', '602', '铁法市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('610', '602', '开原市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('611', '499', '朝阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('612', '611', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('613', '611', '双塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('614', '611', '龙城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('615', '611', '朝阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('616', '611', '建平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('618', '611', '北票市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('619', '611', '凌源市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('620', '499', '葫芦岛');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('621', '620', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('622', '620', '连山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('623', '620', '龙港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('624', '620', '南票区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('625', '620', '绥中县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('626', '620', '建昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('627', '620', '兴城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('628', '0', '吉林');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('629', '628', '长春');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('630', '629', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('631', '629', '南关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('632', '629', '宽城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('633', '629', '朝阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('634', '629', '二道区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('635', '629', '绿园区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('636', '629', '双阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('637', '629', '农安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('638', '629', '九台市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('639', '629', '榆树市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('640', '629', '德惠市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('641', '628', '吉林');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('642', '641', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('643', '641', '昌邑区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('644', '641', '龙潭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('645', '641', '船营区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('646', '641', '丰满区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('647', '641', '永吉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('648', '641', '蛟河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('649', '641', '桦甸市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('650', '641', '舒兰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('651', '641', '磐石市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('652', '628', '四平');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('653', '652', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('654', '652', '铁西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('655', '652', '铁东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('656', '652', '梨树县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('657', '652', '伊通满族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('658', '652', '公主岭市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('659', '652', '双辽市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('660', '628', '辽源');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('661', '660', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('662', '660', '龙山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('663', '660', '西安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('664', '660', '东丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('665', '660', '东辽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('666', '628', '通化');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('667', '666', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('668', '666', '东昌区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('669', '666', '二道江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('670', '666', '通化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('671', '666', '辉南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('672', '666', '柳河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('673', '666', '梅河口市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('674', '666', '集安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('675', '628', '白山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('676', '675', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('677', '675', '八道江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('678', '675', '抚松县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('679', '675', '靖宇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('680', '675', '长白朝鲜族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('681', '675', '江源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('682', '675', '临江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('683', '628', '松原');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('684', '683', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('685', '683', '宁江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('687', '683', '长岭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('688', '683', '乾安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('689', '683', '扶余县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('690', '628', '白城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('691', '690', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('692', '690', '洮北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('693', '690', '镇赉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('694', '690', '通榆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('695', '690', '洮南市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('696', '690', '大安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('697', '628', '延边朝鲜族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('698', '697', '延吉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('699', '697', '图们市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('700', '697', '敦化市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('701', '697', '珲春市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('702', '697', '龙井市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('703', '697', '和龙市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('704', '697', '汪清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('705', '697', '安图县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('706', '0', '黑龙江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('707', '706', '哈尔滨');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('708', '707', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('709', '707', '道里区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('710', '707', '南岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('711', '707', '道外区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('712', '707', '太平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('713', '707', '香坊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('714', '707', '动力区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('715', '707', '平房区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('716', '707', '呼兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('717', '707', '依兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('718', '707', '方正县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('719', '707', '宾  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('720', '707', '巴彦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('721', '707', '木兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('722', '707', '通河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('723', '707', '延寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('724', '707', '阿城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('725', '707', '双城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('726', '707', '尚志市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('727', '707', '五常市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('728', '706', '齐齐哈尔');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('729', '728', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('730', '728', '龙沙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('731', '728', '建华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('732', '728', '铁锋区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('733', '728', '昂昂溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('734', '728', '富拉尔基区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('735', '728', '碾子山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('736', '728', '梅里斯达斡尔族区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('737', '728', '龙江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('738', '728', '依安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('739', '728', '泰来县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('740', '728', '甘南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('741', '728', '富裕县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('742', '728', '克山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('743', '728', '克东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('744', '728', '拜泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('745', '728', '讷河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('746', '706', '鸡西');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('747', '746', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('748', '746', '鸡冠区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('749', '746', '恒山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('750', '746', '滴道区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('751', '746', '梨树区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('752', '746', '城子河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('753', '746', '麻山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('754', '746', '鸡东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('755', '746', '虎林市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('756', '746', '密山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('757', '706', '鹤岗');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('758', '757', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('759', '757', '向阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('760', '757', '工农区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('761', '757', '南山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('762', '757', '兴安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('763', '757', '东山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('764', '757', '兴山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('765', '757', '萝北县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('766', '757', '绥滨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('767', '706', '双鸭山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('768', '767', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('769', '767', '尖山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('770', '767', '岭东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('771', '767', '四方台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('772', '767', '宝山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('773', '767', '集贤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('774', '767', '友谊县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('775', '767', '宝清县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('776', '767', '饶河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('777', '706', '大庆');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('778', '777', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('779', '777', '萨尔图区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('780', '777', '龙凤区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('781', '777', '让胡路区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('782', '777', '红岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('783', '777', '大同区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('784', '777', '肇州县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('785', '777', '肇源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('786', '777', '林甸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('788', '706', '伊春');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('789', '788', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('790', '788', '伊春区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('791', '788', '南岔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('792', '788', '友好区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('793', '788', '西林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('794', '788', '翠峦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('795', '788', '新青区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('796', '788', '美溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('797', '788', '金山屯区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('798', '788', '五营区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('799', '788', '乌马河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('800', '788', '汤旺河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('801', '788', '带岭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('802', '788', '乌伊岭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('803', '788', '红星区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('804', '788', '上甘岭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('805', '788', '嘉荫县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('806', '788', '铁力市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('807', '706', '佳木斯');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('808', '807', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('809', '807', '永红区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('810', '807', '向阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('811', '807', '前进区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('812', '807', '东风区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('813', '807', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('814', '807', '桦南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('815', '807', '桦川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('816', '807', '汤原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('817', '807', '抚远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('818', '807', '同江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('819', '807', '富锦市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('820', '706', '七台河');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('821', '820', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('822', '820', '新兴区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('823', '820', '桃山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('824', '820', '茄子河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('825', '820', '勃利县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('826', '706', '牡丹江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('827', '826', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('828', '826', '东安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('829', '826', '阳明区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('830', '826', '爱民区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('831', '826', '西安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('832', '826', '东宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('833', '826', '林口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('834', '826', '绥芬河市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('835', '826', '海林市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('836', '826', '宁安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('837', '826', '穆棱市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('838', '706', '黑河');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('839', '838', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('840', '838', '爱辉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('841', '838', '嫩江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('842', '838', '逊克县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('843', '838', '孙吴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('844', '838', '北安市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('845', '838', '五大连池市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('846', '706', '绥化');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('847', '846', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('848', '846', '北林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('849', '846', '望奎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('850', '846', '兰西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('851', '846', '青冈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('852', '846', '庆安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('853', '846', '明水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('854', '846', '绥棱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('855', '846', '安达市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('856', '846', '肇东市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('857', '846', '海伦市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('858', '706', '大兴安岭地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('859', '858', '呼玛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('860', '858', '塔河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('861', '858', '漠河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('862', '0', '上海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('863', '862', '上海市辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('864', '863', '黄浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('865', '863', '卢湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('866', '863', '徐汇区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('867', '863', '长宁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('868', '863', '静安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('869', '863', '普陀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('870', '863', '闸北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('871', '863', '虹口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('872', '863', '杨浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('873', '863', '闵行区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('874', '863', '宝山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('875', '863', '嘉定区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('876', '863', '浦东新区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('877', '863', '金山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('878', '863', '松江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('879', '863', '青浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('880', '862', '上海县辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('881', '880', '南汇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('882', '880', '奉贤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('883', '880', '崇明县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('884', '0', '江苏');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('885', '884', '南京');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('886', '885', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('887', '885', '玄武区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('888', '885', '白下区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('889', '885', '秦淮区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('890', '885', '建邺区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('891', '885', '鼓楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('892', '885', '下关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('893', '885', '浦口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('894', '885', '大厂区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('895', '885', '栖霞区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('896', '885', '雨花台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('897', '885', '江宁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('898', '885', '江浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('899', '885', '六合县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('900', '885', '溧水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('901', '885', '高淳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('902', '884', '无锡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('903', '902', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('904', '902', '崇安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('905', '902', '南长区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('906', '902', '北塘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('907', '902', '锡山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('908', '902', '惠山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('909', '902', '滨湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('910', '902', '江阴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('911', '902', '宜兴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('912', '884', '徐州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('913', '912', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('914', '912', '鼓楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('915', '912', '云龙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('916', '912', '九里区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('917', '912', '贾汪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('918', '912', '泉山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('919', '912', '丰  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('920', '912', '沛  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('921', '912', '铜山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('922', '912', '睢宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('923', '912', '新沂市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('924', '912', '邳州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('925', '884', '常州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('926', '925', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('927', '925', '天宁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('928', '925', '钟楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('929', '925', '戚墅堰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('930', '925', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('931', '925', '溧阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('932', '925', '金坛市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('933', '925', '武进市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('934', '884', '苏州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('935', '934', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('936', '934', '沧浪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('937', '934', '平江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('938', '934', '金阊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('939', '934', '虎丘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2214', '2210', '坡头区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2215', '2210', '麻章区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2216', '2210', '遂溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2217', '2210', '徐闻县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2218', '2210', '廉江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2219', '2210', '雷州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2220', '2210', '吴川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2221', '2143', '茂名');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2222', '2221', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2223', '2221', '茂南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2224', '2221', '电白县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2225', '2221', '高州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2226', '2221', '化州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2227', '2221', '信宜市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2228', '2143', '肇庆');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2229', '2228', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2230', '2228', '端州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2231', '2228', '鼎湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2232', '2228', '广宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2233', '2228', '怀集县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2234', '2228', '封开县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2235', '2228', '德庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2236', '2228', '高要市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2237', '2228', '四会市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2238', '2143', '惠州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2239', '2238', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2240', '2238', '惠城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2241', '2238', '博罗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2242', '2238', '惠东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2243', '2238', '龙门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2244', '2238', '惠阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2245', '2143', '梅州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2246', '2245', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2247', '2245', '梅江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2248', '2245', '梅  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2249', '2245', '大埔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2250', '2245', '丰顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2251', '2245', '五华县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2252', '2245', '平远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2253', '2245', '蕉岭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2254', '2245', '兴宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2255', '2143', '汕尾');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2256', '2255', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2257', '2255', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2258', '2255', '海丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2259', '2255', '陆河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2260', '2255', '陆丰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2261', '2143', '河源');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2262', '2261', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2263', '2261', '源城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2264', '2261', '紫金县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2265', '2261', '龙川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2266', '2261', '连平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2267', '2261', '和平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2268', '2261', '东源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2269', '2143', '阳江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2270', '2269', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2271', '2269', '江城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2272', '2269', '阳西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2273', '2269', '阳东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2274', '2269', '阳春市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2275', '2143', '清远');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2276', '2275', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2277', '2275', '清城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2278', '2275', '佛冈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2279', '2275', '阳山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2281', '2275', '连南瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2282', '2275', '清新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2283', '2275', '英德市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2284', '2275', '连州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2285', '2143', '东莞');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2286', '2285', '莞城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2287', '2285', '东城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2288', '2285', '南城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2289', '2285', '万江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2290', '2143', '中山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2291', '2290', '石岐区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2292', '2290', '东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2293', '2290', '西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2294', '2290', '南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2295', '2290', '五桂山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2296', '2143', '潮州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2297', '2296', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2298', '2296', '湘桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2299', '2296', '潮安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2300', '2296', '饶平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2301', '2143', '揭阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2302', '2301', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2303', '2301', '榕城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2304', '2301', '揭东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2305', '2301', '揭西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2306', '2301', '惠来县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2307', '2301', '普宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2308', '2143', '云浮');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2309', '2308', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2310', '2308', '云城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2311', '2308', '新兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2312', '2308', '郁南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2313', '2308', '云安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2314', '2308', '罗定市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2315', '0', '广西');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2316', '2315', '南宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2317', '2316', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2318', '2316', '兴宁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2319', '2316', '新城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2320', '2316', '城北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2321', '2316', '江南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2322', '2316', '永新区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2323', '2316', '市郊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2324', '2316', '邕宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2325', '2316', '武鸣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2326', '2315', '柳州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2327', '2326', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2328', '2326', '城中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2329', '2326', '鱼峰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2330', '2326', '柳南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2331', '2326', '柳北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2332', '2326', '市郊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2333', '2326', '柳江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2334', '2326', '柳城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2335', '2315', '桂林');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2336', '2335', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2337', '2335', '秀峰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2338', '2335', '叠彩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2339', '2335', '象山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2340', '2335', '七星区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2341', '2335', '雁山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2342', '2335', '阳朔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2343', '2335', '临桂县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2344', '2335', '灵川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2345', '2335', '全州县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2346', '2335', '兴安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2347', '2335', '永福县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2348', '2335', '灌阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2349', '2335', '龙胜各县自治区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2350', '2335', '资源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2351', '2335', '平乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2352', '2335', '荔蒲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2353', '2335', '恭城瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2354', '2315', '梧州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2355', '2354', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2356', '2354', '万秀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2357', '2354', '蝶山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2358', '2354', '市郊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2359', '2354', '苍梧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2360', '2354', '藤  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2361', '2354', '蒙山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2362', '2354', '岑溪市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2363', '2315', '北海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2364', '2363', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2365', '2363', '海城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2366', '2363', '银海区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2367', '2363', '铁山港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2368', '2363', '合浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2369', '2315', '防城港');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2370', '2369', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2371', '2369', '港口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2372', '2369', '防城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2373', '2369', '上思县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2374', '2369', '东兴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2375', '2315', '钦州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2376', '2375', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2377', '2375', '钦南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2378', '2375', '钦北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2379', '2375', '浦北县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2380', '2375', '灵山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2381', '2315', '贵港');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2382', '2381', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2383', '2381', '港北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2384', '2381', '港南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2385', '2381', '平南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2386', '2381', '桂平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2387', '2315', '玉林');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2388', '2387', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2389', '2387', '玉州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2390', '2387', '容  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2391', '2387', '陆川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2392', '2387', '博白县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2393', '2387', '兴业县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2394', '2387', '北流市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2395', '2315', '南宁地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2396', '2395', '凭祥市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2397', '2395', '横  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2398', '2395', '宾阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2399', '2395', '上林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2400', '2395', '隆安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2401', '2395', '马山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2402', '2395', '扶绥县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2403', '2395', '崇左县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2404', '2395', '大新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2405', '2395', '天等县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2406', '2395', '宁明县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2407', '2395', '龙州县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2408', '2315', '柳州地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2409', '2408', '合山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2410', '2408', '鹿寨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2411', '2408', '象州县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2412', '2408', '武宣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2413', '2408', '来宾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2414', '2408', '融安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2415', '2408', '三江侗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2416', '2408', '融水苗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2417', '2408', '金秀瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2418', '2408', '忻城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2419', '2315', '贺州地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2420', '2419', '贺州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2421', '2419', '昭平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2422', '2419', '钟山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2423', '2419', '富川瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2424', '2315', '百色地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2425', '2424', '百色市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2426', '2424', '田阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2427', '2424', '田东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2428', '2424', '平果县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2429', '2424', '德保县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2430', '2424', '靖西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2431', '2424', '那坡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2432', '2424', '凌云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2433', '2424', '乐业县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2434', '2424', '田林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2435', '2424', '隆林各族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2436', '2424', '西林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2437', '2315', '河池地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2438', '2437', '河池市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2439', '2437', '宜州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2440', '2437', '罗城仫佬族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2441', '2437', '环江毛南族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2442', '2437', '南丹县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2443', '2437', '天峨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2444', '2437', '凤山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2445', '2437', '东兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2446', '2437', '巴马瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2447', '2437', '都安瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2448', '2437', '大化瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2449', '0', '海南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2450', '2449', '海南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2451', '2450', '通什市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2452', '2450', '琼海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2453', '2450', '儋州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2454', '2450', '琼山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2455', '2450', '文昌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2456', '2450', '万宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2457', '2450', '东方市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2458', '2450', '定安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2459', '2450', '屯昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2460', '2450', '澄迈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2461', '2450', '临高县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2462', '2450', '白沙黎族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2463', '2450', '昌江黎族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2464', '2450', '乐东黎族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2465', '2450', '陵水黎族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2468', '2450', '西沙群岛');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2469', '2450', '南沙群岛');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2471', '2449', '海口');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2472', '2471', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2473', '2471', '振东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2474', '2471', '新华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2475', '2471', '秀英区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2476', '2449', '三亚');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2477', '2476', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2478', '0', '重庆');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2479', '2478', '重庆市辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2480', '2479', '万州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2481', '2479', '涪陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2482', '2479', '渝中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2483', '2479', '大渡口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2484', '2479', '江北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2485', '2479', '沙坪坝区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2486', '2479', '九龙坡区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2487', '2479', '南岸区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2488', '2479', '北碚区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2489', '2479', '万盛区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2490', '2479', '双桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2491', '2479', '渝北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2492', '2479', '巴南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2493', '2479', '黔江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2494', '2478', '重庆县辖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2495', '2494', '长寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2496', '2494', '綦江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2497', '2494', '潼南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2498', '2494', '铜梁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2499', '2494', '大足县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2500', '2494', '荣昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2501', '2494', '璧山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2502', '2494', '梁平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2503', '2494', '城口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2504', '2494', '丰都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2505', '2494', '垫江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2506', '2494', '武隆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2507', '2494', '忠  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2508', '2494', '开  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2509', '2494', '云阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2510', '2494', '奉节县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2511', '2494', '巫山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2512', '2494', '巫溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2513', '2494', '石柱土家族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2517', '2478', '重庆县级');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2518', '2517', '江津市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2519', '2517', '合川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2520', '2517', '永川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2521', '2517', '南川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2522', '0', '四川');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2523', '2522', '成都');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2524', '2523', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2525', '2523', '高新区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2526', '2523', '锦江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2527', '2523', '青羊区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2528', '2523', '金牛区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2529', '2523', '武侯区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2530', '2523', '成华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2531', '2523', '龙泉驿区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2532', '2523', '青白江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2533', '2523', '金堂县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2534', '2523', '双流县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2535', '2523', '温江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2536', '2523', '郫  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2537', '2523', '新都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2538', '2523', '大邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2539', '2523', '蒲江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2540', '2523', '新津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2541', '2523', '都江堰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2542', '2523', '彭州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2543', '2523', '邛崃市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2544', '2523', '崇州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2545', '2522', '自贡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2546', '2545', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2547', '2545', '自流井区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2548', '2545', '贡井区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2549', '2545', '大安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2550', '2545', '沿滩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2551', '2545', '荣  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2552', '2545', '富顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2553', '2522', '攀枝花');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2554', '2553', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2555', '2553', '东  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2556', '2553', '西  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2557', '2553', '仁和区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2558', '2553', '米易县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2559', '2553', '盐边县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2560', '2522', '泸州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2561', '2560', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2562', '2560', '江阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2563', '2560', '纳溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2564', '2560', '龙马潭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2565', '2560', '泸  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2566', '2560', '合江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2567', '2560', '叙永县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2568', '2560', '古蔺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2569', '2522', '德阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2570', '2569', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2571', '2569', '旌阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2572', '2569', '中江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2573', '2569', '罗江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2574', '2569', '广汉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2575', '2569', '什邡市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2576', '2569', '绵竹市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2577', '2522', '绵阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2578', '2577', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2579', '2577', '涪城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2580', '2577', '游仙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2581', '2577', '科学城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2582', '2577', '三台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2583', '2577', '盐亭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2584', '2577', '安  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2585', '2577', '梓潼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2586', '2577', '北川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2587', '2577', '平武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2588', '2577', '江油市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2589', '2522', '广元');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2590', '2589', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2591', '2589', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2592', '2589', '元坝区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2593', '2589', '朝天区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2594', '2589', '旺苍县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2595', '2589', '青川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2596', '2589', '剑阁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2597', '2589', '苍溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2598', '2522', '遂宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2599', '2598', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2600', '2598', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2601', '2598', '蓬溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2602', '2598', '射洪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2603', '2598', '大英县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2604', '2522', '内江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2605', '2604', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2606', '2604', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2607', '2604', '东兴区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2608', '2604', '威远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2609', '2604', '资中县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2610', '2604', '隆昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2611', '2522', '乐山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2612', '2611', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2613', '2611', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2614', '2611', '沙湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2615', '2611', '五通桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2616', '2611', '金口河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2617', '2611', '犍为县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2618', '2611', '井研县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2619', '2611', '夹江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2620', '2611', '沐川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2621', '2611', '峨边彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2622', '2611', '马边彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2623', '2611', '峨眉山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2624', '2522', '南充');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2625', '2624', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2626', '2624', '顺庆区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2627', '2624', '高坪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2628', '2624', '嘉陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2629', '2624', '南部县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2630', '2624', '营山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2631', '2624', '蓬安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2632', '2624', '仪陇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2633', '2624', '西充县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2634', '2624', '阆中市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2635', '2522', '眉山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2636', '2635', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2637', '2635', '东坡区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2638', '2635', '仁寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2639', '2635', '彭山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2640', '2635', '洪雅县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2641', '2635', '丹棱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2642', '2635', '青神县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2643', '2522', '宜宾');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2644', '2643', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2645', '2643', '翠屏区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2646', '2643', '宜宾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2647', '2643', '南溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2648', '2643', '江安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2649', '2643', '长宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2650', '2643', '高  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2651', '2643', '珙  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2652', '2643', '筠连县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2653', '2643', '兴文县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2654', '2643', '屏山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2655', '2522', '广安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2656', '2655', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2657', '2655', '广安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2658', '2655', '岳池县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2659', '2655', '武胜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2660', '2655', '邻水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2661', '2655', '华蓥市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2662', '2522', '达州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2663', '2662', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2664', '2662', '通川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2665', '2662', '达  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2666', '2662', '宣汉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2667', '2662', '开江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2668', '2662', '大竹县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2669', '2662', '渠  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2670', '2662', '万源市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2671', '2522', '雅安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2672', '2671', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2673', '2671', '雨城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2674', '2671', '名山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2675', '2671', '荥经县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2676', '2671', '汉源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2677', '2671', '石棉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2678', '2671', '天全县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2679', '2671', '芦山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2680', '2671', '宝兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2681', '2522', '巴中');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2682', '2681', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2683', '2681', '巴州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2684', '2681', '通江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2685', '2681', '南江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2686', '2681', '平昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2687', '2522', '资阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2688', '2687', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2689', '2687', '雁江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2690', '2687', '安岳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2691', '2687', '乐至县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2692', '2687', '简阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2694', '2693', '汶川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2695', '2693', '理  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2696', '2693', '茂  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2697', '2693', '松潘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2698', '2693', '九寨沟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2699', '2693', '金川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2700', '2693', '小金县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2701', '2693', '黑水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2702', '2693', '马尔康县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2703', '2693', '壤塘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2704', '2693', '阿坝县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2705', '2693', '若尔盖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2706', '2693', '红原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2707', '2522', '甘孜藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2708', '2707', '康定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2709', '2707', '泸定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2710', '2707', '丹巴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2711', '2707', '九龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2712', '2707', '雅江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2713', '2707', '道孚县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2714', '2707', '炉霍县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2715', '2707', '甘孜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2716', '2707', '新龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2717', '2707', '德格县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2718', '2707', '白玉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2719', '2707', '石渠县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2720', '2707', '色达县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2721', '2707', '理塘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2722', '2707', '巴塘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2723', '2707', '乡城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2724', '2707', '稻城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2725', '2707', '得荣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2726', '2522', '凉山彝族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2727', '2726', '西昌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2728', '2726', '木里藏族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2729', '2726', '盐源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2730', '2726', '德昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2731', '2726', '会理县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2732', '2726', '会东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2733', '2726', '宁南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2734', '2726', '普格县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2735', '2726', '布拖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2736', '2726', '金阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2737', '2726', '昭觉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2738', '2726', '喜德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2739', '2726', '冕宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2740', '2726', '越西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2741', '2726', '甘洛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2742', '2726', '美姑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2743', '2726', '雷波县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2744', '0', '贵州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2745', '2744', '贵阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2746', '2745', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2747', '2745', '南明区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2748', '2745', '云岩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2749', '2745', '花溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2750', '2745', '乌当区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2751', '2745', '白云区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2752', '2745', '小河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2753', '2745', '开阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2754', '2745', '息烽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2755', '2745', '修文县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2756', '2745', '清镇市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2757', '2744', '六盘水');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2758', '2757', '钟山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2759', '2757', '六枝特区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2760', '2757', '水城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2761', '2757', '盘  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2762', '2744', '遵义');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2763', '2762', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2764', '2762', '红花岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2765', '2762', '遵义县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2766', '2762', '桐梓县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2767', '2762', '绥阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2768', '2762', '正安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2771', '2762', '凤冈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2772', '2762', '湄潭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2773', '2762', '余庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2774', '2762', '习水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2775', '2762', '赤水市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2776', '2762', '仁怀市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2777', '2744', '安顺');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2778', '2777', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2779', '2777', '西秀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2780', '2777', '平坝县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2781', '2777', '普定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2785', '2744', '铜仁地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2786', '2785', '铜仁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2787', '2785', '江口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2788', '2785', '玉屏侗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2789', '2785', '石阡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2790', '2785', '思南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2792', '2785', '德江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2793', '2785', '沿河土家族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2794', '2785', '松桃苗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2795', '2785', '万山特区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2797', '2796', '兴义市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2798', '2796', '兴仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2799', '2796', '普安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2800', '2796', '晴隆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2801', '2796', '贞丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2802', '2796', '望谟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2803', '2796', '册亨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2804', '2796', '安龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2805', '2744', '毕节地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2806', '2805', '毕节市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2807', '2805', '大方县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2808', '2805', '黔西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2809', '2805', '金沙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2810', '2805', '织金县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2811', '2805', '纳雍县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2813', '2805', '赫章县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2815', '2814', '凯里市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2816', '2814', '黄平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2817', '2814', '施秉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2818', '2814', '三穗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2819', '2814', '镇远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2820', '2814', '岑巩县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2821', '2814', '天柱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2822', '2814', '锦屏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2823', '2814', '剑河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2824', '2814', '台江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2825', '2814', '黎平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2826', '2814', '榕江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2827', '2814', '从江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2828', '2814', '雷山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2829', '2814', '麻江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2830', '2814', '丹寨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2832', '2831', '都匀市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2833', '2831', '福泉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2834', '2831', '荔波县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2835', '2831', '贵定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2836', '2831', '瓮安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2837', '2831', '独山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2838', '2831', '平塘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2839', '2831', '罗甸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2840', '2831', '长顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2841', '2831', '龙里县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2842', '2831', '惠水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2843', '2831', '三都水族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2844', '0', '云南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2845', '2844', '昆明');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2846', '2845', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2847', '2845', '五华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2848', '2845', '盘龙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2849', '2845', '官渡区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2850', '2845', '西山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2851', '2845', '东川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2852', '2845', '呈贡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2853', '2845', '晋宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2854', '2845', '富民县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2855', '2845', '宜良县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2856', '2845', '石林彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2857', '2845', '嵩明县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2860', '2845', '安宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2861', '2844', '曲靖');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2862', '2861', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2863', '2861', '麒麟区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2864', '2861', '马龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2865', '2861', '陆良县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2866', '2861', '师宗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2867', '2861', '罗平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2868', '2861', '富源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2869', '2861', '会泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2870', '2861', '沾益县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2871', '2861', '宣威市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2872', '2844', '玉溪');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2873', '2872', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2874', '2872', '红塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2875', '2872', '江川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2876', '2872', '澄江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2877', '2872', '通海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2878', '2872', '华宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2879', '2872', '易门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2880', '2872', '峨山彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2883', '2844', '保山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2884', '2883', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2885', '2883', '隆阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2886', '2883', '施甸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2887', '2883', '腾冲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2888', '2883', '龙陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2889', '2883', '昌宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2890', '2844', '昭通地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2891', '2890', '昭通市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2892', '2890', '鲁甸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2893', '2890', '巧家县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2894', '2890', '盐津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2895', '2890', '大关县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2896', '2890', '永善县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2897', '2890', '绥江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2898', '2890', '镇雄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2899', '2890', '彝良县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2900', '2890', '威信县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2901', '2890', '水富县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2902', '2844', '楚雄彝族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2903', '2902', '楚雄市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2904', '2902', '双柏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2905', '2902', '牟定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2906', '2902', '南华县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2907', '2902', '姚安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2908', '2902', '大姚县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2909', '2902', '永仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2910', '2902', '元谋县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2911', '2902', '武定县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2912', '2902', '禄丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2914', '2913', '个旧市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2915', '2913', '开远市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2916', '2913', '蒙自县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2917', '2913', '屏边苗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2918', '2913', '建水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2919', '2913', '石屏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2920', '2913', '弥勒县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2921', '2913', '泸西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2922', '2913', '元阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2923', '2913', '红河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2925', '2913', '绿春县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2926', '2913', '河口瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2928', '2927', '文山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2929', '2927', '砚山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2930', '2927', '西畴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2931', '2927', '麻栗坡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2932', '2927', '马关县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2933', '2927', '丘北县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2934', '2927', '广南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2935', '2927', '富宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2936', '2844', '思茅地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2937', '2936', '思茅市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2939', '2936', '墨江哈尼族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2940', '2936', '景东彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2945', '2936', '澜沧拉祜族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2946', '2936', '西盟佤族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2948', '2947', '景洪市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2949', '2947', '勐海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2950', '2947', '勐腊县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2951', '2844', '大理白族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2952', '2951', '大理市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2953', '2951', '漾濞彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2954', '2951', '祥云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2955', '2951', '宾川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2956', '2951', '弥渡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2957', '2951', '南涧彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2959', '2951', '永平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2960', '2951', '云龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2961', '2951', '洱源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2962', '2951', '剑川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2963', '2951', '鹤庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2965', '2964', '瑞丽市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2966', '2964', '潞西市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2967', '2964', '梁河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2968', '2964', '盈江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2969', '2964', '陇川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2970', '2844', '丽江地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2971', '2970', '丽江纳西族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2972', '2970', '永胜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2973', '2970', '华坪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2974', '2970', '宁蒗彝族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2975', '2844', '怒江傈僳族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2976', '2975', '泸水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2977', '2975', '福贡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2980', '2844', '迪庆藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2981', '2980', '中甸县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2982', '2980', '德钦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2983', '2980', '维西傈僳族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2984', '2844', '临沧地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2985', '2984', '临沧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2986', '2984', '凤庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2987', '2984', '云  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2988', '2984', '永德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2989', '2984', '镇康县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2992', '2984', '沧源佤族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2993', '0', '西藏');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2994', '2993', '拉萨');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2995', '2994', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2996', '2994', '城关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2997', '2994', '林周县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2998', '2994', '当雄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2999', '2994', '尼木县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3000', '2994', '曲水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3001', '2994', '堆龙德庆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3002', '2994', '达孜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3003', '2994', '墨竹工卡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3004', '2993', '昌都地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3005', '3004', '昌都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3006', '3004', '江达县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3007', '3004', '贡觉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3008', '3004', '类乌齐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3009', '3004', '丁青县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3010', '3004', '察雅县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3011', '3004', '八宿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3012', '3004', '左贡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3013', '3004', '芒康县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3014', '3004', '洛隆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3015', '3004', '边坝县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3016', '2993', '山南地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3017', '3016', '乃东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3018', '3016', '扎囊县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3019', '3016', '贡嘎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3020', '3016', '桑日县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3021', '3016', '琼结县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3022', '3016', '曲松县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3023', '3016', '措美县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3024', '3016', '洛扎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3025', '3016', '加查县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3026', '3016', '隆子县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3027', '3016', '错那县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3028', '3016', '浪卡子县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3029', '2993', '日喀则地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3030', '3029', '日喀则市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3031', '3029', '南木林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3032', '3029', '江孜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3033', '3029', '定日县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3034', '3029', '萨迦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3035', '3029', '拉孜县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3036', '3029', '昂仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3037', '3029', '谢通门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3038', '3029', '白朗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3039', '3029', '仁布县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3040', '3029', '康马县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3041', '3029', '定结县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3042', '3029', '仲巴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3043', '3029', '亚东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3044', '3029', '吉隆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3045', '3029', '聂拉木县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3046', '3029', '萨嘎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3047', '3029', '岗巴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3048', '2993', '那曲地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3049', '3048', '那曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3050', '3048', '嘉黎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3051', '3048', '比如县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3052', '3048', '聂荣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3053', '3048', '安多县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3054', '3048', '申扎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3055', '3048', '索  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3056', '3048', '班戈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3057', '3048', '巴青县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3058', '3048', '尼玛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3059', '2993', '阿里地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3060', '3059', '普兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3061', '3059', '札达县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3062', '3059', '噶尔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3063', '3059', '日土县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3064', '3059', '革吉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3065', '3059', '改则县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3066', '3059', '措勤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3067', '2993', '林芝地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3068', '3067', '林芝县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3069', '3067', '工布江达县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3070', '3067', '米林县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3071', '3067', '墨脱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3072', '3067', '波密县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3073', '3067', '察隅县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3074', '3067', '朗  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3075', '0', '陕西');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3076', '3075', '西安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3077', '3076', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3078', '3076', '新城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3079', '3076', '碑林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3080', '3076', '莲湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3081', '3076', '灞桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3082', '3076', '未央区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3083', '3076', '雁塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3084', '3076', '阎良区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3085', '3076', '临潼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3086', '3076', '长安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3087', '3076', '蓝田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3088', '3076', '周至县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3089', '3076', '户  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3090', '3076', '高陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3091', '3075', '铜川');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3092', '3091', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3093', '3091', '王益区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3094', '3091', '印台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3095', '3091', '耀  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3096', '3091', '宜君县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3097', '3075', '宝鸡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3098', '3097', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3099', '3097', '渭滨区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3100', '3097', '金台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3101', '3097', '宝鸡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3102', '3097', '凤翔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3103', '3097', '岐山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3104', '3097', '扶风县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3105', '3097', '眉  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3106', '3097', '陇  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3107', '3097', '千阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3108', '3097', '麟游县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3109', '3097', '凤  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3110', '3097', '太白县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3111', '3075', '咸阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3112', '3111', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3113', '3111', '秦都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3114', '3111', '杨陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3115', '3111', '渭城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3116', '3111', '三原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3117', '3111', '泾阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3118', '3111', '乾  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3119', '3111', '礼泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3120', '3111', '永寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3121', '3111', '彬  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3122', '3111', '长武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3123', '3111', '旬邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3124', '3111', '淳化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3125', '3111', '武功县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3126', '3111', '兴平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3127', '3075', '渭南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3128', '3127', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3129', '3127', '临渭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3130', '3127', '华  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3131', '3127', '潼关县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3132', '3127', '大荔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3133', '3127', '合阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3134', '3127', '澄城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3135', '3127', '蒲城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3136', '3127', '白水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3137', '3127', '富平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3138', '3127', '韩城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3139', '3127', '华阴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3140', '3075', '延安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3141', '3140', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3142', '3140', '宝塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3143', '3140', '延长县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3144', '3140', '延川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3145', '3140', '子长县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3146', '3140', '安塞县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3147', '3140', '志丹县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3148', '3140', '吴旗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3149', '3140', '甘泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3150', '3140', '富  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3151', '3140', '洛川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3152', '3140', '宜川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3153', '3140', '黄龙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3154', '3140', '黄陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3155', '3075', '汉中');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3156', '3155', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3157', '3155', '汉台区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3158', '3155', '南郑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3159', '3155', '城固县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3160', '3155', '洋  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3161', '3155', '西乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3162', '3155', '勉  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3163', '3155', '宁强县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3164', '3155', '略阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3165', '3155', '镇巴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3166', '3155', '留坝县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3167', '3155', '佛坪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3168', '3075', '榆林');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3169', '3168', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3170', '3168', '榆阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3171', '3168', '神木县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3172', '3168', '府谷县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3173', '3168', '横山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3174', '3168', '靖边县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3175', '3168', '定边县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3176', '3168', '绥德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3177', '3168', '米脂县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3178', '3168', '佳  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3179', '3168', '吴堡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3180', '3168', '清涧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3181', '3168', '子洲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3182', '3075', '安康');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3183', '3182', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3184', '3182', '汉滨区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3185', '3182', '汉阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3186', '3182', '石泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3187', '3182', '宁陕县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3188', '3182', '紫阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3189', '3182', '岚皋县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3190', '3182', '平利县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3191', '3182', '镇坪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3192', '3182', '旬阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3193', '3182', '白河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3194', '3075', '商洛地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3195', '3194', '商州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3196', '3194', '洛南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3197', '3194', '丹凤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3198', '3194', '商南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3199', '3194', '山阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3200', '3194', '镇安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3201', '3194', '柞水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3202', '0', '甘肃');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3203', '3202', '兰州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3204', '3203', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3205', '3203', '城关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3206', '3203', '七里河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3207', '3203', '西固区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3208', '3203', '安宁区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3209', '3203', '红古区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3210', '3203', '永登县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3211', '3203', '皋兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3212', '3203', '榆中县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3213', '3202', '嘉峪关');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3214', '3213', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3215', '3202', '金昌');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3216', '3215', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3217', '3215', '金川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3218', '3215', '永昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3219', '3202', '白银');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3220', '3219', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3221', '3219', '白银区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3222', '3219', '平川区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3223', '3219', '靖远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3224', '3219', '会宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3225', '3219', '景泰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3226', '3202', '天水');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3227', '3226', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3228', '3226', '秦城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3229', '3226', '北道区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3230', '3226', '清水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3231', '3226', '秦安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3232', '3226', '甘谷县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3233', '3226', '武山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3234', '3226', '张家川回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3235', '3202', '酒泉地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3236', '3235', '玉门市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3237', '3235', '酒泉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3238', '3235', '敦煌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3239', '3235', '金塔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3240', '3235', '肃北蒙古族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3242', '3235', '安西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3243', '3202', '张掖地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3244', '3243', '张掖市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3245', '3243', '肃南裕固族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3246', '3243', '民乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3247', '3243', '临泽县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3248', '3243', '高台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3249', '3243', '山丹县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3250', '3202', '武威地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3251', '3250', '武威市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3252', '3250', '民勤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3253', '3250', '古浪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3254', '3250', '天祝藏族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3255', '3202', '定西地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3256', '3255', '定西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3257', '3255', '通渭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3258', '3255', '陇西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3259', '3255', '渭源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3260', '3255', '临洮县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3261', '3255', '漳  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3262', '3255', '岷  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3263', '3202', '陇南地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3264', '3263', '武都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3265', '3263', '宕昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3266', '3263', '成  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3267', '3263', '康  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3268', '3263', '文  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3269', '3263', '西和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3270', '3263', '礼  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3271', '3263', '两当县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3272', '3263', '徽  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3273', '3202', '平凉地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3274', '3273', '平凉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3275', '3273', '泾川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3276', '3273', '灵台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3277', '3273', '崇信县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3278', '3273', '华亭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3279', '3273', '庄浪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3280', '3273', '静宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3281', '3202', '庆阳地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3282', '3281', '西峰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3283', '3281', '庆阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3284', '3281', '环  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3285', '3281', '华池县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3286', '3281', '合水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3287', '3281', '正宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3288', '3281', '宁  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3289', '3281', '镇原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3290', '3202', '临夏回族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3291', '3290', '临夏市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3292', '3290', '临夏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3293', '3290', '康乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3294', '3290', '永靖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3295', '3290', '广河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3296', '3290', '和政县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3297', '3290', '东乡族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3299', '3202', '甘南藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3300', '3299', '合作市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3301', '3299', '临潭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3302', '3299', '卓尼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3303', '3299', '舟曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3304', '3299', '迭部县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3305', '3299', '玛曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3306', '3299', '碌曲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3307', '3299', '夏河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3308', '0', '青海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3309', '3308', '西宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3310', '3309', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3311', '3309', '城东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3312', '3309', '城中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3313', '3309', '城西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3314', '3309', '城北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3316', '3309', '湟中县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3317', '3309', '湟源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3318', '3308', '海东地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3319', '3318', '平安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3321', '3318', '乐都县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3322', '3318', '互助土族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3323', '3318', '化隆回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3324', '3318', '循化撒拉族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3325', '3308', '海北藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3326', '3325', '门源回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3327', '3325', '祁连县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3328', '3325', '海晏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3329', '3325', '刚察县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3330', '3308', '黄南藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3331', '3330', '同仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3332', '3330', '尖扎县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3333', '3330', '泽库县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3334', '3330', '河南蒙古族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3335', '3308', '海南藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3336', '3335', '共和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3337', '3335', '同德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3338', '3335', '贵德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3339', '3335', '兴海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3340', '3335', '贵南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3341', '3308', '果洛藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3342', '3341', '玛沁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3343', '3341', '班玛县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3344', '3341', '甘德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3345', '3341', '达日县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3346', '3341', '久治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3347', '3341', '玛多县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3348', '3308', '玉树藏族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3349', '3348', '玉树县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3350', '3348', '杂多县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3351', '3348', '称多县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3352', '3348', '治多县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3353', '3348', '囊谦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3354', '3348', '曲麻莱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3356', '3355', '格尔木市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3357', '3355', '德令哈市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3358', '3355', '乌兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3359', '3355', '都兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3360', '3355', '天峻县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3361', '0', '宁夏');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3362', '3361', '银川');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3363', '3362', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3364', '3362', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3365', '3362', '新城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3366', '3362', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3367', '3362', '永宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3368', '3362', '贺兰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3369', '3361', '石嘴山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3370', '3369', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3371', '3369', '大武口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3372', '3369', '石嘴山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3373', '3369', '石炭井区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3374', '3369', '平罗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3375', '3369', '陶乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3376', '3369', '惠农县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3377', '3361', '吴忠');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3378', '3377', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3379', '3377', '利通区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3380', '3377', '中卫县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3381', '3377', '中宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3382', '3377', '盐池县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3383', '3377', '同心县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3384', '3377', '青铜峡市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3385', '3377', '灵武市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3386', '3361', '固原地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3387', '3386', '固原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3388', '3386', '海原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3389', '3386', '西吉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3390', '3386', '隆德县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3391', '3386', '泾源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3392', '3386', '彭阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3393', '0', '新疆');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3394', '3393', '乌鲁木齐');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3395', '3394', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3396', '3394', '天山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3397', '3394', '沙依巴克区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3398', '3394', '新市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3399', '3394', '水磨沟区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3400', '3394', '头屯河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3401', '3394', '南泉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3402', '3394', '东山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3403', '3394', '乌鲁木齐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3404', '3393', '克拉玛依');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3405', '3404', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3406', '3404', '独山子区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3407', '3404', '克拉玛依区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3408', '3404', '白碱滩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3409', '3404', '乌尔禾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3410', '3393', '吐鲁番地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3411', '3410', '吐鲁番市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3412', '3410', '鄯善县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3413', '3410', '托克逊县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3414', '3393', '哈密地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3415', '3414', '哈密市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3417', '3414', '伊吾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3418', '3393', '昌吉回族自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3419', '3418', '昌吉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3420', '3418', '阜康市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3421', '3418', '米泉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3422', '3418', '呼图壁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3423', '3418', '玛纳斯县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3424', '3418', '奇台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3425', '3418', '吉木萨尔县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3426', '3418', '木垒哈萨克自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3428', '3427', '博乐市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3429', '3427', '精河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3430', '3427', '温泉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3432', '3431', '库尔勒市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3433', '3431', '轮台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3434', '3431', '尉犁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3435', '3431', '若羌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3436', '3431', '且末县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3437', '3431', '焉耆回族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3438', '3431', '和静县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3439', '3431', '和硕县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3440', '3431', '博湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3441', '3393', '阿克苏地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3442', '3441', '阿克苏市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3443', '3441', '温宿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3444', '3441', '库车县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3445', '3441', '沙雅县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3446', '3441', '新和县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3447', '3441', '拜城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3448', '3441', '乌什县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3449', '3441', '阿瓦提县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3450', '3441', '柯坪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3452', '3451', '阿图什市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3453', '3451', '阿克陶县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3454', '3451', '阿合奇县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3455', '3451', '乌恰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3456', '3393', '喀什地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3457', '3456', '喀什市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3458', '3456', '疏附县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3459', '3456', '疏勒县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3460', '3456', '英吉沙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3461', '3456', '泽普县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3462', '3456', '莎车县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3463', '3456', '叶城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3464', '3456', '麦盖提县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3465', '3456', '岳普湖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3466', '3456', '伽师县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3467', '3456', '巴楚县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3469', '3393', '和田地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3470', '3469', '和田市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3471', '3469', '和田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3472', '3469', '墨玉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3473', '3469', '皮山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3474', '3469', '洛浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3475', '3469', '策勒县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3476', '3469', '于田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3477', '3469', '民丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3478', '3393', '伊犁哈萨克自治州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3479', '3478', '奎屯市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3480', '3393', '伊犁地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3481', '3480', '伊宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3482', '3480', '伊宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3484', '3480', '霍城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3485', '3480', '巩留县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3486', '3480', '新源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3487', '3480', '昭苏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3488', '3480', '特克斯县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3489', '3480', '尼勒克县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3490', '3393', '塔城地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3491', '3490', '塔城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3492', '3490', '乌苏市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3493', '3490', '额敏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3494', '3490', '沙湾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3495', '3490', '托里县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3496', '3490', '裕民县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3498', '3393', '阿勒泰地区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3499', '3498', '阿勒泰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3500', '3498', '布尔津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3501', '3498', '富蕴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3502', '3498', '福海县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3503', '3498', '哈巴河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3504', '3498', '青河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3505', '3498', '吉木乃县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3506', '3393', '省直辖行政单位');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3507', '3506', '石河子市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3508', '0', '台湾');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3509', '0', '香港');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('3510', '0', '澳门');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1578', '1498', '济宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1579', '1578', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1580', '1578', '市中区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1581', '1578', '任城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1582', '1578', '微山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1583', '1578', '鱼台县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1584', '1578', '金乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1585', '1578', '嘉祥县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1586', '1578', '汶上县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1587', '1578', '泗水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1588', '1578', '梁山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1589', '1578', '曲阜市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1590', '1578', '兖州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1591', '1578', '邹城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1592', '1498', '泰安');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1593', '1592', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1594', '1592', '泰山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1595', '1592', '岱岳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1596', '1592', '宁阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1597', '1592', '东平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1598', '1592', '新泰市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1599', '1592', '肥城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1600', '1498', '威海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1601', '1600', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1602', '1600', '环翠区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1603', '1600', '文登市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1604', '1600', '荣成市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1605', '1600', '乳山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1606', '1498', '日照');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1607', '1606', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1608', '1606', '东港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1609', '1606', '五莲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1610', '1606', '莒  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1611', '1498', '莱芜');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1612', '1611', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1613', '1611', '莱城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1614', '1611', '钢城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1615', '1498', '临沂');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1616', '1615', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1617', '1615', '兰山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1618', '1615', '罗庄区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1619', '1615', '河东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1620', '1615', '沂南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1621', '1615', '郯城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1622', '1615', '沂水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1623', '1615', '苍山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1624', '1615', '费  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1625', '1615', '平邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1626', '1615', '莒南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1627', '1615', '蒙阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1628', '1615', '临沭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1629', '1498', '德州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1630', '1629', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1631', '1629', '德城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1632', '1629', '陵  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1633', '1629', '宁津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1634', '1629', '庆云县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1635', '1629', '临邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1636', '1629', '齐河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1637', '1629', '平原县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1638', '1629', '夏津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1639', '1629', '武城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1640', '1629', '乐陵市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1641', '1629', '禹城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1642', '1498', '聊城');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1643', '1642', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1644', '1642', '东昌府区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1645', '1642', '阳谷县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1646', '1642', '莘  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1647', '1642', '茌平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1648', '1642', '东阿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1649', '1642', '冠  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1650', '1642', '高唐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1651', '1642', '临清市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1652', '1498', '滨州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1653', '1652', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1654', '1652', '滨城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1655', '1652', '惠民县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1656', '1652', '阳信县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1657', '1652', '无棣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1658', '1652', '沾化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1659', '1652', '博兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1660', '1652', '邹平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1661', '1498', '菏泽');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1662', '1661', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1663', '1661', '牡丹区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1664', '1661', '曹  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1665', '1661', '单  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1666', '1661', '成武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1667', '1661', '巨野县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1668', '1661', '郓城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1669', '1661', '鄄城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1670', '1661', '定陶县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1671', '1661', '东明县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1672', '0', '河南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1673', '1672', '郑州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1674', '1673', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1675', '1673', '中原区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1676', '1673', '二七区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1677', '1673', '管城回族区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1678', '1673', '金水区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1679', '1673', '上街区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1680', '1673', '邙山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1681', '1673', '中牟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1682', '1673', '巩义市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1683', '1673', '荥阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1684', '1673', '新密市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1685', '1673', '新郑市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1686', '1673', '登封市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1687', '1672', '开封');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1688', '1687', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1689', '1687', '龙亭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1690', '1687', '顺河回族区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1691', '1687', '鼓楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1692', '1687', '南关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1693', '1687', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1694', '1687', '杞  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1695', '1687', '通许县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1696', '1687', '尉氏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1697', '1687', '开封县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1698', '1687', '兰考县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1699', '1672', '洛阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1700', '1699', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1701', '1699', '老城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1702', '1699', '西工区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1703', '1699', '廛河回族区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1704', '1699', '涧西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1705', '1699', '吉利区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1706', '1699', '洛龙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1707', '1699', '孟津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1708', '1699', '新安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1709', '1699', '栾川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1710', '1699', '嵩  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1711', '1699', '汝阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1712', '1699', '宜阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1713', '1699', '洛宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1714', '1699', '伊川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1715', '1699', '偃师市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1716', '1672', '平顶山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1717', '1716', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1718', '1716', '新华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1719', '1716', '卫东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1720', '1716', '石龙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1721', '1716', '湛河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1722', '1716', '宝丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1723', '1716', '叶  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1724', '1716', '鲁山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1725', '1716', '郏  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1726', '1716', '舞钢市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1727', '1716', '汝州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1728', '1672', '安阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1729', '1728', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1730', '1728', '文峰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1731', '1728', '北关区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1732', '1728', '铁西区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1733', '1728', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1734', '1728', '安阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1735', '1728', '汤阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1736', '1728', '滑  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1737', '1728', '内黄县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1738', '1728', '林州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1739', '1672', '鹤壁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1740', '1739', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1741', '1739', '鹤山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1742', '1739', '山城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1743', '1739', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1744', '1739', '浚  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1745', '1739', '淇  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1746', '1672', '新乡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1747', '1746', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1748', '1746', '红旗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1749', '1746', '新华区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1750', '1746', '北站区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1751', '1746', '郊  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1752', '1746', '新乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1753', '1746', '获嘉县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1754', '1746', '原阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1755', '1746', '延津县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1756', '1746', '封丘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1757', '1746', '长垣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1758', '1746', '卫辉市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1759', '1746', '辉县市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1760', '1672', '焦作');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1761', '1760', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1762', '1760', '解放区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1763', '1760', '中站区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1764', '1760', '马村区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1765', '1760', '山阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1766', '1760', '修武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1767', '1760', '博爱县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1768', '1760', '武陟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1769', '1760', '温  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1770', '1760', '济源市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1771', '1760', '沁阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1772', '1760', '孟州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1773', '1672', '濮阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1774', '1773', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1775', '1773', '市  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1776', '1773', '清丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1777', '1773', '南乐县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1778', '1773', '范  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1779', '1773', '台前县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1780', '1773', '濮阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1781', '1672', '许昌');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1782', '1781', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1783', '1781', '魏都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1784', '1781', '许昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1785', '1781', '鄢陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1786', '1781', '襄城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1787', '1781', '禹州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1788', '1781', '长葛市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1789', '1672', '漯河');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1790', '1789', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1791', '1789', '源汇区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1792', '1789', '舞阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1793', '1789', '临颍县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1794', '1789', '郾城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1795', '1672', '三门峡');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1796', '1795', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1797', '1795', '湖滨区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1798', '1795', '渑池县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1799', '1795', '陕  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1800', '1795', '卢氏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1801', '1795', '义马市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1802', '1795', '灵宝市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1803', '1672', '南阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1804', '1803', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1805', '1803', '宛城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1806', '1803', '卧龙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1807', '1803', '南召县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1808', '1803', '方城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1809', '1803', '西峡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1810', '1803', '镇平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1811', '1803', '内乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1812', '1803', '淅川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1813', '1803', '社旗县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1814', '1803', '唐河县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1815', '1803', '新野县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1816', '1803', '桐柏县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1817', '1803', '邓州市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1818', '1672', '商丘');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1819', '1818', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1820', '1818', '梁园区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1821', '1818', '睢阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1822', '1818', '民权县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1823', '1818', '睢  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1824', '1818', '宁陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1825', '1818', '柘城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1826', '1818', '虞城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1827', '1818', '夏邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1828', '1818', '永城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1829', '1672', '信阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1830', '1829', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1831', '1829', '师河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1832', '1829', '平桥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1833', '1829', '罗山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1834', '1829', '光山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1835', '1829', '新  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1836', '1829', '商城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1837', '1829', '固始县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1838', '1829', '潢川县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1839', '1829', '淮滨县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1840', '1829', '息  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1841', '1672', '周口');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1842', '1841', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1843', '1841', '川汇区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1844', '1841', '扶沟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1845', '1841', '西华县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1846', '1841', '商水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1847', '1841', '沈丘县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1848', '1841', '郸城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1849', '1841', '淮阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1850', '1841', '太康县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1851', '1841', '鹿邑县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1852', '1841', '项城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1853', '1672', '驻马店');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1854', '1853', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1855', '1853', '驿城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1856', '1853', '西平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1857', '1853', '上蔡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1858', '1853', '平舆县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1859', '1853', '正阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1860', '1853', '确山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1861', '1853', '泌阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1862', '1853', '汝南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1863', '1853', '遂平县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1864', '1853', '新蔡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1865', '0', '湖北');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1866', '1865', '武汉');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1867', '1866', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1868', '1866', '江岸区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1869', '1866', '江汉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1870', '1866', '乔口区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1871', '1866', '汉阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1872', '1866', '武昌区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1873', '1866', '青山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1874', '1866', '洪山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1875', '1866', '东西湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1876', '1866', '汉南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1877', '1866', '蔡甸区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1878', '1866', '江夏区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1879', '1866', '黄陂区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1880', '1866', '新洲区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1881', '1865', '黄石');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1882', '1881', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1883', '1881', '黄石港区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1884', '1881', '石灰窑区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1885', '1881', '下陆区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1886', '1881', '铁山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1887', '1881', '阳新县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1888', '1881', '大冶市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1889', '1865', '十堰');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1890', '1889', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1891', '1889', '茅箭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1892', '1889', '张湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1893', '1889', '郧  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1894', '1889', '郧西县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1895', '1889', '竹山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1896', '1889', '竹溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1897', '1889', '房  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1898', '1889', '丹江口市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1899', '1865', '宜昌');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1900', '1899', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1901', '1899', '西陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1902', '1899', '伍家岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1903', '1899', '点军区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1904', '1899', '虎亭区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1905', '1899', '宜昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1906', '1899', '远安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1907', '1899', '兴山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1908', '1899', '秭归县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1909', '1899', '长阳土家族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1910', '1899', '五峰土家族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1911', '1899', '宜都市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1912', '1899', '当阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1913', '1899', '枝江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1914', '1865', '襄樊');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1915', '1914', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1916', '1914', '襄城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1917', '1914', '樊城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1918', '1914', '襄阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1919', '1914', '南漳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1920', '1914', '谷城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1921', '1914', '保康县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1922', '1914', '老河口市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1923', '1914', '枣阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1924', '1914', '宜城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1925', '1865', '鄂州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1926', '1925', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1927', '1925', '梁子湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1928', '1925', '华容区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1929', '1925', '鄂城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1930', '1865', '荆门');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1931', '1930', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1932', '1930', '东宝区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1933', '1930', '京山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1934', '1930', '沙洋县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1935', '1930', '钟祥市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1936', '1865', '孝感');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1937', '1936', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1938', '1936', '孝南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1939', '1936', '孝昌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1940', '1936', '大悟县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1941', '1936', '云梦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1942', '1936', '应城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1943', '1936', '安陆市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1944', '1936', '汉川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1945', '1865', '荆州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1946', '1945', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1947', '1945', '沙市区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1948', '1945', '荆州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1949', '1945', '公安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1950', '1945', '监利县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1951', '1945', '江陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1952', '1945', '石首市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1953', '1945', '洪湖市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1954', '1945', '松滋市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1955', '1865', '黄冈');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1956', '1955', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1957', '1955', '黄州区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1958', '1955', '团风县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1959', '1955', '红安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1960', '1955', '罗田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1961', '1955', '英山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1962', '1955', '浠水县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1963', '1955', '蕲春县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1964', '1955', '黄梅县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1965', '1955', '麻城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1966', '1955', '武穴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1967', '1865', '咸宁');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1968', '1967', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1969', '1967', '咸安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1970', '1967', '嘉鱼县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1971', '1967', '通城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1972', '1967', '崇阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1973', '1967', '通山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1974', '1967', '赤壁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1975', '1865', '随州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1976', '1975', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1977', '1975', '曾都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1978', '1975', '广水市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1980', '1979', '恩施市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1981', '1979', '利川市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1982', '1979', '建始县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1983', '1979', '巴东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1984', '1979', '宣恩县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1985', '1979', '咸丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1986', '1979', '来凤县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1987', '1979', '鹤峰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1988', '1865', '省直辖行政单位');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1989', '1988', '仙桃市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1990', '1988', '潜江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1991', '1988', '天门市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1992', '1988', '神农架林区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1993', '0', '湖南');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1994', '1993', '长沙');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1995', '1994', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1996', '1994', '芙蓉区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1997', '1994', '天心区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1998', '1994', '岳麓区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('1999', '1994', '开福区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2000', '1994', '雨花区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2001', '1994', '长沙县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2002', '1994', '望城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2003', '1994', '宁乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2004', '1994', '浏阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2005', '1993', '株洲');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2006', '2005', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2007', '2005', '荷塘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2008', '2005', '芦淞区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2009', '2005', '石峰区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2010', '2005', '天元区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2011', '2005', '株洲县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2012', '2005', '攸  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2013', '2005', '茶陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2014', '2005', '炎陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2015', '2005', '醴陵市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2016', '1993', '湘潭');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2017', '2016', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2018', '2016', '雨湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2019', '2016', '岳塘区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2020', '2016', '湘潭县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2021', '2016', '湘乡市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2022', '2016', '韶山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2023', '1993', '衡阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2024', '2023', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2025', '2023', '江东区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2026', '2023', '城南区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2027', '2023', '城北区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2028', '2023', '郊   区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2029', '2023', '南岳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2030', '2023', '衡阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2031', '2023', '衡南县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2032', '2023', '衡山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2033', '2023', '衡东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2034', '2023', '祁东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2035', '2023', '耒阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2036', '2023', '常宁市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2037', '1993', '邵阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2038', '2037', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2039', '2037', '双清区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2040', '2037', '大祥区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2041', '2037', '北塔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2042', '2037', '邵东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2043', '2037', '新邵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2044', '2037', '邵阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2045', '2037', '隆回县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2046', '2037', '洞口县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2047', '2037', '绥宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2048', '2037', '新宁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2049', '2037', '城步苗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2050', '2037', '武冈市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2051', '1993', '岳阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2052', '2051', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2053', '2051', '岳阳楼区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2054', '2051', '云溪区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2055', '2051', '君山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2056', '2051', '岳阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2057', '2051', '华容县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2058', '2051', '湘阴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2059', '2051', '平江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2060', '2051', '汨罗市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2061', '2051', '临湘市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2062', '1993', '常德');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2063', '2062', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2064', '2062', '武陵区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2065', '2062', '鼎城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2066', '2062', '安乡县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2067', '2062', '汉寿县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2068', '2062', '澧  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2069', '2062', '临澧县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2070', '2062', '桃源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2071', '2062', '石门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2072', '2062', '津市市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2073', '1993', '张家界');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2074', '2073', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2075', '2073', '永定区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2076', '2073', '武陵源区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2077', '2073', '慈利县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2078', '2073', '桑植县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2079', '1993', '益阳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2080', '2079', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2081', '2079', '资阳区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2082', '2079', '赫山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2083', '2079', '南  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2084', '2079', '桃江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2085', '2079', '安化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2086', '2079', '沅江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2087', '1993', '郴州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2088', '2087', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2089', '2087', '北湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2090', '2087', '苏仙区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2091', '2087', '桂阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2092', '2087', '宜章县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2093', '2087', '永兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2094', '2087', '嘉禾县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2095', '2087', '临武县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2096', '2087', '汝城县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2097', '2087', '桂东县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2098', '2087', '安仁县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2099', '2087', '资兴市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2100', '1993', '永州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2101', '2100', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2102', '2100', '芝山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2103', '2100', '冷水滩区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2104', '2100', '祁阳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2105', '2100', '东安县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2106', '2100', '双牌县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2107', '2100', '道  县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2108', '2100', '江永县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2109', '2100', '宁远县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2110', '2100', '蓝山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2111', '2100', '新田县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2112', '2100', '江华瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2113', '1993', '怀化');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2114', '2113', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2115', '2113', '鹤城区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2116', '2113', '中方县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2117', '2113', '沅陵县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2118', '2113', '辰溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2119', '2113', '溆浦县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2120', '2113', '会同县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2121', '2113', '麻阳苗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2122', '2113', '新晃侗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2123', '2113', '芷江侗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2125', '2113', '通道侗族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2126', '2113', '洪江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2127', '1993', '娄底');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2128', '2127', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2129', '2127', '娄星区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2130', '2127', '双峰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2131', '2127', '新化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2132', '2127', '冷水江市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2133', '2127', '涟源市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2135', '2134', '吉首市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2136', '2134', '泸溪县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2137', '2134', '凤凰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2138', '2134', '花垣县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2139', '2134', '保靖县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2140', '2134', '古丈县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2141', '2134', '永顺县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2142', '2134', '龙山县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2143', '0', '广东');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2144', '2143', '广州');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2145', '2144', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2146', '2144', '东山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2147', '2144', '荔湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2148', '2144', '越秀区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2149', '2144', '海珠区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2150', '2144', '天河区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2151', '2144', '芳村区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2152', '2144', '白云区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2153', '2144', '黄埔区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2154', '2144', '番禺区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2155', '2144', '花都区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2156', '2144', '增城市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2157', '2144', '从化市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2158', '2143', '韶关');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2159', '2158', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2160', '2158', '北江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2161', '2158', '武江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2162', '2158', '浈江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2163', '2158', '曲江县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2164', '2158', '始兴县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2165', '2158', '仁化县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2166', '2158', '翁源县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2167', '2158', '乳源瑶族自治县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2168', '2158', '新丰县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2169', '2158', '乐昌市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2170', '2158', '南雄市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2171', '2143', '深圳');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2172', '2171', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2173', '2171', '罗湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2174', '2171', '福田区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2175', '2171', '南山区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2176', '2171', '宝安区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2177', '2171', '龙岗区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2178', '2171', '盐田区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2179', '2143', '珠海');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2180', '2179', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2181', '2179', '香洲区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2182', '2179', '斗门县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2183', '2143', '汕头');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2184', '2183', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2185', '2183', '达濠区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2186', '2183', '龙湖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2187', '2183', '金园区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2188', '2183', '升平区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2189', '2183', '河浦区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2190', '2183', '南澳县');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2191', '2183', '潮阳市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2192', '2183', '澄海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2193', '2143', '佛山');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2194', '2193', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2195', '2193', '城  区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2196', '2193', '石湾区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2197', '2193', '顺德市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2198', '2193', '南海市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2199', '2193', '三水市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2200', '2193', '高明市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2201', '2143', '江门');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2202', '2201', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2203', '2201', '蓬江区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2204', '2201', '江海区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2205', '2201', '台山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2206', '2201', '新会市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2207', '2201', '开平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2208', '2201', '鹤山市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2209', '2201', '恩平市');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2210', '2143', '湛江');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2211', '2210', '市辖区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2212', '2210', '赤坎区');
INSERT INTO "SCOTT"."THINK_PROVINCE_CITY_AREA" VALUES ('2213', '2210', '霞山区');

-- ----------------------------
-- Table structure for THINK_QUESTION_BANK
-- ----------------------------
DROP TABLE "SCOTT"."THINK_QUESTION_BANK";
CREATE TABLE "SCOTT"."THINK_QUESTION_BANK" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_QUESTION_BANK"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_QUESTION_BANK"."NAME" IS '题库名字';

-- ----------------------------
-- Records of THINK_QUESTION_BANK
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_RESEARCH
-- ----------------------------
DROP TABLE "SCOTT"."THINK_RESEARCH";
CREATE TABLE "SCOTT"."THINK_RESEARCH" (
"ID" NUMBER(11) NOT NULL ,
"RESEARCH_NAME" VARCHAR2(255 BYTE) NULL ,
"SURVEY_ID" NUMBER(11) NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"AUDIT_TIME" NUMBER DEFAULT 0  NOT NULL ,
"CREDITS" NUMBER(11) DEFAULT 0  NOT NULL ,
"AUDIT_STATE" NUMBER(11) DEFAULT 0  NOT NULL ,
"CREATE_TIME" DATE NULL ,
"CREATE_USER" NUMBER(11) NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_RESEARCH" IS '调研管理';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."RESEARCH_NAME" IS '调研名称';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."SURVEY_ID" IS '调研问卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."CREDITS" IS '学分';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."AUDIT_STATE" IS '审核状态(0-待审核,1-已通过,2-已拒绝,3-删除)';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."CREATE_USER" IS '创建者ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH"."OBJECTION" IS '拒绝理由';

-- ----------------------------
-- Records of THINK_RESEARCH
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_RESEARCH_ANSWER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_RESEARCH_ANSWER";
CREATE TABLE "SCOTT"."THINK_RESEARCH_ANSWER" (
"ID" NUMBER(11) NOT NULL ,
"RESEARCH_ID" NUMBER(11) NOT NULL ,
"SURVEY_ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"SURVEY_ANSWER" VARCHAR2(50 BYTE) NOT NULL ,
"CLASSIFICATION" NUMBER(1) NOT NULL ,
"QUESTION_NUMBER" NUMBER(11) NULL ,
"DESCRIBE" BLOB NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."RESEARCH_ID" IS '调研ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."SURVEY_ID" IS '问卷';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."SURVEY_ANSWER" IS '考试答案';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."CLASSIFICATION" IS '题型（1单选题 2多选题 3判断题）';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."QUESTION_NUMBER" IS '题号';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ANSWER"."DESCRIBE" IS '描述';

-- ----------------------------
-- Records of THINK_RESEARCH_ANSWER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_RESEARCH_ATTENDANCE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_RESEARCH_ATTENDANCE";
CREATE TABLE "SCOTT"."THINK_RESEARCH_ATTENDANCE" (
"SURVEY_ID" NUMBER(11) NULL ,
"RESEARCH_ID" NUMBER(11) NULL ,
"USER_ID" NUMBER(11) NULL ,
"STATE" VARCHAR2(255 BYTE) NULL ,
"COMMIT_TIME" DATE NULL ,
"ID" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ATTENDANCE"."SURVEY_ID" IS '问卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ATTENDANCE"."RESEARCH_ID" IS '调研管理关联ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ATTENDANCE"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ATTENDANCE"."STATE" IS '参加调研状态(0-待参加，1-已参加,2-删除,3-已逾期)';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_ATTENDANCE"."COMMIT_TIME" IS '问卷题交时间';

-- ----------------------------
-- Records of THINK_RESEARCH_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_RESEARCH_COLLECT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_RESEARCH_COLLECT";
CREATE TABLE "SCOTT"."THINK_RESEARCH_COLLECT" (
"NAME" VARCHAR2(50 BYTE) NULL ,
"PROJECT_NAME" VARCHAR2(50 BYTE) NULL ,
"PRINCIPAL" VARCHAR2(50 BYTE) NULL ,
"START_TIME" DATE NULL ,
"END_TIME" DATE NULL ,
"SHOULD" NUMBER(10) NULL ,
"REAL" NUMBER(10) NULL ,
"SURVEY_ID" NUMBER(10) NULL ,
"RESEARCH_ID" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_RESEARCH_COLLECT" IS '调研-问卷集合表';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."NAME" IS '名称';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."PROJECT_NAME" IS '项目名称';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."PRINCIPAL" IS '负责人';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."SHOULD" IS '应到人数';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."REAL" IS '实到人数';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."SURVEY_ID" IS '问卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_COLLECT"."RESEARCH_ID" IS '调研ID';

-- ----------------------------
-- Records of THINK_RESEARCH_COLLECT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_RESEARCH_TISSUEID
-- ----------------------------
DROP TABLE "SCOTT"."THINK_RESEARCH_TISSUEID";
CREATE TABLE "SCOTT"."THINK_RESEARCH_TISSUEID" (
"RESEARCH_ID" NUMBER(11) NULL ,
"TISSUE_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_RESEARCH_TISSUEID" IS '调研管理 - 关联组织表';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_TISSUEID"."RESEARCH_ID" IS '调研ID';
COMMENT ON COLUMN "SCOTT"."THINK_RESEARCH_TISSUEID"."TISSUE_ID" IS '组织ID';

-- ----------------------------
-- Records of THINK_RESEARCH_TISSUEID
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_SUMMARY_ATTACHMENT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SUMMARY_ATTACHMENT";
CREATE TABLE "SCOTT"."THINK_SUMMARY_ATTACHMENT" (
"ID" NUMBER(10) NOT NULL ,
"PROJECT_ID" NUMBER NULL ,
"ATTACHMENT_ADDRESS" VARCHAR2(255 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SUMMARY_ATTACHMENT" IS '培训项目总结附件表';
COMMENT ON COLUMN "SCOTT"."THINK_SUMMARY_ATTACHMENT"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_SUMMARY_ATTACHMENT"."ATTACHMENT_ADDRESS" IS '附件地址';

-- ----------------------------
-- Records of THINK_SUMMARY_ATTACHMENT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_SUPPLIER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SUPPLIER";
CREATE TABLE "SCOTT"."THINK_SUPPLIER" (
"SID" NUMBER(11) NOT NULL ,
"SNAME" VARCHAR2(150 BYTE) NOT NULL ,
"STYLE" NUMBER(1) NOT NULL ,
"SC_TYPE" NUMBER(11) NOT NULL ,
"MAIN_COURSE" VARCHAR2(150 BYTE) NOT NULL ,
"LINKMAN" VARCHAR2(100 BYTE) NOT NULL ,
"POSITION" VARCHAR2(100 BYTE) NOT NULL ,
"TEL" VARCHAR2(255 BYTE) NOT NULL ,
"PHONE_NUMBER" VARCHAR2(255 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SUPPLIER" IS '供应商表';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."SID" IS '供应商ID';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."SNAME" IS '供应商名称';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."STYLE" IS '供应商类型 1机构 2讲师';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."SC_TYPE" IS '擅长类型ID';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."MAIN_COURSE" IS '主打课程';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."LINKMAN" IS '联系人';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."POSITION" IS '职位';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."TEL" IS '电话号码';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER"."PHONE_NUMBER" IS '手机号';

-- ----------------------------
-- Records of THINK_SUPPLIER
-- ----------------------------
INSERT INTO "SCOTT"."THINK_SUPPLIER" VALUES ('3', '供应商1', '2', '1', '课程1,课程2', '联系人', '职位', '0755-6020563', '13164789797');

-- ----------------------------
-- Table structure for THINK_SUPPLIER_TYPE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SUPPLIER_TYPE";
CREATE TABLE "SCOTT"."THINK_SUPPLIER_TYPE" (
"ID" NUMBER(11) NOT NULL ,
"TNAME" VARCHAR2(100 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SUPPLIER_TYPE" IS '供应商类别表';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER_TYPE"."ID" IS '类型ID';
COMMENT ON COLUMN "SCOTT"."THINK_SUPPLIER_TYPE"."TNAME" IS '供应商擅长类别名称';

-- ----------------------------
-- Records of THINK_SUPPLIER_TYPE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_SUPPLIER_TYPE" VALUES ('1', '类别1');

-- ----------------------------
-- Table structure for THINK_SURVEY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SURVEY";
CREATE TABLE "SCOTT"."THINK_SURVEY" (
"ID" NUMBER(11) NOT NULL ,
"SURVEY_NAME" VARCHAR2(60 BYTE) NOT NULL ,
"SURVEY_CAT_ID" NUMBER(10) NOT NULL ,
"SURVEY_SCORE" NUMBER(6) DEFAULT 0  NOT NULL ,
"SURVEY_HEIR" NUMBER(10) NOT NULL ,
"SURVEY_UPLOAD_TIME" DATE NOT NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"IS_AVAILABLE" NUMBER(1) DEFAULT 0  NOT NULL ,
"PRINCIPAL" VARCHAR2(60 BYTE) NOT NULL ,
"START_TIME" DATE NOT NULL ,
"END_TIME" DATE NOT NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"SURVEY_MODE" NUMBER(1) NOT NULL ,
"SURVEY_DESC" VARCHAR2(500 BYTE) NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SURVEY" IS '问卷表';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_NAME" IS '调研名称';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_CAT_ID" IS '分类ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_SCORE" IS '调研分数';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_HEIR" IS '上传人';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_UPLOAD_TIME" IS '上传时间';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."STATUS" IS '0表示待审核，1表示已通过，3表示已拒绝,4已删除，5-草稿';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."IS_AVAILABLE" IS '是否启用，0表示禁用，1表示启用';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."PRINCIPAL" IS '负责人';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_MODE" IS '调研方式-废弃';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."SURVEY_DESC" IS '问卷描述';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY"."OBJECTION" IS '拒绝理由';

-- ----------------------------
-- Records of THINK_SURVEY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_SURVEY_ANSWER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SURVEY_ANSWER";
CREATE TABLE "SCOTT"."THINK_SURVEY_ANSWER" (
"ID" NUMBER(11) NOT NULL ,
"PROJECT_ID" NUMBER(11) NOT NULL ,
"SURVEY_ID" NUMBER(11) NOT NULL ,
"U_SURVEY_ID" NUMBER(11) NOT NULL ,
"SURVEY_ANSWER" VARCHAR2(50 BYTE) NOT NULL ,
"CLASSIFICATION" NUMBER(1) NOT NULL ,
"QUESTION_NUMBER" NUMBER(11) NOT NULL ,
"DESCRIBE" BLOB NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."SURVEY_ID" IS '调研ＩＤ';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."U_SURVEY_ID" IS '关联调研人ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."SURVEY_ANSWER" IS '考试答案';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."CLASSIFICATION" IS '题型（1单选题 2多选题 3判断题）';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."QUESTION_NUMBER" IS '题号';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ANSWER"."DESCRIBE" IS '描述';

-- ----------------------------
-- Records of THINK_SURVEY_ANSWER
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_SURVEY_ATTENDANCE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE";
CREATE TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"SURVEY_ID" NUMBER(11) NOT NULL ,
"DEPARTMENT_ID" NUMBER(11) NOT NULL ,
"POSITION_ID" NUMBER(11) NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL ,
"MOBILE_SCANNING" NUMBER(1) DEFAULT 0  NULL ,
"PROJECT_ID" NUMBER(11) NOT NULL ,
"COMMIT_TIME" DATE NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."SURVEY_ID" IS '调研ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."DEPARTMENT_ID" IS '部门ＩＤ';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."POSITION_ID" IS '岗位ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."STATUS" IS '0表示未提交，1表示已提交，2物理删除,3-已逾期';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."PROJECT_ID" IS '关联项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ATTENDANCE"."COMMIT_TIME" IS '问卷题交时间';

-- ----------------------------
-- Records of THINK_SURVEY_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_SURVEY_CATEGORY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SURVEY_CATEGORY";
CREATE TABLE "SCOTT"."THINK_SURVEY_CATEGORY" (
"ID" NUMBER(11) NOT NULL ,
"PID" NUMBER(11) DEFAULT 0  NOT NULL ,
"SORT" NUMBER(11) DEFAULT 1  NOT NULL ,
"CAT_NAME" VARCHAR2(50 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SURVEY_CATEGORY" IS '问卷类别表';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_CATEGORY"."PID" IS '父ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_CATEGORY"."SORT" IS '排序';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_CATEGORY"."CAT_NAME" IS '栏目分类';

-- ----------------------------
-- Records of THINK_SURVEY_CATEGORY
-- ----------------------------
INSERT INTO "SCOTT"."THINK_SURVEY_CATEGORY" VALUES ('1', '0', '1', '问卷分类1');

-- ----------------------------
-- Table structure for THINK_SURVEY_ITEM
-- ----------------------------
DROP TABLE "SCOTT"."THINK_SURVEY_ITEM";
CREATE TABLE "SCOTT"."THINK_SURVEY_ITEM" (
"ID" NUMBER(11) NOT NULL ,
"SURVEY_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"TITLE" VARCHAR2(255 BYTE) NOT NULL ,
"OPTIONA" VARCHAR2(50 BYTE) NULL ,
"OPTIONB" VARCHAR2(50 BYTE) NULL ,
"OPTIONC" VARCHAR2(50 BYTE) NULL ,
"OPTIOND" VARCHAR2(50 BYTE) NULL ,
"OPTIONE" VARCHAR2(50 BYTE) NULL ,
"OPTIONF" VARCHAR2(1000 BYTE) DEFAULT 0  NULL ,
"CLASSIFICATION" VARCHAR2(20 BYTE) NULL ,
"CTIME" NUMBER(10) DEFAULT 0  NOT NULL ,
"ORDER" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_SURVEY_ITEM" IS '问卷题库表';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."SURVEY_ID" IS '问卷SURVEY_ID';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."TITLE" IS '题目名称';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIONA" IS '选项A';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIONB" IS '选项B';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIONC" IS '选项C';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIOND" IS '选项D';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIONE" IS '选项E';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."OPTIONF" IS '其他 0表示没有其他 1表示有其他';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."CLASSIFICATION" IS '问卷分类 1表示单选题 2表示多选题 3判断 4简答';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."CTIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_SURVEY_ITEM"."ORDER" IS '题目排序';

-- ----------------------------
-- Records of THINK_SURVEY_ITEM
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TEST
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TEST";
CREATE TABLE "SCOTT"."THINK_TEST" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"CREATE_USER" NUMBER(11) NOT NULL ,
"CREATE_TIME" DATE NOT NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NOT NULL ,
"START_TIME" DATE NOT NULL ,
"END_TIME" DATE NOT NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"STATUS" NUMBER(1) NOT NULL ,
"AUDIT_STATUS" NUMBER(1) NOT NULL ,
"SCORE" NUMBER(10) NOT NULL ,
"EXAMINATION_ID" NUMBER(10) NOT NULL ,
"ADDRESS" VARCHAR2(512 BYTE) NOT NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TEST" IS '考试表';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."NAME" IS '考试名称';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."CREATE_USER" IS '创建者ID';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."CREATE_TIME" IS '创建时间';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."TYPE" IS '考试方式,0-线上，1-线下';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."START_TIME" IS '开始时间';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."END_TIME" IS '结束时间';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."STATUS" IS '考试状态:0-未开始,1-进行中,2-已结束';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."AUDIT_STATUS" IS '审核状态:0-已通过，1待审核，2已拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."SCORE" IS '学分(参加本次考试可以获得多少学分)';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."EXAMINATION_ID" IS '关联的试卷ID';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."ADDRESS" IS '考试地址';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_TEST"."OBJECTION" IS '拒绝理由';

-- ----------------------------
-- Records of THINK_TEST
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TEST_USER_REL
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TEST_USER_REL";
CREATE TABLE "SCOTT"."THINK_TEST_USER_REL" (
"TEST_ID" NUMBER(10) NOT NULL ,
"USER_ID" NUMBER(10) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TEST_USER_REL" IS '考试-员工关联表';
COMMENT ON COLUMN "SCOTT"."THINK_TEST_USER_REL"."TEST_ID" IS '考试ID';
COMMENT ON COLUMN "SCOTT"."THINK_TEST_USER_REL"."USER_ID" IS '用户ID';

-- ----------------------------
-- Records of THINK_TEST_USER_REL
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TISSUE_AUTH
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TISSUE_AUTH";
CREATE TABLE "SCOTT"."THINK_TISSUE_AUTH" (
"USER_ID" NUMBER(10) NOT NULL ,
"TISSUE_ID" NUMBER(10) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_AUTH"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_AUTH"."TISSUE_ID" IS '组织ID';

-- ----------------------------
-- Records of THINK_TISSUE_AUTH
-- ----------------------------
INSERT INTO "SCOTT"."THINK_TISSUE_AUTH" VALUES ('1', '1');

-- ----------------------------
-- Table structure for THINK_TISSUE_GROUP_ACCESS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS";
CREATE TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" (
"USER_ID" NUMBER(11) NOT NULL ,
"TISSUE_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"JOB_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"MANAGE_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"BRANCH_ID" NUMBER(6) DEFAULT 0  NOT NULL ,
"SERIAL_NUMBER" VARCHAR2(200 BYTE) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" IS '组织用户关联表';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."TISSUE_ID" IS '组织架构关联字段';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."JOB_ID" IS '岗位ID';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."MANAGE_ID" IS '组织架构权限,0-普通用户,1-管理员,2-负责人';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."BRANCH_ID" IS '相同分公司标注';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_GROUP_ACCESS"."SERIAL_NUMBER" IS '序列';

-- ----------------------------
-- Records of THINK_TISSUE_GROUP_ACCESS
-- ----------------------------
INSERT INTO "SCOTT"."THINK_TISSUE_GROUP_ACCESS" VALUES ('54', '3', '5', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TISSUE_GROUP_ACCESS" VALUES ('1', '1', '2', '0', '0', ' ');
INSERT INTO "SCOTT"."THINK_TISSUE_GROUP_ACCESS" VALUES ('53', '1', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TISSUE_GROUP_ACCESS" VALUES ('55', '6', '4', '0', '0', '0');

-- ----------------------------
-- Table structure for THINK_TISSUE_RULE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TISSUE_RULE";
CREATE TABLE "SCOTT"."THINK_TISSUE_RULE" (
"ID" NUMBER(11) NOT NULL ,
"PID" NUMBER(11) DEFAULT 0  NULL ,
"NAME" VARCHAR2(255 BYTE) NULL ,
"ORDER_NUMBER" NUMBER(11) NULL ,
"RULES" VARCHAR2(4000 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TISSUE_RULE" IS '菜单表';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_RULE"."PID" IS '所属菜单';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_RULE"."NAME" IS '菜单名称';
COMMENT ON COLUMN "SCOTT"."THINK_TISSUE_RULE"."ORDER_NUMBER" IS '排序';

-- ----------------------------
-- Records of THINK_TISSUE_RULE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_TISSUE_RULE" VALUES ('3', '2', '编辑部', null, '4');
INSERT INTO "SCOTT"."THINK_TISSUE_RULE" VALUES ('1', '0', '中国太平保险集团', null, '2,3,4,5');
INSERT INTO "SCOTT"."THINK_TISSUE_RULE" VALUES ('2', '1', '分公司1', null, '3,4,6,7,8');
INSERT INTO "SCOTT"."THINK_TISSUE_RULE" VALUES ('5', '1', '分公司2', null, null);
INSERT INTO "SCOTT"."THINK_TISSUE_RULE" VALUES ('6', '2', '市场部', null, null);

-- ----------------------------
-- Table structure for THINK_TOOL_LEARNING
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TOOL_LEARNING";
CREATE TABLE "SCOTT"."THINK_TOOL_LEARNING" (
"ID" NUMBER(11) NOT NULL ,
"TYPEID" NUMBER(11) NULL ,
"TISSUE_ID" NUMBER(11) NULL ,
"JOB_ID" NUMBER(11) NULL ,
"YEAR" NUMBER(11) NULL ,
"JANUARY" NUMBER(11) DEFAULT 0  NULL ,
"FEBRUARY" NUMBER(11) DEFAULT 0  NULL ,
"MARCH" NUMBER(11) DEFAULT 0  NULL ,
"APRIL" NUMBER(11) DEFAULT 0  NULL ,
"MAY" NUMBER(11) DEFAULT 0  NULL ,
"JUNE" NUMBER(11) DEFAULT 0  NULL ,
"JULY" NUMBER(11) DEFAULT 0  NULL ,
"AUGUST" NUMBER(11) DEFAULT 0  NULL ,
"SEPTEMBER" NUMBER(11) DEFAULT 0  NULL ,
"OCTOBER" NUMBER(11) DEFAULT 0  NULL ,
"NOVEMBER" NUMBER(11) DEFAULT 0  NULL ,
"DECEMBER" NUMBER(11) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TOOL_LEARNING" IS '工具管理 - 学习目标';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."TYPEID" IS '类别(0-必修,1-选修,2-修读，3-积分(新增类型))';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."TISSUE_ID" IS '组织架构ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."JOB_ID" IS '岗位ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."YEAR" IS '年份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."JANUARY" IS '一月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."FEBRUARY" IS '二月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."MARCH" IS '三月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."APRIL" IS '四月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."MAY" IS '五月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."JUNE" IS '六月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."JULY" IS '七月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."AUGUST" IS '八月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."SEPTEMBER" IS '九月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."OCTOBER" IS '十月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."NOVEMBER" IS '十一月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_LEARNING"."DECEMBER" IS '十二月份';

-- ----------------------------
-- Records of THINK_TOOL_LEARNING
-- ----------------------------
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('34', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('35', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('36', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('38', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('39', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('40', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('42', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('43', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('44', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('46', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('47', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('48', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('5', '0', '2', '5', '2018', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('6', '1', '2', '5', '2018', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('7', '2', '2', '5', '2018', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('8', '3', '2', '5', '2018', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('22', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('23', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('24', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('1', '0', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('2', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('3', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('4', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('9', '0', '2', '5', '2019', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('10', '1', '2', '5', '2019', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('11', '2', '2', '5', '2019', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('12', '3', '2', '5', '2019', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('13', '0', '2', '5', '2020', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('14', '1', '2', '5', '2020', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('15', '2', '2', '5', '2020', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('16', '3', '2', '5', '2020', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('17', '0', '2', '5', '2021', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('18', '1', '2', '5', '2021', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('19', '2', '2', '5', '2021', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('20', '3', '2', '5', '2021', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('26', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('27', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('28', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('30', '1', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('31', '2', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_TOOL_LEARNING" VALUES ('32', '3', '2', '5', '2017', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for THINK_TOOL_TEACHING
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TOOL_TEACHING";
CREATE TABLE "SCOTT"."THINK_TOOL_TEACHING" (
"ID" NUMBER(11) NOT NULL ,
"TYPEID" NUMBER(11) NULL ,
"TISSUE_ID" NUMBER(11) NULL ,
"LECTURER_ID" NUMBER(11) NULL ,
"YEAR" NUMBER(11) DEFAULT 0  NULL ,
"JANUARY" NUMBER(11,1) DEFAULT 0.0  NULL ,
"FEBRUARY" NUMBER(11,1) DEFAULT 0.0  NULL ,
"MARCH" NUMBER(11,1) DEFAULT 0.0  NULL ,
"APRIL" NUMBER(11,1) DEFAULT 0.0  NULL ,
"MAY" NUMBER(11,1) DEFAULT 0.0  NULL ,
"JUNE" NUMBER(11,1) DEFAULT 0.0  NULL ,
"JULY" NUMBER(11,1) DEFAULT 0.0  NULL ,
"AUGUST" NUMBER(11,1) DEFAULT 0.0  NULL ,
"SEPTEMBER" NUMBER(11,1) DEFAULT 0.0  NULL ,
"OCTOBER" NUMBER(11,1) DEFAULT 0.0  NULL ,
"NOVEMBER" NUMBER(11,1) DEFAULT 0.0  NULL ,
"DECEMBER" NUMBER(11,1) DEFAULT 0.0  NULL ,
"PLANID" NUMBER(11) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TOOL_TEACHING" IS '工具管理 - 授课目标';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."TYPEID" IS '类别(0-授课目标（次）,1-授课时长（小时）,2-课程开发目标（节)';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."TISSUE_ID" IS '组织架构ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."LECTURER_ID" IS '讲师ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."YEAR" IS '年份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."JANUARY" IS '一月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."FEBRUARY" IS '二月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."MARCH" IS '三月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."APRIL" IS '四月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."MAY" IS '五月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."JUNE" IS '六月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."JULY" IS '七月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."AUGUST" IS '八月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."SEPTEMBER" IS '九月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."OCTOBER" IS '十月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."NOVEMBER" IS '十一月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."DECEMBER" IS '十二月份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TEACHING"."PLANID" IS '1-年计划,2季计划,3-月计划';

-- ----------------------------
-- Records of THINK_TOOL_TEACHING
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TOOL_TRAIN
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TOOL_TRAIN";
CREATE TABLE "SCOTT"."THINK_TOOL_TRAIN" (
"ID" NUMBER(11) NOT NULL ,
"TISSUE_ID" NUMBER(11) NULL ,
"YEAR" NUMBER(11) NULL ,
"PROJECT_ID" NUMBER(11) NULL ,
"TRAINING_NUM" NUMBER(11) DEFAULT 0  NULL ,
"BUDGET_NUM" NUMBER(11) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TOOL_TRAIN" IS '工具管理 - 培训项目预算';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TRAIN"."TISSUE_ID" IS '组织架构ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TRAIN"."YEAR" IS '年份';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TRAIN"."PROJECT_ID" IS '项目ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TRAIN"."TRAINING_NUM" IS '年度培训次数';
COMMENT ON COLUMN "SCOTT"."THINK_TOOL_TRAIN"."BUDGET_NUM" IS '年度培训项目总预算';

-- ----------------------------
-- Records of THINK_TOOL_TRAIN
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TOPIC_GROUP
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TOPIC_GROUP";
CREATE TABLE "SCOTT"."THINK_TOPIC_GROUP" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) DEFAULT 0  NOT NULL ,
"NAME" VARCHAR2(1000 BYTE) DEFAULT ''  NOT NULL ,
"THEME" VARCHAR2(4000 BYTE) DEFAULT ''  NULL ,
"PUBLISH_TIME" DATE NOT NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NULL ,
"AUDIT_TIME" NUMBER(11) DEFAULT 0  NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL ,
"TYPE" NUMBER(1) DEFAULT 0  NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TOPIC_GROUP" IS '话题小组表';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."ID" IS '主键ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."USER_ID" IS '发布者ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."NAME" IS '小组名称';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."THEME" IS '小组主题';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."PUBLISH_TIME" IS '发布时间';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."STATUS" IS '审核状态  0待审核 1审核通过 2审核拒绝';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_GROUP"."TYPE" IS '来源类型，0表示:APP 1表示:PC';

-- ----------------------------
-- Records of THINK_TOPIC_GROUP
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_TOPIC_PERSONNEL
-- ----------------------------
DROP TABLE "SCOTT"."THINK_TOPIC_PERSONNEL";
CREATE TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" (
"ID" NUMBER(11) NOT NULL ,
"TOPIC_GROUP_ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER NOT NULL ,
"APPLY_REASON" VARCHAR2(255 BYTE) DEFAULT ''  NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" IS '话题邀请人员关联表';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_PERSONNEL"."TOPIC_GROUP_ID" IS '话题小组ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_PERSONNEL"."USER_ID" IS '指定人员ID';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_PERSONNEL"."APPLY_REASON" IS '申请加入话题小组-申请理由';
COMMENT ON COLUMN "SCOTT"."THINK_TOPIC_PERSONNEL"."STATUS" IS '用户确认状态  0待审核 1审核通过 2审核拒绝';

-- ----------------------------
-- Records of THINK_TOPIC_PERSONNEL
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_USER
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USER";
CREATE TABLE "SCOTT"."THINK_USER" (
"ID" NUMBER(10) NOT NULL ,
"USERNAME" VARCHAR2(60 BYTE) NOT NULL ,
"PASSWORD" VARCHAR2(64 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;

-- ----------------------------
-- Records of THINK_USER
-- ----------------------------
INSERT INTO "SCOTT"."THINK_USER" VALUES ('10', 'add_username', '123456');
INSERT INTO "SCOTT"."THINK_USER" VALUES ('3', 'name3', 'password3');
INSERT INTO "SCOTT"."THINK_USER" VALUES ('4', 'name4', 'password4');
INSERT INTO "SCOTT"."THINK_USER" VALUES ('2', 'upd_name2', 'password2');
INSERT INTO "SCOTT"."THINK_USER" VALUES ('5', 'name5', 'password5');
INSERT INTO "SCOTT"."THINK_USER" VALUES ('6', 'name6', 'password6');

-- ----------------------------
-- Table structure for THINK_USER_COMPANY
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USER_COMPANY";
CREATE TABLE "SCOTT"."THINK_USER_COMPANY" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"COMPANY_ID" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_USER_COMPANY" IS '用户公司关联表(勿删，数据检测使用)';
COMMENT ON COLUMN "SCOTT"."THINK_USER_COMPANY"."USER_ID" IS '用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_USER_COMPANY"."COMPANY_ID" IS '公司ID';

-- ----------------------------
-- Records of THINK_USER_COMPANY
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_USER_IMPORT
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USER_IMPORT";
CREATE TABLE "SCOTT"."THINK_USER_IMPORT" (
"ID" NUMBER(11) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"PHONE" VARCHAR2(15 BYTE) NOT NULL ,
"NAME" VARCHAR2(200 BYTE) NOT NULL ,
"EMAIL" VARCHAR2(200 BYTE) NOT NULL ,
"COMPANY" VARCHAR2(200 BYTE) NULL ,
"PART" VARCHAR2(200 BYTE) NULL ,
"BIRTHDAY" VARCHAR2(20 BYTE) NOT NULL ,
"SEX" NUMBER(1) DEFAULT 0  NULL ,
"JOB_NUM" VARCHAR2(20 BYTE) NOT NULL ,
"STATUS" NUMBER(1) DEFAULT 0  NULL ,
"ERROR_TYPE" NUMBER(1) NOT NULL ,
"ADD_TIME" DATE NULL ,
"AREA" VARCHAR2(50 BYTE) NOT NULL ,
"ROOM" VARCHAR2(50 BYTE) NOT NULL ,
"AGE" NUMBER(11) NOT NULL ,
"SEQUENCE" VARCHAR2(50 BYTE) NOT NULL ,
"JOB_NAME" VARCHAR2(50 BYTE) NOT NULL ,
"JOB_LEVEL" VARCHAR2(50 BYTE) NOT NULL ,
"USER_LEVEL" VARCHAR2(50 BYTE) NOT NULL ,
"EDU" VARCHAR2(50 BYTE) NOT NULL ,
"GROUP_TIME" VARCHAR2(50 BYTE) NOT NULL ,
"CENTER_TIME" VARCHAR2(50 BYTE) NOT NULL ,
"OFFICE_PHONE" VARCHAR2(50 BYTE) NOT NULL ,
"IP_PHONE" VARCHAR2(50 BYTE) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_USER_IMPORT" IS '用户导入临时表';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."USER_ID" IS '管理员用户ID';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."PHONE" IS '手机号';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."NAME" IS '用户名称';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."EMAIL" IS '邮箱';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."COMPANY" IS '公司';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."PART" IS '部门';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."BIRTHDAY" IS '生日';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."SEX" IS '性别 0女 1男';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."JOB_NUM" IS '工号';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."STATUS" IS '数据是否有效 0无效 1有效';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."ERROR_TYPE" IS '无效类型 1数据不完善 2系统中已存在 3文件中重复 4手机号格式有误 5邮箱格式有误 6导入异常';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."ADD_TIME" IS '数据添加时间';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."AREA" IS '区域';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."ROOM" IS '室、房间号';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."AGE" IS '年龄';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."SEQUENCE" IS '序列';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."JOB_NAME" IS '职务、岗位';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."JOB_LEVEL" IS '职级';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."USER_LEVEL" IS '用户组级别';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."EDU" IS '学历';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."GROUP_TIME" IS '入集团日期';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."CENTER_TIME" IS '入中心日期';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."OFFICE_PHONE" IS '办公电话';
COMMENT ON COLUMN "SCOTT"."THINK_USER_IMPORT"."IP_PHONE" IS 'IP电话';

-- ----------------------------
-- Records of THINK_USER_IMPORT
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_USERS
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USERS";
CREATE TABLE "SCOTT"."THINK_USERS" (
"ID" NUMBER NOT NULL ,
"USERNAME" VARCHAR2(60 BYTE) NOT NULL ,
"PASSWORD" VARCHAR2(64 BYTE) NOT NULL ,
"AVATAR" VARCHAR2(255 BYTE) DEFAULT '/UPLOAD/AVATAR/20170303/58B8E79006E75.JPG'  NOT NULL ,
"EMAIL" VARCHAR2(100 BYTE) DEFAULT ''  NULL ,
"EMAIL_CODE" VARCHAR2(60 BYTE) NULL ,
"PHONE" NUMBER NULL ,
"STATUS" NUMBER DEFAULT 2  NOT NULL ,
"REGISTER_TIME" DATE NOT NULL ,
"LAST_LOGIN_IP" VARCHAR2(16 BYTE) DEFAULT ''  NULL ,
"LAST_LOGIN_TIME" DATE NULL ,
"JOB_NUMBER" VARCHAR2(18 BYTE) NULL ,
"PROVINCE" VARCHAR2(30 BYTE) NULL ,
"CITY" VARCHAR2(30 BYTE) NULL ,
"PERSONALIZED_SIGNATURE" VARCHAR2(50 BYTE) NULL ,
"QRCODE" VARCHAR2(50 BYTE) NULL ,
"TOKEN" VARCHAR2(50 BYTE) NULL ,
"TOKEN_EXPIRES" NUMBER NULL ,
"IS_LOGIN" NUMBER NULL ,
"AUDIT_TIME" NUMBER DEFAULT 0  NULL ,
"ORDERNO" VARCHAR2(20 BYTE) NULL ,
"OBJECTION" VARCHAR2(1000 BYTE) NULL ,
"SEQUENCE" VARCHAR2(255 BYTE) NULL ,
"BIRTHDAY" DATE NULL ,
"GENDER" NUMBER(4) DEFAULT 0  NULL ,
"AGE" NUMBER(4) DEFAULT 0  NOT NULL ,
"GROUP_TIME" DATE NULL ,
"CENTER_TIME" DATE NULL ,
"AREA" VARCHAR2(255 BYTE) NULL ,
"ROOM" VARCHAR2(255 BYTE) NULL ,
"RANK" VARCHAR2(255 BYTE) NULL ,
"EDUCATION" NUMBER(4) NULL ,
"MOBILEPHONE" NUMBER(11) NULL ,
"TEL" VARCHAR2(255 BYTE) NULL ,
"IP_PHONE" VARCHAR2(255 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_USERS" IS '用户表';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."USERNAME" IS '用户名';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."PASSWORD" IS '登录密码；MB_PASSWORD加密';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."AVATAR" IS '用户头像，相对于UPLOAD/AVATAR目录';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."EMAIL" IS '登录邮箱';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."EMAIL_CODE" IS '激活码';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."PHONE" IS '手机号';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."STATUS" IS '用户状态 0：拒绝； 1：审核通过 ；2：待审核 ; 3 逻辑删除';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."REGISTER_TIME" IS '注册时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."LAST_LOGIN_IP" IS '最后登录IP';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."LAST_LOGIN_TIME" IS '最后登录时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."JOB_NUMBER" IS '工号';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."PROVINCE" IS '所在省份';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."CITY" IS '所在城市';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."PERSONALIZED_SIGNATURE" IS '个性签名';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."QRCODE" IS '扫码登录CODE';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."TOKEN" IS '用户标识TOKEN';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."TOKEN_EXPIRES" IS 'TOKEN有效时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."IS_LOGIN" IS '0未登录  1登录';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."AUDIT_TIME" IS '审核时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."ORDERNO" IS '工单号';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."OBJECTION" IS '拒绝理由';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."SEQUENCE" IS '序列';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."BIRTHDAY" IS '生日';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."GENDER" IS '0:无 1:男 2:女';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."AGE" IS '年龄';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."GROUP_TIME" IS '入集团时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."CENTER_TIME" IS '入中心时间';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."AREA" IS '区域';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."ROOM" IS '科室';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."RANK" IS '职级';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."EDUCATION" IS '学历: 1:博士研究生 2:硕士研究生 3:本科 4:专科 5:专科以下';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."MOBILEPHONE" IS '手机号码,非登录账号';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."TEL" IS '办公电话';
COMMENT ON COLUMN "SCOTT"."THINK_USERS"."IP_PHONE" IS 'IP电话';

-- ----------------------------
-- Records of THINK_USERS
-- ----------------------------
INSERT INTO "SCOTT"."THINK_USERS" VALUES ('55', 'ss', 'e10adc3949ba59abbe56e057f20f883e', '/UPLOAD/AVATAR/20170303/58B8E79006E75.JPG', null, null, '13132323232', '1', TO_DATE('2017-06-01 16:07:29', 'YYYY-MM-DD HH24:MI:SS'), null, null, '5021', null, null, null, null, null, null, null, '0', '0944497782', null, '1200', null, '0', '0', null, null, null, null, null, null, null, null, null);
INSERT INTO "SCOTT"."THINK_USERS" VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '/Upload/avatar/2017041458f08c8e06db1.png', '675283203@ww.com', null, '13246723211', '1', TO_DATE('2017-03-17 09:45:13', 'YYYY-MM-DD HH24:MI:SS'), '127.0.0.1', TO_DATE('2017-06-01 09:06:28', 'YYYY-MM-DD HH24:MI:SS'), '666111', null, null, 'dffgghwesdfgttttt', 'SaUHlJQ4sJkBMwhMeq7aoEUZBggqkjiV1491970049', 'fd3c0cc832f88f65308f0f5b7aeadd1c', '1492307113', '1', '0', null, null, null, null, null, '0', null, null, null, null, null, null, null, null, null);
INSERT INTO "SCOTT"."THINK_USERS" VALUES ('53', 'cqq', 'e10adc3949ba59abbe56e057f20f883e', '/UPLOAD/AVATAR/20170303/58B8E79006E75.JPG', null, null, '13164789797', '1', TO_DATE('2017-05-27 18:02:03', 'YYYY-MM-DD HH24:MI:SS'), null, null, '80888', null, null, null, null, null, null, null, '0', '0993238693', null, '888', null, '0', '0', null, null, null, null, null, null, null, null, null);
INSERT INTO "SCOTT"."THINK_USERS" VALUES ('54', 'ccc', 'e10adc3949ba59abbe56e057f20f883e', '/UPLOAD/AVATAR/20170303/58B8E79006E75.JPG', '45229486@qq.com', null, '13164789798', '1', TO_DATE('2017-06-01 10:45:21', 'YYYY-MM-DD HH24:MI:SS'), null, null, '12306', null, null, null, null, null, null, null, '0', '0951215180', null, '8088', null, '0', '0', null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for THINK_USERS_TAG
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USERS_TAG";
CREATE TABLE "SCOTT"."THINK_USERS_TAG" (
"ID" NUMBER(11) NOT NULL ,
"TAG_TITLE" VARCHAR2(80 BYTE) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_USERS_TAG" IS '用户标签表';
COMMENT ON COLUMN "SCOTT"."THINK_USERS_TAG"."TAG_TITLE" IS '标签名称';

-- ----------------------------
-- Records of THINK_USERS_TAG
-- ----------------------------
INSERT INTO "SCOTT"."THINK_USERS_TAG" VALUES ('1', '标签1');
INSERT INTO "SCOTT"."THINK_USERS_TAG" VALUES ('2', '标签2');
INSERT INTO "SCOTT"."THINK_USERS_TAG" VALUES ('3', '标签3');

-- ----------------------------
-- Table structure for THINK_USERS_TAG_RELATION
-- ----------------------------
DROP TABLE "SCOTT"."THINK_USERS_TAG_RELATION";
CREATE TABLE "SCOTT"."THINK_USERS_TAG_RELATION" (
"ID" NUMBER(11) NOT NULL ,
"TAG_ID" NUMBER(11) NULL ,
"USER_ID" NUMBER(11) NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_USERS_TAG_RELATION" IS '用户标签关联表';
COMMENT ON COLUMN "SCOTT"."THINK_USERS_TAG_RELATION"."TAG_ID" IS '标签ID';
COMMENT ON COLUMN "SCOTT"."THINK_USERS_TAG_RELATION"."USER_ID" IS '用户ID';

-- ----------------------------
-- Records of THINK_USERS_TAG_RELATION
-- ----------------------------
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('6', '1', '55');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('7', '2', '55');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('8', '3', '55');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('1', '1', '53');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('2', '2', '53');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('3', '1', '54');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('4', '2', '54');
INSERT INTO "SCOTT"."THINK_USERS_TAG_RELATION" VALUES ('5', '3', '54');

-- ----------------------------
-- Table structure for THINK_VERIFY_CODE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_VERIFY_CODE";
CREATE TABLE "SCOTT"."THINK_VERIFY_CODE" (
"ID" NUMBER(11) NOT NULL ,
"PHONE" VARCHAR2(11 BYTE) NOT NULL ,
"VERIFY_CODE" NUMBER(5) NOT NULL ,
"VERIFY_TIME" NUMBER(11) NOT NULL ,
"TIMES" NUMBER(1) DEFAULT 0  NOT NULL ,
"CREATE_TIME" NUMBER(11) NOT NULL ,
"M_TIME" DATE NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_VERIFY_CODE" IS '手机验证码记录表';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."PHONE" IS '手机号';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."VERIFY_CODE" IS '验证码';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."VERIFY_TIME" IS '验证码有效期';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."TIMES" IS '发送验证码次数';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."CREATE_TIME" IS '验证码生成的时间';
COMMENT ON COLUMN "SCOTT"."THINK_VERIFY_CODE"."M_TIME" IS '发送验证码时间';

-- ----------------------------
-- Records of THINK_VERIFY_CODE
-- ----------------------------

-- ----------------------------
-- Table structure for THINK_WELFARE
-- ----------------------------
DROP TABLE "SCOTT"."THINK_WELFARE";
CREATE TABLE "SCOTT"."THINK_WELFARE" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NULL ,
"NEED_SCORE" NUMBER(11) NOT NULL ,
"WELFARE_COVERS" VARCHAR2(255 BYTE) DEFAULT '/UPLOAD/INTEGRATION/20170309/RYD.JPG'  NULL ,
"ADD_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"UPDATE_TIME" NUMBER(11) DEFAULT 0  NOT NULL ,
"IS_DELETE" NUMBER(11) DEFAULT 0  NOT NULL ,
"IS_PUBLIC" NUMBER(11) DEFAULT 0  NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_WELFARE" IS '福利表';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."NAME" IS '福利名称';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."NEED_SCORE" IS '所需积分';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."WELFARE_COVERS" IS '福利封面图';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."ADD_TIME" IS '新增时间';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."UPDATE_TIME" IS '更新时间';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."IS_DELETE" IS '1表示删除，0表示不删除';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE"."IS_PUBLIC" IS '1表示启用，0表示禁用';

-- ----------------------------
-- Records of THINK_WELFARE
-- ----------------------------
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('11', '附录', '111', '/Upload/20170526/5927e07dc7b1e.JPG', '0', '0', '0', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('12', 'qqq', '1', '/Upload/20170526/5927e0b4f3840.JPG', '0', '0', '1', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('13', 'ss1', '12', '/Upload/20170526/5927e142eaecf.JPG', '1495785798', '1498185200', '0', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('1', 'name', '20', 'asdaqweqweqweqweq', '1495782522', '1495782522', '1', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('2', 'name', '20', 'asdaqweqweqweqweq', '1495782531', '1495782531', '1', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('3', 'name', '20', 'asdaqweqweqweqweq', '1495782534', '1495782534', '0', '0');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('5', '福利1', '15', '/Upload/20170526/5927d60df2e94.JPG', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('6', '福利2', '10', '/Upload/20170526/5927de54b7478.JPG', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('7', '福利3', '10', '/Upload/20170526/5927ded30d0f8.JPG', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('8', '福利3', '10', '/Upload/20170526/5927defaede93.JPG', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('9', '福利4', '11', '/Upload/20170526/5927dfe144727.JPG', '0', '0', '0', '1');
INSERT INTO "SCOTT"."THINK_WELFARE" VALUES ('10', '附录', '111', '/Upload/20170526/5927e07dc7b1e.JPG', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for THINK_WELFARE_RECORD
-- ----------------------------
DROP TABLE "SCOTT"."THINK_WELFARE_RECORD";
CREATE TABLE "SCOTT"."THINK_WELFARE_RECORD" (
"ID" NUMBER(11) NOT NULL ,
"NAME" VARCHAR2(255 BYTE) NOT NULL ,
"USER_ID" NUMBER(11) NOT NULL ,
"DEPARTMENT" VARCHAR2(255 BYTE) NULL ,
"NEED_SCORE" NUMBER(11) NOT NULL ,
"TIME" NUMBER(11) NOT NULL 
)
LOGGING
NOCOMPRESS
NOCACHE

;
COMMENT ON TABLE "SCOTT"."THINK_WELFARE_RECORD" IS '福利记录表';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE_RECORD"."NAME" IS '福利名称';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE_RECORD"."USER_ID" IS '用户表的UID';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE_RECORD"."DEPARTMENT" IS '部门(字段废弃)';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE_RECORD"."NEED_SCORE" IS '所需积分';
COMMENT ON COLUMN "SCOTT"."THINK_WELFARE_RECORD"."TIME" IS '兑换时间';

-- ----------------------------
-- Records of THINK_WELFARE_RECORD
-- ----------------------------

-- ----------------------------
-- Sequence structure for SEQ_ADMIN_COMPANY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ADMIN_COMPANY";
CREATE SEQUENCE "SCOTT"."SEQ_ADMIN_COMPANY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ADMIN_MESSAGE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ADMIN_MESSAGE";
CREATE SEQUENCE "SCOTT"."SEQ_ADMIN_MESSAGE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ADMIN_MESSAGE_TYPE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ADMIN_MESSAGE_TYPE";
CREATE SEQUENCE "SCOTT"."SEQ_ADMIN_MESSAGE_TYPE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ADMIN_NAV
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ADMIN_NAV";
CREATE SEQUENCE "SCOTT"."SEQ_ADMIN_NAV"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ADMIN_PROJECT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ADMIN_PROJECT";
CREATE SEQUENCE "SCOTT"."SEQ_ADMIN_PROJECT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 45
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ATTENDANCE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ATTENDANCE";
CREATE SEQUENCE "SCOTT"."SEQ_ATTENDANCE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_AUDIT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_AUDIT";
CREATE SEQUENCE "SCOTT"."SEQ_AUDIT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_AUDIT_CONDITION
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_AUDIT_CONDITION";
CREATE SEQUENCE "SCOTT"."SEQ_AUDIT_CONDITION"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_AUDIT_RULE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_AUDIT_RULE";
CREATE SEQUENCE "SCOTT"."SEQ_AUDIT_RULE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_AUTH_GROUP
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_AUTH_GROUP";
CREATE SEQUENCE "SCOTT"."SEQ_AUTH_GROUP"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 9
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_AUTH_RULE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_AUTH_RULE";
CREATE SEQUENCE "SCOTT"."SEQ_AUTH_RULE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_CENTER_STUDY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_CENTER_STUDY";
CREATE SEQUENCE "SCOTT"."SEQ_CENTER_STUDY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_CHAIQIQI
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_CHAIQIQI";
CREATE SEQUENCE "SCOTT"."SEQ_CHAIQIQI"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COLLIGATE_COMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COLLIGATE_COMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_COLLIGATE_COMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COMPANY_BANNER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COMPANY_BANNER";
CREATE SEQUENCE "SCOTT"."SEQ_COMPANY_BANNER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 10
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_ANSWER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_ANSWER";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_ANSWER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_ARTICLE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_ARTICLE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_ARTICLE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_CARE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_CARE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_CARE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_CATEGORY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_CATEGORY";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_CATEGORY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 8
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_CHAPTER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_CHAPTER";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_CHAPTER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_COMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_COMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_COMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_DETAIL
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_DETAIL";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_DETAIL"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_DETAIL_BAK
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_DETAIL_BAK";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_DETAIL_BAK"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_NOTE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_NOTE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_NOTE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_PRAISE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_PRAISE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_PRAISE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_QUES
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_QUES";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_QUES"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_RECORD
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_RECORD";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_RECORD"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_REPLYCOMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_REPLYCOMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_REPLYCOMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_COURSE_SCORE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_COURSE_SCORE";
CREATE SEQUENCE "SCOTT"."SEQ_COURSE_SCORE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_CREDITS
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_CREDITS";
CREATE SEQUENCE "SCOTT"."SEQ_CREDITS"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_DESIGNATED_PERSONNEL
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_DESIGNATED_PERSONNEL";
CREATE SEQUENCE "SCOTT"."SEQ_DESIGNATED_PERSONNEL"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAM_ANSWER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAM_ANSWER";
CREATE SEQUENCE "SCOTT"."SEQ_EXAM_ANSWER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAM_SCORE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAM_SCORE";
CREATE SEQUENCE "SCOTT"."SEQ_EXAM_SCORE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAMINATION
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAMINATION";
CREATE SEQUENCE "SCOTT"."SEQ_EXAMINATION"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 19
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAMINATION_ATTENDANCE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAMINATION_ATTENDANCE";
CREATE SEQUENCE "SCOTT"."SEQ_EXAMINATION_ATTENDANCE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAMINATION_CATEGORY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAMINATION_CATEGORY";
CREATE SEQUENCE "SCOTT"."SEQ_EXAMINATION_CATEGORY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 3
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAMINATION_COLLECT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAMINATION_COLLECT";
CREATE SEQUENCE "SCOTT"."SEQ_EXAMINATION_COLLECT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 3
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_EXAMINATION_ITEM
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_EXAMINATION_ITEM";
CREATE SEQUENCE "SCOTT"."SEQ_EXAMINATION_ITEM"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 11
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FILE_DOWNLOAD
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FILE_DOWNLOAD";
CREATE SEQUENCE "SCOTT"."SEQ_FILE_DOWNLOAD"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FRIENDS_CIRCLE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FRIENDS_CIRCLE";
CREATE SEQUENCE "SCOTT"."SEQ_FRIENDS_CIRCLE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 24
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FRIENDS_COMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FRIENDS_COMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_FRIENDS_COMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FRIENDS_NEWS
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FRIENDS_NEWS";
CREATE SEQUENCE "SCOTT"."SEQ_FRIENDS_NEWS"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FRIENDS_PRAISE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FRIENDS_PRAISE";
CREATE SEQUENCE "SCOTT"."SEQ_FRIENDS_PRAISE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_FRIENDS_REPLYCOMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_FRIENDS_REPLYCOMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_FRIENDS_REPLYCOMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_INTEGRATION_APPLY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_INTEGRATION_APPLY";
CREATE SEQUENCE "SCOTT"."SEQ_INTEGRATION_APPLY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_INTEGRATION_RECORD
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_INTEGRATION_RECORD";
CREATE SEQUENCE "SCOTT"."SEQ_INTEGRATION_RECORD"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_INTEGRATION_RULE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_INTEGRATION_RULE";
CREATE SEQUENCE "SCOTT"."SEQ_INTEGRATION_RULE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ITEM_INTERACTION
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ITEM_INTERACTION";
CREATE SEQUENCE "SCOTT"."SEQ_ITEM_INTERACTION"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_JOBS_MANAGE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_JOBS_MANAGE";
CREATE SEQUENCE "SCOTT"."SEQ_JOBS_MANAGE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 7
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_JOBS_MANAGE_COPY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_JOBS_MANAGE_COPY";
CREATE SEQUENCE "SCOTT"."SEQ_JOBS_MANAGE_COPY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_LEARNING_OBJECTIVES
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_LEARNING_OBJECTIVES";
CREATE SEQUENCE "SCOTT"."SEQ_LEARNING_OBJECTIVES"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_LECTURER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_LECTURER";
CREATE SEQUENCE "SCOTT"."SEQ_LECTURER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 10
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_LECTURER_COMMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_LECTURER_COMMENT";
CREATE SEQUENCE "SCOTT"."SEQ_LECTURER_COMMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_NEWS
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_NEWS";
CREATE SEQUENCE "SCOTT"."SEQ_NEWS"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 4
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_OAUTH_USER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_OAUTH_USER";
CREATE SEQUENCE "SCOTT"."SEQ_OAUTH_USER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_ORDER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_ORDER";
CREATE SEQUENCE "SCOTT"."SEQ_ORDER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PEROJECT_BUDGET
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PEROJECT_BUDGET";
CREATE SEQUENCE "SCOTT"."SEQ_PEROJECT_BUDGET"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PROJECT_COURSE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PROJECT_COURSE";
CREATE SEQUENCE "SCOTT"."SEQ_PROJECT_COURSE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PROJECT_EXAMINATION
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PROJECT_EXAMINATION";
CREATE SEQUENCE "SCOTT"."SEQ_PROJECT_EXAMINATION"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 20
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PROJECT_SUMMARY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PROJECT_SUMMARY";
CREATE SEQUENCE "SCOTT"."SEQ_PROJECT_SUMMARY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PROJECT_SURVEY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PROJECT_SURVEY";
CREATE SEQUENCE "SCOTT"."SEQ_PROJECT_SURVEY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_PROVINCE_CITY_AREA
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_PROVINCE_CITY_AREA";
CREATE SEQUENCE "SCOTT"."SEQ_PROVINCE_CITY_AREA"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_QUESTION_BANK
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_QUESTION_BANK";
CREATE SEQUENCE "SCOTT"."SEQ_QUESTION_BANK"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_RESEARCH
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_RESEARCH";
CREATE SEQUENCE "SCOTT"."SEQ_RESEARCH"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_RESEARCH_ANSWER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_RESEARCH_ANSWER";
CREATE SEQUENCE "SCOTT"."SEQ_RESEARCH_ANSWER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_RESEARCH_ATTENDANCE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_RESEARCH_ATTENDANCE";
CREATE SEQUENCE "SCOTT"."SEQ_RESEARCH_ATTENDANCE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SUMMARY_ATTACHMENT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SUMMARY_ATTACHMENT";
CREATE SEQUENCE "SCOTT"."SEQ_SUMMARY_ATTACHMENT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SUPPLIER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SUPPLIER";
CREATE SEQUENCE "SCOTT"."SEQ_SUPPLIER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 4
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SUPPLIER_TYPE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SUPPLIER_TYPE";
CREATE SEQUENCE "SCOTT"."SEQ_SUPPLIER_TYPE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SUPPLIERTYPE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SUPPLIERTYPE";
CREATE SEQUENCE "SCOTT"."SEQ_SUPPLIERTYPE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 3
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SURVEY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SURVEY";
CREATE SEQUENCE "SCOTT"."SEQ_SURVEY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SURVEY_ATTENDANCE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SURVEY_ATTENDANCE";
CREATE SEQUENCE "SCOTT"."SEQ_SURVEY_ATTENDANCE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SURVEY_CATEGORY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SURVEY_CATEGORY";
CREATE SEQUENCE "SCOTT"."SEQ_SURVEY_CATEGORY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 2
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_SURVEY_ITEM
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_SURVEY_ITEM";
CREATE SEQUENCE "SCOTT"."SEQ_SURVEY_ITEM"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TEST
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TEST";
CREATE SEQUENCE "SCOTT"."SEQ_TEST"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 125
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_THINK_AUTH_ROLE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_THINK_AUTH_ROLE";
CREATE SEQUENCE "SCOTT"."SEQ_THINK_AUTH_ROLE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TISSUE_RULE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TISSUE_RULE";
CREATE SEQUENCE "SCOTT"."SEQ_TISSUE_RULE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 9
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TOOL_LEARNING
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TOOL_LEARNING";
CREATE SEQUENCE "SCOTT"."SEQ_TOOL_LEARNING"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 49
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TOOL_TEACHING
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TOOL_TEACHING";
CREATE SEQUENCE "SCOTT"."SEQ_TOOL_TEACHING"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TOOL_TRAIN
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TOOL_TRAIN";
CREATE SEQUENCE "SCOTT"."SEQ_TOOL_TRAIN"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TOPIC_GROUP
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TOPIC_GROUP";
CREATE SEQUENCE "SCOTT"."SEQ_TOPIC_GROUP"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_TOPIC_PERSONNEL
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_TOPIC_PERSONNEL";
CREATE SEQUENCE "SCOTT"."SEQ_TOPIC_PERSONNEL"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USER
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USER";
CREATE SEQUENCE "SCOTT"."SEQ_USER"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USER_COMPANY
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USER_COMPANY";
CREATE SEQUENCE "SCOTT"."SEQ_USER_COMPANY"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USER_IMPORT
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USER_IMPORT";
CREATE SEQUENCE "SCOTT"."SEQ_USER_IMPORT"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USERS
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USERS";
CREATE SEQUENCE "SCOTT"."SEQ_USERS"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 56
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USERS_TAG
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USERS_TAG";
CREATE SEQUENCE "SCOTT"."SEQ_USERS_TAG"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 4
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_USERS_TAG_RELATION
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_USERS_TAG_RELATION";
CREATE SEQUENCE "SCOTT"."SEQ_USERS_TAG_RELATION"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 9
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_VERIFY_CODE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_VERIFY_CODE";
CREATE SEQUENCE "SCOTT"."SEQ_VERIFY_CODE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_WELFARE
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_WELFARE";
CREATE SEQUENCE "SCOTT"."SEQ_WELFARE"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 9999999999999999999999999999
 START WITH 16
 NOCACHE ;

-- ----------------------------
-- Sequence structure for SEQ_WELFARE_RECORD
-- ----------------------------
DROP SEQUENCE "SCOTT"."SEQ_WELFARE_RECORD";
CREATE SEQUENCE "SCOTT"."SEQ_WELFARE_RECORD"
 INCREMENT BY 1
 MINVALUE 1
 MAXVALUE 999999999999999999999999999
 START WITH 1
 NOCACHE ;

-- ----------------------------
-- Indexes structure for table DEPT
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table DEPT
-- ----------------------------
ALTER TABLE "SCOTT"."DEPT" ADD PRIMARY KEY ("DEPTNO");

-- ----------------------------
-- Indexes structure for table EMP
-- ----------------------------

-- ----------------------------
-- Checks structure for table EMP
-- ----------------------------
ALTER TABLE "SCOTT"."EMP" ADD CHECK ("EMPNO" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table EMP
-- ----------------------------
ALTER TABLE "SCOTT"."EMP" ADD PRIMARY KEY ("EMPNO");

-- ----------------------------
-- Indexes structure for table THINK_ADMIN_COMPANY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ADMIN_COMPANY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_COMPANY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_COMPANY" ADD CHECK ("STATUS" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ADMIN_COMPANY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_COMPANY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ADMIN_MESSAGE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ADMIN_MESSAGE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("TYPE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("NEWSTIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("FROM_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD CHECK ("IS_DELETE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ADMIN_MESSAGE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ADMIN_MESSAGE_TYPE
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table THINK_ADMIN_MESSAGE_TYPE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_MESSAGE_TYPE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ADMIN_NAV
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table THINK_ADMIN_NAV
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_NAV" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ADMIN_PROJECT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ADMIN_PROJECT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_PROJECT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_PROJECT" ADD CHECK ("IS_PUBLIC" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_PROJECT" ADD CHECK ("START_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ADMIN_PROJECT" ADD CHECK ("END_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ADMIN_PROJECT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ADMIN_PROJECT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("PID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("ATTENDANCE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("MOBILE_SCANNING" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("STATE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD CHECK ("TYPE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ATTENDANCE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_AUDIT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_AUDIT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_AUDIT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_AUDIT_CONDITION
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_AUDIT_CONDITION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT_CONDITION" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_CONDITION" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_CONDITION" ADD CHECK ("CONDITIONA" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_CONDITION" ADD CHECK ("CONDITIONB" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_AUDIT_CONDITION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT_CONDITION" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_AUDIT_RULE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_AUDIT_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("ONEAUDIT_ROLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("TWOAUDIT_ROLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("THREEAUDIT_ROLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("IS_CONDITION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("CONDITION_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD CHECK ("NUM" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_AUDIT_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUDIT_RULE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_AUTH_GROUP
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_AUTH_GROUP
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUTH_GROUP" ADD CHECK ("STATUS" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_AUTH_GROUP
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUTH_GROUP" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_AUTH_GROUP_ACCESS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUTH_GROUP_ACCESS" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUTH_GROUP_ACCESS" ADD CHECK ("GROUP_ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_AUTH_RULE
-- ----------------------------
CREATE INDEX "SCOTT"."IDX_NAMETHINK_AUTH_RULE"
ON "SCOTT"."THINK_AUTH_RULE" ("NAME" ASC)
LOGGING
VISIBLE;

-- ----------------------------
-- Checks structure for table THINK_AUTH_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD CHECK ("TITLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD CHECK ("CONDITION" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_AUTH_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_AUTH_RULE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_CENTER_STUDY
-- ----------------------------
CREATE INDEX "SCOTT"."IDX_PROJECT_IDTHINK_CENTER_STU"
ON "SCOTT"."THINK_CENTER_STUDY" ("PROJECT_ID" ASC)
LOGGING
VISIBLE;
CREATE INDEX "SCOTT"."IDX_SOURCE_IDTHINK_CENTER_STUD"
ON "SCOTT"."THINK_CENTER_STUDY" ("SOURCE_ID" ASC)
LOGGING
VISIBLE;

-- ----------------------------
-- Primary Key structure for table THINK_CENTER_STUDY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_CENTER_STUDY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_CHAIQIQI
-- ----------------------------

-- ----------------------------
-- Triggers structure for table THINK_CHAIQIQI
-- ----------------------------
CREATE OR REPLACE TRIGGER "SCOTT"."TIG_CHAIQIQI" BEFORE INSERT ON "SCOTT"."THINK_CHAIQIQI" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW ENABLE
declare
  nextid number;
begin
  if :new.id is null or :new.id = 0 then
    select seq_chaiqiqi.nextval --执行seq_user获取下一个序列
      into nextid
      from sys.dual;
    :new.id := nextid;//通过特殊变量:new在新增的时候写入上面获取的序列号
  end if;
-- ----------------------------
-- Checks structure for table THINK_CHAIQIQI
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_CHAIQIQI" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_CHAIQIQI" ADD CHECK (ID IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_CHAIQIQI" ADD CHECK (ID IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_CHAIQIQI
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_CHAIQIQI" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COLLIGATE_COMMENT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COLLIGATE_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("LECTURER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("LECTURER_EVALUATION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("COURSE_EVALUATION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("COMMENT_CONTENT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("COMMENT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("PID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD CHECK ("STATE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COLLIGATE_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COLLIGATE_COMMENT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COMPANY_BANNER
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table THINK_COMPANY_BANNER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COMPANY_BANNER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE
-- ----------------------------
CREATE INDEX "SCOTT"."IDX_COURSE_CAT_IDTHINK_COURSE"
ON "SCOTT"."THINK_COURSE" ("COURSE_CAT_ID" ASC)
LOGGING
VISIBLE;

-- ----------------------------
-- Checks structure for table THINK_COURSE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("COURSE_NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("COURSE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("MAKER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("COURSE_COVER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("CREDIT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("AUDITING" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("CLICK_COUNT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE" ADD CHECK ("ARRANGEMENT_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_ANSWER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_ANSWER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ANSWER" ADD CHECK ("QUES_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ANSWER" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ANSWER" ADD CHECK ("CONTENT" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_ANSWER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_ARTICLE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_ARTICLE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_ARTICLE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ARTICLE" ADD CHECK ("ICO" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_ARTICLE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_ARTICLE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_COURSE_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_ATTENDANCE" ADD CHECK ("USER_ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_COURSE_CARE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_CARE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CARE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CARE" ADD CHECK ("CARE_STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CARE" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CARE" ADD CHECK ("USER_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_CARE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CARE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_CATEGORY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_CATEGORY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CATEGORY" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_CATEGORY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CATEGORY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_CHAPTER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_CHAPTER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("PATH" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("TIMELEN" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD CHECK ("CREATE_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_CHAPTER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_CHAPTER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_DETAIL
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_DETAIL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_DETAIL" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_DETAIL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_DETAIL" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_DETAIL_BAK
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_DETAIL_BAK
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_DETAIL_BAK" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_DETAIL_BAK
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_DETAIL_BAK" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_NOTE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_NOTE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_NOTE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_NOTE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_NOTE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_COURSE_PRAISE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_PRAISE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_PRAISE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_PRAISE" ADD CHECK ("PRAISE" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_COURSE_QUES
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_QUES
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("CHAPTER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("TITLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD CHECK ("CONTENT" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_QUES
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_QUES" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_RECORD
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("RECENTLY_LOOKUP_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("START_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("END_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD CHECK ("PROJECT_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_RECORD" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_COURSE_SCORE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_COURSE_SCORE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("LECTURER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("LECTURER_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("COURSE_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD CHECK ("SCORE_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_COURSE_SCORE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_COURSE_SCORE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_CREDITS
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_CREDITS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_CREDITS" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_CREDITS" ADD CHECK ("SOURCE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_CREDITS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_CREDITS" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_DESIGNATED_PERSONNEL
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_DESIGNATED_PERSONNEL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL" ADD CHECK ("PROJECT_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_DESIGNATED_PERSONNEL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_DESIGNATED_PERSONNEL" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAM_ANSWER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAM_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("EXAM_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("U_EXAM_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("EXAM_ANSWER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("CLASSIFICATION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("QUESTION_NUMBER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD CHECK ("TEST_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAM_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAM_ANSWER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAM_SCORE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAM_SCORE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("EXAM_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("TOTAL_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("IS_PUBLISH" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD CHECK ("USE_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAM_SCORE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAM_SCORE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAMINATION
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("TEST_NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("TEST_CAT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("TEST_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("TEST_UPLOAD_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("IS_AVAILABLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD CHECK ("AUDIT_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAMINATION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAMINATION_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" ADD CHECK ("MOBILE_SCANNING" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAMINATION_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ATTENDANCE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAMINATION_CATEGORY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION_CATEGORY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" ADD CHECK ("PID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" ADD CHECK ("SORT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" ADD CHECK ("CAT_NAME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAMINATION_CATEGORY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_CATEGORY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAMINATION_COLLECT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION_COLLECT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_COLLECT" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAMINATION_COLLECT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_COLLECT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_EXAMINATION_ITEM
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION_ITEM
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD CHECK ("TITLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD CHECK ("RIGHT_OPTION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD CHECK ("CTIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD CHECK ("BELONGCOURSE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_EXAMINATION_ITEM
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_EXAMINATION_ITEM_REL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL" ADD CHECK ("EXAMINATION_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL" ADD CHECK ("EXAMINATION_ITEM_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_EXAMINATION_ITEM_REL" ADD CHECK ("SCORE" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_FILE_DOWNLOAD
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_FILE_DOWNLOAD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("COURSE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("PATH" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("TIMELEN" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD CHECK ("STYLE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_FILE_DOWNLOAD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FILE_DOWNLOAD" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_FRIENDS_CIRCLE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_FRIENDS_CIRCLE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("CONTENT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("PUBLISH_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("PID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("STATE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD CHECK ("ORDERNO" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_FRIENDS_CIRCLE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_CIRCLE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_FRIENDS_COMMENT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_FRIENDS_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_COMMENT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_COMMENT" ADD CHECK ("CONTENT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_COMMENT" ADD CHECK ("AUTHOR_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_COMMENT" ADD CHECK ("COMMENT_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_FRIENDS_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_COMMENT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_FRIENDS_NEWS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_NEWS" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_FRIENDS_PRAISE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_FRIENDS_PRAISE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD CHECK ("CID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD CHECK ("PRAISE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD CHECK ("PRAISE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD CHECK ("USER_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_FRIENDS_PRAISE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_PRAISE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_FRIENDS_REPLYCOMMENT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_FRIENDS_REPLYCOMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD CHECK ("AUTHOR_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD CHECK ("REPLY_CONTENT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD CHECK ("REPLY_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD CHECK ("PID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_FRIENDS_REPLYCOMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_FRIENDS_REPLYCOMMENT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_INTEGRATION_APPLY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_INTEGRATION_APPLY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("APPLY_DESCRIPTION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("ATTACHMENT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("ADD_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("ADD_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD CHECK ("STATUS" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_INTEGRATION_APPLY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_APPLY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_INTEGRATION_RECORD
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_INTEGRATION_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RECORD" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RECORD" ADD CHECK ("TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RECORD" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RECORD" ADD CHECK ("SCORE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_INTEGRATION_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RECORD" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_INTEGRATION_RULE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_INTEGRATION_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RULE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RULE" ADD CHECK ("SCORE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_INTEGRATION_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_INTEGRATION_RULE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ITEM_INTERACTION
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ITEM_INTERACTION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ITEM_INTERACTION" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ITEM_INTERACTION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ITEM_INTERACTION" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_JOBS_MANAGE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_JOBS_MANAGE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_JOBS_MANAGE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_JOBS_MANAGE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_JOBS_MANAGE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_JOBS_MANAGE_COPY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_JOBS_MANAGE_COPY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_JOBS_MANAGE_COPY" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_JOBS_MANAGE_COPY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_JOBS_MANAGE_COPY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_LECTURER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_LECTURER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("RESOURCE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("AGE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("LEVEL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("YEAR" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("PRICE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("NUM" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("TIMES" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("ADDRESS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("DESC" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("CERTIFICATE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("SID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("UPDATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("IS_DELETE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("UID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD CHECK ("SCORE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_LECTURER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_LECTURER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_LECTURER_COMMENT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_LECTURER_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_LECTURER_COMMENT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER_COMMENT" ADD CHECK ("LECTURER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER_COMMENT" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_LECTURER_COMMENT" ADD CHECK ("LECTURER_EVALUATION" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_LECTURER_COMMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_LECTURER_COMMENT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_NEWS
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_NEWS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_NEWS" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_NEWS" ADD CHECK ("TITLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_NEWS" ADD CHECK ("CONTENT" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_NEWS" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_NEWS" ADD CHECK ("USER_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_NEWS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_NEWS" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_OAUTH_USER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_OAUTH_USER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("NICKNAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("HEAD_IMG" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("OPENID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("ACCESS_TOKEN" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD CHECK ("LAST_LOGIN_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_OAUTH_USER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_OAUTH_USER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_ORDER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_ORDER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ORDER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_ORDER" ADD CHECK ("ORDER_SN" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_ORDER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_ORDER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_PROJECT_BUDGET
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_PROJECT_BUDGET
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_BUDGET" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_PROJECT_BUDGET" ADD CHECK ("OPTION_NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_PROJECT_BUDGET" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_PROJECT_BUDGET
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_BUDGET" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_PROJECT_COURSE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_PROJECT_COURSE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_COURSE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_PROJECT_COURSE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_COURSE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_PROJECT_EXAMINATION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_EXAMINATION" ADD CHECK ("PROJECT_ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_PROJECT_SUMMARY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_PROJECT_SUMMARY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_SUMMARY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_PROJECT_SUMMARY" ADD CHECK ("ACTUAL_EXPENSES" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_PROJECT_SUMMARY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_SUMMARY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_PROJECT_SURVEY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROJECT_SURVEY" ADD CHECK ("PROJECT_ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_PROVINCE_CITY_AREA
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_PROVINCE_CITY_AREA
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA" ADD CHECK ("NAME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_PROVINCE_CITY_AREA
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_PROVINCE_CITY_AREA" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_QUESTION_BANK
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_QUESTION_BANK
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_QUESTION_BANK" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_QUESTION_BANK" ADD CHECK ("NAME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_QUESTION_BANK
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_QUESTION_BANK" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_RESEARCH
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_RESEARCH
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD CHECK ("CREDITS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD CHECK ("AUDIT_STATE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD CHECK ("CREATE_USER" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_RESEARCH
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_RESEARCH_ANSWER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_RESEARCH_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("RESEARCH_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("SURVEY_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("SURVEY_ANSWER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD CHECK ("CLASSIFICATION" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_RESEARCH_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH_ANSWER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_RESEARCH_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_RESEARCH_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH_ATTENDANCE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_RESEARCH_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_RESEARCH_ATTENDANCE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_SUMMARY_ATTACHMENT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_SUMMARY_ATTACHMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SUMMARY_ATTACHMENT" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_SUMMARY_ATTACHMENT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SUMMARY_ATTACHMENT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_SUPPLIER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("SID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("SNAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("STYLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("SC_TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("MAIN_COURSE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("LINKMAN" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("POSITION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("TEL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SUPPLIER" ADD CHECK ("PHONE_NUMBER" IS NOT NULL);

-- ----------------------------
-- Checks structure for table THINK_SUPPLIER_TYPE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SUPPLIER_TYPE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_SURVEY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_SURVEY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_DESC" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_CAT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_HEIR" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_UPLOAD_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("IS_AVAILABLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("PRINCIPAL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("START_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("END_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD CHECK ("SURVEY_MODE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_SURVEY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_SURVEY_ANSWER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("SURVEY_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("U_SURVEY_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("SURVEY_ANSWER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("CLASSIFICATION" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ANSWER" ADD CHECK ("QUESTION_NUMBER" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_SURVEY_ATTENDANCE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_SURVEY_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("SURVEY_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("PROJECT_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD CHECK ("DEPARTMENT_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_SURVEY_ATTENDANCE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_ATTENDANCE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_SURVEY_CATEGORY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_CATEGORY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_CATEGORY" ADD CHECK ("PID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_CATEGORY" ADD CHECK ("SORT" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_SURVEY_ITEM
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_SURVEY_ITEM
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_ITEM" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ITEM" ADD CHECK ("SURVEY_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ITEM" ADD CHECK ("TITLE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_SURVEY_ITEM" ADD CHECK ("CTIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_SURVEY_ITEM
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_SURVEY_ITEM" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_TEST
-- ----------------------------

-- ----------------------------
-- Triggers structure for table THINK_TEST
-- ----------------------------
CREATE OR REPLACE TRIGGER "SCOTT"."TIG_TEST" BEFORE INSERT ON "SCOTT"."THINK_TEST" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW ENABLE
declare
  nextid number;
begin
  if :new.id is null or :new.id = 0 then
    select SEQ_TEST.nextval
      into nextid
      from sys.dual;
    :new.id := nextid;
  end if;
-- ----------------------------
-- Checks structure for table THINK_TEST
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("CREATE_USER" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("START_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("END_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("AUDIT_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("AUDIT_STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("EXAMINATION_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST" ADD CHECK ("ADDRESS" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TEST
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TEST" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Checks structure for table THINK_TEST_USER_REL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TEST_USER_REL" ADD CHECK ("TEST_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TEST_USER_REL" ADD CHECK ("USER_ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_TISSUE_AUTH
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table THINK_TISSUE_AUTH
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TISSUE_AUTH" ADD PRIMARY KEY ("USER_ID");

-- ----------------------------
-- Indexes structure for table THINK_TISSUE_GROUP_ACCESS
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TISSUE_GROUP_ACCESS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("TISSUE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("JOB_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("MANAGE_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("BRANCH_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD CHECK ("SERIAL_NUMBER" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TISSUE_GROUP_ACCESS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TISSUE_GROUP_ACCESS" ADD PRIMARY KEY ("USER_ID");

-- ----------------------------
-- Checks structure for table THINK_TISSUE_RULE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TISSUE_RULE" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Indexes structure for table THINK_TOOL_LEARNING
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TOOL_LEARNING
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_LEARNING" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TOOL_LEARNING
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_LEARNING" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_TOOL_TEACHING
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TOOL_TEACHING
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_TEACHING" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TOOL_TEACHING
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_TEACHING" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_TOOL_TRAIN
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TOOL_TRAIN
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_TRAIN" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TOOL_TRAIN
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOOL_TRAIN" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_TOPIC_GROUP
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TOPIC_GROUP
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOPIC_GROUP" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_GROUP" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_GROUP" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_GROUP" ADD CHECK ("PUBLISH_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TOPIC_GROUP
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOPIC_GROUP" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_TOPIC_PERSONNEL
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_TOPIC_PERSONNEL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" ADD CHECK ("TOPIC_GROUP_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" ADD CHECK ("STATUS" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_TOPIC_PERSONNEL
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_TOPIC_PERSONNEL" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USER
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER" ADD CHECK ("USERNAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER" ADD CHECK ("PASSWORD" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER" ADD CHECK (id IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USER
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USER_COMPANY
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USER_COMPANY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER_COMPANY" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_COMPANY" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_COMPANY" ADD CHECK ("COMPANY_ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USER_COMPANY
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER_COMPANY" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USER_IMPORT
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USER_IMPORT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("PHONE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("EMAIL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("BIRTHDAY" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("JOB_NUM" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("ERROR_TYPE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("AREA" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("ROOM" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("AGE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("SEQUENCE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("JOB_NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("JOB_LEVEL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("USER_LEVEL" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("EDU" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("GROUP_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("CENTER_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("OFFICE_PHONE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD CHECK ("IP_PHONE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USER_IMPORT
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USER_IMPORT" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USERS
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USERS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK (id IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("USERNAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("PASSWORD" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("AVATAR" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("STATUS" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("REGISTER_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_USERS" ADD CHECK ("AGE" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USERS
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USERS_TAG
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USERS_TAG
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS_TAG" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USERS_TAG
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS_TAG" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_USERS_TAG_RELATION
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_USERS_TAG_RELATION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS_TAG_RELATION" ADD CHECK ("ID" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_USERS_TAG_RELATION
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_USERS_TAG_RELATION" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_VERIFY_CODE
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_VERIFY_CODE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("PHONE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("VERIFY_CODE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("VERIFY_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("TIMES" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("CREATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD CHECK ("M_TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_VERIFY_CODE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_VERIFY_CODE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_WELFARE
-- ----------------------------

-- ----------------------------
-- Triggers structure for table THINK_WELFARE
-- ----------------------------
CREATE OR REPLACE TRIGGER "SCOTT"."tig_welfare" BEFORE INSERT ON "SCOTT"."THINK_WELFARE" REFERENCING OLD AS "OLD" NEW AS "NEW" FOR EACH ROW ENABLE WHEN (new.id is null)
begin
select seq_welfare.nextval into :new.id from dual;
end;
-- ----------------------------
-- Checks structure for table THINK_WELFARE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("NEED_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("ADD_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("UPDATE_TIME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("IS_DELETE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD CHECK ("IS_PUBLIC" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_WELFARE
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_WELFARE" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Indexes structure for table THINK_WELFARE_RECORD
-- ----------------------------

-- ----------------------------
-- Checks structure for table THINK_WELFARE_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD CHECK ("ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD CHECK ("NAME" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD CHECK ("USER_ID" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD CHECK ("NEED_SCORE" IS NOT NULL);
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD CHECK ("TIME" IS NOT NULL);

-- ----------------------------
-- Primary Key structure for table THINK_WELFARE_RECORD
-- ----------------------------
ALTER TABLE "SCOTT"."THINK_WELFARE_RECORD" ADD PRIMARY KEY ("ID");

-- ----------------------------
-- Foreign Key structure for table "SCOTT"."EMP"
-- ----------------------------
ALTER TABLE "SCOTT"."EMP" ADD FOREIGN KEY ("DEPTNO") REFERENCES "SCOTT"."DEPT" ("DEPTNO");
