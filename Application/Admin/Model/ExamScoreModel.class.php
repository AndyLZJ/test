<?php

namespace Common\Model;

use Common\Model\BaseModel;

class ExamScoreModel extends BaseModel
{

	/**获取相应项目的相应考试的考试下面所有学员的考试成绩*
	*/
    public function getAllExaminationResults($total_page = 10,$map){
        $start_page = I("get.p",1,'int');
        $where = array();
        $where["a.project_id"]=$map["project_id"];
        $where["a.exam_id"]=$map["exam_id"];
        $info=$this->alias("a")
        ->join("LEFT JOIN __USERS__ b on a.user_id=b.id")
        ->join("LEFT JOIN __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id")
        ->join("LEFT JOIN __EXAMINATION_ATTENDANCE__ d on a.user_id=d.user_id")
        ->join("LEFT JOIN __TISSUE_RULE__ e on c.tissue_id=e.id")
        ->join("LEFT JOIN __JOBS_MANAGE__ f on c.job_id=f.id")
        ->distinct(true)
        ->where($where)
        ->field("a.id,a.user_id,a.exam_id,a.total_score,a.project_id,a.is_publish,b.username,b.phone,c.tissue_id,c.job_id,d.status,e.name,f.name as fname")
        ->page($start_page,$total_page)
        ->order('a.id asc')
        ->select();
        //统计总条数
        $count = count($info);
        //输出分页
        $show=$this->pageClass($count,$total_page);
        return $data = array(
            'page' => $show,
            'list' => $info,
            'test_name'=> $map['n'],
            'test_id'=>$map['exam_id'],
            'project_id'=>$map['project_id']
        );
    }

    public function getAllExaminationResults2($total_page = 10,$map){
        $start_page = I("get.p",1,'int');
        $where = array();
        $where["a.project_id"]=$map["project_id"];
        // $where["a.exam_id"]=$map["exam_id"];
        $info=M('designated_personnel')->alias("a")
                ->join("LEFT JOIN __USERS__ b on a.user_id=b.id")
                ->join("LEFT JOIN __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id")
                ->join("LEFT JOIN __TISSUE_RULE__ d on c.tissue_id=d.id")
                ->join("LEFT JOIN __JOBS_MANAGE__ e on c.job_id=e.id")
                // ->join("LEFT JOIN __EXAMINATION_ATTENDANCE__ f on a.user_id=f.user_id and a.project_id=f.project_id")
                ->where($where)
                ->field("a.*,b.username,b.phone,c.tissue_id,c.job_id,d.name,e.name as fname")
                ->page($start_page,$total_page)
                ->order('a.id asc')
                ->select();

        foreach($info as $k=>$v){
            $info[$k]['attendance'] = $this->getAttStatus($v['project_id'],$v['user_id'],$map['exam_id']);
            $info[$k]['total_score'] = $this->getTestScore($v['project_id'],$v['user_id'],$map['exam_id']);
        }
        
        //统计总条数
        $count = M('designated_personnel')->alias("a")->distinct(true)->where($where)->count();

        //输出分页
        $show=$this->pageClass($count,$total_page);
        return $data = array(
            'page' => $show,
            'list' => $info,
            'total'=>$count,
            'test_name'=> $map['n'],
            'test_id'=>$map['exam_id'],
            'project_id'=>$map['project_id']
        );
    }

    /**
     * 根据项目id获取所有考试人员的考勤信息
     * $tid  项目id
     * @return [type] [description]
     */
    public function getAttStatus($pid,$user_id,$eid){
        $attendance = M('examination_attendance')
                    ->where(array('project_id'=>$pid,'user_id'=>$user_id,'test_id'=>$eid))
                    ->getField('status');
        return $attendance;
    }

    /**
     * 获取考试分数
     * @param  [type] $pid [项目id]
     * @return [type]      [description]
     */
    public function getTestScore($pid,$user_id,$eid){

        $where['project_id']  = array("eq",$pid);
        $where['user_id']  = array("eq",$user_id);
        $eid = $eid ? $eid : 0;
        $where['exam_id']  = array("eq",$eid);
        $res = M('exam_score')
                ->where($where)
                ->getField('total_score');

        return $res;
    }

    /**
     * 获取项目考试实到人数
     * @param  [type] $pid [项目id]
     * @param  [type] $tid [试卷id]
     * @return [type]      [description]
     */
    public function getAllExamNum($pid,$tid){
        $where['test_id'] = $tid;
        $where['project_id'] = $pid;
        $where['status'] = 1;
        return M('examination_attendance')->where($where)->count();
    }
    
    /**
     * 获取项目考试发布状态
     * @param  [type] $pid [项目id]
     * @param  [type] $tid [试卷id]
     * @return [type]      [description]
     */
    public function getPublishStatus($pid,$tid){

        $where['project_id'] = array("eq",$pid);

        if(!empty($tid)){
            $where['exam_id'] = array("eq",$tid);
        }

        $info = M('exam_score')->where($where)->getField('is_publish',true);
        if(in_array(1,$info)){
            return 1;
        }else{
            return 0;
        }
    }

	 public function acquire($map){
		$arr["a.project_id"]=$map["project_id"];
		$arr["a.exam_id"]=$map["exam_id"];
		$arr["d.test_id"]=$map["exam_id"];
		$count=$this->alias("a")
					->join("left join __USERS__ b on a.user_id=b.id")
					->join("left join __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id")
					->join("left join __EXAMINATION_ATTENDANCE__ d on a.user_id=d.user_id")
					->where($arr)
					->count();
		$Page=new \Think\Page($count,10);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$information=$this->alias("a")
					 ->field("a.id,a.exam_id,a.total_score,a.project_id,a.is_publish,b.username,b.phone,c.tissue_id,c.job_id,d.status")
					 ->join("left join __USERS__ b on a.user_id=b.id")
					 ->join("left join __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id")
					 ->join("left join __EXAMINATION_ATTENDANCE__ d on a.user_id=d.user_id")
					 ->where($arr)
					 ->order('a.id asc')
					 ->limit($Page->firstRow.','.$Page->listRows)
					 ->select();

		$show=$Page->show();
		$tissue=D("TissueRule");
		$jobsManage=D("JobsManage");

		for($i=0;$i<count($information);$i++){
			$maps["id"]=$information[$i]["tissue_id"];
			$arrs["id"]=$information[$i]["job_id"];
			$information[$i]['tissue']=$tissue->field("name")->where($maps)->find();
			$information[$i]["job"]=$jobsManage->field("name")->where($arrs)->find();
		}
		
		return $assign=array(
			"information"=>$information,
			"show"=>$show
		);
	} 
	/**发布考试成绩*
	*/
	public function publish(){
		$get = I('get.');
		$where['project_id'] = $get['project_id'];
		$where['exam_id'] = $get['exam_id'];
		if($get['project_id']){
			$userids = M('designated_personnel')->where(array('project_id'=>$get['project_id']))->select();
			foreach($userids as $k=>$v){
				$info = M('exam_score')->where(array_merge($where,array('user_id'=>$v['user_id'])))->select();
				if(!$info){
					$insertData = array(
						'user_id'=>$v['user_id'],
						'exam_id'=>$get['exam_id'],
						'total_score'=>0,
						'project_id'=>$get['project_id'],
						'is_publish'=>1,
						'test_id'=>0,
						'use_time'=>0
					);
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$insertData['id'] = getNextId('exam_score');
					}
					M('exam_score')->add($insertData);
				}
			}
			$res = M('exam_score')->where($where)->save(array('is_publish'=>1));
			if($res === FALSE){
				return FALSE;
			}
		}
		return TRUE;
        /*static $m = 0;
        $post = I('post.');
        $user_ids = $post['user_id'];unset($post['user_id']);
        $pid = $post['project_id'];unset($post['project_id']);
        $eid = $post['exam_id'];unset($post['exam_id']);
        unset($post['test_mode']);

        foreach($post as $k=>$v){
            if($v){
                $data = $this->saveScore($k,$v,$pid,$eid,$user_ids[$m]);
                $where = array('user_id'=>$user_ids[$m],'exam_id'=>$eid,'project_id'=>$pid);
                $is_publish = M('exam_score')->where($where)->find();
                if($is_publish){
                    $res = M('exam_score')->where($where)->save(array('is_publish'=>1,'total_score'=>$v));
                    if($res === false){
                        return false;
                    }
                }else{
                    $data = array(
                        'user_id'=>$user_ids[$m],
                        'exam_id'=>$eid,
                        'total_score'=>$v,
                        'project_id'=>$pid,
                        'is_publish'=>1,
                        'use_time'=>0
                    );
                    $res = M('exam_score')->add($data);
                    if(!$res){
                        return false;
                    }
                }
                $m += 1;
            }
            break;
        }
		return true;*/
	}

	/**
     * [saveScore 更改考试成绩]
     * @param  [type] $id     [description]这个啥玩意？？？
     * @param  [type] $score  [分数]
     * @param  [type] $pjid   [项目id]
     * @param  [type] $examid [试卷id]
     * @param  [type] $user_id    [用户id]
     * @return [type]         [description]
     */
	public function saveScore($id,$score,$pjid,$examid,$user_id){
        //考试分数信息
		$data['user_id']=$user_id;
        $data["project_id"]=$pjid;
        $data["exam_id"]=$examid;
        $dbid = $this->field('id')->where($data)->find();
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('exam_score');
		}
        if($dbid){
            $data["total_score"]=$score;
            $res = $this->where(array('id'=>$dbid['id']))->save($data);
        }else{
            $data["total_score"]=$score;
            $res = $this->add($data);
        }
        if($res === false){
            return false;
        }

        //考试考勤信息
        $info['user_id'] = $user_id;
        $info['test_id'] = $examid;
        $info['status'] = 1;
        $info['project_id'] = $pjid;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$info['id'] = getNextId('examination_attendance');
		}
        $isset = M('examination_attendance')->field('id')->where(array('project_id'=>$pjid,'user_id'=>$user_id,'test_id'=>$examid))->find();
        if($isset){
            $res = M('examination_attendance')->where(array('id'=>$isset['id']))->save($info);
        }else{
            $res = M('examination_attendance')->add($info);
        }
        if($res === false){
            return false;
        }

		return true;
	}

	/**导入考试成绩*
	*/
	public function importScore($map){
		
		$result=$this->where($map)->find();
		
		if($result==null){
			
			$res=$this->add($map);
		}
		
		return true;
	}

}