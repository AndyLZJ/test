<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 个人中心_授课目标
 * @author Dujuqiang 20170317
 */
class MyGoalController extends AdminBaseController{
	/**
	 * 授课目标首页
	 */
	public function goalPage(){

		$typeid = I('get.typeid');

		$user_id = $_SESSION["user"]["id"];

		if(!$user_id){

			echo "未获取到用户，请登录";
			exit;

		}

		$data = D("MyGoal")->getData();

		$this->assign($data);

		$total_page = $this->total_page;

		//授课情况
		if($typeid == 1){

			//调培训项目课程数据
			$course_list = D("MyGoal")->teaching_data($total_page);


			$this->assign("course_list",$course_list);
			$this->display('teachingsituation');

		//课程开发情况
		}elseif($typeid == 2){


			$course_list = D("MyGoal")->course_data($total_page);

			$this->assign("course_list",$course_list);

			$this->display('curriculumdevelopment');

		}else{

			$data = D("MyGoal")->all_data($total_page);

			$this->assign($data);

			$this->display();
		}


	}

	/***
	 * 培训项目详情
	 */

	public function detail()
	{

		$items = D("Manage")->detail();

		$this->assign($items);

		if($items['project']['is_public'] == 0){
			$department_name = array("不公开");
		}else{
			foreach($items['department'] as $item){
				$department_name[] = $item['name'];
			}
		}

		$department = implode(",",$department_name);
		$this->assign('department',$department);
		$this->display();

	}

	/**
	 * 导出全部Doc
	 */
	public function export_all(){

		//调培训项目课程数据
		$data = D("MyGoal")->all_data();

		$items = array($data);

		$xlsName  = "内训师档案-".date('Y-m-d H:i:s');

		array_unshift($items,array('序号','姓名','所属部门','授课次数','授课课时','授课满意度','课程开发门数','课程开发时数'));

		create_xls($items,$xlsName);

	}

	/**
	 * 导出授课情况 Doc
	 */
	public function export_teaching(){

		$total_page = $this->total_page;

		//调培训项目课程数据
		$data = D("MyGoal")->teaching_data($total_page);

		$items = array();

		foreach($data['list'] as $k=>$list){

			if($list['training_category'] == 0){

				$category_name = "内部培训";

			}elseif($list['training_category'] == 1){

				$category_name = "外派培训";

			}elseif($list['training_category'] == 2){

				$category_name = "外出授课";

			}else{

				$category_name = "--";

			}

			$items[$k]['k'] = $k+1;
			$items[$k]['tissue_name'] = $list['tissue_name'];
			$items[$k]['project_name'] = $list['project_name'];
			$items[$k]['category_name'] = $category_name;
			$items[$k]['start_time'] = $list['start_time'];
			$items[$k]['end_time'] = $list['end_time'];
			$items[$k]['course_name'] = $list['course_name'];
			$items[$k]['project_description'] = $list['project_description'];
			$items[$k]['course_time'] = $list['course_time'];
			$items[$k]['percentage'] = $list['percentage'];

		}

		$xlsName  = "授课情况-".date('Y-m-d H:i:s');

		array_unshift($items,array('序号','主办部门','培训班名称','培训类别','开始时间','结束时间','培训课程名称','培训对象','授课课时','授课满意度'));

		create_xls($items,$xlsName);
	}

	/**
	 * 导出课程开发情况 Doc
	 */

	public function export_course(){

		$total_page = $this->total_page;

		$data = D("MyGoal")->course_data($total_page);

		$items = array();

		foreach($data['list'] as $k=>$list){

			if($list['arrangement_id'] == 1){

				$arrangement_name = "基础层";

			}elseif($list['arrangement_id'] == 2){

				$arrangement_name = "中间层";

			}elseif($list['arrangement_id'] == 3){

				$arrangement_name = "核心层";

			}elseif($list['arrangement_id'] == 4){

				$arrangement_name = "专业层";

			}else{

				$arrangement_name = "--";

			}

			$items[$k]['k'] = $k+1;
			$items[$k]['course_name'] = $list['course_name'];
			$items[$k]['cat_name'] = $list['cat_name'];
			$items[$k]['course_time'] = $list['course_time'];
			$items[$k]['arrangement_name'] = $arrangement_name;
			$items[$k]['create_time'] = date("Y-m-d H:i:s",$list['create_time']);
		}

		$xlsName  = "课程开发情况-".date('Y-m-d H:i:s');

		array_unshift($items,array('序号','课程名称','课程类别','课程时长','所属层次','发布时间'));

		create_xls($items,$xlsName);

	}
}