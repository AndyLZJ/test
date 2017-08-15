<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * 用户model
 */
class StudentModel extends BaseModel{
    protected $tableName = 'test';
    
    public function getStudentInfo(){
        $user_id = session('user.id');
        
        $credit = M('center_study')->where(array('user_id'=>$user_id))->sum('credit');//学分
        $hours = M('center_study')->where(array('user_id'=>$user_id))->sum('hours');//学时
        $hours = number_format($hours / 60 ,2);
        // $courseInfo = $this->getUnFinishedCourse($user_id);     //课程信息
        $testInfo = $this->getUnFinishedExam($user_id);         //考试信息

        $course = $this->getCourse($user_id);

        //公开课课程
        $where2['a.user_id'] = $user_id;
        $where2['a.project_id'] = 0;
        $data2= M('course_chapter a')
    		->join('left join __COURSE__ b on a.course_id=b.id')
			->field("a.user_id,a.course_id")
    		->where($where2)
    		->group('a.user_id,a.course_id')
    		->select();
        foreach($data2 as $k=>$v){
            $per = $this->getCoursePer($v['user_id'],$v['course_id']);
            $data2[$k]['per'] = $per;
        }

        $courses = array_merge($course,$data2);
        $count = count($courses);

        $finishedCourseNum = $unFinishedCourseNum = 0;

        foreach($courses as $k=>$v){
            if($v['per'] == 100){
                $finishedCourseNum += 1;
            }
            $courses[$k]['project_id'] = $v['project_id'] == 0 ? 'true' : $v['project_id'];
        }
        
        $unFinishedCourseNum = $count - $finishedCourseNum;

        $courseSort = $this->courseSort($courses);
        $courseRes = array_slice($courseSort,0,8); //只取前面8个做展示
        
        $test = $this->getTest($user_id);
        $survey = $this->getSurvey($user_id);

		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "id,template,tissue_id,title,type,content,user_id,img,auth_user_id,to_char(create_time,'YYYY-MM-DD HH24:MI:SS') as create_time";
		}else{
			$field = "*";
		}
        $news = M('news')->order('create_time desc')->field($field)->limit(10)->select(); //新闻资讯
        
        return array(
            'data'=>$courseRes,    //所有未完成课程信息
            'credit'=>$credit,              //学分
            'hours'=>$hours,                //学时
            'news'=>$news,                  //新闻资讯
            'schedule'=>$scheduleStr,          //日程信息
            'finishedCourseNum'=>$finishedCourseNum,//已完成的课程数量
            'unFinishedCourseNum'=>$unFinishedCourseNum,//未完成的课程数量
            'unFinishedTestNum'=>$test['num'],                    //未完成的考试数目
            'unFinishedTestInfo'=>$test['list'],                    //未完成的考试信息
            'unFinishedSurveyNum'=>$survey['num'],                //未完成的调研数目
            'unFinishedSurveyInfo'=>$survey['list'],                //未完成的调研信息
            'time'=>date('Y-m-d H:i:s')

        );
    }


    /**
     * 获取未完成的课程信息
     * @param $user_id 用户id
     * @return array 课程信息/已完成、未完成的课程数量
     */
    public function getUnFinishedCourse($user_id){

        $data = M('course_chapter')
                ->alias('a')
                ->join('left join __COURSE__ b on a.course_id=b.id')
                ->join('left join __COURSE_CATEGORY__ c on b.course_cat_id=c.id')
                ->join('left join __PROJECT_COURSE__ d on a.course_id=d.course_id')
                ->join('left join __ADMIN_PROJECT__ e on d.project_id=e.id')
                ->where(array('a.user_id'=>$user_id))
                ->field('a.course_id,b.course_name,b.course_time,b.course_cover,c.cat_name,d.start_time,e.id')
                ->distinct(true)
                ->order('d.start_time')
                ->select();

        foreach($data as $k=>$v){
            $info = M('course_chapter')->where(array('user_id'=>$user_id,'course_id'=>$v['course_id']))->select();
            $data[$k]['finished'] = 1;//课程已完成
            foreach($info as $kk=>$vv){
                if($vv['status'] != 3){
                    $data[$k]['finished'] = 0;//课程未完成
                }
            }
        }
        $finishedCourseNum = 0;
        foreach($data as $k=>$v){
            if($v['finished'] == 1){
                $finishedCourseNum += 1;//已完成的课程数量
            }
        }

        $total = count($data);
        $unFinishedCourseNum = $total-$finishedCourseNum;
        return array('data'=>$data,'finishedCourseNum'=>$finishedCourseNum,'unFinishedCourseNum'=>$unFinishedCourseNum);
    }

    /**
     * 获取未完成的考试
     */
    public function getUnFinishedExam($user_id){
        $num = M('examination_attendance')->where(array('user_id'=>$user_id,'status'=>0))->count();

        $data = M('examination_attendance')->where(array('user_id'=>$user_id,'status'=>0))
                ->field('user_id,test_id,project_id,examination_id')->select();

        $where1['b.audit_status'] = 0;
        $where1['a.user_id'] = 0;
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field1 = "b.id,b.name,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time";
		}else{
			$field1 = "b.id,b.name,b.start_time";
		}
        $minTestStartTimeId = M('test_user_rel')
                                ->alias('a')
                                ->join('left join __TEST__ b on b.id=a.test_id')
                                ->where($where1)
                                ->order('b.start_time')
                                ->field($field1)
                                ->select();
								
        $where2['b.status'] = 1;
        $where2['d.user_id'] = $user_id;
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field2 = "a.project_id,a.test_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,a.test_names,b.test_name";
		}else{
			$field2 = "a.project_id,a.test_id,a.start_time,a.test_names,b.test_name";
		}
        $minExamStartTimeId = M('project_examination')
                                ->alias('a')
                                ->join('left join __EXAMINATION__ b on a.test_id=b.id')
                                ->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
                                ->join('left join __DESIGNATED_PERSONNEL__ d on a.project_id=d.project_id')
                                ->where($where2)
                                ->order('a.start_time')
                                ->field($field2)
                                ->select();
        $count = count($minTestStartTimeId) + count($minExamStartTimeId);
        //距离现在最近的一条未完成的考试信息
        if($minTestStartTimeId && $minExamStartTimeId){
            if($minTestStartTimeId[0]['start_time'] < $minExamStartTimeId[0]['start_time']){
                return array('data'=>$minTestStartTimeId[0],'count'=>$count);
            }else{
                return array('data'=>$minExamStartTimeId[0],'count'=>$count);
            }
        }else if($minTestStartTimeId){
            return array('data'=>$minTestStartTimeId[0],'count'=>$count);
        }else if($minExamStartTimeId){
            return array('data'=>$minExamStartTimeId[0],'count'=>$count);
        }else{
            return array('data'=>array(),'count'=>0);
        }
    }

    /**
     * 获取未完成的调研信息
     */
    public function getUnFinishedServey($user_id){
        $survey = M('designated_personnel')
                    ->alias('a')
                    ->join('left join __PROJECT_SURVEY__ b on a.project_id=b.project_id')
                    ->join('left join __SURVEY__ c on  b.survey_id=c.id')
                    ->field('a.project_id,b.start_time,b.survey_names,b.survey_id,c.survey_name')
                    ->where(array('a.user_id'=>$user_id,'c.status'=>1))
                    ->order('b.start_time')
                    ->select();
        $research=M('research')
                    ->alias('a')
                    ->join('left join __RESEARCH_TISSUEID__ b on a.id=b.research_id')
                    ->join('left join __TISSUE_GROUP_ACCESS__ c on b.tissue_id=c.tissue_id')
                    ->where(array('c.user_id'=>$user_id,'a.audit_state'=>1))
                    ->order()
                    ->field('a.research_name,a.start_time,b.research_id')
                    ->select();
        
        $count = count($survey) + count($survey);
        //距离现在最近的一条未完成的考试信息
        if($survey && $research){
            if($survey[0]['start_time'] < $research[0]['start_time']){
                return array('data'=>$survey[0],'count'=>$count);
            }else{
                return array('data'=>$research[0],'count'=>$count);
            }
        }else if($survey){
            return array('data'=>$survey[0],'count'=>$count);
        }else if($research){
            return array('data'=>$research[0],'count'=>$count);
        }else{
            return array('data'=>array(),'count'=>0);
        }
    }

    /**
     * 获取课程信息
     */
    public function getCourse($user_id,$day=false){
    	if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where = "b.user_id=" . $user_id;
			$where.= " and c.status=1";
			$where.= " and c.auditing=1";
			$where.= " and c.course_name != ''";
			if($day){
	            $where .= " and a.start_time >= to_date('". $day['start_time'] ."','yyyy-mm-dd hh24:mi:ss')";
	            $where .= " and a.end_time <= to_date('". $day['end_time'] ."','yyyy-mm-dd hh24:mi:ss')";
	        }
		}else{
			$where['b.user_id'] = $user_id;
	        $where['c.status'] = 1;
	        $where['c.auditing'] = 1;
	        $where['c.course_name'] = array('neq','');
	        if($day){
	            $where['a.start_time'] = array('egt',$day['start_time']);
	            $where['a.end_time'] = array('elt',$day['end_time']);
	        }
		}
        
        $data = M('project_course')
                ->alias('a')
                ->join('left join __DESIGNATED_PERSONNEL__ b on a.project_id=b.project_id')
                ->join('left join __COURSE__ c on a.course_id=c.id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->join('left join __COURSE_CATEGORY__ e on c.course_cat_id=e.id')
                ->where($where)
                ->select();
        
        foreach($data as $k=>$v){
            //根据课程id获取本课程学习进度
            $per = $this->getCoursePer($v['user_id'],$v['course_id'],$v['project_id']);
            $data[$k]['per'] = $per;
        }

        return $data;
    }

    /**
     * 课程按照学习进度排序
     */
    public function courseSort($data){

        $len = count($data);
        if($len < 1){
            return $data;
        }
        $left = $right = array();
        for($i = 1;$i < $len;$i++){
            if($data[$i]['per'] < $data[0]['per']){
                $left[]=$data[$i];
            }else{
                $right[]=$data[$i];
            }
        }

        $left = $this->courseSort($left);
        $right = $this->courseSort($right);
        return array_merge($left,array($data[0]),$right);
    }

    /**
     * 获取学员某门课程的学习进度
     * @param $user_id 用户id
     * @param $cid 课程id
     * @param $pid 项目id
     */
    public function getCoursePer($user_id,$cid,$pid=false){
        if($pid){
            $count = M('course_chapter')->where(array('user_id'=>$user_id,'course_id'=>$cid,'project_id'=>$pid))->count();
            $finishedNum = M('course_chapter')
                            ->where(array('user_id'=>$user_id,'course_id'=>$cid,'project_id'=>$pid,'status'=>3))
                            ->count();
            $per = floor( ($finishedNum / $count) * 100 );
            return $per;
        }else{
            $count = M('course_chapter')->where(array('user_id'=>$user_id,'course_id'=>$cid))->count();
            $finishedNum = M('course_chapter')
                            ->where(array('user_id'=>$user_id,'course_id'=>$cid,'status'=>3))
                            ->count();
            $per = floor( ($finishedNum / $count) * 100 );
            return $per;
        }
    }


    //未完成的考试信息
    //需要考试id,user_id，项目id,考试名称,时间
    public function getTest($user_id,$day=false){
        $data = M('examination_attendance')->where(array('user_id'=>$user_id,'status'=>0))->select();
        
        foreach($data as $k=>$v){
            if(!$v['project_id']){
            	if(strtolower(C('DB_TYPE')) == 'oracle'){
            		$where1 = "a.user_id=" . $v['user_id'];
            		$where1.= " and a.test_id=" . $v['examination_id'];
					if($day){
	                    $where1 .= " and b.start_time >= to_date('". $day['start_time'] ."','yyyy-mm-dd hh24:mi:ss')";
	            		$where1 .= " and b.end_time <= to_date('". $day['end_time'] ."','yyyy-mm-dd hh24:mi:ss')";
	                }
            	}else{
            		$where1['a.user_id'] = $v['user_id'];
	                $where1['a.test_id'] = $v['examination_id'];
	
	                if($day){
	                    $where1['b.start_time'] = array('egt',$day['start_time']);
	                    $where1['b.end_time'] = array('elt',$day['end_time']);
	                }
            	}
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,b.name,";
					$field.= "to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
				}else{
					$field = "b.start_time,b.name,b.end_time";
				}
                $i = M('test_user_rel a')
                        ->join('left join __TEST__ b on a.test_id=b.id')
                        ->where($where1)
                        ->field($field)
                        ->order('b.start_time')
                        ->find();
                if(!$i['name']){
                    continue;
                }
                $info[$k]['user_id'] = $v['user_id'];
                $info[$k]['test_id'] = $v['examination_id'];
                $info[$k]['start_time'] = $i['start_time'];
                $info[$k]['end_time'] = $i['end_time'];
                $info[$k]['test_name'] = $i['name'];
            }
        }

        //project_examination
        if(strtolower(C('DB_TYPE')) == 'oracle'){
    		$where2 = "b.type in (0,4)";
    		$where2.= " and a.user_id=" . $user_id;
    		$where2.= " and c.start_time != ''";
			if($day){
                $where2 .= " and c.start_time >= to_date('". $day['start_time'] ."','yyyy-mm-dd hh24:mi:ss')";
        		$where2 .= " and c.end_time <= to_date('". $day['end_time'] ."','yyyy-mm-dd hh24:mi:ss')";
            }
    	}else{
    		if($day){
	            $where2['c.start_time'] = array('egt',$day['start_time']);
	            $where2['c.end_time'] = array('elt',$day['end_time']);
	        }
	        $where2['b.type'] = array('in','0,4');
	        $where2['a.user_id'] = $user_id;
	        $where2['c.start_time'] = array('neq','');
    	}
        
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.project_id,b.project_name,c.test_id,to_char(c.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(c.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.test_length,c.test_names,e.status,d.test_name";
		}else{
			$field = "a.project_id,b.project_name,c.test_id,c.start_time,c.end_time,c.test_length,c.test_names,e.status,d.test_name";
		}
        $p_exam = M("designated_personnel a")
                ->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id")
                ->join('LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id = c.project_id')
                ->join('LEFT JOIN __EXAMINATION__ d on c.test_id = d.id')
                ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ e ON d.id = e.test_id AND b.id = e.project_id and e.user_id=a.user_id')
                ->where($where2)
                ->order('c.start_time')
                ->field($field)
                ->select();
        
        //去掉已考试和已过期的
        foreach ($p_exam as $k=>$v) {
            $p_exam[$k]['test_name'] = empty($v['test_names']) ? $v['test_name'] : $v['test_names'];
            if($v['status']==1 || strtotime($v['end_time']) < time()){
                unset($p_exam[$k]);
            }
        }

        $final = array_merge($info,$p_exam);
        return array('list'=>$final,'num'=>count($final));
    }

    /**
     * 未完成的调研信息
     * @param $user_id用户id
     */
    public function getSurvey($user_id,$day=false){
		if(strtolower(C('DB_TYPE')) == 'oracle'){
    		$where1 = "a.state = 0 and a.user_id=" . $user_id;
    		$where2 = "a.status = 0 and a.user_id=" . $user_id;
			if($day){
                $where = " and b.start_time >= to_date('". $day['start_time'] ."','yyyy-mm-dd hh24:mi:ss')";
        		$where .= " and b.end_time <= to_date('". $day['end_time'] ."','yyyy-mm-dd hh24:mi:ss')";
				
				$where1 .= $where;
				$where2 .= $where;
            }
    	}else{
    		$where1['a.state'] = 0;
	        $where1['a.user_id'] = $user_id;
	
	        $where2['a.user_id'] = $user_id;
	        $where2['a.status'] = 0;
	        if($day){
	        	$where1['b.start_time'] = array('egt',$day['start_time']);
	            $where1['b.end_time'] = array('elt',$day['end_time']);
	            $where['b.start_time'] = array('egt',$day['start_time']);
	            $where['b.end_time'] = array('elt',$day['end_time']);
	
	            $where1 = array_merge($where,$where1);
	            $where2 = array_merge($where,$where2);
	        }
    	}
        
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field1 = "a.survey_id,a.research_id,a.user_id,a.state,a.id,b.research_name,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
			$field2 = "a.id,a.user_id,a.survey_id,a.department_id,a.position_id,a.status,a.mobile_scanning,a.project_id,b.survey_name,";
			$field2.= "to_char(c.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(c.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field1 = "a.*,b.research_name,b.start_time,b.end_time";
			$field2 = "a.*,b.survey_name,c.start_time,c.end_time";
		}
        $research = M('research_attendance a')
                    ->join('left join __RESEARCH__ b on b.id=a.research_id')
                    ->where($where1)
                    ->field($field1)
                    ->order('b.start_time')
                    ->select();

        $survey = M('survey_attendance a')
                    ->join('left join __SURVEY__ b on a.survey_id=b.id')
                    ->join('left join __PROJECT_SURVEY__ c on a.project_id=c.project_id and a.survey_id=c.survey_id')
                    ->where($where2)
                    ->field($field2)
                    ->order('c.start_time')
                    ->select();
        
        foreach($research as $k=>$v){
            if(!$v['research_name'] || strtotime($v['end_time']) < time()){
                continue;
            }
            $i['research_id'] = $v['research_id'];//调研id
            $i['survey_id'] = $v['survey_id'];//调研卷子id
            $i['user_id'] = $v['user_id'];//用户id
            $i['name'] = $v['research_name'];//调研名称
            $i['start_time'] = $v['start_time'];//开始时间
            $i['end_time'] = $v['end_time'];//结束时间
            $info[] = $i;
        }
        foreach($survey as $k=>$v){
            if(strtotime($v['end_time']) < time()){
                continue;
            }
            $i['research_id'] = $v['survey_id'];
            $i['project_id'] = $v['project_id'];
            $i['user_id'] = $v['user_id'];
            $i['start_time'] = $v['start_time'];//开始时间
            $i['end_time'] = $v['end_time'];//结束时间
            $i['name'] = !empty($v['survey_names']) ? $v['survey_names'] : $v['survey_name'];

            if(!$i['name']){
                continue;
            }

            $info[] = $i;
        }
        
        return array('list'=>$info,'num'=>count($info));
    }

    /**
     * @param $param Y-m-d
     */
    public function dayTask($param){
        $user_id = $_SESSION["user"]["id"];
        
        $course = $this->getDayCourse($user_id,$param);
        $survey = $this->getDaySurvey($user_id,$param);
        $test = $this->getDayTest($user_id,$param);

        if($course && $test && $survey){
            $list = array_merge($course,$test,$survey);
        }else if($course && $test){
            $list = array_merge($course,$test);
        }else if($test && $survey){
            $list = array_merge($test,$survey);
        }else if($course && $survey){
            $list = array_merge($course,$survey);
        }else if($course){
            $list = $course;
        }else if($test){
            $list = $test;
        }else if($survey){
            $list = $survey;
        }

        foreach($list as $k=>$v){
            if($v['test_name']){
                $list[$k]['type'] = '考试';
                if($v['project_id']){
                    $list[$k]['subType'] = '项目考试';
                }
            }
            if($v['name']){
                $list[$k]['type'] = '调研';
                if($v['project_id']){
                    $list[$k]['subType'] = '项目调研';
                }
            }
            if($v['course_name']){
                $list[$k]['url'] = U('Admin/MyCourse/detail',array('project_id'=>$v['project_id'],'course_id'=>$v['course_id']));
                $list[$k]['type'] = '课程';
            }
        }
        
        $list = array_chunk($list, 5);

        return array("code"=>1000, "message"=>"成功", "list"=>$list);
    }

    /**
     * 获取日程信息
     */
    public function getSchedule(){
        $user_id = $_SESSION["user"]["id"];
        $startTime = date("Y-m-01 00:00:00",strtotime("-1 month", strtotime(date("Y-m-01"))));
        $endTime = date("Y-m-d H:i:s",strtotime("2 month", strtotime(date("Y-m-01 00:00:00"))) - 1);
        $where['start_time'] = $startTime;
        $where['end_time'] = $endTime;

        $course = $this->getCourse($user_id,$where);
        $test = $this->getTest($user_id,$where);
        $survey = $this->getSurvey($user_id,$where);

        if($course && $test['list'] && $survey['list']){
            $data = array_merge($course,$test['list'],$survey['list']);
        }else if($course && $test['list']){
            $data = array_merge($course,$test['list']);
        }else if($test['list'] && $survey['list']){
            $data = array_merge($test['list'],$survey['list']);
        }else if($course && $survey['list']){
            $data = array_merge($course,$survey['list']);
        }else if($course){
            $data = $course;
        }else if($test['list']){
            $data = $test['list'];
        }else if($survey['list']){
            $data = $survey['list'];
        }

        foreach($data as $k=>$v){
            $key = strtotime(date("Y-m-d 00:00:00",strtotime($v["start_time"])));
            $schedule[$key] = date("Y-m-d",strtotime($v["start_time"]));
        }

        return $schedule;
    }

    /**
     * 某一天的课程信息
     */
    public function getDayCourse($user_id,$day){
        $where['b.user_id'] = $user_id;
        $where['c.status'] = 1;
        $where['c.auditing'] = 1;
        if($day){
            $where['a.start_time'] = array('like','%'.date('Y-m-d',strtotime($day)).'%');
        }

		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.project_id,a.course_id,a.course_names,b.user_id,c.course_name,";
			$field.= "to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,";
			$field.= "to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field = "a.project_id,a.course_id,a.start_time,a.end_time,a.course_names,b.user_id,c.course_name";
		}
        $data = M('project_course')
                ->alias('a')
                ->join('left join __DESIGNATED_PERSONNEL__ b on a.project_id=b.project_id')
                ->join('left join __COURSE__ c on a.course_id=c.id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->join('left join __COURSE_CATEGORY__ e on c.course_cat_id=e.id')
                ->where($where)
                ->field($field)
                ->select();

        foreach($data as $k=>$v){
            //根据课程id获取本课程学习进度
            $per = $this->getCoursePer($v['user_id'],$v['course_id'],$v['project_id']);
            if($per == 100){
                unset($data[$k]);
            }
            $data[$k]['course_name'] = !empty($v['course_names']) ? $v['course_names'] : $v['course_name'];
        }
        return $data;
    }

    /**
     * 某一天的调研信息
     */
    public function getDaySurvey($user_id,$day){
        $where['b.start_time'] = array('like','%'.date('Y-m-d',strtotime($day)).'%');

        $where1['a.state'] = 0;
        $where1['a.user_id'] = $user_id;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field1 = "a.survey_id,a.research_id,a.user_id,a.state,a.id,b.research_name,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field1 = "a.*,b.research_name,b.start_time,b.end_time";
		}
        $research = M('research_attendance a')
                    ->join('left join __RESEARCH__ b on b.id=a.research_id')
                    ->where(array_merge($where1,$where))
                    ->field($field1)
                    ->order('b.start_time')
                    ->select();

        $where2['a.user_id'] = $user_id;
        $where2['a.status'] = 0;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field2 = "a.id,a.user_id,a.survey_id,a.department_id,a.position_id,a.status,a.mobile_scanning,a.project_id,b.survey_name,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field2 = "a.*,b.survey_name,b.start_time,b.end_time";
		}
        $survey = M('survey_attendance a')
                    ->join('left join __SURVEY__ b on a.survey_id=b.id')
                    ->where(array_merge($where2,$where))
                    ->field($field2)
                    ->order('b.start_time')
                    ->select();
        foreach($research as $k=>$v){
            $i['research_id'] = $v['research_id'];//调研id
            $i['survey_id'] = $v['survey_id'];//调研卷子id
            $i['user_id'] = $v['user_id'];//用户id
            $i['name'] = $v['research_name'];//调研名称
            $i['start_time'] = $v['start_time'];//开始时间
            $i['end_time'] = $v['end_time'];//结束时间
            $info[] = $i;
        }
        foreach($survey as $k=>$v){
            $i['research_id'] = $v['survey_id'];
            $i['project_id'] = $v['project_id'];
            $i['user_id'] = $v['user_id'];
            $i['start_time'] = $v['start_time'];//开始时间
            $i['end_time'] = $v['end_time'];//结束时间
            $i['name'] = !empty($v['survey_names']) ? $v['survey_names'] : $v['survey_name'];
            $info[] = $i;
        }

        return $info;
    }

    /**
     * 某一天的考试
     */
    public function getDayTest($user_id,$day){
        $data = M('examination_attendance')->where(array('user_id'=>$user_id,'status'=>0))->select();
        foreach($data as $k=>$v){
            if($v['project_id']){
                $where['a.project_id'] = $v['project_id'];
                $where['a.user_id'] = $v['user_id'];
                $where['b.test_id'] = $v['test_id'];
                $where['b.start_time'] = array('like','%'.date('Y-m-d',strtotime($day)).'%');
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "b.test_names,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.test_name";
				}else{
					$field = "b.test_names,b.start_time,b.end_time,c.test_name";
				}
                $i = M('designated_personnel a')
                        ->join('left join __PROJECT_EXAMINATION__ b on a.project_id=b.project_id')
                        ->join('left join __EXAMINATION__ c on b.test_id=c.id')
                        ->where($where)
                        ->field($field)
                        ->order('b.start_time')
                        ->find();
                $info[$k]['user_id'] = $v['user_id'];
                $info[$k]['project_id'] = $v['project_id'];
                $info[$k]['examination_id'] = $v['test_id'];
                $info[$k]['test_name'] = empty($i['test_names']) ? $i['test_name'] : $i['test_names'];
                $info[$k]['start_time'] = $i['start_time'];
                $info[$k]['end_time'] = $i['end_time'];
            }else{
                $where1['a.user_id'] = $v['user_id'];
                $where1['a.test_id'] = $v['examination_id'];
                $where['b.start_time'] = array('like','%'.date('Y-m-d',strtotime($day)).'%');
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.name";
				}else{
					$field = "b.start_time,b.name,b.end_time";
				}
                $i = M('test_user_rel a')
                        ->join('left join __TEST__ b on a.test_id=b.id')
                        ->where($where1)
                        ->field($field)
                        ->order('b.start_time')
                        ->find();
                $info[$k]['user_id'] = $v['user_id'];
                $info[$k]['test_id'] = $v['examination_id'];
                $info[$k]['start_time'] = $i['start_time'];
                $info[$k]['end_time'] = $i['end_time'];
                $info[$k]['test_name'] = $i['name'];
            }
        }

        foreach($info as $k=>$v){
            if(!$v['test_name']){
                unset($info[$k]);
            }
        }
        return $info;
    }

}
