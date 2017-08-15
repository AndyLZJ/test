<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AdminAuditUsersModel extends BaseModel{

	//初始化
	public function __construct(){}

	/**
	 *  获取注册审核会员列表
	 */
	public function getMembersList($total_page = 10){

		$start_page = I("get.p",0,'int');

		$type = I("get.type");

		if(empty($type)){

			$list = M("users")->order('register_time desc')->where("status = 2 and status != 3")->page($start_page,$total_page)->select();

			$count = M("users")->where('status = 2 and status != 3')->count();

		}else{

			$list = M("users")->order('register_time desc')->where("status = 0 and status != 3")->page($start_page,$total_page)->select();

			$count = M("users")->where('status = 0 and status != 3')->count();

		}

		$show = $this->pageClass($count,$total_page);

		$data = array(
			"list" =>$list,
			"page"=>$show,
			"type"=>$type
		);

		return $data;
	}

	/**
	 *  会员状态修改
	 */
	public function statusUpdate(){

		$ids = I("post.ids");

		$data = array("id"=>array("in",$ids));

		$status_num = I("post.status",0);

		$user_all = explode(",",$ids);

		if(!empty($user_all)){

			foreach($user_all as $user_id){

				$user_id = M("tissue_group_access")->where("user_id=".$user_id)->find();

				$group_user_id = M("auth_group_access")->where("user_id=".$user_id)->find();

				if(empty($user_id)){

					M('tissue_group_access')->add(array("user_id"=>$user_id,"tissue_id"=>0,"job_id"=>0,"manage_id"=>0));

				}

				if(empty($group_user_id)){
					M('auth_group_access')->add(array("user_id"=>$user_id,"group_id"=>3));
				}

			}

			$results = M("users")->where($data)->setField("status",$status_num);

		}else{

			$results = false;

		}

		return $results;

	}

	/**
	 *  岗位列表
	 */
	public function jobsList($total_page = 10){

		$start_page = I("get.p",0,'int');

		if(IS_GET){

			$keywords = I("get.keywords");

			$conditions['name'] = array("like","%$keywords%");

			$list = M("jobs_manage")->where($conditions)->order('id desc')->page($start_page,$total_page)->select();

			$count = M("jobs_manage")->where($conditions)->count();

		}else{


			$list = M("jobs_manage")->order('id desc')->page($start_page,$total_page)->select();

			$count = M("jobs_manage")->count();

		}

		$show = $this->pageClass($count,$total_page);

		$data = array(
			"list" =>$list,
			"page"=>$show
		);

		return $data;


	}

	/**
	 *  添加 - 岗位管理
	 */
	public function addJobs(){

		$name = I("post.name");

		$tissueId = $this->tissueId();


		$where['name'] = array("eq",$name);

		$is_jobs_manage = M("jobs_manage")->where($where)->find();

		if(empty($is_jobs_manage)){
			$data = array("name"=>$name,"tissue_id"=>$tissueId);
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('jobs_manage');
			}
			$results = M("jobs_manage")->data($data)->add();
		}else{
			$results = false;
		}

		return $results;

	}

	/**
	 *  删除 - 岗位管理
	 */
	public function delJobs(){

		$ids = I("post.ids");

		$data = array("id"=>array("in",$ids));


		$job_id = M("tissue_group_access")->where(array("job_id"=>array("in",$ids)))->select();

		if(!empty($job_id)){

			$results = 0;

		}else{

			$results = M("jobs_manage")->where($data)->delete();
		}

		return $results;

	}

	/**
	 *  编辑 - 岗位管理
	 */
	public function editorJobs(){

		$id = I("post.id");

		$name = I("post.name");

		$data = array("id"=>$id);

		$info = M("jobs_manage")->where(array('id'=>array('neq',$name),'name'=>$name))->find();

		if($info){
			return array('status'=>0,'info'=>'该岗位名称已存在');
		}
		$results = M("jobs_manage")->where($data)->setField("name",$name);
		if($results){
			return array('status'=>1,'info'=>'编辑成功');
		}else{
			return array('status'=>0,'info'=>'编辑失败');
		}
	}
	
	
	//添加用户
	public function addUser($param){
		if(!$param){
			return false;
		}
		$user_id = $_SESSION["user"]["id"];
	
		$status = 1;//数据是否有效 0无效 1有效
		$error_type = 0;//无效类型 1岗位在系统中已存在 2岗位在当前列表中已存在
		
		//系统中已存在
		$hasSys = M("jobs_manage")->field("name")->where("name='".$param["name"]."'")->find();
		if($hasSys){
			$status = 0;
			$error_type = 1;
		}

		//文件中重复
		$hasFile = M("jobs_import")->field("name")->where("name='".$param["name"]."'")->find();
		if($hasFile){
			$status = 0;
			$error_type = 2;
		}
		
		$data["user_id"] = $user_id;
		$data["name"] = $param["name"];
		$data["status"] = $status;
		$data["error_type"] = $error_type;
		if($param["id"]){
			M("jobs_import")->where("id=".$param["id"])->save($data);
		}else{

			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('auth_group');
			}

			M("jobs_import")->add($data);
		}
	
		return array("code"=>1000, "message"=>"ok", "error_type"=>$error_type);
	}
	
	//获取导入结果
	public function importPage($param){
		$user_id = $_SESSION["user"]["id"];
		$totalNum = M("jobs_import")->field("count(id) as num")->where("user_id=".$user_id)->select();
		$successNum = M("jobs_import")->field("count(id) as num")->where("user_id=".$user_id." and status='1'")->select();
		$totalNum = $totalNum[0]["num"];
		$successNum = $successNum[0]["num"];
	
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 20;
		$start = ($param["page"] - 1) * $param["pageLen"];
	
		$where["user_id"] = $user_id;
		$list = M("jobs_import")->where($where)->limit($start, $param["pageLen"])->select();
	
		//输出分页
		$count = M("jobs_import")->field("count(id) as num")->where($where)->select();
		$pageNav = "";
		if($count[0]["num"] > $param["pageLen"]){
			$pageNav = $this->pageClass($count[0]["num"], $param["pageLen"]);
		}
	
		$data = array('pageNav' => $pageNav, 'list' => $list, "totalNum"=>$totalNum, "successNum"=>$successNum);
		return $data;
	}
	
	//移除临时表用户
	public function delUser($param){
		$user_id = $_SESSION["user"]["id"];
	
		$delNum = 0;
		foreach ($param as $key=>$value){
			$value += 0;
			if(is_int($value)){
				M("jobs_import")->where("id=".$value)->delete();
				$delNum ++;
			}
		}
	
		return array("code"=>1000, "message"=>"ok", "delNum"=>$delNum);
	}
	
	//编辑错误数据
	public function editData($param){
		$resp = $this->addUser($param);
		return $resp;
	}
	
	//保存有效结果
	public function saveValid(){
		$user_id = $_SESSION["user"]["id"];
	
		while (true){

			$userImport = M("jobs_import")->where("user_id=".$user_id." and status='1'")->select();
			if(!$userImport){
				break;
			}
	
			foreach ($userImport as $value){
				$data["name"] = $value["name"];

				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['id'] = getNextId('auth_group');
				}

				M("jobs_manage")->add($data);
				
				M("jobs_import")->where("id=".$value["id"])->delete();
			}
		}
		return array("code"=>1000, "message"=>"ok");
	}
	
	//取消导入--分批删除
	public function cancelImport(){
		$user_id = $_SESSION["user"]["id"];
		while (true){
			$has = M("jobs_import")->where("user_id=".$user_id)->find();
			if(!$has){
				break;
			}
	
			M("jobs_import")->where("user_id=".$user_id)->delete();
		}
	
		return array("code"=>1000, "message"=>"ok");
	}
	
}
