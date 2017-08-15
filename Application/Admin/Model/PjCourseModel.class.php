<?php
namespace Admin\Model;

use Common\Model\BaseModel;

class PjCourseModel extends BaseModel{


	//初始化
	public function __construct(){}


	/**
	 * 获取项目考试
	 */
	public function index($total_page){

		$start_page = I("get.p",0,'int');

		$keywords = I("get.keywords");

		if(!empty($keywords)){
			$where['a.test_name'] = array("like","%$keywords%");
		}

		$where['a.status'] = array("eq",1);

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$where['a.auth_user_id'] = array("in",$specifiedUser);

		$list = M("examination a")
                ->join("LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id")
                ->field("a.id,a.test_name,a.auth_user_id,b.cat_name")
                ->order('a.id asc')
                ->where($where)
                ->page($start_page,$total_page)
                ->select();

		$count = M("examination a")->where($where)->count();

		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

		$show = $this->pageClass($count,$total_page);

		//获取试卷类型
		$examination_category = M("examination_category")->order('id asc')->select();

		$data = array(
			"examination_category"=>$examination_category,
			"list" =>$list,
			"page"=>$show
		);

		return $data;

	}

	/**
	 * 获取项目课程
	 */
	public function course($total_page){

		$start_page = I("get.p",0,'int');

		$keywords = I("get.keywords");

		$typeid = 1;

		$parameter = array("p"=>$start_page,"typeid"=>$typeid);

		$conditions['a.status'] = array("eq",$typeid);
		//$conditions['a.is_public'] = array("eq",1);
		$conditions['a.auditing'] = array("eq",1);
		
		if(!empty($keywords)){
			$conditions['a.course_name'] = array("like","%$keywords%");
		}

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$conditions['a.auth_user_id'] = array("in",$specifiedUser);

		$list = M("course a")
			->field("a.id,a.course_name,a.course_time,a.is_public,a.course_way,a.create_time,a.auth_user_id,c.cat_name,d.username")
			->join("LEFT JOIN __COURSE_DETAIL__ b ON a.id = b.id LEFT JOIN __COURSE_CATEGORY__ c ON a.course_cat_id = c.id LEFT JOIN __USERS__ d ON a.user_id = d.id")
			->order('a.id desc')
			->where($conditions)
			->page($start_page,$total_page)
			->select();

		$count = M("course a")
			->field("a.course_name,a.course_time,a.is_public,a.course_way,a.create_time,a.auth_user_id,c.cat_name")
			->join("LEFT JOIN __COURSE_DETAIL__ b ON a.id = b.id LEFT JOIN __COURSE_CATEGORY__ c ON a.course_cat_id = c.id")
			->where($conditions)
			->order('a.id desc')
			->count();

		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

		$show = $this->pageClass($count,$total_page,$parameter);

		$data = array(
			"list"=>$list,
			"pages"=>$show
		);

		return $data;

	}

	/**
	 * 获取项目 - 调研
	 */
	public function research($total_page){

		$start_page = I("get.p",0,'int');

		$keywords = I("get.keywords");

		if(!empty($keywords)){
			$conditions['a.survey_name'] = array("like","%$keywords%");
		}

		$conditions['a.status'] = array("eq",1);
		$conditions['a.is_available'] = array("eq",1);

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$conditions['a.auth_user_id'] = array("in",$specifiedUser);

		$list = M("survey a")
			->field("a.id,a.survey_name,a.auth_user_id,b.cat_name")
			->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
			->order('a.id desc')
			->where($conditions)
			->page($start_page,$total_page)
			->select();

		$count = M("survey a")
			->field("*")
			->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
			->order('a.id desc')
			->where($conditions)
			->count();

		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

		$show = $this->pageClass($count,$total_page);

		$data = array(
			"list"=>$list,
			"pages"=>$show
		);

		return $data;
	}



	/**
	 * 获取当前组织下所有的用户
	 */
	public function getOrganization(){

		$user_str = $this->getRights();

		$conditions['a.user_id'] = array("in",$user_str);
		$conditions['a.manage_id'] = array("eq",2);

		$list = M("tissue_group_access a")
			->field("b.id,b.username")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
			->where($conditions)
			->select();

		return $list;
	}
	
}