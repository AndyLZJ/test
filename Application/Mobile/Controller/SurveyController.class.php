<?php
namespace Mobile\Controller;
use Think\Controller;
/**
 * @author Dujunqiang 20170308
 * 我的调研
 */
class SurveyController extends CommonController{
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
			$this->error(1024,'用户不存在');
		}
		
		if(!IS_POST || !$_POST){
			$this->error(1025,'不合法数据请求');
		}
		
		return $userId;
	}
	
	/**
	 * 获取调研列表 post
	 * proStatus 调研状态  1带调研 2已结束
	 * 
	 */
	public function getList(){
		$userId = $this->currVerifi();
		if($_POST["proStatus"] != 2){
			$_POST["proStatus"] = 1;
		}
		
		$return = D("survey")->getList($_POST,$userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 获取调研题目
	 * survey_type 问卷类型 int 必须 1培训项目问卷  2组织调研问卷
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 */
	public function getQues(){
		$userId = $this->currVerifi();
		$post = I("post.");
		$post["survey_type"] += 0;
		$post["survey_id"] += 0;
		$post["project_id"] += 0;
		$post["research_id"] += 0;
		
		if($post["survey_type"] != 1 && $post["survey_type"] != 2){
			$this->error(1011, "survey_type 调研类型参数有误");
		}
		
		if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
			$this->error(1012, "缺少 survey_id 问卷ID");
		}
		
		if($post["survey_type"] == 1 && (!is_int($post["project_id"]) || $post["project_id"] < 1)){
			$this->error(1013, "缺少 project_id 项目ID");
		}
		
		if($post["survey_type"] == 2 && (!is_int($post["research_id"]) || $post["research_id"] < 1)){
			$this->error(1014, "缺少 research_id 调研ID");
		}
		
		$return = D("survey")->getQues($post,$userId);
		$data["survey_type"] = $post["survey_type"];
		$data["survey_id"] = $post["survey_id"];
		$data["project_id"] = $post["project_id"];
		$data["research_id"] = $post["research_id"];
		$data["list"] = $return["data"];
		if($return["code"] == 1000){
			$this->success(1000, '获取数据成功', $data);
		}else{
			$this->error($return["code"], $return["message"]);
		}
	}
	
	/**
	 * 提交答案
	 * survey_type 问卷类型 int 必须 1培训项目问卷  2组织调研问卷
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 * topicId 题目ID int 必选
	 * answer 题目答案 string 必选
	 */
	public function answer(){
		$userId = $this->currVerifi();
		$post = I("post.");
		$post["survey_type"] += 0;
		$post["survey_id"] += 0;
		$post["project_id"] += 0;
		$post["research_id"] += 0;
		$post["topicId"] += 0;
		
		if($post["survey_type"] != 1 && $post["survey_type"] != 2){
			$this->error(1011, "survey_type 调研类型参数有误");
		}
		if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
			$this->error(1012, "缺少 survey_id 问卷ID");
		}
		if($post["survey_type"] == 1 && (!is_int($post["project_id"]) || $post["project_id"] < 1)){
			$this->error(1013, "缺少 project_id 项目ID");
		}
		if($post["survey_type"] == 2 && (!is_int($post["research_id"]) || $post["research_id"] < 1)){
			$this->error(1014, "缺少 research_id 调研ID");
		}
		if(!is_int($post["topicId"]) || $post["topicId"] < 1){
			$this->error(1015, "topicId 题目ID必须为大于0的整数");
		}
		if(!$post["answer"] || $post["answer"] == ""){
			$this->error(1016, "缺少参数：answer 题目答案");
		}
		
		$return = D("survey")->answer($post,$userId);
		if($return["code"] == 1000){
			$this->success(1000, '操作成功', $return["data"]);
		}else{
			$this->error($return["code"], $return["message"]);
		}
	}
	
	/**
	 * 提交调研
	 * survey_type 问卷类型 int 必须 1培训项目问卷  2组织调研问卷
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 * answer 答案 json格式 {"89":"A","90":"B","91":"C","92":"简答题文本内容"}
	 */
	public function finish(){
		$userId = $this->verifyUserDataPost();
		$post = I("post.");
		$post["survey_type"] += 0;
		$post["survey_id"] += 0;
		$post["project_id"] += 0;
		$post["research_id"] += 0;
		
		if($post["survey_type"] != 1 && $post["survey_type"] != 2){
			$this->error(1011, "survey_type 调研类型参数有误");
		}
		
		if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
			$this->error(1012, "缺少 survey_id 问卷ID");
		}
		
		if($post["survey_type"] == 1 && (!is_int($post["project_id"]) || $post["project_id"] < 1)){
			$this->error(1013, "缺少  project_id 项目ID");
		}
		
		if($post["survey_type"] == 2 && (!is_int($post["research_id"]) || $post["research_id"] < 1)){
			$this->error(1014, "缺少 research_id 调研ID");
		}
		
		$answerArray = json_decode($post["answer"], true);
		if(!is_array($answerArray) || count($answerArray) == 0){
			$this->error(1014, '请提交问卷答案');
		}
		$post["answer"] = $answerArray;
		
		@D('Trigger')->intergrationTrigger($userId, 9);
		
		$return = D("survey")->finish($post,$userId);
		if($return["code"] == 1000){
			$this->success(1000, '操作成功', $return["data"]);
		}else{
			$this->error($return["code"], $return["message"]);
		}
	}
	
	/**
	 * 已结束查看调研结果
	 * survey_type 问卷类型 int 必须 1培训项目问卷  2组织调研问卷
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 * topicId 题目ID int 可选，不填默认第一题
	 */
	public function seeDetail(){
		$userId = $this->getUserId();
		if(empty($userId)){
			$this->error(1024,'用户不存在');
		}
		
		$post = I("get.");
		$post["survey_type"] += 0;
		$post["survey_id"] += 0;
		$post["project_id"] += 0;
		$post["research_id"] += 0;
		$post["topicId"] += 0;
		
		$data["code"] = 1000;
		$data["message"] = "成功";
		if($post["survey_type"] != 1 && $post["survey_type"] != 2){
			//$this->error(1011, "survey_type 调研类型参数有误");
			$data["code"] = 1011;
			$data["message"] = "survey_type 调研类型参数有误";
		}
		
		if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
			//$this->error(1012, "缺少 survey_id 问卷ID");
			$data["code"] = 1012;
			$data["message"] = "缺少 survey_id 问卷ID";
		}
		
		if($post["survey_type"] == 1 && (!is_int($post["project_id"]) || $post["project_id"] < 1)){
			//$this->error(1013, "缺少 project_id 项目ID");
			$data["code"] = 1013;
			$data["message"] = "缺少 project_id 项目ID";
		}
		
		if($post["survey_type"] == 2 && (!is_int($post["research_id"]) || $post["research_id"] < 1)){
			//$this->error(1014, "缺少 research_id 调研ID");
			$data["code"] = 1014;
			$data["message"] = "缺少 research_id 调研ID";
		}
		
		if($post["topicId"] != 0 && (!is_int($post["topicId"]) || $post["topicId"] < 1)){
			//$this->error(1015, "topicId 题目ID必须为大于0的整数");
			$data["code"] = 1015;
			$data["message"] = "topicId 题目ID必须为大于0的整数";
		}
		if($data["code"] == 1000){
			$data = D("survey")->seeDetail($post, $userId);
		}
		
		$post["auth"] = $post["token"] ."20f883e". $post["secret_key"];
		$post["auth"] = base64_encode($post["auth"]);
		$data["base"] = $post;
		
		$this->assign($data);
		$this->display("HFive/survey");
	}
}