<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/*
 * 考试报表控制器
 */
class ExamReportController extends AdminBaseController{

    /**
     * 考试报表
     * @return [type] [description]
     */
    public function index(){
        $model = D('ExamReport');
        $data = $model->getList();

        $this->assign('data',$data['res']);
        $this->assign('start_time',$data['start_time']);
        $this->assign('end_time',$data['end_time']);
        $this->assign('name',$data['name']);
        $this->assign('page',$data['page']);
        $this->display();
    }

    /**
     * 导出考试报表
     * @return [type] [description]
     */
    public function export(){
        $model = D('ExamReport');
        $data = $model->all();
        $xlsName  = "考试报表-".date('Y-m-d H:i:s');

        array_unshift($data,array('考试名称','考试方式','考试开始时间','考试结束时间','负责人','所属项目/计划','应到人数','实到人数','平均分'));
        create_xls($data,$xlsName);
    }

    /**
     * 讲师库报表
     * @return [type] [description]
     */
    public function lecturer(){
        $model = D('ExamReport');
        $data = $model->getLecturerList();

        //$tissue = $model->getTissues();
        //获取组织架构
        $tool_tree = D("Tool")->tree(0);

        $this->assign('tool_tree',$tool_tree);

        $this->assign('data',$data);
        $this->assign('in',$data['percentage'][0]);
        $this->assign('out',$data['percentage'][1]);
        $this->display();
    }

    /**
     * 讲师课程列表
     * @return [type] [description]
     */
    public function details(){
        $model = D('ExamReport');
        $data = $model->getCourse();

        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 导出讲师库报表
     * @return [type] [description]
     */
    public function exportLecturer(){
        $model = D('ExamReport');
        $data = $model->exportLecturer();
        $xlsName  = "讲师库报表-".date('Y-m-d');

        array_unshift($data,array('讲师来源','所在部门','讲师姓名','授课数量（次）','讲师评分'));
        create_xls($data,$xlsName);
    }

    /**
     * 数据管理-调研报表
     * @return [type] [description]
     */
    public function survey(){
        $model = D('ExamReport');
        $data = $model->getSurveyList();

        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 调研详情
     *
     */
    public function article(){

        $research_id = I('get.research_id');
        $project_id = I('get.project_id');
        $survey_id = I('get.survey_id');

        $data = D('ExamReport')->article();

        $this->assign($data);
        $this->assign("research_id",$research_id);
        $this->assign("project_id",$project_id);
        $this->assign("survey_id",$survey_id);
        $this->display();

    }

    /**
     * 查看详情
     */
    public function checkArticle(){

        $total_page = $this->total_page;

        $data = D('ExamReport')->checkArticle($total_page);

        $this->assign($data);

        $this->display();

    }

    /**
     * 查看用户详情
     */
    public function userArticle(){

        $data = D('ExamReport')->userArticle();

        $this->assign($data);

        $this->display();

    }

    /**
     * 导出所有结果s
     */
    public function exportAll(){

        $data = D('ExamReport')->exportAll();

        $xlsName  = "调研报表-".date('Y-m-d');

        create_xls($data,$xlsName);
    }

    /**
     * 所有问卷信息
     */
    public function addQuestionnaire(){

        $total_page = $this->total_page;

        $data = D('ExamReport')->addQuestionnaire($total_page);

        $this->assign($data);

        $this->display();
    }

    /**
     * 导出调研报表
     * @return [type] [description]
     */
    public function exportSurvey(){

        $model = D('ExamReport');

        $data = $model->getSurveyList();

        $items = array();

        foreach($data['list'] as $k=>$list){

            $items[$k]['name'] = $list['name'];
            $items[$k]['project_name'] = $list['project_name'];
            $items[$k]['username'] = $list['username'];
            $items[$k]['start_time'] = $list['start_time'];
            $items[$k]['end_time'] = $list['end_time'];
            $items[$k]['should'] = $list['should'];
            $items[$k]['real'] = $list['real'];
        }

        $xlsName  = "调研报表-".date('Y-m-d');

        array_unshift($items,array('问卷名称','所属项目/计划','负责人','开始时间','结束时间','应到人数','实到人数'));

        create_xls($items,$xlsName);
    }

    /**
     * 课程库报表
     * @return [type] [description]
     */
    public function course(){
        $model = D('ExamReport');
        $data  = $model->getCourseData();
        $typeInfo = $model->getCourseTypeInfo();
        $cate = $model->getCourseCate();

        // dump($data);
        $this->assign('data',$data);
        $this->assign('typeInfo',$typeInfo);
        $this->assign('cate',$cate);
        $this->display();
    }

    /**
     * 导出课程库报表
     * @return [type] [description]
     */
    public function exportCourse(){
        $model = D('ExamReport');
        $data = $model->exportCourse();
        $xlsName  = "课程库报表-".date('Y-m-d');

        array_unshift($data,array('课程分类','授课方式','课程名称','授课讲师','课程评价','学习次数','累计学时'));
        create_xls($data,$xlsName);
    }

    /**
     * 部门学时统计
     * @return [type] [description]
     */
    public function hoursStat(){
        $model = D('ExamReport');
        $data = $model->getHours();
        $dep = $model->getDep();    //所有的部门

        $this->assign('data',$data);    //学时信息
        $this->assign('dep',$dep);//部门信息拼装

        $this->display();
    }

    /**
     * 导出部门学时信息
     * @return [type] [description]
     */
    public function exportHours(){
        $model = D('ExamReport');
        $data = $model->getAllHours();
		
        if(!$data['list']){
        	$data['list'] = array();
        }
        $xlsName  = "部门学时报表-".date('Y-m-d');
        array_unshift($data['list'],$data['dep']);
        create_xls($data['list'],$xlsName);
    }

}