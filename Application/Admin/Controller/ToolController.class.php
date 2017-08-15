<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 *
 * @Andy
 */
class ToolController extends AdminBaseController{
	
	/**
	 * 目标管理
	*/
	public function target(){

		$tissue_id = I("get.tissue_id",0,'int');
		$job_id = I("get.job_id",0,'int');
		$year = I("get.year",0,'int');
		$plan = I("get.plan",0,'int');

		//获取组织架构
		$tool_tree = D("Tool")->tree(0);

		$level = D('AdminTissue')->hierarchy($tool_tree[0]['id']);
		$this->assign('level',$level);

		//获取组织下所有岗位信息
		$Job_list = D("Tool")->getJob();

		//获取学习目标
		$target_list = D("Tool")->target();

		//获取年份
		$time_year = date("Y",time());

		for($i=0;$i<5;$i++){
			$year_list[$i] = $time_year+$i;
		}

		$rows = array();

		if($plan == 3){

			$month_list = array(".january",".february",".march",".april",".may",".june",".july",".august",".september",".october",".november",".december");

		}elseif($plan == 2){

			foreach($target_list as $k=>$list){

				$one_season = $list['january']+$list['february']+$list['march'];

				$tow_season = $list['april']+$list['may']+$list['june'];

				$three_season = $list['july']+$list['august']+$list['september'];

				$four_season = $list['october']+$list['november']+$list['december'];

				$rows[$k]['one_season'] = ceil($one_season);
				$rows[$k]['tow_season'] = ceil($tow_season);
				$rows[$k]['three_season'] = ceil($three_season);
				$rows[$k]['four_season'] = ceil($four_season);

			}

			$target_list = $rows;

			$month_list = array(".january",".february",".march",".april");
		}else{


			foreach($target_list as $list){

				$total = $list['january']+$list['february']+$list['march']+$list['april']+$list['may']+$list['june']+$list['july']+$list['august']+$list['september']+$list['october']+$list['november']+$list['december'];

				$rows[] = ceil($total);

			}

			$target_list = $rows;

			$month_list = array(".january");
		}

		$this->assign("tissue_id",$tissue_id);
		$this->assign("job_id",$job_id);
		$this->assign("year",$year);
		$this->assign("plan",$plan);
		$this->assign("month_list",$month_list);
		$this->assign("year_list",$year_list);
		$this->assign("tool_tree",$tool_tree);
		$this->assign("job_list",$Job_list);
		$this->assign("target_list",$target_list);
		$this->display();

	}

	/**
	 * 授课目标
	 */
	public function teaching(){

		$tissue_id = I("get.tissue_id",0,'int');
		$lecturer_id = I("get.lecturer_id",0,'int');
		$year = I("get.year",0,'int');
		$plan = I("get.plan",0,'int');

		//获取组织架构
		$tool_tree = D("Tool")->tree(0);

		$level = D('AdminTissue')->hierarchy($tool_tree[0]['id']);
		$this->assign('level',$level);

		//获取组织下所有岗位信息
		$Lecturer_list = D("Tool")->getLecturer();

		//获取学习目标
		$target_list = D("Tool")->teaching();

		//获取年份
		$time_year = date("Y",time());

		for($i=0;$i<5;$i++){
			$year_list[$i] = $time_year+$i;
		}

		if($plan == 3){
			$month_list = array(".january",".february",".march",".april",".may",".june",".july",".august",".september",".october",".november",".december");
		}elseif($plan == 2){

			foreach($target_list as $k=>$list){

				$one_season = $list['january']+$list['february']+$list['march'];

				$tow_season = $list['april']+$list['may']+$list['june'];

				$three_season = $list['july']+$list['august']+$list['september'];

				$four_season = $list['october']+$list['november']+$list['december'];

				$rows[$k]['one_season'] = ceil($one_season);
				$rows[$k]['tow_season'] = ceil($tow_season);
				$rows[$k]['three_season'] = ceil($three_season);
				$rows[$k]['four_season'] = ceil($four_season);

			}

			$target_list = $rows;

			$month_list = array(".january",".february",".march",".april");


		}else{

			foreach($target_list as $list){

				$total = $list['january']+$list['february']+$list['march']+$list['april']+$list['may']+$list['june']+$list['july']+$list['august']+$list['september']+$list['october']+$list['november']+$list['december'];

				$rows[] = ceil($total);

			}

			$target_list = $rows;

			$month_list = array(".january");
		}

		$this->assign("tissue_id",$tissue_id);
		$this->assign("lecturer_id",$lecturer_id);
		$this->assign("year",$year);
		$this->assign("plan",$plan);
		$this->assign("month_list",$month_list);
		$this->assign("year_list",$year_list);
		$this->assign("tool_tree",$tool_tree);
		$this->assign("lecturer_list",$Lecturer_list);
		$this->assign("target_list",$target_list);
		$this->display();

	}

	/**
	 * 培训项目预算
	 */
	public function train(){

		//获取组织架构
		$tool_tree = D("Tool")->tree();

		$train_data = D("Tool")->train();

		$level = D('AdminTissue')->hierarchy($tool_tree[0]['id']);
		$this->assign('level',$level);

		$this->assign("train_data",$train_data);
		$this->assign("tool_tree",$tool_tree);

		$this->display();

	}

	/**
	 * 添加培训项目预算
	 */
	public function addTrain(){

		$result = D("Tool")->addTrain();

		$this->ajaxReturn($result,'json');
	}

	/**
	 * 修改目标管理学时
	 */
	public function uptarget(){

		$result = D("Tool")->uptarget();

		$this->ajaxReturn($result,'json');
	}

	/**
	 * 修改授课目标
	 */
	public function upteaching(){

		$result = D("Tool")->upteaching();

		$this->ajaxReturn($result,'json');

	}

	/**
	 * 修改培训项目预算
	 */
	public function uptrain(){

		$result = D("Tool")->uptrain();

		$this->ajaxReturn($result,'json');
		
	}

	/**
	 * 获取工具管理 - 岗位数据
	 */
	public function AjaxJob(){

		//获取组织下所有岗位信息
		$Job_list = D("Tool")->getJob();

		$this->ajaxReturn($Job_list,'json');

	}

	/**
	 * 获取所属组织下所有讲师
	 */
	public function getLecturer(){

		//获取组织下所有讲师信息
		$Lecturer_list = D("Tool")->getLecturer();

		$this->ajaxReturn($Lecturer_list,'json');
	}

}
