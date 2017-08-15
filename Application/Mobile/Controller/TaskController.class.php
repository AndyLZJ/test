<?php
namespace Mobile\Controller;
use Think\Controller;
/**
 * @author Dujunqiang 20170311
 * 我的任务--审核加分
 */
class TaskController extends CommonController {
	/**
	 * 初始化
	 */
	function __construct(){
		parent::__construct();
	}
	
	//验证是否获取到用户，是否提交数据
	public function currVerifi(){
		$userId = $this->getUserId();
		if(empty($userId)){
			$this->error(1024, '用户不存在');
		}
	
		if(!IS_POST || !$_POST){
			$this->error(1025, '不合法数据请求');
		}
	
		return $userId;
	}
	
	/**
	 * 任务列表
	 * taskStatus 任务状态 1待办任务 2已完成,不传默认1
	 * page 页码，不传默认第1页
	 * pageLen 每页显示条数，默认30条
	 */
	public function getList(){
		$userId = $this->currVerifi();
		if($_POST["taskStatus"] != 2){
			$_POST["taskStatus"] = 1;
		}
		$_POST["page"] += 0;
		$_POST["pageLen"] += 0;
		if(!is_int($_POST["page"]) || $_POST["page"] < 1){
			$_POST["page"] = 1;
		}
		
		if(!is_int($_POST["pageLen"]) || $_POST["pageLen"] < 1){
			$_POST["pageLen"] = 30;
		}
		$return = D("Task")->getList($_POST,$userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 查看任务（待办任务  已完成 同一个接口）
	 * taskId 任务id
	 * taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
	 */
	public function detail(){
		$userId = $this->currVerifi();
		$_POST["taskId"] += 0;
		$_POST["taskType"] += 0;
		
		if(!is_int($_POST["taskId"]) || $_POST["taskId"] < 1){
			$this->error(1011, "缺少参数： taskId 审核任务id");
		}
		
		if(!is_int($_POST["taskType"]) || $_POST["taskType"] < 1){
			$this->error(1011, "缺少参数： taskType 审核任务类型");
		}
		$return = D("Task")->detail($_POST,$userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 审核任务
	 * taskId 任务id
	 * taskStatus 审核状态 1审核通过 2审核拒绝
	 * taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
	 */
	public function operate(){
		$userId = $this->currVerifi();
		$_POST["taskId"] += 0;
		if(!is_int($_POST["taskId"]) || $_POST["taskId"] < 1){
			$this->error(1011, "缺少参数：taskId 审核任务id");
		}
		
		$_POST["taskStatus"] += 0;
		if($_POST["taskStatus"] != 1 && $_POST["taskStatus"] != 2){
			$this->error(1012, "参数：taskStatus 审核状态有误");
		}
		
		$_POST["taskType"] += 0;
		if(!is_int($_POST["taskType"]) || $_POST["taskType"] < 1 || $_POST["taskType"] > 9){
			$this->error(1013, "缺少参数： taskType 审核任务类型");
		}
		
		$return = D("Task")->operate($_POST,$userId);
		if($return["code"] == 1000){
			$this->success(1000, $return["message"], $return["data"]);
		}else{
			$this->error($return["code"], $return["message"]);
		}
	}
}