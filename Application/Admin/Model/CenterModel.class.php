<?php

namespace Common\Model;

use Common\Model\BaseModel;

class CenterModel extends BaseModel
{

	/*
	 * 初始化
	 */
    function __construct(){}

    /**
     * 用户中心 - 学习目标
     * type 1全部 2在线培训 3线下培训
	 * startTime 开始时间
	 * endTime 查询结束时间
	 * p 页码
     */
    public function study($param){
    	$user_id = $_SESSION["user"]["id"];
    	$nowYearStamp = strtotime(date("Y")."-01-01 00:00:00");
    	$nowYear = date("Y");
    	
    	if(!$param["startTime"] || !$param["endTime"]){
    		$param["startTime"] = date('Y-01-01 00:00:00');
    		$endTime = date('Y-01-01 00:00:00', strtotime("+1 year"));
    		$param["endTime"] = date('Y-m-d H:i:s', strtotime($endTime) - 1);
    	}else{
    		$param["startTime"] = $param["startTime"]."-01 00:00:00";
    		$days = date("t", strtotime($param["endTime"]."-01"));
    		$param["endTime"] = $param["endTime"]."-".$days." 23:59:59";
    	}
    	
    	$myTissue = M("tissue_group_access")->where("user_id=".$user_id)->find();
    	
    	//每页条数，导出时设置pageLen=10000
    	$pageLen = $param["pageLen"];
    	if(!$param["pageLen"]){
	    	$pageLen = 20;
    	}
    	
    	$start = ($param["p"] - 1) * $pageLen;
    	$pageNav = "";
    	if($param["type"] == 1){
	    	//全部：姓名 所属部门	学习课程数	获得学分	内部培训班	外派培训班	外出授课	获得学分	学习时长	考试次数	问卷次数	笔记数
    		$key = 0;
    		
    		$upCourseNum = 0;//在线学习课程数量
    		$downCourseNum = 0;//线下课程学习数量
    		$upCredit = 0;//线上课程积分
    		$downCredit = 0;//线下课程积分
    		$total_time = 0;//培训项目总学时
    		$up_time = 0;//在线课程学时
    		$total_credit = 0;//培训项目总学分
    		
    		//获取在线课程数据
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where2 = "user_id=".$user_id;
				$where2.= " and typeid in (4,5)";
				$where2.= " and create_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where2.= " and create_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where2["user_id"] = $user_id;
	    		$where2['create_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where2["typeid"] = array("in", "4,5");
			}
    		$total_credit = M("center_study")->field("credit,source_id")->where($where2)->select();
    		//center_study只统计了在线课程的数据
    		foreach ($total_credit as $value){
    			$course = M("course")->field("course_way,course_time")->where("id=".$value["source_id"])->find();
    			if($course){
    				if($course["course_way"] == 0){
    					$upCredit += $value["credit"];
    					$upCourseNum ++;
    					$total_time += $course["course_time"];
    				}
    			}
    		}
    		
    		//获取线下课程数据
    		//培训班--线下课程数据
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where4 = "a.user_id=".$user_id;
				$where4.= " and c.type in (0,4)";
				$where4.= " and b.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where4.= " and b.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where4["a.user_id"] = $user_id;
	    		$where4['b.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where4['c.type'] = array("in", "0,4");
			}
    		
    		$downPro = M("designated_personnel a")->field("b.*")
    			->join("join __PROJECT_COURSE__ b on a.project_id=b.project_id")
    			->join("join __ADMIN_PROJECT__ c on a.project_id=c.id")
    			->where($where4)
    			->select();
    		foreach ($downPro as $value){
    			$course = M("course")->field("course_time,course_way")->where("id=".$value["course_id"])->find();
    			if($course["course_way"] == 1){
    				if($value["is_attachment"] == 1){
    					//考勤开启
    					$awhere["user_id"] = $user_id;
    					$awhere["course_id"] = $value["course_id"];
    					$awhere["pid"] = $value["project_id"];
    					$awhere["status"] = array("in", "1,2");
    					$attendance = M("attendance")->where($awhere)->find();
    					if($attendance){
    						$downCredit += $value["credit"];
    						$downCourseNum ++;
		    				$total_time += $course["course_time"];
    					}
    				}else{
    					//考勤关闭
    					$downCredit += $value["credit"];
    					$downCourseNum ++;
	    				
    				}
    			}
    		}
    		$results[$key]["hours"] = round($total_time / 60, 2);
    		
    		$results[$key]["up_time"] = round($up_time / 60, 2);
    		
    		//姓名
    		$user = M("users")->where("id=".$user_id)->find();
    		$results[$key]["username"] = $user["username"];
    		
    		//所属部门
    		$results[$key]["part"] = "";
    		if($myTissue["tissue_id"]){
    			$tissueRule = M("tissue_rule")->where("id=".$myTissue["tissue_id"])->find();
    			$results[$key]["part"] = $tissueRule["name"];
    		}
    		
    		//学习课程数
    		$results[$key]["course_num"] = $upCourseNum;
    		
    		//获得学分
    		$results[$key]["upCredit"] = $upCredit;
    		$results[$key]["downCredit"] = $downCredit;
    		
    		//内部培训班
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$whereCommon = "a.user_id=".$user_id;
				
				$whereCommon.= " and b.type in (0,4)";
				$whereCommon.= " and start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$whereCommon.= " and start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$whereCommon["a.user_id"] = $user_id;
	    		
	    		$whereCommon['start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$whereCommon['b.type'] = array("in", "0,4");//已完成的项目
			}
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where5 = $whereCommon . " and b.training_category=0";
			}else{
				$where5 = array_merge($whereCommon,array('b.training_category'=>0));
			}
    		
    		$project0 = M("designated_personnel a")
    					->field("count(a.user_id) as join_num")
    					->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")
    					->where($where5)
    					->select();
    		$results[$key]["project0"] = $project0[0]["join_num"];
    		
    		//外派培训班
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where5 = $whereCommon . " and b.training_category=1";
			}else{
				$where5 = array_merge($whereCommon,array('b.training_category'=>1));
			}
    		
    		$project1 = M("designated_personnel a")
    					->field("count(a.user_id) as join_num")
    					->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")
    					->where($where5)
    					->select();
	
    		$results[$key]["project1"] = $project1[0]["join_num"];
    		
    		//外出授课
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where5 = $whereCommon . " and b.training_category=2";
			}else{
				$where5 = array_merge($whereCommon,array('b.training_category'=>2));
			}
    		
    		$project2 = M("designated_personnel a")
    					->field("count(a.user_id) as join_num")
    					->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")
    					->where($where5)
    					->select();
    		$results[$key]["project2"] = $project2[0]["join_num"];

    		//考试次数--培训项目 
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where8 = "a.user_id=".$user_id;
				$where8.= " and b.type=4";
				$where8.= " and c.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where8.= " and c.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where8["a.user_id"] = $user_id;
	    		$where8['c.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where8['b.type'] = 4;//已完成的项目
			}
    		
    		$projectExam = M("designated_personnel a")
    					->field("count(a.user_id) as join_num")
		    			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")
		    			->join("join __PROJECT_EXAMINATION__ c on a.project_id=c.project_id")	
		    			->where($where8)
		    			->order("c.start_time desc")
		    			->select();
    		
    		//考试次数--工具管理考试
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where9 = "a.user_id=".$user_id;
				$where9.= " and b.audit_status=0";
				$where9.= " and b.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where9.= " and b.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where9["a.user_id"] = $user_id;
	    		$where9["b.audit_status"] = 0;
	    		$where9['b.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
			}
    		
    		$toolExam = M("test_user_rel a")
    					->field("count(a.user_id) as join_num")
    					->join("join __TEST__ b on a.test_id=b.id")
    					->where($where9)
    					->select();
    		$results[$key]["exam_num"] = $projectExam[0]["join_num"] + $toolExam[0]["join_num"];
    		
    		//问卷次数--培训项目
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where10 = "a.user_id=".$user_id;
				$where10.= " and b.type=4";
				$where10.= " and c.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where10.= " and c.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where10["a.user_id"] = $user_id;
	    		$where10['c.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where10['b.type'] = 4;//已完成的项目
			}
    		
    		$projectSurvey = M("survey_attendance a")
    						->field("count(a.user_id) as join_num")
				    		->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")
				    		->join("join __PROJECT_SURVEY__ c on a.project_id=c.project_id")
				    		->where($where10)
				    		->order("c.start_time desc")
				    		->select();
    		
    		//问卷次数--工具管理
    		if($myTissue["tissue_id"]){
    			if(strtolower(C('DB_TYPE')) == 'oracle'){
					$where11 = "a.tissue_id=".$myTissue["tissue_id"];
					$where11.= " and b.audit_state=1";
					$where11.= " and b.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
					$where11.= " and b.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
				}else{
					$where11["a.tissue_id"] = $myTissue["tissue_id"];
		    		$where11["b.audit_state"] = 1;
		    		$where11['b.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
				}
	    		
	    		$toolSurvey = M("research_tissueid a")
	    					->field("count(a.research_id) as join_num")
	    					->join("join __RESEARCH__ b on a.research_id=b.id")
	    					->where($where11)
	    					->select();
    		}else{
    			$toolSurvey[0]["join_num"] = 0;
    		}
    		$results[$key]["survey_num"] = $projectSurvey[0]["join_num"] + $toolSurvey[0]["join_num"];
    		
    		//笔记数
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where12 = "user_id=".$user_id;
				$where12.= " and time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where12.= " and time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where12["user_id"] = $user_id;
    			$where12['time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
			}
    		
    		$note = M("course_note")->field("count(id) as num")->where($where12)->select();
    		$results[$key]["note_num"] = $note[0]["num"];
    	}elseif($param["type"] == 2){
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where3 = "user_id=".$user_id;
				$where3.= " and typeid in (4,5)";
				$where3.= " and create_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where3.= " and create_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where3['create_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where3["user_id"] = $user_id;
	    		$where3["typeid"] = array("in", "4,5");
			}
    		
    		$results = M("center_study")->where($where3)->limit($start, $pageLen)->order("create_time desc")->select();
    		foreach ($results as $key=>$value){
    			//在线培训：完成学习时间	课程名称	课程时长	学分	课程类型	操作
    			$course = M("course")->where("id=".$value["source_id"])->find();
    			$pcourse = M("project_course")->where("course_id=".$value["source_id"]." and project_id=".$value["project_id"])->find();
    			$course_name = $pcourse["course_names"];
    			if(!$pcourse["course_names"]){
    				$course_name = $course["course_name"];
    			}
    			$results[$key]["course_name"] = $course_name;
    			
    			$results[$key]["course_time"] = $course["course_time"];
    			$results[$key]["course_credit"] = $value["credit"];
    			
    			$course_type = M("course_category")->where("id=".$course["course_cat_id"])->find();
    			$results[$key]["course_type"] = $course_type["cat_name"];
    		}
    		
    		$totalNum = M("center_study")->where($where3)->select();
    		$pageNav = $this->pageClass($totalNum[0]["num"], $pageLen);
    	}else{
    		if(strtolower(C('DB_TYPE')) == 'oracle'){
				$where3 = "a.user_id=".$user_id;
				$where3.= " and b.type in (0,4)";
				$where3.= " and b.start_time >to_date('".$param["startTime"]."','yyyy-mm-dd hh24:mi:ss')";
				$where3.= " and b.start_time <to_date('".$param["endTime"]."','yyyy-mm-dd hh24:mi:ss')";
			}else{
				$where3["a.user_id"] = $user_id;
	    		$where3['b.start_time'] = array(array('gt', $param["startTime"]), array('lt', $param["endTime"]));
	    		$where3["b.type"] = array("in", "0,4");
			}
    		
    		//线下培训：主办部门	培训项目名称	培训类别	培训开始时间	培训结束时间	培训学时	培训对象	培训组织人员	获得学分	操作
    		$results = M("designated_personnel a")->field("a.*,b.*")
    			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where3)->limit($start, $pageLen)
    			->order("b.start_time desc")->select();
    		foreach ($results as $key=>$value){
	    		//所属部门
	    		$tissue = M("tissue_group_access")->where("user_id=".$value["user_id"])->find();
	    		$tissueRule = M("tissue_rule")->where("id=".$tissue["tissue_id"])->find();
	    		$results[$key]["part"] = $tissueRule["name"];
	    		
	    		$project_type = "";
	    		if($value["training_category"] == 0){
	    			$project_type = "内部培训";
	    		}elseif($value["training_category"] == 1){
	    			$project_type = "外派培训";
	    		}elseif($value["training_category"] == 2){
	    			$project_type = "外出授课";
	    		}
	    		$results[$key]["project_type"] = $project_type;
	    		
	    		$time = M("project_course a")->field("a.course_id,a.project_id,a.is_attachment,a.credit as p_credit,b.*")->join("join __COURSE__ b on a.course_id=b.id")->where("project_id=".$value["project_id"])->select();
	    		$total_time = 0;
	    		$total_credit = 0;
	    		foreach ($time as $value2){
		    		if($value2["course_way"] == 1){
		    			if($value2["is_attachment"] == 1){
		    				//考勤开启
		    				$awhere["user_id"] = $user_id;
		    				$awhere["course_id"] = $value2["course_id"];
		    				$awhere["pid"] = $value2["project_id"];
		    				$awhere["status"] = array("in", "1,2");
		    				$attendance = M("attendance")->where($awhere)->find();
		    				if($attendance){
		    					$total_time += $value2["course_time"];
		    					$total_credit += $value2["p_credit"];
		    				}
		    			}else{
		    				$total_time += $value2["course_time"];
		    				$total_credit += $value2["p_credit"];
		    			}
		    		}
	    		}
	    		$results[$key]["total_time"] = round($total_time / 60, 2);
	    		$results[$key]["credit"] = $total_credit;
	    		
	    		$manager = M("users")->where("id=".$value["user_id"])->find();
	    		$results[$key]["manager"] = $manager["username"];
    		}
    		
    		$totalNum = M("designated_personnel a")->field("count(a.id) as num")->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where3)->select();
    		$pageNav = $this->pageClass($totalNum[0]["num"], $pageLen);
    	}
    	
    	$data = array(
    			'pageNav' => $pageNav,
    			"list" => $results,
    	);
        return $data;
    }
	
    /**
	 * 学分统计筛选
	 * creditType 1年度 2季度 3月份
	 */
	public function getStudyCredit($creditType){
		$user_id = $_SESSION["user"]["id"];
		$nowYearStamp = strtotime(date("Y")."-01-01 00:00:00");
		$nowYear = date("Y");
		if($creditType == 1){
			$startTime = date('Y-01-01 00:00:00');
			$endTime = date('Y-01-01 00:00:00', strtotime("+1 year"));
			$endTime = date('Y-m-d H:i:s', strtotime($endTime) - 1);
		}elseif($creditType == 2){
			$date = getdate();
			$month = $date['mon'];//当前第几个月
			$year = $date['year'];//但前的年份
			$startMonth = ceil($month / 3) * 3 - 2;//单季第一个月
			$strart = mktime(0, 0, 0, $startMonth, 1, $year);//当季第一天的时间戳
			$end = mktime(0, 0, 0, $startMonth + 3, 1, $year);//当季最后一天的时间戳
			$startTime = date('Y-m-d H:i:s', $strart);
			$endTime = date('Y-m-d H:i:s', $end - 1);
		}else{
			$startTime = date('Y-m-01 00:00:00');
			$endTime = date('Y-m-01 00:00:00', strtotime("+1 month"));
			$endTime = date('Y-m-d H:i:s', strtotime($endTime) - 1);
		}
		
		//申请学分
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where1 = "user_id=".$user_id;
			$where1.= " and typeid not in (4,5)";
			$where1.= " and create_time >to_date('".$startTime."','yyyy-mm-dd hh24:mi:ss')";
			$where1.= " and create_time <to_date('".$endTime."','yyyy-mm-dd hh24:mi:ss')";
		}else{
			$where1["user_id"] = $user_id;
			$where1["typeid"] = array("not in","4,5");
			$where1['create_time'] = array(array('gt', $startTime), array('lt', $endTime));
		}
		
		$applyTotalScore = M("center_study")->field("sum(credit) as apply_credit")->where($where1)->select();
		
		$upCourseNum = 0;//在线学习课程数量
		$downCourseNum = 0;//线下课程学习数量
		$upCredit = 0;//线上课程积分
		$downCredit = 0;//线下课程积分
		
		//获取在线课程数据
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where2 = "user_id=".$user_id;
			$where2.= " and typeid in (4,5)";
			$where2.= " and create_time >to_date('".$startTime."','yyyy-mm-dd hh24:mi:ss')";
			$where2.= " and create_time <to_date('".$endTime."','yyyy-mm-dd hh24:mi:ss')";
		}else{
			$where2["user_id"] = $user_id;
			$where2['create_time'] = array(array('gt', $startTime), array('lt', $endTime));
			$where2["typeid"] = array("in", "4,5");
		}
		
		$total_credit = M("center_study")->field("credit,source_id")->where($where2)->select();
		//center_study只统计了在线课程的数据
		foreach ($total_credit as $value){
			$course = M("course")->field("course_way")->where("id=".$value["source_id"])->find();
			if($course){
				if($course["course_way"] == 0){
					$upCredit += $value["credit"];
					$upCourseNum ++;
				}
			}
		}
		
		//培训班--线下课程数据
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where2 = "a.user_id=".$user_id;
			$where2.= " and c.type in (0,4)";
			$where2.= " and b.start_time >to_date('".$startTime."','yyyy-mm-dd hh24:mi:ss')";
			$where2.= " and b.start_time <to_date('".$endTime."','yyyy-mm-dd hh24:mi:ss')";
		}else{
			$where4["a.user_id"] = $user_id;
			$where4['b.start_time'] = array(array('gt', $startTime), array('lt', $endTime));
			$where4['c.type'] = array("in", "0,4");
		}
		
		$downPro = M("designated_personnel a")->field("b.*")
			->join("join __PROJECT_COURSE__ b on a.project_id=b.project_id")
			->join("join __ADMIN_PROJECT__ c on a.project_id=c.id")
			->where($where4)->select();
		foreach ($downPro as $value){
			$course = M("course")->field("course_way")->where("id=".$value["course_id"])->find();
			if($course["course_way"] == 1){
				if($value["is_attachment"] == 1){
					//考勤开启
					$awhere["user_id"] = $user_id;
					$awhere["course_id"] = $value["course_id"];
					$awhere["pid"] = $value["project_id"];
					$awhere["status"] = array("in", "1,2");
					$attendance = M("attendance")->where($awhere)->find();
					if($attendance){
						$downCredit += $value["credit"];
						$downCourseNum ++;
					}
				}else{
					//考勤关闭
					$downCredit += $value["credit"];
					$downCourseNum ++;
				}
			}
		}
		
		//获取课程学分目标，目标年度学分完成率（当前时间段学分 / 学分目标）
		$myTissue = M("tissue_group_access")->where("user_id=".$user_id)->find();
		$myGole = 0;
		if($myTissue){
			$wheretl["tissue_id"] = $myTissue["tissue_id"];
			$wheretl["job_id"] = $myTissue["job_id"];
			$wheretl["typeid"] = 4;
			$toolGoal = M("tool_learning")->where($wheretl)->find();
			
			//按年统计，所有月份相加
			$myGole += $toolGoal["january"] + $toolGoal["february"] + $toolGoal["march"] +$toolGoal["april"]+$toolGoal["may"]+$toolGoal["june"];
			$myGole += $toolGoal["july"]+$toolGoal["august"]+$toolGoal["september"]+$toolGoal["october"]+$toolGoal["november"]+$toolGoal["december"];
			$myGole = ceil($myGole);
		}
		
		$finishRate = 0;
		$totalScore = $downCredit + $upCredit + $applyTotalScore[0]["apply_credit"];
		if($myGole > 0){
			$finishRate = round($totalScore / $myGole, 2) * 100;
		}
		
		$return = array("totalScore"=>$totalScore, "upCredit"=>$upCredit, "downCredit"=>$downCredit, "finishRate"=>$finishRate);
		return $return;
	}
    
    /**
     * 找人Pk
     */
    public function pk(){

        $start_time = mktime(0,0,0,date('m'),1,date('Y'));

        $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

        $where['time'] = array(array('egt',$start_time),array('elt',$end_time));

        $where['user_id'] = array("eq",$_SESSION['user']['id']);

        $where['score'] = array("gt",0);

        //获取当月积分
        $integral_items = M('integration_record')->where($where)->select();

        $total_integral = $this->pkData($integral_items);

        $total_integral = array_sum($total_integral);
        $where1['time'] = array(array('egt',$start_time),array('elt',$end_time));

        $where1['user_id'] = array("eq",$_SESSION['user']['id']);

        $where1['need_score'] = array("gt",0);
        //获取本月已兑换福利所减掉的积分
        $need_integral = M('Welfare_record')->where($where1)->sum('need_score');
        $this_month_score = $total_integral - $need_integral;
        $data = array(
            "username"=>$_SESSION['user']['username'],
            "avatar"=>$_SESSION['user']['avatar'],
            "integral"=>$this_month_score
        );

        return $data;
    }


    /**
     * PK 成员
     */
    public function pkMember(){

        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);

        //获取用户上级组织名称
        $user = M("tissue_group_access a")
        		->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
        		->field("a.tissue_id,b.pid")
        		->where($where)
        		->find();

        $items = D('AdminTissue')->tree($user['tissue_id']);

        //获取当前用户所在级别
        $level = D('AdminTissue')->hierarchy($items['id']);

        //普通会员
        if($level == 4){

            $items = D('AdminTissue')->tree($user['pid']);

            $is_admin = false;

        }else{

            $is_admin = true;

        }

        $pkMember_list = $this->PeopleData($level,$items);

        $admin_list = array();

        if($is_admin){

            //获取管理组组织ID

            $user_id = array_unique($pkMember_list['admin_list']);

            if(!empty($user_id)){

                $map['tissue_id'] = array("in",$user_id);
                $map['b.id'] = array("gt",0);
                $map['b.status'] = array('eq',1);

                $admin_list = M("tissue_group_access a")->field("b.id,b.username")->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")->where($map)->select();

            }

        }

        $data = array(
            "items"=>$pkMember_list['items'],
            "admin_list"=> $admin_list,
            "is_admin"=>$is_admin
        );

        return $data;

    }


    /**
     * 取出部门和人
     */
    public function PeopleData($level,&$data,&$pkMember_list,&$admin_list){

        $level_arr = array(1=>3,2=>2,3=>1,4=>1);

        foreach($data['_data'] as $item){

            if($item['_level'] == $level_arr[$level]){

                $admin_list[] = $item['pid'];

               $pkMember_list[] = $this->tissuePeople($item);

            }else{

                $admin_list[] = $item['id'];

                $this->PeopleData($level,$item,$pkMember_list,$admin_list);

            }

        }

        $data = array(
            "items"=>$pkMember_list,
            "admin_list"=>$admin_list
        );

        return $data;

    }

    /**
     * 取部门
     */
    public function departmentData($level,&$data,&$pkMember_list,&$admin_list){

        $level_arr = array(1=>2,2=>1);

        if($level >=3){

            $pkMember_list[] = $data;

        }else{


            foreach($data['_data'] as $item){

                if($item['_level'] == $level_arr[$level]){

                    $pkMember_list[] = $item;

                }else{

                    $this->departmentData($level,$item,$pkMember_list,$admin_list);

                }

            }


        }
        return  $pkMember_list;

    }

    /**
     * 查询PK人 从组织架构上取人
     */
    public function tissuePeople($item){

		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$condition = "a.tissue_id in " . $item['id'] . "and b.status != 3";
		}else{
			$condition['a.tissue_id'] = array("in",$item['id']);

	        $condition['b.status'] = array('neq',3);
		}
        $user_list = M("tissue_group_access a")
        		->field("b.id,b.username")
        		->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
        		->where($condition)
        		->select();

        $pkMember_list['name'] = $item['name'];

        $pkMember_list['_data'] = $user_list;

        return $pkMember_list;

    }

    /**
     * PK 成员Ajax
     */
    public function memberAjax(){

        $pk_id = I("post.pk_id");
        $start_time = mktime(0,0,0,date('m'),1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

        //获取PK对象数据
        $where['user_id'] = array("eq",$pk_id);
        $where['score'] = array("gt",0);
        $where['time'] = array(array('egt',$start_time),array('elt',$end_time));

        $pk_items = M('integration_record')->where($where)->select();

        //查询用户名
        $pk_username =  M('users')->field("username,avatar")->where("id=".$pk_id)->find();

        $pk_list = $this->pkData($pk_items);


        //获取自己数据
        $condition['user_id'] = array("eq",$_SESSION['user']['id']);
        $condition['score'] = array("gt",0);
        $condition['time'] = array(array('egt',$start_time),array('elt',$end_time));

        $my_items = M('integration_record')->where($condition)->select();

        $my_list = $this->pkData($my_items);

        //$my_list = $pk_list = array();

        //比较PK数据
        /*
        for($i=0;$i<=5;$i++){
            $result = $this->percentage($pk_user[$i],$my_user[$i]);
            $pk_list[$i] = $result[0];
            $my_list[$i] = $result[1];
        }*/

        //PK月积分
        $pk_integral = array_sum($pk_list);
        $my_integral = array_sum($my_list);

        $data = array(
            "pk_name"=>$pk_username['username'],
            "pk_list"=>$pk_list,
            "pk_avatar"=>$pk_username['avatar'],
            "my_list"=>$my_list,
            "my_name"=>$_SESSION['user']['username'],
            "pk_integral"=>$pk_integral,
            "my_integral"=>$my_integral
        );

        return $data;

    }

    /**
     * @param $data
     * @return array
     * 获取PK数据
     */
    public function pkData($data){

        $type_name = array("好为人师","乐分享","系统达人","任务范儿","爱学习","我是学霸");

        $pk_user = array(0,0,0,0,0,0);

        foreach($data as $item){

            switch($item['type']){
                case $type_name[0]:
                    $pk_user[0] += $item['score'];
                    break;
                case $type_name[1]:
                    $pk_user[1] += $item['score'];
                    break;
                case $type_name[2]:
                    $pk_user[2] += $item['score'];
                    break;
                case $type_name[3]:
                    $pk_user[3] += $item['score'];
                    break;
                case $type_name[4]:
                    $pk_user[4] += $item['score'];
                    break;
                case $type_name[5]:
                    $pk_user[5] += $item['score'];
                    break;
            }

        }

        return $pk_user;

    }

    /**
     * PK 部门Ajax
     */
    public function departmentAjax(){

        $pk_id = I("post.pk_id");

        //获取PK对象数据
        $pk_total = $this->getpk($pk_id);
        $pk_name =  M('tissue_rule')->field("name")->where("id=".$pk_id)->find();

        //获取自己数据
        $condition['user_id'] = array("eq",$_SESSION['user']['id']);
        $my_tissue_id = M('tissue_group_access')->field('tissue_id')->where($condition)->find();
        $my_total = $this->getpk($my_tissue_id['tissue_id']);
        $my_name =  M('tissue_rule')->field("name")->where("id=".$my_tissue_id['tissue_id'])->find();

        //计算平均值
        $my = M('tissue_group_access')->field('tissue_id')->where("tissue_id=".$my_tissue_id['tissue_id'])->count();
        $pk = M('tissue_group_access')->field('tissue_id')->where("tissue_id=".$pk_id)->count();

        if(empty($my)){
            $my_total = 0;
        }else{
            $my_total = round($my_total / $my);
        }

        if(empty($pk)){
            $pk_total = 0;
        }else{
            $pk_total  = round($pk_total / $pk);
        }

        $data = array(
            "pk_name"=>$pk_name['name'],
            "pk_total"=>$pk_total,
            "my_total"=>$my_total,
            "my_name"=>$my_name['name']
        );

        return $data;

    }

    /**
     * 部门PK公共函数
     */
    public function getpk($tissue_id){

        $where['tissue_id'] = array("eq",$tissue_id);

        $list = array();

        $items = M('tissue_group_access')->field('user_id')->where($where)->select();

        if(empty($items)){

            $total = 0;

        }else{

            foreach($items as $item){
                $list[] = $item['user_id'];
            }

            $where = array();
            $where['score'] = array("gt",0);
            $where['user_id'] = array("in",$list);
            $start_time = mktime(0,0,0,date('m'),1,date('Y'));

            $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

            $where['time'] = array(array('egt',$start_time),array('elt',$end_time));

            $integration_list = M('integration_record')->where($where)->select();

            $my_list = $this->pkData($integration_list);

            //合并部门总值
            $total = array_sum($my_list);

        }

        return $total;

    }

    /**
     * 部门PK
     */
    public function pkDepartment(){

        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);

        //获取用户上级组织名称
        $tissue_name = M("tissue_group_access a")->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")->field("b.id,b.name,b.pid")->where($where)->find();


        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);

        //获取用户上级组织名称
        $user = M("tissue_group_access a")->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")->field("a.tissue_id,b.pid")->where($where)->find();

        $items = D('AdminTissue')->tree($user['tissue_id']);

        //获取当前用户所在级别
        $level = D('AdminTissue')->hierarchy($items['id']);

        //普通会员
        if($level == 4){
            $items = D('AdminTissue')->tree($user['pid']);
        }

        $pkMember_list = $this->departmentData($level,$items);
        $data = array(
            "tree_items"=>$pkMember_list,
            "tissue_name"=>$tissue_name
        );

        return $data;


    }

    /**
     * [获取学习目标信息]
     * @return [array] [description]
     */
    public function getStudyData(){
        $user_id = session('user.id');
        $time = explode('-',date('Y-F'));
        $year = $time[0];
        $month = strtolower($time[1]);
        
        $info = M('tissue_group_access a')
                ->join('left join __TOOL_LEARNING__ b on a.tissue_id=b.tissue_id and a.job_id=b.job_id')
                ->where(array('a.user_id'=>$user_id,'b.year'=>$year))
                //->field('typeid,'.$month)
                ->select();

        //dump($info);//类别(0-必修,1-选修,2-修读，3-积分(新增类型)

        $integration = $this->getUserIntegration(); //总积分
        $hours_all = $this->getUserHours();          //总学时
        // $hours_bixiu = $this->getUserHours(false,false,false,4);          //必修学时
        // $hours_xuanxiu = $this->getUserHours(false,false,false,5);          //选修学时

        for($i = 1;$i <= 12;$i ++){
            if($i < 10){
                $i = '0'.$i;
            }
            $k = $this->getUserHours(false,false,$i,4);          //必修学时（一年）
            $v = $this->getUserHours(false,false,$i,5);          //选修学时（一年）

            $hours_bixiu += $k;
            $hours_xuanxiu += $v;
        }
        
        $course = D('Student')->getCourse($user_id);

        //公开课课程
        $where2['a.user_id'] = $user_id;
        $where2['a.project_id'] = 0;
        $where2['a.create_time'] = array('like','%'.date('Y').'%');
        $data2= M('course_chapter a')
                ->join('left join __COURSE__ b on a.course_id=b.id')
                ->where($where2)
                ->group('a.course_id,a.*,b.*')
                ->select();

        foreach($data2 as $k=>$v){
            $per = D('Student')->getCoursePer($v['user_id'],$v['course_id']);
            $data2[$k]['per'] = $per;
        }

        $courses = array_merge($course,$data2);

        $finishedCourseNum = $unFinishedCourseNum = 0;

        foreach($courses as $k=>$v){
            if($v['per'] == 100){
                $finishedCourseNum += 1;    //已完成的课程数量
            }
            $courses[$k]['project_id'] = $v['project_id'] == 0 ? 'true' : $v['project_id'];
        }
               
        $data = array();
        foreach($info as $k=>$v){
            switch ($v['typeid']) {
                case '0':
                    $s = $v['january']+$v['february']+$v['march']+$v['april']+$v['may']+$v['june']+$v['july']+$v['august']+$v['september']+$v['october']+$v['november']+$v['december'];
                    $data[] = round(($hours_bixiu / $s)*100);
                    break;
                case '1':
                    $s = $v['january']+$v['february']+$v['march']+$v['april']+$v['may']+$v['june']+$v['july']+$v['august']+$v['september']+$v['october']+$v['november']+$v['december'];
                    $data[] = round(($hours_xuanxiu / $s)*100);
                    break;
                case '2':
                    $s = $v['january']+$v['february']+$v['march']+$v['april']+$v['may']+$v['june']+$v['july']+$v['august']+$v['september']+$v['october']+$v['november']+$v['december'];
                    $data[] = round(($finishedCourseNum / $s)*100);
                    break;
                case '3':
                    $data[] = round(($integration / $v[$month])*100);
                    break;
            }
        }
        
        $hour_bixiu_1 = $this->getUserHours(false,false,false,4);          //必修学时（当前月份）
        $hours_xuanxiu_1 = $this->getUserHours(false,false,false,5);          //选修学时（当前月份）
        
        $data[] = round(( ($hour_bixiu_1+$hours_xuanxiu_1) / ($info[0][$month]+$info[1][$month]) )*100);

        return $data;
        //$data 0-必修% 1-选修% 2-课程% 3-总积分% 4-总学时%
    }

    /**
     * 获取某用户某年某月的积分  -- 总积分
     * @param  [type] $user_id   [用户id]
     * @param  [type] $year  [年份]
     * @param  [type] $month [月份]
     * @return [int]        [积分数值]
     */
    public function getUserIntegration($user_id=false,$year=false,$month=false){
        if(!$user_id){
            $user_id = session('user.id');
        }
        if(!$year){
            $year = date('Y');
        }
        if(!$month){
            $month = date('m');
        }
        $date = $year . '-' . $month;//2017-03
        $data = M('integration_record')->where(array('user_id'=>$user_id,'score'=>array('gt',0)))->select();
        
        foreach($data as $k=>$v){
            $data[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }
        $integration = 0;
        
        foreach($data as $k=>$v){
            if(strpos($v['time'],$date) !== false){
                $integration += (int)$v['score'];
            }
        }
        return $integration;
    }

    /**
     * 获取某用户某年某月某个类型的总学时
     * @param  boolean $user_id   [用户id]
     * @param  boolean $year  [年份]
     * @param  boolean $month [月份]
     * @param  boolean $typeid [类型id]
     * @return [int]         [学时(分钟)]
     */
    public function getUserHours($user_id=false,$year=false,$month=false,$typeid=false){
        if(!$user_id){
            $user_id = session('user.id');
        }
        if(!$year){
            $year = date('Y');
        }
        if(!$month){
            $month = date('m');
        }

        if(!is_array($typeid)){
            settype($typeid,'array');
        }

        $date = $year . '-' . $month;//2017-03
        $data = M('center_study')
                ->where(array('user_id'=>$user_id,'create_time'=>array('like',"%$date%"),'typeid'=>array('in',$typeid)))
                ->sum('hours');
        $data = $data ? (int)$data : 0;
        return $data;
    }
    




}