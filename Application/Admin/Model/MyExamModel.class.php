<?php
namespace Common\Model;

use Common\Model\BaseModel;

/**
 * ------------------------------------------------------------------------------
 * Description  我的考试操作模型
 * @filename MyExamModel.class.php
 * @author Andy
 * @datetime 2016-1-5 11:37:29
 * -------------------------------------------------------------------------------
 */
class MyExamModel extends BaseModel{
    /*
     * 初始化
     */
    function __construct(){
         
    }
 
    /*
     *我的考试列表
     */
    
    public function examList($total_page = 10){
       
        $start_page = I("get.p",1,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";

       //获取已经过审核的项目
        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);
        $where['b.type'] = array("in",'0,4');

        if(!empty($keyword)){
            $where['_string']="(b.project_name like '%".$keyword."%')  OR (d.test_name like '%".$keyword."%')";
        }

        // $where['e.status'] = array('in','0,1');

        
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.project_id,b.project_name,c.test_id,to_char(c.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(c.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.test_length,c.test_names,e.status,d.test_name,d.test_score,f.total_score";
		}else{
			$field = 'a.project_id,b.project_name,c.test_id,c.start_time,c.end_time,c.test_length,c.test_names,e.status,d.test_name,d.test_score,f.total_score';
		}
		$result = M("designated_personnel a")
            ->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id")
            ->join('LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id = c.project_id')
            ->join('LEFT JOIN __EXAMINATION__ d on c.test_id = d.id')
            ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ e ON d.id = e.test_id AND b.id = e.project_id and e.user_id=a.user_id')
			->join('LEFT JOIN __EXAM_SCORE__ f on a.user_id=f.user_id and a.project_id=f.project_id and d.id=f.exam_id')
            ->where($where)
            ->order('c.start_time desc')
            ->field($field)
            ->page($start_page,$total_page)
            ->select();
        foreach($result as $k=>$v){
            if(strtotime($v['start_time']) > time()){
                $result[$k]['statusinfo'] = '未开始';
            }else if(strtotime($v['end_time']) < time()){
                $result[$k]['statusinfo'] = '已结束';
            }else{
                $result[$k]['statusinfo'] = '进行中';
            }

            //已考
            if($v['status'] == 1){
                $result[$k]['statusinfo'] = '已结束';
            }

            if(!$v['start_time']){
                unset($result[$k]);
            }
        }

        $left = $middle = $right = array();
        foreach($result as $k=>$v){
            if($v['statusinfo'] == '已结束'){
                $right[] = $result[$k];
            }else if($v['statusinfo'] == '进行中'){
                $left[] = $result[$k];
            }else if($v['statusinfo'] == '未开始'){
                $middle[] = $result[$k];
            }
        }
        $final = array_merge($left,$middle,$right);

		$total = M("designated_personnel a")
            ->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id")
            ->join('LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id = c.project_id')
            ->join('LEFT JOIN __EXAMINATION__ d on c.test_id = d.id')
            ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ e ON d.id = e.test_id AND b.id = e.project_id and e.user_id=a.user_id')
            ->where($where)
            ->field("a.*")
            ->select();
        $total = count($total);
       //输出分页
        $show=$this->pageClass($total,$total_page);
        $data = array(
            'page' => $show,
            'map' => $final,
            'keyword'=>$keyword,
            'count'=>$total
        );
       return $data;
    }
    
    /**
     * 考试管理-我的考试
     * @param  integer $total_page [description]
     * @return [type]              [description]
     */
    public function examList2($total_page = 10){
        $name = I('get.keyword');
        $start_page = I("get.p",0,'int');

        if($name){
            $where['a.name'] = array('like',"%$name%");
        }
        $where['b.user_id'] = $_SESSION['user']['id'];
        $where['a.audit_status'] = 0;
        $db = M('test');
        
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.name,a.type,a.status,a.audit_status,a.score,a.examination_id,a.address,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.status as attendance_status";
		}else{
			$field = "a.*,c.status as attendance_status";
		}
		
		$res = $db
            ->alias('a')
            ->join('left join __TEST_USER_REL__ b on a.id=b.test_id')
            ->join('left join __EXAMINATION_ATTENDANCE__ c on c.examination_id=b.test_id and c.user_id=b.user_id')
            ->where($where)
            ->field($field)
            ->order('a.start_time desc')
            ->page($start_page,$total_page)
            ->select();
        foreach($res as $k=>$v){
            $res[$k]['times'] = floor((strtotime($v['end_time'])-strtotime($v['start_time']))/60);

            if(strtotime($v['start_time']) > time()){
                $res[$k]['statusinfo'] = '未开始';
            }else if(strtotime($v['end_time']) < time()){
                $res[$k]['statusinfo'] = '已结束';
            }else{
                $res[$k]['statusinfo'] = '进行中';
            }

            //已考
            if($v['attendance_status'] == 1){
                $res[$k]['statusinfo'] = '已结束';
            }

        }
        $left = $middle = $right = array();
        foreach($res as $k=>$v){
            if($v['statusinfo'] == '已结束'){
                $right[] = $res[$k];
            }else if($v['statusinfo'] == '进行中'){
                $left[] = $res[$k];
            }else if($v['statusinfo'] == '未开始'){
                $middle[] = $res[$k];
            }
        }
        $final = array_merge($left,$middle,$right);
        
        $count = $db
                ->alias('a')
                ->join('left join __TEST_USER_REL__ b on a.id=b.test_id')
                ->where($where)
                ->count();
        $show = $this->pageClass($count,$total_page);

        return array(
            'data'=>$final,
            'keyword'=>$name,
            'page'=>$show,
            'count'=>$count
        );
    }
	
	/**
	 * 全部考试。（项目考试+其他考试）
	 */
	public function examList3(){
		$start_page = I("get.p",1,'int');
		$total_page = 10;
        $keyword=I("get.keyword")?I("get.keyword"):"";

       //获取已经过审核的项目
        $where1['a.user_id'] = array("eq",$_SESSION['user']['id']);
        $where1['b.type'] = array("in",'0,4');

        if(!empty($keyword)){
            $where1['_string']="(b.project_name like '%".$keyword."%')  OR (d.test_name like '%".$keyword."%')";
        }

        // $where['e.status'] = array('in','0,1');
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.project_id,b.project_name,c.test_id,to_char(c.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(c.start_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.test_length,c.test_names,e.status,d.test_name,d.test_score,f.total_score";
		}else{
			$field = "a.project_id,b.project_name,c.test_id,c.start_time,c.end_time,c.test_length,c.test_names,e.status,d.test_name,d.test_score,f.total_score";
		}
        $result = M("designated_personnel a")
                ->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id")
                ->join('LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id = c.project_id')
                ->join('LEFT JOIN __EXAMINATION__ d on c.test_id = d.id')
                ->join('LEFT JOIN __EXAMINATION_ATTENDANCE__ e ON d.id = e.test_id AND b.id = e.project_id and e.user_id=a.user_id')
				->join('LEFT JOIN __EXAM_SCORE__ f on a.user_id=f.user_id and a.project_id=f.project_id and d.id=f.exam_id')
                ->where($where1)
                ->order('c.start_time desc')
                ->field($field)
                ->select();
		foreach($result as $k=>$v){
			if($v['test_id'] != '0'){
				$result[$k]['type'] = '线上';
			}else{
				$result[$k]['type'] = '线下';
			}
            if(strtotime($v['start_time']) > time()){
                $result[$k]['statusinfo'] = '未开始';
            }else if(strtotime($v['end_time']) < time()){
                $result[$k]['statusinfo'] = '已结束';
            }else{
                $result[$k]['statusinfo'] = '进行中';
            }

            //已考
            if($v['status'] == 1){
                $result[$k]['statusinfo'] = '已结束';
            }

            if(!$v['start_time']){
                unset($result[$k]);
            }
        }

		
        if($keyword){
            $where2['a.name'] = array('like',"%$keyword%");
        }
        $where2['b.user_id'] = $_SESSION['user']['id'];
        $where2['a.audit_status'] = 0;
        $db = M('test');
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.name,a.create_user,a.type,a.status,a.audit_status,a.examination_id,a.auth_user_id,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,c.status as attendance_status,d.test_score,e.total_score";
		}else{
			$field = "a.*,c.status as attendance_status,d.test_score,e.total_score";
		}
        $res = $db
            ->alias('a')
            ->join('left join __TEST_USER_REL__ b on a.id=b.test_id')
            ->join('left join __EXAMINATION_ATTENDANCE__ c on c.examination_id=b.test_id and c.user_id=b.user_id')
			->join('LEFT JOIN __EXAMINATION__ d on c.test_id=d.id')
			->join('LEFT JOIN __EXAM_SCORE__ e on d.id=e.exam_id and b.user_id=e.user_id and b.test_id=e.test_id')
            ->where($where2)
            ->field($field)
            ->order('a.start_time desc')
            ->select();
		foreach($res as $k=>$v){
            $res[$k]['times'] = floor((strtotime($v['end_time'])-strtotime($v['start_time']))/60);
			
			if($v['type'] == 1){
				$res[$k]['type'] = '线下';
			}else{
				$res[$k]['type'] = '线上';
			}
            if(strtotime($v['start_time']) > time()){
                $res[$k]['statusinfo'] = '未开始';
            }else if(strtotime($v['end_time']) < time()){
                $res[$k]['statusinfo'] = '已结束';
            }else{
                $res[$k]['statusinfo'] = '进行中';
            }

            //已考
            if($v['attendance_status'] == 1){
                $res[$k]['statusinfo'] = '已结束';
            }

        }
		
		$count = count($result) + count($res);
		$list = array_merge($result,$res);
		$left = $middle = $right = array();
        foreach($list as $k=>$v){
            if($v['statusinfo'] == '已结束'){
                $right[] = $list[$k];
            }else if($v['statusinfo'] == '进行中'){
                $left[] = $list[$k];
				/*if($v['type'] == 1 || $v['test_id'] == 0){//线下考试
                	$right[] = $list[$k];
				}else{
					$left[] = $list[$k];
				}*/
            }else if($v['statusinfo'] == '未开始'){
                $middle[] = $list[$k];
            }
        }
        $final = array_merge($left,$middle,$right);
		
		$data = array_slice($final,($start_page - 1) * $total_page,$total_page);
		$show = $this->pageClass($count,$total_page);

		return array(
            'data'=>$data,
            'keyword'=>$name,
            'page'=>$show
        );
    }
    /*
     *我的考试列表(结束考试列表)
     */
    
    public function examEnd($total_page = 10){
    
        $start_page = I("get.p",0,'int');
        $user_id = $_SESSION['user']['id'];
        $keyword=I("get.keyword")?I("get.keyword"):"";
        //判断当前学员在不在指定人员列表中
        $where = array(
            //'a.user_id' => $user_id,
            'b.type' => 4,
        );
         
        $ret = M('Designated_personnel a')
                ->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __PROJECT_EXAMINATION__ c ON b.id = c.project_id")
                ->where($where)
                ->field('c.project_id,b.project_name,c.specific_information')
                ->select();

        $count = 0;
        $items = array();
        $i = 0;
    
        //$table = array();
        foreach($ret as $k=>$item){
            //json数据格式转化为php数组格式
            $list = json_decode($item['specific_information']);
            foreach($list as $id=>$v){
                if($this->is_project($id,$item['project_id']) == 1){
                    $items[$i]['id'] = $id;
                    $items[$i]['project_id'] = $item['project_id'];
                    $items[$i]['project_name'] = $item['project_name'];
                    $items[$i]['test_name'] = $v->test_name;
                    $items[$i]['user_id'] = $v->user_id;
                    $items[$i]['start_time'] = $v->start_time;
                    $items[$i]['end_time'] = $v->end_time;
                    $items[$i]['exam_time_long'] = $this->getExamTimeLong($v->start_time,$v->end_time);//计算时长
                    $items[$i]['exam_status'] = $this->getEndExamStatus($v->start_time,$v->end_time,$id,$item['project_id']);//判断状态
                    $items[$i]['score'] = $v->score;
                    $i++;
                }
            }
        }
       
        $count = count($items);
        //输出分页
        $show=$this->pageClass($count,$total_page);
        return $data = array(
            'page' => $show,
            'map' => $items,
            'keyword'=>$keyword
        );
    }
    
    
    /*
     * 获取考试结果
     */
    public function getExamResult(){

        $get['t_id'] = I('get.test_id');
        $get['p_id'] = I('get.project_id');
        $flag = I('get.flag');
        //考试时间
        if($flag){
            $data_time = M('test')->field("start_time")
                        ->where(array("id"=>$get['t_id']))->find();
        }else{
            $data_time = M('project_examination')->field("start_time")
                        ->where(array("test_id"=>$get['t_id'],"project_id"=>$get['p_id']))->find();
        }
        //试卷
        $ret = M('Examination')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id')
                ->where('a.id ='.$get['t_id'])
                ->field('a.*,b.cat_name')
                ->find();
        if(!$flag){
            $admin_project = M('admin_project')->where(array("id"=>$get['p_id']))->find();//关联项目
            //试卷状态
            $examination_attendance = M('examination_attendance')
                                    ->where(array("test_id"=>$get['t_id'],"project_id"=>$get['p_id']))->find();
        }else{
            //试卷状态
            $examination_attendance = M('examination_attendance')
                                    ->where(array("examination_id"=>$get['t_id']))->find();
        }


        if(empty($examination_attendance)){
            $examination_item = M('examination_item_rel')
                                ->alias('a')
                                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_id=b.id')
                                ->field("b.*")
                                ->where(array('a.examination_id'=>$get['t_id']))
                                ->select();
        }else{//试题
            $examination_item = M('examination_item_rel')
                                ->alias('a')
                                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_id=b.id')
                                ->join("LEFT JOIN __EXAM_ANSWER__ c ON a.examination_id = c.exam_id")
                                ->field("b.*,c.isexam,c.exam_answer,c.correct_answer")
                                ->where(array('a.examination_id'=>$get['t_id']))
                                ->select();
        }

        $topic_total = array();
        $three = $two = $one = $four = 1;
        $total_score = 0;

        //统计题型总数
        foreach($examination_item as $item){
            if($item['classification'] == 1){
                $topic_total['one'] = $one;
                $one++;
            }elseif($item['classification'] == 2){
                $topic_total['two'] = $two;
                $two++;
            }else if($item['classification'] == 3){
                $topic_total['three'] = $three;
                $three++;
            }else{
                $topic_total['four'] = $four;
                $four++;
            }
            if($item['isexam'] == 1){
                $total_score += $item['item_score'];
            }
        }

        $items = array(
            'flag'=>$flag,
            "test_name"=>$ret['test_name'],
            "test_score"=>$ret['test_score'],
            "cat_name"=>$ret['cat_name'],
            "start_time"=>$ret['start_time'],
            "status"=>$examination_attendance['status'],
            "project_name"=>$admin_project['project_name'],
            "examination_item"=>$examination_item,
            "topic_total"=>$topic_total,
            "data_time"=>$data_time['start_time'],
            "total_score"=>$total_score
        );

        return $items;
       
    }
    
    /**
     *获取试卷题目和答案
     */
    public function getExamQuestionAnswer($get){
        //试卷id
        $id = $get['t_id'];
        //关联项目id
        $p_id = $get['p_id'];
        $p_name = $get['p_name'] ? $get['p_name'] :'';
        //试卷详情
        $exam = M('Examination');
        $ret = $exam->alias('a')->join('LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id AND a.id ='.$id) ->field('a.*,b.cat_name')->find();
        $ret['project_name'] = $p_name;
        $ret['project_id'] = $p_id;
         
        //试卷单选题题目
        $singleChoice = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=1 AND b.id =$id")->field("a.*")->select();
        $singleChoiceTotalScore = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=1 AND b.id =$id")->field("a.*")->sum('item_score');
        $singleChoiceSum = count($singleChoice);
    
        //试卷多选题题目
        $multipleChoice = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=2 AND b.id =$id")->field("a.*")->select();
        $multipleChoiceTotalScore = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=2 AND b.id =$id")->field("a.*")->sum('item_score');
        $multipleChoiceSum = count($multipleChoice);
        //试卷判断题题目
        $descriPtive = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=3 AND b.id =$id")->field("a.*")->select();
        $descriPtiveChoiceTotalScore = M('Examination_item')->alias('a')->join("LEFT JOIN __EXAMINATION__ b ON a.examination_id = b.id")->where("a.classification=3 AND b.id =$id")->field("a.*")->sum('item_score');
        $descriPtiveChoiceSum = count($descriPtive);
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
            "descriPtiveChoiceTotalScore" => $descriPtiveChoiceTotalScore
        );
    }
    /*
     * 计算考试时长
     */
    public function getExamTimeLong($start_time,$end_time){
        //把格式化日期转化为UINX时间戳
        $start = strtotime($start_time);//开始时间
        $end = strtotime($end_time);//结束时间
        //计算时间长（分钟）
        $timeLong = floor(($end - $start)/60);
        return $timeLong;
    }


    
    /*
     * 待考试计算考试状态
     */
     
    public function getExamStatus($start_time,$end_time,$id,$project_id){
        //把格式化日期转化为UINX时间戳
        $start = strtotime($start_time);//开始时间
        $end = strtotime($end_time);//结束时间
        $time = time();//当前时间
         
        //如果开始开始时间小于当前时间待考试
        $st = floor($time - $start);
        $te = floor($time - $end);
        if($st < 0 && $this->is_project($id,$project_id) == 0){
            return  $info = 0;//待考试
        }elseif($st > 0 && $te < 0 && $this->is_project($id,$project_id) == 0){
            return $info = 1;//进行中
        }
    }
    
    /*
     * 已结束考试计算考试状态
     */
    public function getEndExamStatus($start_time,$end_time,$id,$project_id){
        //把格式化日期转化为UINX时间戳
        $start = strtotime($start_time);//开始时间
        $end = strtotime($end_time);//结束时间
        $time = time();//当前时间
         
        //如果开始开始时间小于当前时间待考试
        $st = floor($time - $start);
        $te = floor($time - $end);
        if($st > 0 && $te < 0 && $this->is_project($id,$project_id) == 1){
            return $info = 3;//已考试
        }elseif($te > 0 && $this->is_project($id,$project_id) == 0){
            return $info = 2;////已逾期
             
        }
    
    }
    /**
     * 判断是否已经提交过试卷
     */
    public function is_project($exam_id,$project_id){
        $data = array(
            "test_id"=>$exam_id,
            "project_id"=>$project_id,
            "user_id"=>$_SESSION['user']['id']
        );
        
        $is_return = M('Examination_attendance')->where($data)->getField('status');
        return $is_return;
    }
    
    
    /**
     * 获取试卷题目
     * @param  [type]  $get  [description]
     * @param  boolean $flag [标记是否是非关联项目的考试]
     * @return [type]        [description]
     */
    public function getExamInfo($get,$flag=false){

        //其他考试  test_id考试id  examination_id试卷id
        //项目考试  project_id项目id  examination_id试卷id
        $id = $get['examination_id'];//试卷id
        $pid = $get['project_id'];//项目id
        $tid = $get['test_id'];//考试id
        
        //根据组卷规则判断是读取试题信息还是实时组卷
        $s_type = M('Examination')->where(array('id'=>$get['examination_id']))->field('s_type,number_score')->find();
		$info = explode(',',$s_type['number_score']);

        //项目关联名称
        if($pid){
        	if(strtolower(C('DB_TYPE')) == 'oracle'){
				$p_name = M('admin_project')
						->where('id='.$pid)
						->field("project_name,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time")
						->find();
			}else{
				$p_name = M('admin_project')->where('id='.$pid) ->field('project_name,end_time')->find();
			}
        }

        if($p_name && (strtotime($p_name['end_time']) < time())){
        	return false;
        }else{
            //试卷详情
            $exam = M('Examination');
			
			
            if(!empty($flag)){
            	if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "a.test_length,a.id,a.name,a.type,a.status,a.audit_status,a.score,a.examination_id,a.address,a.pass_line,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.test_score";
				}else{
					$field = "a.*,b.test_score";
				}
				$ret = M('test a')
                    ->join('left join __EXAMINATION__ b on a.examination_id=b.id')
                    ->where(array('a.id'=>$get['test_id']))
                    ->field($field)
                    ->find();
            }else{
            	if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "a.test_name,a.test_cat_id,a.test_score,a.test_heir,a.status,a.is_available,a.principal,a.type,a.pass_line,a.passing_score,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,a.id as examination_id,b.cat_name";
				}else{
					$field = "a.*,a.id as examination_id,b.cat_name";
				}
				$ret = $exam
                    ->alias('a')
                    ->join('LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id')
                    ->where("a.id =".$id)
                    ->field($field)
                    ->find();
                $ret['project_name'] = $p_name['project_name'];
                $ret['project_id'] = $pid;
                 //获取考试时间
                if(strtolower(C('DB_TYPE')) == 'oracle'){
                	$field2 = "to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,test_length";
                }else{
                	$field2 = "start_time,end_time,test_length";
                }
                $start_time = M('project_examination')
                                ->where(array("project_id"=>array("eq",$pid),"test_id"=>array("eq",$id)))
                                ->field($field2)
                                ->find();
				$ret['test_length'] = $start_time['test_length'];
                $ret['start_time'] = $start_time['start_time'];
                $ret['end_time'] = $start_time['end_time'];               
            }
			
			$a = array(
				'examination_id'=>$id,
				'user_id'=>session('user.id')
			);
			if($pid){
				$a['project_id'] = $get['project_id'];
			}else{
				$a['test_id'] = $get['test_id'];
			}
			$r = M('examination_item_rel')
				->where($a)
				->find();
			
			//单选题
			if($s_type['s_type'] == 1){
				if(!$r){
					$random_dan_ids = $this->random_examinations($info[0],1,$info[1],array());
					foreach($random_dan_ids as $k=>$v){
						$singleChoice[$k] = M('examination_item')->where(array('id'=>$v))->find();
						$singleChoice[$k]['score'] = $info[1];
						
						$examItemRelData = array(
							'examination_id' => $id,
							'examination_item_id' => $v,
							'score' => $info[1],
							'user_id'=>session('user.id'),
							'test_id'=>$get['test_id'],
							'project_id'=>$get['project_id']
						);
						M('examination_item_rel')->add($examItemRelData);
					}
				}
			}
			if($pid){
				$singleChoice = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>1))
                ->select();
			}else{
				$singleChoice = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>1))
                ->select();
			}
			
            if($singleChoice){
                $singleChoiceSum = count($singleChoice);
                foreach($singleChoice as $k=>$v){
                    $singleChoiceTotalScore += $v['score'];
                }
            }else{
                $singleChoiceSum = $singleChoiceTotalScore = 0;
            }

            //多选题
            if($s_type['s_type'] == 1){
				if(!$r){
					$random_duo_ids = $this->random_examinations($info[2],2,$info[3],array());
					foreach($random_duo_ids as $k=>$v){
						$multipleChoice[$k] = M('examination_item')->where(array('id'=>$v))->find();
						$multipleChoice[$k]['score'] = $info[3];
						$examItemRelData = array(
							'examination_id' => $id,
							'examination_item_id' => $v,
							'score' => $info[3],
							'user_id'=>session('user.id'),
							'test_id'=>$get['test_id'],
							'project_id'=>$get['project_id']
						);
						M('examination_item_rel')->add($examItemRelData);
					}
				}
			}
			if($pid){
				$multipleChoice = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>2))
                ->select();
			}else{
				$multipleChoice = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>2))
                ->select();
			}
			
            if($multipleChoice){
                $multipleChoiceSum = count($multipleChoice);
                foreach($multipleChoice as $k=>$v){
                    $multipleChoiceTotalScore += $v['score'];
                }
            }else{
                $multipleChoiceSum = $multipleChoiceTotalScore = 0;
            }
			
			//判断题
			if($s_type['s_type'] == 1){
				if(!$r){
					$random_pan_ids = $this->random_examinations($info[4],3,$info[5],array());
					foreach($random_pan_ids as $k=>$v){
						$descriPtive[$k] = M('examination_item')->where(array('id'=>$v))->find();
						$descriPtive[$k]['score'] = $info[5];
						$examItemRelData = array(
							'examination_id' => $id,
							'examination_item_id' => $v,
							'score' => $info[5],
							'user_id'=>session('user.id'),
							'test_id'=>$get['test_id'],
							'project_id'=>$get['project_id']
						);
						M('examination_item_rel')->add($examItemRelData);
					}
				}
			}
			if($pid){
				$descriPtive = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>3))
                ->select();
			}else{
				$descriPtive = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>3))
                ->select();
			}
			
            if($descriPtive){
                $descriPtiveChoiceSum = count($descriPtive);
                foreach($descriPtive as $k=>$v){
                    $descriPtiveChoiceTotalScore += $v['score'];
                }
            }else{
                $descriPtiveChoiceSum = $descriPtiveChoiceTotalScore = 0;
            }
			
            //问答
            if($s_type['s_type'] == 1){
				if(!$r){
					$random_jian_ids = $this->random_examinations($info[6],4,$info[7],array());
					foreach($random_jian_ids as $k=>$v){
						$wd[$k] = M('examination_item')->where(array('id'=>$v))->find();
						$wd[$k]['score'] = $info[7];
						$examItemRelData = array(
							'examination_id' => $id,
							'examination_item_id' => $v,
							'score' => $info[7],
							'user_id'=>session('user.id'),
							'test_id'=>$get['test_id'],
							'project_id'=>$get['project_id']
						);
						M('examination_item_rel')->add($examItemRelData);
					}
				}
			}
			if($pid){
				$wd = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>4))
                ->select();
			}else{
				$wd = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>4))
                ->select();
			}
			
            if($wd){
                $wdSum = count($wd);
                foreach($wd as $k=>$v){
                    $wdTotalScore += $v['score'];
                }
            }else{
                $wdSum = $wdTotalScore = 0;
            }


            return $data = array(
                //详情
                "detail" => $ret,
                'flag'=>$flag,
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


    }
    
    
    /*
     * 处理考试结果
     */
    public function handelExamResult($post){

        if($post['flag']){
            $m = $post['examination_id'];     //试卷id
            $post['examination_id'] = $post['test_id'];     //考试id
            $post['test_id'] = $m;

            $credit = M('test')->where(array('id'=>$post['examination_id']))->field('score')->find();
            $score = $credit['score'];
        }else{
            $post['test_id'] = $post['examination_id'];
            $credit = M('project_examination')
                        ->where(array('project_id'=>$post['project_id'],'test_id'=>$post['test_id']))
                        ->field('credits')->find();
            $score = $credit['credits'];
        }


        try {
            //单选题
            foreach($post['singleChoiceAnswer'] as $k=>$item){
                $this->isexam($post,$k,$item,1);
            }

            //多选题
            foreach($post['tag'] as $k=>$item){
                $str_tag = implode(",",$item);
                $this->isexam($post,$k,$str_tag,2);
            }


            //判断题
            foreach($post['direction'] as $k=>$item){
                $this->isexam($post,$k,$item,3);
            }

            //问答题
            foreach($post['wd'] as $k=>$v){
                $score1 = 0;
                $info = M('examination_item a')
                            ->join('left join __EXAMINATION_ITEM_REL__ b on a.id=b.examination_item_id')
                            ->where(array('a.id'=>$k,'b.examination_id'=>$post['test_id']))
                            ->field('a.keywords,b.score')
                            ->find();

                if($info){
                    $arr = explode(',',$info['keywords']);
                    $m = $info['score'] / count($arr);  //每个关键字的分数
                    foreach($arr as $kk=>$vv){
                        if(strpos($v,$vv) !== false){
                            $score1 += $m;
                        }
                    }
                }
                $score1 = round($score1);
                $i['exam_id'] = $post['test_id'];
                $i['project_id'] = $post['project_id'];
                $i['u_exam_id'] = $post['user_id'];
                $i['exam_answer'] = $v;
                $i['classification'] = 4;
                $i['question_number'] = $k;
                $i['test_id'] = $post['examination_id'];
                $i['wdscore'] = $score1;
                
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$i['id'] = getNextId('auth_group');
					$i['data_tiem'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
				}else{
                	$i['data_tiem'] = date('Y-m-d H:i:s');
				}
                $res = M('exam_answer')->add($i);
                
                if($res == false){
                    return false;
                }

                if($post['flag']){
                    $res = M('exam_score')->where(array('test_id'=>$post['examination_id'],'user_id'=>$post['user_id']))->find();
                    
                    if(!$res){
                        $arr = array(
                            'user_id'=>$post['user_id'],
                            'exam_id'=>$post['test_id'],
                            'total_score'=>$score,
                            'test_id'=>$post['examination_id']
                        );
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$arr['id'] = getNextId('exam_score');
						}
                        $id = M('exam_score')->add($arr);
                    }else{
                        M('exam_score')->where(array('id'=>$res['id']))->setInc('total_score',$score1);
                    }
                }else{
                    $res = M('exam_score')->where(array('exam_id'=>$post['test_id'],'user_id'=>$post['user_id'],'project_id'=>$post['project_id']))->find();
                    
                    if(!$res){
                        $arr = array(
                            'user_id'=>$post['user_id'],
                            'exam_id'=>$post['test_id'],
                            'total_score'=>$score,
                            'project_id'=>$post['project_id']
                        );
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$arr['id'] = getNextId('exam_score');
						}
                        $id = M('exam_score')->add($arr);
                    }else{
                        M('exam_score')->where(array('id'=>$res['id']))->setInc('total_score',$score1);
                    }
                }
            }

            $data = array(
                'user_id'=>$post['user_id'],
                'test_id'=>$post['test_id'],
                'project_id'=>$post['project_id'],
                "status"=>1
            );

            if($post['flag']){
                $data['examination_id'] = $post['examination_id'];

                $info = M('examination_attendance')
                        ->where(array('examination_id'=>$post['examination_id'],'user_id'=>$post['user_id']))
                        ->field('id')
                        ->find();
            }else{
                $info = M('examination_attendance')
                        ->where(array('project_id'=>$post['project_id'],'user_id'=>$post['user_id'],'test_id'=>$post['test_id']))
                        ->field('id')
                        ->find();
            }

            if($info){
                $res = M('examination_attendance')->where(array('id'=>$info['id']))->save(array('status'=>1));
                if($res === false){
                    return false;
                }
            }else{
            	if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['id'] = getNextId('examination_attendance');
				}
                $res = M('Examination_attendance')->data($data)->add();
            }

            //学分处理
            $this->creditAdd(0,$score,$post['test_id'],$post['project_id']);
            if($res){
                return true;
            }
        } catch ( Exception $e ) {
            $results = false;
        }
        return $results;
    }

    /**
     * 验证试卷答案
     */
    public function isexam($post,$k,$item,$typeid){

        $examination_item = M('examination_item')
                            ->where(array('id'=>$k))
                            ->field('right_option')
                            ->find();

        if($examination_item['right_option'] == $item){
            $isexam = 1;//答案正确
        }else{
            $isexam = 0;
        }

        $itea = array();
        $itea['u_exam_id'] = $post['user_id'];//考试人id
        $itea['exam_id'] = $post['test_id'];//试卷id
        $itea['project_id'] = $post['project_id'];//关联项目id
        $itea['exam_answer'] = $item;//题目答案
        $itea['classification'] = $typeid;//题目类型 1单选 2多选 3判断 4简答题
        $itea['question_number'] = $k;//题目序号
        $itea['isexam'] = $isexam;
        $itea['test_id'] = $post['examination_id'];    //考试id
        $itea['correct_answer'] = $examination_item['right_option'];
        $itea['data_tiem'] = date('Y-m-d H:i:s');

        $score = M('examination_item_rel')
                    ->where(array('examination_id'=>$post['test_id'],'examination_item_id'=>$k))
                    ->getField('score');

        if($post['flag']){
            $scoreData['user_id'] = $post['user_id'];
            $scoreData['exam_id'] = $post['test_id'];
            $scoreData['is_publish'] = 0;
            $scoreData['test_id'] = $post['examination_id'];
            $scoreData['total_score'] = 0;
            $scoreData['use_time'] = time() - strtotime($post['start_time']);
            $res = M('exam_score')->where(array('test_id'=>$post['examination_id'],'user_id'=>$post['user_id']))->find();
            
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$scoreData['id'] = getNextId('exam_score');
			}
			
            if(!$res){
                $id = M('exam_score')->add($scoreData);

                if($isexam == 1){
                    $r = M('exam_score')->where(array('id'=>$id))->setInc('total_score',$score);
                }
            }else{
                if($isexam == 1){
                    M('exam_score')->where(array('id'=>$res['id']))->setInc('total_score',$score); 
                }
            }
        }else{
            $scoreData['user_id'] = $post['user_id'];
            $scoreData['exam_id'] = $post['test_id'];
            $scoreData['is_publish'] = 0;
            $scoreData['project_id'] = $post['project_id'];
            // $scoreData['test_id'] = $post['examination_id'];
            $scoreData['total_score'] = 0;
            $scoreData['use_time'] = time() - strtotime($post['start_time']);
            
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$scoreData['id'] = getNextId('exam_score');
			}
            $res = M('exam_score')
            		->where(array('project_id'=>$post['project_id'],'user_id'=>$post['user_id'],'exam_id'=>$post['test_id']))
            		->find();

            if(!$res){
                $id = M('exam_score')->add($scoreData);
                if($isexam == 1){
                    M('exam_score')->where(array('id'=>$id))->setInc('total_score',$score); 
                }
            }else{
                if($isexam == 1){
                    M('exam_score')->where(array('id'=>$res['id']))->setInc('total_score',$score); 
                }
            }
        }
        if(strtolower(C('DB_TYPE')) == 'oracle'){
			$itea['id'] = getNextId('exam_answer');
			$itea['data_tiem'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
        M('Exam_answer')->data($itea)->add();

    }

    /**
     * 删除我的考试
     */
    public function delExam(){

        $test_id = I('get.test_id');

        //关联项目id
        $project_id = I('get.project_id');

        $where['user_id'] = array("eq",$_SESSION['user']['id']);
        $where['test_id'] = array("eq",$test_id);
        $where['project_id'] = array("eq",$project_id);

        $data = M('examination_attendance')->where($where)->find();

        if(empty($data)){

            $add = array(
                "user_id"=>$_SESSION['user']['id'],
                "test_id"=>$test_id,
                "project_id"=>$project_id,
                "status"=>3
            );

            M('examination_attendance')->data($add)->add();

        }else{

            M('examination_attendance')->where($where)->setField('status',3);

        }

        return true;

    }

    /**
     * 获取用户信息
     * @param  [type] $pid [项目id]
     * @param  [type] $eid [试卷id]
     * @param  [type] $user_id [用户id]
     * @return [type]      [description]
     */
    public function getUserInfo($pid,$eid,$user_id){
        $data = M('designated_personnel')
                ->alias('a')
                ->join('left join __USERS__ b on a.user_id=b.id')
                ->join('left join __PROJECT_EXAMINATION__ c on a.project_id = c.project_id')
                ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
                ->join('left join __EXAMINATION_ATTENDANCE__ e on a.project_id=e.project_id and e.test_id=c.test_id and b.id=e.user_id')
                ->join('left join __EXAM_SCORE__ f on a.project_id=f.project_id and f.exam_id=c.test_id and f.user_id=b.id')
                ->join('left join __EXAMINATION__ g on c.test_id=g.id')
                ->field('b.username,c.*,d.project_name,e.status as attendance_status,f.is_publish,f.total_score,g.test_score')
                ->where(array('a.project_id'=>$pid,'a.user_id'=>$user_id,'c.test_id'=>$eid))
                ->find();
        return $data;
    }

    /**
     * 获取用户答题信息
     * @param  [type] $pid [项目id]
     * @param  [type] $eid [试卷id]
     * @param  [type] $user_id [用户id]
     * @return [type]      [description]
     */
    public function getAnswerInfo($pid,$eid,$user_id){
    	
		$type = M('examination')->where(array('id'=>$eid))->getField('s_type');
		$where['a.examination_id'] = $eid;
		if($type == 1){
			$where['a.user_id'] = $user_id;
			$where['a.project_id'] = $pid;
		}
		$res = M('examination_item_rel')
                ->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id=b.id')
                ->where($where)
                ->select();
    
        foreach($res as $k=>$v){
            $info = M('exam_answer')
                    ->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid,'question_number'=>$v['examination_item_id']))
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
     * 获取用户问答题信息
     * @param  [type] $pid [项目id]
     * @param  [type] $eid [试卷id]
     * @param  [type] $user_id [用户id]
     * @return [type]      [description]
     */
    public function getWdScore($pid,$eid,$user_id){
        return M('exam_answer')
                ->where(array('exam_id'=>$eid,'project_id'=>$pid,'u_exam_id'=>$user_id,'classification'=>4))
                ->select();
    }
	
	public function getOfflineData(){
		$get = I('get.');
		$user_id = session('user.id');
		if($get['flag']){
			$data = M('test')
				->where(array('id'=>$get['test_id']))
				->field('name,start_time,end_time,address')
				->find();
			$data['total_score'] = M('exam_score')->where(array('user_id'=>$user_id,'test_id'=>$get['test_id']))->getField('total_score');
			$data['test_name'] = $data['name'];
		}else{
			$data = M('project_examination a')
					->join('left join __EXAMINATION__ b on a.test_id=b.id')
					->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
					->join('left join __DESIGNATED_PERSONNEL__ d on a.project_id=d.project_id')
					->join('left join __EXAM_SCORE__ e on a.project_id=e.project_id and a.test_id=e.exam_id and d.user_id=e.user_id')
					->where(array('a.project_id'=>$get['project_id'],'a.test_id'=>$get['examination_id'],'d.user_id'=>$user_id))
					->field('a.*,b.test_name,c.project_name,e.total_score')
					->find();
			$data['test_name'] = !empty($data['test_names']) ? $data['test_names'] : $data['test_name'];
		}
		return $data;
	}

    /**
     * 计算考试结果
     * @param $eid 试卷id
     * @param $user_id 用户id
     * @param $tid 考试id
     * @param $pid 项目id
     */
    public function countResult($eid,$user_id,$tid=null,$pid=null){
        
		$type = M('examination')->where(array('id'=>$eid))->getField('s_type');
		if($type == 1){
			if($tid){
				$questions = M('examination_item_rel')
						->where(array('examination_id'=>$eid,'user_id'=>$user_id,'test_id'=>$tid))
						->select();
			}else{
				$questions = M('examination_item_rel')
						->where(array('examination_id'=>$eid,'user_id'=>$user_id,'project_id'=>$pid))
						->select();
			}
		}else{
			$questions = M('examination_item_rel')->where(array('examination_id'=>$eid))->select();
		}

        if($tid){
            foreach($questions as $k=>$v){
                $res = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid,'question_number'=>$v['examination_item_id']))->field('isexam,wdscore')->find();
                $res['isexam'] = $res['isexam'] ? $res['isexam'] : $res['isexam'];
                $questions[$k]['isexam'] = $res['isexam'];
                $questions[$k]['wdscore'] = $res['wdscore'];
                $questions[$k]['classification'] = M('examination_item')
                                            ->where(array('id'=>$v['examination_item_id']))
                                            ->getField('classification');
            }

			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$examination = M('test')
						->where(array('id'=>$tid))
						->field("id,name,create_user,type,status,audit_status,score,examination_id,address,pass_line,to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time")
						->find();
				$time = M('exam_answer')
						->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid))
						->field("to_char(data_tiem,'YYYY-MM-DD HH24:MI:SS') as data_tiem")
						->order('data_tiem desc')
						->find();
				$time = $time['data_tiem'];
			}else{
				$examination = M('test')->where(array('id'=>$tid))->find();
				$time = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid))
                            ->limit(1)->getField('data_tiem');
			}
            $score = M('exam_score')->where(array('exam_id'=>$eid,'user_id'=>$user_id,'test_id'=>$tid))->getField('total_score');

            $right_number = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid,'isexam'=>1))
                            ->count();
            $wd_number = M('exam_answer')
                        ->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'test_id'=>$tid,'classification'=>4,'wdscore'=>array('neq',0)))
                        ->count();

            $right_number += $wd_number;
            
            $attendance = M('examination_attendance')->where(array('user_id'=>$user_id,'test_id'=>$eid,'examination_id'=>$tid))->getField('status');
        }
        if($pid){
            // $answer = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid))->select();
            $questions = M('examination_item_rel')->where(array('examination_id'=>$eid))->select();
            foreach($questions as $k=>$v){
                $res = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid,'question_number'=>$v['examination_item_id']))->field('isexam,wdscore')->find();
                $res['isexam'] = $res['isexam'] ? $res['isexam'] : $res['isexam'];

                $questions[$k]['isexam'] = $res['isexam'];
                $questions[$k]['wdscore'] = $res['wdscore'];
                $questions[$k]['classification'] = M('examination_item')
                                            ->where(array('id'=>$v['examination_item_id']))
                                            ->getField('classification');
            }
            
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$examination = M('project_examination a')
                            ->join('left join __EXAMINATION__ b on a.test_id=b.id')
                            ->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
                            ->where(array('a.project_id'=>$pid,'a.test_id'=>$eid))
                            ->field("a.project_id,a.test_id,a.credits,a.test_length,a.manager_id,a.test_names,a.cacheid,a.examination_address,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,b.test_name,c.project_name")
                            ->find();
				$time = M('exam_answer')
						->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid))
						->field("to_char(data_tiem,'YYYY-MM-DD HH24:MI:SS') as data_tiem")
						->order('data_tiem desc')
						->find();
				$time = $time['data_tiem'];
			}else{
				$examination = M('project_examination a')
                            ->join('left join __EXAMINATION__ b on a.test_id=b.id')
                            ->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
                            ->where(array('a.project_id'=>$pid,'a.test_id'=>$eid))
                            ->field('a.*,b.test_name,c.project_name')
                            ->find();
				$time = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid))
                            ->limit(1)->getField('data_tiem');
			}
            $examination['name'] = $examination['test_names'] ? $examination['test_names'] : $examination['test_name'];
            $score = M('exam_score')->where(array('exam_id'=>$eid,'user_id'=>$user_id,'project_id'=>$pid))->getField('total_score');
            $right_number = M('exam_answer')->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid,'isexam'=>1))
                            ->count();
            $wd_number = M('exam_answer')
                        ->where(array('exam_id'=>$eid,'u_exam_id'=>$user_id,'project_id'=>$pid,'classification'=>4,'wdscore'=>array('neq',0)))
                        ->count();

            $right_number += $wd_number;
            $attendance = M('examination_attendance')->where(array('user_id'=>$user_id,'test_id'=>$eid,'project_id'=>$pid))->getField('status');
        }
        
        $total_score = M('examination')->where(array('id'=>$eid))->getField('test_score');
        return array(
            'examination'=>$examination,        //试卷信息
            'question_number'=>count($questions),//试题个数
            // 'answer'=>$answer,               //答案信息
            'score'=>$score,                    //考试得分
            'total_score'=>$total_score,        //试卷总分
            'right_number'=>$right_number,      //正确个数
            'time'=>$time,                      //交卷时间
            'questions'=>$questions,            //试题信息
            'attendance'=>$attendance          //考试状态
        );
    }

    /**
     * 人工阅卷-修改分数
     * @return [type] [description]
     */
    public function changeWdScore(){
        $data = I('post.');
        foreach($data['wd'] as $k=>$v){
            //答案信息
            $where = array('exam_id'=>$data['eid'],'project_id'=>$data['pid'],'u_exam_id'=>$data['user_id'],'question_number'=>$data['id'][$k]);
            $answer = M('exam_answer')
                ->where($where)
                ->field('wdscore,checked,id')
                ->find();
            if($answer){
                //分数信息
                $where1 = array('user_id'=>$data['user_id'],'exam_id'=>$data['eid'],'project_id'=>$data['pid']);
                $scoreData = M('exam_score')->where($where1)->find();
                if($scoreData){
                    M('exam_score')->where(array('id'=>$scoreData['id']))->setDec('total_score',$answer['wdscore']);
                    M('exam_score')->where(array('id'=>$scoreData['id']))->setInc('total_score',$data['wd'][$k]);
                }else{
                    $arr = array(
                        'user_id'=>$data['user_id'],
                        'exam_id'=>$data['eid'],
                        'total_score'=>$data['wd'][$k],
                        'project_id'=>$data['pid'],
                        'use_time'=>0
                    );
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$arr['id'] = getNextId('exam_score');
					}
                    $res = M('exam_score')->add($arr);
                    if(!$res){
	                	return false;
	                }
                }
                $res = M('exam_answer')
                    ->where(array('id'=>$answer['id']))
                    ->save(array('wdscore'=>$data['wd'][$k],'checked'=>1));
                if($res === false){
                    return false;
                }
            }else{
                //分数信息
                $where1 = array('user_id'=>$data['user_id'],'exam_id'=>$data['eid'],'project_id'=>$data['pid']);
                $scoreData = M('exam_score')->where($where1)->find();
                if($scoreData){
                    $res = M('exam_score')->where(array('id'=>$scoreData['id']))->setInc('total_score',$data['wd'][$k]);
                    if(!$res){
	                	return false;
	                }
                }else{
                    $arr = array(
                        'user_id'=>$data['user_id'],
                        'exam_id'=>$data['eid'],
                        'total_score'=>$data['wd'][$k],
                        'project_id'=>$data['pid'],
                        'use_time'=>0
                    );
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$arr['id'] = getNextId('exam_score');
					}
                    $a = M('exam_score')->add($arr);
                    if(!$a){
                        return false;
                    }
                }
                $arr2 = array(
                    'exam_id'=>$data['eid'],
                    'project_id'=>$data['pid'],
                    'u_exam_id'=>$data['user_id'],
                    'classification'=>4,
                    'question_number'=>$data['id'][$k],
                    'data_tiem'=>date('Y-m-d H:i:s'),
                    'wdscore'=>$data['wd'][$k],
                    'checked'=>1
                );
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$arr2['id'] = getNextId('exam_answer');
					$arr2['data_tiem'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
				}
                $res = M('exam_answer')->add($arr2);
                if(!$res){
                	return false;
                }
            }
            //考勤信息
            $where2['user_id']=$data['user_id'];
            $where2['test_id']=$data['eid'];
            $where2['project_id']=$data['pid'];
            $attendanceData = M('examination_attendance')->where($where2)->find();
            if($attendanceData){
                $res = M('examination_attendance')->where($where2)->save(array('status'=>1));
                if($res === false){
                	return false;
                }
            }else{
                $d = array(
                    'user_id'=>$data['user_id'],
                    'test_id'=>$data['eid'],
                    'project_id'=>$data['pid'],
                    'status'=>1
                );
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$d['id'] = getNextId('examination_attendance');
				}
                $rs = M('examination_attendance')->add($d);
                if(!$rs){
                	return false;
                }
            }
        }
        return true;
    }

	/**
	 * $num 要获取的试题数量
	 * $type 要获取的试题类型
	 * $score 试题分数
	 * $where 查询条件
	 */
	public function random_examinations($num,$type,$score,$where){
		$where['classification'] = $type;
		$data = M('Examination_item')->where($where)->getField('id',true);
		
		$res = array_rand($data,$num);
		$res = is_array($res) ? $res : (array)$res;
		
		$ids = array();
		foreach($res as $k=>$v){
			$ids[] = $data[$v];
		}
		
		return $ids;
	}
}
