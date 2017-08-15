<?php 

namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 考试管理模型
 */
class TestManageModel extends BaseModel{
    protected $tableName = 'test';

    /**
     * 考试列表
     * @param  integer $total_page [description]
     * @return [type]              [description]
     */
    public function getTestList($total_page=15){
        $start_page = I("get.p",0,'int');
        $test_name = I('get.test_name');
        $audit_status = I('get.audit_status',0,'int');
        $type = I('get.type');
        $status = I('get.status');
        
        if(isset($type) && $type != ''){
            $where['type'] = $type;
        }
        if(isset($status) && $status != ''){
            $where['status'] = $status;
        }
        // dump($where);
        $where['name'] = array('like',"%$test_name%");
        $where['audit_status'] = $audit_status;
        
        $specifiedUser = D('IsolationData')->specifiedUser(false);
        $where['auth_user_id'] = array('in',$specifiedUser);
	
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$res = M('test')
				->where($where)
				->field("auth_user_id,name,create_user,type,status,audit_status,score,examination_id,address,pass_line,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time")
				->page($start_page,$total_page)
				->order('create_time desc')
				->select();
		}else{
			$res = M('test')->where($where)->page($start_page,$total_page)->order('create_time desc')->select();
		}
    	
		$res = D('IsolationData')->isolationData($res);
        $count = M('test')->where($where)->count();
        $show = $this->pageClass($count,$total_page);

        return array(
            'res'=>$res,
            'page'=>$show,
            'test_name'=>$test_name,
            'type'=>$type,
            'audit_status'=>$audit_status,
            'status'=>$status
        );
    }

    /**
     * 获取所有试卷
     * @return [type] [description]
     */
    public function getAllExmination(){
        $data = I('post.');
        $where['a.status'] = 1;
        $where['a.is_available'] = 1;
        if($data['test_name']){
            $name = $data['test_name'];
            $where['a.test_name'] = array("like","%$name%");
        }
        if($data['test_cate']){
            $where['a.test_cat_id'] = $data['test_cate'];
        }
        //只获取本级+下级数据
        $specifiedUser = D('IsolationData')->specifiedUser(false);
        $where['a.auth_user_id'] = array('in',$specifiedUser);
        
        $res =  M('examination')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION_CATEGORY__ b on a.test_cat_id=b.id')
                ->where($where)
                ->order('a.test_upload_time desc')
                ->field('a.*,b.cat_name')
                ->select();
        
        //$res = D('IsolationData')->isolationData($res);
        
        return array(
            'res'=>$res,
            'test_name'=>$data['test_name'],
            'test_cate'=>$data['test_cate']
        );
    }

    /**
     * 获取所有试卷分类
     * @return [type] [description]
     */
    public function getAllExminationCate(){
        return M('examination_category')->order('sort desc')->select();
    }

    /**
     * 根据组织ID查询所有员工信息
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getStaffById($id){
        $conditions['a.tissue_id'] = array("eq",$id);
        $conditions['b.status'] = array("neq",3);

        $list = M("tissue_group_access a")
            ->field("b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id")
            ->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
            ->where($conditions)
            ->order('a.user_id desc')
            ->select();
        return $list;
    }

    /**
     * 添加考试处理
     */
    public function addTest($data){
        $db = M('test');

        if(strtotime($data['start_time']) > time()){
            $data['status'] = 0;
        }else if(strtotime($data['end_time']) < time()){
            $data['status'] = 2;
        }else{
            $data['status'] = 1;
        }
        $data['type'] = $data['examfs'] == 1 ? 0 : 1;
        $data['examination_id'] = $data['hidTestId'];
        $data['audit_status'] = 1;
        $data['address'] = trim($data['address']);
        $data['create_user'] = $_SESSION['user']['id'];
		$data['score'] = 0;
        $data['orderno'] = D('Trigger')->orderNumber(7);
		$data['auth_user_id'] = session('user.id');
        
        if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('test');
			$data['create_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			$data['start_time'] = array('exp',"to_date('".$data['start_time']."','yy-mm-dd hh24:mi:ss')");
			$data['end_time'] = array('exp',"to_date('".$data['end_time']."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data['create_time'] = date('Y-m-d H:i:s');
		}
		
        $res = $db->add($data);

        if($res){
            $user_id = $data['user_id'];
            foreach($user_id as $k=>$v){
            	if($data['id']){
		        	M('test_user_rel')->where(array('test_id'=>$data['id']))->delete();
				}
                //关联表
                $r = M('test_user_rel')->add(array('test_id'=>$res,'user_id'=>$v));
                if(!$r){
                    return false;
                }
                //考勤表
                $attan_data = array('user_id'=>$v,'status'=>0,'test_id'=>$data['hidTestId'],'examination_id'=>$res);
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$attan_data['id'] = getNextId('examination_attendance');
				}
                $s = M('examination_attendance')->add($attan_data);
                if(!$s){
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取考试信息
     * @param  [type] $id [考试id]
     * @return [type]        [description]
     */
    public function getTestInfo($id){
        $res = M('test')
                ->where(array('id'=>$id))
                ->find();
        // echo M('test')->_sql();
        return $res;
    }

    /**
     * 获取考试发布、参与信息
     * @param  [type] $id [考试id]
     * @return [type]        [description]
     */
    public function getScoreInfo($id){
        $res = M('test')
                ->alias('a')
                ->join('LEFT JOIN __TEST_USER_REL__ d on a.id=d.test_id')
                ->join('LEFT JOIN __EXAM_SCORE__ b on a.id = b.test_id and d.user_id = b.user_id')
                ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ c on a.id=c.examination_id and d.user_id = c.user_id')
                ->field('a.*,b.total_score,b.is_publish,c.status as attendance_status,d.user_id')
                ->where(array('a.id'=>$id))
                ->select();
                // echo M('test')->_sql();
        return $res;
    }

    /**
     * 获取参加考试的所有员工信息
     * @return [type] [description]
     */
    public function getTestuser_ids($id){
        $res = M('test_user_rel')
                ->alias('b')
                ->join('LEFT JOIN __TEST__ a on b.test_id=a.id')
                ->join('LEFT JOIN __USERS__ c on b.user_id = c.id')
                ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ d on b.user_id = d.user_id')
                ->join('LEFT JOIN __TISSUE_RULE__ e on d.tissue_id = e.id')
                ->join('LEFT JOIN __JOBS_MANAGE__ f on d.job_id = f.id')
                ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ g on a.id=g.examination_id and b.user_id=g.user_id')
                ->join('LEFT JOIN __EXAM_SCORE__ h on b.test_id=h.test_id and b.user_id=h.user_id')
                ->distinct(true)
                ->where(array('b.test_id'=>$id))
                ->field('b.*,a.name as test_name,c.username,c.phone,e.name as tissue_name,f.name as job_name,g.status as attendance_status,h.total_score')
                ->select();
                
        return $res;
    }

    /**
     * 发布线上线下考试结果
     * @return [type] [description]
     */
    public function publish($data){
        
		$res = M('exam_score')->where(array('test_id'=>$data['test_id']))->save(array('is_publish'=>1));
        if($res === false){
            return false;
        }
        return true;
    }

    /**
     * 保存成绩
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function saveScore($data){
        $before = M('exam_score')->where(array('test_id'=>$data['test_id']))->select();
        
        if($before){
            foreach($data['user_id'] as $k=>$v){
                $score = !$data['score'][$k] ? 0 : $data['score'][$k];
                $r = M('exam_score')->where(array('test_id'=>$data['test_id'],'user_id'=>$v))->save(array('total_score'=>$score));
                if($r === false){
                    return false;
                }
            }
        }else{
            foreach($data['user_id'] as $k=>$v){
                $score = !$data['score'][$k] ? 0 : $data['score'][$k];
                $info = array('user_id'=>$v,'total_score'=>$score,'test_id'=>$data['test_id']);
                $r = M('exam_score')->add($info);
                if(!$r){
                    return false;
                }
            }
        }
		$t = M('examination_attendance')->where(array('examination_id'=>$data['test_id']))->select();
		if(!$t){
			foreach($data['user_id'] as $k=>$v){
				$info = array('user_id'=>$v,'status'=>1,'examination_id'=>$data['test_id']);
				$s = M('examination_attendance')->add($info);
				if(!$s){
					return false;
				}
			}
		}
        return true;
    }

    /**
     * 获取考勤人员数量
     * @return [type] [description]
     */
    public function getAttendanceNumber($where){
        return M('examination_attendance')->where($where)->count();
    }

    /*
     * 导入成绩处理方法
     * @pamar  $file  导入的文件路径
     * @return $data  导入后经过处理的文件数组
     */
    function uploadScore($file){
        // 拆分数组拼装对应数据表的数组结构
        $list = import_excel($file);
        $data = array();
        foreach($list as $k=>$v){
            if($k > 1 && !empty($v['0'])){
                $data[$k-2]['name'] = $v['0'];
                $data[$k-2]['phone'] = $v['1'];
                $data[$k-2]['score'] = $v['2'];
            }
        }
        return $data;
    }

    /**
     * 根据手机号获取用户ID
     * @return [type] [description]
     */
    public function getuser_idByPhone($phone){
        return M('users')->where(array('phone'=>$phone))->field('id,username')->find();
    }

    /**
     * 获取某一门考试的员工信息
     * tid 考试id
     * user_id 用户id
     * @return [type] [description]
     */
    public function getUserInfo($tid,$user_id){
        $res = M('test_user_rel')
                ->alias('a')
                ->join('LEFT JOIN __TEST__ b on a.test_id=b.id')
                ->join('LEFT JOIN __USERS__ c on a.user_id = c.id')
                ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ d on a.test_id=d.examination_id and a.user_id=d.user_id')
                ->join('LEFT JOIN __EXAM_SCORE__ e on a.user_id=e.user_id and a.test_id=e.test_id')
                ->join('LEFT JOIN __EXAMINATION__ f on b.examination_id = f.id')
                ->where(array('a.test_id'=>$tid,'a.user_id'=>$user_id))
                ->field('a.*,b.name as test_name,c.username,d.status as attendance_status,e.total_score,f.test_score')
                ->find();
                // echo M('test_user_rel')->_sql();
        return $res;
    }

    /**
     * 获取用户答题信息
     * @param  [type] $tid [考试id]
     * @param  [type] $user_id [用户Id]
     * @param  [type] $eid [试卷Id]
	 * @param  [type] $pid [项目ID]
     * @return [type]      [description]
     */
    public function getanswerInfo($tid,$user_id,$eid=null,$pid=null){
        
		$type = M('examination')->where(array('id'=>$eid))->getField('s_type');
		
		$where['a.examination_id'] = $eid;
		if($type == 1){
			$where['a.user_id'] = $user_id;
			if($tid){
				$where['a.test_id'] = $tid;
			}else if($pid){
				$where['a.project_id'] = $pid;
			}
		}
		$res = M('examination_item_rel')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id=b.id')
                ->where($where)
                ->select();

        foreach($res as $k=>$v){
            $info = M('exam_answer')
                    ->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid,'question_number'=>$v['examination_item_id']))
                    ->field('exam_answer,classification,isexam,correct_answer,wdscore,checked')
                    ->find();
            $merge = array_merge($v,$info);
            $res[$k] = $info ? $merge : $v;
        }
        
        $data = array();
        foreach($res as $k=>$v){
            switch ($v['classification']) {
                case 1:
                    $data['dan'][] = $v;
                    break;
                case 2:
                    $data['duo'][] = $v;
                    break;
                case 3:
                    $data['pan'][] = $v;
                    break;
                default:
                    $data['wen'][] = $v;
                    break;
            }
        }
        return $data;
    }

    /**
     * 保存手动阅卷的简答题得分
     * @return [type] [description]
     */
    public function saveAudit(){
        $data = I('post.');
        $db = M('exam_score');
        $whereScore = array('user_id'=>$data['user_id'],'test_id'=>$data['test_id'],'exam_id'=>$data['examination_id']);
        $id = $db->where($whereScore)->find();

        if($id){
            foreach($data['scores'] as $k=>$v){
                if($v==''){
                    continue;
                }
                $before = M('exam_answer')
                    ->where(array('test_id'=>$data['test_id'],'question_number'=>$data['ids'][$k],'u_exam_id'=>$data['user_id']))
                    ->find();//人工阅卷之前的分数信息
                if($before){
                    $info = M('exam_answer')
                        ->where(array('id'=>$before['id']))
                        ->save(array('wdscore'=>$v,'checked'=>1));
                    $db->where(array('id'=>$id['id']))->setDec('total_score',$before['wdscore']);
                    $db->where(array('id'=>$id['id']))->setInc('total_score',$v);
                    if($info === false){
                        return false;
                    }
                }else{
                    $d = array(
                        'exam_id'=>$data['examination_id'],
                        'project_id'=>0,
                        'u_exam_id'=>$data['user_id'],
                        'classification'=>4,
                        'question_number'=>$data['ids'][$k],
                        'test_id'=>$data['test_id'],
                        'wdscore'=>$v,
                        'checked'=>1
                    );
                    M('exam_answer')->add($d);
                    $db->where(array('id'=>$id['id']))->setInc('total_score',$v);
                }
            }
        }else{
            foreach($data['scores'] as $k=>$v){
                if($v==''){
                    continue;
                }
                $answerData = array(
                    'exam_id'=>$data['examination_id'],
                    'project_id'=>0,
                    'u_exam_id'=>$data['user_id'],
                    'classification'=>4,
                    'question_number'=>$data['ids'][$k],
                    'data_tiem'=>date('Y-m-d H:i:s'),
                    'test_id'=>$data['test_id'],
                    'wdscore'=>$v,
                    'checked'=>1
                );
                
                $answerRes = M('exam_answer')->add($answerData);
                if(!$answerRes){
                    return false;
                }

                $scoreData = array(
                    'user_id'=>$data['user_id'],
                    'exam_id'=>$data['examination_id'],
                    'total_score'=>0,
                    'test_id'=>$data['test_id'],
                    'use_time'=>0
                );
                $res = $db->add($scoreData);
                $db->where(array('id'=>$res))->setInc('total_score',$v);
            }
        }

        //考勤
        $whereAtt = array('user_id'=>$data['user_id'],'test_id'=>$data['examination_id'],'examination_id'=>$data['test_id']);
        $att = M('examination_attendance')
        ->where($whereAtt)
        ->find();
        if($att){
            $attRes = M('examination_attendance')->where($whereAtt)->save(array('status'=>1));
            if($attRes===false){
                return false;
            }
        }else{
            $attData = array(
                'user_id'=>$data['user_id'],
                'test_id'=>$data['examination_id'],
                'status'=>1,
                'examination_id'=>$data['test_id']
            );
            $attRes = M('examination_attendance')->add($attData);
            if(!$attData){
                return false;
            }
        }
        return true;
    }

    /**
     * 简答题分数
     * @param  [type] $tid [考试id]
     * @param  [type] $user_id [用户id]
     * @return [type]      [description]
     */
    public function getWdScore($tid,$user_id){
        return M('exam_answer')->where(array('u_exam_id'=>$user_id,'test_id'=>$tid,'classification'=>4))->select();
    }

    /**
     * 根据用户Id获取用户信息
     * @return [type] [description]
     */
    public function getUinfoByuser_id(){
        return M('users')->where(array('id'=>$_SESSION['user']['id']))->find();
    }

    /**
     * 获取试卷详情
     * @param  [type] $id [考试id]
     * @return [type]     [description]
     */
    public function getExamDetail($id){
        $ret = M('test')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION__ b on a.examination_id=b.id')
                ->join('LEFT JOIN __EXAMINATION_CATEGORY__ c on b.test_cat_id=c.id')
                ->where(array('a.id'=>$id))
                ->field('a.*,b.id,b.test_name,b.test_cat_id,b.test_score,b.test_heir,b.test_upload_time,b.status,b.is_available,b.principal,b.test_mode,b.type,c.cat_name')
                ->find();
                
        $db = M('examination_item_rel');
        $singleChoice = $db
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION___item b on a.examination_item_id=b.id')
                ->where(array('a.examination_id'=>$ret['examination_id'],'b.classification'=>1))
                ->select();
        
        $singleChoiceSum = count($singleChoice);
        $singleChoiceTotalScore = $singleChoiceSum * $singleChoice[0]['score'];

        $multipleChoice = $db
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION___item b on a.examination_item_id=b.id')
                ->where(array('a.examination_id'=>$ret['examination_id'],'b.classification'=>2))
                ->select();
        $multipleChoiceSum = count($multipleChoice);
        $multipleChoiceTotalScore = $multipleChoiceSum * $multipleChoice[0]['score'];

        $descriPtive = $db
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION___item b on a.examination_item_id=b.id')
                ->where(array('a.examination_id'=>$ret['examination_id'],'b.classification'=>3))
                ->select();
        $descriPtiveChoiceSum = count($descriPtive);
        $descriPtiveChoiceTotalScore = $descriPtiveChoiceSum * $descriPtive[0]['score'];

        $wd = $db
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION___item b on a.examination_item_id=b.id')
                ->where(array('a.examination_id'=>$ret['examination_id'],'b.classification'=>4))
                ->select();
        $wdSum = count($wd);
        $wdTotalScore = $wdSum * $wd[0]['score'];

        return $data = array(
           //详情
           "detail" => $ret,
           //单选
           "singleChoice" => $singleChoice,
           "singleChoiceSum" => $singleChoiceSum,
           "singleChoiceTotalScore" => $singleChoiceTotalScore,
           //多选
           "multipleChoice" => $multipleChoice,
           "multipleChoiceSum" => $multipleChoiceSum,
           "multipleChoiceTotalScore" => $multipleChoiceTotalScore,
           //判断
           "descriPtive" => $descriPtive,
           "descriPtiveChoiceSum" => $descriPtiveChoiceSum,
           "descriPtiveChoiceTotalScore" => $descriPtiveChoiceTotalScore,
           //问答
           'wd'=>$wd,
           'wdTotalScore'=>$wdTotalScore,
           'wdSum'=>$wdSum
       );

    }

    public function all(){
        $test_id = I('get.test_id');
        $data = $this->getTestuser_ids($test_id);
        $name = $data[0]['test_name'];
        foreach($data as $k=>$v){
            if($v['tissue_name'] == ''){
                $data[$k]['tissue_name'] = '未分配';
            }
            if($v['job_name'] == ''){
                $data[$k]['job_name'] = '未分配';
            }
            if(!$v['total_score']){
                $data[$k]['total_score'] = '0';
            }
            unset($data[$k]['test_id']);unset($data[$k]['user_id']);unset($data[$k]['test_name']);
			unset($data[$k]['attendance_status']);
        }
        return array('name'=>$name,'data'=>$data);
    }

    /**
     * 获取所有公司的部门、人员信息
     * @return [type]      [description]
     */
    public function getCompanys(){
        /*

        $post = I('post.');

        $tissue_id = I('get.tissue_id');

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

        //获取部门下的人
        $pkMember_list = $this->PeopleData($level,$items);

        //获取组织层级数据
        if($level == 1){

            $levelData = $this->levelData(2,$items);

        }


        $data = array(
            "userlist"=>$pkMember_list,
            "items"=>$items,
            "level"=>$level,
            "levelData"=>$levelData,
            "pkMember_list"=>$pkMember_list,
            "tissue_id"=>$tissue_id
        );

        return $data;*/
        return array();
    }

    /**
     * 获取组织层级数据
     */
    public function levelData($level,$items,&$data){

        foreach($items['_data'] as $item){

            if($item['_level'] == $level){

                $data[$item['id']] = $item;

            }else{

                $this->levelData($level,$item,$data);

            }

        }

        return $data;

    }

    /**
     * 取出部门和人
     */
    public function PeopleData($level,&$data,&$pkMember_list){

        $level_arr = array(1=>3,2=>2,3=>1,4=>1);

        foreach($data['_data'] as $item){

            if($item['_level'] == $level_arr[$level]){

                $pkMember_list[$item['id']] = $this->tissuePeople($item);

            }else{

                $this->PeopleData($level,$item,$pkMember_list);

            }

        }

        return $pkMember_list;

    }

    /**
     * 查询PK人 从组织架构上取人
     */
    public function tissuePeople($item){

        $condition['a.tissue_id'] = array("in",$item['id']);

        $condition['b.id'] = array('NEQ','NULL');
        $condition['b.status'] = array('NEQ',3);

        $user_list = M("tissue_group_access a")
        		->field("b.id,b.username,b.phone,b.job_number,c.name")
        		->join("LEFT JOIN __USERS__ b ON a.user_id = b.id LEFT JOIN __JOBS_MANAGE__ c ON a.job_id = c.id")
        		->where($condition)
        		->select();

        $items = array();

        foreach($user_list as $k=>$user){

            $where['user_id'] = array("eq",$user['id']);

            $tag_list = M("users_tag_relation a")
            		->field("b.tag_title")
            		->join("LEFT JOIN __USERS_TAG__ b ON a.tag_id = b.id")
            		->where($where)
            		->select();

            $tag_arr = array();

            foreach($tag_list as $tag){
                $tag_arr[] = $tag['tag_title'];
            }

            $str_tag = implode(",",$tag_arr);

            $items[$k] = $user;

            $items[$k]['tag'] = $str_tag;

        }

        return $items;

    }

	/**
	 * 根据ID获取考试信息
	 */
	public function getTestInfoById($id){
		$res = M('test')->where(array('id'=>$id))->find();
		return $res;
	}
	
	/**
	 * 获取考试的用户信息
	 */
	public function getUinfo($id){
		return M('test_user_rel a')
				->join('left join __USERS__ b on a.user_id=b.id')
				->field('b.id,b.username')
				->where(array('a.test_id'=>$id))
				->select();
	}
	
	/**
	 * 试卷信息
	 */
	public function getEinfo($id){
		return M('test a')
				->join('left join __EXAMINATION__ b on a.examination_id=b.id')
				->field('b.id,b.test_name')
				->where(array('a.id'=>$id))
				->find();
	}


}