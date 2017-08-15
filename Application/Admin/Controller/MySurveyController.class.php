<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * 我的调研控制器
 * @MySurveyController.class.php
 */
class MySurveyController extends AdminBaseController{
    /*
     * 我的调研列表
     */
    public function waitSurvey(){
        //每页显示条数
        $total_page = $this->total_page;
        
        //处理试卷过期
        D('MySurvey')->overdue();

        $approved_data = D('MySurvey')->getWaitSurvey($total_page);
        $this->assign('typeid',$approved_data['typeid']);
        $this->assign('approved_list',$approved_data['map']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('keyword',$approved_data['keyword']);      
        $this->display();

    }

    /**
     * 调研评估
     */
    public function researchList(){

        $total_page = $this->total_page;

        //添加所属组织参加试卷状态
        D('MySurvey')->researchAdd($total_page);

        $approved_data = D('MySurvey')->researchList($total_page);

        $this->assign('typeid',$approved_data['typeid']);
        $this->assign('approved_list',$approved_data['map']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();

    }
    
    /*
     * 已结束的调研列表
     */
    public function endSurvey(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('MySurvey')->getWaitSurvey($total_page);
        $this->assign('approved_list',$approved_data['map']);
        $this->assign('typeid',$approved_data['typeid']);
        $this->assign('approved_page',$approved_data['page']);
        $this->display();
    }
    /*
     * 参加调研
     * $typeid 0 培训问卷 1工具调研
     */
    public function joinSurvey(){
		//如果调研结束，跳转结果页面
    	$typeid = I('get.typeid');
    	$survey_id = I('get.survey_id');
    	if($typeid == 0){
    		$project_id = I('get.project_id');
    		$hasJoin = M('survey_answer')->field("survey_id")->where(array("survey_id"=>$survey_id,"project_id"=>$project_id,"u_survey_id"=>$_SESSION['user']['id']))->find();
	    	if($hasJoin){
	    		$seeUrl = __MODULE__."/my_survey/checksurveyresult/project_id/$project_id/survey_id/$survey_id/typeid/0";
	    		header("Location:".$seeUrl);
	    		exit;
	    	}
    	}else{
    		$research_id = I('get.research_id');
    		$condition['survey_id'] = array("eq",$survey_id);
    		$condition['research_id'] = array("eq",$research_id);
    		$condition['user_id'] = array("eq",$_SESSION['user']['id']);
    		$hasJoin = M('research_answer')->field("survey_id")->where($condition)->find();
    		if($hasJoin){
    			$seeUrl = __MODULE__."/my_survey/checksurveyresult/research_id/$research_id/survey_id/$survey_id/typeid/1";
    			header("Location:".$seeUrl);
    			exit;
    		}
    	}
    	
        $data = D('MySurvey')->getSurveyInfo();
        $this->assign($data);
        $this->display();
    }
    
    /*
     * 处理参加调研提交的结果
     */
    public function handelSurvey(){
       
            if(IS_POST){

                $ret = D('MySurvey')->handelJoinSurveyResult();

                if($ret['state']){

                    if($ret['typeid'] == 0){
                        $url = U('/admin/my_survey/waitsurvey/typeid/0');
                    }else{
                        $url = U('/admin/my_survey/researchlist/typeid/1');
                    }

                    $this->success('提交成功',$url);

                }else{

                    $this->error('不能重复提交');

                }
            }
    }
    
    /*
     * 
     * 查看调研结果
     */
    public function checkSurveyResult(){
    	$survey_id = I("get.survey_id");
    	$survey_id = (int)$survey_id;
    	if($survey_id <= 0){
    		echo "数据错误，非法操作";
    		exit;
    	}
        $items = D('MySurvey')->getSurveyResult();
        $this->assign($items);
        $this->display();
    }

    /**
     * 删除调研
     */
    public function delsurvey(){

        $results = D('MySurvey')->delsurvey();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');
    }
    
}
