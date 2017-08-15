<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 定时任务处理
 * 20170427
 */
class CronMessageController extends Controller{
	/**
	 * 定时处理消息推送 3分钟执行一次
	 */
	public function cron(){
		$timestamp = time();
		//通用触发 D('Trigger')->messageTrigger("消息接收人", "通知标题", "创建时间", "消息类型", "消息发起人", "消息查看地址");
		//消息类型--部分消息无需定时任务
		/*
		1	课程制作	你有课程制作的任务，任务信息如下--no
		2	试卷制作	你有试卷制作的任务，任务信息如下--no
		3	问卷制作	你有问卷制作的任务，任务信息如下--no
		4	授课任务	你有面授课程即将开始开讲，信息如下--ok
		5	成绩发布	你有考试成绩即将发布，信息如下--ok
		6	调研结果	你有调研结果待查看，点击前往--ok
		7	审批任务	你有新的审批任务，点击前往 --行为触发--不推送
		8	统计调研	你有统计调研的任务，信息如下--no
		9	签到提醒	你有签到提醒的任务，信息如下--ok
		10	课程学习	你有待学习课程即将开始，信息如下--ok
		11	参加考试	你有待参加考试即将开始，信息如下--ok
		12	参与调研	你有待参与调研即将开始，信息如下--ok
		13	计划总结	你有已完成项目待写总结，信息如下--ok
		14	互动消息	你的互动有新的评论/赞，请查看 --行为触发
		15	问答消息	你的问答有新消息，请查看： --行为触发
		
		4 5 6 9 10 11 12 13
		
		*/
		$execMin = 3;//执行时间，单位：分
		$startTime = date("Y-m-d H:i:00");
		$endTime = date("Y-m-d H:i:00",time() + $execMin * 60);
		
		//4	授课任务 选修(没有时间，不要)
		/*
		$where4["start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where4["course_way"] = 1;//授课方式:0在线，1面授
		$where4["status"] = 1;
		$where4["is_public"] = 1;//公开课  选修
		$data4 = M("course")->field("id,course_name,user_id,lecturer")->where($where4)->select();
		foreach ($data4 as $key=>$value){
			$lecturerId = $value["lecturer"] + 0;
			if($lecturerId > 0){
				$lecturer = M("lecturer")->field("user_id")->where("id=".$lecturerId)->limit(1)->select();
				if($lecturer){
					$seeUrl = "admin/my_course/detail/course_id/".$value["id"]."/project_id/true/course_name/".$value["course_name"]."/ogtype/1/whetherteach/1";
					D('Trigger')->messageTrigger($lecturer[0]["user_id"], "你有面授课程即将开始开讲，信息如下", date("Y-m-d H:i:s"), 4, $value["user_id"], $seeUrl);
				}
			}
			
			//10课程学习 通知到学员
			$students = M("course_record")->field("user_id")->where("course_id=".$value["id"])->select();
			foreach ($students as $key2=>$value2){
				$seeUrl = "admin/my_course/detail/course_id/".$value["id"]."/project_id/true";
				//9	签到提醒 你有签到提醒的任务，信息如下
				D('Trigger')->messageTrigger($value2["user_id"], "你有签到提醒的任务，信息如下", date("Y-m-d H:i:s"), 9, $value["user_id"], $seeUrl);
				
				//10课程学习
				D('Trigger')->messageTrigger($value2["user_id"], "你有待学习课程即将开始，信息如下", date("Y-m-d H:i:s"), 10, $value["user_id"], $seeUrl);
			}
		}
		*/
		
		//4	授课任务 必修
		$where41["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where41["b.type"] = array("in", "0,4");
		$data41 = M("project_course a")->field("a.start_time,a.lecturer_id,a.course_id,a.project_id,a.course_names as course_name,b.user_id")
			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where41)->select();
		foreach ($data41 as $key=>$value){
			$thisCourse = M("course")->field("course_name,course_way")->where("id=".$value["course_id"])->limit(1)->select();
			if($thisCourse[0]["course_way"] == "1"){
				$lecturerId = $value["lecturer_id"] + 0;
				if($lecturerId > 0){
					$lecturer = M("lecturer")->field("user_id")->where("id=".$lecturerId)->limit(1)->select();
					if($lecturer){
						if(!$value["course_name"]){
							$value["course_name"] = $thisCourse[0]["course_name"];
						}
						$seeUrl = "admin/my_course/detail/course_id/".$value["course_id"]."/project_id/true/course_name/".$value["course_name"]."/ogtype/1/whetherteach/1";
						D('Trigger')->messageTrigger($lecturer[0]["user_id"], "你有面授课程即将开始开讲，信息如下", date("Y-m-d H:i:s"), 4, $value["user_id"], $seeUrl);
					}
				}
			}
			
			$students = M("designated_personnel")->where("project_id=".$value["project_id"])->select();
			foreach ($students as $key2=>$value2){
				$seeUrl = "admin/my_course/detail/course_id/".$value["course_id"]."/project_id/".$value["project_id"];
				if($thisCourse[0]["course_way"] == "1"){
					//9	签到提醒 你有签到提醒的任务，信息如下
					D('Trigger')->messageTrigger($value2["user_id"], "你有签到提醒的任务，信息如下", date("Y-m-d H:i:s"), 9, $value["user_id"], $seeUrl);
				}
				
				//10课程学习 通知到学员
				D('Trigger')->messageTrigger($value2["user_id"], "你有待学习课程即将开始，信息如下", date("Y-m-d H:i:s"), 10, $value["user_id"], $seeUrl);
			}
		}
		
		//5成绩发布  你有考试成绩即将发布，信息如下
		$where5["a.end_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where5["b.type"] = array("in", "0,4");
		$data5 = M("project_examination a")->field("a.start_time,a.end_time,a.test_id,a.project_id,a.manager_id,b.user_id")
			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where5)->select();
		foreach ($data5 as $key=>$value){
			//通知到学员
			$students = M("designated_personnel")->where("project_id=".$value["project_id"])->select();
			foreach ($students as $key2=>$value2){
				if($value["test_id"] > 0){
					//线上考试
					$seeUrl = "admin/my_exam/result/pid/".$value["project_id"]."/eid/".$value["test_id"];
				}else{
					//线下考试
					$seeUrl = "admin/my_exam/lookresultoffline/examination_id/0/project_id/".$value["project_id"];
				}
				D('Trigger')->messageTrigger($value2["user_id"], "你有考试成绩即将发布，信息如下", date("Y-m-d H:i:s"), 5, $value["user_id"], $seeUrl);
			}
		}
		//其他考试
		$where51["a.end_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where51["a.audit_status"] = 0;
		$data51 = M("test a")->field("id,examination_id,create_user,type")->where($where51)->select();
		foreach ($data51 as $key=>$value){
			//通知到学员
			$students = M("test_user_rel")->where("test_id=".$value["id"])->select();
			foreach ($students as $key2=>$value2){
				//线下 admin/my_exam/lookresultoffline/test_id/30/examination_id/0/flag/flag
				//线上 admin/my_exam/result/tid/29/eid/5/user_id/368
				if($value["type"] == 1){
					$seeUrl = "admin/my_exam/lookresultoffline/test_id/".$value["id"]."/examination_id/0/flag/flag";
				}else{
					$seeUrl = "admin/my_exam/result/tid/".$value["id"]."/eid/".$value["examination_id"]."/user_id/".$value["create_user"];
				}
				D('Trigger')->messageTrigger($value2["user_id"], "你有考试成绩即将发布，信息如下", date("Y-m-d H:i:s"), 5, $value["create_user"], $seeUrl);
			}
		}
		
		//6	调研结果	你有调研结果待查看，点击前往
		//项目调研结果
		$where6["a.end_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where6["b.type"] = array("in", "0,4");
		$data6 = M("project_survey a")->field("a.start_time,a.end_time,a.survey_id,a.project_id,a.manager_id,b.user_id")
			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where6)->select();
		foreach ($data6 as $key=>$value){
			//通知到学员
			$students = M("designated_personnel")->where("project_id=".$value["project_id"])->select();
			foreach ($students as $key2=>$value2){
				$seeUrl = "admin/my_survey/checksurveyresult/project_id/".$value["project_id"]."/survey_id/".$value["survey_id"]."/typeid/0";
				D('Trigger')->messageTrigger($value2["user_id"], "你有调研结果待查看，点击前往", date("Y-m-d H:i:s"), 6, $value["user_id"], $seeUrl);
			}
		}
		//系统调研结果
		$where61["a.end_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where61["a.audit_state"] = 1;
		$data61 = M("research a")->field("id,survey_id,create_user")->where($where61)->select();
		foreach ($data61 as $key=>$value){
			//通知到学员
			$students = M("research_attendance")->where("research_id=".$value["id"]." and state='1'")->select();
			foreach ($students as $key2=>$value2){
				$seeUrl = "admin/my_survey/checksurveyresult/research_id/".$value["id"]."/survey_id/".$value["survey_id"]."/typeid/1";
				D('Trigger')->messageTrigger($value2["user_id"], "你有调研结果待查看，点击前往", date("Y-m-d H:i:s"), 6, $value["create_user"], $seeUrl);
			}
		}
		
		//11 参加考试 你有待参加考试即将开始，信息如下
		$where11["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where11["b.type"] = "0";
		$data11 = M("project_examination a")->field("a.start_time,a.end_time,a.test_id,a.project_id,a.manager_id,b.user_id")
			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where11)->select();
		foreach ($data11 as $key=>$value){
			//通知到学员
			$students = M("designated_personnel")->where("project_id=".$value["project_id"])->select();
			foreach ($students as $key2=>$value2){
				if($value["test_id"] > 0){
					//线上考试
					$seeUrl = "admin/my_exam/joinexam/examination_id/".$value["test_id"]."/project_id/".$value["project_id"];
				}else{
					//线下考试
					$seeUrl = "admin/my_exam/lookresultoffline/examination_id/0/project_id/".$value["project_id"];
				}
				D('Trigger')->messageTrigger($value2["user_id"], "你有待参加考试即将开始，信息如下", date("Y-m-d H:i:s"), 11, $value["user_id"], $seeUrl);
			}
		}
		//其他考试
		$where111["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where111["a.audit_status"] = 0;
		$data111 = M("test a")->field("id,examination_id,create_user,type")->where($where111)->select();
		foreach ($data111 as $key=>$value){
			//通知到学员
			$students = M("test_user_rel")->where("test_id=".$value["id"])->select();
			foreach ($students as $key2=>$value2){
				if($value["type"] == 1){
					//线下考试
					$seeUrl = "admin/my_exam/lookresultoffline/test_id/".$value["id"]."/examination_id/0/flag/flag";
				}else{
					//线上考试
					$seeUrl = "admin/my_exam/joinexam/test_id/".$value["id"]."/examination_id/".$value["examination_id"]."/flag/flag";
				}
				D('Trigger')->messageTrigger($value2["user_id"], "你有待参加考试即将开始，信息如下", date("Y-m-d H:i:s"), 11, $value["create_user"], $seeUrl);
			}
		}
		
		//12 参与调研 你有待参与调研即将开始，信息如下
		//项目调研
		$where12["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where12["b.type"] = "0";
		$data12 = M("project_survey a")->field("a.project_id,a.survey_id,a.start_time,a.end_time,a.manager_id,b.user_id")
			->join("join __ADMIN_PROJECT__ b on a.project_id=b.id")->where($where12)->select();
		foreach ($data12 as $key=>$value){
			//通知到学员
			$students = M("designated_personnel")->where("project_id=".$value["project_id"])->select();
			foreach ($students as $key2=>$value2){
				$seeUrl = "admin/my_survey/joinsurvey/survey_id/".$value["survey_id"]."/project_id/".$value["project_id"]."/typeid/0";
				D('Trigger')->messageTrigger($value2["user_id"], "你有待参与调研即将开始，信息如下", date("Y-m-d H:i:s"), 12, $value["user_id"], $seeUrl);
			}
		}
		//系统调研
		$where121["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where121["a.audit_state"] = 1;
		$data121 = M("research a")->field("id,survey_id,create_user")->where($where121)->select();
		foreach ($data121 as $key=>$value){
			//通知到学员
			$tissue = M("research_tissueid")->where("research_id=".$value["id"])->select();
			foreach ($tissue as $thisTissue){
				$students = M("tissue_group_access")->where("tissue_id=".$thisTissue["tissue_id"])->select();
				foreach ($students as $key2=>$value2){
					$seeUrl = "admin/my_survey/joinsurvey/survey_id/".$value["survey_id"]."/research_id/".$value["id"]."/typeid/1";
					D('Trigger')->messageTrigger($value2["user_id"], "你有待参与调研即将开始，信息如下", date("Y-m-d H:i:s"), 12, $value["create_user"], $seeUrl);
				}
			}
		}
		
		//13 计划总结 你有已完成项目待写总结，信息如下
		$where13["a.start_time"] = array(array("egt", $startTime), array("lt", $endTime));
		$where13["a.type"] = "0";
		$data13 = M("admin_project a")->field("id,user_id")->where($where13)->select();
		foreach ($data13 as $key=>$value){
			$seeUrl = "admin/manage/detail/id/".$value["id"]."/typeid/4";
			D('Trigger')->messageTrigger($value["user_id"], "你有已完成项目待写总结，信息如下", date("Y-m-d H:i:s"), 13, $value["user_id"], $seeUrl);
		}
		
		$useTime = time() - $timestamp;
		
		/* $testData["user_id"] = date("Ymd");
		$testData["company_id"] = date("His"); */
		
		$testData["user_id"] = date("mdHis");
		$testData["company_id"] = $useTime;
		M("user_company")->add($testData);
	}
}
