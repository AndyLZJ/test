<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 首页-管理员
 * @author Dujuqiang 20170328
 */
class IndexAdminModel extends BaseModel{
	//初始化
	public function __construct(){}

	/**
	 * 未处理事项
	 */
	public function undo(){
		$user_id = $_SESSION["user"]["id"];
		
	}
	
	/**
	 * 通知
	 * 1、消息分为全部消息、未读消息、已读消息
		全部消息
		全部消息按照时间倒叙排序、最新的消息放在最上面
		未读消息
		未读消息显示未读消息数量，按照时间排序，最新的消息排最上面
		也可单条忽略或者全部忽略未读消息
		已读消息
		已读消息按照时间排序，最新的消息排在最上面
		2、消息列表显示
		消息列表显示任务类型、标题、消息状态、来源
		任务类型：
			课程制作、试卷制作、问卷制作、授课任务、成绩发布、调研结果、审批任务、统计调研、签到提醒、课程学习、参加考试、参与调研、计划总结
		标题：
		【任务类型】你有新的课程学习：课程名称
		【任务类型】你有新的调研任务：调研名称
		查看消息内容
		点击消息标题，进入消息详情页→详细的消息标题、时间、去查看（页面跳转）→页面跳转—对应的消息任务页面
	 */
	public function notice($id){
		$user_id = $_SESSION["user"]["id"];
		$conditon = "";
		if($id){
			$conditon = " AND a.id=".$id;
		}
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.user_id,a.title,a.type_id,a.from_id,a.status,a.is_delete,a.url,";
			$field.= "to_char(a.contents_time,'YYYY-MM-DD HH24:MI:SS') as contents_time,";
			$field.= "to_char(a.newstime,'YYYY-MM-DD HH24:MI:SS') as newstime,";
		}else{
			$field = "a.*,";
		}
		$data = M("admin_message a")
				->field($field . "b.type_name,b.cat_detail")
				->join("JOIN __ADMIN_MESSAGE_TYPE__ b ON a.type_id=b.id")
				->where("user_id=".$user_id." AND is_delete=0 AND status=0".$conditon)
				->order("newstime DESC")
				->select();
		$total = count($data);
		foreach ($data as $key=>$value){
			$data[$key]["page"] = ($key+1)."/".$total;
		}
		return $data;
	}
	
	//标记为已读
	public function readNotice($id){
		$user_id = $_SESSION["user"]["id"];
		$data["status"] = 1;
		M("admin_message")->where("user_id=".$user_id." AND id=".$id)->limit(1)->save($data);
	}
	
	/**
	 * 进行中项目
	 */
	public function active(){
		$user_id = $_SESSION["user"]["id"];
		$list = M("admin_project")->field("id as project_id,project_name")->where("type=0 and user_id=".$user_id)->order("start_time DESC")->limit(10)->select();
		foreach ($list as $key=>$value){
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$sql1 = "SELECT '课程' title,1 sub_type,project_id,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,course_names as sub_name,course_id as sub_id FROM __PROJECT_COURSE__ WHERE project_id=". $value["project_id"];
				$sql2 = "SELECT '考试' title,2 sub_type,project_id,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,test_names as sub_name,test_id as sub_id FROM __PROJECT_EXAMINATION__ WHERE project_id=". $value["project_id"];
				$sql3 = "SELECT '问卷' title,3 sub_type,project_id,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,survey_names as sub_name,survey_id as sub_id FROM __PROJECT_SURVEY__ WHERE project_id=". $value["project_id"];
			}else{
				$sql1 = "SELECT '课程' title,1 sub_type,project_id,start_time,end_time,course_names as sub_name,course_id as sub_id FROM __PROJECT_COURSE__ WHERE project_id=". $value["project_id"];
				$sql2 = "SELECT '考试' title,2 sub_type,project_id,start_time,end_time,test_names as sub_name,test_id as sub_id FROM __PROJECT_EXAMINATION__ WHERE project_id=". $value["project_id"];
				$sql3 = "SELECT '问卷' title,3 sub_type,project_id,start_time,end_time,survey_names as sub_name,survey_id as sub_id FROM __PROJECT_SURVEY__ WHERE project_id=". $value["project_id"];
			}
			
			$sql = $sql1." UNION ".$sql2." UNION ".$sql3." ORDER BY start_time DESC";
			
			$subList = M()->query($sql);
			$list[$key]["title"] = $subList[0]["title"];
			$list[$key]["sub_type"] = $subList[0]["sub_type"];
			$list[$key]["start_time"] = $subList[0]["start_time"];
			$list[$key]["sub_name"] = $subList[0]["sub_name"];
			$list[$key]["sub_id"] = $subList[0]["sub_id"];
			if(!$list[$key]["sub_name"]){
				if($subList[0]["sub_type"] == 1){
					$course = M("course")->field("course_name")->where("id=".$subList[0]["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["course_name"];
				}elseif($subList[0]["sub_type"] == 2){
					$course = M("examination")->field("test_name")->where("id=".$subList[0]["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["test_name"];
				}elseif($subList[0]["sub_type"] == 3){
					$course = M("survey")->field("survey_name")->where("id=".$subList[0]["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["survey_name"];
				}
			}
			//对比当前数据判断已完成
			$total = count($subList);
			$endNum = 0;
			foreach ($subList as $sk=>$sv){
				if(strtotime($sv["end_time"]) < time()){
					$endNum ++;
				}
			}
			$list[$key]["ev"] = $endNum ." / ".$total;
		}
		return array("list"=>$list);
	}
	
	/**
	 * 结束的项目
	 */
	public function actived(){
		$user_id = $_SESSION["user"]["id"];
		
		$sql1 = "SELECT '课程' title,1 sub_type,project_id,start_time,end_time,course_names as sub_name,course_id as sub_id FROM __PROJECT_COURSE__ ";
		$sql2 = "SELECT '考试' title,2 sub_type,project_id,start_time,end_time,test_names as sub_name,test_id as sub_id FROM __PROJECT_EXAMINATION__ ";
		$sql3 = "SELECT '问卷' title,3 sub_type,project_id,start_time,end_time,survey_names as sub_name,survey_id as sub_id FROM __PROJECT_SURVEY__ ";
		
		$sql = "SELECT tmp_table.*,admin_pro.project_name,admin_pro.population,admin_pro.is_public FROM (".$sql1." UNION ".$sql2." UNION ".$sql3.") tmp_table 
			JOIN __ADMIN_PROJECT__ admin_pro ON tmp_table.project_id=admin_pro.id WHERE admin_pro.type=4 and admin_pro.user_id=$user_id ORDER BY tmp_table.end_time DESC";
		
		$list = M()->query($sql);
		
		$list = array_slice($list,0,10);
		foreach($list as $key=>$value){
			if(!$value["sub_id"]) continue;
			if(!$value["sub_name"]){
				if($value["sub_type"] == 1){
					$course = M("course")->field("course_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["course_name"];
				}elseif($value["sub_type"] == 2){
					$course = M("examination")->field("test_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["test_name"];
				}elseif($value["sub_type"] == 3){
					$course = M("survey")->field("survey_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$list[$key]["sub_name"] = $course[0]["survey_name"];
				}
			}
			
			//获取考勤
			$planNum = M("designated_personnel")
					->field("COUNT(id) AS num")
					->where("project_id=".$value["project_id"])
					->select();
			$list[$key]["plan_num"] = $planNum[0]["num"];//计划人数
			if($value["sub_type"] == 1){
				//course_way 授授课方式:0在线，1面授
				$course = M("course")->field("course_way")->where("id=".$value["sub_id"])->limit(1)->select();
				if($course[0]["course_way"] == 1){
					$realNum = M("attendance")->field("COUNT(id) AS num")->where("pid=".$value["project_id"]." AND course_id=".$value["sub_id"])->select();
					$list[$key]["real_num"] = $realNum[0]["num"];//实际人数
				}else{
					$realNum = M("course_chapter")->field("DISTINCT(user_id)")->where("project_id=".$value["project_id"]." AND course_id=".$value["sub_id"])->select();
					$list[$key]["real_num"] = count($realNum);//实际人数
				}
			}elseif($value["sub_type"] == 2){
				//考试
				$realNum = M("examination_attendance")->field("COUNT(id) AS num")->where("project_id=".$value["project_id"]." AND test_id=".$value["sub_id"]." AND status=1")->select();
				$list[$key]["real_num"] = $realNum[0]["num"];//实际人数
			}elseif($value["sub_type"] == 3){
				//问卷
				$realNum = M("survey_answer")->field("DISTINCT(u_survey_id)")->where("project_id=".$value["project_id"]." AND survey_id=".$value["sub_id"])->select();
				$list[$key]["real_num"] = count($realNum);//实际人数
			}
		}
		return array("list"=>$list);
	}
	
	/**
	 * 授课信息统计 所有的
	 * 显示最近四个月的培训人数及课程数量（柱状图）
	 */
	public function teach(){
		$startMonth = date("Y-m-01",strtotime("-4 month", strtotime(date("Y-m-01"))));
		$timeStr = "";
		$courseStr = "";
		$joinStr = "";
		for($i=0; $i<5; $i++){
			$month = date("Y-m",strtotime($i." month", strtotime($startMonth)));
			$course = M("project_course a")
					->field("project_id,course_id,a.start_time")
					->join("JOIN __ADMIN_PROJECT__ b ON a.project_id=b.id")
					->where("(b.type=0 OR b.type=4) AND a.start_time LIKE '".$month."%'")
					->select();
			
			$joinUser = 0;
			foreach ($course as $key=>$value){
				//course_way 授授课方式:0在线，1面授
				$course = M("course")->field("course_way")->where("id=".$value["course_id"])->limit(1)->select();
				if($course[0]["course_way"] == 1){
					$realNum = M("attendance")
							->field("COUNT(id) AS num")
							->where("pid=".$value["project_id"]." AND course_id=".$value["course_id"])
							->select();
					$joinUser += $realNum[0]["num"];//实际人数
				}else{
					$realNum = M("course_chapter")
							->field("DISTINCT(user_id)")
							->where("project_id=".$value["project_id"]." AND course_id=".$value["course_id"])
							->select();
					$joinUser += count($realNum);//实际人数
				}
			}
			
			$timeStr .= "'".$month."',";
			$courseStr .= count($course).",";
			$joinStr .= $joinUser.",";
		}
		$timeStr = substr($timeStr, 0, -1);
		$courseStr = substr($courseStr, 0, -1);
		$joinStr = substr($joinStr, 0, -1);
		
		$return = array("timeStr"=>$timeStr, "courseStr"=>$courseStr, "joinStr"=>$joinStr);
		return $return;
	}
	
	/**
	 * 新闻资讯
	 * 显示资讯标题及发布时间，点击标题进入资讯详情页
	 * 点击更多跳转至资讯列表页，点击标题进入资讯详情页
	 */
	public function news(){
		$user_id = $_SESSION["user"]["id"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$news = M("news")
					->field("id,title,type,to_char(create_time,'YYYY-MM-DD HH24:MI:SS') as create_time")
					->order("create_time DESC")
					->limit(5)
					->select();
		}else{
			$news = M("news")->field("id,title,type,create_time")->order("create_time DESC")->limit(5)->select();
		}
		if($news){
		for ($i=0; $i<5; $i++){
			if(!$news[$i]){
					break;
				}
			$news[$i]["title"] = str_replace("&nbsp;", "", $news[$i]["title"]);
			$news[$i]["type"] = "资讯";
			if($news[$i]["type"] == 1){
				$news[$i]["type"] = "要闻";
			}elseif($news[$i]["type"] == 2){
				$news[$i]["type"] = "培训";
			}elseif($news[$i]["type"] == 3){
				$news[$i]["type"] = "通知";
			}elseif($news[$i]["type"] == 4){
				$news[$i]["type"] = "活动";
			}
		}
	}
		return $news;
	}
	
	/**
	 * 日程提醒（上一月 当前月 下一月）数据
	 * 当天有任务的，有圆点标注提醒；显示任务事项及任务时间
	 */
	public function schedule(){
		$user_id = $_SESSION["user"]["id"];
		$startTime = date("Y-m-01 00:00:00",strtotime("-6 month", strtotime(date("Y-m-01"))));
		$endTime = date("Y-m-d H:i:s",strtotime("2 month", strtotime(date("Y-m-01 00:00:00"))) - 1);
		
		$sql1 = 'SELECT manager_id,1 sub_type,start_time,end_time,project_id FROM __PROJECT_COURSE__ ';
		$sql2 = 'SELECT manager_id,2 sub_type,start_time,end_time,project_id FROM __PROJECT_EXAMINATION__ ';
		$sql3 = 'SELECT manager_id,3 sub_type,start_time,end_time,project_id FROM __PROJECT_SURVEY__ ';
		
		$sql = "SELECT tmp_table.* FROM (".$sql1." UNION ".$sql2." UNION ".$sql3.") tmp_table 
			JOIN __ADMIN_PROJECT__ admin_pro ON tmp_table.project_id=admin_pro.id 
			WHERE tmp_table.manager_id=$user_id AND (admin_pro.type=0 OR admin_pro.type=4) ";

		if(strtolower(C('DB_TYPE')) == 'oracle'){

			$sql .="AND tmp_table.start_time>to_date('$startTime','yyyy-mm-dd hh24:mi:ss') AND tmp_table.start_time<to_date('$endTime','yyyy-mm-dd hh24:mi:ss') ORDER BY tmp_table.start_time ASC";

		}else{
			$sql .="AND tmp_table.start_time>'$startTime' AND tmp_table.start_time<'$endTime' ORDER BY tmp_table.start_time ASC";
		}

		$project = M()->query($sql);
		
		//日期去重--年月日时间戳作为key,保证每天唯一
		$schedule = array();
		foreach ($project as $value){
			$key = strtotime(date("Y-m-d 00:00:00",strtotime($value["start_time"])));
			$schedule[$key] = date("Y-m-d",strtotime($value["start_time"]));
		}
		//审核任务
		//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		$typeCondition1 = " type='2'";
		$typeCondition2 = " status='0'";
		$typeCondition3 = " status='0'";
		$typeCondition4 = " status='0'";
		$typeCondition5 = " status='0'";
		$typeCondition6 = " audit_state='0'";
		$typeCondition7 = " audit_status='1'";
		$typeCondition8 = " status='2'";
		$typeCondition9 = " status='0'";
		//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		//1培训项目
		$table1 = "SELECT 1 task_type,'培训项目审核' task_desc,id as task_id,add_time as create_time
		FROM __ADMIN_PROJECT__ WHERE $typeCondition1";
		
		//2新建课程
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$table2 = "SELECT 2 task_type,'新建课程审核' task_desc,id as task_id,TO_DATE(TO_CHAR(create_time / ( 60 * 60 * 24) + TO_DATE('1970-01-01 08:00:00', 'YYYY-MM-DD HH:MI:SS'), 'YYYY-MM-DD HH:MI:SS'),'yyyy-mm-dd hh24:mi:ss') AS create_time FROM __COURSE__ WHERE $typeCondition2";
		}else{
			$table2 = "SELECT 2 task_type,'新建课程审核' task_desc,id as task_id,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s') FROM __COURSE__ WHERE $typeCondition2";
		}
		//3新建试卷
		$table3 = "SELECT 3 task_type,'新建试卷审核' task_desc,id as task_id,test_upload_time as create_time
		FROM __EXAMINATION__ WHERE $typeCondition3";
		
		//4新建问卷
		$table4 = "SELECT 4 task_type,'新建问卷审核' task_desc,id as task_id,survey_upload_time as create_time
		FROM __SURVEY__ WHERE $typeCondition4";
		
		//5 新建话题（工作圈内容发布）
		$table5 = "SELECT 5 task_type,'工作圈内容发布审核' task_desc,id as task_id,publish_time as create_time
		FROM __FRIENDS_CIRCLE__ WHERE $typeCondition5";
		
		//6发起调研
		$table6 = "SELECT 6 task_type,'发起调研审核' task_desc,id as task_id,create_time
		FROM __RESEARCH__ WHERE $typeCondition6";
		
		//7发起考试
		$table7 = "SELECT 7 task_type,'发起考试审核' task_desc,id as task_id,create_time
		FROM __TEST__ WHERE $typeCondition7";
		
		//8用户注册
		$table8 = "SELECT 8 task_type,'用户注册审核' task_desc,id as task_id,register_time as create_time
		FROM __USERS__ WHERE $typeCondition8";
		
		//9申请积分加分
		if(strtolower(C('DB_TYPE')) == 'oracle') {
			$table9 = "SELECT 9 task_type,'申请积分加分审核' task_desc,id as task_id,TO_DATE(TO_CHAR(add_time / ( 60 * 60 * 24) + TO_DATE('1970-01-01 08:00:00', 'YYYY-MM-DD HH:MI:SS'), 'YYYY-MM-DD HH:MI:SS'),'yyyy-mm-dd hh24:mi:ss') AS create_time FROM __INTEGRATION_APPLY__ WHERE $typeCondition9";
		}else{
			$table9 = "SELECT 9 task_type,'申请积分加分审核' task_desc,id as task_id,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as create_time
		FROM __INTEGRATION_APPLY__ WHERE $typeCondition9";
		}

		
		$sql = $table1." UNION ".$table2." UNION ".$table3." UNION ".$table4." UNION ".$table5." UNION ".$table6." UNION ".$table7." UNION ".$table8." UNION ".$table9;

		if(strtolower(C('DB_TYPE')) == 'oracle'){

			$sqls = "SELECT * FROM ($sql) tmp_table WHERE create_time > to_date('$startTime','yyyy-mm-dd hh24:mi:ss')";

		}else{

			$sqls = "SELECT * FROM ($sql) AS tmp_table WHERE create_time>'$startTime'";

		}

		$task = M()->query($sqls);

		//日期去重--年月日时间戳作为key,保证每天唯一
		$schedule = array();
		foreach ($task as $value){
			$key = strtotime(date("Y-m-d 00:00:00",strtotime($value["create_time"])));
			$schedule[$key] = date("Y-m-d",strtotime($value["create_time"]));
		}
		return array("schedule"=>$schedule, "undoTask"=>count($task));
	}
	
	/**
	 * 当天日程列表
	 */
	public function dayTask($param){
		$user_id = $_SESSION["user"]["id"];
		//项目任务
		$sql1 = "SELECT manager_id,'课程' title,1 sub_type,project_id,start_time,end_time,course_names as sub_name,course_id as sub_id FROM __PROJECT_COURSE__ ";
		$sql2 = "SELECT manager_id,'考试' title,2 sub_type,project_id,start_time,end_time,test_names as sub_name,test_id as sub_id FROM __PROJECT_EXAMINATION__ ";
		$sql3 = "SELECT manager_id,'问卷' title,3 sub_type,project_id,start_time,end_time,survey_names as sub_name,survey_id as sub_id FROM __PROJECT_SURVEY__ ";
		$sql = "SELECT tmp_table.*,admin_pro.project_name,admin_pro.population,admin_pro.is_public FROM (".$sql1." UNION ".$sql2." UNION ".$sql3.") tmp_table
		JOIN __ADMIN_PROJECT__ admin_pro ON tmp_table.project_id=admin_pro.id
		WHERE tmp_table.manager_id=$user_id AND (admin_pro.type=0 OR admin_pro.type=4) AND tmp_table.start_time LIKE '".$param["chooseDay"]."%' ORDER BY tmp_table.start_time ASC";
		$project = M()->query($sql);
		
		//审核任务
		//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		$typeCondition1 = " type='2'";
		$typeCondition2 = " status='0'";
		$typeCondition3 = " status='0'";
		$typeCondition4 = " status='0'";
		$typeCondition5 = " status='0'";
		$typeCondition6 = " audit_state='0'";
		$typeCondition7 = " audit_status='1'";
		$typeCondition8 = " status='2'";
		$typeCondition9 = " status='0'";
		//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		//1培训项目
		$table1 = "SELECT 1 task_type,'培训项目审核' task_desc,id as task_id,add_time as create_time
		FROM __ADMIN_PROJECT__ WHERE $typeCondition1";
		
		//2新建课程
		$table2 = "SELECT 2 task_type,'新建课程审核' task_desc,id as task_id,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s')
		FROM __COURSE__ WHERE $typeCondition2";
		
		//3新建试卷
		$table3 = "SELECT 3 task_type,'新建试卷审核' task_desc,id as task_id,test_upload_time as create_time
		FROM __EXAMINATION__ WHERE $typeCondition3";
		
		//4新建问卷
		$table4 = "SELECT 4 task_type,'新建问卷审核' task_desc,id as task_id,survey_upload_time as create_time
		FROM __SURVEY__ WHERE $typeCondition4";
		
		//5 新建话题（工作圈内容发布）
		$table5 = "SELECT 5 task_type,'工作圈内容发布审核' task_desc,id as task_id,publish_time as create_time
		FROM __FRIENDS_CIRCLE__ WHERE $typeCondition5";
		
		//6发起调研
		$table6 = "SELECT 6 task_type,'发起调研审核' task_desc,id as task_id,create_time
		FROM __RESEARCH__ WHERE $typeCondition6";
		
		//7发起考试
		$table7 = "SELECT 7 task_type,'发起考试审核' task_desc,id as task_id,create_time
		FROM __TEST__ WHERE $typeCondition7";
		
		//8用户注册
		$table8 = "SELECT 8 task_type,'用户注册审核' task_desc,id as task_id,register_time as create_time
		FROM __USERS__ WHERE $typeCondition8";
		
		//9申请积分加分
		$table9 = "SELECT 9 task_type,'申请积分加分审核' task_desc,id as task_id,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as create_time
		FROM __INTEGRATION_APPLY__ WHERE $typeCondition9";
		
		$sql = $table1." UNION ".$table2." UNION ".$table3." UNION ".$table4." UNION ".$table5." UNION ".$table6." UNION ".$table7." UNION ".$table8." UNION ".$table9;
		$sql = "SELECT * FROM ($sql) tmp_table WHERE create_time LIKE '".$param["chooseDay"]."%'";
		$task = M()->query($sql);
		$list = array();
		$key = 0;
		foreach ($project as $value){
			if(!$value["sub_name"]){
				if($value["sub_type"] == 1){
					$course = M("course")->field("course_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$value["sub_name"] = $course[0]["course_name"];
				}elseif($value["sub_type"] == 2){
					$course = M("examination")->field("test_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$value["sub_name"] = $course[0]["test_name"];
				}else{
					$course = M("survey")->field("survey_name")->where("id=".$value["sub_id"])->limit(1)->select();
					$value["sub_name"] = $course[0]["survey_name"];
				}
			}
			
			//
			$list[$key] = $value;
			$list[$key]["dataType"] = 1;
			$key ++;
		}
		foreach ($task as $value){
			$list[$key] = $value;
			$list[$key]["dataType"] = 2;
			$key ++;
		}
		$list = array_chunk($list, 5);
		return array("code"=>1000, "message"=>"成功", "list"=>$list, "task"=>$task);
	}
}