<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 我的考试控制器
 * @Andy
 */
class MyExamController extends AdminBaseController{
    /*
     * 待考考试列表
     */
    public function waitExam(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('MyExam')->examList($total_page);
        // dump($approved_data);
       //获取模型返回数据
        $this->assign('approved_list',$approved_data['map']);
        //获取返回的分页数据
        $this->assign('page',$approved_data['page']);
       //记录搜索关键字
        $this->assign('keyword',$approved_data['keyword']); 
        $this->assign('isnew',0);
        $this->display();
    }
    
    /**
     * 其他考试-非一期项目考试
     * @return [type] [description]
     */
    public function otherExam(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('MyExam')->examList2($total_page);
        $user_id = $_SESSION['user']['id'];
        // dump($approved_data['data']);
       //获取模型返回数据
        $this->assign('data',$approved_data['data']);
        //获取返回的分页数据
        $this->assign('page',$approved_data['page']);
       //记录搜索关键字
        $this->assign('keyword',$approved_data['keyword']); 
        $this->assign('isnew',1);   //标记
        $this->assign('user_id',$user_id);
        $this->display();
    }
	
	/**
	 * 全部考试，项目考试+其他考试
	 */
	public function allExam(){
        $data = D('MyExam')->examList3();
//dump($data);
        $user_id = $_SESSION['user']['id'];
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->assign('keyword',$data['keyword']); 
        $this->assign('isnew',2);   //标记
        $this->assign('user_id',$user_id);
        $this->display();
	}

    /*
     * 已结束的考试
     */
    public function endExam(){
       //每页显示条数
        $total_page = $this->total_page;
        $exam_id = I('get.exam_id');
        $approved_data = D('MyExam')->examEnd($total_page); 
        //获取模型返回数据
        $this->assign('approved_list',$approved_data['map']);
        //获取返回的分页数据
        $this->assign('approved_page',$approved_data['page']);
       //记录搜索关键字
        $this->assign('keyword',$approved_data['keyword']); 
        $this->display();
    }
    
    
    /*
     * 查看已考试的结果
     */
    public function checkResult(){

        $data = D('MyExam')->getExamResult();
        $this->assign('data',$data);
        $this->display();

    }
    /*
     * 参加考试
     */
    public function joinExam(){
        $get = I('get.');
        
        //已考试，跳转结果页面
        $user_id = $_SESSION["user"]["id"];
        if($get["flag"] == "flag" && $get["test_id"]){
        	//工具考试
        	$hasWhere["test_id"] = $get["test_id"];
        }else{
        	//项目考试
        	$hasWhere["project_id"] = $get["project_id"];
        }
        $hasWhere["exam_id"] = $get["examination_id"];
        $hasWhere["u_exam_id"] = $user_id;
        $hasJoin = M("exam_answer")->field("u_exam_id")->where($hasWhere)->find();
        if($hasJoin){
        	if($get["flag"] == "flag" && $get["test_id"]){
        		$seeUrl = __MODULE__."/my_exam/result/tid/".$get["test_id"]."/eid/".$get["examination_id"];
        	}else{
        		$seeUrl = __MODULE__."/my_exam/result/pid/".$get["project_id"]."/eid/".$get["examination_id"];
        	}
        	header("Location:".$seeUrl);
        	exit;
        }
        
        $exam = D('MyExam');
        $flag = $get['flag'];
        $data = $exam->getExamInfo($get,$flag);
        if(!$data){
        	$this->success('该试题已逾期',U('admin/MyExam/waitexam',array('typeid'=>1)));
        	exit;
        }
		
        $times = $data['detail']['test_length'] * 60;
		if(strtotime($data['detail']['end_time']) - time() < $times){
			$times = strtotime($data['detail']['end_time']) - time();
		}
		
        //详情
        $this->assign('xhr',$data['detail']);
        $this->assign('flag',$flag);
        $this->assign('times',$times);
    
        //单选
        $this->assign('singleChoice',$data['singleChoice']);
        $this->assign('singleChoiceSum',$data['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$data['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$data['multipleChoice']);
        $this->assign('multipleChoiceSum',$data['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$data['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$data['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$data['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$data['descriPtiveChoiceTotalScore']);

        //简答题
        $this->assign('wd',$data['wd']);
        $this->assign('wdSum',$data['wdSum']);
        $this->assign('wdTotalScore',$data['wdTotalScore']);

        $this->assign('test_id',$get['test_id']);
        $this->assign('project_id',$get['project_id']);
        $this->assign('examination_id',$data['detail']['examination_id']);

        $num = $data['singleChoiceSum'] + $data['multipleChoiceSum'] + $data['descriPtiveChoiceSum'] + $data['wdSum'];
        $this->assign('questionsNum',$num);
        $this->display();
    }
    
    /*
     * 处理考试提交的结果
     */
    public function handelExam()
    {
        if (IS_POST) {
            //接收提交考试的答案
            $post = I('post.');
            $post['user_id'] = $_SESSION['user']['id'];
            $ret = D('MyExam')->handelExamResult($post);

            $flag = I('post.flag');

            $backUrl = $flag ? 'otherexam' : 'waitexam';

            $tid = $post['test_id'];
            $eid = $post['examination_id'];
            $pid = $post['project_id'];
            
            if ($ret) {
                if($flag){
                    $url = U('result',array('eid'=>$eid,'flag'=>$flag,'tid'=>$tid));
                }else{
                    $url = U('result',array('eid'=>$eid,'pid'=>$pid));
                }
                $this->success('提交成功',$url);
            } else {
                $this->error('不能重复提交',$backUrl);
            }
        }
    }

    /**
     * 考试结果页面
     */
    public function result(){
        $eid = I('get.eid');
        $tid = I('get.tid');
        $pid = I('get.pid');
        $user_id = $_SESSION['user']['id'];

        if($tid){
            $info = D('MyExam')->countResult($eid,$user_id,$tid);
            foreach($info['questions'] as $k=>$v){
                $info['questions'][$k]['url'] = U("checkexaminationanswer#".$v['examination_item_id'],array('test_id'=>$tid,'examination_id'=>$eid,'id'=>$user_id));
            }
            $this->assign('tid',$tid);
        }else{
            $info = D('MyExam')->countResult($eid,$user_id,null,$pid);
            foreach($info['questions'] as $k=>$v){
                $info['questions'][$k]['url'] = U('getProjectExamInfo#'.$v["examination_item_id"],array('project_id'=>$pid,'examination_id'=>$eid));
            }
            $this->assign('pid',$pid);
        }
        // dump($info['questions']);
        $this->assign('eid',$eid);
        $this->assign('user_id',$user_id);
        $this->assign('tid',$tid);
        $this->assign('pid',$pid);
        $this->assign('data',$info);
        $this->display();
    }

    /**
     * 获取试题解析
     */
    public function getAnalysis(){
        $info = M('examination_item')->where(array('id'=>I('get.id')))->getField('analysis');
        $info = $info ? $info : '暂无解析';
        $this->ajaxReturn(array('info'=>$info));
    }


    /**
     * 删除我的考试
     */
    public function delExam(){

        $results = D('MyExam')->delExam();

        if($results){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 查看考试结果
     * @return [type] [description]
     */
    public function checkExaminationAnswer(){
        $model = D('TestManage');
        $data = I('get.');//getExamDetail2  test_id考试id  examination_id试卷id id用户id
//      $res = D('ResourcesManage')->getExamDetail2($data['examination_id'],$data['test_id'],$data['id']);
        $res = D('ResourcesManage')->getExamDetail2($data['examination_id'],$data['id'],$data['test_id'],$data['project_id']);
        $name = $model->getTestInfo($data['test_id']);
        $res['detail']['test_name'] = $name['name'];

        $uinfo = $model->getUserInfo($data['test_id'],$data['id']);
        $answerInfo = $model->getAnswerInfo($data['test_id'],$data['id'],$data['examination_id'],$data['project_id']);
        $wdscore = $model->getWdScore($data['test_id'],$data['id']);

        //详情
        $this->assign('xhr',$res['detail']);
        $this->assign('uInfo',$uinfo);
        $this->assign('answerInfo',$answerInfo);
        $this->assign('test_id',$data['test_id']);  //考试id
        $this->assign('examination_id',$data['examination_id']);//试卷id
        $this->assign('user_id',$data['id']);
        $this->assign('wdscore',$wdscore);
        $this->assign('url',U('result',array('flag'=>1,'tid'=>$data['test_id'],'eid'=>$data['examination_id'],'user_id'=>$_SESSION['user']['id'])));

        //单选
        $this->assign('singleChoice',$res['singleChoice']);
        $this->assign('singleChoiceSum',$res['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$res['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$res['multipleChoice']);
        $this->assign('multipleChoiceSum',$res['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$res['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$res['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$res['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$res['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$res['wd']);
        $this->assign('wdSum',$res['wdSum']);
        $this->assign('wdTotalScore',$res['wdTotalScore']);        
        $this->display();
    }

    /**
     * 获取项目考试信息
     * @return [type] [description]
     */
    public function getProjectExamInfo(){
        $model = D('MyExam');
        $data = I('get.');
        $pid = $data['project_id'];         //项目id
        $eid = $data['examination_id'];     //试卷id
        $user_id = $_SESSION['user']['id'];                 //用户id
        $res = D('ResourcesManage')->getExamDetail2($eid,$user_id,$data['test_id'],$data['project_id']);//根据试卷id获取试卷信息
//      dump($res);
        $names = M('project_examination')->where(array('project_id'=>$pid,'test_id'=>$eid))->getField('test_names');
        $res['detail']['test_name'] = $names ? $names : $res['detail']['test_name'];


        $uinfo = $model->getUserInfo($pid,$eid,$user_id);           //用户数据
        $answerInfo = $model->getAnswerInfo($pid,$eid,$user_id);    //答案数据
        $wdscore = $model->getWdScore($pid,$eid,$user_id);          //问答题分数

        //详情
        $this->assign('xhr',$res['detail']);
        $this->assign('uInfo',$uinfo);
        $this->assign('answerInfo',$answerInfo);
        $this->assign('project_id',$pid);  //考试id
        $this->assign('examination_id',$eid);//试卷id
        $this->assign('user_id',$user_id);
        $this->assign('wdscore',$wdscore);
        $this->assign('url',U('result',array('pid'=>$pid,'eid'=>$eid,'user_id'=>$user_id)));

        //单选
        $this->assign('singleChoice',$res['singleChoice']);
        $this->assign('singleChoiceSum',$res['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$res['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$res['multipleChoice']);
        $this->assign('multipleChoiceSum',$res['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$res['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$res['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$res['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$res['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$res['wd']);
        $this->assign('wdSum',$res['wdSum']);
        $this->assign('wdTotalScore',$res['wdTotalScore']);        
        $this->display('checkexaminationanswer');
    }

    /**
     * 查看线下考试结果
     */
    public function lookResultOffline(){
        $data = D('MyExam')->getOfflineData();
        
        $this->assign('data',$data);
        $this->display();
    }

}