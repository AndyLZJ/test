<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 个人中心_我的笔记
 * @author Dujuqiang 20170317
 */
class MyGoalModel extends BaseModel{
//	初始化
	public function __construct(){}
	
	//????不能从lecturer拿数量数据 相关时间进行转换
    public function getData(){
    	$year = date("Y");
    	$user_id = $_SESSION["user"]["id"];
    	$teachNum = 0;//目标授课次数
    	$teachTime = 0;//目标授课时长
    	$courseNum = 0;//目标课程开发数量

		$where['b.user_id'] = array("eq",$user_id);

		$toolTeach = M("tool_teaching a")
					->join("LEFT JOIN __LECTURER__ b ON a.lecturer_id = b.id")
					->field("a.*")
					->where($where)
					->select();

    	if($toolTeach){

			$map = $this->getTeaching($toolTeach);

    		$teachNum = $map[0]['val'];
    		$teachTime = $map[1]['val'];
    		$courseNum = $map[2]['val'];

			$typeid = array($map[0]['planid'],$map[1]['planid'],$map[2]['planid']);
    	}
    	
		//获取当前用户讲师ID
		$realTeachNum = 0;//实际授课次数
		$realTeachTime = 0;//实际授课时长
		$realCourseNum = 0;//实际课程开发数量
		$lecturer = M("lecturer")->where("user_id=".$user_id." AND is_delete='0'")->limit(1)->select();
		if($lecturer){
			//只获取面授课程，只有此类才有授课时长
			//$cWhere1["create_time"] = array("gt", strtotime($year."-01-01 00:00:00"));
			$cWhere1["lecturer"] = $lecturer[0]["id"];
			$cWhere1["status"] = 1;
			$cWhere1["course_way"] = 1;//0在线，1面授
			$publicCourse = M("course")->where($cWhere1)->select();
			foreach ($publicCourse as $key=>$value){
				//是否项目选择课程
				$project_course = M("project_course a")
					->field("a.project_id,a.start_time,a.end_time")
					->join("JOIN __ADMIN_PROJECT__ b ON a.project_id=b.id")
					->where("a.course_id=".$value["id"]." and (b.type=4 or b.type=0) and a.start_time>'".date("Y-01-01 00:00:00")."'")
					->select();
				//echo M("project_course a")->_sql();
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
				}
			}
			
			//获取课程开发数量按用户不按讲师
			$cWhere2["create_time"] = array("gt", strtotime("Y-01-01 00:00:00"));
			$cWhere2["user_id"] = $user_id;
			$cWhere2["status"] = 1;
			$makeCourse = M("course")->field("id")->where($cWhere2)->select();
			$realCourseNum = count($makeCourse);
		}
		
		//授课时长转化为分钟
		$realTeachTime = round(($realTeachTime / 60), 2);
		
		$rateTeachNum = 0;//授课次数比率
		$rateTeachTime = 0;//授课时长比率
		$rateCourseNum = 0;//课程开发数量比例
		if($teachNum > 0){
			$rateTeachNum = $realTeachNum / $teachNum;
			$rateTeachNum = round($rateTeachNum, 2) * 100;
		}
		
		if($teachTime > 0){
			$rateTeachTime = $realTeachTime / $teachTime;
			$rateTeachTime = round($rateTeachTime, 2) * 100;
		}
		
		if($courseNum > 0){
			$rateCourseNum = $realCourseNum / $courseNum;
			$rateCourseNum = round($rateCourseNum, 2) * 100;
		}
		
		$data["rateTeachNum"] = $rateTeachNum;
		$data["rateTeachTime"] = $rateTeachTime;
		$data["rateCourseNum"] = $rateCourseNum;
		
		$data["teachNum"] = $teachNum;
		$data["teachTime"] = $teachTime;
		$data["courseNum"] = $courseNum;
		
		$data["realTeachNum"] = $realTeachNum;
		$data["realTeachTime"] = $realTeachTime;
		$data["realCourseNum"] = $realCourseNum;
		$data['typeid'] = $typeid;
    	return $data;
    }


	/**
	 * 根据目标管理 授课目标
	 * 返回设置讲师参数
	 */
	public function getTeaching($data){

		$m = date("n",time());

		foreach($data as $k=>$item){
			//planid 1-年计划,2季计划,3-月计划
			//获取的年计划，所有月份数据相加，不用管年月计划
			$val = 0;
			$val += $item["january"] + $item["february"] + $item["march"] +$item["april"]+$item["may"]+$item["june"];
			$val += $item["july"]+$item["august"]+$item["september"]+$item["october"]+$item["november"]+$item["december"];
			$val = ceil($val);
			/*
			if($data['planid'] == 1){
				
			}else if($data['planid'] == 2){

				switch($m){
					case 1:
					case 2:
					case 3:
						$val = $item['january'];
						break;
					case 4:
					case 5:
					case 6:
						$val = $item['april'];
						break;
					case 7:
					case 8:
					case 9:
						$val = $item['july'];
						break;
					default:
						$val = $item['october'];
				}
			}else{

				switch($m){
					case 1:
						$val = $item['january'];
						break;
					case 2:
						$val = $item['february'];
						break;
					case 3:
						$val = $item['march'];
						break;
					case 4:
						$val = $item['april'];
						break;
					case 5:
						$val = $item['may'];
						break;
					case 6:
						$val = $item['june'];
						break;
					case 7:
						$val = $item['july'];
						break;
					case 8:
						$val = $item['august'];
						break;
					case 9:
						$val = $item['september'];
						break;
					case 10:
						$val = $item['october'];
						break;
					case 11:
						$val = $item['november'];
						break;
					default:
						$val = $item['december'];
				}
			}
			*/
			$teaching[$item['typeid']] = array("val"=>$val,"planid"=>$item['planid']);

		}

		return $teaching;

	}

	/**
	 * 课程开发情况
	 */
	public function course_data($total_page){

		$start = I("get.start");
		$end = I("get.end");
		$start_time = mktime(0,0,0,date("m",strtotime($start)),1,date("Y",strtotime($start)));
		$end_time = mktime(23,59,59,date("m",(strtotime($end)))+1,0,date("Y",strtotime($end)));

		$arrangement_id = I("get.arrangement_id");

		$keywords = I("get.keyword");

		$start_page = I("get.p",0,'int');

		if(!empty($keywords)){
			$where['a.course_name'] = array("like","%$keywords%");
		}

		if($arrangement_id >= 1){
			$where['a.arrangement_id'] = array("eq",$arrangement_id);
		}

		if(!empty($start) && !empty($end)){
			$where['a.create_time'] = array(array('egt',$start_time),array('elt',$end_time),'and');
		}elseif(!empty($start)){
			$where['a.create_time'] = array('egt',$start_time);
		}elseif(!empty($end)){
			$where['a.create_time'] = array('elt',$end_time);
		}

		$user_id = $_SESSION["user"]["id"];

		$where['a.user_id'] = array("eq",$user_id);

		$classid = I("get.classid");

		if($classid == 1){
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$results = M("course a")
					->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
					->field("a.id,a.course_name,a.course_time,a.arrangement_id,to_char(a.create_time,'YYYY-MM-DD HH24:MI:SS') as create_time,b.cat_name")
					->order("a.create_time desc")
					->where($where)
					->select();
			}else{
				$results = M("course a")
					->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
					->field("a.id,a.course_name,a.course_time,a.arrangement_id,a.create_time,b.cat_name")
					->order("a.create_time desc")
					->where($where)
					->select();
			}
		}else{
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$results = M("course a")
					->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
					->field("a.id,a.course_name,a.course_time,a.arrangement_id,to_char(a.create_time,'YYYY-MM-DD HH24:MI:SS') as create_time,b.cat_name")
					->order("a.create_time desc")
					->where($where)
					->page($start_page,$total_page)
					->select();
			}else{
				$results = M("course a")
					->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
					->field("a.id,a.course_name,a.course_time,a.arrangement_id,a.create_time,b.cat_name")
					->order("a.create_time desc")
					->where($where)
					->page($start_page,$total_page)
					->select();
			}
		}

		$count = M("course a")->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")->field("a.*,b.cat_name")->where($where)->count();
		
		//输出分页
		$show=$this->pageClass($count,$total_page);

		$course_list = array(
			'page' => $show,
			'list' => $results,
			'keyword'=>$keywords,
			'start_time'=>$start,
			'end_time'=>$end,
			'arrangement_id'=>$arrangement_id
		);

		return $course_list;

	}

	/**
	 *	授课情况
	 */
	public function teaching_data($total_page){

		$start = I("get.start");
		$end = I("get.end");
		$start_time = mktime(0,0,0,date("m",strtotime($start)),1,date("Y",strtotime($start)));
		$end_time = mktime(23,59,59,date("m",(strtotime($end)))+1,0,date("Y",strtotime($end)));

		$start_page = I("get.p",0,'int');

		$user_id = $_SESSION["user"]["id"];

		$where['user_id'] = array("eq",$user_id);
		$where['course_way'] = array("eq",1);

		//获取该用户添加课程ID
		$course_list = M("course")->field("id")->where($where)->select();

		$course_id = array(null);

		foreach($course_list as $list){
			$course_id[] = $list['id'];
		}

		//获取培训项目选中该用户课程数据
		$map['a.course_id'] = array("in",$course_id);

		//搜索条件
		$training_category_id = I("get.training_category_id");
		$tissue_id = I("get.tissue_id");

		if($training_category_id != "" and $training_category_id >=0){
			$map['b.training_category'] = array("eq",$training_category_id);
		}

		if(!empty($start) && !empty($end)){
			$map['a.start_time'] = array('egt',date("Y-m-d H:i:s",$start_time));
			$map['a.end_time'] = array('elt',date("Y-m-d H:i:s",$end_time));
		}elseif(!empty($start)){
			$map['a.start_time'] = array('egt',date("Y-m-d H:i:s",$start_time));
		}elseif(!empty($end)){
			$map['a.end_time'] = array('elt',date("Y-m-d H:i:s",$end_time));
		}

		if(!empty($keywords)){
			$map['b.project_name'] = array("like","%$keywords%");
		}

		if($tissue_id != "" and $tissue_id >=0){
			$map['b.user_id'] = array("eq",$tissue_id);
		}

		$keywords = I("get.keyword");

		if(!empty($keywords)){
			$so['b.project_name']  = array('like',"%$keywords%");
			$so['c.course_name']  = array('like',"%$keywords%");
			$so['_logic'] = 'or';
			$map['_complex'] = $so;
		}

		$classid = I("get.classid");

		if($classid == 1){
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$results = M("project_course a")
					->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __COURSE__ c ON a.course_id = c.id")
					->field("a.id,a.project_id,a.course_id,a.course_names,b.class_name,b.user_id,b.project_name,b.training_category,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.project_description,c.course_name,c.course_time")
					->where($map)
					->select();
			}else{
				$results = M("project_course a")
					->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __COURSE__ c ON a.course_id = c.id")
					->field("a.id,a.project_id,a.course_id,a.course_names,b.class_name,b.user_id,b.project_name,b.training_category,a.start_time,a.end_time,b.project_description,c.course_name,c.course_time")
					->where($map)
					->select();
			}
		}else{
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$results = M("project_course a")
					->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __COURSE__ c ON a.course_id = c.id")
					->field("a.id,a.project_id,a.course_id,a.course_names,b.class_name,b.user_id,b.project_name,b.training_category,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.project_description,c.course_name,c.course_time")
					->where($map)
					->page($start_page,$total_page)
					->select();
			}else{
				$results = M("project_course a")
					->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __COURSE__ c ON a.course_id = c.id")
					->field("a.id,a.project_id,a.course_id,a.course_names,b.class_name,b.user_id,b.project_name,b.training_category,a.start_time,a.end_time,b.project_description,c.course_name,c.course_time")
					->where($map)
					->page($start_page,$total_page)
					->select();
			}
		}



		//输出分页
		$count = M("project_course a")
				->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __COURSE__ c ON a.course_id = c.id")
				->where($map)
				->count();
		$show = $this->pageClass($count,$total_page);

		$rows = array();

		foreach($results as $k=>$list){

			$rows[$k] = $list;

			//获取主办部门数据
			$rows[$k]['tissue_name'] = M("tissue_group_access a")
					->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
					->where("user_id=".$list['user_id'])
					->getField("b.name");

			//获取授课课时
			$rows[$k]['course_name'] = $list['course_names'] ? $list['course_names'] : $list['course_name'];

			//授课满意度
			$course_score = M("course_score")->where("course_id=".$list['course_id'])->sum('course_score');

			$people_number = M("course_score")->where("course_id=".$list['course_id'])->count();

			if($course_score > 0){

				$satisfaction = ( $course_score / (5 * $people_number)) * 100;
			}else{
				$satisfaction = 0;

			}

			$rows[$k]['percentage'] = $satisfaction;

		}


		//获取所有主办部门数据
		$search['a.course_id'] = array("in",$course_id);

		$user_id_all = M("project_course a")
				->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id")
				->field("b.user_id")
				->group("b.user_id")
				->where($search)
				->select();

		$user_arr = array(null);

		foreach($user_id_all as $user_id){
			$user_arr[] = $user_id['user_id'];
		}

		$where = array();
		$where['user_id'] = array("in",$user_arr);

		//获取主办部门数据
		$department_all = M("tissue_group_access a")
				->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
				->field("a.user_id,b.name")
				->where($where)
				->select();


		$course_list = array(
			'page' => $show,
			'list' => $rows,
			'keyword'=>$keywords,
			'start_time'=>$start,
			'end_time'=>$end,
			'department_all'=>$department_all,
			'tissue_id'=>$tissue_id,
			'training_category_id'=>$training_category_id,
		);

		return $course_list;


	}

	/**
	 * 全部显示
	 */
	public function all_data(){

		$start = I("get.start");
		$end = I("get.end");
		$start_time = mktime(0,0,0,date("m",strtotime($start)),1,date("Y",strtotime($start)));
		$end_time = mktime(23,59,59,date("m",(strtotime($end)))+1,0,date("Y",strtotime($end)));

		if(!empty($start) && !empty($end)){
			$where['create_time'] = array(array('egt',$start_time),array('elt',$end_time),'and');
		}elseif(!empty($start)){
			$where['create_time'] = array('egt',$start_time);
		}elseif(!empty($end)){
			$where['create_time'] = array('elt',$end_time);
		}

		$user_id = $_SESSION["user"]["id"];

		$where['user_id'] = array("eq",$user_id);
		$where['course_way'] = array("eq",1);

		//查询用户资料
		$tissue_name = M("tissue_group_access a")
					->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
					->where("a.user_id=".$user_id)
					->getField("b.name");

		//获取课程开发门数
		$course_num = M("course")->where($where)->count();

		//获取开发时数
		$course_total = M("course")->where($where)->sum('course_time');

		//获取该用户添加课程ID
		$course_list = M("course")->field("id")->where($where)->select();

		$course_id = array(null);

		foreach($course_list as $list){
			$course_id[] = $list['id'];
		}

		//获取培训项目选中该用户课程数据
		$map['course_id'] = array("in",$course_id);

		//授课次数
		$so['a.course_id'] = array("in",$course_id);
		$so['b.course_way'] = array("eq",1);
		$teaching_num = M("project_course a")
					->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id")
					->where($so)
					->count();

		//授课课时
		$condition['a.course_id'] = array("in",$course_id);
		$condition['b.course_way'] = array("eq",1);
		$teaching_hour = M("project_course a")
				->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id")
				->where($condition)
				->sum('b.course_time');

		//授课满意度
		$course_score = M("course_score")->where($map)->sum('course_score');

		$people_number = M("course_score")->where($map)->count();

		if($course_score > 0){

			$satisfaction = ( $course_score / (5 * $people_number)) * 100;
		}else{
			$satisfaction = 0;
		}

		$rows = array(
			'k'=>1,
			'username'=>$_SESSION['user']['username'],
			'tissue_name'=>$tissue_name,
			'teaching_num'=>$teaching_num,
			'teaching_hour'=>$teaching_hour,
			'satisfaction'=>$satisfaction,
			'course_num'=>$course_num,
			'course_total'=>$course_total,
			'start'=>$start,
			'end'=>$end
		);

		return $rows;

	}


}