<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class HostExaminationModel extends BaseModel{

    //初始化
    public function __construct(){}
    
    /*
     * 获取指定负责人下的所有考试信息
     */
    public function getProjectExamination($total_page = 10){
    
        $start_page = I("get.p",0,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";
        $user_id=$_SESSION["user"]["id"];
        //如果存在搜索条件则执行
        if(!empty($keyword)){
            $where['c.test_name']= array( "like", "%".$keyword."%");
        }
        $where['a.manager_id'] =  array('eq',$user_id);
        $where['b.type'] =  array('in',array('0','4'));
    
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$list = M('Project_examination a')
	        ->join('LEFT JOIN __ADMIN_PROJECT__ b ON b.id=a.project_id LEFT JOIN __EXAMINATION__ c ON c.id=a.test_id LEFT JOIN __EXAMINATION_CATEGORY__ d ON c.test_cat_id=d.id')
	        ->where($where)
	        ->field("a.cacheid,to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,a.test_names,a.test_id,a.project_id,a.test_length,b.project_name,c.test_name,c.test_score,c.test_mode,d.cat_name")
	        ->order('b.add_time desc')
	        ->page($start_page,$total_page)
	        ->select();
		}else{
			$list = M('Project_examination a')
	        ->join('LEFT JOIN __ADMIN_PROJECT__ b ON b.id=a.project_id LEFT JOIN __EXAMINATION__ c ON c.id=a.test_id LEFT JOIN __EXAMINATION_CATEGORY__ d ON c.test_cat_id=d.id')
	        ->where($where)
	        ->field('a.cacheid,a.start_time,a.end_time,a.test_names,a.test_id,a.project_id,a.test_length,b.project_name,c.test_name,c.test_score,c.test_mode,d.cat_name')
	        ->order('b.add_time desc')
	        ->page($start_page,$total_page)
	        ->select();
		}
        //遍历开始时间和结束时间计算状态
        foreach($list as $k => $v){
            $now = time();
            $s = strtotime($v['start_time']) - $now;//考试开始时间与当前时间的差
            $e = strtotime($v['end_time']) - $now;//考试结束时间和当前时间的差
            if($s > 0){
                $items[$k]['_status'] = 1;//未开始
            }
            if($e < 0){
                $items[$k]['_status'] = 2;//已结束
            }
            if($now >= strtotime($v['start_time']) && $now <= strtotime($v['end_time']) ){
                $items[$k]['_status'] = 3;//进行中
            }

            $items[$k]['test_id'] = $v['test_id'];//考试id
            $items[$k]['cat_name'] = $v['cat_name'];//试卷类别名称
            $items[$k]['project_id'] = $v['project_id'];//关联项目id
            $items[$k]['project_name'] = $v['project_name'];//关联项目名称
            $items[$k]['test_mode'] = $v['test_mode'];//考试方式
            $items[$k]['test_name'] = ($v['test_names'] == '')?$v['test_name']:$v['test_names'];//考试名称
            $items[$k]['start_time'] = $v['start_time'];//开始时间
            $items[$k]['end_time'] = $v['end_time'];//结束时间
            $items[$k]['test_length'] = $v['test_length'];//时长
            $items[$k]['cacheid'] = $v['cacheid'];//标识线上线下考试

        }
        
        $left = $middle = $right = array();
        foreach($items as $k=>$v){
            if($v['_status'] == 2){
                $right[] = $items[$k];
            }else if($v['_status'] == 3){
                $left[] = $items[$k];
            }else if($v['_status'] == 1){
                $middle[] = $items[$k];
            }
        }
        $final = array_merge($left,$middle,$right);

        $count = M('Project_examination a')
        ->join('LEFT JOIN __ADMIN_PROJECT__ b ON b.id=a.project_id LEFT JOIN __EXAMINATION__ c ON c.id=a.test_id LEFT JOIN __EXAMINATION_CATEGORY__ d ON c.test_cat_id=d.id')
        ->where($where)
        ->count();
        //输出分页
        $show=$this->pageClass($count,$total_page);
        return $data = array(
            'page' => $show,
            'list' => $final,
            'keyword'=>$keyword
        );
    }
    
    
    /*
     * 获取考试题目信息
     */
    public function getPreviewExaminationInfo(){
      
            //试卷id
            $id = I('get.id');
            $t_name = I('get.t_name');
            $p_name = I('get.p_name');
            $t = I('get.t');
            //试卷详情
            $exam = M('Examination');
            $ret = $exam->alias('a')->join('LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id AND a.id ='.$id) ->field('a.*,b.cat_name')->find();
            $ret['project_name'] = $p_name;
            $ret['t_name'] = $t_name;
            $ret['t'] = $t;
            
            //试卷单选题题目
            $singleChoice = M('Examination_item_rel')->alias('a')
                ->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
                ->where(array('a.examination_id'=>$id,'b.classification'=>1,'a.user_id'=>session('user.id'),'project_id'=>$pid))
                ->select();
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
     * 查看考试答案
     */
    public function getExaminationAnswer(){
        $get = I('get.');
        return true;
    }
}