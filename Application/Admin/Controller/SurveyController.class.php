<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 主持调研控制器
 * @Andy
 */
class SurveyController extends AdminBaseController{
	
	/***
	显示主持调研列表
	*/
	public function index(){

		$total_page = $this->total_page;
		//根据当前登录用户身份获取指定负责人所主持的调研
		$approved_data = D('Survey')->getProjectSurvey($total_page);
		$this->assign('approved_list',$approved_data['list']);
		$this->assign('approved_page',$approved_data['page']);
		$this->assign('keyword',$approved_data['keyword']);
		$this->display();
	}
		
		
		/* $projectsurveys=D("ProjectSurvey")->getAll();
		//判断培训项目是否通过审核
		for($i=0;$i<count($projectsurveys);$i++){
			$res=D("AdminProject")->isCheck($projectsurveys[$i]["project_id"]);
			if(!$res){
				unset($projectsurveys[$i]);
			}else{
				$projectsurveys[$i]["project_name"]=$res["project_name"];
			}
		}
		$Survey=D("Survey");
		$user_id=$_SESSION["user"]["user_id"];
		$map=array();
		static $e=0;
		for($b=0;$b<count($projectsurveys);$b++){
			$surveyId=explode(",",$projectsurveys[$b]["survey_id"]);
			$specificInformations=json_decode($projectsurveys[$b]["specific_information"],true);
			for($a=0;$a<count($surveyId);$a++){
				$specificInformation=$specificInformations[$surveyId[$a]];
				//获取指定负责人名下的考试
				if($specificInformation["user_id"]==$user_id){
					$map[$e]["project_id"]=$projectsurveys[$b]["project_id"];
					$map[$e]["project_name"]=$projectsurveys[$b]["project_name"];
					$map[$e]["survey_id"]=$surveyId[$a];
					$map[$e]["specific_information"]=$specificInformation;
					$survey=$Survey->getOne($map[$e]["survey_id"]);
					$map[$e]["specific_information"]["survey_mode"]=$survey["survey_mode"];
					$map[$e]["specific_information"]["cat_name"]=$survey["cat_name"];
					//计算考试时长
					$map[$e]["specific_information"]["tokinaga"]=floor(strtotime($map[$e]["specific_information"]["start_time"])-strtotime($map[$e]["specific_information"]["end_time"])%86400/60);
					//判断考试状态
					$g=strtotime($map[$e]["specific_information"]["start_time"])-time();
					$h=strtotime($map[$e]["specific_information"]["end_time"])-time();
					if($g<0&&$h>0){
						$map[$e]["specific_information"]["survey_status"]="进行中";
					}elseif($h<0){
						$map[$e]["specific_information"]["survey_status"]="已完成";
					}elseif($g>0){
						$map[$e]["specific_information"]["survey_status"]="未开始";
					}
			}
				$e++;
			}
		}
		$count=count($map);
		
		$Page=new \Think\Page($count,10);
		$Page->setConfig('header','共 %TOTAL_ROW% 条记录');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$show=$Page->show();
		$map=array_slice($map,$Page->firstRow,$Page->listRows);
		$this->assign("show",$show);
		$this->assign("map",$map);
		$this->display(); */
	

 	/***
	搜索主持考试列表
	*/
	/*public function search(){
		$condition=I("get.survey_name")?I("get.survey_name"):"";
		$projectsurveys=D("ProjectSurvey")->getAll();
		//判断培训项目是否通过审核
		for($i=0;$i<count($projectsurveys);$i++){
			$res=D("AdminProject")->isCheck($projectsurveys[$i]["project_id"]);
			if(!$res){
				unset($projectsurveys[$i]);
			}else{
				$projectsurveys[$i]["project_name"]=$res["project_name"];
			}
		}
		$Survey=D("Survey");
		$user_id=$_SESSION["user"]["user_id"];
		$map=array();
		static $e=0;
		for($b=0;$b<count($projectsurveys);$b++){
			$surveyId=explode(",",$projectsurveys[$b]["survey_id"]);
			$specificInformations=json_decode($projectsurveys[$b]["specific_information"],true);
			for($a=0;$a<count($surveyId);$a++){
				$specificInformation=$specificInformations[$surveyId[$a]];
				//获取指定负责人名下的考试
				if($specificInformation["user_id"]==$user_id){
					$map[$e]["project_id"]=$projectsurveys[$b]["project_id"];
					$map[$e]["project_name"]=$projectsurveys[$b]["project_name"];
					$map[$e]["survey_id"]=$surveyId[$a];
					$map[$e]["specific_information"]=$specificInformation;
					$survey=$Survey->getOne($map[$e]["survey_id"]);
					$map[$e]["specific_information"]["survey_mode"]=$survey["survey_mode"];
					//计算考试时长
					$map[$e]["specific_information"]["tokinaga"]=floor(strtotime($map[$e]["specific_information"]["start_time"])-strtotime($map[$e]["specific_information"]["end_time"])%86400/60);
					//判断考试状态
					$g=strtotime($map[$e]["specific_information"]["start_time"])-time();
					$h=strtotime($map[$e]["specific_information"]["end_time"])-time();
					if($g<0&&$h>0){
						$map[$e]["specific_information"]["survey_status"]="进行中";
					}elseif($h<0){
						$map[$e]["specific_information"]["survey_status"]="已完成";
					}elseif($g>0){
						$map[$e]["specific_information"]["survey_status"]="未开始";
					}
			}
				$e++;
			}
		}
		$maps=array();
		if($condition!=""||isset($_SESSION["my_survey"])){
			$_SESSION["my_survey"]=$condition;
			for($f=0;$f<count($map);$f++){
				$bool=strpos($map[$f]["specific_information"]["survey_name"],$_SESSION["my_survey"]);
				if(md5($bool)=="cfcd208495d565ef66e7dff9f98764da"){
					$bool=6;
				}
				if($bool==false){
					unset($map[$f]);
				}else{
					$maps[]=$map[$f];
				}
			}
		}

		$count=count($maps);
		$Page=new \Think\Page($count,10);
		$Page->setConfig('header','共 %TOTAL_ROW% 条记录');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$show=$Page->show();
		$maps=array_slice($maps,$Page->firstRow,$Page->listRows);
		$this->assign("show",$show);
		$this->assign("map",$maps);
		$this->display("index");
	} */
	
	
	/*
	 * 预览调研
	 */
    public function previewSurvey(){
        $survey = D('Survey');
        $data = $survey->getPreviewSurveyInfo();
        $this->assign($data);
		$this->display('looksurvey');
    }

    /**
     * 查看调研结果
	*/
	public function lookSurveyResult(){
        $survey = D('Survey');
        $data = $survey->getSurveyResultInfo();
        $this->assign($data);
        $this->display('lookresult');
	}

	/**
	**查看调研结果,old
	**/
	public function lookResult(){
		$data=I("get.");
		$assign=D("SurveyItem")->getItems($data["survey_id"]);
		
		for($a=0;$a<count($assign['surveyItems']);$a++){

			if($assign['surveyItems'][$a]["classification"]!=3){
				$surveyAnswers=D("SurveyAnswer")->statistics($data["project_id"],$data["survey_id"],$assign['surveyItems'][$a]['id']);
				static $map=array();
				foreach($surveyAnswers as $k=>$v){
					$answers=explode(",",$v["survey_answer"]);
					for($i=0;$i<count($answers);$i++){
						$map[$answers[$i]]++;
					}
				}
				$assign['surveyItems'][$a]["count"]=count($surveyAnswers);//总票数
				$assign['surveyItems'][$a]["res"]=$map;
			}else{
				$surveyAnswers=D("SurveyAnswer")->statistics($data["project_id"],$data["survey_id"],$assign['surveyItems'][$a]['id']);
				for($e=0;$e<count($surveyAnswers);$e++){
					$assign['surveyItems'][$a]["answer"][$e]=$surveyAnswers[$e]["survey_answer"];
				}
			}

		}
		
		$this->assign("assign",$assign);
		$this->assign('data',$data);
		$this->display("lookresult");
		
	}


}
