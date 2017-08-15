<?php
namespace App\Controller;
use Think\Controller;
   /**
    * @author Dujunqiang 20170304
    * 我的考试
    *
    */
class ExamController extends CommonController{
	//初始化
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取列表
	 * @param examStatus 考试状态  1待考试  2已结束  默认为1待考试
	 */
	public function myExam(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
			$get = I('get.');
			$get["page"] = $get["page"] ? $get["page"] : 1;
			$get["examStatus"] = $get["examStatus"] ? $get["examStatus"] : 1;
			if($get["examStatus"] != 1) $get["examStatus"] = 2;
			
			if(isset($get['page']) && isset($get['examStatus'])){
				$get['page'] = I('get.page', 1, 'int');//分页参数
				$get['examStatus'] = I("get.examStatus",1,'int');
				$info = D('Exam')->myExam($get,$userId);
				if(!$info){
					if($get["examStatus"] == 1){
						$this->error(1025,'管理员还没有给当前用户安排考试');
					}else{
						$this->error(1027,'还没有已结束的考试数据');
					}
				}
				$this->success(1000,'获取数据成功',$info);
			}else{
				$this->error(1023,'缺少必要参数');
			}

	}
	
	/**
	 * 参加考试-获取试题 post
	 * @param["examId"] 试卷ID int 必须
     * param["project_id"] 项目ID 必传
     */
	public function getQuest(){
		if(IS_POST){
			$post = I("post.");
			$post["examId"] += 0;
			$userId = $this->getUserId();
			if(empty($userId)){
				$this->error(1024, '用户不存在');
				die();
			}
			
			$post["project_id"] += 0;
			if(!is_int($post["project_id"]) || $post["project_id"] < 1){
				$this->error(1011, '缺少必要参数项目ID project_id');
			}
			
			if(is_int($post["examId"]) && $post["examId"] > 0){
				$resp = D('Exam')->getQuest($post,$userId);
				if($resp["code"] == 1000){
					$this->success(1000, '获取数据成功', $resp["data"]);
				}else{
					$this->error(1012, $resp["message"]);
				}
			}else{
				$this->error(1012, '缺少必要参数试卷ID examId');
			}
		}else{
			$this->error(1013, '不合法数据请求');
		}
	}
	
	/**
	 * 参加考试-交卷
	 * $param["examId"] 试卷ID int
	 * $param["project_id"] 项目ID int
	 * $param["answer"] 考试答案 json格式 {"89":"A","90":"B"}
	 * use_time 考试用时
	 */
	public function finish(){
		if(IS_POST){
			$post = I("post.");
			$post["examId"] += 0;
			$post["project_id"] += 0;
			$post["use_time"] += 0;
			$userId = $this->getUserId();
			if(empty($userId)){
				$this->error(1024, '用户不存在');
			}
			
			if(!is_int($post["examId"]) || $post["examId"] < 1){
				$this->error(1012, '缺少必要参数试卷id examId');
			}
			if(!is_int($post["project_id"]) || $post["project_id"] < 1){
				$this->error(1013, '缺少必要参数项目id project_id');
			}
			if(!is_int($post["use_time"]) || $post["use_time"] < 1){
				$this->error(1014, '缺少必要参数：考试花费时间 use_time');
			}
			
			$answerArray = json_decode($post["answer"], true);
			/* if(!is_array($answerArray) || count($answerArray) == 0){
				$this->error(1014, '请提交考试答案');
			} */
			
			$post["answer"] = $answerArray;
			
			$info = D('Exam')->finish($post,$userId);
			//print_r($info);
			$this->success($info["code"],$info["message"]);
		}else{
			$this->error(1013,'不合法数据请求');
		}
	}
	
	/**
	 * 已结束-查看详情
	 * @param["examId"] 试卷ID int 必须
     * @param["project_id"] 项目ID 必传
	 */
	public function seeDetail(){
		if(IS_POST){
			$post = I("post.");
			$post["examId"] += 0;
			$post["project_id"] += 0;
			$userId = $this->getUserId();
			if(empty($userId)){
				$this->error(1024,'用户不存在');
				die();
			}
			
			$post["project_id"] += 0;
			if(!is_int($post["project_id"]) || $post["project_id"] < 1){
				$this->error(1011, '缺少必要参数项目ID project_id');
			}
			
			if(is_int($post["examId"]) && $post["examId"] > 0){
				$info = D('Exam')->seeDetail($post,$userId);
				//print_r($info);
				$this->success(1000, "成功", $info);
			}else{
				$this->error(1012,'缺少必要参数试卷ID examId');
			}
		}else{
			$this->error(1013,'不合法数据请求');
		}
	}    
}