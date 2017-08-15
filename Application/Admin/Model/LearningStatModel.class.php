<?php 

namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 员工学习统计模型
 */
class LearningStatModel extends BaseModel{

    protected $tableName = 'center_study'; 
    public function getList(){
    	$total_page=10;
        $search = I('get.');
        $start_page = I("get.p",1,'int');
        $name = $search['name'];
        if(isset($search['tissueid']) && $search['tissueid'] != -1){
            $where['b.tissue_id'] = $search['tissueid'];
        }
        if($name){
            $where['a.username'] = array('like',"%$name%");
        }
			
		/*$tissue_id = M('tissue_group_access')->where(array('user_id'=>session('user.id')))->getField('tissue_id');
		$s = D('ExamReport')->ruleData($tissue_id);
		
		$where['b.tissue_id'] = array('in',$s);*/
        
        $specifiedUser = D('IsolationData')->specifiedUser();
        $where['a.id'] = array('in',$specifiedUser);
		$specifiedUser = D('IsolationData')->specifiedUser();
		$where['a.id'] = array('in',$specifiedUser);
        $data = M('users')
                ->alias('a')
                ->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
                ->join('left join __TISSUE_RULE__ c on b.tissue_id=c.id')
                ->join('left join __JOBS_MANAGE__ d on b.job_id=d.id')
                ->where($where)
                ->field('a.username,a.id,c.name as tissue_name,d.name as job_name')
                ->page($start_page,$total_page)
                ->select();
        foreach($data as $k=>$v){
            $data[$k]['score'] = $this->getScore($v['id']);//积分信息
            $data[$k]['course']= $this->getCourse($v['id']);//课程数量

            //-----学时学分信息----
            //在线数据
            $where2["user_id"] = $v["id"];
            $up_study = M("center_study")->field("sum(credit) as up_credit,sum(hours) as up_hours")->where($where2)->select();
            $up_credit = $up_study[0]["up_credit"];
            $up_time = $up_study[0]["up_hours"];
            
            //线下课程数据
            $where4["a.user_id"] = $v["id"];
            $where4['c.type'] = array("in", "0,4");
            $downPro = M("designated_personnel a")->field("b.*")
            	->join("join __PROJECT_COURSE__ b on a.project_id=b.project_id")
            	->join("join __ADMIN_PROJECT__ c on a.project_id=c.id")
          	  	->where($where4)->select();
            $total_time = 0;//培训项目总学时
            $total_credit = 0;//培训项目总学分
            foreach ($downPro as $value){
            	$course = M("course")->field("course_time,course_way")->where("id=".$value["course_id"])->find();
            	if($course["course_way"] == 1){
            		if($value["is_attachment"] == 1){
            			//考勤开启
            			$awhere["user_id"] = $v["id"];
            			$awhere["course_id"] = $value["course_id"];
            			$awhere["pid"] = $value["project_id"];
            			$awhere["status"] = array("in", "1,2");
            			$attendance = M("attendance")->where($awhere)->find();
            			if($attendance){
            				$downCredit += $value["credit"];
            				$down_time += $course["course_time"];
            			}
            		}else{
            			//考勤关闭
            			$downCredit += $value["credit"];
            			$down_time += $course["course_time"];
            		}
            	}
            }
            
            $data[$k]['credit']= $up_credit + $downCredit;
            $thisHours = $up_time + $down_time;
            $thisHours = round($thisHours / 60, 2);
            $data[$k]['hours']= $thisHours;//学时
        }
        
        $count = M('users')
                ->alias('a')
                ->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
                ->join('left join __TISSUE_RULE__ c on b.tissue_id=c.id')
                ->join('left join __JOBS_MANAGE__ d on b.job_id=d.id')
                ->where($where)
                ->count();
		
		//隔离数据过滤
//		$data = D('IsolationData')->isolationData($data);

        $show = $this->pageClass($count,$total_page);

        return array(
            'data'=>$data,
            'tissueid'=>$search['tissueid'],
            'name'=>$name,
            'page'=>$show
        );
    }

    /**
     * 获取用户总积分
     * @return [type] [description]
     */
    public function getScore($user_id){
        $where['score'] = array('gt',0);
        $where['user_id'] = $user_id;
        return M('integration_record')->where($where)->sum('score');
    }

    /**
     * 获取用户已经学习的课程数量
     * @return [type] [description]
     */
    public function getCourse($user_id){
        $where['user_id'] = $user_id;
        return M('course_record')->where($where)->count();
    }

    /**
     * 获取用户学时、学分信息
     * @return [type] [description]
     */
    public function getCredit($user_id){
        //$where['typeid'] = 2;
        $where['user_id'] = $user_id;
        return M('center_study')->where($where)->field('sum(credit) as credit,sum(hours) as hours')->find();
    }

    /**
     * 导出报表
     * @return [type] [description]
     */
    public function all(){
        $data = M('users')
                ->alias('a')
                ->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
                ->join('left join __TISSUE_RULE__ c on b.tissue_id=c.id')
                ->join('left join __JOBS_MANAGE__ d on b.job_id=d.id')
                ->where($where)
                ->field('c.name as tissue_name,a.username,d.name as job_name,a.id')
                ->select();
        foreach($data as $k=>$v){
            $credit = $this->getCredit($v['id']);//学时学分信息
            $course = $this->getCourse($v['id']);//课程数量
            $score = $this->getScore($v['id']);//积分
            $data[$k]['course']= $course ? $course : '0';//课程数量
            $data[$k]['hours']= $credit['hours'] ? $credit['hours'] : '0';//学时
            $data[$k]['score'] = $score ? $score : '0';//积分信息
            $data[$k]['credit']= $credit['credit'] ? $credit['credit'] : '0';//学分

            $data[$k]['hours']= sprintf('%.2f',($credit['hours'] / 60));//学时
            unset($data[$k]['id']);
        }
        return $data;
    }

    /**
     * 所有部门分类
     * @return [type] [description]
     */
    public function getTissues(){
        return M('tissue_rule')->field('id,name')->select();
    }
	
	/**
	 * 用户学习统计详情
	 */
	public function detail(){
		$id = I('get.id');
		$total_page=30;
        $start_page = I("get.p",1,'int');
		
		$uinfo = M('users a')
                ->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
                ->join('left join __TISSUE_RULE__ c on b.tissue_id=c.id')
                ->join('left join __JOBS_MANAGE__ d on b.job_id=d.id')
				->field('a.username,c.name as tissue_name,d.name as job_name')
				->find();
		$course = $this->courseDetail($id);
		$hour = $this->hourDetail($id);
		$integration = $this->integrationDetail($id);
		$test = $this->testDetail($id);
		$survey = $this->surveyDetail($id);
		
		if(count($course) > count($hour)){
			$max = 'course';
		}else{
			$max = 'hour';
		}
		if(count($$max) < count($integration)){
			$max = 'integration';
		}
		if(count($$max) < count($test)){
			$max = 'test';
		}
		if(count($$max) < count($survey)){
			$max = 'survey';
		}
		
		//以最长的数组为准，填充其他数组
		switch($max){
			case 'course':
				$hour = array_pad($hour,count($$max),array('',''));
				$integration = array_pad($integration,count($$max),array(0,''));
				$test = array_pad($test,count($$max),array(''));
				$survey = array_pad($survey,count($$max),array(''));
				break;
			case 'hour':
				$course = array_pad($course,count($$max),array(''));
				$integration = array_pad($integration,count($$max),array(0,''));
				$test = array_pad($test,count($$max),array(''));
				$survey = array_pad($survey,count($$max),array(''));
				break;
			case 'integration':
				$course = array_pad($course,count($$max),array(''));
				$hour = array_pad($hour,count($$max),array(0,''));
				$test = array_pad($test,count($$max),array(''));
				$survey = array_pad($survey,count($$max),array(''));
				break;
			case 'test':
				$course = array_pad($course,count($$max),array(''));
				$hour = array_pad($hour,count($$max),array(0,''));
				$integration = array_pad($integration,count($$max),array(0,''));
				$survey = array_pad($survey,count($$max),array(''));
				break;
			case 'survey':
				$course = array_pad($course,count($$max),array(''));
				$hour = array_pad($hour,count($$max),array(0,''));
				$integration = array_pad($integration,count($$max),array(0,''));
				$test = array_pad($test,count($$max),array(''));
				break;
		}
		
		$count = count($$max);
		$show = $this->pageClass($count,$total_page);
		$course = array_slice($course,($start_page - 1) * $total_page,$total_page);
		$hour = array_slice($hour,($start_page - 1) * $total_page,$total_page);
		$integration = array_slice($integration,($start_page - 1) * $total_page,$total_page);
		$test = array_slice($test,($start_page - 1) * $total_page,$total_page);
		$survey = array_slice($survey,($start_page - 1) * $total_page,$total_page);
		
		return array(
			'uinfo'=>$uinfo,
			'course'=>$course,
			'hour'=>$hour,
			'integration'=>$integration,
			'test'=>$test,
			'survey'=>$survey,
			'page'=>$show
		);
	}
	
	/**
	 * 课程详情
	 */
	public function courseDetail($id){
        return M('course_record a')
        		->join('left join __COURSE__ b on a.course_id=b.id')
        		->where(array('a.user_id'=>$id))
				->field('b.course_name')
        		->select();
	}
	
	/**
	 * 学时详情
	 */
	public function hourDetail($id){
        $res = M('center_study a')
        		->join('left join __COURSE__ b on a.source_id=b.id')
        		->where(array('a.user_id'=>$id))
        		->field('a.hours,b.course_name')
        		->select();
		foreach($res as $k=>$v){
			$res[$k]['hours'] = $v['hours'] ? $v['hours'] : 0;//学时
			$res[$k]['hours'] = sprintf('%.2f',($v['hours'] / 60));//学时
			
			if(!$v['hours'] && !$v['course_name']){
				unset($res[$k]);
			}
		}
		return $res;
	}
	
	/**
	 * 积分详情
	 */
	public function integrationDetail($id){
        return M('integration_record a')
        		->where(array('a.user_id'=>$id))
        		->field('describe,score')
        		->select();
	}
	
	/**
	 * 考试详情
	 */
	public function testDetail($id){
        $res = M('exam_score')
        		->where(array('user_id'=>$id))
        		->field('project_id,exam_id,test_id')
        		->select();
		
		$data = array();
		foreach($res as $k=>$v){
			if($v['project_id']){
				$name = M('project_examination a')
						->join('left join __EXAMINATION__ b on a.test_id=b.id')
						->field('a.test_names,b.test_name')
						->where(array('a.project_id'=>$v['project_id'],'a.test_id'=>$v['test_id']))
						->find();
				$data[$k]['name'] = $name['test_names'] ? $name['test_names'] : $name['test_name'];
			}else{
				$data[$k] = M('test_user_rel a')
						->join('left join __TEST__ b on a.test_id=b.id')
						->where(array('a.test_id'=>$v['test_id'],'a.user_id'=>$id))
						->field('b.name')
						->find();
			}
			
			if(!$data[$k]['name']){
				unset($data[$k]);
			}
		}
		return $data;
	}
	
	/**
	 * 调研详情
	 */
	public function surveyDetail($id){
		//处理试卷过期
        D('MySurvey')->overdue();
        $survey = D('MySurvey')->getWaitSurvey(9999999999);
		$survey = $survey['map'] ? $survey['map'] : array();
		
		//添加所属组织参加试卷状态
        D('MySurvey')->researchAdd(9999999999);
        $research = D('MySurvey')->researchList($total_page);
		$research = $research['map'] ? $research['map'] : array();
		
		$res = array_merge($survey,$research);
		
		$result = array();
		foreach($res as $k=>$v){
			if($v['survey_name']){
				$result[$k]['name'] = $v['survey_names'] ? $v['survey_names'] : $v['survey_name'];
			}else{
				$result[$k]['name'] = $v['research_name'];
			}
		}

		return $result;
		
	}
}