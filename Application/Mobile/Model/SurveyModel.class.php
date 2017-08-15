<?php
namespace Mobile\Model;

use Think\Model;

/**
 * @author Dujunqiang 20170308
 * 我的学员--我的调研
 */

class SurveyModel extends CommonModel {
    protected $tablePrefix = 'think_';
    protected $tableName = 'designated_personnel';

    //自动验证
    protected $_validate = array(
        array('username', 'empty', '用户名不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('username', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('password', 'checkValid', '密码不能有中文', Model::EXISTS_VALIDATE, 'callback'),
        array('oldPassword', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );
    
    /*
     * 我的调研列表
     * @param param 一维数组
     * @param uid 用户id
     * 注意：我的调研分为   调研  和 问卷两种
     *  问卷-- 管理员创建项目  指定参与人员
     *  调研-- 管理员指定给组织  组织中的一员皆可参与
     */
    public function getList($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
    	}
    	if($param["proStatus"] != 1 && $param["proStatus"] != 2){
    		return array("code"=>1022, "message"=>'proStatus 调研状态提交有误');
    	}
    	
    	//获取培训管理->培训项目问卷
    	if($param["proStatus"] == 1){
    		//$where["c.end_time"] = array("gt",date("Y-m-d H:i:s"));
    		$where['_string'] = "c.end_time > '".date("Y-m-d H:i:s")."' AND e.uid is null ";
    		$order = "c.start_time ASC";
    	}else{
    		//$where["c.end_time"] = array("lt",date("Y-m-d H:i:s"));
    		$where['_string'] = "c.end_time < '".date("Y-m-d H:i:s")."' OR e.uid is not null ";
    		$order = "c.end_time DESC";
    	}
    	$where["a.uid"] = $uid;
    	$where["b.type"] = 0;//进行中项目
    	//$where["b.is_public"] = 1;//公开项目//暂无用
    	$where["d.status"] = 1;//问卷审核已通过
	    $where["d.is_available"] = 1;//问卷已启用
    	$db = M("designated_personnel as a");
    	$list = $db->join("LEFT JOIN __ADMIN_PROJECT__ AS b ON a.project_id=b.id")
    		->join("LEFT JOIN __PROJECT_SURVEY__ AS c ON b.id=c.project_id")
    		->join("LEFT JOIN __SURVEY__ AS d ON c.survey_id=d.id")
    		->join("LEFT JOIN __SURVEY_ATTENDANCE__ AS e ON d.id = e.survey_id AND b.id = e.project_id")
    		->field("d.id as survey_id,c.project_id,d.survey_name,d.survey_score,c.start_time,c.end_time")->where($where)->limit(30)->select();

        if($list){
    		//survey_type 调研类型 1项目调研（从项目发起的调研） 2组织调研（分组组织发起的调研）
    		foreach ($list as $key=>$value){
    			$list[$key]["survey_type"] = 1;
    		}
    	}
    	
    	//获取工具管理-》调研管理
    	if($param["proStatus"] == 1){
    		//$where2["c.end_time"] = array("gt",date("Y-m-d H:i:s"));
    		$where2['_string'] = "c.end_time > '".date("Y-m-d H:i:s")."' AND d.user_id is null ";
    		$order2 = "c.start_time ASC";
    	}else{
    		//$where2["c.end_time"] = array("lt",date("Y-m-d H:i:s"));
    		$where2['_string'] = "c.end_time < '".date("Y-m-d H:i:s")."' OR d.user_id is not null ";
    		$order2 = "c.end_time DESC";
    	}
    	$where2["c.audit_state"] = 1;//审核已通过
    	$where2["a.uid"] = $uid;
    	$db2 = M("tissue_group_access a");
    	$list2 = $db2->field("DISTINCT(c.id) as research_id,c.research_name as survey_name,c.survey_id,c.credits as survey_score,c.start_time,c.end_time")
    		->join("JOIN __RESEARCH_TISSUEID__ b ON a.tissue_id=b.tissue_id")
    		->join("LEFT JOIN __RESEARCH__ c ON b.research_id=c.id")
    		->join("LEFT JOIN __RESEARCH_ATTENDANCE__ d ON b.research_id=d.research_id")
    		->where($where2)->limit(30)->select();
    	//echo $db2->getLastSql();
    	/*
    	if($param["proStatus"] == 1){
    		$where2["b.end_time"] = array("gt",date("Y-m-d H:i:s"));
    		$order2 = "b.start_time ASC";
    	}else{
    		$where2["b.end_time"] = array("lt",date("Y-m-d H:i:s"));
    		$order2 = "b.end_time DESC";
    	}
    	$where2["a.user_id"] = $uid;
    	$where2["b.audit_state"] = 1;//审核已通过
    	$where2["c.status"] = 1;//审核已通过
    	$where2["c.is_available"] = 1;//已启用
    	$db2 = M("research_attendance as a");
    	$list2 = $db2->field("a.state as status,b.id,b.research_name as survey_name,b.credits as survey_score,b.start_time,b.end_time")
    		->join("LEFT JOIN __RESEARCH__ b ON a.survey_id = b.survey_id and a.research_id = b.id ")
    		->join("LEFT JOIN __SURVEY__ as c ON a.survey_id = c.id")
    		->where($where2)->limit(30)->select();
    	*/
    	if($list2){
    		foreach ($list2 as $key=>$value){
    			$list2[$key]["project_id"] = "";
    			$list2[$key]["survey_type"] = 2;
    		}
    	}
    	
    	//echo $db2->getLastSql();
    	//print_r($list2);exit;
    	
    	//合并问卷 and 调研数据
    	$newList = array_merge($list,$list2);
    	$timeArr = array();
    	$return = array();
    	if($param["proStatus"] == 1){
    		//按开始时间ASC
    		foreach($newList as $key=>$value){
    			if($value["start_time"] == "0000-00-00 00:00:00" || $value["end_time"] == "0000-00-00 00:00:00"){
    				continue;
    			}
    			$timeArr[$key] = strtotime($value["start_time"]);
    			
    			$newList[$key]["proStatus"] = 0;//0 考试中 1待考试 2已结束
    			if(strtotime($value["start_time"]) > time()){
    				$newList[$key]["proStatus"] = 1;
    			}
    		}
    		asort($timeArr);
    		foreach ($timeArr as $key=>$value){
    			$return[] = $newList[$key];
    		}
    	}else{
    		//按结束时间 DESC
    		foreach($newList as $key=>$value){
    			if($value["start_time"] == "0000-00-00 00:00:00" || $value["end_time"] == "0000-00-00 00:00:00"){
    				continue;
    			}
    			$timeArr[$key] = strtotime($value["end_time"]);
    			$newList[$key]["proStatus"] = 2;//0 考试中 1待考试 2已结束
    		}
    		arsort($timeArr);
    		foreach ($timeArr as $key=>$value){
    			$return[] = $newList[$key];
    		}
    	}

    	if($return){
	    	return array("code"=>1000, "message"=>'操作成功', "data"=>$return);
    	}else{
    		if($param["proStatus"] == 1){
		    	return array("code"=>1023, "message"=>'管理员还没有给该学员安排调研');
    		}else{
		    	return array("code"=>1024, "message"=>'当前没有已结束的数据');
    		}
    	}
    }
    
    /**
     * 获取调研题目
	 * survey_type 调研类型 int 必须 1培训项目问卷  2组织调研
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
     */
    public function getQues($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
    	}
    	
    	$resp = M("survey_item a")->where("survey_id=".$param["survey_id"])->order("id ASC")->select();
    	if(!$resp){
    		return array("code"=>1023, "message"=>'当前调研没有调研题目');
    	}
    	foreach ($resp as $key=>$value){
    	
    		if($param["survey_type"] == 1){
    			//项目调研
    			$aWhere["u_survey_id"] = $uid;
    			$aWhere["question_number"] = $value["id"];
    			$hasAnswer = M("survey_answer")->field("survey_answer")->where($aWhere)->limit(1)->select();
    		}else{
    			//组织调研
    			$aWhere["user_id"] = $uid;
    			$aWhere["question_number"] = $value["id"];
    			$hasAnswer = M("research_answer")->field("survey_answer")->where($aWhere)->limit(1)->select();
    		}
    	
    		if($hasAnswer){
    			$resp[$key]["my_answer"] = $hasAnswer[0]["survey_answer"];
    		}
    	
    		if($value["classification"] == 1 || $value["classification"] == 2){
    			$resp[$key]["options"]["A"] = $resp[$key]["optiona"];
    			$resp[$key]["options"]["B"] = $resp[$key]["optionb"];
    			if($resp[$key]["optionc"]){
	    			$resp[$key]["options"]["C"] = $resp[$key]["optionc"];
    			}
    			if($resp[$key]["optiond"]){
	    			$resp[$key]["options"]["D"] = $resp[$key]["optiond"];
    			}
    			if($resp[$key]["optione"]){
    				$resp[$key]["options"]["E"] = $resp[$key]["optione"];
    			}
    			if($resp[$key]["optionf"]){
    				$resp[$key]["options"]["F"] = $resp[$key]["optionf"];
    			}
    		}elseif($resp[$key]["classification"] == 3){
    			$resp[$key]["options"]["A"] = $resp[$key]["optiona"];
    			$resp[$key]["options"]["B"] = $resp[$key]["optionb"];
    		}
    	
    		unset($resp[$key]["optiona"]);
    		unset($resp[$key]["optionb"]);
    		unset($resp[$key]["optionc"]);
    		unset($resp[$key]["optiond"]);
    		unset($resp[$key]["optione"]);
    		unset($resp[$key]["optionf"]);
    		unset($resp[$key]["ctime"]);
    	}
    	
    	return array("code"=>1000, "message"=>'操作成功', "data"=>$resp);
    }
    
    /**
     * 提交答案
   	 * survey_type 调研类型 int 必须 1培训项目问卷  2组织调研
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 * topicId 题目ID int 必选
	 * answer 题目答案 string 必选
     */
    public function answer($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
    	}
    	
    	$db = M("survey_item");
    	$where["id"] = $param["topicId"];
    	$where["survey_id"] = $param["survey_id"];
    	$survey = $db->where($where)->limit(1)->select();
    	if(!$survey){
    		return array("code"=>1025, "message"=>'提交的题目ID未获取到对应的题目');
    	}
    	
    	//验证答案合法性
    	if($survey[0]["classification"] == 1){
    		$param["answer"] =  strtoupper($param["answer"]);
    		if(!preg_match('/^[A-E]{1}$/', $param["answer"])){
    			return array("code"=>1026,"message"=>'单选答案必须为A-E的单一字母');
    		}
    	}elseif($survey[0]["classification"] == 2){
    		//多选注意顺序  有可能答题是 BA CA ca cdA
    		$param["answer"] = str_split($param["answer"]);
    		sort($param["answer"]);
    		$param["answer"] =  strtoupper(implode($param["answer"]));
    		if(!preg_match('/^[A-E]{2,5}$/', $param["answer"])){
    			return array("code"=>1027,"message"=>'多选答案必须为A-E的字母组合');
    		}
    	}elseif($survey[0]["classification"] == 3){
    		if($param["answer"] != "对" && $param["answer"] != "错"){
    			return array("code"=>1028,"message"=>'判断答案必须为对 or 错');
    		}
    	}elseif($survey[0]["classification"] ==4){
    		$param["answer"] = trim($param["answer"]);
    		if($param["answer"] == ""){
    			return array("code"=>1028,"message"=>'简答题答案不能未空');
    		}
    	}else{
    		return array("code"=>1029, "message"=>'调研题目目前支持单选、多选、判断、简答');
    	}
    	
    	//判断当前用户是否有此调研
    	if($param["survey_type"] == 1){
    		//项目调研
    		$db = M("designated_personnel");
    		$hasThis = $db->field("uid")->where("project_id=".$param["project_id"]." AND uid=".$uid)->limit(1)->select();
    		if(!$hasThis){
    			return array("code"=>1027, "message"=>'当前调研不属于该学员');
    		}
    		//----------------OK------------------
    		//问卷是否进行中，问卷已结束提示交卷
    		$act = M("survey");
    		$actTime = $act->field("start_time,end_time")->where("id=".$param["survey_id"]." AND status='1' AND is_available='1'")->limit(1)->select();
    		if(!$actTime){
    			return array("code"=>1029, "message"=>'当前问卷不存在或已关闭');
    		}
    		if(time() > strtotime($actTime[0]["end_time"])){
    			return array("code"=>1030, "message"=>'问卷已结束，请交卷');
    		}
    		if(time() < strtotime($actTime[0]["start_time"])){
    			return array("code"=>1031, "message"=>'问卷未开始，请等待');
    		}
    		//项目调研
    		$answer = M("survey_answer");
    		$aWhere["u_survey_id"] = $uid;
    		$aWhere["question_number"] = $param["topicId"];
    		$hasAnswer = $answer->field("survey_answer")->where($aWhere)->limit(1)->select();
    		if($hasAnswer){
    			$data["survey_answer"] = $param["answer"];
    			$return = $answer->where($aWhere)->save($data);
    			if($return === false){
    				return array("code"=>1032,"message"=>'更新失败，可尝试重新操作');
    			}
    		}else{
    			$data["project_id"] = $param["project_id"];
    			$data["survey_id"] = $param["survey_id"];
    			$data["u_survey_id"] = $uid;
    			$data["survey_answer"] = $param["answer"];
    			$data["classification"] = $survey[0]["classification"];
    			$data["question_number"] = $param["topicId"];
    			$data["describe"] = "";
    			$return = $answer->add($data);
    			if($return < 0 || $return === false){
    				return array("code"=>1033,"message"=>'提交失败，可尝试重新操作');
    			}
    		}
    	}else{
    		//组织调研
    		$canJoin = M("research_tissueid");
    		$canJoin = $canJoin->field("tissue_id,research_id")->where("research_id=".$param["research_id"])->limit(1)->select();
    		if(!$canJoin){
    			return array("code"=>1028, "message"=>'当前调研不属于该学员');
    		}
    		
    		$tissue = M("tissue_group_access");
    		$tissuid = $tissue->field("tissue_id")->where("uid=".$uid." AND tissue_id=".$canJoin[0]["tissue_id"])->limit(1)->select();
    		$tissuid = $tissuid[0]["tissue_id"];
    		if(!$tissuid){
    			return array("code"=>1028, "message"=>'当前学员还没有指定组织');
    		}
    		
	    	//调研是否进行中，调研已结束提示交卷
	    	$act = M("research");
	    	$research = $act->field("survey_id,start_time,end_time")->where("id=".$param["research_id"]." AND audit_state=1")->limit(1)->select();
	    	if(!$research){
	    		return array("code"=>1029, "message"=>'当前调研不存在');
	    	}
	    	if(time() > strtotime($research[0]["end_time"])){
	    		return array("code"=>1030, "message"=>'调研已结束，请交卷');
	    	}
	    	if(time() < strtotime($research[0]["start_time"])){
	    		return array("code"=>1031, "message"=>'调研未开始，请等待');
	    	}
	    	
	    	//此调研是否已提交过
	    	$answer = M("research_answer");
	    	$aWhere["user_id"] = $uid;
	    	$aWhere["question_number"] = $param["topicId"];
	    	$hasAnswer = $answer->field("survey_answer")->where($aWhere)->limit(1)->select();
    		if($hasAnswer){
    			$data["survey_answer"] = $param["answer"];
    			$return = $answer->where($aWhere)->save($data);
    			if($return === false){
    				return array("code"=>1032, "message"=>'更新失败，可尝试重新操作');
    			}
    		}else{
    			$data["research_id"] = $param["research_id"];
    			$data["survey_id"] = $param["survey_id"];
    			$data["user_id"] = $uid;
    			$data["survey_answer"] = $param["answer"];
    			$data["classification"] = $survey[0]["classification"];
    			$data["question_number"] = $param["topicId"];
    			$data["describe"] = "";
    			$return = $answer->add($data);
    			if($return < 0 || $return === false){
    				return array("code"=>1033,"message"=>'提交失败，可尝试重新操作');
    			}
    		}
    	}
    	return array("code"=>1000, "message"=>'操作成功');
    }
    
    /**
     * 提交调研
	 * survey_type 调研类型 int 必须 1培训项目问卷  2组织调研
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
     */
    public function finish($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
    	}
    	if($param["survey_type"] == 1){
    		//保存调研结果
    		foreach ($param["answer"] as $key=>$value){
    			//获取正确答案
    			$where["id"] = $key;
    			$survey_item = M("survey_item")->field("classification")->where($where)->limit(1)->select();
    			if(!$survey_item){
    				continue;
    			}
    			
    			//1表示单选择题 2表示多选 3判断题 4问答题'
    			if($survey_item[0]["classification"] == 1){
    				$value =  strtoupper($value);
    				if(!preg_match('/^[A-E]{1}$/', $value)){
    					//return array("code"=>1025,"message"=>'单选答案必须为A-E的单一字母');
    					continue;
    				}
    			}elseif($survey_item[0]["classification"] == 2){
    				$value =  strtoupper($value);
    			}elseif($survey_item[0]["classification"] == 3){
    				$value =  strtoupper($value);
    			}else{
    				//简答题，判断关键字
    			}
    		
    			$data["survey_answer"] = $value;
    		
    			//是否已有答题数据
    			$aWhere["u_survey_id"] = $uid;
    			$aWhere["question_number"] = $key;
    			$aWhere["project_id"] = $param["project_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$hasAnswer = M("survey_answer")->where($aWhere)->limit(1)->select();
    			if($hasAnswer){
    				//已答过题，修改答案
    				M("survey_answer")->where($aWhere)->save($data);
    			}else{
    				$data["project_id"] = $param["project_id"];
    				$data["survey_id"] = $param["survey_id"];
    				$data["u_survey_id"] = $uid;
    				$data["classification"] = $survey_item[0]["classification"];
    				$data["question_number"] = $key;
    				$data["describe"] = "";
    				M("survey_answer")->add($data);
    			}
    		}
    		
    		$db = M("survey_attendance");
    		$hasAtten = $db->field("id")->where("uid=".$uid." AND survey_id=".$param["survey_id"]." AND project_id=".$param["project_id"])->limit(1)->select();
    		if($hasAtten){
    			$data["status"] = 1;
    			$aWhere2["uid"] = $uid;
    			$aWhere2["survey_id"] = $param["survey_id"];
    			$aWhere2["project_id"] = $param["project_id"];
    			$return = $db->where($aWhere2)->save($data);
    			if($return === false){
    				return array("code"=>1032,"message"=>'更新失败，可尝试重新操作');
    			}
    		}else{
    			$data["uid"] = $uid;
    			$data["survey_id"] = $param["survey_id"];
    			//获取岗位部门
    			$job = M("tissue_group_access as a");
    			$job = $job->where("a.uid=".$uid)->field("a.tissue_id,a.job_id")->select();
    			
    			$data["department_id"] = $job[0]["tissue_id"];//部门ID
    			$data["position_id"] = $job[0]["job_id"];//岗位ID
    			$data["status"] = 1;
    			$data["mobile_scanning"] = "";
    			$data["project_id"] = $param["project_id"];
    			$return = $db->add($data);
    			if($return < 0 || $return === false){
    				return array("code"=>1033,"message"=>'提交失败，可尝试重新操作');
    			}
    			
	    		//添加学分学时
    			$pro_survey = M("project_survey")->where("project_id=".$param["project_id"]." AND survey_id=".$param["survey_id"])->limit(1)->select();
    			$diffTime = strtotime($pro_survey[0]["end_time"]) - strtotime($pro_survey[0]["start_time"]);
    			$hours = ceil($diffTime / 60);
	    		$data1["create_time"] = date("Y-m-d H:i:s");
	    		$data1["typeid"] = 1;
    			$data1["credit"] = $pro_survey[0]["credit"];
	    		$data1["source_id"] = $param["survey_id"];
	    		$data1["project_id"] = $param["project_id"];
	    		$data1["user_id"] = $uid;
	    		$data1["hours"] = $hours;
	    		M("center_study")->add($data1);
    		}
    		
    	}else{
    		//工具管理-->调研
    		if(!$param["research_id"]){
    			return array("code"=>1023, "message"=>'survey_type=2时，缺少参数： research_id 调研id');
    		}
    		
    		//保存调研结果
    		foreach ($param["answer"] as $key=>$value){
    			//获取正确答案
    			$where["id"] = $key;
    			$survey_item = M("survey_item")->field("classification")->where($where)->limit(1)->select();
    			if(!$survey_item){
    				continue;
    			}
    		
    			//1表示单选择题 2表示多选 3判断题 4问答题'
    			if($survey_item[0]["classification"] == 1){
    				$value =  strtoupper($value);
    				if(!preg_match('/^[A-E]{1}$/', $value)){
    					//return array("code"=>1025,"message"=>'单选答案必须为A-E的单一字母');
    					continue;
    				}
    			}elseif($survey_item[0]["classification"] == 2){
	    			$value =  strtoupper($value);
    			}elseif($survey_item[0]["classification"] == 3){
    				//判断题 汉字对错
    				$value =  strtoupper($value);
    			}else{
    				//简答题，判断关键字
    			}
    		
    			$data["survey_answer"] = $value;
    			//是否已有答题数据
    			$aWhere["user_id"] = $uid;
    			$aWhere["question_number"] = $key;
    			$aWhere["research_id"] = $param["research_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$hasAnswer = M("research_answer")->where($aWhere)->limit(1)->select();
    			if($hasAnswer){
    				//已答过题，修改答案
    				M("research_answer")->where($aWhere)->save($data);
    			}else{
    				$data["research_id"] = $param["research_id"];
    				$data["survey_id"] = $param["survey_id"];
    				$data["user_id"] = $uid;
    				$data["classification"] = $survey_item[0]["classification"];
    				$data["question_number"] = $key;
    				$data["describe"] = "";
    				M("research_answer")->add($data);
    			}
    		}
    		
    		$db = M("research_attendance");
    		$hasAtten = $db->field("user_id")->where("user_id=".$uid." AND survey_id=".$param["survey_id"]." AND research_id=".$param["research_id"])->limit(1)->select();
    		if($hasAtten){
    			$data["state"] = 1;
    			$aWhere2["user_id"] = $uid;
    			$aWhere2["survey_id"] = $param["survey_id"];
    			$aWhere2["research_id"] = $param["research_id"];
    			$return = $db->where($aWhere2)->save($data);
    			if($return === false){
    				return array("code"=>1032,"message"=>'更新失败，可尝试重新操作');
    			}
    		}else{
    			$data["survey_id"] = $param["survey_id"];
    			$data["research_id"] = $param["research_id"];
    			$data["user_id"] = $uid;
    			$data["state"] = 1;
    			$return = $db->add($data);
    			if($return < 0 || $return === false){
    				return array("code"=>1033,"message"=>'提交失败，可尝试重新操作');
    			}
    			
    			//添加学分学时
    			$research = M("research")->where("id=".$param["research_id"]." AND survey_id=".$param["survey_id"])->limit(1)->select();
    			$diffTime = strtotime($research[0]["end_time"]) - strtotime($research[0]["start_time"]);
    			$hours = ceil($diffTime / 60);
    			
    			$data1["create_time"] = date("Y-m-d H:i:s");
    			$data1["typeid"] = 3;
    			$data1["credit"] = $research[0]["credits"];
    			$data1["source_id"] = $param["research_id"];
    			$data1["project_id"] = 0;
    			$data1["user_id"] = $uid;
    			$data1["hours"] = $hours;
    			M("center_study")->add($data1);
    		}
    	}
    	
    	return array("code"=>1000, "message"=>'操作成功');
    }
    
    /**
     * 已结束查看调研结果-----套路和获取试题基本一样，统计答案占比
     * survey_type 调研类型 int 必须 1培训项目问卷  2组织调研
	 * survey_id 问卷ID int 必传
	 * project_id 项目ID int survey_type=1 时必传
	 * research_id 调研ID int survey_type=2  时必传
	 * topicId 题目ID int 可选，不填默认第一题
     */
    public function seeDetail($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
    	}
    	
    	$where["survey_id"] = $param["survey_id"];
    	if(is_int($param["topicId"]) && $param["topicId"] > 0){
    		$where["id"] = $param["topicId"];
    	}
    	$resp = M("survey_item a")->where($where)->order("id ASC")->limit(1)->select();
    	$resp = $resp[0];
    	if(!$resp){
    		return array("code"=>1023, "message"=>'当前调研没有调研题目或者题号有误');
    	}
    	
    	if($resp["classification"] == 1 || $resp["classification"] == 2){
    		//单选 / 多选
    		if($param["survey_type"] == 1){
	    		$answer = M("survey_answer");
	    		$aWhere["project_id"] = $param["project_id"];
	    		$aWhere["survey_id"] = $param["survey_id"];
	    		$aWhere["question_number"] = $resp["id"];
    		}else{
    			$answer = M("research_answer");
    			$aWhere["research_id"] = $param["research_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$aWhere["question_number"] = $resp["id"];
    		}
    		$resp["a_num"] = 0;
    		$resp["b_num"] = 0;
    		$resp["c_num"] = 0;
    		$resp["d_num"] = 0;
    		$resp["e_num"] = 0;
    		$resp["a_rate"] = "0%";
    		$resp["b_rate"] = "0%";
    		$resp["c_rate"] = "0%";
    		$resp["d_rate"] = "0%";
    		$resp["e_rate"] = "0%";
    		
    		$optiona = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer LIKE '%A%'")->select();
    		$optionb = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer LIKE '%B%'")->select();
    		$optionc = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer LIKE '%C%'")->select();
    		if($resp["optiond"]){
	    		$optiond = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer LIKE '%D%'")->select();
    		}
    		if($resp["optione"]){
	    		$optione = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer LIKE '%E%'")->select();
    		}
    		
    		$resp["a_num"] = $optiona[0]["num"];
    		$resp["b_num"] = $optionb[0]["num"];
    		$resp["c_num"] = $optionc[0]["num"];
    		$resp["d_num"] = $optiond[0]["num"] ? $optiond[0]["num"] : 0;
    		$resp["e_num"] = $optione[0]["num"] ? $optione[0]["num"] : 0;
    		
    		$total = $optiona[0]["num"] + $optionb[0]["num"] + $optionc[0]["num"] + $optiond[0]["num"] + $optione[0]["num"];
    		if($total > 0){
    			$resp["a_rate"] = (round($optiona[0]["num"] / $total, 2) * 100)."%";
    			$resp["b_rate"] = (round($optionb[0]["num"] / $total, 2) * 100)."%";
    			$resp["c_rate"] = (round($optionc[0]["num"] / $total, 2) * 100)."%";
    			$resp["d_rate"] = (round($optiond[0]["num"] / $total, 2) * 100)."%";
    			$resp["e_rate"] = (round($optione[0]["num"] / $total, 2) * 100)."%";
    		}
    	}elseif($resp["classification"] == 3){
    		//判断
    		if($param["survey_type"] == 1){
    			$answer = M("survey_answer");
    			$aWhere["project_id"] = $param["project_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$aWhere["question_number"] = $resp["id"];
    		}else{
    			$answer = M("research_answer");
    			$aWhere["research_id"] = $param["research_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$aWhere["question_number"] = $param["topicId"];
    		}
    		$resp["a_num"] = 0;
    		$resp["b_num"] = 0;
    		$resp["a_rate"] = "0%";
    		$resp["b_rate"] = "0%";
    		
    		$optiona = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer='A'")->select();
    		$optionb = $answer->field("count(id) as num,survey_answer")->where($aWhere)->where("survey_answer='B'")->select();
    		$resp["a_num"] = $optiona[0]["num"];
    		$resp["b_num"] = $optionb[0]["num"];
    		
    		$total = $optiona[0]["num"] + $optionb[0]["num"];
    		if($total > 0){
    			$resp["a_rate"] = (round($optiona[0]["num"] / $total, 2) * 100)."%";
    			$resp["b_rate"] = (round($optionb[0]["num"] / $total, 2) * 100)."%";
    		}
    	}elseif($resp["classification"] == 4){
    		//简答
    		if($param["survey_type"] == 1){
    			$answer = M("survey_answer");
    			$aWhere["project_id"] = $param["project_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$aWhere["question_number"] = $resp["id"];
    		}else{
    			$answer = M("research_answer");
    			$aWhere["research_id"] = $param["research_id"];
    			$aWhere["survey_id"] = $param["survey_id"];
    			$aWhere["question_number"] = $resp["id"];
    		}
    		$resp["myAnswer"] = "";
    		$hasAnswer = $answer->field("survey_answer")->where($aWhere)->select();
    		if($hasAnswer){
    			$myAnswers = "";
    			foreach ($hasAnswer as $aValue){
    				$myAnswers .= $aValue["survey_answer"];
    				$myAnswers .= "<br><br>";
    			}
    			$resp["myAnswer"] = $myAnswers;
    		}
    	}
    	
    	$resp["topicId"] = $resp["id"];
    	$resp["survey_type"] = $param["survey_type"];
    	$resp["survey_id"] = $param["survey_id"];
    	$resp["project_id"] = $param["project_id"];
    	$resp["research_id"] = $param["research_id"];
    	$resp["project_id"] = $param["project_id"];
    	
    	$allQues = M("survey_item a");
    	$allWhere["survey_id"] = $param["survey_id"];
    	$allResp = $allQues->field("id")->where($allWhere)->order("id ASC")->select();
    	//获取当前试题在题库的位置，判断是否有上一题  下一题
    	$orderArr = array();
    	foreach ($allResp as $key=>$value){
    		$orderArr[$key] = $value["id"];
    	}
    	
    	$order = array_search($resp["id"],$orderArr);
    	if($order > 0 && $order < (count($orderArr) - 1)){
    		$resp["preId"] = $orderArr[$order - 1];
    		$resp["preStatus"] = 1;
    		$resp["nextId"] = $orderArr[$order + 1];
    		$resp["nextStatus"] = 1;
    	}elseif($order == (count($orderArr) - 1)){
    		$resp["preId"] = $orderArr[$order - 1];
    		$resp["preStatus"] = 1;
    		$resp["nextId"] = "";
    		$resp["nextStatus"] = 0;
    	}elseif($order == 0 && count($orderArr) > 0){
    		$resp["preId"] = '';
    		$resp["preStatus"] = 0;
    		$resp["nextId"] = $orderArr[$order + 1];
    		$resp["nextStatus"] = 1;
    	}elseif($order == 0 && count($orderArr) == 0){
    		$resp["preId"] = '';
    		$resp["preStatus"] = 0;
    		$resp["nextId"] = '';
    		$resp["nextStatus"] = 0;
    	}
    	return array("code"=>1000, "message"=>'操作成功', "data"=>$resp);
    }
}