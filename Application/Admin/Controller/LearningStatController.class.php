<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/*
 * 员工学习统计控制器
 */
class LearningStatController extends AdminBaseController{

    public function index(){
        $model = D('LearningStat');
        $data = $model->getList();
        //$tissue = $model->getTissues();

        //获取组织架构
        $tool_tree = D("Tool")->tree(0);

        $this->assign('tool_tree',$tool_tree);
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 导出报表
     * @return [type] [description]
     */
    public function export(){
        $model = D('LearningStat');
        $data = $model->all();
        $xlsName  = "员工学习统计-".date('Y-m-d H:i:s');

        array_unshift($data,array('部门','姓名','岗位','学习课程','总学时','总积分','总学分'));
        create_xls($data,$xlsName);
    }
	
	/**
	 * 员工学习统计详情
	 */
	public function detail(){
		$data = D('LearningStat')->detail();
		$this->assign('uinfo',$data['uinfo']);
		$this->assign('page',$data['page']);
		$this->assign('course',$data['course']);
		$this->assign('hour',$data['hour']);
		$this->assign('integration',$data['integration']);
		$this->assign('test',$data['test']);
		$this->assign('survey',$data['survey']);
		
		$this->display();
	}
}