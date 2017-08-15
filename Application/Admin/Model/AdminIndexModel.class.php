<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AdminIndexModel extends BaseModel{

	//初始化
	public function __construct(){}

	public function indexLecturer(){

		//获取当前用户讲师ID
		$lecturer = M("lecturer")->field('id')->where("user_id=".$_SESSION["user"]["id"]." AND is_delete='0'")->find();

		if(empty($lecturer['id'])){
			$lecturer['id'] = 0;
		}
		//正在进行的项目
		$where['b.lecturer'] = array('eq',$lecturer['id']);

		$where['c.type'] = array("in",array(0,4));

		$where['a.end_time'] = array("lt",date("Y-m-d H:i:s",time()));

		//获取当前用户讲师ID
		$realTeachNum = 0;//实际授课次数
		$realTeachTime = 0;//实际授课时长
		$realCourseNum = 0;//实际课程开发数量
		$user_num = 0; //学习用户数量

		$teaching_total = 0;

		$course_total = 0;

		//统计授课时长
		$week_teaching = array();

		$study_items = array();

		$curriculum_data = array();

		//只获取面授课程，只有此类才有授课时长
			//$cWhere1["create_time"] = array("gt", strtotime($year."-01-01 00:00:00"));
			$cWhere1["lecturer"] = $lecturer['id'];
			$cWhere1["status"] = 1;
			//$cWhere1["course_way"] = 1;//0在线，1面授

			$publicCourse = M("course")->where($cWhere1)->select();

			foreach ($publicCourse as $key=>$value){
				//是否项目选择课程

				$project_course = M("project_course a")->field("a.project_id,a.start_time,a.end_time")
					->join("JOIN __ADMIN_PROJECT__ b ON a.project_id=b.id")
					->where("a.course_id=".$value["id"]." and (b.type=4 or b.type=0) and a.start_time>'".date("Y-01-01 00:00:00")."'")->select();

				foreach ($project_course as $k2=>$v2){
					if(strtotime($v2["end_time"]) > time()){
						continue;
					}

					$realTeachNum ++;

					$diffTime = $value["course_time"];
					if($diffTime < 0){
						$diffTime = 0;
					}
					$realTeachTime += $diffTime;

					$condition['project_id'] = array("eq",$v2['project_id']);
					$condition['course_id'] = array("eq",$value["id"]);

					$course_chapter = M("course_chapter")->where($condition)->select();

					$user_num +=count($course_chapter);

					foreach($course_chapter as $chapter){

						$Study = $this->Statistics(strtotime($chapter['create_time']),strtotime($chapter['create_time']),$value["course_time"],2);

						for($i=7;$i>0;$i--){

							$study_items[$i] += $Study[$i];

						}

					}

				}

				//授课时长
				$Statistics = $this->Statistics($value['create_time'],$value['create_time'],$value["course_time"],1);

				for($i=7;$i>0;$i--){
					$week_teaching[$i] += $Statistics[$i];
				}

				//统计课件数量
				$chapter_num = count(json_decode($value['chapter'],true));

				$course_total += $chapter_num;

				$curriculum = $this->Statistics($value['create_time'],$value['create_time'],$chapter_num,3);

				//课件数量
				for($i=7;$i>0;$i--){
					$curriculum_data[$i] += $curriculum[$i];
				}

			}

			$week_data = $this->data_statistics($week_teaching,1);


			$study_data = $this->data_statistics($study_items,1);


			//学员时长
			$duration_total = ($realTeachTime * $user_num);

			//授课时长转化为分钟
			$realTeachTime = ceil($realTeachTime / 60);

		if(strtolower(C('DB_TYPE')) == 'oracle'){

			$startTime = date("Y-m-d H:i:s",time());

			$map = " where b.lecturer = ".$lecturer['id']." and c.type in(0,4) and a.end_time < to_date('$startTime','yyyy-mm-dd hh24:mi:ss')";

			$query = "SELECT b.create_time,b.course_time,b.course_name,a.project_id,a.course_id,a.start_time,a.end_time,(select project_name from think_admin_project c where c.id = a.project_id) as project_name,c.type FROM __PROJECT_COURSE__ a LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __ADMIN_PROJECT__ c ON a.project_id = c.id".$map;

			$course_data = M()->query($query);

		}else{

			$course_data = M("project_course a")->field('b.create_time,b.course_time,b.course_name,a.project_id,a.course_id,a.start_time,a.end_time,(select project_name from think_admin_project c where c.id = a.project_id) as project_name,c.type')->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __ADMIN_PROJECT__ c ON a.project_id = c.id")->where($where)->select();

		}

		$curriculum_data = $this->data_statistics($curriculum_data,2);
		
		//课程数量
		$curriculum_total = M("course")->where("lecturer=".$lecturer['id']." and status = 1")->count();

		$data = array(
			'course_data'=>array_slice($course_data,0,5),
			'course_total'=>$course_total,
			'curriculums_total'=>$curriculum_total,
			'duration_total'=>$duration_total,
			'teaching_total'=>$realTeachTime,
			'week'=>implode(",",$week_data['number']),
			"month"=>implode(",",$week_data['month']),
			"week_total"=>$week_data['number_total'],
			"study"=>implode(",",$study_data['number']),
			"study_month"=>implode(",",$study_data['month']),
			"study_total"=>$study_data['number_total'],
			"curriculum"=>implode(",",$curriculum_data['number']),
			"curriculum_month"=>implode(",",$curriculum_data['month']),
			"curriculum_totals"=>$curriculum_data['number_total'],
		);

		return $data;


	}

	/**
	 * 一周数据统计
	 */
	public function Statistics($start_time,$end_time,$timeLen = '',$typeid = 1){

		$data = array();

		for($i=7;$i>0;$i--){

			$DateWeek = $this->DateWeek($i);

			if(($DateWeek['start_time'] <= $start_time) && $DateWeek['end_time'] >= $end_time){

				$data[$i] = $timeLen;

			}else{

				$data[$i] = 0;
			}
		}

		return $data;

	}

	/**
	 * 获取一周时间
	 */
	public function DateWeek($i){

		$start_time = strtotime(date("Y-m-d 00:00:00",time())) - (86400 * $i);

		$end_time = strtotime(date("Y-m-d 23:59:59",time()))  - (86400 * $i);

		$date = array(
			"start_time"=>$start_time,
			"end_time"=>$end_time,
		);

		return $date;
	}

	/**
	 * 统计公共函数
	 */
	public function data_statistics($items,$typeid = 1){

		$total = array_sum($items);

		if($typeid == 1){

			foreach($items as $k=>$item){

				$DateWeek = $this->DateWeek($k);

				if(!empty($item)){
					$number[$k] = $item;
				}else{
					$number[$k]= 0;
				}

				$month[$k] = "'".date("n月d号",$DateWeek['start_time'])."'";
			}

			$number_total = $total;

		}else{

			foreach($items as $k=>$item){

				$DateWeek = $this->DateWeek($k);

				if(!empty($item)){
					$number[$k] = $item;
				}else{
					$number[$k]= 0;
				}

				$month[$k] = "'".date("n月d号",$DateWeek['start_time'])."'";
			}

			$number_total = array_sum($number);

		}

		$data = array(
			'number'=>$number,
			'number_total'=>$number_total,
			'month'=>$month,
		);

		return $data;


	}

}
