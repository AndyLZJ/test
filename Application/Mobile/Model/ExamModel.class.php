<?php
namespace Mobile\Model;

use Think\Model;

/**
 * @author Dujunqiang 20170304
 *
 */

class ExamModel extends CommonModel {
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
     * 我的考试列表
     * @param $param["examStatus"] 考试类型  1待考试   2已结束
     * @param $uid 用户id
     */
    public function myExam($param,$uid){
    	if(!$param || !$uid){
    		return false;
    	}
    	
    	$where = array();
    	if($param["examStatus"] == 1){
    		$order = "c.start_time ASC";
	    	$where['_string'] = "c.end_time > '".date("Y-m-d H:i:s")."' AND e.uid is null ";
    	}else{
    		$order = "c.end_time DESC";
	    	$where['_string'] = "c.end_time < '".date("Y-m-d H:i:s")."' OR e.uid is not null ";
    	}
    	
    	$where['a.uid'] = array("eq",$uid);
    	$where['b.type'] = array("in","0,4");//项目进行中 / 已完成
    	$where['d.status'] = array("eq",1);//试卷已审核通过
    	$where['d.is_available'] = array("eq",1);//试卷已启用
    	
    	$data = M("designated_personnel a")->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id=b.id")
    		->join("LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id=c.project_id")
    		->join("LEFT JOIN __EXAMINATION__ d ON c.test_id=d.id")
    		->join("LEFT JOIN __EXAM_SCORE__ e ON e.uid=a.uid and e.project_id=a.project_id AND e.exam_id=c.test_id ")
    		->field("b.project_name,c.project_id,c.start_time,c.end_time,c.test_id,c.test_names,d.test_name")
    		->where($where)->order($order)->limit(50)->select();
    	/* echo M("designated_personnel a")->getLastSql();
    	exit; */
    	foreach($data as $key=>$value){
    		$examStatus = 2;//0 考试中 1待考试 2已结束 3已交卷（考试时间未到）
    		$score = 0;
    		if($param["examStatus"] == 2){
    			$scoreDb = M("exam_score");
    			$scoreResp = $scoreDb->field("total_score")->where(array("uid"=>$uid,"exam_id"=>$value["test_id"], "project_id"=>$value["project_id"]))->limit(1)->select();
    			$score = $scoreResp[0]["total_score"] ? $scoreResp[0]["total_score"] : 0;
    			
    			if(strtotime($value["end_time"]) > time()){
    				$examStatus = 3;
    			}
    		}else{
    			$examStatus = 0;//0 考试中 1待考试 2已结束 3已交卷（考试时间未到）
    			if(strtotime($value["start_time"]) > time()){
    				$examStatus = 1;
    			}
    		}
    		if($value["test_names"]){
	    		$data[$key]["test_name"] = $value["test_names"];
    			unset($data[$key]["test_names"]);
    		}
    		
    		$data[$key]["examStatus"] = $examStatus;
    		$data[$key]["score"] = $score;
    	}
    	return $data;
    }
    
    /**
     * 获取试题
     * $param["examId"] 试卷ID int
     * project_id 项目ID int 必选
     */
    public function getQuest($param,$uid){
    	if(!$param || !$uid){
    		return false;
    	}
    	
    	if(!is_int($param["examId"]) || $param["examId"] < 1){
    		return array("code"=>1021, "message"=>"请提交考试ID");
    	}
    	
    	if(!is_int($param["project_id"]) || $param["project_id"] < 1){
    		return array("code"=>1022, "message"=>"请提交项目ID");
    	}
    	
    	$testTime = M("project_examination")->field("start_time,end_time")->where("project_id=".$param["project_id"]." and test_id=".$param["examId"])->limit(1)->select();
    	if(time() < strtotime($testTime[0]["start_time"])){
    		return array("code"=>1023, "message"=>"考试还未开始，请等待考试开始");
    	}
    	if(time() > strtotime($testTime[0]["end_time"])){
    		return array("code"=>1024, "message"=>"考试考试已结束，无法答题");
    	}
    	$time_left = strtotime($testTime[0]["end_time"]) - time();
    	
    	$quesWhere["a.examination_id"] = $param["examId"];
    	$ques = M("examination_item_rel as a")->field("id,title,optionA,optionB,optionC,optionD,optionE,classification")//b.right_option
    		->join("join __EXAMINATION_ITEM__ as b on a.examination_item_id=b.id")	
    		->where($quesWhere)->order("a.examination_item_id asc")->select();
    	if(!$ques){
    		return array("code"=>1025, "message"=>"system error,当前考试中没有获取到试题");
    	}
    	
    	foreach ($ques as $key=>$value){
	    	$aWhere["u_exam_id"] = $uid;
	    	$aWhere["question_number"] = $value["id"];
	    	$aWhere["project_id"] = $param["project_id"];
	    	$aWhere["exam_id"] = $param["examId"];
    		$answer = M("exam_answer")->field("exam_answer")->where($aWhere)->limit(1)->select();
    		if($answer){
    			$ques[$key]["my_answer"] = $answer[0]["exam_answer"];
    		}
    		
    		if($value["classification"] == 1 || $value["classification"] == 2){
				$ques[$key]["options"]["A"] = $ques[$key]["optiona"];
				$ques[$key]["options"]["B"] = $ques[$key]["optionb"];
				$ques[$key]["options"]["C"] = $ques[$key]["optionc"];
				if($ques[$key]["optiond"]){
					$ques[$key]["options"]["D"] = $ques[$key]["optiond"];
				}
				if($ques[$key]["optione"]){
					$ques[$key]["options"]["E"] = $ques[$key]["optione"];
				}
    		}elseif($ques[$key]["classification"] == 3){
    			$ques[$key]["options"]["A"] = $ques[$key]["optiona"];
    			$ques[$key]["options"]["B"] = $ques[$key]["optionb"];
    		}
    		unset($ques[$key]["optiona"]);
    		unset($ques[$key]["optionb"]);
    		unset($ques[$key]["optionc"]);
    		unset($ques[$key]["optiond"]);
    		unset($ques[$key]["optione"]);
    	}
    	
    	$data["time_left"] = $time_left;
    	$data["examId"] = $param["examId"];
    	$data["project_id"] = $param["project_id"];
    	$data["list"] = $ques;
    	return array("code"=>1000, "message"=>"成功", "data"=>$data);
    }
    
    /**
     * 参加考试-提交答案
	 * @param $param.examId 试卷ID int 必须
	 * @param $param.quesId 题目ID int 必须
	 * @param $param.answer 提交答案 str 必须
	 * project_id 项目ID 必传
	 * $uid 用户ID
     */
    public function answer($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1021,"message"=>'提交失败，未获取到参数或用户数据');
    	}
    	
    	$param["examId"] += 0;
    	$param["quesId"] += 0;
    	$param["project_id"] += 0;
    	$param["answer"] = trim($param["answer"]);
    	if(!is_int($param["examId"]) || !is_int($param["quesId"]) || $param["examId"] < 1 || $param["quesId"] < 1){
    		return array("code"=>1022,"message"=>'提交失败，请提交examId 试卷ID和quesId 题目ID');
    	}
    	
    	if(!$param["project_id"] || $param["project_id"] < 1){
    		return array("code"=>1021, "message"=>'提交失败，请提交project_id 项目ID');
    	}
    	
    	if($param["answer"] == ""){
    		return array("code"=>1023,"message"=>'请提交答案');
    	}
    	
    	$db = M("examination_item");
    	$where["id"] = $param["quesId"];
    	$where["examination_id"] = $param["examId"];
    	$exam = $db->field("right_option,item_score,classification,keywords")->where($where)->limit(1)->select();
    	if(!$exam){
    		return array("code"=>1024,"message"=>'未获取到试题');
    	}
    	
    	//判断当前用户是否有此试卷
    	$db = M("designated_personnel");
    	$hasThis = $db->field("uid")->where("project_id=".$param["project_id"]." AND uid=".$uid)->limit(1)->select();
    	if(!$hasThis){
    		return array("code"=>1028, "message"=>'当前考试不属于该学员');
    	}
    	
		$isExam = 0;
		//1表示单选择题 2表示多选 3判断题 4问答题'
		if($exam[0]["classification"] == 1){
			$param["answer"] =  strtoupper($param["answer"]);
			if(!preg_match('/^[A-E]{1}$/', $param["answer"])){
				return array("code"=>1025,"message"=>'单选答案必须为A-E的单一字母');
			}
			if($param["answer"] == $exam[0]["right_option"]){
				$isExam = 1;
			}
		}elseif($exam[0]["classification"] == 2){
			//多选注意顺序  有可能答题是 BA CA ca cdA
			$answer = str_split($param["answer"]);
			sort($answer);
			$answer =  strtoupper(implode($answer));
			if(!preg_match('/^[A-E]{2,5}$/', $answer)){
				return array("code"=>1025,"message"=>'多选答案必须为A-E的字母组合');
			}
			$answer = str_split($answer);
			$answer = implode(",",$answer);//正确答案是B,C   提交答案是BC
			if($exam[0]["right_option"] == $answer){
				$isExam = 1;
			}
		}elseif($exam[0]["classification"] == 3){
			//判断题 汉字对错
			if($param["answer"] != "对" && $param["answer"] != "错"){
				return array("code"=>1026,"message"=>'判断答案必须为对 or 错');
			}
			if($param["answer"] == $exam[0]["right_option"]){
				$isExam = 1;
			}
		}else{
			$isExam = null;
			//简答题，判断关键字----人工阅卷，题目暂不计分
			/*
			$exam[0]["keywords"] = str_replace("，", ",", $exam[0]["keywords"]);
			$keywordArr = explode(",", $exam[0]["keywords"]);
			*/
		}
		
		$data["exam_answer"] = $param["answer"];
		$data["isExam"] = $isExam;
		$data["data_tiem"] = date("Y-m-d H:i:s");
		
		//是否已有答题数据
		$answer = M("exam_answer");
		$aWhere["u_exam_id"] = $uid;
		$aWhere["question_number"] = $param["quesId"];
		$hasAnswer = $answer->where($aWhere)->limit(1)->select();
		if($hasAnswer){
			//已答过题，修改答案
			$upWhere["question_number"] = $param["quesId"];
			$upWhere["exam_id"] = $param["examId"];
			$upWhere["u_exam_id"] = $uid;
			$db = M("exam_answer");
			$return = $db->where($upWhere)->save($data);
			if($return === false){
				return array("code"=>1031,"message"=>'更新失败，可尝试重新操作');
			}
		}else{
			$data["project_id"] = $param["project_id"];//项目ID
			$data["classification"] = $exam[0]["classification"];
			$data["correct_answer"] = $exam[0]["right_option"];
			$data["u_exam_id"] = $uid;
			$data["exam_id"] = $param["examId"];
			$data["question_number"] = $param["quesId"];
			$return = $answer->add($data);
			if($return < 0 || $return === false){
				return array("code"=>1031,"message"=>'提交失败，可尝试重新操作');
			}
		}
    	return array("code"=>1000,"message"=>"答题成功");
    }
    
    /**
     * 交卷
     * $param["examId"] 试卷ID int
     * $param["project_id"] 项目ID int
     */
    public function finish($param,$uid){
    	if(!$param || !$uid){
    		return array("code"=>1001,"message"=>"缺少参数");
    	}
    	$param["examId"] += 0;
    	$param["project_id"] += 0;
    	if(!is_int($param["examId"]) || $param["examId"] < 1){
    		return array("code"=>1002, "message"=>"缺少参数：examId，试卷id");
    	}
    	
    	if(!is_int($param["project_id"]) || $param["project_id"] < 1){
    		return array("code"=>1011, "message"=>"缺少参数：project_id，项目id");
    	}
    	
    	$testTime = M("project_examination")->field("start_time,end_time")->where("project_id=".$param["project_id"]." and test_id=".$param["examId"])->limit(1)->select();
    	if(time() < strtotime($testTime[0]["start_time"])){
    		return array("code"=>1011, "message"=>"考试还未开始，请等待考试开始");
    	}
    	/* if(time() > strtotime($testTime[0]["end_time"])){
    		return array("code"=>1011, "message"=>"考试考试已结束，无法答题");
    	} */
    	
    	//判断试卷是否存在
    	$exam = M("project_examination");
    	$hasData = $exam->where("test_id=".$param["examId"])->select();
    	if(!$hasData){
    		return array("code"=>1012, "message"=>"没有找到对应的试卷examId");
    	}
    	
    	//判断当前用户是否有此试卷
    	$db = M("designated_personnel");
    	$hasThis = $db->field("uid")->where("project_id=".$param["project_id"]." AND uid=".$uid)->limit(1)->select();
    	if(!$hasThis){
    		return array("code"=>1028, "message"=>'当前考试不属于该学员');
    	}
    	
    	//循环保存答案
    	foreach ($param["answer"] as $key=>$value){
    		//获取正确答案
    		$where["id"] = $key;
    		$exam = M("examination_item")->field("right_option,item_score,classification,keywords")->where($where)->limit(1)->select();
    		if(!$exam){
    			continue;
    		}
    		$isExam = 0;
    		//1表示单选择题 2表示多选 3判断题 4问答题'
    		$exam_answer = "";
    		if($exam[0]["classification"] == 1){
    			$exam_answer =  strtoupper($value);
    			if($exam_answer == $exam[0]["right_option"]){
    				$isExam = 1;
    			}
    		}elseif($exam[0]["classification"] == 2){
    			$exam_answer =  strtoupper($value);
    			if($exam[0]["right_option"] == $exam_answer){
    				$isExam = 1;
    			}
    		}elseif($exam[0]["classification"] == 3){
    			$exam_answer =  strtoupper($value);
    			if($exam_answer == $exam[0]["right_option"]){
    				$isExam = 1;
    			}
    		}else{
    			//获取此道题目分值
    			$exam_answer = $value;
    			$thisTest = M("examination_item_rel")->where("examination_id=".$param["examId"]." and examination_item_id=".$key)->limit(1)->select();
    			if($thisTest){
    				//简答题，判断关键字
    				$keywordArr = explode(",", $exam[0]["keywords"]);
    				$keywordNum = count($keywordArr);
    				$thisTestScore = $thisTest[0]["score"];
    				$isAllRight = true;
    				$getScore = 0;
    				foreach ($keywordArr as $valueWord){
	    				if(strstr($exam_answer, $valueWord)){
	    					$getScore += $thisTestScore / $keywordNum;
	    				}else{
	    					$isAllRight = false;
	    				}
    				}
    				
    				if($isAllRight){
    					$getScore = $thisTestScore;
    				}else{
    					$getScore = ceil($getScore);
    				}
    				$data["wdscore"] = $getScore;
    				$data["checked"] = "0";
	    			$isExam = 0;
    			}else{
	    			$isExam = 0;
    			}
    		}
    		
    		$data["exam_answer"] = $exam_answer;
    		$data["isExam"] = $isExam;
    		$data["data_tiem"] = date("Y-m-d H:i:s");
    		
    		//是否已有答题数据
			$aWhere["u_exam_id"] = $uid;
			$aWhere["question_number"] = $key;
			$aWhere["project_id"] = $param["project_id"];
			$hasAnswer = M("exam_answer")->where($aWhere)->limit(1)->select();
			if($hasAnswer){
				//已答过题，修改答案
				$upWhere["question_number"] = $key;
				$upWhere["exam_id"] = $param["examId"];
				$upWhere["project_id"] = $param["project_id"];
				$upWhere["u_exam_id"] = $uid;
				$return = M("exam_answer")->where($upWhere)->save($data);
			}else{
				$data["project_id"] = $param["project_id"];//项目ID
				$data["classification"] = $exam[0]["classification"];
				$data["correct_answer"] = $exam[0]["right_option"];
				$data["u_exam_id"] = $uid;
				$data["exam_id"] = $param["examId"];
				$data["question_number"] = $key;
				$return = M("exam_answer")->add($data);
			}
    	}
    	
    	//计算总分
    	$where2["a.u_exam_id"] = $uid;
    	$where2["a.exam_id"] = $param["examId"];
    	$where2["a.project_id"] = $param["project_id"];
    	$where2["a.isExam"] = 1;
    	$where2["b.examination_id"] = $param["examId"];
    	$resp = M("exam_answer as a")->field("sum(b.score) as total_score")
    		->join("JOIN __EXAMINATION_ITEM_REL__ as b ON a.question_number=b.examination_item_id")
    		->where($where2)->select();
    	//echo M("exam_answer as a")->getLastSql();
    	$totalScore = $resp[0]["total_score"];
    	
    	//再加上简答题得分
    	$where3["a.exam_id"] = $param["examId"];
    	$where3["a.project_id"] = $param["project_id"];
    	$where3["a.u_exam_id"] = $uid;
    	$where3["a.classification"] = 4;
    	$wdscore = M("exam_answer a")->field("sum(wdscore) as total_score")->where($where3)->select();
    	$wdscore = $wdscore[0]["total_score"];
    	$totalScore = $totalScore + $wdscore;
    	$totalScore = round($totalScore);
    	
    	$score = M("exam_score");
    	$sWhere["uid"] = $uid;
    	$sWhere["exam_id"] = $param["examId"];
    	$sWhere["project_id"] = $param["project_id"];
    	$hasScore = $score->where($sWhere)->select();
    	
    	$data3["uid"] = $uid;
    	$data3["exam_id"] = $param["examId"];
    	$data3["total_score"] = $totalScore;
    	$data3["project_id"] = $param["project_id"];
    	$data3["is_publish"] = 0;
    	$data3["test_id"] = $param["examId"];
    	$data3["use_time"] = $param["use_time"];
    	if($hasScore){
	    	$return = $score->where($sWhere)->save($data3);
	    	if($return === false){
	    		return array("code"=>1031,"message"=>'更新失败，可尝试重新操作');
	    	}
    	}else{
	    	$return = $score->add($data3);
	    	if($return < 0 || $return === false){
	    		return array("code"=>1031,"message"=>'提交失败，可尝试重新操作');
	    	}
    	}
    	
    	//think_examination_attendance 学员考试考勤表 
    	$attWhere["uid"] = $uid;
    	$attWhere["test_id"] = $param["examId"];
    	$atten = M("examination_attendance")->field("id")->where($attWhere)->limit(1)->select();
    	//获取岗位部门
    	$job = M("tissue_group_access as a");
    	$job = $job->where("a.uid=".$uid)->field("a.tissue_id,a.job_id")->select();
    	if($atten){
			$attData["status"] = 1;
			M("examination_attendance")->where($attWhere)->limit(1)->save($attData);
    	}else{
			$attData["uid"] = $uid;
			$attData["test_id"] = $param["examId"];
			$attData["department_id"] = $job[0]["tissue_id"];//部门ID
			$attData["position_id"] = $job[0]["job_id"];//岗位ID
			$attData["status"] = 1;
			$attData["project_id"] = $param["project_id"];
			$attData["examination_id"] = $param["examId"];;//?????where
			M("examination_attendance")->add($attData);
			
			//添加学分学时
			$pro_exam = M("project_examination")->where("project_id=".$param["project_id"]." AND test_id=".$param["examId"])->limit(1)->select();
			$diffTime = strtotime($pro_exam[0]["end_time"]) - strtotime($pro_exam[0]["start_time"]);
			$hours = ceil($diffTime / 60);
			$data1["create_time"] = date("Y-m-d H:i:s");
			$data1["typeid"] = 0;
			$data1["credit"] = $pro_exam[0]["credits"];
			$data1["source_id"] = $param["examId"];
			$data1["project_id"] = $param["project_id"];
			$data1["user_id"] = $uid;
			$data1["hours"] = $hours;
			M("center_study")->add($data1);
    	}
    	return array("code"=>1000,"message"=>"操作成功 ");
    }
    
    /**
     * 查看考试题目详情
     * $param["examId"] 试卷ID int
     * $param["quesId"] 题目ID 不传默认试卷第一题,相应ID为对应的题目
     */
    public function seeDetail($param,$uid){
    	if(!$param || !$uid){
    		return false;
    	}
    	
    	if(!is_int($param["examId"]) || $param["examId"] < 1){
    		return "请提交考试ID";
    	}
    	
    	if(!is_int($param["project_id"]) || $param["project_id"] < 1){
    		return "请提交项目ID";
    	}
    	$quesWhere["a.examination_id"] = $param["examId"];
    	$ques = M("examination_item_rel as a")->field("id,title,optionA,optionB,optionC,optionD,optionE,classification,b.right_option,score")//b.right_option
    		->join("join __EXAMINATION_ITEM__ as b on a.examination_item_id=b.id")	
    		->where($quesWhere)->order("a.examination_item_id asc")->select();
    	if(!$ques){
    		return "system error,当前考试中没有获取到试题";
    	}
    	
    	//考试时间未结束，不能查看详情
    	$peWhere["project_id"] = $param["project_id"];
    	$peWhere["test_id"] = $param["examId"];
    	$project_exam = M("project_examination")->field("end_time")->where($peWhere)->select();
    	if(time() <= strtotime($project_exam[0]["end_time"])){
    		return "考试结束时间未到，请稍后查看";
    	}
    	
    	foreach ($ques as $key=>$value){
	    	$aWhere["u_exam_id"] = $uid;
	    	$aWhere["question_number"] = $value["id"];
	    	$aWhere["project_id"] = $param["project_id"];
	    	$aWhere["exam_id"] = $param["examId"];
    		$answer = M("exam_answer")->field("exam_answer,isexam")->where($aWhere)->limit(1)->select();
    		if($answer){
    			$ques[$key]["isexam"] = $answer[0]["isexam"];
    			$ques[$key]["my_answer"] = $answer[0]["exam_answer"];
    		}else{
    			$ques[$key]["isexam"] = 0;
    			$ques[$key]["my_answer"] = "";
    		}
    		
    		if($value["classification"] == 1 || $value["classification"] == 2){
				$ques[$key]["options"]["A"] = $ques[$key]["optiona"];
				$ques[$key]["options"]["B"] = $ques[$key]["optionb"];
				$ques[$key]["options"]["C"] = $ques[$key]["optionc"];
				$ques[$key]["options"]["D"] = $ques[$key]["optiond"];
				if($ques[$key]["optione"]){
					$ques[$key]["options"]["E"] = $ques[$key]["optione"];
				}
    		}elseif($ques[$key]["classification"] == 3){
    			if($value["right_option"] == "对"){
    				if($ques[$key]["optiona"] == "对"){
    					$value["right_option"] = "A";
    				}else{
    					$value["right_option"] = "B";
    				}
    			}
    			if($value["right_option"] == "错"){
    				if($ques[$key]["optiona"] == "错"){
    					$value["right_option"] = "A";
    				}else{
    					$value["right_option"] = "B";
    				}
    			}
    			$ques[$key]["options"]["A"] = $ques[$key]["optiona"];
    			$ques[$key]["options"]["B"] = $ques[$key]["optionb"];
    		}
    		unset($ques[$key]["optiona"]);
    		unset($ques[$key]["optionb"]);
    		unset($ques[$key]["optionc"]);
    		unset($ques[$key]["optiond"]);
    		unset($ques[$key]["optione"]);
    	}
    	
    	$where3["uid"] = $uid;
    	$where3["exam_id"] = $param["examId"];
    	$where3["project_id"] = $param["project_id"];
    	$exam_score = M("exam_score")->where($where3)->select();
    	$use_time = $exam_score[0]["use_time"];
    	//转为时分秒--按一天内计算
    	$use_time = gmstrftime('%H:%M:%S',$use_time);
    	
    	$data["use_time"] = $use_time;
    	$data["total_score"] = $exam_score[0]["total_score"];
    	$data["list"] = $ques;
    	return $data;
    }
}
