<?php 

namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 考试报表模型
 */
class ExamReportModel extends BaseModel{
    protected $tableName = 'test';

    public function getList(){

        $get = I('get.');
        $start_page = I("get.p",1,'int');
        $total_page = 10;
        $name = $get['name'];
		
		$specifiedUser = D('IsolationData')->specifiedUser();
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where = "auth_user_id in (".implode(',',$specifiedUser).")";
		}else{
			$where['auth_user_id'] = array('in',$specifiedUser);
		}
		
        //条件拼装
        if($get['start_time'] && $get['end_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$where .= " and start_time >=to_date('".$get['start_time']."','yy-mm-dd hh24:mi:ss')";
				$where .= " and end_time <=to_date('".$get['end_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$where['start_time'] = array('EGT',$get['start_time']);
        		$where['end_time'] = array('ELT',$get['end_time']);
        	}
        }else if($get['start_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$where .= " and start_time>=to_date('".$get['start_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$where['start_time'] = array('EGT',$get['start_time']);
        	}
        }else if($get['end_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$where .= " and end_time<=to_date('".$get['end_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$where['end_time'] = array('ELT',$get['end_time']);
        	}
        }
        if($name){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$where .= " and test_name like %".$name."%";
        	}else{
        		$where['test_name'] = array('like',"%".$name."%");
        	}
        }

        $allDb = M('examination_collect');
        $testDb = M('test');
        $examinationDb = M('examination');
        $allData = $allDb->select();
		
        if(!$allData){
           
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$testData = $testDb
                    ->alias('a')
                    ->join('left join __USERS__ b on a.create_user=b.id')
                    ->field("a.id,a.name,a.create_user,a.type,a.status,a.audit_status,a.score,a.examination_id,a.address,a.orderno,a.objection,a.pass_line,a.test_length,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.username")
                    ->select();
			}else{
				$testData = $testDb
                    ->alias('a')
                    ->join('left join __USERS__ b on a.create_user=b.id')
                    ->field('a.*,b.username')
                    ->select();
			}

            foreach($testData as $k=>$v){
                $infoi['test_name'] = $v['name'];
                $infoi['create_user'] = $v['create_user'];
                $infoi['type'] = $v['type'];
                $infoi['start_time'] = $v['start_time'];
                $infoi['end_time'] = $v['end_time'];
                $infoi['principal'] = $v['username'];
                $infoi['test_id'] = $v['id'];
                $infoi['examination_id'] = $v['examination_id'];//试卷Id
                $infoi['auth_user_id'] = $v['auth_user_id'];
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$infoi['id'] = getNextId('examination_collect');
					$infoi['start_time'] = array('exp',"to_date('".$v['start_time']."','yy-mm-dd hh24:mi:ss')");
					$infoi['end_time'] = array('exp',"to_date('".$v['end_time']."','yy-mm-dd hh24:mi:ss')");
				}
				
                $i = $allDb->add($infoi);
                if(!$i){
                    return false;
                }
            }
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "a.project_id,a.test_id,a.credits,a.test_length,a.manager_id,a.test_names,a.cacheid,a.examination_address,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.test_name,c.project_name,d.username";
			}else{
				$field = "a.*,b.test_name,c.project_name,d.username";
			}
			$examinationData = M('project_examination')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION__ b on a.test_id=b.id')
                ->join('LEFT JOIN __ADMIN_PROJECT__ c on a.project_id=c.id')
                ->join('left join __USERS__ d on a.manager_id=d.id')
                ->field($field)
                ->select();

            foreach($examinationData as $K=>$v){
                $infoj['test_name'] = empty($v['test_names']) ? $v['test_name'] : $v['test_names'];
                $infoj['create_user'] = $v['test_heir'];
                $infoj['type'] = $v['type'];
                $infoj['start_time'] = $v['start_time'];
                $infoj['end_time'] = $v['end_time'];
                $infoj['principal'] = $v['username'];
                $infoj['test_id'] = $v['project_id'];
                $infoj['examination_id'] = $v['test_id'];//试卷id
                $infoj['belong_project_id'] = $v['project_id'];
                $infoj['belong_project_name'] = $v['project_name'];
				$infoj['auth_user_id'] = $v['auth_user_id'];
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$infoj['id'] = getNextId('examination_collect');
					$infoj['start_time'] = array('exp',"to_date('".$v['start_time']."','yy-mm-dd hh24:mi:ss')");
					$infoj['end_time'] = array('exp',"to_date('".$v['start_time']."','yy-mm-dd hh24:mi:ss')");
				}
				
                $j = $allDb->add($infoj);
                if(!$j){
                    return false;
                }
            }

            //更新汇总表之后保存最后一条数据的id
            $testid = $testDb->max('id');
            $saveTestMaxIdRes = $allDb->where('id != 0')->save(array('test_max_id'=>$testid));
            if($saveTestMaxIdRes === false){
                return false;
            }
            $examinationid = M('project_examination')->max('project_id');
            $saveExaminationMaxIdRes = $allDb->where('id != 0')->save(array('examination_max_id'=>$examinationid));
            if($saveExaminationMaxIdRes === false){
                return false;
            }

        }else{
            $testMaxId = $allDb->max('test_max_id');
            $testWhere['a.id'] = array('GT',$testMaxId);
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$testRes = $testDb
                    ->alias('a')
                    ->join('left join __USERS__ b on a.create_user=b.id')
                    ->where($testWhere)
                    ->field("a.id,a.name,a.create_user,a.type,a.status,a.audit_status,a.score,a.examination_id,a.address,a.orderno,a.objection,a.pass_line,a.test_length,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.username")
                    ->select();
			}else{
				$testRes = $testDb
                    ->alias('a')
                    ->join('left join __USERS__ b on a.create_user=b.id')
                    ->where($testWhere)
                    ->field("a.*,b.username")
                    ->select();
			}
            
            if($testRes){
                foreach($testRes as $k=>$v){
                    $infoi['test_name'] = $v['name'];
                    $infoi['create_user'] = $v['create_user'];
                    $infoi['type'] = $v['type'];
                    $infoi['start_time'] = $v['start_time'];
                    $infoi['end_time'] = $v['end_time'];
                    $infoi['principal'] = $v['username'];
                    $infoi['test_id'] = $v['id'];
                    $infoi['examination_id'] = $v['examination_id'];//试卷Id
		    		$infoi['auth_user_id'] = $v['auth_user_id'];
					
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$infoi['id'] = getNextId('examination_collect');
						$infoi['start_time'] = array('exp',"to_date('".$v['start_time']."','yy-mm-dd hh24:mi:ss')");
						$infoi['end_time'] = array('exp',"to_date('".$v['end_time']."','yy-mm-dd hh24:mi:ss')");
					}
					
                    $i = $allDb->add($infoi);
                    if(!$i){
                        return false;
                    }
                }
            }

            $examinationMaxId = $allDb->max('examination_max_id');
            $examinationWhere['a.project_id'] = array('GT',$examinationMaxId);
            
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$examinationData = M('project_examination')
                    ->alias('a')
                    ->join('LEFT JOIN __EXAMINATION__ b on a.test_id=b.id')
                    ->join('LEFT JOIN __ADMIN_PROJECT__ c on a.project_id=c.id')
                    ->join('left join __USERS__ d on a.manager_id=d.id')
                    ->field("a.project_id,a.test_id,a.credits,a.test_length,a.manager_id,a.test_names,a.cacheid,a.examination_address,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.test_name,c.project_name,d.username")
                    ->select();
			}else{
				$examinationData = M('project_examination')
                    ->alias('a')
                    ->join('LEFT JOIN __EXAMINATION__ b on a.test_id=b.id')
                    ->join('LEFT JOIN __ADMIN_PROJECT__ c on a.project_id=c.id')
                    ->join('left join __USERS__ d on a.manager_id=d.id')
                    ->field('a.*,b.test_name,c.project_name,d.username')
                    ->select();
			}
            
            if($examinationRes){
                foreach($examinationRes as $K=>$v){
                    $infoj['test_name'] = empty($v['test_names']) ? $v['test_name'] : $v['test_names'];
                    $infoj['create_user'] = $v['test_heir'];
                    $infoj['type'] = $v['type'];
                    $infoj['start_time'] = $v['start_time'];
                    $infoj['end_time'] = $v['end_time'];
                    $infoj['principal'] = $v['username'];
                    $infoj['test_id'] = $v['project_id'];
                    $infoj['examination_id'] = $v['test_id'];//试卷id
                    $infoj['belong_project_id'] = $v['project_id'];
                    $infoj['belong_project_name'] = $v['project_name'];
		    		$infoj['auth_user_id'] = $v['auth_user_id'];
					
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$infoj['id'] = getNextId('examination_collect');
						$infoj['start_time'] = array('exp',"to_date('".$v['start_time']."','yy-mm-dd hh24:mi:ss')");
						$infoj['end_time'] = array('exp',"to_date('".$v['end_time']."','yy-mm-dd hh24:mi:ss')");
					}
					
                    $j = $allDb->add($infoj);
                    if(!$j){
                        return false;
                    }
                }
            }
            $testid = $testDb->max('id');
            $saveTestMaxIdRes = $allDb->where('id != 0')->save(array('test_max_id'=>$testid));
            if($saveTestMaxIdRes === false){
                return false;
            }
            $examinationid = M('project_examination')->max('project_id');
            $saveExaminationMaxIdRes = $allDb->where('id != 0')->save(array('examination_max_id'=>$examinationid));
            if($saveExaminationMaxIdRes === false){
                return false;
            }
        }

        
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "id,test_name,test_id,create_user,type,principal,belong_project_id,belong_project_name,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,auth_user_id";
		}else{
			$field = "*";
		}
        if(empty($name)){
            $res = $allDb
            	->where($where)
            	->page($start_page,$total_page)
				->field($field)
            	->order('start_time desc')
            	->select();
        }else{
            $res = $allDb
            	->where($where)
				->field($field)
            	->order('start_time desc')
            	->select();
        }
        
        foreach($res as $k=>$v){
            $info = $this->getFieldById($v['create_user']);
            $res[$k]['create_user'] = $info['username'];
        }
        $count = $allDb->where($where)->count();
		
		//隔离数据过滤
		$res = D('IsolationData')->isolationData($res);

        $show = $this->pageClass($count,$total_page);

        //考试应到、实到人数
        foreach($res as $k=>$v){
            $attendance = $this->getNumByTid($v['test_id'],$v['belong_project_id'],$v['id']);    //考试id  项目id
            $res[$k]['should'] = $attendance['should'];
            $res[$k]['real'] = $attendance['real'];
            $res[$k]['avg'] = $attendance['avg'];
        }

        return array(
            'res'=>$res,
            'name'=>$name,
            'start_time'=>$get['start_time'],
            'end_time'=>$get['end_time'],
            'page'=>$show
        );
    }

    /**
     * 根据考试id、项目id获取本次考试应到/实到人数/平均分
     * @param  [type] $tid [description]
     * @param  [type] $pid [description]
     * @return [type]      [description]
     */
    public function getNumByTid($tid,$pid=false,$id=false){
        $db = M('examination_attendance');
        //获取__EXAMINATION__表中人员的应到实到平均分:需要提供 试卷ID 项目id  
        //think_test表：需提供考试id  

        if($pid){
            $should = M('designated_personnel')->where(array('project_id'=>$pid))->count();
            $real = $db->where(array('status'=>1,'project_id'=>$pid,'test_id'=>$tid))->count();
            $score = M('exam_score')->where(array('project_id'=>$pid,'exam_id'=>$tid))->sum('total_score');
            $r = $score / $real;
            $avg = $r ? $r : 0;
        }else{
            $should = $db->where(array('examination_id'=>$tid))->count();
            $real = $db->where(array('status'=>1,'examination_id'=>$tid))->count();
            $score = M('exam_score')->where(array('test_id'=>$tid))->sum('total_score');
            $r = $score / $real;
            $avg = $r ? $r : 0;
        }
        
		$avg = round($avg,2);
        return array('should'=>$should,'real'=>$real,'avg'=>$avg);
    }

    /**
     * [根据用户id获取用户信息字段 ]
     * @param  [type] $id [用户id]
     * @param  [type] $field [要获取的字段名]
     * @return [type]     [description]
     */
    public function getFieldById($id,$field='username'){
        return M('users')->where(array('id'=>$id))->field($field)->find();
    }

    /**
     * 获取所有考试信息
     * @return [type] [description]
     */
    public function all(){
        $specifiedUser = D('IsolationData')->specifiedUser(false);
		$where['auth_user_id'] = array('in',$specifiedUser);
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "id,test_name,test_id,create_user,type,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,principal,test_max_id,examination_max_id,belong_project_id,belong_project_name,auth_user_id";
		}else{
			$field = "*";
		}
		$res = M('examination_collect')->where($where)->field($field)->select();
		//隔离数据过滤
		$res = D('IsolationData')->isolationData($res);
		
        // dump($res);
        foreach($res as $k=>$v){
            $info = $this->getFieldById($v['create_user']);
            $res[$k]['principal'] = $info['username'];
            $attendance = $this->getNumByTid($v['id']);
            $res[$k]['should'] = $attendance['should'];
            $res[$k]['real'] = $attendance['real'];
            $res[$k]['avg'] = $attendance['avg'];
            if($v['type']==1){
                $res[$k]['type'] = '线下';
            }else{
                $res[$k]['type'] = '线上';
            }
            if(!$v['avg']){
                $res[$k]['avg'] = '0';
            }
            unset($res[$k]['id']);
            unset($res[$k]['test_id']);
            unset($res[$k]['create_user']);
            unset($res[$k]['test_max_id']);
            unset($res[$k]['auth_user_id']);
            unset($res[$k]['examination_max_id']);
            unset($res[$k]['belong_project_id']);
            unset($res[$k]['isequality']);
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				unset($res[$k]['numrow']);
			}
        }
        return $res;
    }

    /**
     * 讲师库报表
     * @param  integer $total_page [description]
     * @return [type]              [description]
     */
    public function getLecturerList($total_page=10){
        $search = I('get.');
        $name = $search['name'];
        $start_page = I("get.p",0,'int');
        if(isset($search['type']) && $search['type'] != -1){
            $where['a.type'] = $search['type'];
        }
        if(!empty($search['tissue']) && $search['tissue'] != -1){
            $where['d.id'] = $search['tissue'];
        }
        if($name){
            $where['a.name'] = array('like',"%$name%");
        }

        $specifiedUser = D('IsolationData')->specifiedUser();
		$where['a.auth_user_id'] = array('in',$specifiedUser);
        if(empty($name)){
            $data = M('lecturer')
                ->alias('a')
                ->join('LEFT JOIN __USERS__ b on a.user_id=b.id')
                ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ c on b.id=c.user_id')
                ->join('LEFT JOIN __TISSUE_RULE__ d on c.tissue_id=d.id')
                ->field('a.*,d.name as tissue_name')
                ->where($where)
                ->page($start_page,$total_page)
                ->order('a.create_time desc')
                ->select();
        }else{
            $data = M('lecturer')
                ->alias('a')
                ->join('LEFT JOIN __USERS__ b on a.user_id=b.id')
                ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ c on b.id=c.user_id')
                ->join('LEFT JOIN __TISSUE_RULE__ d on c.tissue_id=d.id')
                ->field('a.*,d.name as tissue_name')
                ->where($where)
                ->order('a.create_time desc')
                ->select();
        }

        foreach($data as $k=>$v){
            $num = M('project_course')
                ->alias('a')
                ->join('left join __COURSE__ b on a.course_id=b.id')
                ->join('left join __LECTURER__ c on b.lecturer=c.id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->where(array('c.id'=>$v['id']))
                ->count();
            $data[$k]['num'] = $num;
        }

        $count = M('lecturer')
                ->alias('a')
                ->join('LEFT JOIN __USERS__ b on a.user_id=b.id')
                ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ c on b.id=c.user_id')
                ->join('LEFT JOIN __TISSUE_RULE__ d on c.tissue_id=d.id')
                ->field('a.*,d.name as tissue_name')
                ->where($where)
                ->count();
		
		//隔离数据过滤
		$data = D('IsolationData')->isolationData($data);
        $show = $this->pageClass($count,$total_page);
        $per = $this->getLecturerNum();

        return array(
            'list'=>$data,
            'type'=>$search['type'],
            'tissue'=>$search['tissue'],
            'page'=>$show,
            'name'=>$search['name'],
            'percentage'=>$per
        );
    }


    /**
     * 获取内部、外部讲师数量
     * @return [type] [description]
     */
    public function getLecturerNum(){
        $in = M('lecturer')->where(array('type'=>0))->count();
        $out= M('lecturer')->where(array('type'=>1))->count();
        return array($in,$out);
    }

    /**
     * 获取课程/讲师相关信息
     * @return [type] [description]
     */
    public function getCourse($total_page=10){
        $id = I('get.id',0,'int');
        $nowpage = I("get.p",1,'int');
        $uinfo = M('lecturer')->alias('a')->join('LEFT JOIN __USERS__ b on a.user_id=b.id')
                ->where(array('a.id'=>$id))->field('a.*,b.job_number')->find();
		
		$where['c.id'] = $id;
		$where['b.lecturer_name'] = $uinfo['name'];
		$where['_logic'] = 'OR';
        $data = M('project_course')
                ->alias('a')
                ->join('left join __COURSE__ b on a.course_id=b.id')
                ->join('left join __LECTURER__ c on b.lecturer=c.id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->where($where)
                ->page($nowpage,$total_page)
                ->field('a.project_id,a.start_time as pj_start_time,b.*,c.score as lscore,d.project_name')
                ->select();

        $count = M('project_course')
                ->alias('a')
                ->join('left join __COURSE__ b on a.course_id=b.id')
                ->join('left join __LECTURER__ c on b.lecturer=c.id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->where(array('c.id'=>$id))
                ->count();

        foreach($data as $k=>$v){
            $attendance = $this->getCourseAttendance($v['project_id'],$v['id']);
            $data[$k]['should'] = $attendance['should'];
            $data[$k]['real'] = $attendance['real'];

            $temp[] = $v['course_name'];
        }

        $course_count = count(array_unique($temp));
        $show = $this->pageClass($count,$total_page);
        $allpage = ceil($count / $total_page);

        return array(
            'list'=>$data,
            'page'=>$show,
            'uinfo'=>$uinfo,
            'allpage'=>$allpage,
            'nowpage'=>$nowpage,
            'count'=>$count,
            'course_count'=>$course_count
        );
    }

    /**
     * @param $pid 项目id
     * @param $cid 课程id
     */
    public function getCourseAttendance($pid,$cid){
        $should = M('designated_personnel')->where(array('project_id'=>$pid))->count();
        $where['pid'] = $pid;
        $where['course_id'] = $cid;
        $where['status'] = 1;
        $real = M('attendance')->where($where)->count();
        return array('should'=>$should,'real'=>$real);
    }
    
    /**
     * 导出讲师库报表
     * @return [type] [description]
     */
    public function exportLecturer(){
        $data = M('lecturer')
                ->alias('a')
                ->join('LEFT JOIN __USERS__ b on a.user_id=b.id')
                ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ c on b.id=c.user_id')
                ->join('LEFT JOIN __TISSUE_RULE__ d on c.tissue_id=d.id')
                ->field('a.type,d.name as tissue_name,a.name,a.num,a.score')
                ->select();
        foreach($data as $k=>$v){
            if($v['type']==1){
                $data[$k]['type'] = '外部讲师';
            }else{
                $data[$k]['type'] = '内部讲师';
            }
            if($v['tissue_name'] == ''){
                $data[$k]['tissue_name'] = '---';
            }
        }
        return $data;
    }

    /**
     * 获取所有部门
     * @return [type] [description]
     */
    public function getTissues(){
        $data = M('tissue_rule')->field('id,name')->select();
        
        return $data;
    }

    /**
     * 调研报表
     * @return [type] [description]
     */
    public function getSurveyList($total_page=10){
        //处理试卷过期
        D('MySurvey')->overdue();
        //添加所属组织参加试卷状态
        D('MySurvey')->researchAdd($total_page);

        $get = I('get.');
        $start_page = I('get.p',0,'int');
		
		$specifiedUser = D('IsolationData')->specifiedUser();
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$map = "a.auth_user_id in (".implode(',',$specifiedUser).")";
		}else{
			$where['a.auth_user_id'] = array('in',$specifiedUser);
		}
		
        //其它调研数据
        if($get['start_time'] && $get['end_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$map .= " and a.start_time >=to_date('".$get['start_time']."','yy-mm-dd hh24:mi:ss')";
				$map .= " and a.end_time >=to_date('".$get['end_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$map['a.start_time'] = array('EGT',$get['start_time']);
            	$map['a.end_time'] = array('ELT',$get['end_time']);
        	}
        }else if($get['start_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$map .= " and a.start_time >=to_date('".$get['start_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$map['a.start_time'] = array('GT',$get['start_time']);
        	}
        }else if($get['end_time']){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$map .= " and a.end_time >=to_date('".$get['end_time']."','yy-mm-dd hh24:mi:ss')";
        	}else{
        		$map['a.end_time'] = array('LT',$get['end_time']);
        	}
        }
        if(!empty($get['name'])){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
        		$map .= " and (a.research_name like %".$get['name']."% OR b.survey_name like %".$get['name']."%)";
        	}else{
        		$map['a.research_name|b.survey_name'] = array('like',"%".$get['name']."%");
        	}
        }

		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.research_name,a.survey_id,a.credits,a.audit_state,a.create_user,a.orderno,a.objection,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,to_char(a.audit_time,'YYYY-MM-DD HH24:MI:SS') as audit_time,b.survey_name";
		}else{
			$field = "a.*,b.survey_name";
		}
		$researchAll = M('research a')
			->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")
			->field($field)
			->where($map)
			->select();
        //培训项目问卷调研
        if($get['start_time'] && $get['end_time']){
            $where['a.start_time'] = array('EGT',$get['start_time']);
            $where['a.end_time'] = array('ELT',$get['end_time']);
        }else if($get['start_time']){
            $where['a.start_time'] = array('GT',$get['start_time']);
        }else if($get['end_time']){
            $where['a.end_time'] = array('LT',$get['end_time']);
        }
        if(!empty($get['name'])){
            $where['a.survey_names|b.survey_name'] = array('like',"%".$get['name']."%");
        }

		$specifiedUser = D('IsolationData')->specifiedUser();
		$where['b.auth_user_id'] = array('in',$specifiedUser);
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.project_id,a.survey_id,a.manager_id,a.credit,a.survey_names,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.survey_name,c.project_name,d.username";
		}else{
			$field = "a.*,b.survey_name,c.project_name,d.username";
		}
		$survey_all = M('project_survey a')
			->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")
			->join('LEFT JOIN __ADMIN_PROJECT__ c on a.project_id = c.id')
			->join('LEFT JOIN __USERS__ d ON a.manager_id = d.id')
			->field($field)
			->where($where)
			->select();

        $research_data = $survey_data = array();

        foreach($researchAll as $k=>$research){

            //应到人数
            $should = M('research_attendance')->where(array('research_id'=>$research['id']))->count();

            //实到人数
            $real = M('research_attendance')->where(array('research_id'=>$research['id'],'state'=>1))->count();
            $research_data[$k]['id'] = $research['id'];
            $research_data[$k]['survey_id'] = $research['survey_id'];
            $research_data[$k]['name'] = $research['research_name'] ? $research['research_name'] : $research['survey_names'];
            $research_data[$k]['project_name'] = "";
            $research_data[$k]['username'] = "";
            $research_data[$k]['start_time'] = $research['start_time'];
            $research_data[$k]['end_time'] = $research['end_time'];
            $research_data[$k]['should'] = $should;
            $research_data[$k]['real'] = $real;
            $research_data[$k]['typeid'] = 1;
            $research_data[$k]['auth_user_id'] = $research['auth_user_id'];

            $research_data[$k]['sort'] = strtotime($research['start_time']);
        }


        foreach($survey_all as $k=>$survey){

            //应到人数
            $should = M('survey_attendance')
            			->where(array('project_id'=>$survey['project_id'],'survey_id'=>$survey['survey_id']))
            			->count();

            //实到人数
            $real = M('survey_attendance')
            		->where(array('project_id'=>$survey['project_id'],'survey_id'=>$survey['survey_id'],'status'=>1))
            		->count();
            $survey_data[$k]['id'] = $survey['project_id'];
            $survey_data[$k]['survey_id'] = $survey['survey_id'];
            $survey_data[$k]['name'] = $survey['survey_name'];
            $survey_data[$k]['project_name'] = $survey['project_name'];
            $survey_data[$k]['username'] = $survey['username'];
            $survey_data[$k]['start_time'] = $survey['start_time'];
            $survey_data[$k]['end_time'] = $survey['end_time'];
            $survey_data[$k]['should'] = $should;
            $survey_data[$k]['real'] = $real;
            $survey_data[$k]['typeid'] = 2;
            $survey_data[$k]['auth_user_id'] = $survey['auth_user_id'];
            $survey_data[$k]['sort'] = strtotime($survey['start_time']);
        }

        //合并数组
        $items_data = array_merge($research_data,$survey_data);

        //排序
        $list_data = $this->arr_sort($items_data,'sort');

        if($start_page > 1){
            $start_page = $start_page -1;
        }

        $start_page = $start_page * $total_page;

        $list = array_slice($list_data,$start_page,$total_page);

        //统计总调研数量
        $count = count($list_data);
		
		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);
		
        $show = $this->pageClass($count,$total_page);

        return array(
            'list'=>$list,
            'start_time'=>$get['start_time'],
            'end_time'=>$get['end_time'],
            'page'=>$show
        );

    }

    /**
     * @param $array
     * @param $key
     * @param string $order
     * @return array
     * 数组排序
     */

    function arr_sort($array,$key,$order="asc"){//asc是升序 desc是降序
        $arr_nums = $arr = array();
        foreach($array as $k=>$v){
            $arr_nums[$k]=$v[$key];
        }
        if($order=='asc'){
            asort($arr_nums);
        }else{
            arsort($arr_nums);
        }
        foreach($arr_nums as $k=>$v){
            $arr[$k]=$array[$k];
        }
        return $arr;

    }

    /**
     * 获取调研参与人数
     * @param  [type]  $id   [调研id]
     * @param  [type]  $pid   [项目id]
     * @return [type]        [description]
     */
    public function getAttendanceNum($id,$pid=false){
        if($pid){
            $where['survey_id'] = $id;
            $where['project_id'] = $pid;
            $where['status'] = 1;
            $should = M('designated_personnel')->where(array('project_id'=>$pid))->count();
            $real = M('survey_attendance')->where($where)->count();
            return array('should'=>$should,'real'=>$real);
        }else{
            $cond['research_id'] = $id;
            $cond['state'] = 1;
            
            $tissueIds = M('research_tissueid')->where(array('research_id'=>$id))->field('tissue_id')->select();

            foreach($tissueIds as $k=>$v){
                $temp[] = $v['tissue_id'];
            }

            $should = M('tissue_group_access')->where(array('tissue_id'=>array('in',$temp)))->count();
            $real = M('research_attendance')->where($cond)->count();
            return array('should'=>$should,'real'=>$real);
        }
    }

    /**
     * 导出调研报表
     * @return [type] [description]
     */
    public function exportSurvey(){
    	if(strtolower(C('DB_TYPE')) == 'oracle'){
    		$data = M('research_collect')
				->field("name,project_name,principal,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,should,real")
				->select();
    	}else{
    		$data = M('research_collect')->field('survey_id,research_id',true)->select();
    	}
        
        return $data;
    }

    /**
     * 课程库报表
     * @param  integer $total_page [description]
     * @return [type]              [description]
     */
    public function getCourseData($total_page=10){
        $get = I('get.');
        $name = I('get.name');
        $start_page = I('get.p',1,'int');

        if(isset($get['cate']) && $get['cate'] != -1){
            $where['b.id'] = $get['cate'];
        }
        if(isset($get['type']) && $get['type'] != -1){
            $where['a.course_way'] = $get['type'];
        }
        if(!empty($name)){
            $where['a.course_name'] = array('like',"%$name%");
        }
        $where['auditing']=1;
        $where['status'] = 1;
        
		$specifiedUser = D('IsolationData')->specifiedUser(false);
		$where['a.auth_user_id'] = array('in',$specifiedUser);
        $data = M('course')
                ->alias('a')
                ->join('left join __COURSE_CATEGORY__ b on a.course_cat_id=b.id')
                ->join('left join __LECTURER__ c on a.lecturer=c.id')
                ->field('a.id,a.course_name,a.score,a.course_way,a.lecturer_name,b.cat_name,c.name,a.auth_user_id')
                ->order('a.create_time desc')
                ->where($where)
                ->page($start_page,$total_page)
                ->select();

        foreach($data as $k=>$v){
            $data[$k]['num'] = M('center_study')->where(array('source_id'=>$v['id']))->count();
            $time = $this->getCourseTimes($v['id']);
            
            $data[$k]['hours'] = number_format($time/60,2);

            $data[$k]['name'] = !$v['name'] ? $v['lecturer_name'] : $v['name'];
        }

        $sort = array(  
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
            'field'     => 'num',       //排序字段  
        );  
        $arrSort = array();  
        foreach($data AS $uniqid => $row){  
            foreach($row AS $key=>$value){  
                $arrSort[$key][$uniqid] = $value;  
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);  
        } 

        $count = M('course')
                ->alias('a')
                ->join('left join __COURSE_CATEGORY__ b on a.course_cat_id=b.id')
                ->join('left join __LECTURER__ c on a.lecturer=c.id')
                ->where($where)
                ->count();
		
		//隔离数据过滤
		$data = D('IsolationData')->isolationData($data);
        $show = $this->pageClass($count,$total_page);
        return array(
            'data'=>$data,
            'cate'=>$get['cate'],
            'type'=>$get['type'],
            'name'=>$name,
            'page'=>$show
        );
    }

    /**
     * 获取课程类别信息(在线/面授)
     * @return [type] [description]
     */
    public function getCourseTypeInfo(){
    	
		$specifiedUser = D('IsolationData')->specifiedUser(false);
        $onData = M('course')
        		->field('id,auth_user_id')
        		->where(array('course_way'=>0,'auditing'=>1,'status'=>1,'auth_user_id'=>array('in',$specifiedUser)))
        		->select();
        $offData= M('course')
        		->field('id,auth_user_id')
        		->where(array('course_way'=>1,'auditing'=>1,'status'=>1,'auth_user_id'=>array('in',$specifiedUser)))
        		->select();
        
		//隔离数据过滤
		$onData = D('IsolationData')->isolationData($onData);
		$offData = D('IsolationData')->isolationData($offData);

        $on = $off = array();
        foreach($onData as $k=>$v){
            $tempOn[] = $v['id'];
        }
        foreach($offData as $k=>$v){
            $tempOff[] = $v['id'];
        }
		
		if(!$tempOn || !$tempOff){
			return array(
            'on'=>0,
            'off'=>0
        );
		}

        foreach($tempOn as $k=>$v){
            $s = $this->getCourseTimes($v);
            $timeOn += number_format($s/60,2);
        }
        foreach($tempOff as $k=>$v){
            $s = $this->getCourseTimes($v);
            $timeOff += number_format($s/60,2);
        }
        $on['count'] = count($tempOn);  //在线课程总数(门)
        if(count($tempOn) > 0){
	        $on['num'] = M('course_record')->where(array('course_id'=>array('in',$tempOn)))->count();//学习次数
        }
        $on['hours'] = $timeOn;//总学时，精确两位小数

        $off['count'] = count($tempOff);  //面授课程总数(门)
        if(count($tempOff) > 0){
        	$off['num'] = M('course_record')->where(array('course_id'=>array('in',$tempOff)))->count();//学习次数
        }
        $off['hours'] = $timeOff;//总学时，精确两位小数

        $total = $on['count'] + $off['count'];
        $on['percent'] = (number_format($on['count']/$total,4))*100;
        $off['percent'] = (number_format($off['count']/$total,4))*100;
        return array(
            'on'=>$on,
            'off'=>$off
        );
    }

    /**
     * 根据课程id获取课程学时
     * @param  [type] $cid [课程id]
     * @return [type]      [学时，单位:秒]
     */
    public function getCourseTimes($cid){
        $hours = M('center_study')->where(array('source_id'=>$cid))->sum('hours');
        return $hours;
    }

    /**
     * 获取课程分类信息
     * @return [type] [description]
     */
    public function getCourseCate(){
        $specifiedUser = D('IsolationData')->specifiedUser(false);
        $where['b.auth_user_id'] = array('in',$specifiedUser);
        $where['auditing']=1;
        $where['status'] = 1;
        return M('course_category a')
                ->join('left join __COURSE__ b on a.id=b.course_cat_id')
                ->where($where)
                ->distinct(true)
                ->field('a.id,a.cat_name')
                ->select();
    }

    /**
     * 导出课程库报表
     * @return [type] [description]
     */
    public function exportCourse(){
        $where['auditing']=1;
        $where['status'] = 1;
        $data = M('course')
                ->alias('a')
                ->join('left join __COURSE_CATEGORY__ b on a.course_cat_id=b.id')
                ->join('left join __LECTURER__ c on a.lecturer=c.id')
                ->field('b.cat_name,a.course_way,a.course_name,c.name,a.score,a.id')
                ->order('a.create_time desc')
                ->where($where)
                ->select();
        
        foreach($data as $k=>$v){
            $data[$k]['num'] = M('center_study')->where(array('source_id'=>$v['id']))->count();
            $time = $this->getCourseTimes($v['id']);
            $data[$k]['hours'] = number_format($time/60,2);

            $data[$k]['course_way'] = $v['course_way']==1 ? '面授' : '在线';
            unset($data[$k]['id']);
        }
        
        $sort = array(  
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
            'field'     => 'num',       //排序字段  
        );  
        $arrSort = array();  
        foreach($data AS $uniqid => $row){  
            foreach($row AS $key=>$value){  
                $arrSort[$key][$uniqid] = $value;  
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);  
        } 

        return $data;
    }

    /**
     * 获取部门学时信息
     * @return [type] [description]
     */
    public function getHours(){
        $start = I('post.start');
        
        //如果传入了日期数据，计算当月的学时信息
        //未传日期则计算最近三个月的学时信息
        if($start){
            $arr = explode('-',$start);
            $year = $arr[0];
            $month = $arr[1];

            $m = array($month);
            $dates = "['".$start."']";
        }else{
            $year = date('Y');
            $month = date('m');

            //只计算最近本年度三个月的学时信息
            $months = array(date('m',strtotime('-2 month')),date('m',strtotime('-1 month')),$month);
            foreach($months as $k=>$v){
                $m[] = strtolower($v);
            }
            //时间拼装,最近三个月
            $dates = "['".date('Y-m',strtotime('-2 month'))."','".date('Y-m',strtotime('-1 month'))."','".date('Y-m')."']";
        }

        $db = M('tissue_rule');
        $level0 = $db->where(array('pid'=>0))->getField('id');
        $level1 = $db->where(array('pid'=>$level0))->field('id')->select();
        foreach($level1 as $k=>$v){
            $temp[] = $v['id'];
        }
        
		$tissue_id = M('tissue_group_access')->where(array('user_id'=>session('user.id')))->getField('tissue_id');
		$s = D('ExamReport')->ruleData($tissue_id);
		
        $deps = $db->where(array('pid'=>array('in',$s)))->field('id,name')->select();
        foreach($deps as $k=>$v){
            $ids = M('tissue_group_access')->where(array('tissue_id'=>$v['id']))->field('user_id')->select();
            foreach($ids as $kk=>$vv){
                foreach($m as $k1=>$v1){
		            $total_time = 0;
                    $date = $year. '-' .$v1;
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$where = "user_id=".$vv['user_id'];
						$where.= " and create_time like %to_date('".$date."','yyyy-mm')%";
					}else{
						$where['user_id'] = $vv['user_id'];
                    	$where['create_time'] = array('like',"%$date%");
					}
                    
                    $res = M('center_study')->where($where)->sum('hours');
                    
                    //培训班--线下课程数据
                    if(strtolower(C('DB_TYPE')) == 'oracle'){
						$where4 = "a.user_id=".$vv['user_id'];
						$where4.= " and c.type in (0,4)";
						$where4.= " and b.start_time like %to_date('".$date."','yyyy-mm')%";
					}else{
						$where4["a.user_id"] = $vv['user_id'];
	                    $where4['b.start_time'] = array('like',"%$date%");
	                    $where4['c.type'] = array("in", "0,4");
					}
                    
                    $downPro = M("designated_personnel a")->field("b.*")
                    	->join("join __PROJECT_COURSE__ b on a.project_id=b.project_id")
                    	->join("join __ADMIN_PROJECT__ c on a.project_id=c.id")
                   		->where($where4)
                   		->select();
                    
                    foreach ($downPro as $value){
                    	$course = M("course")
                    			->field("course_name,course_way,course_time")
                    			->where("id=".$value["course_id"])
                    			->find();
                    	if($course["course_way"] == 1){
                    		if($value["is_attachment"] == 1){
                    			//考勤开启
                    			$awhere["user_id"] = $vv['user_id'];
                    			$awhere["course_id"] = $value["course_id"];
                    			$awhere["pid"] = $value["project_id"];
                    			$awhere["status"] = array("in", "1,2");
                    			$attendance = M("attendance")->where($awhere)->find();
                    			if($attendance){
                    				$total_time += $course["course_time"];
                    			}
                    		}else{
                    			//考勤关闭
                    			$total_time += $course["course_time"];
                    		}
                    	}
                    }
                    
                    $res = $res + $total_time;
                    $res = round($res / 60, 2);
                    $deps[$k]['hours'][$v1] += $res;
                }
            }
        }

        $values = array();
        foreach($deps as $k=>$v){
            $values[$deps[$k]['name']] = '['.implode(',',$v['hours']).']';
        }

        foreach($values as $k=>$v){
            $temp[] = "{name:'".$k."',type:'bar',data:".$v."}";
        }
        $temp = '['.implode(',',$temp).']';


        return array(
            'list'=>$temp,
            'dates'=>$dates,
            'start'=>$start
        );
    }

    /**
     * 获取部门信息
     * @return [type] [description]
     */
    public function getDep(){
        $db = M('tissue_rule');
        $level0 = $db->where(array('pid'=>0))->getField('id');
        $level1 = $db->where(array('pid'=>$level0))->field('id')->select();
        foreach($level1 as $k=>$v){
            $temp[] = $v['id'];
        }
        
		$tissue_id = M('tissue_group_access')->where(array('user_id'=>session('user.id')))->getField('tissue_id');
		$s = D('ExamReport')->ruleData($tissue_id);
		
        $departent = M('tissue_rule')->where(array('pid'=>array('in',$s)))->field('name')->select();

        $count = count($departent);
        for($i=0;$i<$count;$i++){
            $temp3 .= "'".$departent[$i]['name']."',";
        }

        return '['.rtrim($temp3,',').']';
    }

    /**
     * [数字月份转换英文]
     * @return [type] [description]
     */
    public function getMonthByNum($num){
        $monthArr = array(
            '01'=>'january',
            '02'=>'february',
            '03'=>'march',
            '04'=>'april',
            '05'=>'may',
            '06'=>'june',
            '07'=>'july',
            '08'=>'august',
            '09'=>'september',
            '10'=>'october',
            '11'=>'november',
            '12'=>'december'
        );
        return $monthArr[$num];
    }

    /**
     * 月份英文转换成数字格式
     * @return [type] [description]
     */
    public function getNumByMonth($month){
        $monthArr = array(
            'january'=>'01',
            'february'=>'02',
            'march'=>'03',
            'april'=>'04',
            'may'=>'05',
            'june'=>'06',
            'july'=>'07',
            'august'=>'08',
            'september'=>'09',
            'october'=>'10',
            'november'=>'11',
            'december'=>'12'
        );
        return $monthArr[$month];
    }

    /**
     * 获取所有部门学时信息
     * @return [type] [description]
     */
    public function getAllHours(){
        //部门信息
        $data = D("Tool")->tree(0);
        /* static $num = 0;
        foreach($data[1]['_data'] as $k=>$v){
            foreach($v['_data'] as $kk=>$vv){
                $departent[$num]['id'] = $vv['id'];
                $departent[$num]['name'] = $vv['name'];
                ++$num;
            }
        } */
		
        $where["id"] = array("in", $data[0]["rules"]);
        $departent = M("tissue_rule")->where($where)->select();
        
        $months = M('center_study')->getField('create_time',true);
        foreach($months as $k=>$v){
            $months[$k] = date('Y-m',strtotime($v));
        }
        $months = array_unique($months);
        
        foreach($departent as $k=>$v){
            $ids = M('tissue_group_access')->where(array('tissue_id'=>$v['id']))->field('user_id')->select();
            foreach($ids as $kk=>$vv){
                foreach($months as $k1=>$v1){
                    $where['user_id'] = $vv['user_id'];
                    $where['create_time'] = array('like',"%$v1%");
                    $res = M('center_study')->where($where)->sum('hours');
                   	
                    //培训班--线下课程数据
                    if(strtolower(C('DB_TYPE')) == 'oracle'){
						$where4 = "a.user_id=".$vv['user_id'];
						$where4.= " and c.type in (0,4)";
						$where4.= " and b.start_time like %to_date('".$v1."','yyyy-mm')%";
					}else{
						$where4["a.user_id"] = $vv['user_id'];
	                    $where4['b.start_time'] = array('like',"%$v1%");
	                    $where4['c.type'] = array("in", "0,4");
					}
                    
                    $downPro = M("designated_personnel a")->field("b.*")
		                    ->join("join __PROJECT_COURSE__ b on a.project_id=b.project_id")
		                    ->join("join __ADMIN_PROJECT__ c on a.project_id=c.id")
		                    ->where($where4)
		                    ->select();
                    $total_time = 0;
                    foreach ($downPro as $value){
                    	$course = M("course")->field("course_name,course_way,course_time")->where("id=".$value["course_id"])->find();
                    	if($course["course_way"] == 1){
                    		if($value["is_attachment"] == 1){
                    			//考勤开启
                    			$awhere["user_id"] = $vv['user_id'];
                    			$awhere["course_id"] = $value["course_id"];
                    			$awhere["pid"] = $value["project_id"];
                    			$awhere["status"] = array("in", "1,2");
                    			$attendance = M("attendance")->where($awhere)->find();
                    			if($attendance){
                    				$total_time += $course["course_time"];
                    			}
                    		}else{
                    			//考勤关闭
                    			$total_time += $course["course_time"];
                    		}
                    	}
                    }
                    
                    $res = $res + $total_time;
                    $res = round($res / 60, 2);
                    $departent[$k]['hours'][$v1] += $res;
                }
            }
        }
        foreach($departent as $k=>$v){
            $dep[$k] = $v['name'];
            foreach($v['hours'] as $kk=>$vv){
                $arr[$kk][] = $vv == 0 ? '0' : $vv;
            }
        }
        foreach($arr as $k=>$v){
            array_unshift($arr[$k],$k);
        }
        array_unshift($dep,' ');
        return array('list'=>$arr,'dep'=>$dep);
    }


    /**
     * 调研详情
     *
     */
    public function article(){

        $research_id = I('get.research_id');
        $project_id = I('get.project_id');

        $survey_id = I('get.survey_id');

        if(!empty($research_id)){
            $survey_id = M('research')->where("id=".$research_id)->getField('survey_id');
        }else{

            $project_survey = M('project_survey')->where("project_id=".$project_id)->find();

            $survey_id = $project_survey['survey_id'];
        }

        $where['a.id'] = array("eq",$survey_id);

        //问卷信息
        $survey_info = M('survey a')
        			->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
        			->where($where)
        			->field('a.survey_name,a.survey_desc,b.cat_name')
        			->find();

        //问卷试题
        $survey_item = M('survey_item')->where("survey_id=".$survey_id)->select();

        $items = array();

        foreach($survey_item as $k=>$item){

            $items[$k] = $item;

            $item_opt = M('survey_item_opt')
            			->field('letter,option,opt_img')
            			->where("item_id=".$item['id'])
            			->order("order asc")
            			->select();

            $option_list = array();

            //循环问卷选项
            foreach($item_opt as $i=>$value){

                $option_list[$i] = $value;

                if(!empty($research_id)){

                    $map['research_id'] = array("eq",$research_id);
                    $map['survey_id'] = array("eq",$survey_id);
                    $map['classification'] = array("eq",$item['classification']);
                    $map['survey_answer'] = array("eq",$value['letter']);

                    //统计选中该选项人数
                    $people_num = M('research_answer')->where($map)->count();

                    //统计该选项总人数
                    unset($map['survey_answer']);
                    $people_total = M('research_answer')->where($map)->count();

                }else{

                    $where['project_id'] = array("eq",$project_survey['project_id']);
                    $map['survey_id'] = array("eq",$project_survey['survey_id']);
                    $map['survey_answer'] = array("eq",$value['letter']);
                    $map['classification'] = array("eq",$item['classification']);

                    //统计选中该选项人数
                    $people_num = M('survey_answer')->where($map)->count();

                    //统计该选项总人数
                    unset($map['survey_answer']);
                    $people_total = M('survey_answer')->where($map)->count();

                }

                //计算百份比
                if($people_num <= 0){
                    $percentage = 0;
                }else{
                    $percentage = round($people_num / $people_total,1) * 100;
                }

                $option_list[$i]['people_num'] = $people_num;
                $option_list[$i]['percentage'] = $percentage;
            }

            $items[$k]['option'] = $option_list;


        }

        $data = array(

            'survey_info'=>$survey_info,
            'survey_item'=>$items

        );

        return $data;

    }

    /**
     * 查看详情
     */
    public function checkArticle($total_page){

        $id = I('get.id');

        $research_id = I('get.research_id');

        $project_id = I('get.project_id');

        $survey_id = I('get.survey_id');

        $start_page = I("get.p",0,'int');

        if(!empty($research_id)){
            $survey_id = M('research')->where("id=".$research_id)->getField('survey_id');
        }else{
            $project_survey = M('project_survey')->where("project_id=".$project_id)->find();

            $survey_id = $project_survey['survey_id'];
        }

        $items = array();

        if(!empty($research_id)){

            $where['research_id'] = array("eq",$research_id);
            $where['survey_id'] = array("eq",$survey_id);
            $where['classification'] = array("eq",3);
            $where['question_number'] = array("eq",$id);

            //问卷试题
            $results = M('research_answer')
            		->where($where)
            		->group("user_id")
            		->order("id asc")
            		->page($start_page,$total_page)
            		->select();

            foreach($results as $k=>$value){

                $map['survey_id'] = array("eq",$value['survey_id']);
                $map['research_id'] = array("eq",$value['research_id']);
                $map['user_id'] = array("eq",$value['user_id']);
                $map['state'] = array("eq",1);

                $commit_time = M('research_attendance')->where($map)->getField('commit_time');

                if(!empty($commit_time)){

                    $items[$k] = $value;

                    $items[$k]['commit_time'] = $commit_time;

                }

            }

            $count =  $results = M('research_answer')->where($where)->group("user_id")->select();

            $count = count($count);

        }else{

            $where['project_id'] = array("eq",$project_survey['project_id']);
            $where['survey_id'] = array("eq",$project_survey['survey_id']);
            $where['classification'] = array("eq",3);
            $where['question_number'] = array("eq",$id);

            //问卷试题
            $results = M('survey_answer')->where($where)->page($start_page,$total_page)->select();
            foreach($results as $k=>$value){
                $map['survey_id'] = array("eq",$value['survey_id']);
                $map['project_id'] = array("eq",$value['project_id']);
                $map['status'] = array("eq",1);
                $map['user_id'] = array("eq",$value['u_survey_id']);
                $commit_time = M('survey_attendance')->where($map)->getField('commit_time');
                if(!empty($commit_time)){
                    $items[$k] = $value;
                    $items[$k]['commit_time'] = $commit_time;
                }
            }
            $count =  M('survey_answer')->where($where)->count();
        }

        //输出分页
        $show=$this->pageClass($count,$total_page);

        $data = array(
            'page' => $show,
            'list' => $items,
        );
        return $data;


    }

    /**
     * 查看用户详情
     */
    public function userArticle(){

        $research_id = I('get.research_id');

        if(!empty($research_id)){
            $survey_id = I('get.survey_id');
            $user_id = I('get.user_id');

            $where['a.survey_id'] = array("eq",$survey_id);
            $where['a.research_id'] = array("eq",$research_id);
            $where['a.user_id'] = array("eq",$user_id);

            //查询问卷标题
            $research_info = M('research_attendance a')
            			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
            			->field('a.id,a.commit_time,b.username,b.email')
            			->where($where)
            			->find();

            //问卷试题
            $survey_item = M('survey_item')->where("survey_id=".$survey_id)->select();
            $items = array();
            foreach($survey_item as $k=>$item){
                $map['question_number'] = array("eq",$item['id']);
                $map['research_id'] = array("eq",$research_id);
                $map['user_id'] = array("eq",$user_id);
                $map['survey_id'] = array("eq",$survey_id);
                $items[$k] = $item;
                $item_opt = M('research_answer')
                			->field('classification,survey_answer,describe')
                			->where($map)
                			->find();
                $items[$k]['answer'] = $item_opt;
            }
        }else{
            $u_survey_id = I('get.u_survey_id');
            $project_id = I('get.project_id');
            $survey_id = I('get.survey_id');

            $where['a.user_id'] = array("eq",$u_survey_id);
            $where['a.project_id'] = array("eq",$project_id);
            $where['a.survey_id'] = array("eq",$survey_id);

            //查询问卷标题
            $research_info = M('survey_attendance a')
            			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
            			->field('a.id,a.commit_time,b.username,b.email')
            			->where($where)
            			->find();

            //问卷试题
            $survey_item = M('survey_item')->where("survey_id=".$survey_id)->select();
            $items = array();
            foreach($survey_item as $k=>$item){
                $items[$k] = $item;
                $map['question_number'] = array("eq",$item['id']);
                $map['project_id'] = array("eq",$project_id);
                $map['u_survey_id'] = array("eq",$u_survey_id);
                $map['survey_id'] = array("eq",$survey_id);
                $item_opt = M('survey_answer')
                			->field('classification,survey_answer,describe')
                			->where($map)
                			->find();
                $items[$k]['answer'] = $item_opt;
            }
            $user_id = $u_survey_id;
        }

        $data = array(
            'items'=>$items,
            'research_info'=>$research_info,
            'research_id'=>$research_id,
            'project_id'=>$project_id,
            'user_id'=>$user_id,
            'survey_id'=>$survey_id
        );
        return $data;

    }


    /**
     * 导出所有结果s
     */
    public function exportAll(){

        $research_id = I('get.research_id');
        $survey_id = I('get.survey_id');
        $project_id = I('get.project_id');
        $user_id = I('get.user_id');

        if(!empty($research_id)){
            $survey_id = M('research')->where("id=".$research_id)->getField('survey_id');
        }else{
            $project_survey = M('project_survey')->where("project_id=".$project_id)->find();

            $survey_id = $project_survey['survey_id'];
        }

        if(!empty($research_id)){

            $where['a.survey_id'] = array("eq",$survey_id);
            $where['a.research_id'] = array("eq",$research_id);
            $where['a.state'] = array("eq",1);

            if(!empty($user_id)){
                $where['a.user_id'] = array("eq",$user_id);
            }

            //参与人数据
            $research_list = M('research_attendance a')
                ->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
                ->field('a.id,a.survey_id,a.user_id,a.commit_time,b.username,b.email')
                ->where($where)
                ->select();

            //查询试卷标题
            $survey_item_list = M('survey_item')->where("survey_id=".$survey_id)->select();
            $header_title = array('账号（企业邮箱)','姓名');
            $item_title = array();;

            foreach($survey_item_list as $list){
                $item_title[] = $list['title'];
            }

            $user_title = array_merge($header_title,$item_title);

            //循环取出单个人数据
            $user_list = array();
            foreach($research_list as $i=>$list){
                $answer_list = array();
                //查询问卷所有填写项
                $survey_item = M('survey_item')->where("survey_id=".$list['survey_id'])->select();
                foreach($survey_item as $k=>$item){
                    $map['question_number'] = array("eq",$item['id']);
                    $map['survey_id'] = array("eq",$item['survey_id']);
                    $map['research_id'] = array("eq",$research_id);
                    $map['user_id'] = array("eq",$list['user_id']);
                    $map['classification'] = array("eq",$item['classification']);

                    //查询用户填写答案
                    $item_opt = M('research_answer')
                        ->field('classification,survey_answer,describe')
                        ->where($map)
                        ->find();

                    //查询试卷选项
                    $survey_item_opt = M('survey_item_opt')->where("item_id=".$item['id'])->select();
                    $answer = array();
                    //单选题
                    if($item_opt['classification'] == 1){
                        foreach($survey_item_opt as $val){
                            if($val['letter'] == $item_opt['survey_answer']){
                                $answer = $val['option'];
                            }
                        }
                    }else if($item_opt['classification'] == 2){
                        $survey_answer = explode(",",$item_opt['survey_answer']);
                        foreach($survey_item_opt as $val){
                            if(in_array($val['letter'],$survey_answer)){
                                $answer[] = $val['option'];
                            }
                        }
                        $answer = implode(",",$answer);
                    }else{
                        $answer = $item_opt['describe'];
                    }
                    $answer_list[] =  $answer;
                }
                $user_info = array($list['email'],$list['username']);
                $user_list[$i] = array_merge($user_info,$answer_list);
            }
            $data = array_merge(array($user_title),$user_list);
        }else{
            $where['a.survey_id'] = array("eq",$survey_id);
            $where['a.project_id'] = array("eq",$project_id);
            $where['a.status'] = array("eq",1);
            if(!empty($user_id)){
                $where['a.user_id'] = array("eq",$user_id);
            }

            //参与人数据
            $research_list = M('survey_attendance a')
                ->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
                ->field('a.id,a.survey_id,a.user_id as user_id,a.commit_time,b.username,b.email')
                ->where($where)
                ->select();

            //查询试卷标题
            $survey_item_list = M('survey_item')->where("survey_id=".$survey_id)->select();
            $header_title = array('账号（企业邮箱)','姓名');
            $item_title = array();;
            foreach($survey_item_list as $list){
                $item_title[] = $list['title'];
            }

            $user_title = array_merge($header_title,$item_title);


            //循环取出单个人数据
            $user_list = array();

            foreach($research_list as $i=>$list){

                $answer_list = array();

                //查询问卷所有填写项
                $survey_item = M('survey_item')->where("survey_id=".$list['survey_id'])->select();

                foreach($survey_item as $k=>$item){

                    $map['question_number'] = array("eq",$item['id']);
                    $map['survey_id'] = array("eq",$item['survey_id']);
                    $map['project_id'] = array("eq",$project_id);
                    $map['u_survey_id'] = array("eq",$list['user_id']);
                    $map['classification'] = array("eq",$item['classification']);

                    //查询用户填写答案
                    $item_opt = M('survey_answer')
                        ->field('classification,survey_answer,describe')
                        ->where($map)
                        ->find();

                    //查询试卷选项
                    $survey_item_opt = M('survey_item_opt')->where("item_id=".$item['id'])->select();

                    $answer = array();

                    //单选题
                    if($item_opt['classification'] == 1){

                        foreach($survey_item_opt as $val){

                            if($val['letter'] == $item_opt['survey_answer']){
                                $answer = $val['option'];
                            }

                        }

                    }else if($item_opt['classification'] == 2){

                        $survey_answer = explode(",",$item_opt['survey_answer']);

                        foreach($survey_item_opt as $val){

                            if(in_array($val['letter'],$survey_answer)){
                                $answer[] = $val['option'];
                            }
                        }
                        $answer = implode(",",$answer);

                    }else{
                        $answer = $item_opt['describe'];
                    }
                    $answer_list[] =  $answer;
                }

                $user_info = array($list['email'],$list['username']);
                $user_list[$i] = array_merge($user_info,$answer_list);
            }
            $data = array_merge(array($user_title),$user_list);
        }

        return $data;


    }


    /**
     * 所有问卷信息
     */
    public function addQuestionnaire($total_page){

        $research_id = I('get.research_id');

        $survey_id = I('get.survey_id');

        $project_id = I('get.project_id');

        $start_page = I("get.p",0,'int');

        if(!empty($research_id)){
            $survey_id = M('research')->where("id=".$research_id)->getField('survey_id');
        }else{
            $project_survey = M('project_survey')->where("project_id=".$project_id)->find();
        }

        if(!empty($research_id)){

            $where['a.research_id'] = array("eq",$research_id);
            $where['a.survey_id'] = array("eq",$survey_id);
            $where['a.state'] = array("eq",1);

            //问卷试题
            $results = M('research_attendance a')
            		->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
            		->field("a.*,b.username")
            		->where($where)
            		->page($start_page,$total_page)
            		->select();

            $count =  M('research_attendance a')->where($where)->count();

        }else{

            $where['a.project_id'] = array("eq",$project_survey['project_id']);
            $where['a.survey_id'] = array("eq",$project_survey['survey_id']);
            $where['a.status'] = array("eq",1);

            //问卷试题
            $results = M('survey_attendance a')
            			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
            			->field("a.*,b.username")
            			->where($where)
            			->page($start_page,$total_page)
            			->select();

            $count =  M('survey_attendance a')->where($where)->count();

        }

        //输出分页
        $show = $this->pageClass($count,$total_page);

        $data = array(
            'page' => $show,
            'list' => $results,
        );

        return $data;

    }

    /**
	 * 获取当前用户和所有下级组织的用户id
	 */
	public function ruleData($tissue_id){
		$rule_list = M("tissue_rule")->field("id")->where("pid=".$tissue_id)->select();

		foreach($rule_list as $list){
			$rows[] = $list['id'];
			$this->ruleData($list['id']);
		}
		
		$rows[] = $tissue_id;
		return $rows;

	}
}